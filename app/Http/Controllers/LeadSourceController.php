<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\LeadSource;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Twilio\Rest\Client;

class LeadSourceController extends Controller
{

    /**
     * Twilio Client
     */
    protected $_twilioClient;

    public function __construct(Client $twilioClient)
    {
        $this->_twilioClient = $twilioClient;
    }
	
	/**
     * Display a listing of lead sources
     * @param Request $request
     * @return Response with all found lead sources
     */
	public function manage(Request $request)
    {
		$token = session('token');
		
		if(!$token){
			return redirect()->route('index');
		} else {
			$uid = FirebaseController::verifyToken($token);
			$user = User::where('firebase_id', '=', $uid)->first();
			
			$context = [
				'leadSources' => LeadSource::where('lead_sources.user_id', '=', $user->id)
					->get(),
				'appSid' => $this->_appSid(),
				'uid' => $uid,
				'token' => $token
				
			];

			return response()->view('lead_sources.index', $context);
		}
    }
	
	/**
     * Display a listing of lead sources
     * @param Request $request
     * @return Response with all found lead sources
     */
	public function ajaxManage(Request $request)
    {
		$token = session('token');
		$uid = FirebaseController::verifyToken($token);
		$user = User::where('firebase_id', '=', $uid)->first();
        $context = [
            'leadSources' => LeadSource::where('lead_sources.user_id', '=', $user->id)
					->get(),
            'appSid' => $this->_appSid(),
        ];
		
        return response()->view('lead_sources.leadsources', $context);
    }
	
    /**
     * Store a new lead source (i.e phone number) and redirect to edit
     * page
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $appSid = $this->_appSid();
		$token = session('token');
		$uid = FirebaseController::verifyToken($token);
		$user = User::where('firebase_id', '=', $uid)->first();

        $phoneNumber = $request->number;

        $this->_twilioClient->incomingPhoneNumbers
            ->create(
                [
                    "phoneNumber" => $phoneNumber,
                    "voiceApplicationSid" => $appSid,
                    "voiceCallerIdLookup" => true
                ]
            );

        $leadSource = new LeadSource(
            [
                'number' => $phoneNumber,
				'user_id' => $user->id
            ]
        );
        $leadSource->save();

		return response()->json($leadSource);
    }

    /**
     * Show the form for editing a lead source
     *
     * @param  int $id
     * @return Response
     */
    public function edit(Request $request)
    {
		$leadSource = LeadSource::find($request->id);
		
		return response()->json($leadSource);
        /*return response()->view(
            'lead_sources.edit',
            $content
        );*/
    }

    /**
     * Update the lead source in storage.
     *
     * @param  Request $request
     * @param  int $id
     * @return Response
     */
    public function update(Request $request)
    {
        $this->validate(
            $request,
            [
                'forwarding_number' => 'required',
                'description' => 'required',
				'id' => 'required'
            ]
        );

        $leadSourceToUpdate = LeadSource::find($request->id);
        $leadSourceToUpdate->fill($request->all());
        $leadSourceToUpdate->save();

        return response()->json('ye boi');
    }

    /**
     * Remove the lead source from storage and release the number
     *
     * @param  int $id
     * @return Response
     */
    public function destroy(Request $request)
    {
        $leadSourceToDelete = LeadSource::find($request->id);
        $phoneToDelete = $this->_twilioClient->incomingPhoneNumbers
            ->read(
                [
                    "phoneNumber" => $leadSourceToDelete->number
                ]
            );

        if ($phoneToDelete) {
            $phoneToDelete[0]->delete();
        }
        $leadSourceToDelete->delete();

        return response()->json('ye boi');
    }
	
    /**
     * The Twilio TwiML App SID to use
     * @return string
     */
    private function _appSid()
    {
        $appSid = config('app.twilio')['TWILIO_APP_SID'];
        if (isset($appSid)) {
            return $appSid;
        }

        return $this->_findOrCreateCallTrackingApp();
    }

    private function _findOrCreateCallTrackingApp()
    {
        $existingApp = $this->_twilioClient->applications->read(
            array(
                "friendlyName" => 'Yello | Call Tracking App'
            )
        );
        if ($existingApp) {
            return $existingApp[0]->sid;
        }

        $newApp = $this->_twilioClient->applications
            ->create('Yello | Call Tracking App');

        return $newApp->sid;
    }
}
