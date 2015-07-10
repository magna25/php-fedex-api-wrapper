<?php 
	namespace System\Shipping\FEDEX\Entity;
	
	use System\Shipping\FEDEX\Fedex;
	use System\Shipping\FEDEX\Request;
	
	class RequestProofOfDelivery extends Fedex{
		
		/*
		*@var document type
		*/
		private $documentType = 'PDF';
		
		private $savePath = 'labels/';
		
		/*
		*@var consignee
		*/
		private $consignee;
		
		private $additionalComments;
		
		private $faxSender;
		
		private $faxRecipient;
		
		private $trackingNumber;
		/*
		*@method - set proof type
		*/
		public function proofType($var){$this->proofType = $var;}
		
		/*
		*@method - set document type
		*/
		public function documentType($var){$this->documentType = $var;}
		
		/*
		*@method - set additionalComments
		*/
		public function additionalComments($var){$this->additionalComments = $var;}
		
		/*
		*@var document type
		*/
		public function consignee($arr){
			$arr = array_map('trim', explode(",", $arr));
			$companyName = isset($arr['CompanyName']) ? $arr['CompanyName'] : null;
			
			$this->consigne = [
				'Contact' => [
					'PersonName' => $arr[0],
					'CompanyName' => $companyName,
					'PhoneNumber' => $arr[6]
				],
				'Address' => [
					'StreetLines' => [$arr[1]],
					'City' => $arr[2],
					'StateOrProvinceCode' => $arr[3],
					'PostalCode' => $arr[4],
					'CountryCode' => $arr[5]
				]
			];
			
		}
		
		public function faxSender($fax_detail){
			$fax_detail = array_map('trim', explode(",", $fax_detail));
			
			$this->faxSender = [
				'Contact' => [
					'FaxNumber' => $fax_detail[0],
					'PhoneNumber' => $fax_detail[1],
					'PersonName' => $fax_detail[2],
					'CompanyName' => $fax_detail[3],
					'Department' => $fax_detail[4],
				],
			];
		}
		
		public function faxRecipient($fax_detail){
			$address = array_map('trim', explode(",", $fax_detail['Address'][0]));
			
			$fax_detail = array_map('trim', explode(",", $fax_detail['Contact'][0]));
			
			$this->faxRecipient = [
				'Contact' => [
					'FaxNumber' => $fax_detail[0],
					'PhoneNumber' => $fax_detail[1],
					'PersonName' => $fax_detail[2],
					'CompanyName' => $fax_detail[3],
					'Department' => $fax_detail[4],
				],
				'Address' => [
					'StreetLines' => [$address[0]],
					'City' => $address[1],
					'StateOrProvinceCode' => $address[2],
					'PostalCode' => $address[3],
					'CountryCode' => $address[4]
				]
			];
		}
		
		/*
		*@method - print proof of delivery
		*/
		public function getProof($trackingNum){
			$this->trackingNumber = $trackingNum;
			return $this->processRequest('letter');
		}
		
		/*
		*@method - fax proof of delivery
		*/
		public function faxProof($trackingNum){
			$this->trackingNumber = $trackingNum;
			return $this->processRequest('fax');
		}
		
		private function processRequest($type){
			$request = new \stdClass;
			$request->TransactionDetail = [
				'CustomerTransactionId' => '*** SPOD Request using PHP ***',
				'Localization' => [
					'LanguageCode'=>'EN'
				]
			];
			
			$request->QualifiedTrackingNumber = ['TrackingNumber' => $this->trackingNumber];

			$request->AdditionalComments = $this->additionalComments;
			$request->LetterFormat = $this->documentType;  
			$request->Consignee = $this->consignee;
			
			$obj = new Request;

			try{
				if($type == "letter"){
					$response =  $obj->sendRequest($request, 'TrackService_v9.wsdl', 'retrieveSignatureProofOfDeliveryLetter', 'trck:9:1:0');
					
					if($response->Notifications->Code == 0){
						file_put_contents($this->savePath.'proof-'.generateRandomString(5).'.'.$this->documentType, $response->Letter);
					}
				}
				else if($type == "fax"){
					$request->FaxSender = $this->faxSender;
					$request->FaxRecipient = $this->faxRecipient;
					
					$response =  $obj->sendRequest($request, 'TrackService_v9.wsdl', 'sendSignatureProofOfDeliveryFax', 'trck:9:1:0');
				}
				
				return $response;
			}
			catch (SoapFault $e) {
				throw new \Exception($e->faultstring());
			}
		}
		
	}