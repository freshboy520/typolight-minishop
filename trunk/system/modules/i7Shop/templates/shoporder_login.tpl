<h2><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_TITLE']; ?></h2>
<div class="subtitle"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_SUBTITLE']; ?></div>
<div class="line"></div>
<form action="<? echo $this->formAction; ?>" method="post" name="loginform" id="loginform">
	<? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_TITLE_LOGIN']; ?>
<div class="login_fields">
<?php foreach ($this->fields as $objWidget): ?>
<div class="widget">
   <?php echo ($objWidget instanceof FormCaptcha) ? $objWidget->generateQuestion() : $objWidget->generateLabel(); ?><?php echo $objWidget->generateWithError(); ?><?php if ($objWidget->required): ?><span class="mandatory">*</span><?php endif; ?> 
	<div class="clear"></div>
</div>
<?php endforeach; ?>
</div>
<?if($this->errorLogin):?>
<div class="login_error">
	<? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_LOGIN_ERROR']; ?>
</div>
<? endif; ?>

<a href="javascript:;" onclick="document.loginform.submit();" class="steplink"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_LOGIN_COMMAND']; ?></a>
<input type="hidden" name="FORM_SUBMIT" value="order_login_form" />
<input type="hidden" name="a" value="orderl" id="a" />
<input type="hidden" name="sa" value="<? echo $this->sa; ?>" id="sa" />

</form>

<form action="<? echo $this->formAction; ?>" method="post" name="registerform" id="registerform">
<? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_TITLE_REGISTER']; ?>
<div class="register">
<?php foreach ($this->register_fields as $objWidget): ?>
<div class="widget">
  <?php echo ($objWidget instanceof FormCaptcha) ? $objWidget->generateQuestion() : $objWidget->generateLabel(); ?>
	<?php echo $objWidget->generateWithError(); ?> <?php if ($objWidget->required): ?><span class="mandatory">*</span><?php endif; ?> 
		<?php if(method_exists($objWidget, "generateConfirmationLabel")): ?>
					<div class="clear"></div>
				</div>
			<div class="widget">
			<? echo $objWidget->generateConfirmationLabel(); ?><? echo $objWidget->generateConfirmation(); ?> 
		<?endif; ?>
	<div class="clear"></div>
</div>
<?php endforeach; ?>
</div>

<?if($this->userExistsAlready):?>
<div class="login_error">
	<? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_REGISTER_USEREXISTS']; ?>
</div>
<? endif; ?>

<a href="javascript:;" onclick="document.registerform.submit();" class="steplink"><? echo $GLOBALS['TL_LANG']['i7SHOP']['ORDER_STEP1_REGISTER_COMMAND']; ?></a>
<input type="hidden" name="FORM_SUBMIT" value="order_register_form" />
<input type="hidden" name="a" value="orderl" id="a" />
<input type="hidden" name="sa" value="<? echo $this->sa; ?>" id="sa" />

</form>