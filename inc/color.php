<?php

class SimpleBlog_Color
{
    protected $alpha = 1;

    protected $red;
    protected $green;
    protected $blue;

    protected $hue;
    protected $saturation;
    protected $lightness;

    protected $l;
    protected $a;
    protected $b;

    protected $hasRgb = false;
    protected $hasHsl = false;
    protected $hasLab = false;

    const rgb2xyzMatrix = [ 0.4360747,  0.3850649,  0.1430804,
                            0.2225045,  0.7168786,  0.0606169,
                            0.0139322,  0.0971045,  0.7141733 ];

    const xyz2rgbMatrix = [ 3.1338561, -1.6168667, -0.4906146,
                           -0.9787684,  1.9161415,  0.0334540,
                            0.0719453, -0.2289914,  1.4052427 ];

    public function __construct($color = null)
    {
        if (is_string($color)) {
            if ($color[0] == '#') {
                $this->setHexValue($color);
            } else if (substr($color, 0, 3) == 'rgb') {
                $this->setRgbStringValue($color);
            } else if (substr($color, 0, 3) == 'hsl') {
                $this->setHslStringValue($color);
            }
        } else if (is_array($color)) {
            if (isset($color['r']) && isset($color['g']) && isset($color['b'])) {
                $this->setRgbValue($color['r'], $color['g'], $color['b'], isset($color['a']) ? $color['a'] : 1);
            }
            else if (isset($color['red']) && isset($color['green']) && isset($color['blue'])) {
                $this->setRgbValue($color['red'], $color['green'], $color['blue'], isset($color['alpha']) ? $color['alpha'] : 1);
            }
            else if (isset($color['h']) && isset($color['s']) && isset($color['l'])) {
                $this->setHslValue($color['h'], $color['s'], $color['l'], isset($color['a']) ? $color['a'] : 1);
            }
            else if (isset($color['hue']) && isset($color['saturation']) && isset($color['lightness'])) {
                $this->setHslValue($color['hue'], $color['saturation'], $color['lightness'], isset($color['alpha']) ? $color['alpha'] : 1);
            }
            else if (isset($color['l']) && isset($color['a']) && isset($color['b'])) {
                $this->setLabValue($color['l'], $color['a'], $color['b'], isset($color['alpha']) ? $color['alpha'] : 1);
            }
        }
    }

    public function setHexValue($hex)
    {
        if ($hex[0] == '#') $hex = substr($hex, 1);
        $length = strlen($hex);
        if ($length == 6 || $length == 8) {
            $this->setRgbValue(
                intval(substr($hex, 0, 2), 16),
                intval(substr($hex, 2, 2), 16),
                intval(substr($hex, 4, 2), 16),
                $length == 8 ? intval(substr($hex, 6, 2), 16) / 255 : 1
            );
        } else if ($length == 3 || $length == 4) {
            $this->setRgbValue(
                intval($hex[0], 16) * 17,
                intval($hex[1], 16) * 17,
                intval($hex[2], 16) * 17,
                $length == 4 ? (intval($hex[3], 16) * 17) / 255 : 1
            );
        } else {
            throw new Exception("Invalid hexadecimal value: '{$hex}'. Value should be 3, 4, 6 or 8 characters long.");
        }
    }

    public function setRgbStringValue($rgb)
    {
        if (preg_match('/rgba?\s*\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(,\s*((\d*\.)?\d+)\s*)?\)/', $rgb, $match)) {
            $this->setRgbValue(
                (int)$match[1],
                (int)$match[2],
                (int)$match[3],
                count($match) > 4 && $match[4] ? (float)$match[5] : 1
            );
        } else {
            throw new Exception("Invalid rgb(a) value: '{$rgb}'.");
        }
    }

    public function setHslStringValue($rgb)
    {
        if (preg_match('/hsla?\s*\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(,\s*((\d*\.)?\d+)\s*)?\)/', $rgb, $match)) {
            $this->setHslValue(
                (int)$match[1],
                (int)$match[2],
                (int)$match[3],
                count($match) > 4 && $match[4] ? (float)$match[5] : 1
            );
        } else {
            throw new Exception("Invalid rgb(a) value: '{$rgb}'.");
        }
    }

    public function setRgbValue(int $red, int $green, int $blue, float $alpha = 1)
    {
        $this->hasRgb = true;

        $this->red = $red;
        $this->green = $green;
        $this->blue = $blue;
        $this->setAlpha($alpha);

        return $this;
    }

    public function setHslValue(int $hue, int $saturation, int $lightness, float $alpha = 1)
    {
        $this->hasHsl = true;

        $this->hue = $hue;
        $this->saturation = $saturation;
        $this->lightness = $lightness;
        $this->setAlpha($alpha);

        return $this;
    }

    public function setLabValue(int $l, int $a, int $b, float $alpha = 1)
    {
        $this->hasLab = true;

        $this->l = $l;
        $this->a = $a;
        $this->b = $b;
        $this->setAlpha($alpha);

        return $this;
    }

    public function setAlpha(float $alpha)
    {
        if ($alpha < 0) $alpha = 0;
        if ($alpha > 1) $alpha = 1;
        $this->alpha = $alpha;
    }

    public function getAlpha()
    {
        return $this->alpha;
    }

    public function getRed()
    {
        if (!$this->hasRgb) $this->calcRgb();
        return $this->red;
    }

    public function getGreen()
    {
        if (!$this->hasRgb) $this->calcRgb();
        return $this->green;
    }

    public function getBlue()
    {
        if (!$this->hasRgb) $this->calcRgb();
        return $this->blue;
    }

    public function getHue()
    {
        if (!$this->hasHsl) $this->calcHsl();
        return $this->hue;
    }

    public function getSaturation()
    {
        if (!$this->hasHsl) $this->calcHsl();
        return $this->saturation;
    }

    public function getLightness()
    {
        if (!$this->hasHsl) $this->calcHsl();
        return $this->lightness;
    }

    public function getL()
    {
        if (!$this->hasLab) $this->calcLab();
        return $this->l;
    }

    public function getA()
    {
        if (!$this->hasLab) $this->calcLab();
        return $this->a;
    }

    public function getB()
    {
        if (!$this->hasLab) $this->calcLab();
        return $this->b;
    }

    public function withRed(int $red)
    {
        if (!$this->hasRgb) $this->calcRgb();
        $color = new self();
        $color->setRgbValue($red, $this->green, $this->blue);
        return $color;
    }

    public function withGreen(int $green)
    {
        if (!$this->hasRgb) $this->calcRgb();
        $color = new self();
        $color->setRgbValue($this->red, $green, $this->blue);
        return $color;
    }

    public function withBlue(int $blue)
    {
        if (!$this->hasRgb) $this->calcRgb();
        $color = new self();
        $color->setRgbValue($this->red, $this->green, $blue);
        return $color;
    }

    public function withHue(int $hue)
    {
        if (!$this->hasHsl) $this->calcHsl();
        $color = new self();
        $color->setHslValue($hue, $this->saturation, $this->lightness);
        return $color;
    }

    public function withSaturation(int $saturation)
    {
        if (!$this->hasHsl) $this->calcHsl();
        $color = new self();
        $color->setHslValue($this->hue, $saturation, $this->lightness);
        return $color;
    }

    public function withLightness(int $lightness)
    {
        if (!$this->hasHsl) $this->calcHsl();
        $color = new self();
        $color->setHslValue($this->hue, $this->saturation, $lightness);
        return $color;
    }

    public function withL(int $l)
    {
        if (!$this->hasLab) $this->calcLab();
        $color = new self();
        $color->setLabValue($l, $this->a, $this->b);
        return $color;
    }

    public function withA(int $a)
    {
        if (!$this->hasLab) $this->calcLab();
        $color = new self();
        $color->setLabValue($this->l, $a, $this->b);
        return $color;
    }

    public function withB(int $b)
    {
        if (!$this->hasLab) $this->calcLab();
        $color = new self();
        $color->setLabValue($this->l, $this->a, $b);
        return $color;
    }

    public function mixedWith(SimpleBlog_Color $other, float $amount = 0.5)
    {
        if ($amount < 0) $amount = 0;
        if ($amount > 1) $amount = 1;

        if (!$this->hasRgb) $this->calcRgb();
        $newColor = new self();
        $myAmount = 1 - $amount;
        $newColor->setRgbValue(
            $this->red * $myAmount + $other->getRed() * $amount,
            $this->green * $myAmount + $other->getGreen() * $amount,
            $this->blue * $myAmount + $other->getBlue() * $amount
        );

        return $newColor;
    }

    public function getLuminocity()
    {
        if (!$this->hasRgb) $this->calcRgb();
        return round(100 * (0.21 * $this->red + 0.72 * $this->green + 0.07  * $this->blue) / 255);
    }

    public function getHexString($withHash = true, $withAlpha = false)
    {
        if (!$this->hasRgb) $this->calcRgb();
        $hex = sprintf("%02X%02X%02X", $this->red, $this->green, $this->blue);
        if ($withAlpha) {
            if ($withAlpha === 'force' || $this->alpha < 1) {
                $alpha = round($this->alpha * 255);
                $hex .= sprintf("%02X", $alpha);
            }
        }

        return ($withHash ? '#' : '') . $hex;
    }

    public function getRgbString($withAlpha = true)
    {
        if (!$this->hasRgb) $this->calcRgb();
        $rgb = 'rgb';
        if ($withAlpha === 'force' || $this->alpha < 1) $rgb .= 'a';
        $rgb .= "({$this->red}, {$this->green}, {$this->blue}";
        if ($withAlpha === 'force' || $this->alpha < 1) $rgb .= ", {$this->alpha}";
        $rgb .= ')';
        return $rgb;
    }

    public function getRawRgbString()
    {
        if (!$this->hasRgb) $this->calcRgb();
        return "{$this->red}, {$this->green}, {$this->blue}";
    }

    public function getHslString($withAlpha)
    {
        if (!$this->hasHsl) $this->calcHsl();
        $hsl = 'hsl';
        if ($withAlpha === 'force' || $this->alpha < 1) $hsl .= 'a';
        $hsl .= "({$this->hue}, {$this->saturation}, {$this->lightness}";
        if ($withAlpha === 'force' || $this->alpha < 1) $hsl .= ", {$this->alpha}";
        $hsl .= ')';
        return $hsl;
    }

    protected function calcRgbFromHsl()
    {
        $max = $this->lightness <= 50 ? round(255 * ($this->lightness / 50)) : 255;
        $min = $this->lightness <= 50 ? 0 : round(255 * (($this->lightness - 50) / 50));

        $offset = (255 - $min) / 2 * (1 - $this->saturation / 100);
        $min += $offset;
        $max -= $offset;
        $delta = $max - $min;

        if ($this->hue <= 60) {
            // Red - Yellow
            $this->red = $max;
            $this->green = $min + $delta * $this->hue / 60;
            $this->blue = $min;
        } else if ($this->hue <= 120) {
            // Yellow - Green
            $this->red = $max - ($delta * ($this->hue - 60) / 60);
            $this->green = $max;
            $this->blue = $min;
        } else if ($this->hue <= 180) {
            // Green - Teal
            $this->red = $min;
            $this->green = $max;
            $this->blue = $min + $delta * ($this->hue - 120) / 60;
        } else if ($this->hue <= 240) {
            // Teal - Blue
            $this->red = $min;
            $this->green = $max - ($delta * ($this->hue - 180) / 60);
            $this->blue = $max;
        } else if ($this->hue <= 300) {
            // Blue - Purple
            $this->red = $min + $delta * ($this->hue - 240) / 60;
            $this->green = $min;
            $this->blue = $max;
        } else if ($this->hue <= 360) {
            // Purple - Red
            $this->red = $max;
            $this->green = $min;
            $this->blue = $max - ($delta * ($this->hue - 180) / 60);
        }

        $this->hasRgb = true;
    }

    protected function calcHslFromRgb()
    {
        $min = min($this->red, $this->green, $this->blue);
        $max = max($this->red, $this->green, $this->blue);

        if ($min == $max) {
            $this->hue = 0;
            $this->saturation = 0;
            $this->lightness = round(100 * $min / 255);
        } else {

            $delta = $max - $min;
            $this->saturation = round(100 * $delta / $max);
            $this->lightness = $max < 255 ? round(50 * $max / 255) : round(100 - (50 * $delta / 255));

            if ($this->red == $max) {
                if ($this->green == $min) {
                    // 300 - 360
                    $this->hue = round(300 + 60 * ($this->red - $this->blue) / $delta) % 360;
                } else {
                    // 0 - 60
                    $this->hue = 60 - round(60 * ($this->red - $this->green) / $delta);
                }
            } else if ($this->green == $max) {
                if ($this->red == $min) {
                    // 120 - 180
                    $this->hue = round(180 - 60 * ($this->green - $this->blue) / $delta);
                } else {
                    // 60 - 120
                    $this->hue = round(60 + 60 * ($this->green - $this->red) / $delta);
                }
            } else if ($this->blue == $max) {
                if ($this->red == $min) {
                    // 180 - 240
                    $this->hue = round(180 + 60 * ($this->blue - $this->green) / $delta);
                } else {
                    // 180 - 240
                    $this->hue = round(240 + 60 * ($this->blue - $this->red) / $delta);
                }
            }
        }

        $this->hasHsl = true;
    }

    protected static function correctChannel($c) {
        if ($c > 0.04045)
            $c = pow(($c + 0.055) / 1.055, 2.4);
        else
            $c = $c / 12.92;
        return $c;
    }

    protected static function correctChannelInv($c) {
        if ($c > 0.0031308)
            $c = pow($c, 1 / 2.4) * 1.055 - 0.055;
        else
            $c = $c * 12.92;
        return min(1, max(0, $c));
    }

    protected static function f($t) {
        $d = 6 / 29;
        $d3 = $d * $d * $d;

        if ($t > $d3) return pow($t, 1/3);
        return ($t / (3 * $d3)) + (4 / 29);
    }

    protected static function fInv($t) {
        $d = 6/29;
        $d2 = $d * $d;
        if ($t > $d) return $t * $t * $t;
        return ($t - 4 / 29) * 3 * $d2;
    }

    protected static function rgbToXyz($r, $g, $b)
    {
        $m = self::rgb2xyzMatrix;

        $r = self::correctChannel($r / 255);
        $g = self::correctChannel($g / 255);
        $b = self::correctChannel($b / 255);

        return [
            'x' => $r * $m[0] + $g * $m[1] + $b * $m[2],
            'y' => $r * $m[3] + $g * $m[4] + $b * $m[5],
            'z' => $r * $m[6] + $g * $m[7] + $b * $m[8]
        ];
    }

    protected static function labToXyz($l, $a, $b)
    {
        return [
            'x' => 0.964212 * self::fInv(($l + 16) / 116 + $a / 500),
            'y' => self::fInv(($l + 16) / 116),
            'z' => 0.825188 * self::fInv(($l + 16) / 116 - $b / 200)
        ];
    }

    protected function calcRgbFromLab()
    {
        $xyz = self::labToXyz($this->l, $this->a, $this->b);

        $m = self::xyz2rgbMatrix;

        $this->red = round(255 * self::correctChannelInv($xyz['x'] * $m[0] + $xyz['y'] * $m[1] + $xyz['z'] * $m[2]));
        $this->green = round(255 * self::correctChannelInv($xyz['x'] * $m[3] + $xyz['y'] * $m[4] + $xyz['z'] * $m[5]));
        $this->blue = round(255 * self::correctChannelInv($xyz['x'] * $m[6] + $xyz['y'] * $m[7] + $xyz['z'] * $m[8]));

        $this->hasRgb = true;
    }

    protected function calcLabFromRgb()
    {
        $xyz = self::rgbToXyz($this->red, $this->green, $this->blue);

        $this->l = 116 * self::f($xyz['y']) - 16;
        $this->a = 500 * (self::f($xyz['x'] / 0.964212) - self::f($xyz['y']));
        $this->b = 200 * (self::f($xyz['y']) - self::f($xyz['z'] / 0.825188));

        $this->hasLab = true;
    }

    protected function calcRgb()
    {
        if ($this->hasHsl) {
            $this->calcRgbFromHsl();
        } else if ($this->hasLab) {
            $this->calcRgbFromLab();
        } else {
            $this->red = 0;
            $this->green = 0;
            $this->blue = 0;
            $this->hasRgb = true;
        }
    }

    protected function calcHsl()
    {
        if (!$this->hasRgb) {
            $this->calcRgb();
        }
        $this->calcHslFromRgb();
    }

    protected function calcLab()
    {
        if (!$this->hasRgb) {
            $this->calcRgb();
        }
        $this->calcLabFromRgb();
    }
}
