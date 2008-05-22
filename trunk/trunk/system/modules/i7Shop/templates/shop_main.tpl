<div class="i7shop">
<?
	switch($this->action) {
?>
<? case "basket":?>
<? echo $this->code; ?>
<? break; // end basket?>

<? case "list":?>
<? if($this->category->categoryTitle): ?><h1><? echo $this->category->categoryTitle;?></h1><?endif;?>

<? foreach($this->articles as $article){ echo $article; } ?>
<? break; // end list?>

<? case "orderl":case "order":case "orderp":case "ordero":case "orderd":case "ordert":?>
<? echo $this->order; ?>
<? break; // end list?>

<? } // end switch?>
</div>


<!--<div class="smallnav">
	<br />
	<a href="<? echo $this->formAction; ?>?a=basket">basket</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<? echo $this->formAction; ?>?a=list">list</a>
</div>
-->