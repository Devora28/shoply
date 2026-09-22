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
        Schema::create('about_pages', function (Blueprint $table) {
            $table->id();
            $table->string('hero_eyebrow');
            $table->string('hero_title');
            $table->text('hero_description');
            $table->string('mission_eyebrow');
            $table->string('mission_title');
            $table->text('mission_description');
            $table->string('mission_image')->nullable();
            $table->string('values_eyebrow');
            $table->string('values_title');
            $table->text('values_description');
            $table->string('team_eyebrow');
            $table->string('team_title');
            $table->text('team_description');
            $table->string('milestones_eyebrow');
            $table->string('milestones_title');
            $table->text('milestones_description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('about_pages');
    }
};
