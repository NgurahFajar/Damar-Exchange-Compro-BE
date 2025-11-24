<?php

namespace App\Repositories;

use App\Models\Image;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class ImageRepository
{
    protected Image $model;
    const MAX_IMAGES = 6;

    public function __construct(Image $model)
    {
        $this->model = $model;
    }

    public function getAllImages()
    {
        return $this->model
            ->orderBy('created_at', 'desc')
            ->get();
    }


    public function findByUser(int $id, int $userId): ?Image
    {
        return $this->model->where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    public function store(array $data): Image
    {
        return $this->model->create($data);
    }

    public function update(int $id, array $data): ?Image
    {
        $image = $this->model->find($id);
        if ($image) {
            $image->update($data);
            return $image->fresh();  // Return the updated model
        }
        return null;
    }

    public function delete(int $id): bool
    {
        $image = $this->model->find($id);
        if ($image) {
            return $image->delete();
        }
        return false;
    }

    public function countUserImages(int $userId): int
    {
        return $this->model->where('user_id', $userId)->count();
    }

    public function canAddMore(int $userId): bool
    {
        return $this->countUserImages($userId) < self::MAX_IMAGES;
    }
}
