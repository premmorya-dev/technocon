<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\CommandRegistrationWhatsapp\RegistrationWhatsapp;
use App\Console\CommandRazorpayWebhook\RazorpayWebhook;
use App\Console\CommandSendPaymentSuccessEmail\SendPaymentSuccessEmail;
use App\Console\CommandPaymentSuccessWhatsapp\PaymentSuccessWhatsapp;
use App\Console\CommandBulkRegistrationWhatsapp\BulkRegistrationWhatsapp;
use App\Console\CommandSubscribedWhatsapp\SubscribedWhatsapp;

class Kernel extends ConsoleKernel
{

    protected $commands = [
        RegistrationWhatsapp::class,
        RazorpayWebhook::class,
        SendPaymentSuccessEmail::class,
        PaymentSuccessWhatsapp::class,
        BulkRegistrationWhatsapp::class,
        SubscribedWhatsapp::class,
      
    ];


    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/CommandSendEmail');
        $this->load(__DIR__.'/CommandZoikCommonListSync');
        $this->load(__DIR__.'/CommandZoikmailProgramListSync');
        $this->load(__DIR__.'/CommandRegistrationWhatsapp');
        $this->load(__DIR__.'/CommandRazorpayWebhook');
        $this->load(__DIR__.'/CommandBulkRegistrationWhatsapp');
        $this->load(__DIR__.'/CommandSubscribedWhatsapp');




        require base_path('routes/console.php');
    }
}
