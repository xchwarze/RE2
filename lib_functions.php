<?php
/*
DSR! Exploits Utils v4
For Private use only!
*/


############################
# Normal Random Functions
############################
function rand_text($len) {
    $chars = 'qwQWepEPasASdfDFghGHjkJKlrLRtyTYuiUIozOZxcXCvbVBnmNM';
    $string = '';
    for ($i = 0; $i < $len; $i++) {
        $pos = mt_rand(0, strlen($chars) - 1);
        $string .= $chars{$pos};
    }
    return $string;
}

function rand_text_array($min, $max, $total) {
    $chars = 'qwQWepEPasASdfDFghGHjkJKlrLRtyTYuiUIozOZxcXCvbVBnmNM';
    $srt_array = array();
    for ($a = 0; $a <= $total; $a++) {
        $string = '';
        $random = mt_rand($min, $max);
        for ($i = 0; $i < $random; $i++) {
            $pos = mt_rand(0, strlen($chars) - 1);
            $string .= $chars{$pos};
        }
        $srt_array[] = $string;
    }
    return $srt_array;
}

function rand_alpha($len) {
    $chars = 'qwQW0epEP1asAS2dfDF3ghGH4jkJK5lrLR6tyTY7uiUI8ozOZ9xcXCvbVBnmNM';
    $string = '';
    for ($i = 0; $i < $len; $i++) {
        $pos = mt_rand(0, strlen($chars) - 1);
        $string .= $chars{$pos};
    }
    return $string;
}

function rand_alpha_array($min, $max, $total) {
    $chars = 'qwQW0epEP1asAS2dfDF3ghGH4jkJK5lrLR6tyTY7uiUI8ozOZ9xcXCvbVBnmNM';
    $srt_array = array();
    for ($a = 0; $a <= $total; $a++) {
        $string = '';
        $random = mt_rand($min, $max);
        for ($i = 0; $i < $random; $i++) {
            $pos = mt_rand(0, strlen($chars) - 1);
            $string .= $chars{$pos};
        }
        $srt_array[] = $string;
    }
    return $srt_array;
}


############################
# JS Obfuscate Functions
############################
function JSObfuscate($JS, $variables, $min = 16, $max = 32) {
	$new_variables = rand_text_array($min, $max, count($variables));
	return str_replace($variables, $new_variables, $JS);
}

//delete


############################
# ShellCode Functions
############################
//delete


############################
# Junk Functions
############################
//delete


############################
# Misc Functions
############################
//delete

?>