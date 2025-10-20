<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    // 🧠 This runs right before saving to the database
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['image']) && is_string($data['image'])) {
            $path = storage_path('app/public/' . $data['image']);

            if (file_exists($path)) {
                $imageData = file_get_contents($path);
                $data['image'] = $imageData; // 🟢 Save as BLOB
                unlink($path); // remove temp file
            }
        }

        return $data;
    }

    // 🧾 Optional: success message after saving
    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Product added successfully!';
    }
}
