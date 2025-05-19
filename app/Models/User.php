<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'password',
        'monthly_income',
        'currency_preference',
        'profile_picture',
        'bio',
        'occupation',
        'location',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'monthly_income' => 'decimal:2',
        ];
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }

    public function financialGoals()
    {
        return $this->hasMany(FinancialGoal::class);
    }

    public function getTotalExpenses($startDate = null, $endDate = null)
    {
        try {
            $query = $this->expenses();
            
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }

            return $query->sum('amount') ?? 0;
        } catch (\Exception $e) {
            \Log::error('Error calculating total expenses: ' . $e->getMessage());
            return 0;
        }
    }

    public function getTotalIncome($startDate = null, $endDate = null)
    {
        try {
            $query = $this->incomes();
            
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }

            return $query->sum('amount') ?? 0;
        } catch (\Exception $e) {
            \Log::error('Error calculating total income: ' . $e->getMessage());
            return 0;
        }
    }

    public function getNetSavings($startDate = null, $endDate = null)
    {
        try {
            $income = $this->getTotalIncome($startDate, $endDate);
            $expenses = $this->getTotalExpenses($startDate, $endDate);
            return $income - $expenses;
        } catch (\Exception $e) {
            \Log::error('Error calculating net savings: ' . $e->getMessage());
            return 0;
        }
    }
}
