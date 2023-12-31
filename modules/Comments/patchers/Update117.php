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

use App\Support\ToModuleMigrator;
use Modules\Core\Updater\UpdatePatcher;

return new class extends UpdatePatcher
{
    public function run(): void
    {
        ToModuleMigrator::make('comments')
            ->migrateMorphs('App\\Models\\Comment', 'Modules\\Comments\\Models\\Comment')
            ->migrateLanguageFiles(['comment.php'])
            ->deleteConflictedFiles([app_path('Models/Comment.php')]);
    }

    public function shouldRun(): bool
    {
        return file_exists(app_path('Models/Comment.php'));
    }
};
