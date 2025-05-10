<?php

namespace App\Providers;

use App\Models\FinancialGoal;
use App\Policies\FinancialGoalPolicy;
use App\Models\Income;
use App\Policies\IncomePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        FinancialGoal::class => FinancialGoalPolicy::class,
        Income::class => IncomePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
} 