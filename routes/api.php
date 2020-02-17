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

// test
//Route::prefix('v1')->name('api.v1.')->group(function() {
//    Route::get('version', function() {
//        abort(403, 'test');
//        return 'this is version v1';
//    })->name('version');
//});
//
//Route::prefix('v2')->name('api.v2.')->group(function() {
//    Route::get('version', function() {
//        return 'this is version v2';
//    })->name('version');
//});

//Route::prefix('v1')->namespace('Api')
//    ->name('api.v1.')
////    ->middleware('throttle:1,1') // 节流机制,限制调用频率,1 分钟 1 次
//    ->group(function() {
//        // 短信验证码
//        Route::post('verificationCodes','VerificationCodesController@store')
//            ->name('verificationCodes.store');
//        // 用户注册
//        Route::post('users','UsersController@store')->name('users.store');
//});


Route::prefix('v1')
    ->namespace('Api')
    ->name('api.v1.')
    ->group(function () {

        Route::middleware('throttle:' . config('api.rate_limits.sign'))
            ->group(function () {
                // 图形验证码
                Route::post('captchas','CaptchasController@store')
                    ->name('captchas.store');
                // 短信验证码
                Route::post('verificationCodes', 'VerificationCodesController@store')
                    ->name('verificationCodes.store');
                // 用户注册
                Route::post('users', 'UsersController@store')
                    ->name('users.store');
                // 第三方登录
                Route::post('socials/{social_type}/authorizations','AuthorizationsController@socialStore')
                    ->where('social_type','weixin')  // where('social_type', 'weixin|weibo') 参数匹配限制
                    ->name('socials.authorizations.store');
                // 登录
                Route::post('authorizations', 'AuthorizationsController@store')
                    ->name('api.authorizations.store');

                // 刷新token
                Route::put('authorizations/current','AuthorizationsController@update')
                    ->name('authorizations.update');
                // 删除token
                Route::delete('authorizations/current', 'AuthorizationsController@destroy')
                    ->name('authorizations.destroy');
            });

        Route::middleware('throttle:' . config('api.rate_limits.access'))
            ->group(function () {
                // 游客可以访问的接口

                // 某个用户的详情
                Route::get('users/{user}','UsersController@show')
                    ->name('users.show');
                // 分类列表
                Route::get('categories','CategoriesController@index')
                    ->name('categories.index');
                // 话题列表，详情
                Route::resource('topics','TopicsController')->only([
                    'index','show'
                ]);
                // 话题回复列表
                Route::get('topics/{topic}/replies','RepliesController@index')
                    ->name('topics.replies.index');
                // 某个用户发布的话题
                Route::get('users/{user}/topics', 'TopicsController@userIndex')
                    ->name('users.topics.index');
                // 某个用户的回复列表
                Route::get('users/{user}/replies','RepliesController@userIndex')
                    ->name('users.replies.index');

                // 登录后可以访问的接口
                Route::middleware('auth:api')->group(function(){
                    // 当前登录用户的信息
                    Route::get('user','UsersController@me')
                        ->name('user.show');
                    // 上传图片
                    Route::post('images','ImagesController@store')
                        ->name('images.store');
                    // 编辑登录用户信息
                    Route::patch('user','UsersController@update')
                        ->name('user.update');
                    // 发布 更新 删除话题
                    Route::resource('topics','TopicsController')->only([
                        'store', 'update', 'destroy'
                    ]);
                    // 发布回复
                    Route::post('topics/{topic}/replies','RepliesController@store')
                        ->name('topics.replies.store');
                    // 删除回复
                    Route::delete('topics/{topic}/replies/{reply}','RepliesController@destroy')
                        ->name('topics.replies.destroy');
                    // 通知列表
                    Route::get('notifications','NotificationsController@index')
                        ->name('notifications.index');
                });
            });
    });