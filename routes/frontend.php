<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\RegistrationController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\WebhookController;


Route::get('/event/registration/{seo_handle}', [RegistrationController::class, 'seo_handle_index']);
Route::get('/event/registration/{seo_handle}/{workshop_code}', [RegistrationController::class, 'workshop_code_handle_index']);


Route::get('/event/registered/{seo_handle}/view', [RegistrationController::class, 'registered']);
Route::get('/event/shipping-address-view', [RegistrationController::class, 'shippingAddressView']);
Route::get('/event/edit-registration', [RegistrationController::class, 'editRegistration']);
Route::post('/payment/store', [RegistrationController::class, 'savePayment'])->name('payment.store');


Route::get('/event/program-login/{seo_handle}', [RegistrationController::class, 'login']);
Route::post('/event/do_login', [RegistrationController::class, 'do_login']);
Route::post('/event/forget-email', [RegistrationController::class, 'forgetEmail']);


Route::post('/webhook/{webhook_handle}', [WebhookController::class, 'index']);

Route::post('/inbound-whatsapp-webhook', [WebhookController::class, 'inBoundWhatsappWebhook']);




// Ajax Route
Route::group(['prefix' => 'ajax'], function () {
    Route::get('get-seat', [AjaxController::class, 'getSeat'])->name('ajax.get-seat');  
    Route::post('add-student-registration', [AjaxController::class, 'addStudentRegistration'])->name('ajax.add-student-registration');
    Route::post('update-shipping-address', [AjaxController::class, 'updateShippingAddress'])->name('ajax.update-shipping-address');
    Route::post('update-registration', [AjaxController::class, 'updateRegistration'])->name('update-registration');
});