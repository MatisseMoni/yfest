<?php

namespace YOOtheme\Theme\Joomla;

use Joomla\CMS\HTML\HTMLHelper;
use YOOtheme\Theme\ViewHelper as BaseViewHelper;

class ViewHelper extends BaseViewHelper
{
    /**
     * @inheritdoc
     */
    public function image($url, array $attrs = [])
    {
        $url = (array) $url;
        $url[0] = $this->cleanImageUrl($url[0]);

        return parent::image($url, $attrs);
    }

    /**
     * @inheritdoc
     */
    public function bgImage($url, array $params = [])
    {
        return parent::bgImage($this->cleanImageUrl($url), $params);
    }

    /**
     * @inheritdoc
     */
    public function comImage($element, array $params = [])
    {
        if (!empty($element->attrs['src'])) {
            $element->attrs['src'] = $this->cleanImageUrl($element->attrs['src']);
        }
        parent::comImage($element, $params);
    }

    protected function cleanImageUrl($url)
    {
        return $this->isAbsolute($url) ? $url : HTMLHelper::_('cleanImageURL', $url)->url;
    }
}
