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
        Schema::table('users', function (Blueprint $table) {
            $table->string('theme')->default('light')->after('avatar');
        });

        $company = \App\Models\Company::query()->first();
        if ($company) {
            \App\Models\Setting::query()->updateOrCreate(
                [
                    'company_id' => $company->id,
                    'branch_id' => null,
                    'key' => 'enable_theme_mode',
                ],
                [
                    'group' => 'appearance',
                    'value' => '1',
                    'type' => 'boolean',
                    'description' => 'Enable Dark/Night Mode Switcher for the whole application',
                    'is_public' => true,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('theme');
        });

        \App\Models\Setting::query()->where('key', 'enable_theme_mode')->delete();
    }
};
