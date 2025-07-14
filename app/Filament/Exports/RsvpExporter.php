<?php

namespace App\Filament\Exports;

use App\Models\Rsvp;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class RsvpExporter extends Exporter
{
    protected static ?string $model = Rsvp::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')
                ->label('Full Name'),
            ExportColumn::make('attendence')
                ->label('Attended')
                ->formatStateUsing(fn(Rsvp $record): string => $record->attendence ? 'Yes' : 'No'),
            ExportColumn::make('no_of_pax')
                ->label('Number of Guests')
                ->prefix('Pax'),
            ExportColumn::make('created_at')
                ->label('Responded At')
                ->formatStateUsing(fn(Rsvp $record): string => $record->created_at->format('M d, Y')),
            // ExportColumn::make('total_attendance')
            //     ->label('Total Attendance')
            //     ->state(function (Rsvp $record): float {
            //         if ($record->attendence === false) {
            //             return 0;
            //         }
            //         // dd($record->no_of_pax);
            //         return $record->count($record->no_of_pax);
            //     })
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your rsvp export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
