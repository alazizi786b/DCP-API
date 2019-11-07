<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
//use App\Http\Requests\LoginRequest;
 
// /use GuzzleHttp\Client;

class GuzzleController extends Controller
{
    //public function guxxle(){
    	//$client = new \GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000/api/']);
// Send a request to https://foo.com/api/test
//$response = $client->request('GET', 'datacsv');
			// [
			// 	'headers' => [
			// 		'Authorisation' => $encrypted,
			// 		'Content-Type'  => 'application/json'
			// 	],
			// 	'json'    => [
			// 		'deviceId' => $input,
			// 		'portName' => $wco_port_name,
			// 		'country'  => $wco_country,
			// 		'portType' => $wco_port_type
			// 	]
			// ]
		//);
		//$client = new \GuzzleHttp\Client(['base_uri' => 'http://127.0.0.1:8000/api/']);
// Send a request to https://foo.com/api/test
		//$response = $client->request('GET', 'datacsv');

       //dd($response);
// 		$url = 'http://127.0.0.1:8000/api/';
// $postString = 'datacsv';


   //try {
//     $client = new \GuzzleHttp\Client();
// // Send a request to https://foo.com/api/test
// 		$result = $client->get('http://127.0.0.1:8000/api/datacsv', [
//     'headers'        => ['Accept' => 'application/json', 
//                          'Content-Type' => 'application/json',
//                          'Authorization' => 'Bearer {{token}}'  ],
//     'decode_content' => true
// ]);
// 		dd($result);
    //]);
//} catch (\GuzzleHttp\Exception\RequestException $e) {
    //$result = $e->getResponse();
//
//var_export($result->getStatusCode());
//var_export($result->getBody());

	//	return array($response );

		/********************************/

            
//$client  = new \GuzzleHttp\Client();
// $request = $client->post('http://127.0.0.1:8000/api/login', [
//     'form_params' => [
//         'email' => request()->email,
//         'password' => request()->password,

        
//     ],
//     'headers' =>[
//          'Content-Type' => 'application/json',
//          'Accept'  => 'application/json',
//          'X-Requested-With' => 'XMLHttpRequest',
//          'oauth_clients' => [
//         	    'id'  => '3',
//         'secret' => 'sPH5PcGSP34swgDlJe6hSYuK0FJhIbdo0dMP5t1Q',
//         'redirect' => 'http://localhost',
//         ],
           
//     ]
// ]);


/******************************************************/

// $response = $client->request('POST', 'http://127.0.0.1:8000/api/login', [
// 	'headers' => [
//     	'Content-Type' => 'application/json',
//     	'Accept' => 'application/json',
//     	'X-Requested-With' => 'XMLHttpRequest',
//     ],
//     'Body' => ['raw'  =>[
//         'email' => "admin@3gca.org",
//         'password' => "Admin@1234",
//         ],
        
//     ],

// ]);
// dd($response);

/******************************************************/

//dd($request);
// try {
// 	//dd($request);
//     $response = $client->send( $request );
// } catch (\GuzzleHttp\Exception\ClientException $e) {
//     echo 'Caught response: ' . $e->getResponse()->getStatusCode();
//     echo getBody();
// 		/********************************/
//     }
// }
}
