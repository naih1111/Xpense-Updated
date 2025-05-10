<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ResponsiveNavLink extends Component
{
    /**
     * The active state of the link.
     *
     * @var bool
     */
    public $active;

    /**
     * Create a new component instance.
     */
    public function __construct($active = false)
    {
        $this->active = $active;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.responsive-nav-link');
    }
} 