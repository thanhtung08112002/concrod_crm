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

namespace Modules\Core\Http\Controllers\Api\Resource;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Facades\Innoclapps;
use Modules\Core\Http\Controllers\ApiController;
use Modules\Core\Resource\Placeholders;

class PlaceholdersController extends ApiController
{
    /**
     * Retrieve placeholders via fields.
     */
    public function index(Request $request): JsonResponse
    {
        return $this->response(Placeholders::createGroupsFromResources(
            $request->input('resources', [])
        ));
    }

    /**
     * Parse placeholders via input fields.
     */
    public function parseViaInputFields(Request $request): JsonResponse
    {
        $content = $request->content;

        $this->placeholders($request->input('resources', []), $request)->each(function ($data) use (&$content) {
            $content = $data['placeholders']->parseWhenViaInputFields($content);
        });

        return $this->response($content);
    }

    /**
     * Parse placeholders via interpolation.
     */
    public function parseViaInterpolation(Request $request): JsonResponse
    {
        $content = $request->content;

        $this->placeholders($request->input('resources', []), $request)->each(function ($data) use (&$content) {
            $content = $data['placeholders']->parseViaInterpolation($content);
        });

        return $this->response($content);
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    protected function placeholders(array $resources, Request $request)
    {
        return collect($resources)->map(function (array $resource) {
            $instance = Innoclapps::resourceByName($resource['name']);

            return $instance ? [
                'record' => $record = $instance->displayQuery()->find($resource['id']),
                'resource' => $instance,
                'placeholders' => new Placeholders($instance, $record),
            ] : null;
        })
            ->filter()
            ->reject(fn ($data) => $request->user()->cant('view', $data['record']))
            ->unique(fn ($data) => $data['resource']->name());
    }
}
