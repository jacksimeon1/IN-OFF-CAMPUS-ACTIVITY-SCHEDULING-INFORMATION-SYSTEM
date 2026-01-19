<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FixStorageLink extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:fix-link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix the storage link for file access';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $publicStoragePath = public_path('storage');
        $storageAppPublicPath = storage_path('app/public');

        // Remove existing link if it exists
        if (File::exists($publicStoragePath)) {
            if (is_link($publicStoragePath)) {
                unlink($publicStoragePath);
                $this->info('Removed existing storage link.');
            } else {
                File::deleteDirectory($publicStoragePath);
                $this->info('Removed existing storage directory.');
            }
        }

        // Create the symbolic link
        if (PHP_OS_FAMILY === 'Windows') {
            // For Windows, create a junction
            $command = 'mklink /J "' . $publicStoragePath . '" "' . $storageAppPublicPath . '"';
            exec($command, $output, $returnCode);
            
            if ($returnCode === 0) {
                $this->info('Storage link created successfully on Windows.');
            } else {
                // Fallback: copy files instead of symlink
                File::copyDirectory($storageAppPublicPath, $publicStoragePath);
                $this->info('Storage files copied (fallback method).');
            }
        } else {
            // For Unix-like systems
            symlink($storageAppPublicPath, $publicStoragePath);
            $this->info('Storage link created successfully.');
        }

        return 0;
    }
}
