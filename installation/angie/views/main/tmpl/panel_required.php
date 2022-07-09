<?php
/**
 * ANGIE - The site restoration script for backup archives created by Akeeba Backup and Akeeba Solo
 *
 * @package   angie
 * @copyright Copyright (c)2009-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

/** @var  AngieViewMain  $this */
?>
<div>

	<h3><?php echo AText::_('MAIN_HEADER_REQUIRED') ?></h3>

	<p><?php echo AText::_('MAIN_LBL_REQUIRED') ?></p>
	<div width="100%">
		<div>
		<?php foreach ($this->reqSettings as $key => $option): ?>
			<div class="uk-margin-small" uk-grid>
				<div class="uk-width-expand">
					<label style="width:250px">
						<?php echo $option['label']; ?>
					</label>
					<?php if (array_key_exists('notice',$option) && $option['notice']): ?>
						<div class="akeeba-help-text">
							<?php echo $option['notice']; ?>
						</div>
					<?php endif; ?>
				</div>
				<div class="uk-width-auto">
						<span class="akeeba-label--<?php echo $option['current'] ? 'success' : 'failure'; ?>">
							<?php echo $option['current'] ? AText::_('GENERIC_LBL_YES') : AText::_('GENERIC_LBL_NO'); ?>
						</span>
				</div>
			</div>
		<?php endforeach; ?>
		</div>
	</div>
</div>
