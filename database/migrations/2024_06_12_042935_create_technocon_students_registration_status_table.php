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
        Schema::create('technocon_students_registration_status', function (Blueprint $table) {
            $table->id('student_registration_status_id');
            $table->string('registration_status',255);
            $table->string('bootstrap_class',255);
            $table->string('registration_status_backend',50);
            $table->text('registration_status_description');
            $table->datetime('added_datetime')->nullable();          
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technocon_students_registration_status');
    }
};
