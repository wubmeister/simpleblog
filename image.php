<?php

$tint = "#ccc";

function hexToHsl($hex) {
    $hex = str_replace('#', '', $hex);

    $red = strlen($hex) == 3 ? intval($hex[0], 16) * 17 : intval(substr($hex, 0, 2), 16);
    $green = strlen($hex) == 3 ? intval($hex[1], 16) * 17 : intval(substr($hex, 2, 2), 16);
    $blue = strlen($hex) == 3 ? intval($hex[2], 16) * 17 : intval(substr($hex, 4, 2), 16);

    $min = min($red, $green, $blue);
    $max = max($red, $green, $blue);

    if ($min == $max) {
        return [ 'h' => 0, 's' => 0, 'l' => round(100 * $min / 255) ];
    }

    $delta = $max - $min;
    $sat = round(100 * $delta / $max);

    // 0    red
    // 60   yellow
    // 120  green
    // 180  teal
    // 240  blue
    // 300  purple

    $lgt = $max < 255 ? round(50 * $max / 255) : round(100 - (50 * $delta / 255));

    if ($red == $max) {
        if ($green == $min) {
            // 300 - 360
            $hue = round(300 + 60 * ($red - $blue) / $delta) % 360;
        } else {
            // 0 - 60
            $hue = 60 - round(60 * ($red - $green) / $delta);
        }
    } else if ($green == $max) {
        if ($red == $min) {
            // 120 - 180
            $hue = round(180 - 60 * ($green - $blue) / $delta);
        } else {
            // 60 - 120
            $hue = round(60 + 60 * ($green - $red) / $delta);
        }
    } else if ($blue == $max) {
        if ($red == $min) {
            // 180 - 240
            $hue = round(180 + 60 * ($blue - $green) / $delta);
        } else {
            // 180 - 240
            $hue = round(240 + 60 * ($blue - $red) / $delta);
        }
    }

    return [
        'h' => $hue,
        's' => $sat,
        'l' => $lgt
    ];
}

function hslToHex($hsl) {
    $red = 0;
    $green = 0;
    $blue = 0;

    // 0    red
    // 60   yellow
    // 120  green
    // 180  teal
    // 240  blue
    // 300  purple

    // s = d / m
    // d = s * m

    $max = $hsl['l'] <= 50 ? round(255 * ($hsl['l'] / 50)) : 255;
    $min = $hsl['l'] <= 50 ? 0 : round(255 * (($hsl['l'] - 50) / 50));
    // $delta = ($hsl['s'] / 100) * $max

    $offset = (255 - $min) / 2 * (1 - $hsl['s'] / 100);
    $min += $offset;
    $max -= $offset;
    $delta = $max - $min;

    if ($hsl['h'] <= 60) {
        // Red - Yellow
        $red = $max;
        $green = $min + $delta * $hsl['h'] / 60;
        $blue = $min;
    } else if ($hsl['h'] <= 120) {
        // Yellow - Green
        $red = $max - ($delta * ($hsl['h'] - 60) / 60);
        $green = $max;
        $blue = $min;
    } else if ($hsl['h'] <= 180) {
        // Green - Teal
        $red = $min;
        $green = $max;
        $blue = $min + $delta * ($hsl['h'] - 120) / 60;
    } else if ($hsl['h'] <= 240) {
        // Teal - Blue
        $red = $min;
        $green = $max - ($delta * ($hsl['h'] - 180) / 60);
        $blue = $max;
    } else if ($hsl['h'] <= 300) {
        // Blue - Purple
        $red = $min + $delta * ($hsl['h'] - 240) / 60;
        $green = $min;
        $blue = $max;
    } else if ($hsl['h'] <= 360) {
        // Purple - Red
        $red = $max;
        $green = $min;
        $blue = $max - ($delta * ($hsl['h'] - 180) / 60);
    }

    return sprintf('#%02X%02X%02X', $red, $green, $blue);
}

$color = '#EEEEEE';
if (isset($_GET['color']) && $_GET['color']) {
    $tint = $_GET['color'];
    // $hsl = hexToHsl($tint);
    // $hsl['l'] = 93;
    // $color = hslToHex($hsl);
    $color = ($tint[0] == '#' ? '' : '#') . $tint;
}

if (isset($_GET['size'])) {
    $width = (int)$_GET['size'];
    $height = $width;
} else {
    $width = (isset($_GET['width'])) ? (int)$_GET['width'] : 800;
    $height = (isset($_GET['height'])) ? (int)$_GET['height'] : round($width * .75);
}

$svg = "<svg version=\"1.1\" viewBox=\"0 0 {$width} {$height}\" xmlns=\"http://www.w3.org/2000/svg\">";
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
$svg .= "</svg>";


header('Content-Type: image/svg+xml');
echo $svg;
