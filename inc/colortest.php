<?php

require_once "color.php";

$color = new SimpleBlog_Color('#1B6CA8');
$colorHover = $color->withL($color->getL() - 10);

var_dump($colorHover->getRgbString());
