
<!-- indexer::stop -->
<div class="<?php echo $this->class; ?> block"<?php echo $this->cssID; ?><?php if ($this->style): ?> style="<?php echo $this->style; ?>"<?php endif; ?>>
<?php if ($this->headline): ?>

<<?php echo $this->hl; ?>><?php echo $this->headline; ?></<?php echo $this->hl; ?>>
<?php endif; ?>
<?php foreach($this->faq as $category=>$faqs): ?>

<h2><?php echo $category ?></h2>

<ul>
<?php foreach ($faqs as $faq): ?>
  <li class="<?php echo $faq['class']; ?>"><a href="<?php echo $faq['href']; ?>" title="<?php echo $faq['title']; ?>"><?php echo $faq['question']; ?></a></li>
<?php endforeach; ?>
</ul>
<?php endforeach; ?>

</div>
<!-- indexer::continue -->
