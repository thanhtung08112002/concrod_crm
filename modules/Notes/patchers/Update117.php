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
        ToModuleMigrator::make('notes')
            ->migrateMorphs('App\\Models\\Note', 'Modules\\Notes\\Models\\Note')
            ->migrateDbLanguageKeys('note')
            ->migrateLanguageFiles(['note.php'])
            ->deleteConflictedFiles($this->getConflictedFiles());
    }

    public function shouldRun(): bool
    {
        return file_exists(app_path('Models/Note.php'));
    }

    protected function getConflictedFiles(): array
    {
        return [
            app_path('Resources/Note'),
            app_path('Models/Note.php'),
        ];
    }
};
