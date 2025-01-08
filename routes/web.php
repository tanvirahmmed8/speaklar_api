<?php

use App\Http\Controllers\PowerinaiController;
use App\Http\Controllers\WesternCallController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
     return view('welcome');
//   $collection = updateCallData(41);
//   $collection = updateCallDataPai(10);
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
    Route::get('/', [WesternCallController::class, 'index']);
    Route::post('/send-call', [WesternCallController::class, 'sendCall']);
    Route::post('/callback-call', [WesternCallController::class, 'callbackCall']);
});

Route::prefix('powerinai')->group(function () {
    Route::get('/system-update', [PowerinaiController::class, 'systemUpdate']);
    Route::get('/', [PowerinaiController::class, 'index']);
    Route::post('/send-call', [PowerinaiController::class, 'sendCall']);
    Route::post('/callback-call', [PowerinaiController::class, 'callbackCall']);
});
