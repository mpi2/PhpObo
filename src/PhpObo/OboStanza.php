<?php

namespace PhpObo;

class OboStanza extends OboTVPair implements IOboStanza
{
    protected $type = null;

    public function getType()
    {
        return $this->type;
    }
    
    public function setType($type)
    {
        $this->type = $type;
    }
}
