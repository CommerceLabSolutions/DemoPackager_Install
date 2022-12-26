<?php
/**
 * @copyright	Copyright (c) 2022 animation. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
jimport('joomla.plugin.plugin');

/**
 * system - pro2storeanimation Plugin
 *
 * @package		Joomla.Plugin
 * @subpakage	animation.pro2storeanimation
 */
class plgsystempro2storeanimation extends JPlugin {

	/**
	 * Constructor.
	 *
	 * @param 	$subject
	 * @param	array $config
	 */
	function __construct(&$subject, $config = array()) {
		// call parent constructor
		parent::__construct($subject, $config);
	}

	/**
	 * Listener for the `onAfterRoute` event
	 *
	 * @return  void
	 *
	 * @since   1.0
	 */
	public function onAfterRender()
	{
		$input = Factory::getApplication()->input;
		$session = Factory::getSession();
		$option  = $input->get('option');
		if($option == "com_protostore"){
			$session->set('animation', true);
		}else{
			$session->set('animation', false);
		}		
	}
}