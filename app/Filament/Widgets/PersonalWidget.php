<?php

namespace App\Filament\Widgets;

use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\Widget;

class PersonalWidget extends Widget
{
    use HasWidgetShield;
    
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = -99;

    protected static string $view = 'filament.widgets.personal-widget';
}
