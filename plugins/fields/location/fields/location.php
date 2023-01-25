<?php

defined('_JEXEC') or die();

use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Registry\Registry;

class JFormFieldLocation extends FormField
{
    public $type = 'location';

    public function getInput()
    {
        HTMLHelper::_('script', Uri::root() . 'plugins/fields/location/app/location.min.js');

        $params = new Registry(PluginHelper::getPlugin('fields', 'location')->params);
        $document = Factory::getDocument();
        $document->addScriptOptions('location', $params);

        $data = parent::getLayoutData();

        return "<yootheme-field-location><input type=\"hidden\" name=\"{$data['name']}\" value=\"{$data['value']}\"></yootheme-field-location>";
    }
}
