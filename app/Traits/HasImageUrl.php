<?php

namespace App\Traits;

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

    public function deleteLocalImage(): void
    {
        $path = $this->{$this->imagePathField()} ?? null;

        if ($path && \Storage::disk('public')->exists($path)) {
            \Storage::disk('public')->delete($path);
        }
    }
}