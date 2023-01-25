<?php

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;

foreach ($list as $item) {
	include ModuleHelper::getLayoutPath('mod_articles_news', '_item');
}
