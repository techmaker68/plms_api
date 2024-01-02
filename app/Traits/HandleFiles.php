<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HandleFiles
{
    /**
     * Handle file uploads, both single and multiple, for a given attribute.
     *
     * @param mixed $value The uploaded file or array of files.
     * @param string $attribute The model attribute to set.
     * @param string $directory The directory where files should be uploaded.
     */
    protected function handleFileUpload($value, $attribute, $directory = '')
    {
        if ($value instanceof UploadedFile) {
            $oldFilePath = $this->getOriginal($attribute);
            $this->deleteIfExists($oldFilePath);

            $path = $this->upload($value, $directory);
            $this->attributes[$attribute] = $path;
        } elseif (is_array($value)) {
            $filePaths = [];
            foreach ($value as $file) {
                if ($file instanceof UploadedFile) {
                    $path = $this->upload($file, $directory);
                    if ($path) {
                        $filePaths[] = $path;
                    }
                }
            }

            $existing = $this->getOriginal($attribute);
            $existingPaths = $existing ? $existing : [];

            $existingPaths = array_filter($existingPaths, function ($path) {
                return !empty($path);
            });

            $allPaths = array_merge($existingPaths, $filePaths);
            $this->attributes[$attribute] = implode(',', $allPaths);
        } else {
            $this->attributes[$attribute] = $value;
        }
    }

    protected function upload(UploadedFile $file, string $directory): ?string
    {
        $fileName = $this->generateFileName($file);
        $path = $file->storeAs($directory, $fileName, 'public_media');
        return $path ? $path : null;
    }

    protected function generateFileName(UploadedFile $file): string
    {
        return uniqid() . '-' . time() . '.' . $file->getClientOriginalExtension();
    }

    protected function deleteIfExists(?string $filePath): void
    {
        if ($filePath && Storage::disk('public_media')->exists($filePath)) {
            Storage::disk('public_media')->delete($filePath);
        }
    }

    protected function unlinkFiles($filePaths)
    {
        if (is_array($filePaths)) {
            foreach ($filePaths as $path) {
                $this->deleteSingleFile($path);
            }
        } else {
            $this->deleteSingleFile($filePaths);
        }
    }

    private function deleteSingleFile($filePath)
    {
        if ($filePath) {
            $fullPath = 'media/' . $filePath;
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }
}
