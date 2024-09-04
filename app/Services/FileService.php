<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    /**
     * Dosyayı belirtilen dizine kaydeder.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  string  $directory
     * @return string
     */
    public function storeImage(UploadedFile $file, string $directory): string
    {
        // Dosyayı belirtilen dizine kaydet
        $path = $file->store("public/images/{$directory}");

        // Dosyanın yolunu döndür (public/ kısmı eklenmeyecek)
        return str_replace('public/', '', $path);
    }


    /**
     * Get the URL of an image from the storage.
     *
     * @param  string  $path
     * @return string
     */
    public function getImageUrl(string $path): string
    {
        // Storage::url() içinde public/ varsa kaldır
        $path = 'public/' . $path;
        return Storage::url($path);
    }


    /**
     * Delete a file from storage.
     *
     * @param  string  $path
     * @return bool
     */
    public function deleteFile(string $path): bool
    {
        // `public/` kısmını ekleyerek dosyanın var olup olmadığını kontrol et
        $fullPath = 'public/' . $path;

        if (Storage::exists($fullPath)) {
            // Dosya varsa, sil
            return Storage::delete($fullPath);
        }

        // Dosya yoksa, false döndür
        return false;
    }

}

// protected $imageService;

// public function __construct(ImageService $imageService)
// {
//     $this->imageService = $imageService;
// }
// $request->validate([
//     'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
// ]);

// $file = $request->file('image');
// $directory ="avatars";

// // Dosyayı kaydet ve URL'ini al
// $fileUrl = $this->fileService->storeImage($file, $directory);
