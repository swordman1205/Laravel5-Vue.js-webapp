<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {

    Route::post('register', 'Auth\RegisterController@register');
    Route::post('login', 'Auth\LoginController@login');
});

Route::prefix('companies')->group(function () {
    Route::post('/', 'CompanyController@storeCompany');
    Route::post('register', 'CompanyController@registerCompany');

    Route::middleware('auth')->group(function () {
        Route::get('{company}', 'CompanyController@getCompany');
        Route::put('{company}', 'CompanyController@updateCompany');
        Route::get('{company}/courses', 'CompanyController@getCourses');
        Route::put('{company}/name', 'CompanyController@updateCompanyName');
        Route::put('{company}/address', 'CompanyController@updateCompanyAddress');
        Route::post('{company}/sports', 'CompanyController@addSports');

    });
});

Route::middleware('auth')->prefix('users')->group(function () {
    Route::get('{user}', 'UserController@getUser');
    Route::put('{user}', 'UserController@updateUser');
    Route::put('{user}/password', 'UserController@setPassword');
    Route::put('{user}/email', 'UserController@setEmail');
    Route::put('{user}/update-password', 'UserController@updatePassword');
    Route::put('{user}/update-email', 'UserController@updateEmail');
});

Route::middleware('auth')->group(function () {
    Route::get('/friends', 'UserController@getFriends');
});

Route::middleware('auth')->prefix('courses')->group(function () {
    Route::post('/', 'CourseController@storeCourse');

    Route::middleware('can:update,course')->group(function () {
        Route::put('{course}', 'CourseController@updateCourse');
        Route::put('{course}/dates', 'CourseController@updateCourseDates');
        Route::post('{course}/responsible', 'CourseController@updateStepThree');
        Route::put('{course}/services', 'CourseController@updateStepFour');
        Route::put('{course}/documents', 'CourseController@updateDocuments');
        Route::put('{course}/offer', 'CourseController@updateCourseOffer');
        Route::put('{course}/lesson', 'CourseController@updateCourseLesson');
        Route::put('{course}/subscriptions', 'CourseController@updateCourseSubscriptions');
        Route::put('{course}/lesson-packages', 'CourseController@updateCourseLessonPackages');
        Route::post('{course}/description', 'CourseController@updateCourseDescription');
        Route::put('{course}/active', 'CourseController@activateCourse');
        Route::post('{course}/duplicate', 'CourseController@duplicateCourse');
        Route::post('{course}/duplicateIn/{company}', 'CourseController@duplicateCourseIn');
        Route::delete('{course}', 'CourseController@deleteCourse');
    });
});

Route::middleware('auth')->prefix('reservations')->group(function () {
    Route::post('/', 'ReservationController@storeReservation');
    Route::get('/', 'ReservationController@getReservations');
    Route::put('/{reservation}/confirmations', 'ReservationController@saveConfirmation');
    Route::put('/{reservation}/confirm', 'ReservationController@confirmReservation');
});

Route::get('courses_by_distance', 'CourseController@coursesByDistance');
Route::get('federations', 'FederationController@getFederations');
Route::get('disabilities', 'DisabilityController@getDisabilities');
Route::get('services', 'ServiceController@getServices');
Route::get('documents', 'DocumentController@getAll');
Route::get('{course}/documents_by_course', 'DocumentController@getAllByCourse');
Route::get('subscriptions', 'SubscriptionController@getSubscriptions');
Route::get('subscription-services', 'SubscriptionServiceController@getServices');
Route::get('legal-forms', 'LegalFormController@getLegalForms');
Route::get('typologies', 'TypologyController@getTypologies');

Route::get('google/places', 'GoogleMapsController@autoCompletePlaces');
Route::get('sports/search', 'SportController@searchSports');
Route::get('sports/{sport}', 'SportController@getSport');


Route::post('postBuy', 'CourseController@postBuy')->name('postBuy');
Route::delete('carts/{cartelement}', 'CartController@deleteCartElement');
Route::post('addToCart', 'CartController@addToCart');


Route::post('filterCourses', 'CourseController@filterElements');
Route::post('contactform', 'HomeController@contactform')->name("contactform");

Route::get('courses/search', 'CourseController@search');