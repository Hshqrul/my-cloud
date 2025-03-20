<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class PersonalWidget extends Widget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -99;

    protected static string $view = 'filament.widgets.personal-widget';
}
