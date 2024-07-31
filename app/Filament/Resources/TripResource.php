<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TripResource\Pages;
use App\Filament\Resources\TripResource\RelationManagers;
use App\Models\Trip;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\TextInput::make('starting_point')->required(),
            Forms\Components\TextInput::make('ending_point')->required(),
            Forms\Components\DateTimePicker::make('starting_at')->required(),
            Forms\Components\TextInput::make('available_places')->integer()->required(),
            Forms\Components\TextInput::make('price')->integer()->required(),
            Forms\Components\Select::make('user_id')->options(User::all()->pluck('lastname', 'id'))
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('starting_point'),
            Tables\Columns\TextColumn::make('ending_point'),
            Tables\Columns\TextColumn::make('starting_at'),
            Tables\Columns\TextColumn::make('available_places'),
            Tables\Columns\TextColumn::make('price'),
            Tables\Columns\TextColumn::make('user_id'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrip::route('/create'),
            'edit' => Pages\EditTrip::route('/{record}/edit'),
        ];
    }
}
