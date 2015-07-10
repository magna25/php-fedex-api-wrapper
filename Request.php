<?php 
	namespace Fedex;
	
	class Request extends Authentication{
		
		/*
		*@method send soap request
		*@ returns object
		*/
		public function sendRequest($request, $wsdl_file, $service, $version){	
			$version = explode(':', $version);
			
			$request->WebAuthenticationDetail = $this->authenticate();
			$request->ClientDetail = $this->clientDetails();
			$request->Version = ['ServiceId' => $version[0], 'Major' => $version[1], 'Intermediate' => $version[2], 'Minor' => $version[3]];

			$client = new \SoapClient($_SERVER['DOCUMENT_ROOT'].'/System/Shipping/Fedex/wsdl/'.$wsdl_file, ['trace' => 1, 'cache_wsdl' => WSDL_CACHE_NONE]);
			$response = $client->$service($request);
			
			return $response;
		}
	}