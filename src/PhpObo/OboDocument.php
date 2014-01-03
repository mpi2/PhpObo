<?php

namespace PhpObo;

class OboDocument implements IOboDocument
{
    protected $header = null;
    protected $stanzas = null;
    protected $merge = null;
    protected $stanzaTags = array(
        'Typedef', 'Term', 'Instance'
    );
    protected $termTags = array(
        'id', 'is_anonymous', 'name', 'namespace', 'alt_id',
        'def', 'comment', 'subset', 'synonym', 'xref',
        'is_a', 'intersection_of', 'union_of', 'disjoint_from', 'relationship',
        'is_obsolete', 'replaced_by', 'consider', 'created_by', 'creation_date'
    );
    protected $typedefTags = array(
        'id', 'is_anonymous', 'name', 'namespace', 'alt_id',
        'def', 'comment', 'subset', 'synonym', 'xref',
        'domain', 'range', 'is_anti_symmetric', 'is_cyclic', 'is_reflexive',
        'is_symmetric', 'is_transitive', 'is_a', 'inverse_of', 'transitive_over',
        'relationship', 'is_obsolete', 'replaced_by', 'consider'
    );
    protected $instanceTags = array(
        'id', 'is_anonymous', 'name', 'namespace', 'alt_id',
        'def', 'comment', 'subset', 'synonym', 'xref',
        'instance_of', 'property_value', 'is_obsolete', 'replaced_by', 'consider'
    );

    public function __construct()
    {
        $this->mergeStanzas(true);
    }

    public function mergeStanzas($merge = true)
    {
        $this->merge = (bool)$merge;
    }

    public function getHeader()
    {
        return $this->header;
    }
    
    public function getStanzas($type = null)
    {
        if ($type && isset($this->stanzas[$type]))
            return (array)$this->stanzas[$type];
        return (array)$this->stanzas;
    }
    
    /**
     * @throws \Exception
     * @param IOboTVPair $header OBO document header
     */
    public function setHeader(IOboTVPair $header)
    {
        if ( ! $header->get('format-version'))
            throw new \Exception('Format-version missing from Header');
        $this->header = $header;
    }
    
    public function addStanza(IOboTVPair $stanza)
    {
        $tvPairs = $stanza; //treat object like hash (SPL ArrayAccess)
        $type = $stanza->getType();
        
        if ( ! isset($tvPairs['id']) || $tvPairs['id'] === '')
            throw new \Exception('Id tag missing in Stanza');

        if ($this->merge && isset($this->stanzas[$type])) {
            for ($i = 0; $i < count($this->stanzas[$type]); $i++) {
                if ($this->stanzas[$type][$i]['id'] == $tvPairs['id']) {
                    foreach (array_keys($tvPairs) as $key) {
                        if ( ! isset($this->stanzas[$type][$i][$key]) ||
                            (isset($this->stanzas[$type][$i][$key]) && ! is_array($this->stanzas[$type][$i][$key]))
                        )
                        {
                            $this->stanzas[$type][$i][$key] = $tvPairs[$key];
                        }
                        else if (is_array($this->stanzas[$type][$i][$key]) && is_array($tvPairs[$key]))
                        {
                            foreach ($tvPairs[$key] as $value)
                                $this->stanzas[$type][$i][$key][] = $value;
                            $this->stanzas[$type][$i][$key] = array_unique($this->stanzas[$type][$i][$key]);
                        }
                        else
                        {
                            $this->stanzas[$type][$i][$key][] = $tvPairs[$key];
                        }
                    }
                    return;
                }
            }
        }
        
        $this->stanzas[$stanza->getType()][] = $tvPairs;
    }
    
    public function serialize(IOboSerializer $serializer = null)
    {
        if ($serializer)
            return $serializer::serialize($this);
        return OboSerializer::serialize($this);
    }
    
    public function getStanzaTags()
    {
        return $this->stanzaTags;
    }
    
    public function setStanzaTags(array $tags)
    {
        $this->stanzaTags = $tags;
    }

    public function getTermTags()
    {
        return $this->termTags;
    }
    
    public function setTermTags(array $tags)
    {
        $this->termTags = $tags;
    }
    
    public function getInstanceTags()
    {
        return $this->instanceTags;
    }
    
    public function setInstanceTags(array $tags)
    {
        $this->instanceTags = $tags;
    }
    
    public function getTypedefTags()
    {
        return $this->typedefTags;
    }
    
    public function setTypedefTags(array $tags)
    {
        $this->typedefTags = $tags;
    }
}
