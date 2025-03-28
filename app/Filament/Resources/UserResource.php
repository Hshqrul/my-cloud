<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Split;
use Filament\Support\Enums\MaxWidth;
use Illuminate\Support\Facades\Hash;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\Textarea;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rules\Password;
use Filament\Forms\Components\Placeholder;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\Actions\Action;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\UserResource\Widgets\UserOverview;
use Filament\Actions\EditAction;
use Filament\Support\Exceptions\Cancel;
use Filament\Tables\Actions\EditAction as ActionsEditAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-c-user-group';

    // protected static ?string $recordTitleAttribute = 'name';

    public static function getNavigationBadge(): ?string
    {
        return User::where('email_verified_at', null)->count() > 0 ? User::where('email_verified_at', null)->count() : null;
    }


    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Split::make([
                    Section::make('Details')
                        ->description(fn(): string => 'Manage personal information for user')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('media')
                                ->label('Profile Photo')
                                // ->avatar()
                                ->imageEditor()
                                ->collection('avatars'),
                            Grid::make()
                                ->schema([
                                    Forms\Components\TextInput::make('username')
                                        ->label(__('Username'))
                                        ->unique(ignoreRecord: true),
                                    Forms\Components\TextInput::make('email')
                                        ->label('Email Address')
                                        ->email()
                                        ->unique(ignoreRecord: true),
                                    Forms\Components\TextInput::make('name')
                                        ->label('Name')
                                        ->required()
                                        ->maxLength(255),
                                    Group::make()
                                        ->relationship('profile')
                                        ->schema([
                                            TextInput::make('tel_no') // if want to include another table but has relation use (profile.user.username)
                                                ->label('Phone Number')
                                                ->tel()
                                                ->telRegex('/^[+]*[(]{0,1}[0-9]{1,4}[)]{0,1}[-\s\.\/0-9]*$/'),
                                        ])
                                ])
                        ])->grow(true),
                    Group::make()
                        ->schema([
                            Section::make([
                                Placeholder::make('email_verified_at')
                                    ->label('Email Verified At')
                                    ->content(fn(User $record): string => $record->email_verified_at ? (string) $record->email_verified_at->format('d/m/Y H:i') : '-'),
                                Forms\Components\Actions::make([
                                    Action::make('verify')
                                        ->icon('heroicon-m-check-circle')
                                        ->color('success')
                                        ->badge()
                                        ->hiddenLabel()
                                        ->action(fn(Model $record) => static::doAssignEmailVerification($record))
                                        ->hidden(fn(User $user) => $user->email_verified_at != null)
                                        ->tooltip('Click to verify email address'),
                                ])
                                    ->hiddenOn(['create', 'view'])
                                    ->fullWidth(),
                            ])
                                ->heading('Verification')
                                ->description(fn(): string => 'Manage status for user.'),
                            Section::make([
                                Placeholder::make('created_at')
                                    ->label('Registered At')
                                    ->content(fn(User $record): string => $record->created_at ? (string) $record->created_at->format('d/m/Y H:i') : '-'),
                                Placeholder::make('updated_at')
                                    ->label('Last Modified')
                                    ->content(fn(User $record): string => $record->updated_at ? (string) $record->updated_at->format('d/m/Y H:i') : '-'),
                            ]),
                        ])
                        ->hiddenOn(['create', 'view'])
                        ->grow(false),
                ])->from('md'),
                Forms\Components\Tabs::make()
                    ->schema([
                        Forms\Components\Tabs\Tab::make('Personal Information')
                            ->icon('heroicon-c-information-circle')
                            ->schema([
                                Section::make('Personal Information')
                                    ->description(fn(): string => 'Manage personal information for user')
                                    ->relationship('profile')
                                    ->schema([
                                        TextInput::make('address'),
                                        TextInput::make('city'),
                                        TextInput::make('postcode')
                                            ->numeric(),
                                        TextInput::make('state'),
                                        TextInput::make('country'),
                                    ])
                                    ->columns(2),
                            ]),

                        Forms\Components\Tabs\Tab::make('Roles')
                            ->icon('heroicon-c-shield-exclamation')
                            ->schema([
                                Section::make('Roles')
                                    ->description(fn(): string => 'Manage roles for user')
                                    ->schema([
                                        Select::make('roles')
                                            ->hiddenLabel()
                                            ->relationship('roles', 'name')
                                            ->getOptionLabelFromRecordUsing(fn(Model $record) => Str::headline($record->name))
                                            ->multiple()
                                            ->preload()
                                            ->searchable()
                                            ->optionsLimit(5)
                                            ->columnSpanFull(),
                                    ])
                            ]),

                        Forms\Components\Tabs\Tab::make('Password')
                            ->icon('heroicon-c-lock-closed')
                            ->schema([
                                Forms\Components\Section::make()
                                    ->schema([
                                        Forms\Components\TextInput::make('password')
                                            ->password()
                                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                            ->revealable()
                                            ->rule(Password::default())
                                            ->same('passwordConfirmation')
                                            ->required(),
                                        Forms\Components\TextInput::make('passwordConfirmation')
                                            ->password()
                                            ->dehydrateStateUsing(fn(string $state): string => Hash::make($state))
                                            ->dehydrated(false)
                                            ->revealable()
                                            ->required(),
                                    ])
                            ])
                            ->hiddenOn(['edit', 'view']),
                    ])
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 2
                    ]),
            ])->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')
                    ->label('Profile Photo')
                    ->collection('avatars')
                    ->circular()
                    ->wrap(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->description(fn(Model $record) => $record->username)
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->label('Role')
                    ->formatStateUsing(fn($state): string => Str::headline($state))
                    ->colors(['info'])
                    ->badge(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email Address')
                    ->icon('heroicon-m-envelope')
                    ->iconColor('warning')
                    ->alignLeft()
                    ->searchable(),
                Tables\Columns\TextColumn::make('profile.tel_no')
                    ->label('Phone Number')
                    ->icon('heroicon-m-phone')
                    ->iconColor('success')
                    ->alignLeft()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->label('Verified at')
                    ->dateTime()
                    ->toggleable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Registered at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Last Modified')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                TernaryFilter::make('email_verified_at')
                    ->label('Email verification')
                    ->placeholder('All users')
                    ->trueLabel('Verified users')
                    ->falseLabel('Not verified users')
                    ->queries(
                        true: fn(Builder $query) => $query->whereNotNull('email_verified_at'),
                        false: fn(Builder $query) => $query->whereNull('email_verified_at'),
                        blank: fn(Builder $query) => $query, // In this example, we do not want to filter the query when it is blank.
                    ),
            ])
            ->filtersTriggerAction(
                fn(\Filament\Tables\Actions\Action $action) => $action
                    ->button()
                    ->label('Filter'),
            )
            ->actions([
                // Impersonate::make(),
                ActionGroup::make([
                    Tables\Actions\ViewAction::make()
                        ->slideOver()
                        // ->modalDescription()
                        ->modalHeading(fn(User $record): string => $record->name)
                        ->modalFooterActions([
                            Tables\Actions\EditAction::make()
                                ->color('gray'),
                        ])
                        ->modalCancelAction(true),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])->icon('heroicon-m-ellipsis-horizontal'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getWidgets(): array
    {
        return [
            UserOverview::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return __("menu.nav_group.access");
    }

    public static function doAssignEmailVerification($user): void
    {
        if (!method_exists($user, 'notify')) {
            $userClass = $user::class;

            throw new Exception("Model [{$userClass}] does not have a [notify()] method.");
        }

        if ($user->email_verified_at == null) {
            $user->email_verified_at = now();
            $user->save();

            Notification::make()
                ->title(__('resource.user.notifications.verified_done.title'))
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title(__('resource.user.notifications.verified_warning.title'))
                ->body(__('resource.user.notifications.verified_warning.description'))
                ->danger()
                ->send();
        }
    }
}
