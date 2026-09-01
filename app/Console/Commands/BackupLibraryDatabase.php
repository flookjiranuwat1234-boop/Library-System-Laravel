<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

#[Signature('library:backup')]
#[Description('Create a compressed private backup of library data')]
class BackupLibraryDatabase extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $tables = ['users', 'categories', 'books', 'borrow_records', 'settings', 'notifications'];
        $payload = ['created_at' => now()->toIso8601String(), 'tables' => []];

        foreach ($tables as $table) {
            $payload['tables'][$table] = DB::table($table)->orderBy('id')->get()->map(fn (object $row): array => (array) $row)->all();
        }

        $filename = 'backups/library-'.now()->format('Y-m-d-His').'.json.gz';
        Storage::disk('local')->put($filename, gzencode(json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR), 9));

        $cutoff = now()->subDays((int) config('library.backup_retention_days'));
        foreach (Storage::disk('local')->files('backups') as $file) {
            if (Storage::disk('local')->lastModified($file) < $cutoff->timestamp) {
                Storage::disk('local')->delete($file);
            }
        }

        $this->info("สร้างไฟล์สำรอง {$filename} แล้ว");

        return self::SUCCESS;
    }
}
