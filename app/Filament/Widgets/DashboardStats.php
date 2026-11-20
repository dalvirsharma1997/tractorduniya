<?php

namespace App\Filament\Widgets;

use App\Models\Lead;
use App\Models\UsedListing;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStats extends BaseWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array
    {
        return [
            Stat::make("Today's leads", Lead::whereDate('created_at', today())->count())
                ->description('New enquiries today')
                ->color('success'),
            Stat::make('New leads (unactioned)', Lead::where('status', 'new')->count())
                ->description('Awaiting first contact')
                ->color('warning'),
            Stat::make('Pending used-listing approvals', UsedListing::where('status', 'pending')->count())
                ->description('Need review')
                ->color('danger'),
        ];
    }
}
