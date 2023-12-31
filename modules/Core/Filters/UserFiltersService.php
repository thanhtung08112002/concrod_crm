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

namespace Modules\Core\Filters;

use Modules\Core\Models\Filter;

class UserFiltersService
{
    public function get(int $userId, string $identifier)
    {
        return Filter::with(['defaults' => fn ($query) => $query->where('user_id', $userId)])
            ->visibleFor($userId)
            ->ofIdentifier($identifier)
            ->orderBy('name')
            ->get();
    }
}
