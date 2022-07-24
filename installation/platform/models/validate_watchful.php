<?php
/**
 * ANGIE - The site restoration script for backup archives created by Akeeba Backup and Akeeba Solo
 *
 * @package   angie
 * @copyright Copyright (c)2009-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

// defined('_AKEEBA') or die();


$watchful_key = (isset($_POST["watchful_key"])) ? $_POST["watchful_key"] : '';
$debug        = (isset($_POST["debug"])) ? $_POST["debug"] : false;
$extension_id = 18;

$domain    = $_SERVER['HTTP_HOST'];
$ip        = file_get_contents('https://api.ipify.org');
$localhost = (gethostbyname($_SERVER['HTTP_HOST']) == '127.0.0.1') ? '1' : '0';

$get_queries = "watchful_key=$watchful_key&domain=$domain&ip=$ip&extension_id=$extension_id&localhost=$localhost&action=status_install";
if ($debug)
{
	$get_queries .= '&debug=1';
}


$url = "https://commercelab.solutions/index.php?option=com_ajax&plugin=validatewatchfull&format=json&$get_queries";

$response = json_decode(file_get_contents($url), true)['data'][0];

// Decode Messages
if ($response['message_html'] != '')
{
	$response['message_html'] = base64_decode($response['message_html']);
}


echo json_encode($response);
