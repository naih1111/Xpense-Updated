<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'amount',
        'period',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getSpentAmount()
    {
        return $this->category->expenses()
            ->whereBetween('date', [$this->start_date, $this->end_date ?? now()])
            ->sum('amount');
    }

    public function getRemainingAmount()
    {
        return $this->amount - $this->getSpentAmount();
    }

    public function getSpentPercentage()
    {
        return ($this->getSpentAmount() / $this->amount) * 100;
    }
} 