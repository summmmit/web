<?php


Route::group([ 'prefix' => 'user', 'middleware' => 'auth' ], function(){

    /*
     * User Forgot password (get)
     */
    Route::get('/first/page/{userId}', array(
        'as' => 'user-first-page',
        'uses' => 'LoginAndRegisterController@goToAfterSignIn'
    ));
    /*
     * User Forgot password (get)
     */
    Route::get('/home', array(
        'as' => 'user-home',
        'uses' => 'User\UserAccountController@getHome'
    ));
    /*
     * User Recover Account (get)
     */
    Route::get('/account/recover/{code}', array(
        'as' => 'user-account-recover',
        'uses' => 'LoginAndRegisterController@getRecover'
    ));
    /*
     * Validate Admin By school code and teacher code
     */
    Route::Post('/school/validation', array(
        'as' => 'mobile-user-school-validation-post',
        'uses' => 'SchoolController@postValidateSchool'
    ));
    /*
     * User Welcome Settings (get)
     */
    Route::get('/welcome/settings', array(
        'as' => 'user-welcome-settings',
        'uses' => 'User\UserAccountController@getWelcomeSettings'
    ));
    /*
     * Class set Intial Settings (Post)
     */
    Route::Post('/class/set/intial/post', array(
        'as' => 'user-class-set-initial-post',
        'uses' => 'User\UserAccountController@postSetInitial'
    ));
    /*
     * Class set Intial Settings (get)
     */
    Route::get('/class/set/intial', array(
        'as' => 'user-class-set-initial',
        'uses' => 'User\UserAccountController@getSetInitial'
    ));
    /**
     * Get User Profile (get)
     */
    Route::get('/profile', array(
        'as' => 'user-profile',
        'uses' => 'User\UserAccountController@getProfile'
    ));
    /**
     * Get User Details (post)
     */
    Route::Post('/get/details', array(
        'as' => 'user-get-details',
        'uses' => 'User\UserAccountController@postGetDetails'
    ));
    /**
     * Update User Details (post)
     */
    Route::Post('/update/details', array(
        'as' => 'user-update-details',
        'uses' => 'User\UserAccountController@postUpdateDetails'
    ));

});