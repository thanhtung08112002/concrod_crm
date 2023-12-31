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

use Illuminate\Support\Facades\Broadcast;
use Modules\Deals\Models\Deal;

Broadcast::channel('Modules.Deals.Models.Deal.{dealId}', function ($user, $dealId) {
    return $user->can('view', Deal::findOrFail($dealId));
});
