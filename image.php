<?php

$color = '#EEEEEE';
if (isset($_GET['color']) && $_GET['color']) {
    $tint = $_GET['color'];
    $color = ($tint[0] == '#' ? '' : '#') . $tint;
}

if (isset($_GET['size'])) {
    $width = (int)$_GET['size'];
    $height = $width;
} else {
    $width = (isset($_GET['width'])) ? (int)$_GET['width'] : 800;
    $height = (isset($_GET['height'])) ? (int)$_GET['height'] : round($width * .75);
}

$svg = "<svg version=\"1.1\" width=\"{$width}\" height=\"{$height}\" viewBox=\"0 0 {$width} {$height}\" xmlns=\"http://www.w3.org/2000/svg\">";

if (isset($_GET['text'])) {
    $svg .= "<defs><style type=\"text/css\"><![CDATA[
    @import url('https://fonts.googleapis.com/css2?family%3DPlayfair+Display:wght%40400;900&display%3Dswap');
    ]]></style></defs>";
}

$svg .= "<rect x=\"0\" y=\"0\" width=\"{$width}\" height=\"{$height}\" fill=\"{$color}\"/>";

$numCircles = rand(4, 7);
$minRadius = min($width, $height) / 16;
$maxRadius = min($width, $height) / 2;
for ($i = 0; $i < $numCircles; $i++) {
    $cx = rand(0, $width);
    $cy = rand(0, $height);
    $radius = rand($minRadius, $maxRadius);
    $alpha = rand(25, 75) / 100;
    $svg .= "<circle cx=\"{$cx}\" cy=\"{$cy}\" r=\"{$radius}\" fill=\"rgba(255,255,255,{$alpha})\"/>";
}

if (isset($_GET['text'])) {
    $cx = $width / 2;
    $cy = ($height / 2) - ($height / 80);
    $fs = $height / 8;
    $svg .= "<text x=\"{$cx}\" y=\"{$cy}\" text-anchor=\"middle\" style=\"font: 900 {$fs}px 'Playfair Display', serif;\">{$_GET['text']}</text>";
}
if (isset($_GET['subtext'])) {
    $fs = $height / 15;
    $cx = $width / 2;
    $cy = ($height / 2) + $fs;
    $svg .= "<text x=\"{$cx}\" y=\"{$cy}\" text-anchor=\"middle\" style=\"font: 400 {$fs}px 'Playfair Display', serif;opacity:.5\">{$_GET['subtext']}</text>";
}

$svg .= "</svg>";


header('Content-Type: image/svg+xml');
echo $svg;
