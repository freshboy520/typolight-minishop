
<div class="event<?php echo $this->class; ?>">
<span class="date"><?php echo $this->date; ?></span>
<a href="<?php echo $this->href; ?>" title="<?php echo $this->title; ?> (<?php if ($this->day): echo $this->day; ?>, <?php endif; echo $this->date; if ($this->time): ?>, <?php echo $this->time; endif;?>)"><?php echo $this->title; ?></a>
<?php if ($this->time): ?>
<span class="time">(<?php echo $this->time; ?>)</span>
<?php endif; ?>
</div>
