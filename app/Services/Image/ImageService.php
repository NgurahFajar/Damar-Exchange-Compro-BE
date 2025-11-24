<?php

namespace App\Services\Image;

use App\Exceptions\Image\ImageException;
use App\Repositories\ImageRepository;
use \Illuminate\Support\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected ImageRepository $imageRepository;

    public function __construct(ImageRepository $imageRepository)
    {
        $this->imageRepository = $imageRepository;
    }

    /**
     * Get all publicly available images
     *
     * @return Collection
     */
    public function getPublicImages()
    {
        // Using existing getUserImages method but without user ID filter
        return $this->imageRepository->getAllImages();
    }

    /**
     * Get images for a specific user
     *
     * @param int $userId
     * @return Collection
     */
    public function getUserImages(int $userId)
    {
        return $this->imageRepository->getUserImages($userId);
    }

    /**
     * Find an image by ID and user ID
     *
     * @param mixed $id
     * @param mixed $userId
     * @return mixed
     */
    public function findImage($id, $userId)
    {
        return $this->imageRepository->findByUser($id, $userId);
    }

    /**
     * Store a new image
     *
     * @param array $data
     * @return mixed
     * @throws ImageException
     */
    public function storeImage(array $data)
    {
        if (!$this->imageRepository->canAddMore($data['user_id'])) {
            throw ImageException::maxImagesReached();
        }

        // Standardize the path
        $file = $data['image'];
        $userId = $data['user_id'];
        $path = $file->store("user_images/{$userId}", 'public');

        // Update data array with standardized path
        $data['path'] = $path;
        $data['is_public'] = true;

        return $this->imageRepository->store($data);
    }

    /**
     * Update an existing image
     *
     * @param int $imageId
     * @param UploadedFile $file
     * @param int $userId
     * @return mixed
     * @throws ImageException
     * @throws \Exception
     */
    public function updateImage(int $imageId, UploadedFile $file, int $userId)
    {
        // Find the existing image
        $existingImage = $this->findImage($imageId, $userId);

        if (!$existingImage) {
            throw ImageException::notFound();
        }

        try {
            // Delete old file if it exists
            if ($existingImage->path) {
                Storage::disk('public')->delete($existingImage->path);
            }

            // Store new file
            $path = $file->store("images/user_{$userId}", 'public');

            // Update image record
            $updatedImage = $this->imageRepository->update($imageId, [
                'filename' => basename($path),
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'path' => $path,
                'is_public' => true // Keep the image public after update
            ]);

            if (!$updatedImage) {
                // If update fails, clean up the uploaded file
                Storage::disk('public')->delete($path);
                throw new \Exception('Failed to update image record');
            }

            return $updatedImage;

        } catch (\Exception $e) {
            // Clean up any uploaded file if there was an error
            if (isset($path)) {
                Storage::disk('public')->delete($path);
            }
            throw $e;
        }
    }

    /**
     * Delete an image
     *
     * @param mixed $id
     * @return mixed
     */
    public function deleteImage($id)
    {
        return $this->imageRepository->delete($id);
    }
}
