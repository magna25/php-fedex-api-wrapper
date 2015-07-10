<?php 
	namespace Fedex;
	
	Class Shipping extends Fedex{
		
		/*
		 * @var default expiration date: string
		 */
		private $emailLabelExpiration;
		
		/*
		 * @var defualt message to label receiver: string
		 */
		private  $messageToRecipient = 'A shipping Label has been sent to you.';
		
		/*
		 * @metho set expiration date
		 */
		public function labelExpirationDate($val){$this->emailLabelExpiration = $val;}

		/*
		 * @method send label to customer
		 */
		public function emailLabelTo($email){
						
			$request = $this->getRequest();
			
			if(!isset($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)){
				throw new \Exception('Invalid Email Address');
			}
			
			$request->Actions = ['TRANSFER'];
			
			if(!$this->emailLabelExpiration){
				throw new \Exception('Expiration date for the label is required.');
			}
			
			$request->RequestedShipment->SpecialServicesRequested = [
				'SpecialServiceTypes' => ['PENDING_SHIPMENT'],
				'PendingShipmentDetail' => [
					'Type' => 'EMAIL',
					'ExpirationDate' => $this->emailLabelExpiration,
					'EmailLabelDetail' => [
						'Message' => $this->messageToRecipient,
						'Recipients' => ['EmailAddress' => $email, 'Role' => 'SHIPMENT_COMPLETOR']
					]
				]
			];

			$obj = new Request;
			
			try{
				$response = $obj->sendRequest($request, 'OpenShipService_v7.wsdl', 'createPendingShipment', 'ship:7:0:0');
				
				
				return $response;
			}
			catch (SoapFault $e) {
				throw new \Exception(var_dump($e));
			}
		}
		
		/*
		 * @method initiate ship
		 * @returns object
		 */
		public function getLabel(){
			
			$request = $this->getRequest();
			
			$obj = new Request;
			
			try{
				$validate = $obj->sendRequest($request, 'ShipService_v15.wsdl', 'validateShipment', 'ship:15:0:0');
				
				if($validate->Notifications->Code == "0000"){
					$response = $obj->sendRequest($request, 'ShipService_v15.wsdl', 'processShipment', 'ship:15:0:0');
					
					file_put_contents($request->savePath.'fed'.generateRandomString(5).'.'.$request->labelFormat, $response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image);
					
					return $response;
				}
				return $validate;
			}
			catch (SoapFault $e) {
				throw new \Exception(var_dump($e));
			}
		}
	}