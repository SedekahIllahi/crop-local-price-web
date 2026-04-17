<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Illuminate\View\Component;

class AutoAlert extends Component
{
    public $type;

    public $message;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        if (Session::has('success')) {
            $this->type = 'success';
            $this->message = Session::get('success');
        } elseif (Session::has('error')) {
            $this->type = 'danger';
            $this->message = Session::get('error');
        } elseif (Session::has('warning')) {
            $this->type = 'warning';
            $this->message = Session::get('warning');
        } elseif (Session::has('info')) {
            $this->type = 'info';
            $this->message = Session::get('info');
        } else {
            $this->type = '';
            $this->message = '';
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.auto-alert');
    }
}
