<?php

namespace PhpObo;

class LineReader implements ILineReader
{
    protected $handle = null;

    public function __construct(&$handle = null)
    {
        if ($handle)
            $this->setHandle($handle);
    }
    
    /**
     * @throws \Exception
     * @param resource $handle 
     */
    public function setHandle(&$handle)
    {
        if ( ! is_resource($handle))
            throw new \Exception('Invalid handle passed to LineReader');
        $this->handle = $handle;
    }
    
    public function getHandle()
    {
        return $this->handle;
    }

    public function getLine()
    {
        $line = fscanf($this->handle, "%[^\n\r]");
        return ($line === false) ? false : current($line);
    }
}
