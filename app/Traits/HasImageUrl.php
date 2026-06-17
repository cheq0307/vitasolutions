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
 * Uso en Product: use HasImageUrl; (usa los valores por defecto image_path / image_url)
 * Uso en Center:  use HasImageUrl; y override imagePathField() / imageUrlField()
 */
trait HasImageUrl
{
    protected function imagePathField(): string
    {
        return 'image_path';
    }

    protected function imageUrlField(): string
    {
        return 'image_url';
    }

    /**
     * Devuelve la URL final de la imagen lista para usar en <img src="">.
     * Prioridad: archivo local > URL externa > null
     */
    public function getResolvedImageUrl(): ?string
    {
        $path = $this->{$this->imagePathField()} ?? null;
        $url  = $this->{$this->imageUrlField()}  ?? null;

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
        $path = $this->{$this->imagePathField()} ?? null;

        if ($path && \Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
        }
    }
}
