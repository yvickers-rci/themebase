<?php

if(false === tb_get_setting('load_extended_tracking')){
	return;
}

/**
 * hooks into gravity forms to track the submit button clicks as google events
 * requires new _gaq analytics to be setup on site
 */
add_filter("gform_submit_button", "add_conversion_tracking_code", 10, 2);
if(!function_exists('add_conversion_tracking_code')){
	function add_conversion_tracking_code($button, $form) {
		//setup some default information - category, action and label are required
		$defaults = array(
			'category'=>'form',
			'action'=>'submit',
			'label'=>get_the_title(),
			'value'=>null,
			'non_interaction'=>null,
		);
		extract($defaults);

		//find google event variables
		foreach($form['fields'] as $field){
			switch($field['label']){
				default:
				break;

				case 'google_event_category':
	                $category = htmlspecialchars($field['defaultValue'],ENT_QUOTES);
				break;
				case 'google_event_action':
	                $action = htmlspecialchars($field['defaultValue'],ENT_QUOTES);
				break;
				case 'google_event_label':
					$label = htmlspecialchars($field['defaultValue'],ENT_QUOTES);
				break;
				case 'google_event_value':
	                $value = (int)$field['defaultValue'];
				break;
				case 'google_event_non_interaction':
	                $non_interaction = (($field['defaultValue'] != '')? 'true':'false');
				break;
			}
		}

		$gaq_params = array(
			"'_trackEvent'",
			"'".$category."'",
			"'".$action."'",
			"'".$label."'",
		);
		if(!is_null($value)){
			$gaq_params[] = $value;
	    	if(!is_null($non_interaction)) $gaq_params[] = $non_interaction;
		}

	    $dom = new DOMDocument();
	    $dom->loadHTML($button);
	    $input = $dom->getElementsByTagName('input')->item(0);
	    $input->setAttribute("onClick","_gaq.push([".implode(',',$gaq_params)."]);");
	    return $dom->saveHtml();
	}
}