
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>
<?php if ($this->error): ?>

<?php echo $this->error; ?> 
<?php else: ?>

<h1><?php echo $this->question; ?></h1>

<div class="ce_text block">
<?php if ($this->addImage): ?>
<div class="image_container"<?php if ($this->margin || $this->float): ?> style="<?php echo $this->margin . $this->float; ?>"<?php endif; ?>>
<?php if ($this->fullsize): ?><a href="<?php echo $this->href; ?>" title="<?php echo $this->alt; ?>" rel="lightbox"><?php endif; ?>
<img src="<?php echo $this->src; ?>"<?php echo $this->imgSize; ?> alt="<?php echo $this->alt; ?>" /><?php if ($this->fullsize): ?></a><?php endif; ?> 
<?php if ($this->caption): ?>
<div class="caption"><?php echo $this->caption; ?></div>
<?php endif; ?>
</div>
<?php endif; ?>
<?php echo $this->answer; ?>
</div>
<?php if ($this->enclosure): ?>

<div class="enclosure">
<img src="<?php echo $this->enclosureIcon; ?>"<?php echo $this->enclosureSize; ?> alt="<?php echo $this->enclosureTitle; ?>" class="mime_icon" /> <a href="<?php echo $this->enclosureHref; ?>" title="<?php echo $this->enclosureTitle; ?>"><?php echo $this->enclosureLink; ?></a>
</div>
<?php endif; ?>

<p class="info"><?php echo $this->info; ?></p>
</div>
<?php endif; ?>

<p class="back"><a href="<?php echo $this->referer; ?>" title="<?php echo $this->back; ?>"><?php echo $this->back; ?></a></p>
