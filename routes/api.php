<?php

use App\Http\Controllers\OrganizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('api')->group(function () {
    Route::get('buildings', [OrganizationController::class, 'index']);
    Route::get('organizations/search', [OrganizationController::class, 'searchByName']);
    Route::get('organizations/search/by_activity', [OrganizationController::class, 'searchByActivityName']);
    Route::get('organizations/within-radius', [OrganizationController::class, 'withinRadius']);
    Route::get('organizations/{id}', [OrganizationController::class, 'show']);
    Route::get('organizations/by_building/{buildingId}', [OrganizationController::class, 'byBuilding']);
    Route::get('organizations/by_activity/{activityId}', [OrganizationController::class, 'byActivity']);
});
