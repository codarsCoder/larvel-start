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
        $baseUrl = env('BACKEND_URL');
        // $baseUrl = "http://localhost:8000";

        // Eğer path boş ise boş bir string döndür
        if (!$path) {
            return '';
        }
$path = Storage::url($path);
        // Path'de zaten 'storage' dizini varsa, doğrudan birleştir
        return $baseUrl . $path ;
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

// $file = $request->file('image');
// $directory = "avatars";
// $imagePath = $this->fileService->storeImage($file, $directory);

// // Veritabanına `images/avatars/filename.jpg` şeklinde yol kaydedebilirsiniz.

// $imagePath = 'images/avatars/filename.jpg';
// $imageUrl = $this->fileService->getImageUrl($imagePath);

// // $imageUrl ile dosyanın URL'ini alabilirsiniz.
// $imagePath = 'images/avatars/filename.jpg';
// $success = $this->fileService->deleteFile($imagePath);

// // $success true/false olarak dosyanın silinip silinmediğini belirtir.
