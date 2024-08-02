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
        Schema::create('technocon_event_program', function (Blueprint $table) {
            $table->id('program_id');
            $table->integer('event_id')->default(0);
            $table->string('program_code',4);
            $table->date('dates',250);
            $table->date('start_dates')->default('2024-01-01');
            $table->string('program_name',100);
            $table->string('program_name_for_certificate',100);
            $table->string('program_name_sms_dlt',29);
            $table->integer('status')->default(1);
            $table->integer('rstatus')->default(1);
            $table->enum('program_type', ['Workshop'])->default('Workshop');
            $table->integer('workshop_duration',50)->default('1');
           
            $table->enum('program_duration_time_unit', ['Hour','Day','Week','Month','Year'])->default('Day');
            
            $table->integer('time_zone_id')->default(79);
            $table->string('registration_no_prefix',255)->default('REG/');
            $table->date('last_date')->default('2024-02-23');
            $table->date('end_dates')->default('2024-01-01');
            $table->time('start_time')->default('09:00:00');
            $table->time('end_time')->default('17:30:00');
            $table->enum('level', ['college','school'])->default('college');
            $table->decimal('fees', total: 10, places: 2);
            $table->integer('currency_id')->default(1);
            $table->enum('fees_inclusive_tax', ['Y','N'])->default('N');
            $table->integer('tax_rate')->default(18);
            $table->integer('payment_gateway_fee_rate')->default(0);
            $table->integer('max_member')->default(1);
            $table->longText('any_special_terms');
            $table->string('certificate_title',50)->default('Certificate of Participation');
            $table->string('online_content',500)->default('na');
            $table->text('content_links');
            $table->string('registration_page_url',255);
            $table->string('registration_page_short_url',255);
            $table->string('registration_page_root_domain',255)->default('technocon.org');
            $table->string('event_website',255)->default('https://technocon.org');
            $table->string('program_details_page_url',255)->default('');
            $table->string('program_details_page_short_url',255)->default('');
            $table->enum('enable_payment_link', ['Y','N'])->default('Y');
            $table->integer('payment_gateway_id')->default(0);
            $table->integer('sms_gateway_id')->default(1);
            $table->integer('smtp_id')->default(1);
            $table->integer('registration_email_template_id')->default(1);
            $table->integer('registration_sms_template_id')->default(1);
            $table->integer('payment_email_template_id')->default(1);
            $table->integer('payment_sms_template_id')->default(1);
            $table->integer('currency_exchange_gateway_id')->default(1);
            $table->string('short_url_domain',255)->default('w.t2k.in');
            $table->string('contact_us_email',255)->default('contact@technocon.org');
            $table->string('contact_us_mobile',255)->default('+91-8744001111');
            $table->string('zoik_app_workshop_list_uid',13)->default('0');
            $table->string('zoik_app_common_list_uid',13)->default('0');
            $table->integer('zoik_app_id')->default(0);
            $table->string('zoik_app_workshop_list_email_field_mapping',50)->default('registered_email_alias');
            $table->string('zoik_app_common_list_email_field_mapping',50)->default('registered_email');
            $table->text('zoik_app_list_fields');
            $table->integer('selection_status_after_registartion')->default(1);
            $table->integer('selection_status_after_payment')->default(6);
            $table->integer('invitation_status_after_registartion')->default(1);
            $table->integer('invitation_status_after_payment')->default(1);
            $table->integer('payment_status_after_registration')->default(0);
            $table->integer('payment_status_after_payment')->default(1);
            $table->string('email_header_banner_url',255)->default('https://technocon.org/images/workshops/tryst-2024-iit-delhi/registration/tryst-2024-iit-delhi-workshop-registration-page-banner.jpg');
            $table->string('email_header_banner_alt',255)->default('Tryst 204 IIT Delhi');
            $table->integer('short_url_api_id')->default(1);
            $table->integer('short_url_channel_id')->default(2);
            $table->integer('whatsapp_api_id')->default(1);
            $table->integer('whatsapp_sender_id')->default(1);
            $table->enum('whatsapp_notification_on_registration', ['Y','N'])->default('Y');
            $table->enum('sms_notification_on_registration', ['Y','N'])->default('Y');
            $table->enum('email_notification_on_registration', ['Y','N'])->default('Y');
            $table->enum('whatsapp_notification_on_payment', ['Y','N'])->default('Y');
            $table->enum('sms_notification_on_payment', ['Y','N'])->default('Y');
            $table->enum('email_notification_on_payment', ['Y','N'])->default('Y');
            $table->integer('whatsapp_template_id_on_registration_success')->default(1);
            $table->integer('whatsapp_template_id_on_payment_success')->default(2);
            $table->enum('enable_gate_pass', ['Y','N'])->default('N');
            $table->integer('enable_gate_pass_on_selection_status_id')->default(-1);
            $table->enum('enable_address_field', ['Y','N'])->default('N');
            $table->integer('enable_address_field_on_selection_status_id')->default(-1);           
            $table->integer('enable_digital_certificate_on_selection_status_id')->default(-1);
            $table->longText('digital_certificate_config_json');
            $table->longText('certificate_record_config_json');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technocon_settings_2024');
    }
};
