<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class Alert extends Component
{
    public string|array $message;
    public string|null $title;
    public string $type;
    public string $icon;
    /**
     * Create a new component instance.
     */

    public function __construct()
    {
        if(Session::has('custom-alert')){
            $this->type = Session::get('custom-alert')['type'];
            $this->message = Session::get('custom-alert')['message'];
            $this->title = Session::get('custom-alert')['title'];
            $this->icon();
        }else{
            $this->type = '';
            $this->message = '';
            $this->title = '';
            $this->icon();
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }
    
    private function icon()
    {
        $this->icon = match ($this->type) {
            'success' => 'ph-duotone ph-check-circle me-2',
            'info' => 'ph-duotone ph-info me-2',
            'warning' => 'ph-duotone ph-warning me-2',
            'danger' => 'ph-duotone ph-x-circle me-2',
            default => '',
        };
    }
}
