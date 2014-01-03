<?php

namespace PhpObo;

class OboSerializer implements IOboSerializer
{
    
    public static function serialize(IOboDocument $document)
    {
        //serialize header
        $s = static::serializeHeader($document->getHeader());
        
        //serialize body        
        $stanzas = $document->getStanzas();
        
        //ensure the order of stanzas start with Typedef, Term, Instance
        foreach ($document->getStanzaTags() as $stanza) {
            if (isset($stanzas[$stanza])) {
                $s .= static::serializeStanzas($document, array($stanza => $stanzas[$stanza]));
                unset($stanzas[$stanza]);
            }
        }
        
        //then other stanzas should be sorted alphabetically
        ksort($stanzas);
        $s .= static::serializeStanzas($document, $stanzas);
        
        return $s;
    }
    
    protected static function serializeHeader(IOboTVPair $header)
    {
        $s = '';
        
        $tvPairs = $header->getTVPairs();
        
        //overwrite generator
        $tvPairs['auto-generated-by'] = 'PhpObo 1.0';
        
        ksort($tvPairs);
        
        foreach ($tvPairs as $tag => $value) {
            $s .= static::serializeTagValue($tag, $value);
        }
        
        return $s . PHP_EOL;
    }

    protected static function serializeStanzas(IOboDocument &$document, array $stanzas)
    {
        $s = '';
        
        foreach ($stanzas as $type => $tvPairSet) {
            foreach ($tvPairSet as $tvPairs) {
                $s .= "[{$type}]" . PHP_EOL;
                $s .= static::serializeTVPairs($document, $type, $tvPairs);
            }
        }
        
        return $s;
    }
    
    protected static function serializeTVPairs(IOboDocument &$document, $type, $tvPairs)
    {
        $s = '';
        
        //ensure the order of the tags as defined in the class properties
        if (in_array($type, $document->getStanzaTags())) {
            foreach ($document->{"get{$type}Tags"}() as $tag) {
                if (isset($tvPairs[$tag])) {
                    $s .= static::serializeTagValue($tag, $tvPairs[$tag]);
                    unset($tvPairs[$tag]);
                }
            }
        }
        
        foreach ($tvPairs as $tag => $value) {
            $s .= static::serializeTagValue($tag, $value);
        }
        
        return $s . PHP_EOL;
    }
    
    protected static function serializeTagValue($tag, $value)
    {
        if (is_array($value)) {
            $s = '';
            sort($value);
            foreach ($value as $val) {
                $s .= $tag . ': ' . $val . PHP_EOL;
            }
            return $s;
        }
        
        return $tag . ': ' . $value . PHP_EOL;
    }
}
