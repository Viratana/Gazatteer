<?php

namespace App\Filament\User\Resources\LocationCodes;

use App\Filament\User\Resources\LocationCodes\Pages\CreateLocationCode;
use App\Filament\User\Resources\LocationCodes\Pages\EditLocationCode;
use App\Filament\User\Resources\LocationCodes\Pages\ListLocationCodes;
use App\Filament\User\Resources\LocationCodes\Schemas\LocationCodeForm;
use App\Filament\User\Resources\LocationCodes\Tables\LocationCodesTable;
use App\Models\LocationCode;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class LocationCodeResource extends Resource
{
    protected static ?string $model = LocationCode::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::CodeBracket;

    protected static ?int $navigationSort = 4;

    protected static string | UnitEnum | null $navigationGroup = 'Location';

    public static function form(Schema $schema): Schema
    {
        return LocationCodeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LocationCodesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLocationCodes::route('/'),
            // 'create' => CreateLocationCode::route('/create'),
            // 'edit' => EditLocationCode::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }


    public static function canCreate(): bool
    {
        return false;
    }
}
