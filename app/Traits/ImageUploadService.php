<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Guarda una imagen subida en storage/app/public/{folder}/
     * Devuelve la ruta relativa al disco public (ej: "products/abc123.webp")
     *
     * @param  UploadedFile $file
     * @param  string       $folder  'products' | 'centers'
     * @return string               Ruta relativa para guardar en BD
     */
    public function store(UploadedFile $file, string $folder = 'products'): string
    {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($folder, $filename, 'public');
        return $path; // ej: "products/uuid.jpg"
    }

    /**
     * Elimina una imagen del storage dado su path relativo.
     */
    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Reemplaza una imagen: borra la anterior y guarda la nueva.
     * Devuelve el path de la nueva imagen.
     */
    public function replace(UploadedFile $newFile, ?string $oldPath, string $folder = 'products'): string
    {
        $this->delete($oldPath);
        return $this->store($newFile, $folder);
    }
}