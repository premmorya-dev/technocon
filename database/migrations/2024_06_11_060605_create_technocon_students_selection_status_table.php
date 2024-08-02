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
        Schema::create('technocon_students_selection_status', function (Blueprint $table) {
            $table->id('student_selection_status_id');
            $table->string('selection_status',255);
            $table->string('bootstrap_class',255);
            $table->string('selection_status_backend',50);
            $table->text('selection_status_description');          
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technocon_students_selection_status');
    }
};
