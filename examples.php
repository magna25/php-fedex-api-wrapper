<?php 
	require_once("autoloader.php");
	use Tracking;
	use Rate;
	use Shipping;
	use Returns;
	use Entity\DeleteShipment;
	use Entity\RequestNotification;
	use Entity\RequestProofOfDelivery;
	use Entity\PickupService;
	use Entity\DeletePendingShipment;
	
	try{
		/*
		*
		*
		*for all service codes, packaging codes and stuff...drop by the Service.php class and get the appropriate ones...
		*
		*
		*/
		
		
		/*********************************
		@Tracking request example
		**********************************/
		$obj = new Tracking;
		$response = $obj->track('xxx');
		
		
		/**********************************
		*@shipping request example
		************************************/
		
		$obj = new Shipping;
		$obj->shipFrom('Henny Magna, 1110 Dayton St, Denver, CO, 80023, US, 1234567890');
		$obj->shipTo('Googleplex, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, US, 1234567890');
		$obj->weight(20);
		
		// default: "00" if different from default, you don't need to specify w*l*h
		$obj->packagingCode('03');
		
		$obj->width(10);
		$obj->height(10);
		$obj->length(10);
		$obj->insuredValue(20);
		
		//defaults to "00"
		$obj->dropOffCode('01');
		
		defaults to "00"
		$obj->serviceCode('05');
		
		//needed for international shipping
		$obj->international(true);
		$obj->customsClearance(['Payor' => 'sender', TotalCustomsValue => 400, 'CountryCode' => 'US', 'Commodities' => [['Pieces' => 1, 'Quantity' => 4, 'Description' => 'Dog Meat', 'CountryOfManufacture' => 'US', 'Weight' => 20, 'UnitPrice' => 20.00, 'CustomsValue' => 100.000000]]]);
		
		//optional details to put on the label
		$obj->invoiceNumber('34895KJHFSDF27JSFDS');
		$obj->customerReferenceNumber('134893');
		$obj->poNumber('KJSHDS2i342SDF82SD');
		$obj->shipId('52283FSDHS');
		
		//if you want to email the label to a customer you need to call the below two methods. Note: you can't email and get the label at the same time.
		$obj->labelExpirationDate('2015-07-20');
		$response = $obj->emailLabelTo('test@gmail.com');
		
		//get the shipping label
		$response = $obj->getLabel();
		
		
		
		/*******************************************
		*@Send Tracking Notification requst example
		********************************************/
		
		$obj = new RequestNotification;
		$obj->senderDetail(['Email' => 'Hennymaster1@gmail.com', 'Name' => 'Henny Magna']);
		$obj->recipients('test@gmail.com,test2@gmail.com, ksjdf');
		$response = $obj->sendNotification('xxx');//tracking num
		
		
		/*********************************
		*@Request rate example...
		**********************************/
		
		$obj = new Rate;
		$obj->shipFrom('Henny Magna, 1110 Dayton St, Denver, CO, 80023, US, 1234567890');
		$obj->shipTo('Googleplex, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, US, 1234567890');
		$obj->weight(20);
		
		// default: "00" if different than default, you don't need to specify w*l*h
		//$obj->packagingCode('01');
		
		$obj->width(10);
		$obj->height(10);
		$obj->length(10);
		$obj->insuredValue(20);
		
		//defaults to "00"
		$obj->dropOffType('03');
		
		//defaults to "00"
		//$obj->serviceCode('16');
		
		//needed for international shippings
		$obj->international(true);
		$obj->customsClearance(['Payor' => 'sender', TotalCustomsValue => 400, 'CountryCode' => 'US', 'Commodities' => [['Pieces' => 1, 'Quantity' => 4, 'Description' => 'Dog Meat', 'CountryOfManufacture' => 'US', 'Weight' => 20, 'UnitPrice' => 20.00, 'CustomsValue' => 100.000000]]]);
		
		$response = $obj->getRate();
		
		
		
		/************************************
		*@delete/void shipment example
		****************************************/
		$obj = new DeleteShipment;
		$response = $obj->delete('XXX', 'xx'); //replace with tracking number and service code
		
		
		/************************************
		*@get/email return label example
		***************************************/
		
		$obj = new Returns;
		$obj->shipFrom('Henny Magna, 1110 Dayton St, Denver, CO, 80023, US, 1234567890');
		$obj->shipTo('Googleplex, 1600 Amphitheatre Pkwy, Mountain View, CA 94043, US, 1234567890');
		$obj->weight(20);
		
		// default: "00" if different from default, you don't need to specify w*l*h
		$obj->packagingCode('04');
		
		$obj->width(10);
		$obj->height(10);
		$obj->length(10);
		$obj->insuredValue(20);
		
		//defaults to "00":
		$obj->dropOffCode('03');
		
		//defaults to "00"
		$obj->serviceCode('02');
		
		//needed for international shipping
		$obj->international(true);
		$obj->customsClearance(['Payor' => 'sender', TotalCustomsValue => 400, 'CountryCode' => 'US', 'Commodities' => [['Pieces' => 1, 'Quantity' => 4, 'Description' => 'Dog Meat', 'CountryOfManufacture' => 'US', 'Weight' => 20, 'UnitPrice' => 20.00, 'CustomsValue' => 100.000000]]]);
		
		//if you want to email the label to a customer you need to call the below two methods. Note: you can't email and get the label at the same time, if you try that it will be a separate label..
		$obj->labelExpirationDate('2015-07-20');
		$response = $obj->emailReturnLabelTo('test@gmail.com');
		
		//optional details to put on the label
		$obj->rma('KJSFH98345SKS');
		$obj->reason('Missing Parts');
		$obj->invoiceNumber('34895KJHFSDF27JSFDS');
		$obj->customerReferenceNumber('134893');
		$obj->poNumber('KJSHDS2i342SDF82SD');
		$obj->shipId('52283FSDHS');
		
		//save label
		$response = $obj->getReturnLabel();
		
		//if you would like Fedex deliver the return label to the customer and pick the package up..call the below methods..
		$obj->readyTime([strtotime("2015/06/30 10:00am"), strtotime("2015/06/30 6:00pm")]); //time the package is ready and the last time the carrier can pick up..
		$obj->dropOffCode('01');
		$obj->instructions('package is left on the porch');//optional
		$response = $obj->requestTag();
		
		
		/******************************
		*@request proof of delivery example
		*****************************/
		
		$obj = new RequestProofOfDelivery;
		$obj->documentType('png');
		$obj->consignee('Henny Magna, 1110 Dayton St, Denver, CO, 80023, US, 1234567890');
		$obj->additionalComments('what comments?');
		
		//if you want to fax the proof of delivery to someone you need to follow the below format...contact array order: fax number, phone number, persons name, company name, department
		$obj->faxSender('9015550001,9015550001, Henny Magna, My Company .Inc, My Department');
		//the address requirement is ridiculous but that's what Fedex requires...seriously? Just include country code at the end and you are fine.  
		$obj->faxRecipient(['Contact' => ['9015550001,9015550001, Henny Magna, My Company .Inc, My Department'], 'Address' => ['street name, city, state, zip, US']]);
		$response = $obj->faxProof('xxx');//replace with valid tracking number
		
		//will save the proof to specified folder..default folder is "labels/"
		//$response = $obj->getProof('xxx');;//replace with valid tracking number
		
		
		/*******************************
		*@ pick up service example
		***************************/
		
		$obj = new PickupService;
		//To schedule a pickup 
		//order: persons name, company name, phone and adddress...
		$obj->pickupLocation('Henny Magna, My Company Name .Inc, 1234567890, 1400 Dayton St, Denver, CO, 80023, US');
		$obj->building(['apartment', 'b25']); //building type and further description like building number and stuff..
		$obj->readyTime([strtotime("2015/06/29 10:30am"), strtotime("2015/06/29 7:30pm")]);//time the package is ready and time your company closes.
		$obj->packageDetail(['20', '1', '0']);//weight, package count, oversize package count..
		$obj->carrierCode('01');
		$response = $obj->schedulePickup();//don't forget to make a note of the PickupConfirmationNumber and location you get from the response in case you need it to cancel later.
		
		
		//to cancel a pickup 
		$obj->scheduledDate(strtotime("2015/06/29"));
		$obj->confirmationNumber('4');
		$obj->locationCode('DENA');
		$obj->carrierCode('00');
		$response = $obj->cancelPickup();
		
		
		/**********************
		*@ delete pending shipment example
		*******************************/
		
		$obj = new DeletePendingShipment;
		$response = $obj->delete('795491600674', 'GROUND');//replace with valid tracking number and service type: ground, express, freight..;
		
		
		echo "<pre>";
		print_r($response);
		echo "</pre>";
	}
	catch(\Exception $e){
		echo "<pre>";
		print_r($e);
		echo "</pre>";
	}
