<?php
	namespace Fedex;
	
	Class Tracking extends Fedex{
		
		public function track($tracking_num){
			
			$request = new \stdClass;
			
			$request->ProcessingOptions = 'INCLUDE_DETAILED_SCANS';
			
			$request->SelectionDetails = ['PackageIdentifier' => ['Type' => 'TRACKING_NUMBER_OR_DOORTAG','Value' =>  $tracking_num]];
			
			$obj = new Request;
			
			try{
				return $obj->sendRequest($request, 'TrackService_v9.wsdl', 'track', 'trck:9:1:0');
			} 
			catch (SoapFault $e) {
				throw new \Exception($e->faultstring());
			}
			
			return false;
		}
	}