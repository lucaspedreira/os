<?php

namespace App\Filament\Resources\EscolaridadeResource\Pages;

use App\Filament\Resources\EscolaridadeResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageEscolaridades extends ManageRecords
{
    protected static string $resource = EscolaridadeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
