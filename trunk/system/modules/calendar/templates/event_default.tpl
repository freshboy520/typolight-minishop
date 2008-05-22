
<h1><?php echo $this->title; ?></h1>
<div class="info">
<?php echo $this->date; ?> 
</div>
<?php if ($this->recurring): ?>
<div class="recurring">
<?php echo $this->recurring; if ($this->until): ?> <?php echo $this->until; endif; ?>.
</div>
<?php endif; ?>
<div class="ce_text">
<?php echo $this->details; ?>
</div>
