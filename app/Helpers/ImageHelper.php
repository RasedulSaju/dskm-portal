<?php
// app/Helpers/ImageHelper.php

namespace App\Helpers;

class ImageHelper
{
    // Standard sizes for each image type
    const SIZES = [
        'avatar'    => ['width' => 300,  'height' => 300,  'crop' => true],
        'cover'     => ['width' => 1200, 'height' => 400,  'crop' => true],
        'event'     => ['width' => 1200, 'height' => 600,  'crop' => true],
        'gallery'   => ['width' => 1200, 'height' => 900,  'crop' => false],
        'memorial'  => ['width' => 400,  'height' => 400,  'crop' => true],
        'smoronika' => ['width' => 1200, 'height' => 600,  'crop' => false],
        'notice'    => ['width' => 1200, 'height' => 600,  'crop' => false],
        'general'   => ['width' => 1200, 'height' => 900,  'crop' => false],
    ];

    const MAX_FILE_SIZE = 5242880; // 5MB
    const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    const UPLOAD_BASE    = 'storage/uploads/';

    /**
     * Process, resize, convert to WebP and save uploaded image
     * Deletes old image if provided
     *
     * @param array  $file       $_FILES['fieldname']
     * @param string $type       avatar|cover|event|gallery|memorial|smoronika|notice|general
     * @param string|null $oldFile  Existing file path to delete after upload
     * @return string|false      Relative path on success, false on failure
     */
    public static function upload(array $file, string $type = 'general', ?string $oldFile = null)
    {
        // Validate
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        if ($file['size'] > self::MAX_FILE_SIZE) {
            return false;
        }

        // Verify real image using getimagesize
        $imageInfo = @getimagesize($file['tmp_name']);
        if (!$imageInfo) {
            return false;
        }

        if (!in_array($imageInfo['mime'], self::ALLOWED_TYPES)) {
            return false;
        }

        // Create source image from uploaded file
        $source = self::createFromFile($file['tmp_name'], $imageInfo['mime']);
        if (!$source) {
            return false;
        }

        // Get dimensions
        $origWidth  = imagesx($source);
        $origHeight = imagesy($source);

        // Get target size
        $size = self::SIZES[$type] ?? self::SIZES['general'];

        // Resize/crop
        $processed = self::resize($source, $origWidth, $origHeight, $size['width'], $size['height'], $size['crop']);
        imagedestroy($source);

        if (!$processed) {
            return false;
        }

        // Save as WebP
        $subDir = self::UPLOAD_BASE . $type . 's/';
        $fullDir = dirname(__DIR__, 2) . '/' . $subDir;

        if (!is_dir($fullDir)) {
            mkdir($fullDir, 0775, true);
        }

        $filename  = uniqid($type . '_', true) . '.webp';
        $savePath  = $fullDir . $filename;
        $publicPath = $subDir . $filename;

        // Check if WebP is supported
        if (function_exists('imagewebp')) {
            $saved = imagewebp($processed, $savePath, 85); // 85% quality
        } else {
            // Fallback to JPEG
            $filename  = uniqid($type . '_', true) . '.jpg';
            $savePath  = $fullDir . $filename;
            $publicPath = $subDir . $filename;
            $saved = imagejpeg($processed, $savePath, 85);
        }

        imagedestroy($processed);

        if (!$saved) {
            return false;
        }

        // Delete old file if exists
        if ($oldFile) {
            self::delete($oldFile);
        }

        return $publicPath;
    }

    /**
     * Delete an image file from storage
     */
    public static function delete(?string $filePath): bool
    {
        if (!$filePath) {
            return false;
        }

        $fullPath = dirname(__DIR__, 2) . '/' . $filePath;

        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }

        return false;
    }

    /**
     * Create GD image resource from file
     */
    private static function createFromFile(string $path, string $mime)
    {
        switch ($mime) {
            case 'image/jpeg':
                return @imagecreatefromjpeg($path);
            case 'image/png':
                return @imagecreatefrompng($path);
            case 'image/gif':
                return @imagecreatefromgif($path);
            case 'image/webp':
                return @imagecreatefromwebp($path);
            default:
                return false;
        }
    }

    /**
     * Resize image - crop to exact dimensions or scale to fit
     */
    private static function resize($source, int $origW, int $origH, int $targetW, int $targetH, bool $crop)
    {
        // Don't upscale - only downscale
        if ($origW <= $targetW && $origH <= $targetH) {
            $canvas = imagecreatetruecolor($origW, $origH);
            self::preserveTransparency($canvas);
            imagecopy($canvas, $source, 0, 0, 0, 0, $origW, $origH);
            return $canvas;
        }

        if ($crop) {
            return self::cropResize($source, $origW, $origH, $targetW, $targetH);
        } else {
            return self::scaleResize($source, $origW, $origH, $targetW, $targetH);
        }
    }

    /**
     * Crop and resize to exact dimensions (center crop)
     */
    private static function cropResize($source, int $origW, int $origH, int $targetW, int $targetH)
    {
        $origRatio  = $origW / $origH;
        $targetRatio = $targetW / $targetH;

        if ($origRatio > $targetRatio) {
            // Crop width
            $newH = $origH;
            $newW = (int)($origH * $targetRatio);
        } else {
            // Crop height
            $newW = $origW;
            $newH = (int)($origW / $targetRatio);
        }

        $cropX = (int)(($origW - $newW) / 2);
        $cropY = (int)(($origH - $newH) / 2);

        $canvas = imagecreatetruecolor($targetW, $targetH);
        self::preserveTransparency($canvas);

        imagecopyresampled($canvas, $source, 0, 0, $cropX, $cropY, $targetW, $targetH, $newW, $newH);

        return $canvas;
    }

    /**
     * Scale to fit within dimensions (maintain aspect ratio)
     */
    private static function scaleResize($source, int $origW, int $origH, int $targetW, int $targetH)
    {
        $ratio  = min($targetW / $origW, $targetH / $origH);
        $newW   = (int)($origW * $ratio);
        $newH   = (int)($origH * $ratio);

        $canvas = imagecreatetruecolor($newW, $newH);
        self::preserveTransparency($canvas);

        imagecopyresampled($canvas, $source, 0, 0, 0, 0, $newW, $newH, $origW, $origH);

        return $canvas;
    }

    /**
     * Preserve transparency for PNG/GIF
     */
    private static function preserveTransparency($canvas): void
    {
        imagealphablending($canvas, false);
        imagesavealpha($canvas, true);
        $transparent = imagecolorallocatealpha($canvas, 255, 255, 255, 127);
        imagefilledrectangle($canvas, 0, 0, imagesx($canvas), imagesy($canvas), $transparent);
    }
}
