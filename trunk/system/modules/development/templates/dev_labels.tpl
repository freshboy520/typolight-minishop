
<div id="tl_buttons">
<a href="<?php echo $this->href; ?>" class="header_back" title="<?php echo $this->title; ?>"><?php echo $this->button; ?></a>
</div>

<div id="tl_extension">

<h2 class="sub_headline"><?php echo $this->headline; ?></h2><?php echo $this->message; ?> 
<?php if (!$this->files): ?>

<form action="<?php echo $this->action; ?>" class="tl_form" method="post">
<div class="tl_formbody_edit">
<input type="hidden" name="FORM_SUBMIT" value="tl_labels" />
<div class="tl_tbox">
  <h3><label for="ctrl_language"><?php echo $this->label; ?></label></h3>
  <select name="language" id="ctrl_language" class="tl_select" onfocus="Backend.getScrollOffset();"><?php echo $this->options; ?></select><?php if ($this->help): ?> 
  <p class="tl_help"><?php echo $this->help; ?></p><?php endif; ?> 
</div>
</div>
<div class="tl_submit_container">
<input type="submit" name="clear" id="clear" class="tl_submit" alt="clear cache tables" value="<?php echo $this->submit; ?>" /> 
</div>
</form>
<?php else: ?>

<div class="tl_labels_container">
<?php foreach ($this->files as $strGroup=>$arrFiles): ?>

<h3>system/modules/<?php echo $strGroup; ?></h3>
<?php foreach ($arrFiles as $strFile=>$arrFile): $i=0; ?>
<?php if (is_null($arrFile)): ?>

<div class="tl_labels error">
<h4><?php echo $strFile; ?></h4>
<?php echo $this->error; ?> 
</div>
<?php elseif (count($arrFile)): ?>

<div class="tl_labels warning">
<h4><?php echo $strFile; ?></h4>
<?php echo $this->warning; ?> 

<table cellpadding="0" cellspacing="0" summary="missing labels"><?php foreach ($arrFile as $strKey=>$arrLabels): ?> 
  <tr>
    <td class="<?php echo (($i%2) == 0) ? 'even' : 'odd'; ?>"><?php echo $strKey; ?></td>
    <td class="<?php echo (($i++%2) == 0) ? 'even' : 'odd'; ?>"><?php echo implode('<br />', (array) $arrLabels); ?></td>
  </tr><?php endforeach; ?> 
</table>

</div>
<?php else: ?>

<div class="tl_labels ok">
<h4><?php echo $strFile; ?></h4>
<?php echo $this->ok; ?> 
</div>
<?php endif; endforeach; endforeach; ?>

</div>
<?php endif; ?>

</div>
