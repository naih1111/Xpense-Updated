<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Dropdown extends Component
{
    /**
     * The alignment of the dropdown.
     *
     * @var string
     */
    public $align;

    /**
     * The width of the dropdown.
     *
     * @var string
     */
    public $width;

    /**
     * The content classes of the dropdown.
     *
     * @var string
     */
    public $contentClasses;

    /**
     * Create a new component instance.
     */
    public function __construct($align = 'right', $width = '48', $contentClasses = '')
    {
        $this->align = $align;
        $this->width = $width;
        $this->contentClasses = $contentClasses;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.dropdown');
    }
} 