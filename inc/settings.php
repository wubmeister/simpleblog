<?php

require_once "color.php";

function simpleblog_generate_settings_cache($pageBgColor, $primaryColor, $secondaryColor, $textColor, $linkColor)
{
    /*
    Derived settings:

    - Link color:                   primary_color
    - Link hover color:             link_color - lab(10, 0, 0)
    - Primary hover color:          primary_color - lab(10, 0, 0)
    - Primary soft color:           primary_color * .2 + white * .8
    - Primary soft hover color:     primary_soft_color - lab(10, 0, 0)
    - Secondary hover color:        secondary_color - lab(10, 0, 0)
    - Secondary soft color:         secondary_color * .2 + white * .8
    - Secondary soft hover color:   secondary_soft_color - lab(10, 0, 0)
    - Nav colors:                   based on primary_color

    */

    if (!$pageBgColor) $pageBgColor = '#FFFFFF';
    if (!$primaryColor) $primaryColor = '#1B6CA8';
    if (!$secondaryColor) $secondaryColor = '#0A97B0';
    if (!$textColor) $textColor = '#000000';
    if (!$linkColor) $linkColor = $primaryColor;

    $pbg = new SimpleBlog_Color($pageBgColor);
    $pc = new SimpleBlog_Color($primaryColor);
    $sc = new SimpleBlog_Color($secondaryColor);
    $tc = new SimpleBlog_Color($textColor);
    $lc = new SimpleBlog_Color($linkColor);

    $white = new SimpleBlog_Color("#FFF");

    $lcHover = $lc->withL($lc->getL() - 10);
    $pcHover = $pc->withL($pc->getL() - 10);
    $pcSoft = $pc->mixedWith($white, 0.8);
    $pcSoftHover = $pcSoft->withL($pcSoft->getL() - 10);
    $scHover = $sc->withL($sc->getL() - 10);
    $scSoft = $sc->mixedWith($white, 0.8);
    $scSoftHover = $scSoft->withL($scSoft->getL() - 10);

    $data = [
        'key' => md5("{$pageBgColor}{$primaryColor}{$secondaryColor}{$textColor}{$linkColor}"),
        'page_bg_color' => $pbg->getRgbString(),
        'page_bg_color_rgb' => $pbg->getRawRgbString(),
        'primary_color' => $pc->getRgbString(),
        'primary_color_rgb' => $pc->getRawRgbString(),
        'primary_color_hover' => $pcHover->getRgbString(),
        'primary_counter_color' => $pc->getLuminocity() <= 75 ? $white->getRgbString() : $tc->getRgbString(),
        'primary_counter_color_rgb' => $pc->getLuminocity() <= 75 ? $white->getRawRgbString() : $tc->getRawRgbString(),
        'primarysoft_color' => $pcSoft->getRgbString(),
        'primarysoft_color_hover' => $pcSoftHover->getRgbString(),
        'secondary_color' => $sc->getRgbString(),
        'secondary_color_hover' => $scHover->getRgbString(),
        'secondary_counter_color' => $sc->getLuminocity() <= 75 ? $white->getRgbString() : $tc->getRgbString(),
        'secondary_counter_color_rgb' => $sc->getLuminocity() <= 75 ? $white->getRawRgbString() : $tc->getRawRgbString(),
        'secondarysoft_color' => $scSoft->getRgbString(),
        'secondarysoft_color_hover' => $scSoftHover->getRgbString(),
        'text_color' => $tc->getRgbString(),
        'text_color_rgb' => $tc->getRawRgbString(),
        'link_color' => $lc->getRgbString(),
        'link_color_hover' => $lcHover->getRgbString(),
    ];

    $php = '<' . '?php' . PHP_EOL . PHP_EOL;
    $php .= 'return [' . PHP_EOL;
    foreach ($data as $key => $value) {
        $php .= "    '{$key}' => '{$value}',\n";
    }
    $php .= '];' . PHP_EOL;

    file_put_contents(__DIR__ . '/settings_cache.php', $php);

    return $data;
}

function simpleblog_get_color_settings()
{
    static $colors = null;

    if (!$colors) {

        $pageBgColor = get_theme_mod('simpleblog_page_bg_color');
        $primaryColor = get_theme_mod('simpleblog_primary_color');
        $secondaryColor = get_theme_mod('simpleblog_secondary_color');
        $textColor = get_theme_mod('simpleblog_text_color');
        $linkColor = get_theme_mod('simpleblog_link_color');
        $key = md5("{$pageBgColor}{$primaryColor}{$secondaryColor}{$textColor}{$linkColor}");

        $data = null;
        // if (file_exists(__DIR__ . '/settings_cache.php')) {
        //     $data = include("settings_cache.php");
        // }

        if (!$data || $data['key'] != $key) {
            $data = simpleblog_generate_settings_cache($pageBgColor, $primaryColor, $secondaryColor, $textColor, $linkColor);
        }

        $colors = $data;
    }

    return $colors;
}

function simpleblog_get_color($key, $convertToHex = false)
{
    $colors = simpleblog_get_color_settings();
    if (!isset($colors[$key])) return "";

    $color = $colors[$key];

    if ($convertToHex) {
        if (preg_match('/rgba?\((\d{1,3}),\s(\d{1,3}),\s(\d{1,3})/', $color, $match)) {
            $color = sprintf(
                '%02X%02X%02X',
                (int)$match[1],
                (int)$match[2],
                (int)$match[3]
            );
        } else {
            // $color = "";
        }
    }
    return $color;
}
