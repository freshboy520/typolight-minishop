<div class="order_payment">
	<h2><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_TITLE']; ?></h2>
	<div class="subtitle"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_SUBTITLE']; ?></div>
	<div class="line"></div>
	<form action="<? echo $this->formAction; ?>" method="post" name="payment">
		<?php foreach ($this->fields as $objWidget): ?>
		<div class="widget">
		  <?php echo ($objWidget instanceof FormCaptcha) ? $objWidget->generateQuestion() : $objWidget->generateLabel(); ?><?php echo $objWidget->generateWithError(); ?> <?php if ($objWidget->required): ?><span class="mandatory">*</span><?php endif; ?> 
			<div class="clear"></div>
		</div>
		<?php endforeach; ?>

	<a href="<? echo $this->back_link; ?>" class="steplink"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_BACK_COMMAND']; ?></a>
	<a href="javascript:;" onclick="document.payment.submit();" class="steplink"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP3_CONTINUE_COMMAND']; ?></a>
	<input type="hidden" name="FORM_SUBMIT" value="order_payment" />
	<input type="hidden" name="a" value="orderp" id="a" />
	</form>
</div>