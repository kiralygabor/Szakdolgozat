<?php

namespace App\Enums;

enum AttachmentType: string
{
    case Image = 'image';
    case File = 'file';

    private const IMAGE_EXTENSIONS = ['jpg', 'jpeg', 'png', 'gif'];

    public static function fromExtension(string $extension): self
    {
        return in_array(strtolower($extension), self::IMAGE_EXTENSIONS)
            ? self::Image
            : self::File;
    }
}
