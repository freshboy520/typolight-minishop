Order confirmation Qwstion on-line store

Order ID: <? for($i=0; $i<(5-strlen($this->orderId));$i++){echo "0";} echo $this->orderId; ?>

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



Your order has been successfully billed on your creditcard. Thank you for shopping at qwstion.com, we hope you enjoy your bag!

-------------------------------------------------



