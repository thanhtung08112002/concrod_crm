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

namespace Modules\Core\Fields;

class Email extends Text
{
    /**
     * Input type
     */
    public string $inputType = 'email';

    /**
     * Boot the field
     *
     * @return void
     */
    public function boot()
    {
        $this->rules(['email', 'nullable'])->prependIcon('Mail')
            ->tapIndexColumn(function ($column) {
                $column->useComponent('table-data-email-column');
            })->provideSampleValueUsing(fn () => uniqid().'@example.com');
    }
}
