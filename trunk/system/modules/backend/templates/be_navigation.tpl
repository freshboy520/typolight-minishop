<ul class="tl_level_2"><?php foreach ($this->modules as $strModule=>$arrConfig): ?> 
  <li><a href="typolight/main.php?do=<?php echo $strModule; ?>" class="<?php echo $arrConfig['class']; ?>" title="<?php echo $arrConfig['title']; ?>"<?php echo $arrConfig['icon']; ?> onclick="this.blur();"><?php echo $arrConfig['label']; ?></a></li><?php endforeach; ?> 
</ul>