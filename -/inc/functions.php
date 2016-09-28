<?

function generateRandomColor() {
	$mode = 'hsl';
	$hue = rand(0,360);
	$saturation = rand(45,67);
	$lightness = $saturation + rand(-10,10);
	
	return array($mode, $hue, $saturation, $lightness);
}

function convertColor($input) {
	$origHex = $input['hex-o'];
	$origRGB = $input['rgb-o'];
	$origHSL = $input['hsl-o'];
	$newHex = $input['hex'];
	$newRGB = $input['rgb'];
	$newHSL = $input['hsl'];
	
	if($origHex != $newHex) {
		$base = explodeHex($newHex);
	} elseif($origRGB != $newRGB) {
		$base = explodeRGB($newRGB);
	} elseif($origHSL != $newHSL) {
		$base = explodeHSL($newHSL);
	} else {
		$base = explodeHex($origHex);
	}
	
	return $base;
}

function loadPalette($palette) {
	$colors = explode(',',$palette);
	
	if(count($colors)) {
		unset($_SESSION['palette']);
		foreach($colors as $color) {
			$newColor = addToPalette(explodeHex($color));
		}
	}
	
	return $newColor;
}

function clearPalette() {
	session_unset();
}

function addToPalette($hex) {
	$hexC = outputColor($hex);
	$inPalette = false;
	if($_SESSION['palette'] && is_array($_SESSION['palette'])) {
		foreach($_SESSION['palette'] as $color) {
			if($color['hex'] == $hexC) {
				$inPalette = true;
			}
		}
	}

	if(!$inPalette) {
		$addColor = outputAllModes($hex);
		$_SESSION['palette'][] = $addColor;
	}

	return $hex;
}

function removeFromPalette($hex) {
	if($_SESSION['palette']) {
		foreach($_SESSION['palette'] as $color) {
			if($color['hex'] != '#'.$hex) {
				$newPalette[] = $color;
			}
		}
		unset($_SESSION['palette']);
		$_SESSION['palette'] = $newPalette;
	}
}

function explodeRGB($input) {
	$input = trim(str_replace(' ','',$input));
	if (stripos($input, 'rgba') !== false) {
		$rgb = sscanf($input, "rgba(%d, %d, %d, %f)");
	} else {
		$rgb = sscanf($input, "rgb(%d, %d, %d)");
	}

	return array('rgb',$rgb[0],$rgb[1],$rgb[2]);
}

function explodeHSL($input) {
	$input = trim(str_replace(' ','',$input));
	$input = str_replace('%', '', $input);
	if (stripos($input, 'hsla') !== false) {
		$hsl = sscanf($input, "hsla(%d, %d, %d, %f)");
	} else {
		$hsl = sscanf($input, "hsl(%d, %d, %d)");
	}
	
	
	return array('hsl',$hsl[0],$hsl[1],$hsl[2]);
}

function explodeHex($input) {
	$input = trim(str_replace('#','',$input));
	if(strlen($input) > 6) {
		$hex = substr($input, 0, 6);
	} elseif(strlen($input) == 6) {
		$hex = $input;
	} elseif(strlen($input) == 3) {
		$hex = substr($input,0,1).substr($input,0,1);
		$hex.= substr($input,1,1).substr($input,1,1);
		$hex.= substr($input,2,1).substr($input,2,1);
	} else {
		$hex = str_pad($input, 6, "0", STR_PAD_LEFT);
	}

	$r = substr($hex,0,2);
	$g = substr($hex,2,2);
	$b = substr($hex,4,2);

	return array('hex',$r,$g,$b);
}

function outputColor($color) {
	switch($color[0]) {
		case 'hsl':
			$output = "hsla($color[1],$color[2]%,$color[3]%,1)";
			break;
		case 'hex':
			$output = "#$color[1]$color[2]$color[3]";
			break;
		case 'rgb':
			$output = "rgba($color[1],$color[2],$color[3],1)";
			break;
	}
	
	return $output;
}

function outputAllModes($color) {
	$output = array();
	switch($color[0]) {
		case 'hsl':
			$hslA = $color;
			$rgbA = hslToRGB($color);
			$hexA = rgbToHex($rgbA);
			break;
		case 'hex':
			$hexA = $color;
			$rgbA = hexToRGB($color);
			$hslA = rgbToHSL($rgbA);
			break;
		case 'rgb':
			$rgbA = $color;
			$hexA = rgbToHex($color);
			$hslA = rgbToHSL($color);
			break;
	}

	$hsl = outputColor($hslA);
	$rgb = outputColor($rgbA);
	$hex = outputColor($hexA);
	$name = nameThatColor($hslA);

	return array('hex'  => $hex,
	             'rgb'  => $rgb,
	             'hsl'  => $hsl,
	             'name' => $name);
}

function gradeColor($color, $step) {
	switch($color[0]) {
		case 'hsl':
			$hsl = $color;
			break;
		case 'rgb':
			$hsl = rgbToHSL($color);
			break;
		case 'hex':
			$rgb = hexToRGB($color);
			$hsl = rgbToHSL($rgb);
			break;
	}
	$hsl[3] += $step;
	if($hsl[3] > 100) { $hsl[3] = 100; }
	if($hsl[3] < 0) { $hsl[3] = 0; }

	return $hsl;
}

// Thanks to this brilliant 24 Ways article:
// https://24ways.org/2010/calculating-color-contrast/
function getContrastYIQ($hexcolor){
	$r = hexdec(substr($hexcolor,0,2));
	$g = hexdec(substr($hexcolor,2,2));
	$b = hexdec(substr($hexcolor,4,2));
	$yiq = (($r*299)+($g*587)+($b*114))/1000;
	return ($yiq >= 128) ? 'black' : 'white';
}

function nameThatColor($hsl) {
	$name = array();
	switch(true) {
		case ($hsl[2] < 6):
			$name = "grey"; $isGrey = true; break;
		case ($hsl[2] < 11):
			$saturation = "greyish"; break;
		case ($hsl[2] < 26):
			$saturation = "muted"; break;
		case ($hsl[2] < 46):
			$saturation = "flat"; break;
		case ($hsl[2] < 71):
			$saturation = "bright"; break;
		case ($hsl[2] < 85):
			$saturation = "vibrant"; break;
		case ($hsl[2] <= 100):
			$saturation = "lurid"; break;
	}
	switch(true) {
		case ($hsl[3] < 17):
			if($isGrey) {
				$light = "charcoal";
			} else {
				$light = "dark";
			}
			break;
		case ($hsl[3] < 45):
			$light = "deep";
			break;
		case ($hsl[3] < 61):
			if($isGrey) {
				$light = "heather";
			} else {
				$light = "rich";
			}
			break;
		case ($hsl[3] < 75):
			$light = "medium";
			break;
		case ($hsl[3] < 90):
			$light = "light";
			break;
		case ($hsl[3] < 100):
			$light = "faded";
			break;
	}
	if(!$isGrey) {
		switch(true) {
			case ($hsl[1] < 12):
				$name = "red";
				if($hsl[2] < 28) {
					$light = "reddish";
					$name = "brown";
				}
				break;
			case ($hsl[1] < 14):
				$name = "peach";
				if($hsl[2] < 28) {
					$name = "brown";
				}
				break;
			case ($hsl[1] < 22):
				$name = "red orange";
				if($hsl[2] < 28) {
					$name = "brown";
				}
				break;
			case ($hsl[1] < 33):
				$name = "orange";
				if($hsl[2] < 50) {
					$name = "brown";
				}
				break;
			case ($hsl[1] < 40):
				$name = "yellow orange";
				if($hsl[2] < 50) {
					$name = "tan";
				}
				break;
			case ($hsl[1] < 43):
				$name = "gold";
				if($hsl[2] < 50) {
					$light = "golden";
					$name = "tan";
				}
				break;
			case ($hsl[1] < 62):
				$name = "yellow";
				if($hsl[2] < 50) {
					if($hsl[1] < 50) {
						$name = "gold";
					} else {
						$name = "olive";
					}
				}
				break;
			case ($hsl[1] < 77):
				$name = "green yellow";
				if($hsl[2] < 30) {
					$name = "moss";
				}
				break;
			case ($hsl[1] < 89):
				$name = "lime";
				if($hsl[2] < 30) {
					$name = "moss";
				}
				break;
			case ($hsl[1] < 131):
				$name = "green"; break;
			case ($hsl[1] < 144):
				$name = "mint"; break;
			case ($hsl[1] < 165):
				$name = "turquoise"; break;
			case ($hsl[1] < 190):
				$name = "aqua"; break;
			case ($hsl[1] < 196):
				$name = "cyan"; break;
			case ($hsl[1] < 204):
				$name = "baby blue"; break;
			case ($hsl[1] < 225):
				$name = "sky blue"; break;
			case ($hsl[1] < 241):
				$name = "blue"; break;
			case ($hsl[1] < 252):
				$name = "indigo"; break;
			case ($hsl[1] < 262):
				$name = "violet"; break;
			case ($hsl[1] < 279):
				$name = "purple"; break;
			case ($hsl[1] < 291):
				$name = "grape"; break;
			case ($hsl[1] < 300):
				$name = "purplish pink"; break;
			case ($hsl[1] < 323):
				$name = "pink";
				if($hsl[2] < 30) {
					$light="reddish";
					$name = "purple";
				}
				break;
			case ($hsl[1] < 339):
				$name = "rose";
				if($hsl[2] < 30) {
					$name = "mauve";
				}
				break;
			case ($hsl[1] < 354):
				$name = "magenta"; break;
			case ($hsl[1] <= 360):
				$name = "red";
				if($hsl[2] < 17) {
					$light = "reddish";
					$name = "grey";
				} elseif($hsl[2] < 26) {
					$light = "greyish";
					$name = "red";
				} elseif(($hsl[2] > 25) && ($hsl[2] < 60)) {
					if($hsl[3] < 40) {
						$name = "maroon";
					}
				} elseif($hsl[3] > 59) {
					if($hsl[2] > 50) {
						$name = "pink";
					}
				}
				break;
		}
	}
	switch(true) {
		case ($hsl[3] == 0):
			$name = "black";
			$saturation = "blackest";
			$light = "of";
			break;
		case ($hsl[3] == 100):
			$name = "white";
			$saturation = "shining";
			$light = "brilliant";
			break;
	}

	$name = $saturation . ' ' . $light . ' ' . $name;
	return $name;
}

/*
	 RGB to HSL (and back again) adapted from here:
	 http://stackoverflow.com/questions/2353211/hsl-to-rgb-color-conversion
*/
function rgbToHSL($rgb) {
	$r = $rgb[1]/255;
	$g = $rgb[2]/255;
	$b = $rgb[3]/255;
	$max = max($r, $g, $b);
	$min = min($r, $g, $b);
	$h = 0;
	$s = 0;
	$l = ($max + $min) / 2;
	
	if($max == $min){
		$h = $s = 0;
	} else {
		$d = $max - $min;
		$s = $l > 0.5 ? $d / (2 - $max - $min) : $d / ($max + $min);
		switch($max) {
			case $r:
				$h = ($g - $b) / $d + ($g < $b ? 6 : 0);
				break;
			case $g: 
				$h = ($b - $r) / $d + 2;
				break;
			case $b:
				$h = ($r - $g) / $d + 4;
				break;
		}
		$h /= 6;
	}
	return array('hsl', round($h*360), round($s*100), round($l*100));
}

function hueToRGB($p, $q, $t) {
	if($t < 0) $t += 1;
	if($t > 1) $t -= 1;
	if($t < 1/6) return $p + ($q - $p) * 6 * $t;
	if($t < 1/2) return $q;
	if($t < 2/3) return $p + ($q - $p) * (2/3 - $t) * 6;

	return $p;
}

function hslToRGB($hsl){
	$r;
	$g;
	$b;
	$h = $hsl[1]/360;
	$s = $hsl[2]/100;
	$l = $hsl[3]/100;
	
	if($s == 0) {
		$r = $g = $b = $l;
	} else {
		$q = $l < 0.5 ? $l * (1 + $s) : $l + $s - $l * $s;
		$p = 2 * $l - $q;
		$r = hueToRGB($p, $q, $h + 1/3);
		$g = hueToRGB($p, $q, $h);
		$b = hueToRGB($p, $q, $h - 1/3);
	}

	return array('rgb', floor($r*255), floor($g*255), floor($b*255));
}

/*
	Hex to RGB (and back again) adapted from here:
	https://bavotasan.com/2011/convert-hex-color-to-rgb-using-php/
*/
function hexToRGB($hex) {	
	$r = hexdec($hex[1]);
	$g = hexdec($hex[2]);
	$b = hexdec($hex[3]);
	return array('rgb', $r, $g, $b);
}

function rgbToHex($rgb) {
	$r = str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
	$g = str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);
	$b = str_pad(dechex($rgb[3]), 2, "0", STR_PAD_LEFT);
	
	return array('hex', $r, $g, $b);
}

?>