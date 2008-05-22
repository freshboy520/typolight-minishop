<h2><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TITLE']; ?></h2>
<div class="subtitle"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_SUBTITLE']; ?></div>
<div class="line"></div>
<table cellpadding="0" cellspacing="0"class="baskettable">
	<tr>
		<th><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_ITEM']; ?></th>
		<th><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_AMOUNT']; ?></th>
		<th class="money"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_UNITPRICE']; ?></th>
		<th class="money"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BASKET_SUBTOTAL']; ?></th>
	</tr>
<? foreach($this->articles as $article){ echo $article; } ?>
	<tr>
		<th><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TOTAL']; ?></th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th class="money"><? echo $this->total; ?></th>
	</tr>

	<tr>
		<th><? echo $this->taxes_info; ?></th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th class="money"><? echo $this->taxCosts; ?></th>
	</tr>
	<tr>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th class="money">&nbsp;</th>
	</tr>
	<tr>
		<th><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_TOTAL_SHIPPMENT']; ?></th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th class="money"><? echo $this->shippmentCosts; ?></th>
	</tr>
	<tr class="strong">
		<th><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_ENDTOTAL']; ?></th>
		<th>&nbsp;</th>
		<th>&nbsp;</th>
		<th class="money"><? echo $this->endtotal; ?></th>
	</tr>
</table>

<div class="overviewinfos">
	<div class="bill_address block">
		<strong><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BLOCK_BILLADDRESSINFO']; ?></strong><br />
		<? echo $this->billAddress; ?>
	</div>
<? if($this->shipAddress): ?>
	<div class="shipping_address block">
		<strong><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BLOCK_SHIPPINGADDRESSINFO']; ?></strong><br />
		<? echo $this->shipAddress; ?>
	</div>
<?endif;?>

	<div class="payment block last">
		<strong><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BLOCK_PAYMENTINFO']; ?></strong><br />
		<? echo $this->paymentDetails; ?>
	</div>
	<div class="clear"></div>
</div>

<? if($this->payment_error): ?>
<div class="payment_error">
	<strong><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_PAYMENT_ERROR_TITLE']; ?></strong><br />
	<? echo $this->payment_error; ?>
	<!-- <? echo $this->debug_error; ?>-->
	<br /><br /><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_PAYMENT_ERROR_TEXT']; ?>
</div>
<? endif; ?>

<a href="<? echo $this->back_link; ?>" class="steplink"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_BACK_COMMAND']; ?></a>
<a href="<? echo $this->place_order_link; ?>" class="steplink"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP4_CONTINUE_COMMAND']; ?></a>
