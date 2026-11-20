<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SeoMetaResource\Pages;
use App\Models\SeoMeta;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SeoMetaResource extends Resource
{
    protected static ?string $model = SeoMeta::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass';

    protected static ?string $navigationGroup = 'SEO';

    protected static ?string $navigationLabel = 'Page Meta Overrides';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('path')
                    ->helperText('Static page path, e.g. /about. Leave blank if overriding a specific record instead.'),
                Forms\Components\TextInput::make('routable_type')->helperText('e.g. tractor, implement, blog — leave blank for a static page override.'),
                Forms\Components\TextInput::make('routable_id')->numeric(),
                Forms\Components\TextInput::make('meta_title')->columnSpanFull(),
                Forms\Components\Textarea::make('meta_description')->rows(2)->columnSpanFull(),
                Forms\Components\TextInput::make('canonical_url')->url(),
                Forms\Components\FileUpload::make('og_image')->image()->directory('seo'),
            ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('path')->placeholder('—'),
                Tables\Columns\TextColumn::make('routable_type')->placeholder('—'),
                Tables\Columns\TextColumn::make('meta_title')->limit(50),
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
            'index' => Pages\ListSeoMetas::route('/'),
            'create' => Pages\CreateSeoMeta::route('/create'),
            'edit' => Pages\EditSeoMeta::route('/{record}/edit'),
        ];
    }
}
