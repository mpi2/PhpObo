<?php

namespace PhpObo;

class OboInstance extends OboStanza
{
    public function __construct()
    {
        $this->setType('Instance');
    }
    
    /**
     * @param string $type argument passed will be ignored. Always sets to 'Instance'
     */
    public function setType($type)
    {
        $this->type = 'Instance';
    }
    
    public function setId($id)
    {
        $this->tvPairs['id'] = $this->prepareValue($id);
    }
    
    public function setName($name)
    {
        $this->tvPairs['name'] = $this->prepareValue($name);
    }
    
    public function setInstanceOf($id)
    {
        $this->tvPairs['instance_of'] = $this->prepareValue($id);
    }
}
