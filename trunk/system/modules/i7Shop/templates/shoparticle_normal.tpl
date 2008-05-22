<div class="shop_article normal">
	<div class="image">
		<div class="inner">
			<? if($this->image);?><a href="<? echo $this->imageFullSize; ?>" rel="lightbox"><img src="<? echo $this->image; ?>" alt="<? echo $this->article->title; ?>"/></a>
		</div>
	</div>
	<div class="description">
		<div class="inner">
			<h3><? echo $this->article->title; ?></h3>
			<div class="description block"><p class="inner"><? echo $this->article->text; ?></p></div>
			<div class="price block"><? echo $this->price; ?></div>
			<div class="action block">
				<a href="<? echo $this->addToBasketLink; ?>"><? echo $this->addToBasketText; ?></a>
			</div>
		</div>
	</div>
	<div class="clear"></div>
</div>
