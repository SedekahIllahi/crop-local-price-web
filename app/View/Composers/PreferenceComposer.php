<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Models\Preference;
use Illuminate\Support\Facades\Cache;

class PreferenceComposer
{
    public function compose(View $view)
    {
        $prefs = Cache::remember('prefs_composer', 60 * 60 * 24, function () {
            $prefs = [];
            $pref = Preference::where('group', 'site')->get()->pluck('value', 'name');
            foreach ($pref as $key => $value) {
                $prefs[$key] = $value;
            }
            return $prefs;
        });
        $view->with('prefs_composer', $prefs);
    }
}
