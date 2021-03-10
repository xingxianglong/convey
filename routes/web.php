<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return '这里好像什么都没有!';
});

/* 后台管理 begin */

//上传
Route::post('/Admin/bWQ1X1VwbG9hZC9JbWdfbWQ1','Admin\UploadController@Img')->middleware('checkAdminToken');



//省市区
Route::post('/Admin/bWQ1X1N5c3RlbS9DaXR5L0FjY29yZGluZ1Byb3ZpbmNlR2V0RGF0YV9tZDU=','Admin\System\CityController@AccordingProvinceGetData')->middleware('checkAdminToken');
Route::post('/Admin/bWQ1X1N5c3RlbS9EaXN0cmljdC9BY2NvcmRpbmdDaXR5R2V0RGF0YV9tZDU=','Admin\System\DistrictController@AccordingCityGetData')->middleware('checkAdminToken');



//登录
Route::get('/Admin/bWQ1X0xvZ2luL0luZGV4X21kNQ==','Admin\LoginController@Index');
Route::post('/Admin/bWQ1X0xvZ2luL0xvZ2luVmFsaWRhdGlvbl9tZDU=','Admin\LoginController@LoginValidation');
Route::post('/Admin/bWQ1X0xvZ2luL091dF9tZDU=','Admin\LoginController@Out');



//首页
Route::get('/Admin/bWQ1X0luZGV4L0luZGV4X21kNQ==','Admin\IndexController@Index')->middleware('checkAdminToken');
Route::get('/Admin/Index/Main','Admin\IndexController@Index')->middleware('checkAdminToken');



//管理员
Route::get('/Admin/System/AdministratorRole/Index','Admin\System\AdministratorRoleController@Index')->middleware('checkAdminToken');
Route::post('/Admin/System/AdministratorRole/GetPage','Admin\System\AdministratorRoleController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/System/AdministratorRole/Form/{jump_type}/{id}','Admin\System\AdministratorRoleController@Form')->middleware('checkAdminToken');
Route::post('/Admin/System/AdministratorRole/Add','Admin\System\AdministratorRoleController@Add')->middleware('checkAdminToken');
Route::post('/Admin/System/AdministratorRole/Edit','Admin\System\AdministratorRoleController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/System/AdministratorRole/Delete','Admin\System\AdministratorRoleController@Delete')->middleware('checkAdminToken');


Route::get('/Admin/System/Administrator/Index','Admin\System\AdministratorController@Index')->middleware('checkAdminToken');
Route::post('/Admin/System/Administrator/GetPage','Admin\System\AdministratorController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/System/Administrator/Form/{jump_type}/{id}','Admin\System\AdministratorController@Form')->middleware('checkAdminToken');
Route::post('/Admin/System/Administrator/Add','Admin\System\AdministratorController@Add')->middleware('checkAdminToken');
Route::post('/Admin/System/Administrator/Edit','Admin\System\AdministratorController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/System/Administrator/Delete','Admin\System\AdministratorController@Delete')->middleware('checkAdminToken');
Route::get('/Admin/System/Administrator/FormAccount/{id}','Admin\System\AdministratorController@FormAccount')->middleware('checkAdminToken');
Route::post('/Admin/System/Administrator/EditAccount','Admin\System\AdministratorController@EditAccount')->middleware('checkAdminToken');


//系统配置
Route::get('/Admin/System/Config/Index','Admin\System\ConfigController@Index')->middleware('checkAdminToken');
Route::post('/Admin/System/Config/GetPage','Admin\System\ConfigController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/System/Config/Form/{jump_type}/{id}','Admin\System\ConfigController@Form')->middleware('checkAdminToken');
Route::post('/Admin/System/Config/Add','Admin\System\ConfigController@Add')->middleware('checkAdminToken');
Route::post('/Admin/System/Config/Edit','Admin\System\ConfigController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/System/Config/Delete','Admin\System\ConfigController@Delete')->middleware('checkAdminToken');



//门店
Route::get('/Admin/Store/Store/Index','Admin\Store\StoreController@Index')->middleware('checkAdminToken');
Route::get('/Admin/Store/Store/GetInfo/{id}','Admin\Store\StoreController@GetInfo')->middleware('checkAdminToken');
Route::post('/Admin/Store/Store/GetPage','Admin\Store\StoreController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/Store/Store/Form/{jump_type}/{id}','Admin\Store\StoreController@Form')->middleware('checkAdminToken');
Route::post('/Admin/Store/Store/Add','Admin\Store\StoreController@Add')->middleware('checkAdminToken');
Route::post('/Admin/Store/Store/Edit','Admin\Store\StoreController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/Store/Store/Delete','Admin\Store\StoreController@Delete')->middleware('checkAdminToken');
Route::get('/Admin/Store/Store/Select/{is_radio}','Admin\Store\StoreController@Select')->middleware('checkAdminToken');


Route::get('/Admin/Store/Consultant/Index','Admin\Store\ConsultantController@Index')->middleware('checkAdminToken');
Route::get('/Admin/Store/Consultant/GetInfo/{id}','Admin\Store\ConsultantController@GetInfo')->middleware('checkAdminToken');
Route::post('/Admin/Store/Consultant/GetPage','Admin\Store\ConsultantController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/Store/Consultant/Form/{jump_type}/{id}','Admin\Store\ConsultantController@Form')->middleware('checkAdminToken');
Route::post('/Admin/Store/Consultant/Add','Admin\Store\ConsultantController@Add')->middleware('checkAdminToken');
Route::post('/Admin/Store/Consultant/Edit','Admin\Store\ConsultantController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/Store/Consultant/Delete','Admin\Store\ConsultantController@Delete')->middleware('checkAdminToken');
Route::get('/Admin/Store/Consultant/Select/{is_radio}/{store_id}','Admin\Store\ConsultantController@Select')->middleware('checkAdminToken');


Route::get('/Admin/Store/ConsultantPosition/Index','Admin\Store\ConsultantPositionController@Index')->middleware('checkAdminToken');
Route::post('/Admin/Store/ConsultantPosition/GetPage','Admin\Store\ConsultantPositionController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/Store/ConsultantPosition/Form/{jump_type}/{id}','Admin\Store\ConsultantPositionController@Form')->middleware('checkAdminToken');
Route::post('/Admin/Store/ConsultantPosition/Add','Admin\Store\ConsultantPositionController@Add')->middleware('checkAdminToken');
Route::post('/Admin/Store/ConsultantPosition/Edit','Admin\Store\ConsultantPositionController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/Store/ConsultantPosition/Delete','Admin\Store\ConsultantPositionController@Delete')->middleware('checkAdminToken');



//房源
Route::get('/Admin/House/House/Index/{is_deal}','Admin\House\HouseController@Index')->middleware('checkAdminToken');
Route::post('/Admin/House/House/GetPage','Admin\House\HouseController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/House/House/Form/{jump_type}/{id}/{is_deal}','Admin\House\HouseController@Form')->middleware('checkAdminToken');
Route::post('/Admin/House/House/Add','Admin\House\HouseController@Add')->middleware('checkAdminToken');
Route::post('/Admin/House/House/Edit','Admin\House\HouseController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/House/House/Delete','Admin\House\HouseController@Delete')->middleware('checkAdminToken');


Route::get('/Admin/House/Classify/Index','Admin\House\ClassifyController@Index')->middleware('checkAdminToken');
Route::post('/Admin/House/Classify/GetPage','Admin\House\ClassifyController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/House/Classify/Form/{jump_type}/{id}','Admin\House\ClassifyController@Form')->middleware('checkAdminToken');
Route::post('/Admin/House/Classify/Add','Admin\House\ClassifyController@Add')->middleware('checkAdminToken');
Route::post('/Admin/House/Classify/Edit','Admin\House\ClassifyController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/House/Classify/Delete','Admin\House\ClassifyController@Delete')->middleware('checkAdminToken');


Route::get('/Admin/House/Direction/Index','Admin\House\DirectionController@Index')->middleware('checkAdminToken');
Route::post('/Admin/House/Direction/GetPage','Admin\House\DirectionController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/House/Direction/Form/{jump_type}/{id}','Admin\House\DirectionController@Form')->middleware('checkAdminToken');
Route::post('/Admin/House/Direction/Add','Admin\House\DirectionController@Add')->middleware('checkAdminToken');
Route::post('/Admin/House/Direction/Edit','Admin\House\DirectionController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/House/Direction/Delete','Admin\House\DirectionController@Delete')->middleware('checkAdminToken');


Route::get('/Admin/House/Decorate/Index','Admin\House\DecorateController@Index')->middleware('checkAdminToken');
Route::post('/Admin/House/Decorate/GetPage','Admin\House\DecorateController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/House/Decorate/Form/{jump_type}/{id}','Admin\House\DecorateController@Form')->middleware('checkAdminToken');
Route::post('/Admin/House/Decorate/Add','Admin\House\DecorateController@Add')->middleware('checkAdminToken');
Route::post('/Admin/House/Decorate/Edit','Admin\House\DecorateController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/House/Decorate/Delete','Admin\House\DecorateController@Delete')->middleware('checkAdminToken');


Route::get('/Admin/House/Label/Index','Admin\House\LabelController@Index')->middleware('checkAdminToken');
Route::post('/Admin/House/Label/GetPage','Admin\House\LabelController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/House/Label/Form/{jump_type}/{id}','Admin\House\LabelController@Form')->middleware('checkAdminToken');
Route::post('/Admin/House/Label/Add','Admin\House\LabelController@Add')->middleware('checkAdminToken');
Route::post('/Admin/House/Label/Edit','Admin\House\LabelController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/House/Label/Delete','Admin\House\LabelController@Delete')->middleware('checkAdminToken');


Route::get('/Admin/House/Configuration/Index','Admin\House\ConfigurationController@Index')->middleware('checkAdminToken');
Route::post('/Admin/House/Configuration/GetPage','Admin\House\ConfigurationController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/House/Configuration/Form/{jump_type}/{id}','Admin\House\ConfigurationController@Form')->middleware('checkAdminToken');
Route::post('/Admin/House/Configuration/Add','Admin\House\ConfigurationController@Add')->middleware('checkAdminToken');
Route::post('/Admin/House/Configuration/Edit','Admin\House\ConfigurationController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/House/Configuration/Delete','Admin\House\ConfigurationController@Delete')->middleware('checkAdminToken');


Route::get('/Admin/House/PaymentWay/Index','Admin\House\PaymentWayController@Index')->middleware('checkAdminToken');
Route::post('/Admin/House/PaymentWay/GetPage','Admin\House\PaymentWayController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/House/PaymentWay/Form/{jump_type}/{id}','Admin\House\PaymentWayController@Form')->middleware('checkAdminToken');
Route::post('/Admin/House/PaymentWay/Add','Admin\House\PaymentWayController@Add')->middleware('checkAdminToken');
Route::post('/Admin/House/PaymentWay/Edit','Admin\House\PaymentWayController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/House/PaymentWay/Delete','Admin\House\PaymentWayController@Delete')->middleware('checkAdminToken');


Route::get('/Admin/House/Rent/Index','Admin\House\RentController@Index')->middleware('checkAdminToken');
Route::post('/Admin/House/Rent/GetPage','Admin\House\RentController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/House/Rent/Form/{jump_type}/{id}','Admin\House\RentController@Form')->middleware('checkAdminToken');
Route::post('/Admin/House/Rent/Add','Admin\House\RentController@Add')->middleware('checkAdminToken');
Route::post('/Admin/House/Rent/Edit','Admin\House\RentController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/House/Rent/Delete','Admin\House\RentController@Delete')->middleware('checkAdminToken');


Route::get('/Admin/House/BuildingType/Index','Admin\House\BuildingTypeController@Index')->middleware('checkAdminToken');
Route::post('/Admin/House/BuildingType/GetPage','Admin\House\BuildingTypeController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/House/BuildingType/Form/{jump_type}/{id}','Admin\House\BuildingTypeController@Form')->middleware('checkAdminToken');
Route::post('/Admin/House/BuildingType/Add','Admin\House\BuildingTypeController@Add')->middleware('checkAdminToken');
Route::post('/Admin/House/BuildingType/Edit','Admin\House\BuildingTypeController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/House/BuildingType/Delete','Admin\House\BuildingTypeController@Delete')->middleware('checkAdminToken');


Route::get('/Admin/House/Community/Index','Admin\House\CommunityController@Index')->middleware('checkAdminToken');
Route::get('/Admin/House/Community/GetInfo/{id}','Admin\House\CommunityController@GetInfo')->middleware('checkAdminToken');
Route::post('/Admin/House/Community/GetPage','Admin\House\CommunityController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/House/Community/Form/{jump_type}/{id}','Admin\House\CommunityController@Form')->middleware('checkAdminToken');
Route::post('/Admin/House/Community/Add','Admin\House\CommunityController@Add')->middleware('checkAdminToken');
Route::post('/Admin/House/Community/Edit','Admin\House\CommunityController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/House/Community/Delete','Admin\House\CommunityController@Delete')->middleware('checkAdminToken');
Route::get('/Admin/House/Community/Select/{is_radio}','Admin\House\CommunityController@Select')->middleware('checkAdminToken');


Route::get('/Admin/House/BrandApartment/Index','Admin\House\BrandApartmentController@Index')->middleware('checkAdminToken');
Route::post('/Admin/House/BrandApartment/GetPage','Admin\House\BrandApartmentController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/House/BrandApartment/Form/{jump_type}/{id}','Admin\House\BrandApartmentController@Form')->middleware('checkAdminToken');
Route::post('/Admin/House/BrandApartment/Add','Admin\House\BrandApartmentController@Add')->middleware('checkAdminToken');
Route::post('/Admin/House/BrandApartment/Edit','Admin\House\BrandApartmentController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/House/BrandApartment/Delete','Admin\House\BrandApartmentController@Delete')->middleware('checkAdminToken');



//用户
Route::get('/Admin/User/User/Index','Admin\User\UserController@Index')->middleware('checkAdminToken');
Route::get('/Admin/User/User/GetInfo/{id}','Admin\User\UserController@GetInfo')->middleware('checkAdminToken');
Route::post('/Admin/User/User/GetPage','Admin\User\UserController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/User/User/Detail/{id}','Admin\User\UserController@Detail')->middleware('checkAdminToken');
Route::get('/Admin/User/User/Select/{is_radio}','Admin\User\UserController@Select')->middleware('checkAdminToken');



//预约
Route::get('/Admin/User/Reservation/Index','Admin\User\ReservationController@Index')->middleware('checkAdminToken');
Route::get('/Admin/User/Reservation/GetInfo/{id}','Admin\User\ReservationController@GetInfo')->middleware('checkAdminToken');
Route::post('/Admin/User/Reservation/GetPage','Admin\User\ReservationController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/User/Reservation/Detail/{id}','Admin\User\ReservationController@Detail')->middleware('checkAdminToken');
Route::get('/Admin/User/Reservation/Select/{is_radio}','Admin\User\ReservationController@Select')->middleware('checkAdminToken');



//意见建议
Route::get('/Admin/Advice/Advice/Index','Admin\Advice\AdviceController@Index')->middleware('checkAdminToken');
Route::post('/Admin/Advice/Advice/GetPage','Admin\Advice\AdviceController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/Advice/Advice/Detail/{id}','Admin\Advice\AdviceController@Detail')->middleware('checkAdminToken');



//广告
Route::get('/Admin/Advertising/Banner/Index','Admin\Advertising\BannerController@Index')->middleware('checkAdminToken');
Route::post('/Admin/Advertising/Banner/GetPage','Admin\Advertising\BannerController@GetPage')->middleware('checkAdminToken');
Route::get('/Admin/Advertising/Banner/Form/{jump_type}/{id}','Admin\Advertising\BannerController@Form')->middleware('checkAdminToken');
Route::post('/Admin/Advertising/Banner/Add','Admin\Advertising\BannerController@Add')->middleware('checkAdminToken');
Route::post('/Admin/Advertising/Banner/Edit','Admin\Advertising\BannerController@Edit')->middleware('checkAdminToken');
Route::post('/Admin/Advertising/Banner/Delete','Admin\Advertising\BannerController@Delete')->middleware('checkAdminToken');




/* 后台管理 end */



