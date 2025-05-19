<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($goal) {
            $validator = Validator::make($goal->toArray(), [
                'name' => 'required|string|max:255',
                'target_amount' => 'required|numeric|min:0',
                'current_amount' => 'required|numeric|min:0',
                'target_date' => 'required|date|after:today',
            ]);

            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->toArray());
            }

            if ($goal->current_amount > $goal->target_amount) {
                throw ValidationException::withMessages([
                    'current_amount' => ['Current amount cannot be greater than target amount.'],
                ]);
            }
        });

        static::created(function ($goal) {
            if ($goal->current_amount > 0) {
                $goal->user->expenses()->create([
                    'amount' => $goal->current_amount,
                    'description' => "Financial Goal: {$goal->name}",
                    'type' => 'saving',
                    'date' => now(),
                ]);
            }
        });

        static::updating(function ($goal) {
            if ($goal->isDirty('current_amount')) {
                $oldAmount = $goal->getOriginal('current_amount');
                $newAmount = $goal->current_amount;

                if ($newAmount > $goal->target_amount) {
                    throw ValidationException::withMessages([
                        'current_amount' => ['Current amount cannot be greater than target amount.'],
                    ]);
                }

                // Update or create the associated expense
                $expense = $goal->user->expenses()
                    ->where('type', 'saving')
                    ->where('description', 'like', "%{$goal->name}%")
                    ->first();

                if ($expense) {
                    $expense->update(['amount' => $newAmount]);
                } else {
                    $goal->user->expenses()->create([
                        'amount' => $newAmount,
                        'description' => "Financial Goal: {$goal->name}",
                        'type' => 'saving',
                        'date' => now(),
                    ]);
                }
            }
        });

        static::deleted(function ($goal) {
            // Delete associated expense when goal is deleted
            $goal->user->expenses()
                ->where('type', 'saving')
                ->where('description', 'like', "%{$goal->name}%")
                ->delete();
        });
    }

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