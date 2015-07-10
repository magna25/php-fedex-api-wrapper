<?php 
	namespace Fedex;
	
	Class Returns extends Fedex{
		
		private $rma;
		
		private $reason;
		
		private $recipient;
		
		private $emailLabelExpiration;
		
		private $messageToRecipient = 'Return Label has been sent to you.';
		
		private $merchantPhone;
		
		private $readyTime;
		
		private $latestPickupTime;
		
		private $instructions;
		
		public function rma($val){$this->rma = $val;}
		
		public function reason($val){$this->reason = $val;}
		
		public function labelExpirationDate($val){$this->emailLabelExpiration = $val;}
		
		public function contactPhone($val){$this->merchantPhone = $val;}
		
		public function messageToRecipient($val){$this->messageToRecipient = $val;}
		
		public function readyTime($val){$this->readyTime = $val[0]; $this->latestPickupTime = $val[1];}
		
		public function instructions($val){$this->instructions = $val;}
		
		/*
		 * @method create return label
		 * @returns object
		 */
		public function getReturnLabel(){
			return $this->processReturn('print');
		}
		
		public function emailReturnLabelTo($email){
			$this->recipient = $email;
			return $this->processReturn('email');
		}
		
		public function requestTag(){
			return $this->processReturn('requestTag');
		}
		private function processReturn($type){
			
			$request = $this->getRequest();
			$savePath = $request->savePath;
			
			$labelFormat = $request->labelFormat;
			
			unset($request->savePath, $request->labelFormat);
			
			$obj = new Request;
			
			try{
				if($type == "print"){
					
					$request->RequestedShipment->SpecialServicesRequested = ['SpecialServiceTypes' => ['RETURN_SHIPMENT'], 'ReturnShipmentDetail' => ['Rma' => ['Number' => $this->rma, 'Reason' => $this->reason], 'ReturnType' => 'PRINT_RETURN_LABEL']];

					$validate = $obj->sendRequest($request, 'ShipService_v15.wsdl', 'validateShipment', 'ship:15:0:0');
					
					if($validate->Notifications->Code == "0000"){
						
						$response = $obj->sendRequest($request, 'ShipService_v15.wsdl', 'processShipment', 'ship:15:0:0');
						
						file_put_contents($savePath.'fed-ret'.generateRandomString(5).'.'.$labelFormat, $response->CompletedShipmentDetail->CompletedPackageDetails->Label->Parts->Image);
						
						return $response;
					}
					return $validate;
				}
				else if($type == "email"){
					if(!$this->emailLabelExpiration){
						throw new \Exception('Expiration date for the label is required.');
					}
					$request->RequestedShipment->SpecialServicesRequested = [
						'ShipmentSpecialServiceType' => 'RETURN_SHIPMENT',
						'SpecialServiceTypes' => ['RETURN_SHIPMENT', 'PENDING_SHIPMENT'],
						'EMailNotificationDetail' => [
							'PersonalMessage' => '',
							'Recipients' => [
								'EMailNotificationRecipientType' => 'RECIPIENT',
								'EMailAddress' => $this->recipient,
								'Format' => 'HTML',
								'Localization' => [
									'LanguageCode' => 'EN',
									'LocaleCode' => 'US'
								],
							]
						],	
						'ReturnShipmentDetail' => [
							'ReturnType' => 'PENDING',
							'ReturnEMailDetail' => [
								'MerchantPhoneNumber' => $this->merchantPhone
							]
						],
						'PendingShipmentDetail' => [
							'Type' => 'EMAIL',
							'ExpirationDate' => $this->emailLabelExpiration,
							'NotificationEMailAddress' => '796439@Fedex.com',
							'NotificationMessage' => 'EMAIL TEST MESSAGE'
						]
					];			
					$obj = new Request;
					
						return $obj->sendRequest($request, 'ShipService_v15.wsdl', 'processShipment', 'ship:15:0:0');
				}
				else if($type == "requestTag"){
				
					$request->RequestedShipment->PickupDetail = ['ReadyDateTime' => $this->readyTime, 'LatestPickupDateTime' => $this->latestPickupTime, 'CourierInstructions' => $this->instructions];
					
					$request->RequestedShipment->SpecialServicesRequested = ['SpecialServiceTypes' => 'RETURN_SHIPMENT', 'ReturnShipmentDetail' => ['ReturnType' => 'Fedex_TAG', 'Rma' => ['Number' => $this->rma, 'Reason' => $this->reason]]];
					
					return $obj->sendRequest($request, 'ShipService_v15.wsdl', 'processTag', 'ship:15:0:0');
				}
			}
			catch (SoapFault $e) {
				throw new \Exception($e->faultstring());
			}
		}
	}