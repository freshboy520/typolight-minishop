<?

class ShopPaymentIPayment {
	
	public $debug = 1;
	
	protected $accountId = "99999";
	protected $userId = "99999";
	protected $transactionPassword=0;
	protected $adminactionpassword="5cfgRT34xsdedtFLdfHxj7tfwx24fe";
	protected $currency;
	protected $amount;
	protected $type;
	protected $paymentType;
	protected $clientName="HANSMUSTER";
	protected $transactionId;
	protected $orderId;
	protected $ccNumber;
	protected $ccExpMonth;
	protected $ccExpYear;
	protected $cardType;
	protected $cardCheckCode;
	protected $ip = "212.249.119.119";
	protected $shopId = 1;
	protected $parsedResponse = false;
	protected $returnHash;
	
	public function __construct() {
		return $this;
	}
	
	public function setAccountId($id) {
		$this->accountId = $id;
	}
	public function getAccountId() { return $this->accountId; }
	
	public function setTransactionPassword($password) {
		$this->transactionPassword = $password;
	}
	public function getTransactionPassword() { return $this->transactionPassword; }
	
	public function setAdminactionPassword($password) {
		$this->adminactionpassword = $password;
	}
	public function getAdminactionPassword() { return $this->adminactionpassword; }
	
	public function setUserId($id) {
		$this->userId = $id;
	}
	public function getUserId() { return $this->userId; }
	
	public function setCurrency($currency) {
		$this->currency = $currency;
	}
	public function getCurrency() { return $this->currency; }
	
	public function setAmount($amount) {
		$this->amount = $amount;
	}
	public function getAmount() { return $this->amount; }
	
	public function setClientName($name) {
		$this->clientName = $name;
	}
	public function getClientName() { return $this->clientName; }
	
	public function setCardNumber($ccNumber) {
		$this->ccNumber = $ccNumber;
	}
	public function getCardNumber() { return $this->ccNumber; }
	
	public function setCardCheckCode($cardCheckCode) {
		$this->cardCheckCode = $cardCheckCode;
	}
	public function getCardCheckCode() { return $this->cardCheckCode; }
	
	public function setExpMonth($ccExpMonth) {
		$this->ccExpMonth = $ccExpMonth;
	}
	public function getExpMonth() { return $this->ccExpMonth; }
	
	public function setExpYear($ccExpYear) {
		$this->ccExpYear = $ccExpYear;
	}
	public function getExpYear() { return $this->ccExpYear; }
	
	public function setCardType($cardType) {
		$this->cardType = $cardType;
	}
	public function getCardType() { return $this->cardType; }
	
	public function setIp($ip) {
		$this->ip = $ip;
	}
	public function getIp() { return $this->ip; }
	
	public function setOrderId($id) {
		$this->orderId = $id;
	}
	public function getOrderId() { return $this->orderId; }
	
	public function setTransactionId($id) {
		$this->transactionId = $id;
	}
	public function getTransactionId() { return $this->transactionId; }
	
	
	public function bill() {
		
		if(!$this->amount) {
			$this->errorCode = "1";
			return false;
		}
		if(!$this->currency) {
			$this->errorCode = "2";
			return false;
		}
		
		$this->type = "auth";
		$this->paymentType = "cc";
	
	
		$params = array();
		$params['use_payment_authentication'] 		= 0;
		$params['error_lang'] 										= "en";
		
		$params['trxuser_id'] 										= $this->userId;
		$params['trxpassword'] 										= $this->transactionPassword;
		$params['adminactionpassword'] 						= $this->adminactionpassword;
		$params['trx_currency'] 									= $this->currency;
		$params['trx_amount'] 										= $this->amount;
		$params['trx_typ'] 												= $this->type;
		$params['trx_paymenttyp'] 								= $this->paymentType;
		$params['trxuser_id'] 										= $this->userId;
		$params['addr_name'] 											= urlencode($this->clientName);
		
		$params['transaction_id'] 								= $this->transactionId;
		$params['shopper_id'] 										= $this->shopId;
		$params['order_id'] 											= $this->orderId;
		
		$params['advanced_strict_id_check']				= 0;
		$params['transaction_id'] 								= $this->userId;
		$params['transaction_id'] 								= $this->userId;
		
		$params['gateway'] 												= 1;
		$params['from_ip'] 												= $this->ip;

		// NOW THE IMPORTANT PART
		$params['cc_number'] 											= $this->ccNumber;
		$params['cc_expdate_month']	 							= $this->ccExpMonth;
		$params['cc_expdate_year'] 								= $this->ccExpYear;
		$params['cc_typ']					 								= $this->cardType;
		$params['cc_checkcode']					 					= $this->cardCheckCode;

		$queryString = "?";		
		foreach($params as $key => $value) {
			$queryString .= $key."=".$value."&";
		}
		$queryString = rtrim($queryString, "&");
		
		
		$url = "https://ipayment.de/merchant/".$this->accountId."/processor.php".$queryString;
		$process = curl_init($url);

		curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($process);
		if($this->debug) echo curl_getinfo($process, CURLINFO_EFFECTIVE_URL);
		$internalResonse = $this->parseResponse($response);

		return $internalResonse;
	}
	
	
	public function parseResponse($resp) {
		$lines = split("\n", $resp);

		$status	= preg_replace("/^Status=/", "", $lines[0]);
	  $params = preg_replace("/^Params=/", "", $lines[1]);
		$pairs 	= 	split("\&",$params);
		$this->returnHash = array();
		foreach($pairs as $pair) {
			list($key, $value) = split("=", $pair);
			$this->returnHash[$key] = $value;
		}
		$this->parsedResponse = true;
		
		if($this->getStatus() == "SUCCESS") {
			return true;
		}
		return false;
	}
	
	public function getDebugResonse() {
		return "<pre>".print_r($this->returnHash, true)."</pre>";
	}
	
	public function getStatus() {
		return $this->returnHash['ret_status'];
	}
	
	public function getErrorCode() {
		return $this->returnHash['ret_errorcode'];
	}
	
	public function getErrorMesage() {
		return urldecode($this->returnHash['ret_errormsg']);
	}
	
	public function getBookNr() {
		return $this->returnHash['ret_booknr'];
	}
	
}

?>