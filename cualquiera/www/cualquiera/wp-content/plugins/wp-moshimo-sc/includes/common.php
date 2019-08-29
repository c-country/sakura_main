<?php
function space_to_array($str) {
	$str = htmlspecialchars($str);
	$str = mb_convert_kana($str, s, "UTF-8");
	$arrStr = explode(" ", $str);
	return $arrStr;
}

function s_len($text, $len, $after = '...') {
	$text = strip_tags($text);
	$text = mb_strimwidth($text,0,$len,$after);
	return $text;
}

function func_make_words($keyword,$except_words) {
	if(!empty($except_words)) {
		$words = $keyword . " " . func_exceptWords($except_words);
	} else {
		$words = $keyword;
	}
	return $words;
}

function func_exceptWords($text) {
	$text = htmlspecialchars($text);
	$text = mb_convert_kana($text, s);
	$arrText = explode(" ", $text);

	for($i=0; $i<count($arrText); $i++) {
		$exceptWord .= "-".$arrText[$i]." ";
	}
	return trim($exceptWord);
}

function get_content($url) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	ob_start();
	curl_exec($ch);
	curl_close($ch);
	$string = ob_get_contents();
	ob_end_clean();
	return $string;
}
?>