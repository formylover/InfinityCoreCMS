<?php
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Extra credits for this file
* Mark D. Hamill (mhamill@computer.org)
*
*/

// digest_constants.php
// Written by Mark D. Hamill, mhamill@computer.org
// This software is designed to work with phpBB Version 2.0.20

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking attempt');
}

// Table names. Change if you prefer to use different table names. I used the mod_ prefix to make them stand out from other standard phpBB tables.
// Removed because we have defined them in constants.php
/*
define('DIGEST_SUBSCRIPTIONS_TABLE', $table_prefix . 'digest_subscriptions');
define('DIGEST_SUBSCRIBED_FORUMS_TABLE', $table_prefix . 'digest_subscribed_forums');
*/

// Various variables for digest
define('DIGEST_WEEKLY_DIGEST_DAY', 0); // Day of week to send weekly digest, use 0-6 where 0=Sunday, 6=Saturday etc.
define('DIGEST_USE_DEFAULT_STYLESHEET', true); // true if you want HTML digest to apply default theme stylesheet, otherwise false. Use of a custom stylesheet will override this setting.
define('DIGEST_USE_CUSTOM_STYLESHEET', false); // set to true to enable the stylesheet below
define('DIGEST_CUSTOM_STYLESHEET_PATH', IP_ROOT_PATH . 'templates/common/acp.css'); // You will need to create this stylesheet, if you enable it

if (!defined('DIGEST_SITE_URL'))
{
	$digest_server_url = create_digest_server_url();
	define('DIGEST_SITE_URL', $digest_server_url);
	unset($digest_server_url);
}

define('DIGEST_VERSION', '1.0.13'); // Don't change this; the mod author changes this.
define('DIGEST_DATE_FORMAT', 'd M Y h:i A '); // How post date will be displayed as text in the post. Use formats found at http://www.php.net/manual/en/function.date.php
define('DIGEST_SERVER_DATE_DISPLAY', 'Y/m/d'); // How server date will be displayed as text in mail digest summary. Use formats found at http://www.php.net/manual/en/function.date.php
define('DIGEST_SHOW_SUMMARY', true); // Shows a summary of who got a digest, how many digest were sent, etc. Useful when setting up the digests or if you want to collect statistics information, otherwise set to false.
define('DIGEST_SHOW_SUMMARY_TYPE', 'text'); // 'html' or 'text' . Text is best if capturing output to a file.
define('DIGEST_HTML_ENCODING', 'UTF-8'); // May need to change this if not using English.
define('DIGEST_TEXT_ENCODING', 'UTF-8'); // May need to change this if not inside the United States. Should be a plain text character set only.
unset ($protocol);

function create_digest_server_url()
{
	// usage: $server_url = create_digest_server_url();
	global $config;

	$server_protocol = ($config['cookie_secure']) ? 'https://' : 'http://';
	$server_name = preg_replace('#^\/?(.*?)\/?$#', '\1', trim($config['server_name']));
	$server_port = ($config['server_port'] <> 80) ? ':' . trim($config['server_port']) : '';
	$script_name = preg_replace('/^\/?(.*?)\/?$/', '\1', trim($config['script_path']));
	$script_name = ($script_name == '') ? '' : '/' . $script_name;
	$server_url = $server_protocol . $server_name . $server_port . $script_name;
	while(substr($server_url, -1, 1) == '/')
	{
		$server_url = substr($server_url, 0, -1);
	}
	$server_url = $server_url . '/';

	return $server_url;
}

?>