<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialGoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'target_amount',
        'current_amount',
        'target_date',
        'status',
    ];

    protected $casts = [
        'target_date' => 'date',
        'target_amount' => 'decimal:2',
        'current_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getProgressPercentage()
    {
        return ($this->current_amount / $this->target_amount) * 100;
    }

    public function getRemainingAmount()
    {
        return $this->target_amount - $this->current_amount;
    }

    public function updateStatus()
    {
        if ($this->current_amount >= $this->target_amount) {
            $this->status = 'completed';
        } elseif ($this->target_date < now() && $this->current_amount < $this->target_amount) {
            $this->status = 'failed';
        } else {
            $this->status = 'in_progress';
        }

        $this->save();
    }

    public function getStatus()
    {
        if ($this->current_amount >= $this->target_amount) {
            return 'completed';
        } elseif ($this->target_date < now() && $this->current_amount < $this->target_amount) {
            return 'failed';
        } else {
            return 'in_progress';
        }
    }
} 