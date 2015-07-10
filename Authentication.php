<?php 
	namespace Fedex;
	
	class Authentication extends Fedex{
		
		/*
		*@access key and password
		*@returns array
		*/
		protected function authenticate(){
				return [
					'UserCredential' => [
						'Key' => 'xxx', 
						'Password' => 'xxx'
						]
				];
		}
		
		/*
		*@account number and meter #
		*@returns array
		*/
		protected function clientDetails(){
				return [
					'AccountNumber' => 'xxx',
					'MeterNumber' => 'xxx'
				];
		}
	}