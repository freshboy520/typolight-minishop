
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>

<form action="<?php echo $this->action; ?>" method="post">
<div class="formbody"><?php if ($this->message): ?> 
<p class="<?php echo $this->mclass; ?>"><?php echo $this->message; ?></p><?php endif; ?> 
<input type="hidden" name="FORM_SUBMIT" value="<?php echo $this->formId; ?>" />
<input type="text" name="email" class="text" value="<?php echo $this->email; ?>" />
<input type="submit" name="submit" class="submit" value="<?php echo $this->submit; ?>" />
</div>
</form>

</div>
