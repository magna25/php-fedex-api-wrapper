<?php 
	namespace Fedex\Entity;
	
	use Fedex\Fedex;
	use Fedex\Request;
	
	class PickupService extends Fedex{
		
		private $pickupAddress;
		
		private $packageLocation = 'NONE';
		
		private $buildingType;
		
		private $buildingTypeDescription;
		
		private $readyTime;
		
		private $companyCloseTime;
		
		private $packageCount = 1;
		
		private $weight;
		
		private $units = 'LB';
		
		private $oversizedCount = 0;
		
		private $carrierCode = '00';
		
		private $remarks;
		
		private $confirmationNumber;
		
		private $locationCode;
		
		private $scheduledDate;
		
		public function pickupLocation($shipper){
			$shipper = array_map('trim', explode(",", $shipper));
			
			$this->pickupDetail = [
					'Contact' => [
						'PersonName' => $shipper[0],
						'CompanyName' => $shipper[1],
						'PhoneNumber' => $shipper[2]
					],
					'Address' => [
						'StreetLines' => $shipper[3],
						'City' => $shipper[4],
						'StateOrProvinceCode' => $shipper[5],
						'PostalCode' => $shipper[6],
						'CountryCode' => $shipper[7]
					]
			];
		}
		
		public function building($val){
			$this->buildingType = $val[0];
			$this->buildingTypeDescription = $val[1];
		}
		
		public function readyTime($val){
			$this->readyTime = $val[0];
			$this->companyCloseTime = $val[1];
		}
		
		
		public function packageDetail($val){
			$this->weight = $val[0];
			$this->packageCount = $val[1];
			$this->oversizedCount = $val[2];
			if(isset($val[3])){$this->packageLocation = $val[4];}
		}
		
		public function carrierCode($val){$this->carrierCode = $val;}
		
		public function remarks($val){$this->remarks = $val;}
		
		public function confirmationNumber($val){$this->confirmationNumber = $val;}
		
		public function locationCode($val){$this->locationCode = $val;}
		
		public function scheduledDate($val){$this->scheduledDate = $val;}
		
		public function schedulePickup(){
			
			$request = new \stdClass;

			$request->OriginDetail = new \stdClass;
			$request->OriginDetail->PickupLocation = $this->pickupDetail;
			$request->OriginDetail->PackageLocation = $this->packageLocation;
			$request->OriginDetail->BuildingPartCode = strtoupper($this->buildingType);
			$request->OriginDetail->BuildingPartDescription = strtoupper($this->buildingTypeDescription);
			$request->OriginDetail->ReadyTimestamp = $this->readyTime;
			$request->OriginDetail->CompanyCloseTime = $this->companyCloseTime;
			$request->PackageCount = $this->packageCount;
			$request->TotalWeight = ['Value' => $this->weight, 'Units' => $this->units];
			$request->CarrierCode = $this->getPickupCarrier($this->carrierCode);
			$request->OversizePackageCount = $this->oversizedCount;
			$request->CourierRemarks = $this->remarks;
			
			$obj = new Request;
			
			try{
				$response = $obj->sendRequest($request, 'PickupService_v9.wsdl', 'createPickup', 'disp:9:0:0');
				
				return $response;
			}
			catch (SoapFault $e) {
				throw new \Exception($e->faultstring());
			}
		}
		
		public function cancelPickup(){
			$request = new \stdClass;
			$request->CarrierCode = $this->getPickupCarrier($this->carrierCode);
			$request->PickupConfirmationNumber = $this->confirmationNumber;
			$request->ScheduledDate = $this->scheduledDate;
			$request->Location = $this->locationCode;
			$request->CourierRemarks = $this->remarks;
			
			$obj = new Request;
			
			try{
				$response = $obj->sendRequest($request, 'PickupService_v9.wsdl', 'cancelPickup', 'disp:9:0:0');
				
				return $response;
			}
			catch (SoapFault $e) {
				throw new \Exception($e->faultstring());
			}
		}
		
	}