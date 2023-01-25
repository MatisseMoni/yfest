<?php

namespace YOOtheme\Theme\Joomla;

class StreamWrapper
{
    /**
     * @var array|false
     */
    protected $stat;

    /**
     * @var int
     */
    protected $length;

    /**
     * @var int
     */
    protected $position;

    /**
     * @var string
     */
    protected $output;

    /**
     * @var string[]
     */
    protected static $outputs = [];

    /**
     * @var callable[]
     */
    protected static $objects = [];

    /**
     * Retrieve information about a file.
     */
    public function url_stat($path)
    {
        if (is_callable($object = static::getObject($path))) {
            static::setOutput($path, $object($path));
        }

        if (is_string($output = static::getOutput($path))) {
            return static::getStat($output);
        }

        return false;
    }

    /**
     * Function to open file or url
     */
    public function stream_open($path)
    {
        if (!is_string($output = static::getOutput($path))) {
            return false;
        }

        $this->stat = static::getStat($output);
        $this->length = strlen($output);
        $this->position = 0;
        $this->output = $output;

        return true;
    }

    /**
     * Read stream
     */
    public function stream_read($count)
    {
        $result = substr($this->output, $this->position, $count);

        $this->position += $count;

        return $result;
    }

    /**
     * Retrieve information about a file resource
     */
    public function stream_stat()
    {
        return $this->stat;
    }

    /**
     * Function to get the current position of the stream
     */
    public function stream_tell()
    {
        return $this->position;
    }

    /**
     * Function to test for end of file pointer
     */
    public function stream_eof()
    {
        return $this->position >= $this->length;
    }

    /**
     * The read write position updates in response to $offset and $whence
     */
    public function stream_seek($offset, $whence)
    {
        switch ($whence) {
            case \SEEK_SET:
                if ($offset < $this->length && $offset >= 0) {
                    $this->position = $offset;
                    return true;
                }

                break;

            case \SEEK_CUR:
                if ($offset >= 0) {
                    $this->position += $offset;
                    return true;
                }

                break;

            case \SEEK_END:
                if ($this->length + $offset >= 0) {
                    $this->position = $this->length + $offset;
                    return true;
                }

                break;
        }

        return false;
    }

    /**
     * Change stream options
     */
    public function stream_set_option()
    {
        return true;
    }

    /**
     * Sets a object
     */
    public static function setObject($object)
    {
        $key = spl_object_hash($object);

        static::$objects[$key] = $object;

        return $key;
    }

    /**
     * Gets an object
     */
    protected static function getObject($path)
    {
        $path = substr($path, strpos($path, '://') + 3);

        foreach (static::$objects as $key => $object) {
            if (str_starts_with($path, $key)) {
                return $object;
            }
        }

        return null;
    }

    /**
     * Sets an output
     */
    protected static function setOutput($path, $output)
    {
        static::$outputs[$path] = $output;
    }

    /**
     * Gets an output
     */
    protected static function getOutput($path)
    {
        return static::$outputs[$path] ?? null;
    }

    /**
     * Retrieve file information for a string
     */
    protected static function getStat($string)
    {
        $time = time();
        $length = strlen($string);

        return [
            'dev' => 0,
            'ino' => 0,
            'mode' => 0,
            'nlink' => 1,
            'uid' => 0,
            'gid' => 0,
            'rdev' => 0,
            'size' => $length,
            'atime' => $time,
            'mtime' => $time,
            'ctime' => $time,
            'blksize' => '512',
            'blocks' => ceil($length / 512),
        ];
    }
}
