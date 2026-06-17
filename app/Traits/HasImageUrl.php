<?php

namespace App\Traits;

/**
 * Trait HasImageUrl
 *
 * Agrega al modelo un accessor que devuelve la URL correcta de la imagen:
 * - Si hay archivo local (image_path) → asset() con la ruta local
 * - Si hay URL externa (image_url)   → la URL tal cual
 * - Si no hay nada                   → null (las vistas muestran placeholder)
 *
 * Uso en Product:   use HasImageUrl; con $imagePathField = 'image_path', $imageUrlField = 'image_url'
 * Uso en Center:    use HasImageUrl; con $imagePathField = 'logo_path',   $imageUrlField = 'logo'
 */
trait HasImageUrl
{
    /**
     * Columna que guarda la ruta local relativa a storage/app/public/
     * Override en el modelo si el nombre es diferente.
     */
    protected string $imagePathField = 'image_path';

    /**
     * Columna que guarda la URL externa (fallback).
     * Override en el modelo si el nombre es diferente.
     */
    protected string $imageUrlField = 'image_url';

    /**
     * Devuelve la URL final de la imagen lista para usar en <img src="">.
     * Prioridad: archivo local > URL externa > null
     */
    public function getResolvedImageUrl(): ?string
    {
        $path = $this->{$this->imagePathField} ?? null;
        $url  = $this->{$this->imageUrlField}  ?? null;

        if ($path) {
            return asset('storage/' . $path);
        }

        if ($url && filter_var($url, FILTER_VALIDATE_URL)) {
            return $url;
        }

        return null;
    }

    /**
     * Elimina el archivo local anterior del storage cuando se reemplaza.
     */
    public function deleteLocalImage(): void
    {
        $path = $this->{$this->imagePathField} ?? null;

        if ($path && \Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
        }
    }
}