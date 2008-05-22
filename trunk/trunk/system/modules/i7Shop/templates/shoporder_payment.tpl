<div class="order_payment">
	<h2><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_TITLE']; ?></h2>
	<div class="subtitle"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_SUBTITLE']; ?></div>
	<div class="line"></div>
	
	<form action="<? echo $this->formAction; ?>" method="post" name="payment">
	<script type="text/javascript" charset="utf-8">
		function checkDisplay() {
			document.getElementById('paymentcc').style.display = 'none';
			if(document.getElementById('payment_cc').checked) {
				document.getElementById('paymentcc').style.display = 'block';
			}
		}
	</script>
	
	<div id="selectpaymentmethode">
		<input type="radio" name="payment" value="prepay" id="payment_prepay" onclick="checkDisplay();" <? if($this->paymentMethode == "prepay"): ?>checked="checked"<? endif; ?>><label for="payment_prepay">prepay</label><br />
		<input type="radio" name="payment" value="cc" id="payment_cc" onclick="checkDisplay();" <? if($this->paymentMethode == "cc"): ?>checked="checked"<? endif; ?>><label for="payment_cc">Creditcard</label>
	</div>
	
	
		<div id="paymentcc" style="display:<? if($this->paymentMethode == "cc"): ?>block<? else: ?>none<? endif; ?>">
		<?php foreach ($this->fields as $objWidget): ?>
		<div class="widget">
		  <?php echo ($objWidget instanceof FormCaptcha) ? $objWidget->generateQuestion() : $objWidget->generateLabel(); ?><?php echo $objWidget->generateWithError(); ?> <?php if ($objWidget->required): ?><span class="mandatory">*</span><?php endif; ?> 
			<div class="clear"></div>
		</div>
		<?php endforeach; ?>
		</div>

	<a href="<? echo $this->back_link; ?>" class="steplink"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_BACK_COMMAND']; ?></a>
	<a href="javascript:;" onclick="document.payment.submit();" class="steplink"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_CONTINUE_COMMAND']; ?></a>
	<input type="hidden" name="FORM_SUBMIT" value="order_payment" />
	<input type="hidden" name="a" value="orderp" id="a" />
	</form>
</div>