<?php

use App\Http\Controllers\WesternCallController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
   // return view('welcome');
//    $collection = updateCallData(41);
//    $response = json_decode($collection->response);
//  $response->uuid;
// return $collection;
    // Example usage
// $phoneNumber = '+44 20 7946 0958'; // Example UK number
// $cleaned = removeCountryCode($phoneNumber);
// echo "Cleaned Phone Number: " . $cleaned; // Output will be: "20 7946 0958"

});

Route::prefix('western')->group(function () {
    Route::get('/system-update', [WesternCallController::class, 'systemUpdate']);
    Route::post('/send-call', [WesternCallController::class, 'sendCall']);
    Route::post('/callback-call', [WesternCallController::class, 'callbackCall']);

    Route::get('/call-status-update', [WesternCallController::class, 'CallStatusUpdate'])->name('call.status.update');
    Route::get('/info-send-gohighlevel', [WesternCallController::class, 'infoSendGohighlevel'])->name('info.send.gohighlevel');
});
