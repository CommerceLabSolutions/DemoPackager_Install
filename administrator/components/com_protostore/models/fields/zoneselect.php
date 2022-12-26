<?php
/**
 * @package   Pro2Store
 * @author    Ray Lawlor - pro2.store
 * @copyright Copyright (C) 2021 Ray Lawlor - pro2.store
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

// No direct access to this file
use Joomla\CMS\Language\Text;
use Joomla\CMS\Language\LanguageHelper;
use Protostore\Country\Country;
use Protostore\Country\CountryFactory;

defined('_JEXEC') or die('Restricted access');

/**
 * Clicks field.
 *
 * @since 2.0
 */
class JFormFieldZoneselect extends JFormField
{
	/**
	 * The form field type.
	 *
	 * @var    string
	 * @since 2.0
	 */
	protected $type = 'Zoneselect';

	/**
	 * Method to get the field input markup.
	 *
	 * @return  string    The field input markup.
	 *
	 * @since 2.0
	 */
	protected function getInput(): string
	{

		$html = array();


		$zones = CountryFactory::getZoneList(0,0, true);

		$required = ($this->element['required'] ? 'required' : '');
		$html[] = '<select '.$required.' class="uk-select" v-model="form.' . $this->id . '">';
		$html[] = '<option value="">' . Text::_('COM_PROTOSTORE_ZONES_SELECT_A_ZONE') . '</option>';
		/** @var $zones Zones */
		foreach ($zones as $zone) {
			$html[] = '<option value="'.$zone->id.'">' . $zone->zone_name . '</option>';
		}

		$html[] = '</select>';

		return implode('', $html);


	}
}
