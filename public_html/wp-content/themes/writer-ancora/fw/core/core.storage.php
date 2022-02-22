<?php
/**
 * Writer Ancora Framework: theme variables storage
 *
 * @package	writer_ancora
 * @since	writer_ancora 1.0
 */

// Disable direct call
if ( ! defined( 'ABSPATH' ) ) { exit; }

// Get theme variable
if (!function_exists('writer_ancora_storage_get')) {
	function writer_ancora_storage_get($var_name, $default='') {
		global $WRITER_ANCORA_STORAGE;
		return isset($WRITER_ANCORA_STORAGE[$var_name]) ? $WRITER_ANCORA_STORAGE[$var_name] : $default;
	}
}

// Set theme variable
if (!function_exists('writer_ancora_storage_set')) {
	function writer_ancora_storage_set($var_name, $value) {
		global $WRITER_ANCORA_STORAGE;
		$WRITER_ANCORA_STORAGE[$var_name] = $value;
	}
}

// Check if theme variable is empty
if (!function_exists('writer_ancora_storage_empty')) {
	function writer_ancora_storage_empty($var_name, $key='', $key2='') {
		global $WRITER_ANCORA_STORAGE;
		if (!empty($key) && !empty($key2))
			return empty($WRITER_ANCORA_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return empty($WRITER_ANCORA_STORAGE[$var_name][$key]);
		else
			return empty($WRITER_ANCORA_STORAGE[$var_name]);
	}
}

// Check if theme variable is set
if (!function_exists('writer_ancora_storage_isset')) {
	function writer_ancora_storage_isset($var_name, $key='', $key2='') {
		global $WRITER_ANCORA_STORAGE;
		if (!empty($key) && !empty($key2))
			return isset($WRITER_ANCORA_STORAGE[$var_name][$key][$key2]);
		else if (!empty($key))
			return isset($WRITER_ANCORA_STORAGE[$var_name][$key]);
		else
			return isset($WRITER_ANCORA_STORAGE[$var_name]);
	}
}

// Inc/Dec theme variable with specified value
if (!function_exists('writer_ancora_storage_inc')) {
	function writer_ancora_storage_inc($var_name, $value=1) {
		global $WRITER_ANCORA_STORAGE;
		if (empty($WRITER_ANCORA_STORAGE[$var_name])) $WRITER_ANCORA_STORAGE[$var_name] = 0;
		$WRITER_ANCORA_STORAGE[$var_name] += $value;
	}
}

// Concatenate theme variable with specified value
if (!function_exists('writer_ancora_storage_concat')) {
	function writer_ancora_storage_concat($var_name, $value) {
		global $WRITER_ANCORA_STORAGE;
		if (empty($WRITER_ANCORA_STORAGE[$var_name])) $WRITER_ANCORA_STORAGE[$var_name] = '';
		$WRITER_ANCORA_STORAGE[$var_name] .= $value;
	}
}

// Get array (one or two dim) element
if (!function_exists('writer_ancora_storage_get_array')) {
	function writer_ancora_storage_get_array($var_name, $key, $key2='', $default='') {
		global $WRITER_ANCORA_STORAGE;
		if (empty($key2))
			return !empty($var_name) && !empty($key) && isset($WRITER_ANCORA_STORAGE[$var_name][$key]) ? $WRITER_ANCORA_STORAGE[$var_name][$key] : $default;
		else
			return !empty($var_name) && !empty($key) && isset($WRITER_ANCORA_STORAGE[$var_name][$key][$key2]) ? $WRITER_ANCORA_STORAGE[$var_name][$key][$key2] : $default;
	}
}

// Set array element
if (!function_exists('writer_ancora_storage_set_array')) {
	function writer_ancora_storage_set_array($var_name, $key, $value) {
		global $WRITER_ANCORA_STORAGE;
		if (!isset($WRITER_ANCORA_STORAGE[$var_name])) $WRITER_ANCORA_STORAGE[$var_name] = array();
		if ($key==='')
			$WRITER_ANCORA_STORAGE[$var_name][] = $value;
		else
			$WRITER_ANCORA_STORAGE[$var_name][$key] = $value;
	}
}

// Set two-dim array element
if (!function_exists('writer_ancora_storage_set_array2')) {
	function writer_ancora_storage_set_array2($var_name, $key, $key2, $value) {
		global $WRITER_ANCORA_STORAGE;
		if (!isset($WRITER_ANCORA_STORAGE[$var_name])) $WRITER_ANCORA_STORAGE[$var_name] = array();
		if (!isset($WRITER_ANCORA_STORAGE[$var_name][$key])) $WRITER_ANCORA_STORAGE[$var_name][$key] = array();
		if ($key2==='')
			$WRITER_ANCORA_STORAGE[$var_name][$key][] = $value;
		else
			$WRITER_ANCORA_STORAGE[$var_name][$key][$key2] = $value;
	}
}

// Add array element after the key
if (!function_exists('writer_ancora_storage_set_array_after')) {
	function writer_ancora_storage_set_array_after($var_name, $after, $key, $value='') {
		global $WRITER_ANCORA_STORAGE;
		if (!isset($WRITER_ANCORA_STORAGE[$var_name])) $WRITER_ANCORA_STORAGE[$var_name] = array();
		if (is_array($key))
			writer_ancora_array_insert_after($WRITER_ANCORA_STORAGE[$var_name], $after, $key);
		else
			writer_ancora_array_insert_after($WRITER_ANCORA_STORAGE[$var_name], $after, array($key=>$value));
	}
}

// Add array element before the key
if (!function_exists('writer_ancora_storage_set_array_before')) {
	function writer_ancora_storage_set_array_before($var_name, $before, $key, $value='') {
		global $WRITER_ANCORA_STORAGE;
		if (!isset($WRITER_ANCORA_STORAGE[$var_name])) $WRITER_ANCORA_STORAGE[$var_name] = array();
		if (is_array($key))
			writer_ancora_array_insert_before($WRITER_ANCORA_STORAGE[$var_name], $before, $key);
		else
			writer_ancora_array_insert_before($WRITER_ANCORA_STORAGE[$var_name], $before, array($key=>$value));
	}
}

// Push element into array
if (!function_exists('writer_ancora_storage_push_array')) {
	function writer_ancora_storage_push_array($var_name, $key, $value) {
		global $WRITER_ANCORA_STORAGE;
		if (!isset($WRITER_ANCORA_STORAGE[$var_name])) $WRITER_ANCORA_STORAGE[$var_name] = array();
		if ($key==='')
			array_push($WRITER_ANCORA_STORAGE[$var_name], $value);
		else
			array_push($WRITER_ANCORA_STORAGE[$var_name][$key], $value);
	}
}

// Inc/Dec array element with specified value
if (!function_exists('writer_ancora_storage_inc_array')) {
	function writer_ancora_storage_inc_array($var_name, $key, $value=1) {
		global $WRITER_ANCORA_STORAGE;
		if (!isset($WRITER_ANCORA_STORAGE[$var_name])) $WRITER_ANCORA_STORAGE[$var_name] = array();
		if (empty($WRITER_ANCORA_STORAGE[$var_name][$key])) $WRITER_ANCORA_STORAGE[$var_name][$key] = 0;
		$WRITER_ANCORA_STORAGE[$var_name][$key] += $value;
	}
}

// Concatenate array element with specified value
if (!function_exists('writer_ancora_storage_concat_array')) {
	function writer_ancora_storage_concat_array($var_name, $key, $value) {
		global $WRITER_ANCORA_STORAGE;
		if (!isset($WRITER_ANCORA_STORAGE[$var_name])) $WRITER_ANCORA_STORAGE[$var_name] = array();
		if (empty($WRITER_ANCORA_STORAGE[$var_name][$key])) $WRITER_ANCORA_STORAGE[$var_name][$key] = '';
		$WRITER_ANCORA_STORAGE[$var_name][$key] .= $value;
	}
}

// Call object's method
if (!function_exists('writer_ancora_storage_call_obj_method')) {
	function writer_ancora_storage_call_obj_method($var_name, $method, $param=null) {
		global $WRITER_ANCORA_STORAGE;
		if ($param===null)
			return !empty($var_name) && !empty($method) && isset($WRITER_ANCORA_STORAGE[$var_name]) ? $WRITER_ANCORA_STORAGE[$var_name]->$method(): '';
		else
			return !empty($var_name) && !empty($method) && isset($WRITER_ANCORA_STORAGE[$var_name]) ? $WRITER_ANCORA_STORAGE[$var_name]->$method($param): '';
	}
}

// Get object's property
if (!function_exists('writer_ancora_storage_get_obj_property')) {
	function writer_ancora_storage_get_obj_property($var_name, $prop, $default='') {
		global $WRITER_ANCORA_STORAGE;
		return !empty($var_name) && !empty($prop) && isset($WRITER_ANCORA_STORAGE[$var_name]->$prop) ? $WRITER_ANCORA_STORAGE[$var_name]->$prop : $default;
	}
}
?>