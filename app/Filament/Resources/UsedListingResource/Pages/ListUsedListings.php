<?php

namespace App\Filament\Resources\UsedListingResource\Pages;

use App\Filament\Resources\UsedListingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUsedListings extends ListRecords
{
    protected static string $resource = UsedListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
