<?

class ShopPaymentPrepay {
	

	protected $accountId = "99999";
	protected $userId = "99999";
	protected $transactionPassword=0;
	protected $adminactionpassword="";
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
			return true;
	}
	
	
	public function parseResponse($resp) {
	
	}
	
	public function getDebugResonse() {
		
	}
	
	public function getStatus() {
	
	}
	
	public function getErrorCode() {
	
	}
	
	public function getErrorMesage() {
		
	}
	
	public function getBookNr() {
		return "1";
	}
	
}

?>