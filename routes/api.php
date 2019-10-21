<?php


Route::group(['middleware' => 'localization'], function () {

        Route::post('login', 'Auth\LoginController@login');
        
        Route::post('register', 'Auth\RegisterController@register');
        Route::post('recover', 'Auth\ResetPasswordController@recover');
        Route::get('find/{token}', 'Auth\ResetPasswordController@find');
        Route::post('reset', 'Auth\ResetPasswordController@reset');


        Route::group([
            'middleware' => 'auth:api'
        ], function () {
            Route::get('logout', 'Auth\LoginController@logout');

            Route::post('lookup/{user_device}/{checking_method}', 'TACController@lookup');

           // Route::get('datacsv', 'DataInExcelController@DataToCSV');

            Route::put('matched/{imei_number}', 'TACController@matched');
            Route::put('not-matched/{imei_number}', 'TACController@notMatched');

            Route::post('report', 'SurveyDataController@report');

            Route::get('profile', 'Profile\ProfileController@showProfile');
            Route::get('profile/{id}/edit', 'Profile\ProfileController@getProfile');
            Route::put('profile/{id}/edit', 'Profile\ProfileController@editProfile');

            Route::get('profile/{id}/password', 'Profile\ProfileController@getPassword');
            Route::put('profile/{id}/password', 'Profile\ProfileController@editPassword');
            Route::get('datacsv', 'DataInExcelController@csv_export');

            Route::group(['middleware' => 'superadmin'], function () {

                Route::put('activate-staff/{id}', 'SuperAdmin\SuperAdminController@activateStaff');
                Route::put('deactivate-staff/{id}', 'SuperAdmin\SuperAdminController@deactivateStaff');

                Route::get('license-agreements', 'SuperAdmin\LicenseAgreementController@index');
                Route::post('license-agreement', 'SuperAdmin\LicenseAgreementController@store');
                Route::get('license-agreement/{id}', 'SuperAdmin\LicenseAgreementController@show');

                //User Routes for Super Admin
                Route::get('get-users', 'SuperAdmin\UserController@index');
                Route::post('create-user', 'SuperAdmin\UserController@store');
                Route::delete('delete-user/{id}', 'SuperAdmin\UserController@destroy');
                Route::put('update-user/{id}', 'SuperAdmin\UserController@update');
                Route::get('get-user/{id}', 'SuperAdmin\UserController@show');

                Route::get('/activate', 'Auth\ActivationController@activate')->name('auth.activate');

            });

            Route::get('/get-user-info/{id}', 'Auth\LicenseController@getUserCurrentInfo');

            Route::get('/get-user-license/{id}', 'Auth\LicenseController@getRecentLicense');

            Route::put('/update-user-license/{id}', 'Auth\LicenseController@updateLicensedUser');

            Route::put('/update-user-app-license/{id}', 'Auth\LicenseController@updateAppLicenseUser');


            Route::delete('/activity/{id}', 'IMEI\ImController@destroy');


            Route::get('/search_counter/{id}', 'IMEI\CounterfeitController@searchCounterImages');
            Route::get('/users_activity', 'IMEI\UsersActivitiesController@users_activity');
            Route::post('/search_users_activity', 'IMEI\UsersActivitiesController@search_users_activity');


            /*****************************************************************
             **************** Super Admin & Admin Routes *********************
             ****************************************************************/
            Route::group(['middleware' => 'role:superadmin'], function () {

                Route::group(['middleware' => 'role:admin'], function () {
                    //All Users Activities
                    Route::get('datatable/users-activity', 'DataTable\UserActivityController@getRecords');


                    //All System Feedbacks
                    Route::get('datatable/feedback', 'DataTable\FeedbackController@getRecords');

                    //All User Licenses
                    Route::get('datatable/licenses', 'DataTable\LicensesController@getRecords');


                    Route::get('/user-licenses/{id}', 'Auth\LicenseController@getUserLicenses');

                    Route::get('/get_users_activity', 'IMEI\UsersActivitiesController@get_users_activity');
                });
            });


            Route::group(['middleware' => 'staff'], function () {
                //Feedback Route
                Route::post('feedback', 'FeedbackController@giveFeedback');
            });

        });


});
