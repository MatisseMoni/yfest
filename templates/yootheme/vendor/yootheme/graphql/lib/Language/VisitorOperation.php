<?php

declare(strict_types=1);

namespace YOOtheme\GraphQL\Language;

class VisitorOperation
{
    /** @var bool */
    public $doBreak;

    /** @var bool */
    public $doContinue;

    /** @var bool */
    public $removeNode;
}
