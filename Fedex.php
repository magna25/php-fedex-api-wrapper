<?php 
namespace Fedex;

class Fedex extends Service{	
	/*
	  @var weight
	*/
	private $weight;
	
	/*
	  @var length
	*/
	private $length;
	
	/*
	  @var height
	*/
	private $height;
	
	/*
	  @var units -- inches
	*/
	private $units = 'IN';
	
	/*
	  @var units -- pounds
	*/
	private $weightUnits = 'LB';
	
	/*
	  @var number of packages
	*/
	private $packageCount = 1;
	
	/*
	  @var serviceCode
	*/
	private $serviceCode = '00';
	
	/*
	  @var dropOffCode
	*/
	private $dropOffCode = '00';
	
	/*
	  @var packaging type
	*/
	private $packagingCode = '00';
	
	/*
	  @var insurance
	*/
	private $insuredValue;
	
	/*
	  @var shipping date
	*/
	private $shippingDate;
	
	/*
	  @var shipFrom
	*/
	private $shipFrom;
	
	/*
	  @var shipTo
	*/
	private $shipTo;
	
	/*
	  @var label format
	*/
	private $labelFormat = 'png';
	
	/*
	  @var folder to save the labels
	*/
	private $savePath = 'labels/';
	
	/*
	  @var customer reference 
	*/
	private $customerReferenceNumber;
	
	/*
	  @var invoice number
	*/
	private $invoiceNumber;
	
	/*
	  @var po number - have no idea what's it for 
	*/
	private $poNumber;
	
	/*
	  @var ship id ??
	*/
	private $shipId;
	
	/*
	  @var international shipment
	*/
	private $international = false;
	
	/*
	  @var residential address
	*/
	private $residentialAddress = false;
	
	/*
	*@var international shipping customs clearance
	*/
	private $customsClearance;
	
	/*
	*@var currency code;
	*/
	private $currency = 'USD';
	
	
	public function __construct(){
		$this->shippingDate = strtotime("2015/06/30 10:00am");
	}
	
	/*
	 * @method set weight
	 */
	public function weight($val){$this->weight = $val;}
	
	/*
	 * @method set width
	 */
	public function width($val){$this->width = $val;}
	
	/*
	 * @method set length
	 */
	public function length($val){$this->length = $val;}
	
	/*
	 * @method set height
	 */
	public function height($val){$this->height = $val;}
	
	/* 
	 *@method set units
	 */
	public function units($val){$this->units = $val;}
	
	/* 
	 *@method set units
	 */
	public function weightUnits($val){$this->weightUnits = $val;}
	
	/*
	 * @method set dropOffCode
	 */
	public function dropOffCode($val){$this->dropOffCode = $val;}
	
	/*
	 * @method set packageCount
	 */
	public function packageCount($val){$this->packageCount = $val;}
	
	/*
	 * @method set serviceCode
	 */
	public function serviceCode($val){$this->serviceCode = $val;}
	
	/*
	 * @method set packagingCode
	 */
	public function packagingCode($val){$this->packagingCode = $val;}
	
	/*
	 * @method set insuredValue
	 */
	public function insuredValue($val){$this->insuredValue = $val;}
	
	/*
	 * @method set shipping date
	 */
	public function shippingDate($val){$this->shippingDate = $val;}
	
	/*
	 * @method set shipping date
	 */
	public function labelFormat($val){$this->labelFormat = $val;}
	
	/*
	 * @method set savepath
	 */
	public function savePath($val){$this->savePath = $val;}
	
	/*
	 * @method set reference number
	 */
	public function customerReferenceNumber($val){$this->customerReferenceNumber = $val;}
	
	/*
	 * @method set savepath
	 */
	public function invoiceNumber($val){$this->invoiceNumber = $val;}
	
	/*
	 * @method set po number
	 */
	public function poNumber($val){$this->poNumber = $val;}
	
	/*
	 * @method set shipId
	 */
	public function shipId($val){$this->shipId = $val;}
	
	/*
	 * @method set residentialAddress
	 */
	public function residentialAddress($val){$this->residentialAddress = $val;}
	
	/*
	 * @method set shipping type
	 */
	public function international($val){$this->international = $val;}
	
	/*
	 * @method Shipper
	 * @returns array
	 */
	public function shipFrom($shipper){
		$shipper = array_map('trim', explode(",", $shipper));
		
		if(count($shipper) < 6){
			throw new \Exception('Shipper Information is not complete.');
		}
		
		$this->shipFrom = [
			'Contact' => [
				'PersonName' => $shipper[0],
				'PhoneNumber' => $shipper[6],
			],
			'Address' => [
				'StreetLines' => $shipper[1],
				'City' => $shipper[2],
				'StateOrProvinceCode' => $shipper[3],
				'PostalCode' => $shipper[4],
				'CountryCode' => $shipper[5],
				'Residential' => $this->residentialAddress
			]
		];
	}
	
	/*
	 * @method Recipient
	 * @returns array
	 */
	public function shipTo($recipient){
		$recipient = array_map('trim', explode(",", $recipient));
		
		if(count($recipient) < 6){
			throw new \Exception('Recipient Information is not complete.');
		}
		
		$this->shipTo = [
			'Contact' => [
				'PersonName' => $recipient[0],
				'PhoneNumber' => $recipient[6]
			],
			'Address' => [
				'StreetLines' => $recipient[1],
				'City' => $recipient[2],
				'StateOrProvinceCode' => $recipient[3],
				'PostalCode' => $recipient[4],
				'CountryCode' => $recipient[5],
				'Residential' => $this->residentialAddress
			]
		];
	}
	
	/*
	 * @method Package details
	 * @returns array
	 */
	private function Package(){
		$package = [
			'GroupPackageCount' => 1,
			'Weight' => [
				'Value' => $this->weight,
				'Units' => $this->weightUnits,
			],
			
		];
		
		if($this->packagingCode == "00"){
			if(!$this->length || !$this->width || !$this->height){
				throw new \Exception('Package dimensions are not complete. You need to specify: width, length, and height.');
			}
			$package['Dimensions'] = ['Length' => $this->length, 'Width' => $this->width, 'Height' => $this->height, 'Units' => $this->units];
		}
		
		$package['ItemDescription'] = ['test item description'];
		
		$package['CustomerReferences'] = [
			'0' => ['CustomerReferenceType' => 'CUSTOMER_REFERENCE', 'Value' => $this->customerReferenceNumber],
			'1' => ['CustomerReferenceType' => 'INVOICE_NUMBER', 'Value' => $this->invoiceNumber],
			'2' => ['CustomerReferenceType' => 'P_O_NUMBER', 'Value' => $this->poNumber],
			'3' => ['CustomerReferenceType' => 'SHIPMENT_INTEGRITY', 'Value' => $this->shipId]
		];
		
		return $package;
	}
	
	/*
	 * @method label
	 * @returns array
	 */
	 
	private function label(){
		return [
				'LabelFormatType' => 'COMMON2D', // valid values COMMON2D, LABEL_DATA_ONLY
				'ImageType' => strtoupper($this->labelFormat),  // valid values DPL, EPL2, PDF, ZPLII and PNG
				//'LabelStockType' => 'PAPER_7X4.75',
		];
	} 
	
	private function payment(){
		
		$obj = new Authentication;
		
		return [
			'PaymentType' => 'SENDER',
				'Payor' => [
					'ResponsibleParty' => [
						'AccountNumber' => $obj->clientDetails()['AccountNumber'],
						'Contact' => null,
						'Address' => [
							'CountryCode' => 'US'
						]
					]
				]
		];
	}
	
	public function customsClearance($arr){
		$obj = new Authentication;
		
		$accountNum = strtoupper($arr['Payor']) == "SENDER" ? $obj->clientDetails()['AccountNumber'] : $arr['AccountNumber'];
		
		$shipment['DutiesPayment'] = [
			'PaymentType' => strtoupper($arr['Payor']), // valid values RECIPIENT, SENDER and THIRD_PARTY
			'Payor' => [
				'ResponsibleParty' => [
					'AccountNumber' => $accountNum,
					'Contact' => null,
					'Address' => ['CountryCode' => $arr['CountryCode']]
					]
			]
		];
		
		$shipment['CustomsValue'] = [
			'Currency' => $this->currency,
			'Amount' => $arr['TotalCustomsValue']
		];
		
		//$shipment['DocumentContent'] = 'NON_DOCUMENTS'; 
		
		foreach($arr['Commodities'] as $val){
			$shipment['Commodities'] = [
				'NumberOfPieces' => $val['Pieces'],
				'Description' => $val['Description'],
				'CountryOfManufacture' => $val['CountryOfManufacture'],
				'Weight' => [
					'Units' => $this->weightUnits, 
					'Value' => $val['Weight']
				],
				'Quantity' => $val['Quantity'],
				'QuantityUnits' => 'EA',
				'UnitPrice' => [
					'Currency' => 'USD', 
					'Amount' => $val['UnitPrice']
				],
				'CustomsValue' => [
					'Currency' => $this->Currency, 
					'Amount' => $val['CustomsValue']
				]
			];
			}
		$shipment['ExportDetail'] = [
			'B13AFilingOption' => 'NOT_REQUIRED'
		];
		
		//throw new \Exception(var_dump($shipment));
		$this->customsClearance = $shipment;
	}

	protected function getRequest(){
		$request = new \stdClass;
		$request->RequestedShipment = new \stdClass;
		$request->RequestedShipment->DropoffType = $this->getDropOffType($this->dropOffCode);
		$request->RequestedShipment->ShipTimestamp = $this->shippingDate;
		$request->RequestedShipment->ServiceType = $this->getServiceType($this->serviceCode);
		$request->RequestedShipment->PackagingType = $this->getPackagingType($this->packagingCode);
		$request->RequestedShipment->TotalInsuredValue = ['Amount' => $this->insuredValue, 'Currency' => 'USD'];
		$request->RequestedShipment->RequestedPackageLineItems = $this->package();
		$request->RequestedShipment->Shipper = $this->shipFrom;
		$request->RequestedShipment->Recipient = $this->shipTo;
		$request->RequestedShipment->PackageCount = $this->packageCount;
		$request->RequestedShipment->LabelSpecification = $this->label();
		$request->RequestedShipment->ShippingChargesPayment = $this->payment();
		$request->labelFormat = $this->labelFormat;
		$request->savePath = $this->savePath;
		
		if($this->international){
			$request->RequestedShipment->CustomsClearanceDetail = $this->customsClearance;
		}
		
		return $request;
	}	
}