<?php

namespace PhpObo;

abstract class OboTVPair implements IOboTVPair
{
    protected $tvPairs = array();
    protected $retainComments = true;

    public function add($tag, $value = null)
    {
        $value = (is_null($value)) ? '' : $this->prepareValue($value);
        $tag = $this->prepareTag($tag);
        
        if (isset($this->tvPairs[$tag]) && ! is_array($this->tvPairs[$tag])) {
            $this->tvPairs[$tag] = (array)$this->tvPairs[$tag];
        }
        
        if (isset($this->tvPairs[$tag]) && is_array($this->tvPairs[$tag])) {
            $this->tvPairs[$tag][] = $value;
        } else {
            $this->tvPairs[$tag] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->tvPairs[$this->prepareTag($offset)]);
    }
    
    public function offsetUnset($offset)
    {
        $this->remove($this->prepareTag($offset));
    }
    
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
    
    public function offsetSet($offset, $value)
    {
        $this->add($this->prepareTag($offset), $this->prepareValue($value));
    }

    public function remove($tag)
    {
        unset($this->tvPairs[$this->prepareTag($tag)]);
    }
    
    public function get($tag)
    {
        $tag = $this->prepareTag($tag);
        return (isset($this->tvPairs[$tag])) ? $this->tvPairs[$tag] : false;
    }
    
    public function current()
    {
        return current($this->tvPairs);
    }
    
    public function key()
    {
        return key($this->tvPairs);
    }
    
    public function next()
    {
        return next($this->tvPairs);
    }
    
    public function rewind()
    {
        reset($this->tvPairs);
    }
    
    public function valid()
    {
        return (key($this->tvPairs) !== null);
    }

    public function __get($tag)
    {
        return $this->get($tag);
    }
    
    public function __set($tag, $value)
    {
        $this->add($tag, $value);
    }
    
    protected function prepareTag($tag)
    {
        return preg_replace('/[_]+/', '_', preg_replace('/[^a-zA-Z0-9_-]/', '_', $tag));
    }
    
    protected function prepareValue($value = null)
    {
        if ( ! $this->retainComments) {
            $value = preg_replace('/[^\\\\]!.*$/', '', $value);
            $value = str_replace('\\!', '!', $value);
        }
        return trim($value);
    }
    
    public function retainTrailingComments($retain = true)
    {
        $this->retainComments = (bool)$retain;
    }
    
    public function getTVPairs()
    {
        return $this->tvPairs;
    }
    
    public function setTVPairs(array $pairs)
    {
        $this->tvPairs = $pairs;
    }
}
