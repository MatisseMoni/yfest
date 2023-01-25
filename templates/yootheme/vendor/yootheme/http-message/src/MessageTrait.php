<?php

namespace YOOtheme\Http;

trait MessageTrait
{
    /**
     * Gets content type.
     *
     * @return string|null
     */
    public function getContentType()
    {
        $result = $this->getHeader('Content-Type');

        return $result ? $result[0] : null;
    }

    /**
     * Gets content length.
     *
     * @return int|null
     */
    public function getContentLength()
    {
        $result = $this->getHeader('Content-Length');

        return $result ? (int) $result[0] : null;
    }
}
