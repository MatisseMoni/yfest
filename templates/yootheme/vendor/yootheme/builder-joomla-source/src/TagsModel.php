<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\CMS\Factory;
use Joomla\Component\Tags\Site\Model\TagsModel as BaseModel;

class TagsModel extends BaseModel
{
    protected function getListQuery()
    {
        $query = parent::getListQuery();
        $params = $this->state->get('params');
        $this->setState('list.start', $params->get('list.start'));

        if ($params->get('all_tags_orderby', 'title') == 'rand') {
            $query->clear('order');
            $query->order(
                Factory::getDbo()
                    ->getQuery(true)
                    ->rand()
            );
        }

        return $query;
    }
}
