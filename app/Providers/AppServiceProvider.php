<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\View\Components\ApplicationLogo;
use App\View\Components\NavigationMenu;
use App\View\Components\GuestLayout;
use App\View\Components\AppLayout;
use App\View\Components\ResponsiveNavLink;
use App\View\Components\NavLink;
use App\View\Components\Dropdown;
use App\View\Components\DropdownLink;
use App\View\Components\Modal;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register the application logo component
        Blade::component('application-logo', ApplicationLogo::class);
        
        // Register the navigation menu component
        Blade::component('navigation-menu', NavigationMenu::class);
        
        // Register the guest layout component
        Blade::component('guest-layout', GuestLayout::class);
        
        // Register the app layout component
        Blade::component('app-layout', AppLayout::class);
        
        // Register the responsive nav link component
        Blade::component('responsive-nav-link', ResponsiveNavLink::class);
        
        // Register the nav link component
        Blade::component('nav-link', NavLink::class);
        
        // Register the dropdown component
        Blade::component('dropdown', Dropdown::class);
        
        // Register the dropdown link component
        Blade::component('dropdown-link', DropdownLink::class);

        // Register the modal component
        Blade::component('modal', Modal::class);
    }
}
