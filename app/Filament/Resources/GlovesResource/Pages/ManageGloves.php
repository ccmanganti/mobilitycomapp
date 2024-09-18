<?php

namespace App\Filament\Resources\GlovesResource\Pages;

use App\Filament\Resources\GlovesResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageGloves extends ManageRecords
{
    protected static string $resource = GlovesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
