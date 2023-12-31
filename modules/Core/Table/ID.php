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

namespace Modules\Core\Table;

class ID extends Column
{
    /**
     * Initialize new ID instance.
     */
    public function __construct(?string $label = null, string $attribute = 'id')
    {
        parent::__construct($attribute, $label);

        $this->minWidth('120px')->width('120px');
    }
}
