<?php

use Illuminate\Support\Facades\Route;

Route::get('/autocomplete', \NovaEntitySelectField\Http\Controllers\AutocompleteController::class)
->name('nova-entity-select-field.autocomplete');
