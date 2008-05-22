<h2><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_TITLE']; ?></h2>
<div class="subtitle"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SUBTITLE']; ?></div>
<div class="line"></div>
<form action="<? echo $this->formAction; ?>" method="post" name="addressform">
	
<div class="address_fields">
<?php foreach ($this->fields as $objWidget): ?>
<div class="widget">
  <?php echo ($objWidget instanceof FormCaptcha) ? $objWidget->generateQuestion() : $objWidget->generateLabel(); ?><?php echo $objWidget->generateWithError(); ?> <?php if ($objWidget->required): ?><span class="mandatory">*</span><?php endif; ?> 
	<div class="clear"></div>
</div>
<?php endforeach; ?>
</div>

<?if(!$this->sa): ?>
<div class="ctrl_shipping_address">
	<a href="<? echo $this->formAction; ?>?a=order&amp;sa=1"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SHIPPING_ADDRESS_YES']; ?></a>
</div>
<? endif; ?>
<div class="shipping_address_widgets" id="shipping_address" style="display:<?if($this->sa) echo "block;"; else echo "none;"; ?>">
	<h3><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SHIPPING_ADDRESS_TITLE']; ?></h3>
	<?php foreach ($this->shp_fields as $objWidget): ?>
		<div class="widget">
		  <?php echo ($objWidget instanceof FormCaptcha) ? $objWidget->generateQuestion() : $objWidget->generateLabel(); ?><?php echo $objWidget->generateWithError(); ?> <?php if ($objWidget->required): ?><span class="mandatory">*</span><?php endif; ?> 
			<div class="clear"></div>
		</div>
	<?php endforeach; ?>
</div>
<?if($this->sa): ?>
<div class="ctrl_shipping_address_none">
	<a href="<? echo $this->formAction; ?>?a=order&amp;sa=0"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_SHIPPING_ADDRESS_NO']; ?></a>
</div>
<? endif; ?>

<div class="legal_fields">
	<? echo $this->legal_info; ?>
<?php foreach ($this->legalFields as $objWidget): ?>
<div class="widget">
  <?php echo $objWidget->generateWithError(); ?>
	<div class="clear"></div>
</div>
<?php endforeach; ?>
</div>

<a href="<? echo $this->back_link; ?>" class="steplink"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_BACK_COMMAND']; ?></a>
<a href="javascript:;" onclick="document.addressform.submit();" class="steplink"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP2_CONTINUE_COMMAND']; ?></a>
<input type="hidden" name="validate_order_address_form" value="1" id="validate_order_form" />
<input type="hidden" name="FORM_SUBMIT" value="order_step_1" />
<input type="hidden" name="a" value="order" id="a" />
<input type="hidden" name="sa" value="<? echo $this->sa; ?>" id="sa" />
</form>