
<div class="layout_full block<?php echo $this->class; ?>">
<h1><?php echo $this->newsHeadline; ?></h1>
<?php if ($this->hasMetaFields): ?>
<p class="info"><?php echo $this->date; ?> <?php echo $this->author; ?> <?php echo $this->commentCount; ?></p>
<?php endif; ?>
<?php if ($this->hasSubHeadline): ?>
<h2><?php echo $this->subHeadline; ?></h2>
<?php endif; ?>
<div class="ce_text">
<?php if ($this->addImage): ?>
<div class="image_container"<?php if ($this->margin || $this->float): ?> style="<?php echo $this->margin . $this->float; ?>"<?php endif; ?>>
<?php if ($this->fullsize): ?><a href="<?php echo $this->href; ?>" title="<?php echo $this->alt; ?>" rel="lightbox"><?php endif; ?>
<img src="<?php echo $this->src; ?>"<?php echo $this->imgSize; ?> alt="<?php echo $this->alt; ?>" /><?php if ($this->fullsize): ?></a><?php endif; ?> 
<?php if ($this->caption): ?>
<div class="caption"><?php echo $this->caption; ?></div>
<?php endif; ?>
</div>
<?php endif; echo $this->text; ?>
</div>
<?php if ($this->enclosure): ?>
<div class="enclosure">
<img src="<?php echo $this->enclosureIcon; ?>"<?php echo $this->enclosureSize; ?> alt="<?php echo $this->enclosureTitle; ?>" class="mime_icon" /> <a href="<?php echo $this->enclosureHref; ?>" title="<?php echo $this->enclosureTitle; ?>"><?php echo $this->enclosureLink; ?></a>
</div>
<?php endif; ?>
</div>
