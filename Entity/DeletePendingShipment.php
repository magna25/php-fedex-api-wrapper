<?php 
	namespace Fedex\Entity;
	
	use Fedex\Fedex;
	use Fedex\Request;
	
	class DeletePendingShipment extends Fedex{
		/*
		*@method - initiate delete request
		*/
		public function delete($track_num, $serviceType){
			
			$request = new \stdClass;

			$request->TrackingIds = ['TrackingIdType' => $serviceType, 'TrackingNumber' => $track_num];
			
			$obj = new Request;
			
			try{
				$response = $obj->sendRequest($request, 'OpenShipService_v7.wsdl', 'deletePendingShipment', 'ship:7:0:0');
				
				return $response;
			}
			catch (SoapFault $e) {
				throw new \Exception(var_dump($e));
			}
		}
		
	}