<?php
/**
 * Writer Ancora Framework: strings manipulations
 *
 * @package	writer_ancora
 * @since	writer_ancora 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Check multibyte functions
if ( ! defined( 'WRITER_ANCORA_MULTIBYTE' ) ) define( 'WRITER_ANCORA_MULTIBYTE', function_exists('mb_strpos') ? 'UTF-8' : false );

if (!function_exists('writer_ancora_strlen')) {
	function writer_ancora_strlen($text) {
		return WRITER_ANCORA_MULTIBYTE ? mb_strlen($text) : strlen($text);
	}
}

if (!function_exists('writer_ancora_strpos')) {
	function writer_ancora_strpos($text, $char, $from=0) {
		return WRITER_ANCORA_MULTIBYTE ? mb_strpos($text, $char, $from) : strpos($text, $char, $from);
	}
}

if (!function_exists('writer_ancora_strrpos')) {
	function writer_ancora_strrpos($text, $char, $from=0) {
		return WRITER_ANCORA_MULTIBYTE ? mb_strrpos($text, $char, $from) : strrpos($text, $char, $from);
	}
}

if (!function_exists('writer_ancora_substr')) {
	function writer_ancora_substr($text, $from, $len=-999999) {
		if ($len==-999999) { 
			if ($from < 0)
				$len = -$from; 
			else
				$len = writer_ancora_strlen($text)-$from;
		}
		return WRITER_ANCORA_MULTIBYTE ? mb_substr($text, $from, $len) : substr($text, $from, $len);
	}
}

if (!function_exists('writer_ancora_strtolower')) {
	function writer_ancora_strtolower($text) {
		return WRITER_ANCORA_MULTIBYTE ? mb_strtolower($text) : strtolower($text);
	}
}

if (!function_exists('writer_ancora_strtoupper')) {
	function writer_ancora_strtoupper($text) {
		return WRITER_ANCORA_MULTIBYTE ? mb_strtoupper($text) : strtoupper($text);
	}
}

if (!function_exists('writer_ancora_strtoproper')) {
	function writer_ancora_strtoproper($text) { 
		$rez = ''; $last = ' ';
		for ($i=0; $i<writer_ancora_strlen($text); $i++) {
			$ch = writer_ancora_substr($text, $i, 1);
			$rez .= writer_ancora_strpos(' .,:;?!()[]{}+=', $last)!==false ? writer_ancora_strtoupper($ch) : writer_ancora_strtolower($ch);
			$last = $ch;
		}
		return $rez;
	}
}

if (!function_exists('writer_ancora_strrepeat')) {
	function writer_ancora_strrepeat($str, $n) {
		$rez = '';
		for ($i=0; $i<$n; $i++)
			$rez .= $str;
		return $rez;
	}
}

if (!function_exists('writer_ancora_strshort')) {
	function writer_ancora_strshort($str, $maxlength, $add='...') {
	//	if ($add && writer_ancora_substr($add, 0, 1) != ' ')
	//		$add .= ' ';
		if ($maxlength < 0) 
			return $str;
		if ($maxlength < 1 || $maxlength >= writer_ancora_strlen($str)) 
			return strip_tags($str);
		$str = writer_ancora_substr(strip_tags($str), 0, $maxlength - writer_ancora_strlen($add));
		$ch = writer_ancora_substr($str, $maxlength - writer_ancora_strlen($add), 1);
		if ($ch != ' ') {
			for ($i = writer_ancora_strlen($str) - 1; $i > 0; $i--)
				if (writer_ancora_substr($str, $i, 1) == ' ') break;
			$str = trim(writer_ancora_substr($str, 0, $i));
		}
		if (!empty($str) && writer_ancora_strpos(',.:;-', writer_ancora_substr($str, -1))!==false) $str = writer_ancora_substr($str, 0, -1);
		return ($str) . ($add);
	}
}

// Clear string from spaces, line breaks and tags (only around text)
if (!function_exists('writer_ancora_strclear')) {
	function writer_ancora_strclear($text, $tags=array()) {
		if (empty($text)) return $text;
		if (!is_array($tags)) {
			if ($tags != '')
				$tags = explode($tags, ',');
			else
				$tags = array();
		}
		$text = trim(chop($text));
		if (is_array($tags) && count($tags) > 0) {
			foreach ($tags as $tag) {
				$open  = '<'.esc_attr($tag);
				$close = '</'.esc_attr($tag).'>';
				if (writer_ancora_substr($text, 0, writer_ancora_strlen($open))==$open) {
					$pos = writer_ancora_strpos($text, '>');
					if ($pos!==false) $text = writer_ancora_substr($text, $pos+1);
				}
				if (writer_ancora_substr($text, -writer_ancora_strlen($close))==$close) $text = writer_ancora_substr($text, 0, writer_ancora_strlen($text) - writer_ancora_strlen($close));
				$text = trim(chop($text));
			}
		}
		return $text;
	}
}

// Return slug for the any title string
if (!function_exists('writer_ancora_get_slug')) {
	function writer_ancora_get_slug($title) {
		return writer_ancora_strtolower(str_replace(array('\\','/','-',' ','.'), '_', $title));
	}
}

// Replace macros in the string
if (!function_exists('writer_ancora_strmacros')) {
	function writer_ancora_strmacros($str) {
		return str_replace(array("{{", "}}", "((", "))", "||"), array("<i>", "</i>", "<b>", "</b>", "<br>"), $str);
	}
}

// Unserialize string (try replace \n with \r\n)
if (!function_exists('writer_ancora_unserialize')) {
	function writer_ancora_unserialize($str) {
		if ( is_serialized($str) ) {
			try {
				$data = unserialize($str);
			} catch (Exception $e) {
				dcl($e->getMessage());
				$data = false;
			}
			if ($data===false) {
				try {
					$data = @unserialize(str_replace("\n", "\r\n", $str));
				} catch (Exception $e) {
					dcl($e->getMessage());
					$data = false;
				}
			}
			//if ($data===false) $data = @unserialize(str_replace(array("\n", "\r"), array('\\n','\\r'), $str));
			return $data;
		} else
			return $str;
	}
}
?>