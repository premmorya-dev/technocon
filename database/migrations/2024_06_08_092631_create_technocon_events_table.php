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
        Schema::create('technocon_event', function (Blueprint $table) {
            $table->id('event_id');

            $table->string('event_code',4);
            $table->string('event_backend_name',255);
            $table->string('event_presenting_partner_1',255);
            $table->string('event_presenting_partner_2_association_slug',255);
            $table->string('event_presenting_partner_2',255);
            $table->string('event_presenting_partner_1_association_slug',255);
            $table->string('event_name_prefix',255);
            $table->string('event_name',255);
            $table->string('event_name_suffix',255);
            $table->string('event_partner_1_association_slug',255);
            $table->string('event_partner_1',255);
            $table->string('event_partner_1_suffix',255);
            $table->string('event_partner_2_association_slug',255);
            $table->string('event_partner_2',255);
            $table->string('event_partner_2_suffix',255);
            $table->string('event_partner_3_association_slug',255);
            $table->string('event_partner_3',255);
            $table->string('event_partner_3_suffix',255);
            $table->date('event_from_date');
            $table->date('event_end_date');
            $table->string('event_url',255);
            $table->string('registration_page_banner_url',255);
            $table->string('registration_page_header_main_text',255);
            $table->text('registration_page_header_important_notes',255);
            $table->string('whatsapp_notification_banner_image_dir_url',255);
            $table->string('whatsapp_notification_banner_image',255);
         

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technocon_event');
    }
};
