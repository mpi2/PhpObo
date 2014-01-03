<?php

namespace PhpObo;

interface IOboTVPair extends \ArrayAccess, \Iterator
{
    public function add($tag, $value = null);
    public function remove($tag);
    public function get($tag);
    public function __get($tag);
    public function __set($tag, $value);
    public function getTVPairs();
    public function setTVPairs(array $pairs);
}
