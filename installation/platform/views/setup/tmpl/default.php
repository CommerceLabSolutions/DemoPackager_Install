<?php
/**
 * ANGIE - The site restoration script for backup archives created by Akeeba Backup and Akeeba Solo
 *
 * @package   angie
 * @copyright Copyright (c)2009-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

defined('_AKEEBA') or die();

/** @var $this AngieViewSetup */

$document = $this->container->application->getDocument();

$document->addScript('angie/js/json.min.js');
$document->addScript('angie/js/ajax.min.js');
$document->addScript('platform/js/setup.min.js');

$url = 'index.php';

$document->addScriptDeclaration(<<<JS
var akeebaAjax = null;

akeeba.System.documentReady(function(){
	akeebaAjax = new akeebaAjaxConnector('$url');
});

JS
);

$watchful_key = isset($_GET["watchful_key"]) ? $_GET["watchful_key"] : '';

$this->loadHelper('select');

echo $this->loadAnyTemplate('steps/buttons');
echo $this->loadAnyTemplate('steps/steps', ['helpurl' => 'https://www.akeeba.com/documentation/solo/angie-joomla-setup.html']);

?>

<style type="text/css">
	#btnNext {
		display: none;
	}
</style>

<form id="setupForm" name="setupForm" action="index.php" method="post" class="uk-form uk-container uk-container-center uk-container-xsmall uk-margin-top">
	<div>
		<button class="akeeba-btn--dark" style="display: none;" onclick="toggleHelp(); return false;">
			<span class="akion-help"></span>
			Show / hide help
		</button>
	</div>

	<div class="">
		<div class="uk-margin-top uk-panel">

			<h3>Watchful Key</h3>

			<div class="uk-margin-small" uk-grid>
				<div class="uk-width-1-2">
					Watchful Key is Required to finish the Installation
				</div>
				<div class="uk-width-1-2 uk-margin-remove-top uk-grid-colapse">
					<div uk-grid>
						
						<div class="uk-width-expand">
							<input class="uk-input uk-width-1-1" type="text" id="watchful_key" name="watchfulkey" value="<?php echo $watchful_key ?>" />
							<input type="hidden" id="hidden_watchful_key" name="hidden_watchful_key" value="" />
							<input type="hidden" name="allow_ytp" id="allow_ytp" value="0">
						</div>
						<div class="uk-width-auto" style="padding-left: 0;">
							<button id="validate_watchful" class="uk-button uk-button-small uk-button-danger uk-width-1-1" style="height: 36px;">
								<span class="watchful_validating_uncheck">Validate</span>
								<span class="watchful_validating_spinner" style="width: 20px; height: 20px; display: none;" uk-spinner></span>
								<span class="watchful_validating_check" uk-icon="icon: check" style="display: none;"></span>
							</button>
						</div>
						<div class="uk-margin-remove uk-width-1-1 watchful_key_alert akeeba-help-text uk-text-danger uk-animation-fade uk-animation-fast" style="display: none">
		                    Field Can't be empty
		                </div>
					</div>

				</div>

			</div>
			<div class="uk-width-1-1 uk-margin-top-large uk-text-center">
				<span class="watchful_validating_locked uk-margin-top uk-text-danger" uk-icon="icon: lock; ratio: 2.5"></span>
				<span class="watchful_validating_unlocked uk-margin-top uk-text-success" style="display: none;" uk-icon="icon: unlock; ratio: 2.5"></span>
			</div>
		</div>

		<!-- Site parameters -->
		<div class="uk-margin-top locked uk-hidden">

			<h3><?php echo AText::_('SETUP_HEADER_SITEPARAMS') ?></h3>

			<div class="uk-margin-small" uk-grid>
				<div class="uk-width-1-2">
					<?php echo AText::_('SETUP_LBL_SITENAME'); ?>
					<span class="uk-text-danger"> *</span>
				</div>
				<div class="uk-width-1-2">
					<input required class="uk-input uk-width-1-1" type="text" id="sitename" name="sitename" value="<?php echo $this->stateVars->sitename ?>" />
				</div>
				<span class="akeeba-help-text" style="display: none">
                    <?php echo AText::_('SETUP_LBL_SITENAME_HELP') ?>
                </span>
			</div>

			<div class="uk-margin-small" uk-grid>
				<div class="uk-width-1-2">
					<?php echo AText::_('SETUP_LBL_SITEEMAIL'); ?>
					<span class="uk-text-danger"> *</span>
				</div>
				<div class="uk-width-1-2">
					<input required class="uk-input uk-width-1-1" type="text" id="siteemail" name="siteemail" value="<?php echo $this->stateVars->siteemail ?>" />
				</div>
				<span class="akeeba-help-text" style="display: none">
                    <?php echo AText::_('SETUP_LBL_SITEEMAIL_HELP') ?>
                </span>
			</div>
			<div class="uk-margin-small" uk-grid>
				<div class="uk-width-1-2">
					<?php echo AText::_('SETUP_LBL_EMAILSENDER'); ?>
					<span class="uk-text-danger"> *</span>
				</div>
				<div class="uk-width-1-2">
					<input required class="uk-input uk-width-1-1" type="text" id="emailsender" name="emailsender" value="<?php echo $this->stateVars->emailsender ?>" />
				</div>

			</div>

		</div>
		<div class="uk-margin-top uk-margin-large-bottom locked uk-hidden">
			<h3><?php echo AText::_('SETUP_HEADER_SUPERUSERPARAMS') ?></h3>
			<div class="">

				<div class="uk-margin-small" uk-grid>
					<div class="uk-width-1-2">
						<?php echo AText::_('SETUP_LABEL_SUPERUSEREMAIL'); ?>
						<span class="uk-text-danger"> *</span>
					</div>
					<div class="uk-width-1-2">
						<input required class="uk-input uk-width-1-1" type="text" id="superuseremail" name="superuseremail" value="" />
					</div>
					<span class="akeeba-help-text" style="display: none">
						<?php echo AText::_('SETUP_LABEL_SUPERUSEREMAIL_HELP') ?>
					</span>
				</div>

				<div class="uk-margin-small" uk-grid>
					<div class="uk-width-1-2">
						Name
						<span class="uk-text-danger"> *</span>
					</div>
					<div class="uk-width-1-2">
						<input required class="uk-input uk-width-1-1" type="text" id="superusername" name="superusername" value="" />
					</div>
				</div>
				
				<div class="uk-margin-small" uk-grid>
					<div class="uk-width-1-2">
						<?php echo AText::_('SETUP_LABEL_SUPERUSERPASSWORD'); ?>
						<span class="uk-text-danger"> *</span>
					</div>
					<div class="uk-width-1-2">
						<input placeholder="8 characters min." required class="uk-input uk-width-1-1" type="password" min="8" id="superuserpassword" name="superuserpassword" value="" />
					</div>
					<span class="akeeba-help-text" style="display: none">
						<?php echo AText::_('SETUP_LABEL_SUPERUSERPASSWORD_HELP2') ?>
					</span>
				</div>
				<div class="uk-margin-small" uk-grid>
					<div class="uk-width-1-2">
						<?php echo AText::_('SETUP_LABEL_SUPERUSERPASSWORDREPEAT'); ?>
						<span class="uk-text-danger"> *</span>
					</div>
					<div class="uk-width-1-2">
						<input placeholder="8 characters min." required class="uk-input uk-width-1-1" type="password" min="8" id="superuserpasswordrepeat" name="superuserpasswordrepeat" value="" />
						<span class="superuserpassword_not_match uk-text-danger" style="display: none">
							Passwords do not match
						</span>
					</div>
				</div>
			</div>
		</div>
		<div class="akeeba-panel--orange uk-hidden" style="opacity: 0; height: 0;">

			<div class="akeeba-form-group" style="opacity: 0; height: 0;">
				<label for="livesite">
					<?php echo AText::_('SETUP_LBL_LIVESITE'); ?>
				</label>
				<input type="text" id="livesite" name="livesite" value="<?php echo $this->stateVars->livesite ?>" />
				<?php if (substr(PHP_OS, 0, 3) == 'WIN'): ?>
					<p class="akeeba-block--warning">
						<span class="akion-android-warning"></span>
						<?php echo AText::_('SETUP_LBL_LIVESITE_WINDOWS_WARNING') ?>
					</p>
				<?php endif; ?>
				<span class="akeeba-help-text" style="display: none">
					  <?php echo AText::_('SETUP_LBL_LIVESITE_HELP') ?>
				</span>
			</div>

			<?php if($this->protocolMismatch): ?>
				<div class="akeeba-block--warning" style="opacity: 0; height: 0;">
					<?php echo AText::_('SETUP_LBL_SERVERCONFIG_DISABLEFORCESSL_WARN')?>
				</div>
			<?php endif; ?>

			<div class="akeeba-form-group" style="opacity: 0; height: 0;">
				<label for="force_ssl">
					<?php echo AText::_('SETUP_LABEL_FORCESSL'); ?>
				</label>
				<?php echo AngieHelperSelect::forceSSL($this->stateVars->force_ssl); ?>
				<span class="akeeba-help-text" style="display: none">
					<?php echo AText::_('SETUP_LABEL_FORCESSL_TIP') ?>
				</span>
			</div>
			<div class="akeeba-form-group" style="opacity: 0; height: 0;">
				<label for="cookiedomain">
					<?php echo AText::_('SETUP_LBL_COOKIEDOMAIN'); ?>
				</label>
				<input type="text" id="cookiedomain" name="cookiedomain"
					   value="<?php echo $this->stateVars->cookiedomain ?>" />
				<span class="akeeba-help-text" style="display: none">
					<?php echo AText::_('SETUP_LBL_COOKIEDOMAIN_HELP') ?>
				</span>
			</div>
			<div class="akeeba-form-group" style="opacity: 0; height: 0;">
				<label for="cookiepath">
					<?php echo AText::_('SETUP_LBL_COOKIEPATH'); ?>
				</label>
				<input type="text" id="cookiepath" name="cookiepath"
					   value="<?php echo $this->stateVars->cookiepath ?>" />
				<span class="akeeba-help-text" style="display: none">
					<?php echo AText::_('SETUP_LBL_COOKIEPATH_HELP') ?>
				</span>
			</div>
			<?php if (true || version_compare($this->container->session->get('jversion'), '3.2', 'ge')): ?>
			<div class="akeeba-form-group" style="opacity: 0; height: 0;">
				<label for="mailonline">
					<?php echo AText::_('SETUP_LBL_MAILONLINE'); ?>
				</label>
				<div class="akeeba-toggle">
					<input type="radio" <?php echo !$this->stateVars->mailonline ? 'checked="checked"' : '' ?>
						   name="mailonline" id="mailonline-0" value="0" />
					<label for="mailonline-0" class="red">
						<?php echo AText::_('GENERIC_LBL_NO') ?>
					</label>

					<input type="radio" <?php echo $this->stateVars->mailonline ? 'checked="checked"' : '' ?>
						   name="mailonline" id="mailonline-1" value="1" />
					<label for="mailonline-1" class="green">
						<?php echo AText::_('GENERIC_LBL_YES') ?>
					</label>
				</div>
			</div>
			<?php endif; ?>
			<div class="akeeba-form-group--checkbox--pull-right" style="opacity: 0; height: 0;">
				<label>
					<input type="checkbox" id="usesitedirs" name="usesitedirs" />
					<?php echo AText::_('SETUP_LBL_USESITEDIRS'); ?>
				</label>
				<span class="akeeba-help-text" style="display: none">
					  <?php echo AText::_('SETUP_LBL_USESITEDIRS_HELP') ?>
				</span>
			</div>
			<header class="akeeba-block-header">
				<h3><?php echo AText::_('SETUP_HEADER_SERVERCONFIG') ?></h3>
			</header>

			<p class="akeeba-block--info small">
				<?php echo AText::_('SETUP_SERVERCONFIG_DESCR') ?>
			</p>

			<?php if ($this->htaccessSupported && $this->hasHtaccess): ?>
				<div class="akeeba-form-group">
					<label for="htaccessHandling"><?= AText::_('SETUP_LBL_HTACCESSCHANGE_LBL') ?></label>
					<?= AngieHelperSelect::genericlist($this->htaccessOptions, 'htaccessHandling', null, 'value', 'text', $this->htaccessOptionSelected) ?>
					<span class="akeeba-help-text" style="display: none">
					  <?= AText::_('SETUP_LBL_HTACCESSCHANGE_DESC') ?>
				</span>
				</div>
			<?php endif; ?>

			<?php if ($this->webConfSupported): ?>
				<div class="akeeba-form-group--checkbox--pull-right">
					<label <?php echo $this->replaceWeconfigOptions['disabled'] ?>>
						<input type="checkbox" value="1" id="replacewebconfig"
							   name="replacewebconfig" <?php echo $this->replaceWeconfigOptions['disabled'] ?> <?php echo $this->replaceWeconfigOptions['checked'] ?> />
						<?php echo AText::_('SETUP_LBL_SERVERCONFIG_REPLACEWEBCONFIG'); ?>
					</label>
					<span class="akeeba-help-text" style="display: none">
						  <?php echo AText::_($this->replaceWeconfigOptions['help']) ?>
					</span>
				</div>
			<?php endif; ?>

			<div class="akeeba-form-group--checkbox--pull-right">
				<label <?php echo $this->removePhpiniOptions['disabled'] ?>>
					<input type="checkbox" value="1" id="removephpini"
						   name="removephpini" <?php echo $this->removePhpiniOptions['disabled'] ?> <?php echo $this->removePhpiniOptions['checked'] ?> />
					<?php echo AText::_('SETUP_LBL_SERVERCONFIG_REMOVEPHPINI'); ?>
				</label>
				<span class="akeeba-help-text" style="display: none">
						  <?php echo AText::_($this->removePhpiniOptions['help']) ?>
					</span>
			</div>

			<?php if ($this->htaccessSupported): ?>
				<div class="akeeba-form-group--checkbox--pull-right">
					<label <?php echo $this->removeHtpasswdOptions['disabled'] ?>>
						<input type="checkbox" value="1" id="removehtpasswd"
							   name="removehtpasswd" <?php echo $this->removeHtpasswdOptions['disabled'] ?> <?php echo $this->removeHtpasswdOptions['checked'] ?> />
						<?php echo AText::_('SETUP_LBL_SERVERCONFIG_REMOVEHTPASSWD'); ?>
					</label>
					<span class="akeeba-help-text" style="display: none">
						  <?php echo AText::_($this->removeHtpasswdOptions['help']) ?>
					</span>
				</div>
			<?php endif; ?>

		</div>
	</div>

	<div class="akeeba-container--50-50 uk-hidden" style="opacity: 0; height: 0;">
		<!-- Fine-tuning -->
		<div class="akeeba-panel--info" style="margin-top: 0">
			<header class="akeeba-block-header">
				<h3><?php echo AText::_('SETUP_HEADER_FINETUNING') ?></h3>
			</header>

			<div class="form-horizontal">
				<div class="akeeba-form-group">
					<label for="siteroot">
						<?php echo AText::_('SETUP_LABEL_SITEROOT'); ?>
					</label>
					<input type="text" disabled="disabled" id="siteroot"
						   value="<?php echo $this->stateVars->site_root_dir ?>" />
					<span class="akeeba-help-text" style="display: none">
						<?php echo AText::_('SETUP_LABEL_SITEROOT_HELP') ?>
					</span>
				</div>
				<div class="akeeba-form-group">
					<label for="tmppath">
						<?php echo AText::_('SETUP_LABEL_TMPPATH'); ?>
					</label>
					<input type="text" id="tmppath" name="tmppath"
						   value="<?php echo $this->stateVars->tmppath ?>" />
					<span class="akeeba-help-text" style="display: none">
						<?php echo AText::_('SETUP_LABEL_TMPPATH_HELP') ?>
					</span>
				</div>
				<div class="akeeba-form-group">
					<label for="logspath">
						<?php echo AText::_('SETUP_LABEL_LOGSPATH'); ?>
					</label>
					<input type="text" id="logspath" name="logspath"
						   value="<?php echo $this->stateVars->logspath ?>" />
					<span class="akeeba-help-text" style="display: none">
						<?php echo AText::_('SETUP_LABEL_LOGSPATH_HELP') ?>
					</span>
				</div>
			</div>
		</div>
		<?php if (isset($this->stateVars->superusers)): ?>
			<!-- Super Administrator settings -->

		<?php endif; ?>
	</div>
	<div class="akeeba-container--50-50 uk-hidden" style="opacity: 0; height: 0;">
		<!-- FTP options -->
		<?php if ($this->hasFTP): ?>
			<div class="akeeba-panel-info">
				<header class="akeeba-block-header">
					<h3>
						<?php echo AText::_('SETUP_HEADER_FTPPARAMS') ?>
					</h3>
				</header>
				<p class="akeeba-block--info small">
					<?php echo AText::_('SETUP_LABEL_FTPENABLE_HELP') ?>
				</p>

				<div class="text-center" style="margin-bottom: 20px">
                    <span id="showFtpOptions" class="akeeba-btn--green"
						  style="display: <?php echo $this->stateVars->ftpenable ? 'none' : 'inline'; ?>">
                        <?php echo AText::_('SETUP_LABEL_FTPENABLE') ?>
                    </span>
					<span id="hideFtpOptions" class="akeeba-btn--red"
						  style="display: <?php echo $this->stateVars->ftpenable ? 'inline' : 'none'; ?>">
                        <?php echo AText::_('SETUP_LABEL_FTPDISABLE') ?>
                    </span>
				</div>

				<input type="hidden" id="enableftp" name="enableftp"
					   value="<?php echo $this->stateVars->ftpenable; ?>" />

				<div id="ftpLayerHolder"
					 style="display: <?php echo $this->stateVars->ftpenable ? 'block' : 'none'; ?>">
					<div class="akeeba-form-group">
						<label for="ftphost">
							<?php echo AText::_('SETUP_LABEL_FTPHOST'); ?>
						</label>
						<input type="text" id="ftphost" name="ftphost"
							   value="<?php echo $this->stateVars->ftphost ?>" />
						<span class="akeeba-help-text" style="display: none">
							<?php echo AText::_('SETUP_LABEL_FTPHOST_HELP') ?>
						</span>
					</div>
					<div class="akeeba-form-group">
						<label for="ftpport">
							<?php echo AText::_('SETUP_LABEL_FTPPORT'); ?>
						</label>
						<input type="text" id="ftpport" name="ftpport"
							   value="<?php echo empty($this->stateVars->ftpport) ? '21' : $this->stateVars->ftpport ?>" />
						<span class="akeeba-help-text" style="display: none">
					 		<?php echo AText::_('SETUP_LABEL_FTPPORT_HELP') ?>
						</span>
					</div>
					<div class="akeeba-form-group">
						<label for="ftpuser">
							<?php echo AText::_('SETUP_LABEL_FTPUSER'); ?>
						</label>
						<input type="text" id="ftpuser" name="ftpuser"
							   value="<?php echo $this->stateVars->ftpuser ?>" />
						<span class="akeeba-help-text" style="display: none">
							<?php echo AText::_('SETUP_LABEL_FTPUSER_HELP') ?>
						</span>
					</div>
					<div class="akeeba-form-group">
						<label for="ftppass">
							<?php echo AText::_('SETUP_LABEL_FTPPASS'); ?>
						</label>
						<input type="password" id="ftppass" name="ftppass"
							   value="<?php echo $this->stateVars->ftppass ?>" />
						<span class="akeeba-help-text" style="display: none">
							<?php echo AText::_('SETUP_LABEL_FTPPASS_HELP') ?>
						</span>
					</div>
					<div class="akeeba-form-group">
						<label for="ftpdir">
							<?php echo AText::_('SETUP_LABEL_FTPDIR'); ?>
						</label>
						<div class="akeeba-input-group">
							<input type="text" id="ftpdir" name="ftpdir"
								   value="<?php echo $this->stateVars->ftpdir ?>" />
							<span class="akeeba-input-group-btn">
								<button type="button" class="akeeba-btn" id="ftpbrowser"
										onclick="openFTPBrowser();">
									<span class="akion-android-folder-open"></span>
									<?php echo AText::_('SESSION_BTN_BROWSE'); ?>
								</button>
							</span>
						</div>
						<span class="akeeba-help-text" style="display: none">
							<?php echo AText::_('SETUP_LABEL_FTPDIR_HELP') ?>
						</span>
					</div>
				</div>
			</div>
		<?php endif; ?>

	</div>

	<div style="display: none;">
		<input type="hidden" name="view" value="setup" />
		<input type="hidden" name="task" value="apply" />
	</div>
</form>

<div id="browseModal" class="modal" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="browseModalLabel" style="display: none">

	<div class="akeeba-renderer-fef">
		<div class="akeeba-panel--teal">
			<header class="akeeba-block-header">
				<h3 id="browseModalLabel"><?php echo AText::_('GENERIC_FTP_BROWSER'); ?></h3>
			</header>
			<iframe id="browseFrame" src="about:blank" width="100%" height="300px"></iframe>
		</div>
	</div>

</div>

<div id="error_response-watchfull-modal" uk-modal>
    <div class="uk-modal-dialog uk-modal-body uk-background-secondary" style="padding: 0;">
        <button class="uk-modal-close-default" type="button" uk-close></button>
		<div class="uk-width-1-1 error_response-watchfull"></div>
    </div>
</div>

<script type="text/javascript">

	jQuery(document).ready(function($) {

		$('input', $('#setupForm')).on('keyup', function() {
			var completed_fields = true;
			$('#setupForm').find('input:required').each(function(index, value) {

				if ($(value).val() == '')
				{
					completed_fields = false;
				}
				if ($(value).attr('id') == 'superuserpasswordrepeat')
				{
					if ($(value).val() != $('#superuserpassword').val())
					{
						$('.superuserpassword_not_match').show();
						completed_fields = false;
					} else {
						$('.superuserpassword_not_match').hide();
					}
				}
			});

			if (completed_fields)
			{
				$('#btnNext').css('display', 'block');
			}
		});
		$('#validate_watchful').on('click', function(e) {

			e.preventDefault();
			var watchful_key = $('#watchful_key').val();

			if (watchful_key == '')
			{
				$('.watchful_key_alert').show();
			}
			else
			{
				$('.watchful_key_alert').hide();
				$('.error_response-watchfull').html('');

				$('.watchful_validating_spinner').show();
				// $('.watchful_validating_locked').hide();

				$.ajax({
		            type: 'POST',
		            url: "platform/models/validate_watchful.php",
		            data: {
		            	'watchful_key': watchful_key,
		            	'debug': false
		            },
		            success: function(data, textStatus, request){

		            	var response = JSON.parse(data);


						$('.watchful_validating_spinner').hide();

						if (response.show_status)
						{

							$('#hidden_watchful_key').val(watchful_key);

							$('.watchful_validating_unlocked').show();
							$('.watchful_validating_locked').hide();
							$('.watchful_validating_uncheck').hide();
							$('.watchful_validating_check').show();
							$('#validate_watchful').attr('disabled', true).addClass('uk-disabled');
							$('#watchful_key').attr('disabled', true).addClass('uk-disabled uk-button-success');
							$('.locked.uk-hidden').removeClass('uk-hidden');


							if (response.ytp_status)
							{
								$('#allow_ytp').val('1');
							}
						}
						else
						{
							$('.error_response-watchfull').html(response.message_html).show();
							UIkit.modal('#error_response-watchfull-modal').show();
						}
		            }
				});

			}

		})
	});

	<?php if (isset($this->stateVars->superusers)): ?>
	setupSuperUsers = <?php echo json_encode($this->stateVars->superusers); ?>;
	<?php endif; ?>

	akeeba.System.documentReady(function() {
		<?php if (isset($this->stateVars->superusers)): ?>
		setupSuperUserChange();
		<?php endif; ?>
		setupDefaultTmpDir  = '<?php echo addcslashes($this->stateVars->default_tmp, '\\') ?>';
		setupDefaultLogsDir = '<?php echo addcslashes($this->stateVars->default_log, '\\') ?>';
	});
</script>
