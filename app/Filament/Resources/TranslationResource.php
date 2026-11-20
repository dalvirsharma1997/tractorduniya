<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TranslationResource\Pages;
use App\Models\Blog;
use App\Models\News;
use App\Models\Translation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class TranslationResource extends Resource
{
    protected static ?string $model = Translation::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static ?string $navigationGroup = 'Content';

    protected static ?string $navigationLabel = 'Translations (Hindi)';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('entity_type')
                    ->options(['blog' => 'Blog', 'news' => 'News'])
                    ->required()
                    ->live(),
                Forms\Components\Select::make('entity_id')
                    ->label('Item')
                    ->options(function (callable $get) {
                        return match ($get('entity_type')) {
                            'blog' => Blog::pluck('title', 'id'),
                            'news' => News::pluck('title', 'id'),
                            default => [],
                        };
                    })
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('language_code')
                    ->label('Language')
                    ->options(['hi' => 'Hindi'])
                    ->default('hi')
                    ->required(),
                Forms\Components\Select::make('field_name')
                    ->label('Field')
                    ->options(['title' => 'Title', 'excerpt' => 'Excerpt', 'body' => 'Body'])
                    ->required(),
                Forms\Components\Textarea::make('field_value')
                    ->label('Translated value')
                    ->rows(6)
                    ->required()
                    ->columnSpanFull(),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('entity_type')->badge(),
                Tables\Columns\TextColumn::make('entity_id'),
                Tables\Columns\TextColumn::make('language_code')->badge(),
                Tables\Columns\TextColumn::make('field_name'),
                Tables\Columns\TextColumn::make('field_value')->limit(60),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('entity_type')->options(['blog' => 'Blog', 'news' => 'News']),
                Tables\Filters\SelectFilter::make('language_code')->options(['hi' => 'Hindi']),
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
            'index' => Pages\ListTranslations::route('/'),
            'create' => Pages\CreateTranslation::route('/create'),
            'edit' => Pages\EditTranslation::route('/{record}/edit'),
        ];
    }
}
