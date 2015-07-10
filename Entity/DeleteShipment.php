<?php 
	namespace Fedex\Entity;
	
	use Fedex\Fedex;
	use Fedex\Request;
	
	class DeleteShipment extends Fedex{
		
		/*
		*@var - delete type: DELETE_ONE_PACKAGE, DELETE_ALL_PACKAGES, DELETE_ENTIRE_CONSOLIDATION, LEGACY
		*/
		private $deleteType = 'DELETE_ALL_PACKAGES';
		
		/*
		*@method - set delete type
		*/
		public function deleteType($var){$this->deleteType = $var;}
		
		/*
		*@method - initiate delete request
		*/
		public function delete($track_num, $serviceCode){
			
			$request = new \stdClass;

			$request->ShipTimeStamp = date('c');
			$request->TrackingId = ['TrackingIdType' => $this->getServiceType($serviceCode), 'TrackingNumber' => $track_num];
			$request->DeletionControl = $this->deleteType;
			
			$obj = new Request;
			
			try{
				$response = $obj->sendRequest($request, 'ShipService_v15.wsdl', 'deleteShipment', 'ship:15:0:0');
				
				return $response;
			}
			catch (SoapFault $e) {
				throw new \Exception($e->faultstring());
			}
		}
		
	}