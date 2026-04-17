<?php

namespace App\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class ThemeModeComposer
{
    public function compose(View $view)
    {
        $defaultTheme = getDefaultTheme();
        $view->with('theme_mode', $defaultTheme);
    }
}
