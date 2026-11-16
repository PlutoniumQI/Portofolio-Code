<?php

namespace App\Filament\Support;

use App\Support\Media;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;

class PortfolioUpload
{
    /**
     * @return array<int, string>
     */
    public const IMAGE_TYPES = [
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/gif',
        'image/avif',
    ];

    public static function disk(): string
    {
        return Media::defaultDisk();
    }

    public static function diskField(string $name = 'image_disk'): Hidden
    {
        return Hidden::make($name)->default(fn (): string => self::disk());
    }

    public static function image(string $name, string $directory, string $label = 'Image'): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->image()
            ->acceptedFileTypes(self::IMAGE_TYPES)
            ->disk(self::disk())
            ->directory($directory)
            ->visibility('public')
            ->maxSize(config('portfolio.max_upload_kb'))
            ->helperText(self::disk() === 'github'
                ? 'Uploaded to the GitHub assets repository.'
                : 'Stored on the local public disk.');
    }

    public static function gallery(string $name, string $directory, string $label = 'Gallery'): FileUpload
    {
        return self::image($name, $directory, $label)
            ->multiple()
            ->reorderable()
            ->appendFiles()
            ->panelLayout('grid');
    }

    public static function document(string $name, string $directory, string $label = 'File'): FileUpload
    {
        return FileUpload::make($name)
            ->label($label)
            ->acceptedFileTypes(['application/pdf'])
            ->disk(self::disk())
            ->directory($directory)
            ->visibility('public')
            ->maxSize(config('portfolio.max_upload_kb'))
            ->helperText('PDF only. Uploaded to the GitHub assets repository in production.');
    }
}
