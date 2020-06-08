<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;

class MessageController extends Controller
{
    public function index()
    {

    }

    public function create()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        // todo: save message to the database...



        // validate address using lob
        $lob = new \Lob\Lob(env('LOB_PRIVATE_KEY'));

        $verificationResponse = $lob->usVerifications()->verify(array(
            'primary_line' => $request->input('address'),
            'zip_code'     => $request->input('zipcode')
        ));

        $addressPrimaryLine = data_get($verificationResponse, 'primary_line');
        $addressLastLine = data_get($verificationResponse, 'last_line');
        $addressString = $addressPrimaryLine.', '.$addressLastLine;

        $latitude = data_get($verificationResponse, 'components.latitude');
        $longitude = data_get($verificationResponse, 'components.longitude');

        // Get elected officials for a given address from Google Civic API
        $response = $this->fetchElectedOfficials($addressString);

        // Pair the officials with the office they hold in one array
        $electedOfficials = $this->parseOfficialsResponse($response);

        return view('payment', compact('electedOfficials'));
    }

    /**
     * @param string $address
     * @return mixed
     */
    private function fetchElectedOfficials(string $address)
    {
        $apiKey = env('GOOGLE_API_KEY');

        $endpoint = 'https://www.googleapis.com/civicinfo/v2/representatives?key='.$apiKey;

        $response = Http::timeout(15)
            ->get($endpoint . '&address=' . $address)
            ->getBody()
            ->getContents();

        return json_decode($response, true);
    }

    /**
     * @param array $response
     * @return \Illuminate\Support\Collection
     */
    private function parseOfficialsResponse(array $response)
    {
        $offices = collect(data_get($response, 'offices'));
        $officials = collect(data_get($response, 'officials'));

        return $officials->map(function ($official, $index) use ($offices) {
            $officialsIndex = $index;

            $officeOfOfficial = $offices->filter(function($office) use ($officialsIndex) {
                $officialIndices = data_get($office, 'officialIndices');
                return in_array($officialsIndex, $officialIndices);
            })->first();

            // remove and rename "name" key
            $officeOfOfficial['office_name'] = $officeOfOfficial['name'];
            unset($officeOfOfficial['name']);

            return array_merge($official, $officeOfOfficial);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function edit(Message $message)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Message $message)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Message  $message
     * @return \Illuminate\Http\Response
     */
    public function destroy(Message $message)
    {
        //
    }
}
