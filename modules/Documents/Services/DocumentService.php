<?php
/**
 * Concord CRM - https://www.concordcrm.com
 *
 * @version   1.2.1
 *
 * @link      Releases - https://www.concordcrm.com/releases
 * @link      Terms Of Service - https://www.concordcrm.com/terms
 *
 * @copyright Copyright (c) 2022-2023 KONKORD DIGITAL
 */

namespace Modules\Documents\Services;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Modules\Billable\Services\BillableService;
use Modules\Core\Contracts\Services\CreateService;
use Modules\Core\Contracts\Services\Service;
use Modules\Core\Contracts\Services\UpdateService;
use Modules\Core\Models\Model;
use Modules\Documents\Enums\DocumentStatus;
use Modules\Documents\Models\Document;
use Modules\Documents\Models\DocumentType;

class DocumentService implements Service, CreateService, UpdateService
{
    /**
     * Create new document in storage.
     */
    public function create(array $attributes): Document
    {
        $signers = $attributes['signers'] ?? [];

        $attributes['data'] = collect($this->extraDataAttributes())->mapWithKeys(
            fn ($attribute) => [$attribute => $attributes[$attribute] ?? null]
        )->all();

        // API usage
        if (! ($attributes['document_type_id'] ?? null)) {
            $attributes['document_type_id'] = DocumentType::getDefaultType();
        }

        // API usage when requires_signature is not provided
        if (! ($attributes['requires_signature'] ?? null)) {
            $attributes['requires_signature'] = count($signers) > 0 ? true : false;
        }

        if (! isset($attributes['locale'])) {
            $attributes['locale'] = Auth::user()->preferredLocale();
        }

        $model = new Document($attributes);
        $model->status = DocumentStatus::DRAFT;

        $model->save();

        if ($model->requires_signature === true) {
            $model->signers()->createMany($signers);
        }

        if ($billable = $attributes['billable'] ?? null) {
            (new BillableService())->save($billable, $model);
        }

        return $model;
    }

    /**
     * Update document in storage.
     */
    public function update(Model $model, array $attributes): Document
    {
        $signers = Arr::pull($attributes, 'signers');

        if ($model->status === DocumentStatus::ACCEPTED) {
            Arr::forget($attributes, ['content', 'title', 'brand_id', 'requires_signature', 'billable']);
        }

        $data = $model->data ?? [];

        foreach ($this->extraDataAttributes() as $key) {
            if (array_key_exists($key, $attributes)) {
                $data[$key] = $attributes[$key];
            }
        }

        if (($attributes['send'] ?? null) || $model->isDirty('send_at') && $model->send_at) {
            $data['send_initiated_by'] = Auth::id();
        }

        $model->fill([...$attributes, ...['data' => $data]])->save();

        if ($model->requires_signature === false) {
            $model->signers()->delete();
        } elseif (is_array($signers)) {
            $this->updateSigners($signers, $model);
        }

        if ($billable = $attributes['billable'] ?? null) {
            (new BillableService())->save($billable, $model);
        }

        if ($attributes['send'] ?? false) {
            (new DocumentSendService())->send($model);
        }

        return $model;
    }

    /**
     * Save/update the given signers for the given document
     */
    protected function updateSigners(array $signers, Document $document): void
    {
        $signers = collect($signers);
        $altered = false;

        $deleted = $document->signers->pluck('email')->diff($signers->pluck('email'));
        $currentTotal = $document->signers->count();

        if ($currentTotal > 0) {
            if ($deleted->count() === $currentTotal) {
                $altered = (bool) $document->signers()->delete();
            } elseif ($deleted->isNotEmpty()) {
                $altered = (bool) $document->signers()->whereIn('email', $deleted)->delete();
            }

            $signers->each(function ($signer) use ($document) {
                $this->updateOrCreateSigner($document, $signer['email'], $signer);
            });
        } elseif ($document->status !== DocumentStatus::ACCEPTED) {
            $signers->each(function ($signer) use ($document) {
                $document->signers()->create($signer);
            });
        }

        if ($altered && $document->status === DocumentStatus::ACCEPTED) {
            // TODO Log activity?
            $document->forceFill([
                'status' => DocumentStatus::DRAFT,
                'accepted_at' => null,
                'marked_accepted_by' => null,
            ])->save();
        }
    }

    /**
     * Update or create document signer.
     */
    protected function updateOrCreateSigner(Document $document, string $email, array $data): void
    {
        $signer = $document->signers()->where('email', $email)->first();

        if ($signer) {
            if ($document->status !== DocumentStatus::ACCEPTED) {
                $signer->fill($data)->save();
            } else {
                $signer->fill(['send_email' => $data['send_email'] ?? false])->save();
            }

            return;
        }

        if ($document->status !== DocumentStatus::ACCEPTED) {
            $document->signers()->create($data);
        }
    }

    /**
     * Get the model extra data attributes
     */
    protected function extraDataAttributes(): array
    {
        return ['send_mail_account_id', 'send_mail_subject', 'send_mail_body', 'recipients', 'pdf'];
    }
}
