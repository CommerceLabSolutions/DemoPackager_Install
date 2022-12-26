<?php

/**
 * @package   Universal Elements 
 * @author    Cloud Chief - CommerceLab.solutions
 * @copyright Copyright (C) 2022 CommerceLab  - CommerceLab.solutions
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 */

namespace YpsApp_userform;
use \Joomla\CMS\Factory;
use Joomla\Component\Fields\Administrator\Helper\FieldsHelper;
use YOOtheme\Config as Yooconfig;
use YOOtheme\Builder;
use YOOtheme\Path;
use Joomla\CMS\Form\Form;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Language\Text;
return [

    'extend' => [

        Builder::class => function (Builder $builder) {

            $builder->addTypePath(Path::get('./elements/*/element.json'));

        },

    ],
    'config' => function (Yooconfig $yooconfig) {
        $lang = Factory::getLanguage();
        $lang->load('plg_user_profile', JPATH_ADMINISTRATOR);
        $lang->load('com_users', JPATH_SITE);
        $profileForm =  Form::getInstance("user.profile", JPATH_ROOT.'/plugins/user/profile/forms/profile.xml', array());
        foreach ($profileForm->getFieldsets() as $profile_fieldset) {
            $profile_fields = $profileForm->getFieldset($profile_fieldset->name);
            $profile = array();            
            foreach ($profile_fields as $profile_field) { 
               // $profile[str_replace("]","",str_replace("profile[", "", $profile_field->name))] = str_replace("]","",str_replace("profile[", "", $profile_field->name));
                $profile[strip_tags(Text::_($profile_field->label))] = str_replace("]","",str_replace("profile[", "", $profile_field->name));
            }
        }
        $coreForm =  Form::getInstance("com_users.user", JPATH_ROOT.'/components/com_users/forms/registration.xml', array());
        foreach ($coreForm->getFieldsets() as $core_fieldset) {
            $core_fields = $coreForm->getFieldset($core_fieldset->name);
            $core = array();            
            foreach ($core_fields as $core_field) {
              if($core_field->name != "spacer"){
                $core[str_replace("&#160;*","",strip_tags(Text::_($core_field->label)))] = $core_field->name;
              }
            }
        }

        $extra =  FieldsHelper::getFields('com_users.user', Factory::getUser(), true);
        $ex = array(); 
        foreach ($extra as $extra_fieldset) { 
            $ex[$extra_fieldset->label] = $extra_fieldset->name;
        }

        $final_fields =  array_merge(array_merge($core,$profile),$ex);

        return [
            'userFields' => [
                'joomla' => [
                    'fields' => $final_fields,
                ]
            ]
        ];
    }

];