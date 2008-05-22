Order confirmation

Order ID: <? echo $this->orderId; ?>

Articles
========
<? foreach($this->articles as $article){ echo $article; } ?>


Total: <? echo $this->total; ?>

Included VAT: <? echo $this->taxCosts; ?>

Shipment costs: <? echo $this->shippmentCosts; ?>

Total including VAT and Shipment costs: <? echo $this->endtotal; ?>



Billing address:
<? echo $this->billAddress; ?>

<? if(strlen($this->shipAddress) > 3): ?>
Shipment address:
<? echo $this->shipAddress; ?>
<? endif; ?>

<? if($this->paymentDetails['payment'] == "prepay"): ?>
Please transact the amount of <? echo $this->endtotal; ?> on the bank account: XYZ
<? else: ?>
Your order has been successfully billed on your creditcard.
<? endif; ?>



Thank you for shopping.

-------------------------------------------------



