<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lead;
use App\LeadSource;
use DB;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\Twiml;
use SimpleXMLElement;
use Carbon\Carbon;

class LeadController extends Controller
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
     * Display a listing of leads
     * @param Request $request
     * @return Response with all found leads
     */
    public function dashboard(Request $request)
    {
        $context = [
            'leadSources' => LeadSource::all(),
			'leads' => Lead::all()
				->sortByDesc('created_at'), 
            'appSid' => $this->_appSid()
        ];
		
        return response()->view('leads.index', $context);
    }
	
	/*Parse Phone numbers into a more user friendly format*/
	public function parseNumber($number){
		$splitNumber = str_split($number);
		$parsedNumber = $splitNumber[0]." ".$splitNumber[1]." (".$splitNumber[2].$splitNumber[3].$splitNumber[4].") ".$splitNumber[5].$splitNumber[6].$splitNumber[7]."-".$splitNumber[8].$splitNumber[9].$splitNumber[10].$splitNumber[11];
		
		return response($parsedNumber);
	}
	
	/**
     * Display listing of leads specified by filter queries, based on AJAX request
     *
     * @return Response with all filtered leads
     */
	public function ajaxRequest(Request $request){
		switch ($request->filter){
			case "date":
				$context = [
					'leadSources' => LeadSource::all(),
					'leads' => Lead::whereDate('created_at', '>=', $request->value)
						->orderBy('created_at', 'desc')
						->get(),
					'switch' => $request->filter
				];
				break;
			
			case "status":
				switch ($request->value) {
					case "completed":
						$context = [
							'leadSources' => LeadSource::all(),
							'leads' => Lead::where('status', 'completed')
								->orderBy('created_at', 'desc')
								->get(),
							'switch' => $request->filter
						];
						break;
					case "no-answer":
						$context = [
							'leadSources' => LeadSource::all(),
							'leads' => Lead::where('status', '!=', 'completed')
								->orderBy('created_at', 'desc')
								->get(),
							'switch' => $request->filter
						];
						break;
				}
				break;
			
			case "leadSource":
				$context = [
					'leadSources' => LeadSource::all(),
					'leads' => Lead::where('lead_source_id', '=', $request->value)
						->orderBy('created_at', 'desc')
						->get(),
					'switch' => $request->filter
				];
				break;
		}
		
       	return response()->json($context);
		//return response()->json($request);
	}

    /**
     * Endpoint which store a new lead with its lead source and forward the call
     *
     * @param  Request $request Input data
     * @return Response Twiml to redirect call to the forwarding number
     */
    public function store(Request $request)
    {
        $leadSource = LeadSource::where(['number' => $request->input('To')])
            ->first();
        $lead = new Lead();
        $lead->leadSource()->associate($leadSource->id);

        $lead->city = $this->_normalizeName($request->input('FromCity'));
        $lead->state = $this->_normalizeName($request->input('FromState'));

        $lead->caller_number = $request->input('From');
        $lead->caller_name = $request->input('CallerName');
        $lead->call_sid = $request->input('CallSid');

        $lead->save();
		
		$whisperString = '<?xml version="1.0" encoding="UTF-8"?>
			<Response>
				<Gather input="dtmf" finishOnKey="1">
					<Say voice="alice" language="en-CA">
						Call tracking by Yello. This call is directed to '.$leadSource->description.'. Press one when you are ready.
					</Say>
				</Gather>
			</Response>';
			
		$whisper = new SimpleXMLElement($whisperString);
		
		$whisper->asXML('xml/'.$lead->lead_source_id.'_whisper.xml');
		
        $forwardMessage = new Twiml();
        $dial = $forwardMessage->dial([
			'action' => route('handleStatus'),//.'?CallSid='.$lead->call_sid,
			'method' => 'GET',
		]);
		$dial->number($leadSource->forwarding_number, [
			'url' => 'http://'.$_SERVER['SERVER_NAME'].'/xml/'.$lead->lead_source_id.'_whisper.xml', //Forwards to whisper
			//'statusCallbackEvent' => 'completed',
			//'statusCallback' => route('handleStatus').'?CallSid='.$lead->call_sid
		]);
		
        return response($forwardMessage, 201)
            ->header('Content-Type', 'application/xml');
    }

    /**
     * Display all lead sources as JSON, grouped by lead source
     *
     * @param  Request $request
     * @return Response
     */
    public function summaryByLeadSource()
    {
        return response()->json(Lead::byLeadSource());
    }

    /**
     * Display all lead sources as JSON, grouped by city
     *
     * @param  Request $request
     * @return Response
     */
    public function summaryByCity()
    {
        return response()->json(Lead::byCity());
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
                "friendlyName" => 'Call tracking app'
            )
        );
        if (count($existingApp)) {
            return $existingApp[0]->sid;
        }

        $newApp = $this->_twilioClient->applications
            ->create('Call tracking app');

        return $newApp->sid;
    }

    private function _normalizeName($toNormalize)
    {
        if (is_null($toNormalize)) {
            return '';
        } else {
            return $toNormalize;
        }
    }
	
	public function statusDuration() {
		$call_sid = $_GET['CallSid'];
		$callStatus = $_GET['DialCallStatus'];
		$callDuration = $_GET['DialCallDuration'];
		/*$sid    = env("TWILIO_ACCOUNT_SID");
		$token  = env("TWILIO_AUTH_TOKEN");
		$twilio = new Client($sid, $token);*/
        $lead = Lead::where('call_sid', $call_sid)
			->get();
		
		//$call = $twilio->calls($call_sid)->fetch();
				
		$lead[0]->duration = $callDuration;//$call->duration;
		$lead[0]->status = $callStatus;//$call->status;
		$lead[0]->save();
		
		$yada = new Twiml();
		return response($yada)
			->header('Content-Type', 'application/xml');
	}
	
	/*Test page for testing whisper*/
	public function test(){
		
		$sid    = env("TWILIO_ACCOUNT_SID");
		$token  = env("TWILIO_AUTH_TOKEN");
		$caller = env("TEST_CALLER");
		$callee = env("TEST_CALLEE");
		$twilio = new Client($sid, $token);
		
		//return $sid.' '.$token;
		$call = $twilio->calls
					   ->create($callee,
								$caller,
								array("url" => "http://demo.twilio.com/docs/voice.xml")
					   );

		return $call->sid;
	}
}
