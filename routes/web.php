Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

/**
 * Privacy Policy - Static page required for Google Play Store listing.
 * Served as a static HTML file from public/ with a clean URL (no .html extension).
 * URL must remain stable: changing it requires Play Store re-review.
 */
Route::get('/privacy-policy', function () {
    return response()->file(public_path('privacy-policy.html'), [
        'Content-Type' => 'text/html; charset=UTF-8',
        'X-Content-Type-Options' => 'nosniff',
    ]);
})->name('privacy-policy');
