<?php

namespace App\Filament\Resources\LeadAssignmentRuleResource\Pages;

use App\Filament\Resources\LeadAssignmentRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLeadAssignmentRule extends EditRecord
{
    protected static string $resource = LeadAssignmentRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
