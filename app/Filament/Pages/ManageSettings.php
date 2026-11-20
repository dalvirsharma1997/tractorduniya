<?php

namespace App\Filament\Pages;

use App\Models\Setting;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class ManageSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $navigationGroup = 'Administration';

    protected static string $view = 'filament.pages.manage-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill([
            'site_name' => Setting::get('site_name', 'TractorDuniya.in'),
            'contact_phone' => Setting::get('contact_phone'),
            'contact_email' => Setting::get('contact_email'),
            'whatsapp_number' => Setting::get('whatsapp_number'),
            'facebook_url' => Setting::get('facebook_url'),
            'youtube_url' => Setting::get('youtube_url'),
            'instagram_url' => Setting::get('instagram_url'),
            'emi_default_interest_rate' => Setting::get('emi_default_interest_rate', '10.5'),
        ]);
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Site Info')
                    ->schema([
                        TextInput::make('site_name')->required(),
                        TextInput::make('contact_phone')->tel(),
                        TextInput::make('contact_email')->email(),
                        TextInput::make('whatsapp_number')->tel(),
                    ])->columns(2),
                Section::make('Social Links')
                    ->schema([
                        TextInput::make('facebook_url')->url(),
                        TextInput::make('youtube_url')->url(),
                        TextInput::make('instagram_url')->url(),
                    ])->columns(3),
                Section::make('EMI Calculator Defaults')
                    ->schema([
                        TextInput::make('emi_default_interest_rate')
                            ->label('Default interest rate (%)')
                            ->numeric(),
                    ]),
            ])
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach ($data as $key => $value) {
            Setting::set($key, $value);
        }

        Notification::make()->title('Settings saved')->success()->send();
    }
}
