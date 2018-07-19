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

Route::resource(
    'available_number', 'AvailableNumberController', ['only' => ['index']]
);

Route::resource(
    'lead_source', 'LeadSourceController', ['except' => ['index', 'create', 'show']]
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

/*Route::get(
    'lead/summary-by-lead-source',
    ['as' => 'lead.summary_by_lead_source',
     'uses' => 'LeadController@summaryByLeadSource'
    ]
);

Route::get(
    'lead/summary-by-city',
    ['as' => 'lead.summary_by_city',
     'uses' => 'LeadController@summaryByCity'
    ]
);*/

Route::get(
	'temp',
	function () {
		return view('lead_sources.temp');
	}
);

Route::resource(
    'lead', 'LeadController', ['only' => ['store']]
);

Route::post(
	'ajaxRequest',
	['as' => 'ajaxRequest',
     'uses' => 'LeadController@ajaxRequest'
    ]
);