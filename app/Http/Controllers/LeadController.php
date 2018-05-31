<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Lead;
use App\LeadSource;
use DB;
use Illuminate\Http\Request;
use Twilio\Rest\Client;
use Twilio\Twiml;

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
            'appSid' => $this->_appSid()
        ];

        return response()->view('leads.index', $context);
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
			
        $forwardMessage = new Twiml();
        $dial = $forwardMessage->dial();
		$dial->number($leadSource->forwarding_number, ['url' => 'http://phplaravel-73309-509403.cloudwaysapps.com/whisper?desc='.$leadSource->description]);//Forwards to whisper

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
	
	/*Creates an XML file which Twilio whispers to callee*/
	public function whisper(){
		$desc = $_GET["desc"];
		$whisperMessage = new Twiml();
		
		$whisperMessage->say('Call tracking by Yello. This call is directed to '.$desc, ['voice' => 'alice', 'language' => 'en-CA']);
		
		return response($whisperMessage, 201)
           	->header('Content-Type', 'application/xml');
	}
}
