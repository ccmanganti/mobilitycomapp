<?php

namespace App\Filament\Resources\ActionsResource\Pages;

use App\Filament\Resources\ActionsResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageActions extends ManageRecords
{
    protected static string $resource = ActionsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
