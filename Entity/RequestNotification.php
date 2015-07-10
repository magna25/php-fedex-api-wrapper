<?php 
	namespace System\Shipping\FEDEX\Entity;
	
	use System\Shipping\FEDEX\Fedex;
	use System\Shipping\FEDEX\Request;
	
	class RequestNotification extends Fedex{
		
		/*
		*@var - email receivers
		*/
		private $recipients = [];
		
				/*
		*@var - email receivers
		*/
		private $messageToRecipient = 'Tracking Details';
		
		/*
		*@method - sender detail
		*/
		private $senderDetail;
		
		/*
		*@method - set messageToRecipient
		*/
		public function messageToRecipient($var){$this->messageToRecipient = $var;}
		
		/*
		*@method - set sender detail
		*/
		public function senderDetail($var){$this->senderDetail = (object) $var;}
		
		/*
		*@method - set recipients
		*/
		public function recipients($emails){
			$emails = explode(",", $emails);
			
			foreach($emails as $email){
				$this->recipients = [
						'EMailNotificationRecipientType' => 'RECIPIENT',
						'EMailAddress' => $email,
						'NotificationEventsRequested' => ['ON_DELIVERY', 'ON_EXCEPTION', 'ON_SHIPMENT'],
						'Format' => 'HTML'
				];
			}
		}
		
		/*
		*@method - sendNotifications
		*/
		public function sendNotification($trackingNumber){
			if(!$this->senderDetail){
				throw new \Exception("Sender detail is required. Format: array('Email' => 'SENDER_EMAIL', 'NAME' => 'SENDER_NAME')");
			}
			else if(!$this->recipients){
				throw new \Exception('At least one recipient email address is required');
			}
			
			$request = new \stdClass;
			
			$request->Version = ['ServiceId' => 'trck', 'Major' => '9', 'Intermediate' => '1', 'Minor' => '0'];
			$request->TrackingNumber = $trackingNumber;
			//$request->ShipDateRangeBegin = ''; // Replace with ship date begin
			//$request->ShipDateRangeEnd = ''; // Replace with ship date end
			$request->SenderEMailAddress = $this->senderDetail->Email;
			$request->SenderContactName = $this->senderDetail->Name;
			$request->NotificationDetail = [
				'PersonalMessage' => $this->messageToRecipient,
				'Recipients' => $this->recipients
			];
			
			$obj = new Request;
			
			try{
				$response = $obj->sendRequest($request, 'TrackService_v9.wsdl', 'sendNotifications');
				
				return $response;
			}
			catch (SoapFault $e) {
				throw new \Exception($e->faultstring());
			}
		}
		
	}