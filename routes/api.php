<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\User;
use App\Http\Controllers\UserController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//this sanctum update protects the user branch, so no need for file manipulating

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// due to the sanctum auth, we need to send a token to the backend which is being done in the flutter app through inteerceptors

// // remember never use get for sensitive information, just post ... so you don't get hacked
// Route::get("hello",function () {
//     return response()->json(["message"=>"Successfully Connected to the database"]);
// });

// Route::post("register",function (Request $request) {
//     return response()->json($request);
// });


// Authentication routes
Route::prefix('/auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    // 195027 password
    //3218 pin
    Route::post('checkotp', [AuthController::class, 'checkOtp']);
    Route::post('sendotp', [AuthController::class, 'sendOtpNow']);
    // Route::post('verifybvn', [UserController::class, 'verifyBVN']);
});

// protected routes
Route::middleware('auth:sanctum')->prefix('/account')->group(function () {

    Route::post('createtxpin', [UserController::class, 'createTxPin']);
    Route::post('verifybvn', [UserController::class, 'verifyBVN']);
    Route::post('SendSmsOtp', [UserController::class, 'sendSmsOtp']);
});



// check the otps model


Route::get('/', function (Request $request) {

    return User::first();
});
