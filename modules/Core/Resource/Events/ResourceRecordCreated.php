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

namespace Modules\Core\Resource\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Core\Models\Model;
use Modules\Core\Resource\Resource;

class ResourceRecordCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create new ResourceRecordCreated instance.
     */
    public function __construct(public Model $model, public Resource $resource)
    {
    }
}