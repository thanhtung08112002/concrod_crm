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

namespace Modules\Deals\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\ApiController;
use Modules\Deals\Services\PipelineService;

class PipelineDisplayOrder extends ApiController
{
    /**
     * Save the pipelines display order.
     */
    public function __invoke(Request $request, PipelineService $service): JsonResponse
    {
        $request->validate([
            'order.*.id' => 'required|int',
            'order.*.display_order' => 'required|int',
        ]);

        foreach ($request->input('order') as $pipeline) {
            $service->saveDisplayOrder($pipeline['id'], $pipeline['display_order'], $request->user()->id);
        }

        return $this->response('', 204);
    }
}
