<?php

namespace PhpObo;

class OboTerm extends OboStanza
{
    public function __construct()
    {
        $this->setType('Term');
    }
    
    /**
     * @param string $type argument passed will be ignored. Always sets to 'Term'
     */
    public function setType($type)
    {
        $this->type = 'Term';
    }
    
    public function setId($id)
    {
        $this->tvPairs['id'] = $this->prepareValue($id);
    }
    
    public function setName($name)
    {
        $this->tvPairs['name'] = $this->prepareValue($name);
    }
}
