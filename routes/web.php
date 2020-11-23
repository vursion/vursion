<?php

Route::get('vursion', function () {
	return response()->json(phpversion());
})->name('vursion')->middleware((version_compare(app()->version(), '5.6.12') >= 0) ? 'signed' : null);
