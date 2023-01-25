<?php

namespace YOOtheme\Builder\Joomla\Source;

use Joomla\Component\Content\Site\Model\ArticlesModel as BaseModel;

class ArticlesModel extends BaseModel
{
    protected function getListQuery()
    {
        $fieldId = false;
        $ordering = $this->getState('list.ordering', '');

        if (str_starts_with($ordering, 'a.field:')) {
            $fieldId = (int) substr($ordering, 8);
            $this->setState('list.ordering', 'fields.value');
        }

        $tags = (array) $this->getState('filter.tags', []);
        $tagOperator = $this->getState('filter.tag_operator', 'IN');

        if ($tags && $tagOperator === 'IN') {
            $this->setState('filter.tag', $tags);
        }

        $query = parent::getListQuery();

        if ($fieldId) {
            $query->leftJoin(
                "#__fields_values AS fields ON a.id = fields.item_id AND fields.field_id = {$fieldId}"
            );
        }

        if ($tags) {
            $tagCount = count($tags);
            $tags = implode(',', $tags);

            if ($tagOperator === 'NOT IN') {
                $query->where(
                    "a.id NOT IN (SELECT content_item_id FROM #__contentitem_tag_map WHERE tag_id IN ({$tags}))"
                );
            }

            if ($tagOperator === 'AND') {
                $query->where(
                    "(SELECT COUNT(1) FROM #__contentitem_tag_map WHERE tag_id IN ({$tags}) AND content_item_id = a.id) = $tagCount"
                );
            }
        }

        if ($this->getState('list.alphanum') && $ordering != 'RAND()') {
            $ordering = $this->getState('list.ordering', 'a.ordering');
            $order = $this->getState('list.direction', 'ASC');
            $query->clear('order');
            $query->order(
                "(substr({$ordering}, 1, 1) > '9') {$order}, {$ordering}+0 {$order}, {$ordering} {$order}"
            );
        }

        return $query;
    }
}
