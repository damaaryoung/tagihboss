<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', 'API\UserController@login');
Route::post('register', 'API\UserController@register');
Route::group(['middleware' => 'auth:api'], function(){
	Route::get('logout', 'API\UserController@logout')->middleware('auth:api');
	Route::post('details', 'API\UserController@details');
	Route::get('user-authenticated', 'API\UserController@getUserLogin')->name('user.authenticated');
	Route::post('user-micro', 'API\UserController@checkUserMicro');
	Route::get('user-lists', 'API\UserController@userLists')->name('user.index');
	Route::put('history', 'API\UserController@userHistory');
	Route::get('roles', 'API\RolePermissionController@getAllRole')->name('roles');
	Route::get('permissions', 'API\RolePermissionController@getAllPermission')->name('permission');
	Route::post('role-permission', 'API\RolePermissionController@getRolePermission')->name('role_permission');
	Route::post('set-role-permission', 'API\RolePermissionController@setRolePermission')->name('set_role_permission');
	Route::post('set-role-user', 'API\RolePermissionController@setRoleUser')->name('user.set_role');
	Route::get('get-next-action', 'API\AssigmentController@setNextActions');
	Route::get('get-case-category', 'API\AssigmentController@setCaseCategory');
	Route::get('get-asset-debitur', 'API\AssigmentController@setAssetDeb');
	Route::get('get-kondisi-pekerjaan', 'API\AssigmentController@setKondisiPekerjaan');
	Route::get('get-getPermission', 'API\DocumentationController@getPermission');
	Route::post('assigment-activity-store', 'API\AssigmentController@assigmentActivityStore');
	Route::post('assigment-visit-a-store', 'API\AssigmentController@assigmentVisitAStore');
	Route::post('assigment-visit-b-store', 'API\AssigmentController@assigmentVisitBStore');
	Route::post('assigment-payment-store', 'API\AssigmentController@assigmentPaymentStore');
	Route::get('assigment-count', 'API\AssigmentController@assigmentCount');
	Route::get('activity-count', 'API\AssigmentController@activityCount');
	Route::get('visit-count', 'API\AssigmentController@visitCount');
	Route::get('payment-count', 'API\AssigmentController@paymentCount');
	Route::get('assigment-chart', 'API\AssigmentController@assigmentChart');
	Route::get('title-infocoll', 'API\InfoCollectionController@titleInfocoll');
	Route::resource('/user', 'API\UserController')->except(['show']);
	Route::resource('/assigment', 'API\AssigmentController')->except(['show']);
	Route::resource('/activity', 'API\ActivityController')->except(['show']);
	Route::resource('/visit-tempat-tinggal', 'API\VisitTempatTinggalController')->except(['show']);
	Route::resource('/visit-jaminan', 'API\VisitJaminanController')->except(['show']);
	Route::resource('/payment', 'API\PaymentController')->except(['show']);
	Route::resource('/notif', 'API\NotifController')->except(['show']);
	Route::resource('/infocoll', 'API\InfoCollectionController')->except(['show']);
	Route::resource('/documentation', 'API\DocumentationController')->except(['show']);
	Route::get('/help-support', 'API\DocumentationController@help');
	Route::get('/help-support-pick/{id}', 'API\DocumentationController@helpPick');
	Route::get('/print/{id}', 'API\PaymentController@print');
});
