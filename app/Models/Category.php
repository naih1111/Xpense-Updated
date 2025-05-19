<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',
        'user_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $validator = Validator::make($category->toArray(), [
                'name' => 'required|string|max:255',
                'type' => 'required|in:income,expense',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                throw ValidationException::withMessages($validator->errors()->toArray());
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function incomes()
    {
        return $this->hasMany(Income::class);
    }

    public function budget()
    {
        return $this->hasOne(Budget::class);
    }

    public function getTotalAmount($startDate = null, $endDate = null)
    {
        $query = $this->type === 'expense' ? $this->expenses() : $this->incomes();
        
        if ($startDate && $endDate) {
            $query->whereBetween('date', [$startDate, $endDate]);
        }

        return $query->sum('amount');
    }
} 