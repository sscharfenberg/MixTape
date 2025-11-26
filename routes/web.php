<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SpaController;

// add api routes.
require("api.php");

// default route if no other route get matched
Route::any('/{any}', [SpaController::class, "show"])
    ->where('any', '.*');

