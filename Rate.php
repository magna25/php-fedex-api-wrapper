<?php 
	namespace System\Shipping\FEDEX;
	
	Class Rate extends Fedex{		
		/*
		 * @method get Rate
		 * @returns object
		 */
		public function getRate(){
			
			$request = $this->getRequest();

			$obj = new Request;
			
			try{
				$response = $obj->sendRequest($request, 'RateService_v16.wsdl', 'getRates', 'crs:16:0:0');
				
				return $response;
			}
			catch (SoapFault $e) {
				throw new \Exception($e->faultstring());
			}
		}
	}