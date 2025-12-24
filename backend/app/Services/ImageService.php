<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Models\ProductImage;

class ImageService
{
    protected $maxWidth = 800;
    protected $maxHeight = 800;
    protected $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp'];

    /**
     * Сохранить изображения для продукта
     */
    public function saveProductImages(int $productId, string $article, array $files): array
    {
        $savedImages = [];
        $sortOrder = $this->getNextSortOrder($productId);

        foreach ($files as $file) {
            if (!$file instanceof UploadedFile) {
                continue;
            }

            $extension = strtolower($file->getClientOriginalExtension());
            if (!in_array($extension, $this->allowedExtensions)) {
                continue;
            }

            // Генерируем имя файла: артикул_порядковый номер
            $filename = $article . '_' . $sortOrder . '.' . $extension;
            $path = 'products/' . $filename;

            // Ресайзим и сохраняем изображение
            $resizedImage = $this->resizeImage($file, $this->maxWidth, $this->maxHeight);
            Storage::disk('public')->put($path, $resizedImage);

            // Сохраняем в базу данных
            $image = ProductImage::create([
                'product_id' => $productId,
                'image_path' => $path,
                'sort_order' => $sortOrder,
            ]);

            $savedImages[] = $image;
            $sortOrder++;
        }

        return $savedImages;
    }

    /**
     * Сохранить изображения для продукта из путей к файлам
     */
    public function saveProductImagesFromPaths(int $productId, string $article, array $filePaths): array
    {
        $savedImages = [];

        // Сортируем по индексу в имени файла
        usort($filePaths, function($a, $b) {
            $indexA = $this->getImageIndex(basename($a));
            $indexB = $this->getImageIndex(basename($b));
            return $indexA <=> $indexB;
        });

        foreach ($filePaths as $filePath) {
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            if (!in_array($extension, $this->allowedExtensions)) {
                continue;
            }

            $index = $this->getImageIndex(basename($filePath));
            // Генерируем имя файла: артикул_порядковый номер
            $filename = $article . '_' . $index . '.' . $extension;
            $path = 'products/' . $filename;

            // Ресайзим и сохраняем изображение
            $resizedImage = $this->resizeImageFromPath($filePath, $this->maxWidth, $this->maxHeight);
            Storage::disk('public')->put($path, $resizedImage);

            // Сохраняем в базу данных
            $image = ProductImage::create([
                'product_id' => $productId,
                'image_path' => $path,
                'sort_order' => $index,
            ]);

            $savedImages[] = $image;
        }

        return $savedImages;
    }

    /**
     * Получить индекс изображения из имени файла
     */
    private function getImageIndex(string $filename): int
    {
        // Предполагаем формат article_index.ext
        $parts = explode('_', pathinfo($filename, PATHINFO_FILENAME));
        $lastPart = end($parts);
        return is_numeric($lastPart) ? (int)$lastPart : 0;
    }

    /**
     * Ресайзить изображение из пути
     */
    private function resizeImageFromPath(string $filePath, int $maxWidth, int $maxHeight): string
    {
        $imageInfo = getimagesize($filePath);
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];

        // Если изображение уже меньше или равно максимальным размерам, возвращаем оригинал
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return file_get_contents($filePath);
        }

        // Вычисляем новые размеры, сохраняя пропорции
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);

        // Создаем новое изображение
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Загружаем оригинальное изображение
        $sourceImage = null;
        switch ($mime) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($filePath);
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($filePath);
                // Сохраняем прозрачность для PNG
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                break;
            case 'image/webp':
                $sourceImage = imagecreatefromwebp($filePath);
                break;
            default:
                throw new \Exception('Unsupported image type');
        }

        if (!$sourceImage) {
            throw new \Exception('Failed to create image from file');
        }

        // Ресайзим
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Сохраняем в буфер
        ob_start();
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($newImage, null, 90);
                break;
            case 'image/png':
                imagepng($newImage, null, 9);
                break;
            case 'image/webp':
                imagewebp($newImage, null, 90);
                break;
        }
        $resizedData = ob_get_clean();

        // Освобождаем память
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $resizedData;
    }

    /**
     * Обновить порядок изображений
     */
    public function updateImageOrder(int $productId, array $images): void
    {
        foreach ($images as $imageData) {
            if (isset($imageData['id']) && $imageData['id']) {
                ProductImage::where('id', $imageData['id'])
                    ->where('product_id', $productId)
                    ->update(['sort_order' => $imageData['sort_order']]);
            }
        }
    }

    /**
     * Удалить изображения
     */
    public function deleteImages(array $imageIds): void
    {
        $images = ProductImage::whereIn('id', $imageIds)->get();

        foreach ($images as $image) {
            // Удаляем файл
            Storage::disk('public')->delete($image->image_path);
            // Удаляем запись из БД
            $image->delete();
        }
    }

    /**
     * Получить следующий sort_order для продукта
     */
    protected function getNextSortOrder(int $productId): int
    {
        $maxOrder = ProductImage::where('product_id', $productId)->max('sort_order') ?? 0;
        return $maxOrder + 1;
    }

    /**
     * Ресайзить изображение
     */
    protected function resizeImage(UploadedFile $file, int $maxWidth, int $maxHeight): string
    {
        $imageInfo = getimagesize($file->getPathname());
        if (!$imageInfo) {
            throw new \Exception('Invalid image file');
        }

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];

        // Если изображение уже меньше или равно максимальным размерам, возвращаем оригинал
        if ($width <= $maxWidth && $height <= $maxHeight) {
            return file_get_contents($file->getPathname());
        }

        // Вычисляем новые размеры, сохраняя пропорции
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);

        // Создаем новое изображение
        $newImage = imagecreatetruecolor($newWidth, $newHeight);

        // Загружаем оригинальное изображение
        $sourceImage = null;
        switch ($mime) {
            case 'image/jpeg':
                $sourceImage = imagecreatefromjpeg($file->getPathname());
                break;
            case 'image/png':
                $sourceImage = imagecreatefrompng($file->getPathname());
                // Сохраняем прозрачность для PNG
                imagealphablending($newImage, false);
                imagesavealpha($newImage, true);
                break;
            case 'image/webp':
                $sourceImage = imagecreatefromwebp($file->getPathname());
                break;
            default:
                throw new \Exception('Unsupported image type');
        }

        if (!$sourceImage) {
            throw new \Exception('Failed to create image from file');
        }

        // Ресайзим
        imagecopyresampled($newImage, $sourceImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Сохраняем в буфер
        ob_start();
        switch ($mime) {
            case 'image/jpeg':
                imagejpeg($newImage, null, 90);
                break;
            case 'image/png':
                imagepng($newImage, null, 9);
                break;
            case 'image/webp':
                imagewebp($newImage, null, 90);
                break;
        }
        $resizedData = ob_get_clean();

        // Освобождаем память
        imagedestroy($sourceImage);
        imagedestroy($newImage);

        return $resizedData;
    }
}
