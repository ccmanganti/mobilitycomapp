<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GlovesResource\Pages;
use App\Filament\Resources\GlovesResource\RelationManagers;
use App\Models\Gloves;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Filament\Tables\Columns\ToggleColumn;

class GlovesResource extends Resource
{
    protected static ?string $model = Gloves::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    
    protected static ?string $navigationGroup = 'Resource Management';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                ->disabled()
                ->relationship('user', 'name')
                ->default(fn() => Auth::id()),

                // Forms\Components\TextInput::make('user_id')
                //     ->disabled()
                //     ->default(fn() => Auth::id()),
                Forms\Components\TextInput::make('name')
                    ->label('Patient User Name')
                    ->required(),   
                Forms\Components\TextInput::make('serial_number')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Patient User Name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->searchable(),
                ToggleColumn::make('default')
                ->beforeStateUpdated(function ($record, $state) {
                    // Get the current authenticated user
                    $user = Auth::user();

                    // Set the toggled glove as the default
                    $record->update(['default' => true]);

                    // Unset the default state for all other gloves owned by the user
                    Gloves::where('user_id', $user->id)
                        ->where('id', '!=', $record->id) // Exclude the current glove being toggled
                        ->update(['default' => false]);
                }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->since()
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->color(function (Gloves $record) {
                        // Calculate the time difference from now to created_at
                        $createdAt = Carbon::parse($record->created_at);
                        
                        // Check if the record was created within the last 2 minutes
                        if ($createdAt->diffInMinutes(Carbon::now()) <= 2) {
                            return 'success'; // Record created recently
                        }
                        return 'primary'; // Record not recent
                    }),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageGloves::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $user = Auth::user();
        
        // COMMENT FOR TESTING

        // $gloves = $user->gloves; // Get all gloves of the user
        // foreach ($gloves as $glove) {
        //     $actions = $glove->readings; // Access actions for each glove
        //     dd($actions->toArray()); // This will dump actions for the first glove
        // }
        return parent::getEloquentQuery()->where('user_id', $user->id);
    }
}
