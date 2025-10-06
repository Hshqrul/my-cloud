<?php

namespace App\Filament\Resources\FinanceResource\Pages;

use App\Filament\Resources\FinanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditFinance extends EditRecord
{
    protected static string $resource = FinanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

//     protected function mutateFormDataBeforeSave(array $data): array
//     {
//         if ($data['user_id'] === null || !$data['user_id']) {
//             // unset($data['user_id']);
//             $data['user_id'] = auth()->user()->id;
//         }
// dd($data);
//         return $data;
//     }
}
