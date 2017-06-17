<?php
/*
Plugin Name: WhiteListDomains
Plugin URI: https://github.com/Panthro/YourlsWhitelistDomains
Description: Plugin which allow only whitelisted domains, forked from: https://github.com/LudoBoggio/YourlsBlacklistIPs
Version: 1.0
Author: Panthro
Author URI: http://passeionaweb.com.br
*/

// No direct call
if( !defined( 'YOURLS_ABSPATH' ) ) die();

// Hook the custom function into the 'pre_check_domain_flood' event
if (!yourls_is_admin())
	yourls_add_filter( 'shunt_add_new_link', 'panthro_whitelist_domain_root' );

// Hook the admin page into the 'plugins_loaded' event
yourls_add_action( 'plugins_loaded', 'panthro_whitelist_domain_add_page' );


function panthro_string_to_list($string) {

	$list = preg_split('/\r?\n/', $string);
	$rv = array();
	foreach ($list as $x) {
		$x = trim($x);
		if ($x === "") continue;
		$x = strtolower($x);
		array_push($rv, $x);
	}
	return $rv;
}

function panthro_domain_list() {
	// return domain list as an array.
	$list = yourls_get_option ('panthro_whitelist_domain_list', '');
	if ($list === "") return array();
	if (is_array($list)) return $list;
	
	return panthro_string_to_list($list);
}


// Get whitelisted domains from YOURLS options feature and compare with current domain address
function panthro_whitelist_domain_root ( $bol, $url ) {

	//if (yourls_is_admin()) return $return;

	$domain = strtolower(parse_url($url, PHP_URL_HOST));

	$domain_list = panthro_domain_list();
	if ( $domain_list ) {

		foreach ($domain_list as $needle) {
			if ($needle === "") continue;
			// exact match
			if ($domain === $needle) return false;
			// domain = www.example.com, $needle == example.com
			$needle = ".$needle";
			if (substr($domain, -strlen($needle)) === $needle) return false;
		}
	}

	$return['status']    = 'fail';
	$return['code']      = 'error:domain-not-allowed';
	$return['message']   = 'This domain is not allowed';
	$return['errorCode'] = '400';
	return $return;
}

// Add admin page
function panthro_whitelist_domain_add_page () {
	yourls_register_plugin_page( 'panthro_whitelist_domain', 'Whitelist domains', 'panthro_whitelist_domain_do_page' );
}

// Display admin page
function panthro_whitelist_domain_do_page () {
	if( isset( $_POST['action'] ) && $_POST['action'] == 'whitelist_domain' ) {
		panthro_whitelist_domain_process ();
	} else {
		panthro_whitelist_domain_form ();
	}
}


// Display form to administrate whitelisted domains list
function panthro_whitelist_domain_form () {
	$nonce = yourls_create_nonce( 'whitelist_domain' ) ;
	$domain_list = panthro_domain_list();
	$domain_list_display = yourls_esc_textarea(implode("\r\n", $domain_list));

	echo <<<HTML
		<h2> WhiteList domains</h2>
		<form method="post">
		
		<input type="hidden" name="action" value="whitelist_domain" />
		<input type="hidden" name="nonce" value="$nonce" />
		
		<p>Whitelist following domains
		<textarea cols="30" rows="5" name="whitelist_form"
		placeholder="Enter domain addresses here, one per line"
		>$domain_list_display</textarea>
		</p>
		
		<p><input type="submit" value="Save" /></p>
		</form>
HTML;
}

// Update whitelisted domains list
function panthro_whitelist_domain_process () {
	// Check nonce
	yourls_verify_nonce( 'whitelist_domain' ) ;
	// Update list
	$list = panthro_string_to_list( $_POST['whitelist_form'] );
	yourls_update_option ( 'panthro_whitelist_domain_list',$list );
	echo "White list updated" ;
}
?>
