<?php

namespace App\Filament\Resources\OsResource\Pages;

use App\Filament\Resources\OsResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOs extends CreateRecord
{
    protected static string $resource = OsResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
//        dd($data);
        return $data;
    }
}
