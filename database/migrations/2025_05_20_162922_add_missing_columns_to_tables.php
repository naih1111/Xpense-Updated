<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            if (!Schema::hasColumn('incomes', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('incomes', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable();
            }
        });
        Schema::table('expenses', function (Blueprint $table) {
            if (!Schema::hasColumn('expenses', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('expenses', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable();
            }
        });
        Schema::table('budgets', function (Blueprint $table) {
            if (!Schema::hasColumn('budgets', 'category')) {
                $table->string('category')->nullable();
            }
            if (!Schema::hasColumn('budgets', 'category_id')) {
                $table->unsignedBigInteger('category_id')->nullable();
            }
        });
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'currency_preference')) {
                $table->string('currency_preference')->default('PHP');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incomes', function (Blueprint $table) {
            $table->dropColumn(['category', 'category_id']);
        });
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropColumn(['category', 'category_id']);
        });
        Schema::table('budgets', function (Blueprint $table) {
            $table->dropColumn(['category', 'category_id']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('currency_preference');
        });
    }
};
