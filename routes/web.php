<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');
Route::get('/come-funziona', 'HomeController@index')->name("index.howworks");
Route::get('home', 'HomeController@index')->name('home');
Route::get('chisiamo', 'HomeController@about')->name('about');
Route::get('contatti', 'HomeController@contactUs')->name('contact_us');
Route::get('privacy', 'HomeController@privacy')->name('privacy');
Route::get('terms', 'HomeController@terms')->name('terms');

Route::get('verify-user', 'Auth\RegisterController@verifyUser')->name('register.verify');

Route::middleware('auth')->get('profile/', 'UserController@showProfile')->name('profile');

Route::middleware('auth')->get('societa_sportive/', 'CompanyController@index');
Route::middleware('auth')->get('societa_sportive/{companySlug}/dashboard', 'CompanyController@dashboard')->name('companies.dashboard')->middleware('can:view,companySlug');
Route::get('societa_sportive/{companySlug}', 'CompanyController@companyInfo')->name('company.show');


Route::get('societa-sportive', function () {
    return view('companies.landing');
})->name('companies.landing');

Route::get('corsi/search', 'CourseController@search');
Route::get('corsi/create', 'CourseController@create')->name('courses.create');
Route::get('corsi/{courseSlug}', 'CourseController@show')->name('courses.show');

Route::get('corsi/{courseSlug}/edit', 'CourseController@edit')->middleware('can:update,courseSlug');

Route::get('reservations/{reservation}/confirmations', 'ReservationController@showConfirmation');
Route::get('reservations/{reservation}/confirm', 'ReservationController@showConfirm');

Auth::routes();

Route::get('/facebook-redirect', 'SocialAuthController@facebookRedirect');
Route::get('/facebook-callback', 'SocialAuthController@facebookCallback');
Route::get('/google-redirect', 'SocialAuthController@googleRedirect');
Route::get('/google-callback', 'SocialAuthController@googleCallback');


Route::get('{purchase}/summary', 'CartController@summaryIndex')->name('summary');

Route::get('/search-results', 'SeoDispatcherController@showSearchResults');

Route::middleware('auth')->resource('carts', 'CartController');

// REDIRECTS
Route::get("/societa/{old}", 'SeoDispatcherController@redirectCompany');
Route::get("/corso/{old}", 'SeoDispatcherController@redirectCourse');
Route::get('courses/{courseSlug}', function ($course) {
    return redirect('corsi/' . $course->slug);
});
Route::get('companies/{companySlug}', function ($company) {
    return redirect('societa_sportive/' . $company->slug);
});

Route::post("/unbounce", 'UnbounceController@saveDataFromUnbounce')->name('unbounce');

/**
 * SEO ROUTES
 */
Route::get('/sport/{name}', 'SeoDispatcherController@getCoursesBySportPage')->name('seo.sport');
Route::get('/comune/{name}', 'SeoDispatcherController@getCoursesByCity')->name('seo.comune');
Route::get('/sport/{sportname}/comune/{cityName}', 'SeoDispatcherController@getCoursesBySportAndCityPage')->name('seo.comune_sport');

Route::get('{targa}', 'SeoDispatcherController@getCoursesByProvince')->where([
    'targa' => '[\w-]+'
]);
Route::get('{targa}/{comune}/{istat}', 'SeoDispatcherController@getComuneAndAll')->where([
    'targa' => '[\w-]+',
    'comune' => '[\w-]+',
    'istat' => '[\d-]+']);
Route::get('{targa}/{comune}/{istat}/{descrKey}', 'SeoDispatcherController@getSportAndAll')->where([
    'targa' => '[\w-]+',
    'comune' => '[\w-]+',
    'descrKey' => '[\w-]+',
    'istat' => '[\w-]+'
]);
Route::get('{targa}/{comune}/{istat}/{categoria}/{descrKey}', 'SeoDispatcherController@getSportAndCategoryAndAll')->where([
    'targa' => '[\w-]+',
    'comune' => '[\w-]+',
    'descrKey' => '[\w-]+',
    'categoria' => '[\w-]+',
    'istat' => '[\d-]+'
]);
Route::get('{targa}/{comune}/{istat}/{categoria}/{descrKey}/eta{da_eta}-{a_eta}anni', 'SeoDispatcherController@getSportAndCategoryAndAgeAndAll')->where([
    'targa' => '[\w-]+',
    'comune' => '[\w-]+',
    'descrKey' => '[\w-]+',
    'categoria' => '[\w-]+',
    'istat' => '[\d-]+',
    'da_eta' => '[\d-]+',
    'a_eta' => '[\d-]+'
]);
