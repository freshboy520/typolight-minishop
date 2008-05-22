<tr class="shop_article">
	<td><? echo $this->article->title; ?></td>
	<td><input name="i7shop_quantity_<? echo $this->article->id; ?>" value="<? echo $this->quantity; ?>" size="3" onchange="document.i7shopbasketform.submit();" /></td>
	<td class="money"><? echo $this->price; ?></td>
	<td class="money"><? echo $this->subtotal; ?></td>
	<td class="action"><a href="<? echo $this->deleteFromBasketLink; ?>"><? echo $GLOBALS['TL_LANG']['i7SHOP']['BASKET_DELETE_COMMAND']; ?></a></td>
</tr>
