<h2><? echo $GLOBALS['TL_LANG']['i7SHOP']['BASKET_TITLE']; ?></h2>
<form action="<? echo $this->formAction; ?>" name="i7shopbasketform">
<? if(count($this->articles)):?>
<table cellpadding="0" cellspacing="0" class="baskettable">
	<tr>
		<th><? echo $GLOBALS['TL_LANG']['i7SHOP']['BASKET_ITEM']; ?></th>
		<th><? echo $GLOBALS['TL_LANG']['i7SHOP']['BASKET_AMOUNT']; ?></th>
		<th class="money"><? echo $GLOBALS['TL_LANG']['i7SHOP']['BASKET_UNITPRICE']; ?></th>
		<th class="money"><? echo $GLOBALS['TL_LANG']['i7SHOP']['BASKET_SUBTOTAL']; ?></th>
		<th>&nbsp;</th>
	</tr>
<? foreach($this->articles as $article){ echo $article; } ?>
<tr class="strong">
	<th><? echo $GLOBALS['TL_LANG']['i7SHOP']['BASKET_TOTAL']; ?></th>
	<th>&nbsp;</th>
	<th>&nbsp;</th>
	<th class="money"><? echo $this->total; ?></th>
	<th></th>
</tr>
</table>
<div class="ordercommand">
<a href="<? echo $this->startOrderLink; ?>" class="steplink"><? echo $GLOBALS['TL_LANG']['i7SHOP']['BASKET_ORDER_COMMAND']; ?></a>
</div>
<? else:  // no articles?>

<? echo $GLOBALS['TL_LANG']['i7SHOP']['BASKET_EMPTY']; ?>

<? endif; ?>
<input type="hidden" name="a" value="basket" id="a" />
<input type="hidden" name="updateBasket" value="true" id="updateBasket" />


<!-- <a href="javascript:;" onclick="document.i7shopbasketform.submit()">update basket</a> -->
</form>