<?php

Route::get('vursion', function () {
	ob_start();
	phpinfo(1);
	$success = preg_match('/\bphp version ([A-Za-z0-9\.\-\+]*)\b/i', ob_get_contents(), $matches);
	ob_end_clean();

	if ($success) {
		return response()->json($matches[1]);
	}
})->name('vursion')->middleware((version_compare(app()->version(), '5.6.12') >= 0) ? 'signed' : null);
