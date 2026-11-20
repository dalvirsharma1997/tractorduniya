<?php

namespace App\Filament\Resources\UsedListingResource\Pages;

use App\Filament\Resources\UsedListingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUsedListing extends EditRecord
{
    protected static string $resource = UsedListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
