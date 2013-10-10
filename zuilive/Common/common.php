<?php
	//格式化函数，将可以将字节（bytes）转换成想要的格式单位
	function byteFormat($bytes, $unit = "", $decimals = 2) {
		$units = array('B' => 0, 'KB' => 1, 'MB' => 2, 'GB' => 3, 'TB' => 4, 'PB' => 5, 'EB' => 6, 'ZB' => 7, 'YB' => 8);

		$value = 0;
		if ($bytes > 0) {
			// Generate automatic prefix by bytes 
			// If wrong prefix given
			if (!array_key_exists($unit, $units)) {
				$pow = floor(log($bytes)/log(1024));
				$unit = array_search($pow, $units);
			}

			// Calculate byte value by prefix
			$value = ($bytes/pow(1024,floor($units[$unit])));
		}

		// If decimals is not numeric or decimals is less than 0 
		// then set default value
		if (!is_numeric($decimals) || $decimals < 0) {
			$decimals = 2;
		}

		// Format output
		return sprintf('%.' . $decimals . 'f '.$unit, $value);
	}

	//生成随机字母和数字 $len随机字符串长度
	function genRandomString($len) 
	{ 
	    $chars = array( 
	        "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",  
	        "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",  
	        "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",  
	        "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",  
	        "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",  
	        "3", "4", "5", "6", "7", "8", "9" 
	    ); 
	    $charsLen = count($chars) - 1; 
	     
	    $output = ""; 
	    for ($i=0; $i<$len; $i++) 
	    { 
	        $output .= $chars[mt_rand(0, $charsLen)]; 
	    } 
	    return $output; 
	} 
?>