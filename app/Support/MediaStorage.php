<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaStorage
{
    /**
     * Resolve the storage disk to use for media uploads and URLs.
     */
    public static function diskName(): string
    {
        $default = config('filesystems.default');
        if (in_array($default, ['spaces', 's3'])) {
            return $default;
        }

        // Prefer spaces if key is configured
        if (config('filesystems.disks.spaces.key')) {
            return 'spaces';
        }

        return $default ?: 'spaces';
    }

    /**
     * Get the full public URL for a stored media path.
     */
    public static function url(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        // Direct external URL
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Local static theme asset
        if (Str::startsWith($path, 'assets/')) {
            return asset($path);
        }

        $disk = static::diskName();

        try {
            $url = Storage::disk($disk)->url($path);

            // If an absolute URL is already returned
            if (Str::startsWith($url, ['http://', 'https://'])) {
                return $url;
            }

            // If a relative URL was generated (e.g. empty DO_URL prefixing root),
            // resolve it to the full DigitalOcean Spaces URL
            $bucket = config("filesystems.disks.{$disk}.bucket");
            $region = config("filesystems.disks.{$disk}.region", 'fra1');

            if ($bucket) {
                return "https://{$bucket}.{$region}.digitaloceanspaces.com".Str::start($url, '/');
            }

            return asset($path);
        } catch (\Throwable $e) {
            Log::warning("Failed to resolve media URL for '{$path}': {$e->getMessage()}");

            return asset($path);
        }
    }

    /**
     * Upload an uploaded file to storage under a date-based subfolder.
     */
    public static function upload(UploadedFile $file, string $directory): string
    {
        $ext = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = trim($directory, '/').'/'.date('Y/m').'/'.Str::uuid().'.'.$ext;
        $disk = static::diskName();

        try {
            $stored = Storage::disk($disk)->putFileAs('', $file, $filename, ['visibility' => 'public']);

            if (! $stored) {
                Log::error("Failed to upload file to {$disk}: putFileAs returned false for {$filename}");
                throw new \RuntimeException("Failed to upload file to {$disk} storage.");
            }
        } catch (\Throwable $e) {
            Log::error("Media upload exception on disk [{$disk}]: {$e->getMessage()}", [
                'filename' => $filename,
                'exception' => $e,
            ]);

            throw $e;
        }

        return $filename;
    }

    /**
     * Delete a stored media file if it's not a remote or local asset.
     */
    public static function delete(?string $path): void
    {
        if (! $path || Str::startsWith($path, ['http://', 'https://', 'assets/'])) {
            return;
        }

        $disk = static::diskName();

        try {
            Storage::disk($disk)->delete($path);
        } catch (\Throwable $e) {
            Log::warning("Failed to delete media from [{$disk}] at '{$path}': {$e->getMessage()}");
        }
    }
}
