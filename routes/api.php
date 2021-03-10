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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/* API begin */


//广告
Route::get('/Advertising/Banner/GetList','Api\Advertising\BannerController@GetList');



//房源
Route::get('/House/Classify/GetList','Api\House\ClassifyController@GetList');

Route::get('/House/Direction/GetList','Api\House\DirectionController@GetList');

Route::get('/House/Decorate/GetList','Api\House\DecorateController@GetList');

Route::get('/House/Label/GetList','Api\House\LabelController@GetList');

Route::get('/House/Configuration/GetList','Api\House\ConfigurationController@GetList');

Route::get('/House/PaymentWay/GetList','Api\House\PaymentWayController@GetList');

Route::get('/House/BuildingType/GetList','Api\House\BuildingTypeController@GetList');

Route::get('/House/Rent/GetList','Api\House\RentController@GetList');

Route::get('/House/BrandApartment/GetList','Api\House\BrandApartmentController@GetList');

Route::post('/House/House/GetPage','Api\House\HouseController@GetPage');
Route::post('/House/House/GetInfo','Api\House\HouseController@GetInfo');
Route::post('/House/House/Add','Api\House\HouseController@Add')->middleware('checkApiToken');

Route::get('/House/Community/GetList','Api\House\CommunityController@GetList');
Route::post('/House/Community/GetInfo','Api\House\CommunityController@GetInfo');



//门店
Route::post('/Store/Store/GetInfo','Api\Store\StoreController@GetInfo');
Route::post('/Store/Store/GetLeaseHousePage','Api\Store\StoreController@GetLeaseHousePage');
Route::post('/Store/Store/GetConsultantPage','Api\Store\StoreController@GetConsultantPage');
Route::post('/Store/Store/GetDealHousePage','Api\Store\StoreController@GetDealHousePage');

Route::post('/Store/Consultant/GetInfo','Api\Store\ConsultantController@GetInfo');
Route::post('/Store/Consultant/GetLeaseHousePage','Api\Store\ConsultantController@GetLeaseHousePage');



//用户
Route::post('/User/User/XcxAccredit','Api\User\UserController@XcxAccredit');
Route::post('/User/User/GetInfo','Api\User\UserController@GetInfo')->middleware('checkApiToken');

Route::post('/User/Collect/Update','Api\User\CollectController@Update')->middleware('checkApiToken');
Route::post('/User/Collect/GetPage','Api\User\CollectController@GetPage')->middleware('checkApiToken');

Route::post('/User/Reservation/Add','Api\User\ReservationController@Add')->middleware('checkApiToken');
Route::post('/User/Reservation/GetPage','Api\User\ReservationController@GetPage')->middleware('checkApiToken');

Route::post('/User/Consult/Add','Api\User\ConsultController@Add')->middleware('checkApiToken');
Route::post('/User/Consult/GetPage','Api\User\ConsultController@GetPage')->middleware('checkApiToken');

Route::post('/Advice/Advice/Add','Api\Advice\AdviceController@Add')->middleware('checkApiToken');

Route::post('/User/Reservation/GetConsultantPage','Api\User\ReservationController@GetConsultantPage')->middleware('checkApiToken');


/* API end */




