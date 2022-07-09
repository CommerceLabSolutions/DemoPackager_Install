<?php
/**
 * ANGIE - The site restoration script for backup archives created by Akeeba Backup and Akeeba Solo
 *
 * @package   angie
 * @copyright Copyright (c)2009-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

$cmsCode          = defined('ANGIE_INSTALLER_NAME') ? ANGIE_INSTALLER_NAME : 'Generic';
$cmsName          = ($cmsCode === 'Generic') ? 'generic PHP site' : "$cmsCode site";
$supportedDrivers = (defined('ANGIE_DBDRIVER_ALLOWED') && is_array(ANGIE_DBDRIVER_ALLOWED))
	? ANGIE_DBDRIVER_ALLOWED
	: ['mysqli', 'pdomysql'];

$supportedDrivers = array_map(function ($x) {
	return AText::_('DATABASE_LBL_TYPE_' . $x);
}, $supportedDrivers);

$lastDriver           = array_pop($supportedDrivers);
$manyDrivers          = count($supportedDrivers) >= 1;
$joiner               = $manyDrivers ? ' or ' : '';
$supportedDriversText = implode(', ', $supportedDrivers) . $joiner . $lastDriver;

?>
<div class="akeeba-panel--red">
	<header class="akeeba-block-header">
		<h3>You must enable the <?= $supportedDriversText ?> extension on PHP <?= PHP_VERSION ?></h3>
	</header>
	<div>
		<p>
			Your restored <?= $cmsName ?> will need to connect to a MySQL-compatible database to operate.
		</p>
		<p>
			Connecting to a MySQL-compatible database from a PHP application — such as your <?= $cmsName ?> — has two
			requirements:
		</p>
		<ol>
			<li>A MySQL-compatible database server such as MySQL, MariaDB or Percona.</li>
			<li>A PHP extension which is able to talk to the database server.</li>
		</ol>
		<p>
			Your server <strong>does not</strong> meet the second requirement.
		</p>
		<p>
			Your <?= $cmsName ?> requires the <?= $supportedDriversText ?> PHP extension to be able to talk to MySQL
			databases.
			This extension is not enabled on PHP <?= PHP_VERSION ?> on your site. As a result, the restoration cannot
			proceed.
		</p>
		<div class="akeeba-block--info large">
			<p>
				Please contact your host and ask them to enable the <?= $supportedDriversText ?> PHP extension on
				PHP <?= PHP_VERSION ?> or give you instructions to do so yourself.
			</p>
			<p>
				Once you do that please restart the backup restoration.
			</p>
		</div>
		<hr />
		<h5>Further information and clarifications</h5>
		<p class="small">
			This message <strong>cannot and will not appear if PHP <?= PHP_VERSION ?> on your server has functioning
				database server connection support</strong> compatible with your <?= $cmsName ?>. If you see this
			message something's
			wrong on your server, we told you what it is and how to fix it. Please do not contact us asking us what to
			do; this page already tells you what to do. Please do not contact us reporting a bug; your host failing
			to configure a new version of PHP properly does not constitute a bug in our software. Please do not contact
			us asking us to help you follow these instructions; we are not your host, therefore we'd have to simply
			repeat what
			you have already read on this page.
		</p>
		<p class="small">
			Do keep in mind that each PHP version on your server has its own extensions and configuration
			(<code>php.ini</code> file). This is why downgrading to an earlier PHP version may “magically” fix your
			problem. Again, this is not a bug in our software, it's your host having misconfigured
			PHP <?= PHP_VERSION ?> on their server. Please remember that we are not your host, therefore we have no
			control over your server's configuration.
		</p>
		<p class="small">
			Finally note that the fact that your server does not support database connectivity was printed in the first
			page of this restoration script. As you can see under “Pre-installation check” the ‘Database Support’ displays a red error label with the word ‘No‘.
		</p>
		<p class="small">
			Thank you for your understanding!
		</p>
	</div>
</div>
