<?php

namespace App\Filament\Clusters\AccountSetting\Pages;

use Exception;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Split;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use App\Filament\Clusters\AccountSetting;
use Filament\Pages\SubNavigationPosition;
use Illuminate\Contracts\Auth\Authenticatable;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Termwind\Enums\Color;

class Profile extends Page implements HasForms
{
    use InteractsWithForms, InteractsWithActions;

    protected static ?string $navigationIcon = 'heroicon-c-user-circle';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.clusters.account-setting.pages.profile';

    protected static ?string $cluster = AccountSetting::class;

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Start;

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $data = $this->getUser()?->attributesToArray();

        $this->form->fill($data);
    }

    public function getUser(): Authenticatable & Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }

    /**
     * Defines the form schema of the page.
     *
     * @param  \Filament\Forms\Form  $form
     * @return \Filament\Forms\Form
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make('Avatar')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->hiddenLabel()
                            ->imageEditor()
                            ->collection('avatars'),
                    ])
                    ->icon('heroicon-s-user'),
                    Section::make('Details')
                    ->schema([
                        Grid::make()->schema([
                            TextInput::make('username')
                                ->readOnly(),
                            TextInput::make('email')
                                ->label('Email Address')
                                ->disabled()
                                ->email(),
                            Split::make([
                                TextInput::make('name')
                                ->label(__('Name'))
                            ])->columnSpanFull()
                        ])
                    ])
                    ->icon('heroicon-s-document-text')
                    ->grow(false),
                ])->from('md'),
                Section::make('Personal Information')
                    ->relationship('profile')
                    ->schema([
                        TextInput::make('tel_no')
                            ->label('Phone Number')
                            ->tel()
                            ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                        TextInput::make('address'),
                        TextInput::make('city'),
                        TextInput::make('postcode')
                            ->numeric(),
                        TextInput::make('state'),
                        TextInput::make('country'),
                    ])
                    ->icon('heroicon-s-identification')
                    ->columns(2)
            ])
            ->statePath('data')
            ->model(auth()->user());
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('Update')
                ->color('primary')
                ->submit('Update'),
        ];
    }

    public function update()
    {
        try {
            auth()->user()->update(
                $this->form->getState()
            );

            Notification::make()
                ->title('Profile updated!')
                ->success()
                ->send();
        } catch (\Throwable $th) {
            Notification::make()
                ->title('Failed to update.')
                ->danger()
                ->send();
        }
    }
}
