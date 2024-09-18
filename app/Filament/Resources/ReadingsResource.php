<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReadingsResource\Pages;
use App\Filament\Resources\ReadingsResource\RelationManagers;
use App\Models\Readings;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
class ReadingsResource extends Resource
{
    protected static ?string $model = Readings::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Resource Management';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('gloves_id')
                    ->relationship('gloves', 'id')
                    ->required(),
                Forms\Components\TextInput::make('finger_1')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('finger_2')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('finger_3')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('finger_4')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('finger_5')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('gloves.id')
                    ->label('Glove ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('gloves.name')
                    ->label('Patient')
                    ->sortable(),
                Tables\Columns\TextColumn::make('finger_1')
                    ->label('Pinky Finger')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('finger_2')
                    ->label('Ring Finger')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('finger_3')
                    ->label('Middle Finger')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('finger_4')
                    ->label('Index Finger')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('finger_5')
                    ->label('Thumb Finger')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->badge()
                    ->since()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color(function (Readings $record) {
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
            ->defaultSort('created_at', 'desc')
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
            'index' => Pages\ManageReadings::route('/'),
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
        return parent::getEloquentQuery()->whereHas('gloves', function($query) use ($user){
            $query->where('user_id', $user->id);
        });
    }
}
