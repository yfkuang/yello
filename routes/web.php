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

Route::get(
    '/', function () {
        return redirect(route('dashboard'));
    }
);

Route::get(
    'dashboard',
    ['as' => 'dashboard',
     'uses' => 'LeadController@dashboard'
    ]
);

Route::get(
    'manage_numbers',
	['as' => 'manage_numbers',
     'uses' => 'LeadSourceController@manage'
    ]
);

Route::resource(
    'available_number', 'AvailableNumberController', ['only' => ['index']]
);

Route::get(
	'handleStatus',
	['as' => 'handleStatus',
	'uses' => 'LeadController@statusDuration'
	]
);

Route::get(
	'test',
	['as' => 'test',
	'uses' => 'LeadController@test'
	]
);

Route::resource(
    'lead', 'LeadController', ['only' => ['store']]
);

/*Firebase SDK*/
Route::get('/phpfirebase_sdk','FirebaseController@index');

/*AJAX Requests*/
Route::post(
	'ajaxRequest',
	['as' => 'ajaxRequest',
     'uses' => 'LeadController@ajaxRequest'
    ]
);

Route::post(
	'ajaxNumbers',
	['as' => 'ajaxNumbers',
     'uses' => 'AvailableNumberController@ajaxNumbers'
    ]
);

Route::post(
    'ajaxStore',
	['as' => 'ajaxStore',
     'uses' => 'LeadSourceController@store'
    ]
);

Route::post(
    'ajaxEdit',
	['as' => 'ajaxEdit',
     'uses' => 'LeadSourceController@edit'
    ]
);

Route::post(
    'ajaxSave',
	['as' => 'ajaxSave',
     'uses' => 'LeadSourceController@update'
    ]
);

Route::post(
    'ajaxDelete',
	['as' => 'ajaxDelete',
     'uses' => 'LeadSourceController@destroy'
    ]
);

Route::post(
    'ajaxLeadSources',
	['as' => 'ajaxLeadSources',
     'uses' => 'LeadSourceController@ajaxManage'
    ]
);