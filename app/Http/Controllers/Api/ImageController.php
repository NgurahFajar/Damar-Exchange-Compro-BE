<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ImageRequest;
use App\Repositories\ImageRepository;
use App\Services\Image\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    protected ImageRepository $imageRepository;
    protected ImageService $imageService;

    public function __construct(ImageService $imageService, ImageRepository $imageRepository)
    {
        $this->imageService = $imageService;
        $this->imageRepository = $imageRepository;
    }

    public function index(): JsonResponse
    {
        try {
            $images = $this->imageService->getPublicImages();

            $transformedImages = $images->map(function ($image) {
                return [
                    'id' => $image->id,
                    'original_name' => $image->original_name,
                    'path' => $image->path,
                    'size' => $image->size,
                    'mime_type' => $image->mime_type,
                    'created_at' => $image->created_at->toDateTimeString()
                ];
            });

            return response()->json([
                'status' => 'success',
                'data' => $transformedImages,
                'message' => 'Images retrieved successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch images',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function update(ImageRequest $request, $id): JsonResponse
    {
        try {
            $userId = auth()->id();

            // Check if image exists and belongs to user
            $existingImage = $this->imageService->findImage($id, $userId);

            if (!$existingImage) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Image not found'
                ], 404);
            }

            if (!$request->hasFile('image')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No image file provided'
                ], 400);
            }

            $file = $request->file('image');

            // Update the image
            $updatedImage = $this->imageService->updateImage($id, $file, $userId);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $updatedImage->id,
                    'original_name' => $updatedImage->original_name,
                    'path' => $updatedImage->path,
                    'size' => $updatedImage->size,
                    'mime_type' => $updatedImage->mime_type,
                    'created_at' => $updatedImage->created_at->toDateTimeString(),
                    'updated_at' => $updatedImage->updated_at->toDateTimeString()
                ],
                'message' => 'Image updated successfully'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(ImageRequest $request): JsonResponse
    {
        try {
            if (!$request->hasFile('image')) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No image file provided'
                ], 400);
            }

            $file = $request->file('image');
            $userId = auth()->id();

            // Use the same path structure
            $path = $file->store("user_images/{$userId}", 'public');

            $imageData = [
                'user_id' => $userId,
                'filename' => basename($path),
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
                'path' => $path,
                'image' => $file // Pass the file object for the service to handle
            ];

            $image = $this->imageService->storeImage($imageData);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $image->id,
                    'original_name' => $image->original_name,
                    'path' => $image->path,
                    'size' => $image->size,
                    'mime_type' => $image->mime_type,
                    'created_at' => $image->created_at->toDateTimeString()
                ],
                'message' => 'Image uploaded successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to upload image',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id): JsonResponse
    {
        try {
            $userId = auth()->id();
            $image = $this->imageService->findImage($id, $userId);

            if (!$image) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Image not found'
                ], 404);
            }

            // Delete the file from storage
            Storage::disk('public')->delete($image->path);

            // Delete the database record
            $this->imageService->deleteImage($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Image deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete image',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
