<?php
/****************************************
VARIOUS UTILITIES
****************************************/
// Is Element Empty ( borrowed from Roots http://rootstheme.com/ )
if(!function_exists('is_element_empty')){
	function is_element_empty($element) {
		$element = trim($element);
		return empty($element) ? false : true;
	}
}