// app/Support/MediaPathGenerator.php

namespace App\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

class MediaPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        if ($this->isProduct($media)) {
            return 'products/';
        }

        return $media->id . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        if ($this->isProduct($media)) {
            return 'products/conversions/';
        }

        return $media->id . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        if ($this->isProduct($media)) {
            return 'products/responsive/';
        }

        return $media->id . '/responsive/';
    }

    private function isProduct(Media $media): bool
    {
        return $media->model_type === 'App\\Models\\Product';
    }
}