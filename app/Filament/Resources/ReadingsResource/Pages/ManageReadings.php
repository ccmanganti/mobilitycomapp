<?php

namespace App\Filament\Resources\ReadingsResource\Pages;

use App\Filament\Resources\ReadingsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageReadings extends ManageRecords
{
    protected static string $resource = ReadingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
