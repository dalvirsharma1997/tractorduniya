<?php

namespace App\Filament\Resources\LeadAssignmentRuleResource\Pages;

use App\Filament\Resources\LeadAssignmentRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeadAssignmentRules extends ListRecords
{
    protected static string $resource = LeadAssignmentRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
