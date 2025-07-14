<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Rsvp;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Exports\RsvpExporter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RsvpResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RsvpResource\RelationManagers;
use Filament\Tables\Columns\Summarizers\Sum;

class RsvpResource extends Resource
{
    protected static ?string $label = 'List RSVPs';

    protected static ?string $model = Rsvp::class;

    protected static ?string $navigationIcon = 'heroicon-c-clipboard-document-check';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('attendence')
                    ->required(),
                Forms\Components\TextInput::make('no_of_pax')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                Tables\Actions\ExportAction::make()
                    ->exporter(RsvpExporter::class),
            ])
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\IconColumn::make('attendence')
                    ->boolean(),
                Tables\Columns\TextColumn::make('no_of_pax')
                    ->numeric()
                    ->sortable()
                    ->summarize(Sum::make('total_attendance')->label('Total Attendance')),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
            'index' => Pages\ManageRsvps::route('/'),
        ];
    }
}
