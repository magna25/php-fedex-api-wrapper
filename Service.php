<?php 
	namespace Fedex;
	
	class Service{
		
		/*
		*@var service type: array
		*/
		private $serviceType = [
			'00' => 'FEDEX_GROUND',
			'01' => 'FEDEX_2_DAY',
			'02' => 'FEDEX_2_DAY_AM',
			'03' => 'FEDEX_1_DAY_FREIGHT',
			'04' => 'FEDEX_2_DAY_FREIGHT',
			'05' => 'FEDEX_3_DAY_FREIGHT',
			'06' => 'FEDEX_PRIORITY_OVERNIGHT',
			'07' => 'FEDEX_FIRST_OVERNIGHT_FREIGHT',
			'08' => 'FEDEX_STANDARD_OVERNIGHT_FREIGHT',
			'09' => 'FEDEX_SAMEDAY',
			'10' => 'FEDEX_FIRST_OVERNIGHT',
			'11' => 'FEDEX_SAMEDAY',
			'12' => 'FEDEX_HOME_DELIVERY',
			'13' => 'EUROPE_FIRST_INTERNATIONAL_PRIORITY',
			'14' => 'INTERNATIONAL_ECONOMY',
			'15' => 'INTERNATIONAL_FIRST',
			'16' => 'INTERNATIONAL_PRIORITY',
			'17' => 'INTERNATIONAL_ECONOMY_ FREIGHT',
			'18' => 'INTERNATIONAL_PRIORITY_ FREIGHT',
		];
		
		/*
		*@var packaging type: array
		*/
		private $packagingType = [
			'00' => 'YOUR_PACKAGING',
			'01' => 'FEDEX_10KG_BOX',
			'02' => 'FEDEX_25KG_BOX',
			'03' => 'FEDEX_BOX',
			'04' => 'FEDEX_ENVELOPE',
			'05' => 'FEDEX_PAK',
			'07' => 'FEDEX_TUBE',
			'08' => 'FEDEX_EUROPE_FIRST_CUSTOMER_OWN_PACKAGING',
			'09' => 'FEDEX_EUROPE_FIRST_ENVELOPE',
			'10' => 'FEDEX_EUROPE_FIRST_PACK',
			'11' => 'FEDEX_EUROPE_FIRST_10KG_BOX',
		];
		
		/*
		*var drop off type: array 
		*/
		private $dropOffType = [
			'00' => 'STATION',
			'01' => 'REGULAR_PICKUP',
			'02' => 'REQUEST_COURIER',
			'03' => 'DROP_BOX',
			'04' => 'BUSINESS_SERVICE_CENTER',
		];

		private $pickupCarrierCode = [
			'00' => 'FDXE', //express
			'01' => 'FDXG', //ground
			'02' => 'FDXC',//cargo
			'03' => 'FXCC',// critical
			'04' => 'FXFR' //freight
		];
		
		/*
		*@method get service type: array
		*/
		public function getServiceType($id){
			if(!array_key_exists($id, $this->serviceType)){
				throw new \Exception('Invalid Service Code');
			}
			return $this->serviceType[''.$id.''];
		}
		
		/*
		*@var get packagingt type: array
		*/
		public function getPackagingType($id){
			if(!array_key_exists($id, $this->packagingType)){
				throw new \Exception('Invalid Drop off Code');
			}
			return $this->packagingType[''.$id.''];
		}
		
		/*
		*@var get packagingt type: array
		*/
		public function getDropOffType($id){
			if(!array_key_exists($id, $this->packagingType)){
				throw new \Exception('Invalid Packaging Code');
			}
			return $this->dropOffType[''.$id.''];
		}
		
		/*
		*@var get packagingt type: array
		*/
		public function getPickupCarrier($id){
			if(!array_key_exists($id, $this->pickupCarrierCode)){
				throw new \Exception('Invalid Carrier Code');
			}
			return $this->pickupCarrierCode[''.$id.''];
		}
	}