<?php

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

// // remember never use get for sensitive information, just post ... so you don't get hacked
// Route::get("hello",function () {
//     return response()->json(["message"=>"Successfully Connected to the database"]);
// });

// Route::post("register",function (Request $request) {
//     return response()->json($request);
// });


// Authentication routes
Route::prefix('/auth')->group(function(){
    Route::post('register',[UserController::class,'register']);
    // 812750 password
    //3218 pin
    Route::post('checkotp',[UserController::class,'checkOtp']); 
    Route::post('sendotp',[UserController::class,'sendOtpNow']); 
    
});

// protected routes
Route::middleware('auth:sanctum')->prefix('/account')->group(function(){
    
    Route::post('createtxpin',[UserController::class,'createTxPin']); 
});





Route::get('/',function(Request $request){
   
    return User::first();
    
});
