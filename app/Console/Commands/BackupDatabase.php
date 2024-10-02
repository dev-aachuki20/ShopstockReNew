<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class BackupDatabase extends Command
{    
    protected $signature = 'backup:database';
    protected $description = 'Backup the database';
    protected $backupFilePath;
   
    public function handle()
    {
        $backupPath = storage_path('app/db_backups/');
        // Create the backup directory if it doesn't exist
        if (!File::exists($backupPath)) {
            File::makeDirectory($backupPath, 0755, true);
        }

        $fileName = 'backup_db' . now()->format('d_M_Y_H_i_s') . '.sql';
        $filePath = $backupPath . $fileName;
        $this->backupFilePath = $backupPath . $fileName;
        $host = env('DB_HOST', '127.0.0.1');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $database = env('DB_DATABASE');
        try {
            putenv('PATH=' . getenv('PATH') . env('DB_MYSLDUMP_PATH'));
            // for complete database backup
            // $command = 'mysqldump --user=' . $username . ' --password=' . $password . ' --host=' . $host . ' --databases ' . $database . ' > ' . $backupPath . $fileName;
            $deleteTables= config('db_backup_tables.delete_tables');
            // command for taking backup of specific tables only
           // $command = 'mysqldump --user=' . $username . ' --password=' . $password . ' --host=' . $host . ' ' . $database . ' ' . implode(' ', $deleteTables) . ' > ' . $backupPath . $fileName;
           $command = 'mysqldump -u '.$username.' --password='.$password.' -h '.$host.' '.$database.' --no-tablespaces '.implode(' ', $deleteTables).' > '.$backupPath.$fileName;
            $returnVar = null;
            $output = null;
            exec($command, $output, $returnVar);

            // Upload backup to Google Drive
            $this->uploadToGoogleDrive($filePath, $fileName);
            // Delete the local backup file after uploading
            // File::delete($filePath);

            if ($returnVar !== 0) {
                throw new \Exception('mysqldump command failed: ' . implode(PHP_EOL, $output));
            }
            // Cache the backup file path
            Cache::put('database_backup_filepath', $this->backupFilePath, now()->addMinutes(5));
            $this->info('Database backup completed successfully.');
            $this->info('Backup file created at: ' . $filePath);
        } catch (\Exception $e) {
            //dd($e->getMessage());
            $this->error('Database backup failed. Error: ' . $e->getMessage());
            // Log the exception for debugging
            Log::info($e->getMessage());
            //\Log::error($e->getMessage());
        }
    }

    private function uploadToGoogleDrive($filePath, $fileName)
    {
        try {
            // Get the access token using refresh token
            $accessToken = $this->getAccessToken();

            // Prepare the file for upload
            $fileData = file_get_contents($filePath);
            $boundary = uniqid();
            $delimiter = "--" . $boundary;

            $metadata = json_encode([
                'name' => $fileName,
                'parents' => [env('GOOGLE_DRIVE_FOLDER_ID')], // Optional: Folder ID
            ]);

            $postBody = implode("\r\n", [
                $delimiter,
                'Content-Type: application/json; charset=UTF-8',
                '',
                $metadata,
                $delimiter,
                'Content-Type: application/octet-stream',
                '',
                $fileData,
                $delimiter . "--",
            ]);

            // Set up cURL request to upload the file
            $uploadUrl = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $uploadUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer $accessToken",
                "Content-Type: multipart/related; boundary=$boundary",
            ]);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postBody);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $uploadResponse = curl_exec($ch);
            curl_close($ch);

            $this->info('Backup uploaded to Google Drive successfully: ' . $fileName);
        } catch (\Exception $e) {
            $this->error('Failed to upload backup to Google Drive: ' . $e->getMessage());
            Log::error('Google Drive upload error: ' . $e->getMessage());
        }
    }

    private function getAccessToken()
    {
        // Use refresh token to get a new access token
        $tokenUrl = 'https://oauth2.googleapis.com/token';
        $postFields = [
            'client_id' => env('GOOGLE_DRIVE_CLIENT_ID'),
            'client_secret' => env('GOOGLE_DRIVE_CLIENT_SECRET'),
            'refresh_token' => env('GOOGLE_DRIVE_REFRESH_TOKEN'),
            'grant_type' => 'refresh_token',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postFields));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $tokenData = json_decode($response, true);
        return $tokenData['access_token'];
    }
}
