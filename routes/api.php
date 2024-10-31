<?php

use App\Models\Client; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\RateReviewAndCommentController;
use App\Http\Controllers\SmartphoneController;
use App\Models\RateReviewAndComment;
use App\Models\Smartphone;

//Get user by index
Route::get('/client', [ClientController::class, 'index']);

//Store user data
Route::post('/client',[ClientController::class,'store']);

//allow to use GET,HEAD,POST,PUT,DELETE
Route::apiResource('client', ClientController::class);

//Show client by id
Route::get('/client/{id}',[ClientController::class,'show']);

//Update client data
Route::put('/client/{id}',[ClientController::class,'update']);

//Delete client data
Route::delete('/client/{id}',[ClientController::class,'destroy']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//post login user data
Route::post('/login', [ClientController::class, 'login']);
//get login user data
Route::get('/login', [ClientController::class, 'index']);

//Get smartphone by index
Route::get('/smartphone', [SmartphoneController::class, 'index']);
//Store smartphone data
Route::post('/smartphone',[SmartphoneController::class,'store']);
//allow to use GET,HEAD,POST,PUT,DELETE
Route::apiResource('smartphone', SmartphoneController::class);
//Show smartphone by id
Route::get('/smartphone/{id}',[SmartphoneController::class,'show']);
//Update smartphone data
Route::put('/smartphone/{id}',[SmartphoneController::class,'update']);
//Delete smartphone data
Route::delete('/smartphone/{id}',[SmartphoneController::class,'destroy']);


Route::get('/client/username/{username}', [ClientController::class, 'checkUsername']);
Route::post('/client/{username}', [ClientController::class, 'client']);

Route::get('/client/email/{email}', [ClientController::class, 'checkEmail']);

Route::apiResource('review', RateReviewAndCommentController::class);
Route::post('/review', [RateReviewAndCommentController::class, 'store']);
Route::get('/review/{id}', [RateReviewAndCommentController::class, 'show']);


Route::post('/reviews', [RateReviewAndCommentController::class, 'store']);
Route::get('/reviews', [RateReviewAndCommentController::class, 'index']);
Route::get('/reviews/{id}', [RateReviewAndCommentController::class, 'show']);
Route::delete('/reviews/{id}', [RateReviewAndCommentController::class, 'destroy']);
Route::put('/reviews/{id}', [RateReviewAndCommentController::class, 'update']);

Route::get('/smartphone/{id}/ratings', [SmartphoneController::class, 'getRatings']);

Route::get('/reviews/{smartphone_id}', [SmartphoneController::class, 'getReviewsBySmartphoneId']);
Route::get('/reviews/{smartphone_id}', [RateReviewAndCommentController::class, 'getReviewsBySmartphoneId']);