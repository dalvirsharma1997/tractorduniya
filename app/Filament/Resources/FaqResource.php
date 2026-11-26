<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FaqResource\Pages;
use App\Models\Faq;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class FaqResource extends Resource
{
    protected static ?string $model = Faq::class;

    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';

    protected static ?string $navigationGroup = 'Content';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('faqable_type')
                    ->options([
                        'tractor' => 'Tractor', 'implement' => 'Implement', 'brand' => 'Brand',
                        'category' => 'Category', 'dealer' => 'Dealer', 'page' => 'Page',
                    ])
                    ->live(),
                Forms\Components\Select::make('faqable_id')
                    ->label('Related item')
                    ->options(function (callable $get) {
                        return match ($get('faqable_type')) {
                            'tractor' => \App\Models\Tractor::pluck('name', 'id'),
                            'implement' => \App\Models\Implement::pluck('name', 'id'),
                            'brand' => \App\Models\Brand::pluck('name', 'id'),
                            'category' => \App\Models\Category::pluck('name', 'id'),
                            'dealer' => \App\Models\Dealer::pluck('name', 'id'),
                            default => [],
                        };
                    })
                    ->searchable(),
                Forms\Components\TextInput::make('question')->required()->columnSpanFull(),
                Forms\Components\Textarea::make('answer')->required()->columnSpanFull(),
                Forms\Components\TextInput::make('sort_order')->numeric()->default(0),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('question')->searchable()->limit(60),
                Tables\Columns\TextColumn::make('faqable_type')->badge(),
                Tables\Columns\TextColumn::make('sort_order'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('faqable_type')->options([
                    'tractor' => 'Tractor', 'implement' => 'Implement', 'brand' => 'Brand',
                    'category' => 'Category', 'dealer' => 'Dealer', 'page' => 'Page',
                ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([Tables\Actions\DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFaqs::route('/'),
            'create' => Pages\CreateFaq::route('/create'),
            'edit' => Pages\EditFaq::route('/{record}/edit'),
        ];
    }
}
