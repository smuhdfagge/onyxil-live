<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * Saves uploads straight into public/uploads so the site works on shared
 * hosting without a storage symlink. Large photos are scaled down and
 * converted to WebP when the server's GD library supports it.
 */
class ImageUpload
{
    public static function store(UploadedFile $file, string $folder, int $maxWidth = 1920): string
    {
        $dir = public_path('uploads/'.$folder.'/'.date('Y/m'));
        File::ensureDirectoryExists($dir);
        $name = Str::random(20);
        $mime = $file->getMimeType();

        $loaders = [
            'image/jpeg' => 'imagecreatefromjpeg',
            'image/png' => 'imagecreatefrompng',
            'image/webp' => 'imagecreatefromwebp',
            'image/gif' => 'imagecreatefromgif',
        ];

        if (isset($loaders[$mime]) && function_exists($loaders[$mime]) && function_exists('imagewebp')) {
            $src = @$loaders[$mime]($file->getRealPath());
            if ($src) {
                $w = imagesx($src);
                $h = imagesy($src);
                if ($w > $maxWidth) {
                    $nh = (int) round($h * $maxWidth / $w);
                    $dst = imagecreatetruecolor($maxWidth, $nh);
                    imagealphablending($dst, false);
                    imagesavealpha($dst, true);
                    imagecopyresampled($dst, $src, 0, 0, 0, 0, $maxWidth, $nh, $w, $h);
                    imagedestroy($src);
                    $src = $dst;
                } else {
                    imagepalettetotruecolor($src);
                    imagealphablending($src, false);
                    imagesavealpha($src, true);
                }
                imagewebp($src, $dir.'/'.$name.'.webp', 82);
                imagedestroy($src);

                return 'uploads/'.$folder.'/'.date('Y/m').'/'.$name.'.webp';
            }
        }

        $ext = $file->guessExtension() ?: 'bin';
        $file->move($dir, $name.'.'.$ext);

        return 'uploads/'.$folder.'/'.date('Y/m').'/'.$name.'.'.$ext;
    }

    public static function delete(?string $path): void
    {
        if ($path && Str::startsWith($path, 'uploads/') && ! Str::contains($path, '..')) {
            File::delete(public_path($path));
        }
    }
}
