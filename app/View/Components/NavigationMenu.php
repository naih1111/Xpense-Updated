<?php

namespace App\View\Components;

use Illuminate\View\Component;
use App\Models\User;

class NavigationMenu extends Component
{
    /**
     * The user instance.
     *
     * @var \App\Models\User
     */
    public $user;

    /**
     * Create a new component instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.navigation-menu');
    }
} 