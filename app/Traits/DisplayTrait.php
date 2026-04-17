<?php
namespace App\Traits;

use App\Models\Navigation;
use App\Models\Preference;

trait DisplayTrait
{
    public function display(string $view, array $data = [])
    {
        $sidebar = $this->getSidebar();
        $lang = ['en', 'id'];
        $site = $this->getPreference();
        $data = array_merge($data, ['sidebar' => $sidebar], ['_langs'=>$lang], ['_site'=>$site]);

        return view($view, $data);
    }

    protected function getSidebar()
    {
        return Navigation::where('display', true)
                        ->where('parent_id', null)
                        ->where('active', true)
                        ->with('child')
                        ->orderBy('order', 'asc')->get();
    }

    protected function getPreference(){
        $preferences = Preference::where('group', 'site')->get();
        $new = new \stdClass();

        foreach ($preferences as $preference) {
            $new->{$preference['name']} = $preference['value'];
        }

        // Output
        return $new;
    }

    protected function setRule($role=null) {
        // Untuk testing, nonaktifkan pembatasan akses
        return true;
    }
}