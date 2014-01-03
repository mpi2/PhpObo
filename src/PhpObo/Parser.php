<?php

namespace PhpObo;

class Parser
{
    protected $lineReader = null;
    protected $document = null;
    protected $stanzaClass = null;
    protected $headerClass = null;
    protected $retainComments = null;

    public function __construct(ILineReader $lineReader)
    {
        $this->lineReader = $lineReader;
        $this->setDefaultDocument();
        $this->setDefaultHeaderClass();
        $this->setDefaultStanzaClass();
    }
    
    public function setDocument(IOboDocument $document)
    {
        $this->document = $document;
    }
    
    public function setDefaultDocument()
    {
        $this->document = new OboDocument();
    }

    public function getDocument()
    {
        return $this->document;
    }
    
    public function setStanzaClass(IOboTVPair &$class)
    {
        $this->termClass = $class;
    }
    
    public function setDefaultStanzaClass()
    {
        $this->stanzaClass = new OboStanza();
    }

    public function setHeaderClass(IOboTVPair &$class)
    {
        $this->headerClass = $class;
    }
    
    public function setDefaultHeaderClass()
    {
        $this->headerClass = new OboHeader();
    }
    
    public function parse()
    {
        $isHeader = true;
        $header = clone $this->headerClass;
        if ( ! is_null($this->retainComments))
            $header->retainTrailingComments($this->retainComments);
        $stanza = null;
        
        while (false !== ($line = $this->lineReader->getLine())) {
            
            $line = trim($line);
            
            //if line is blank or is a whole comment line then skip it
            if ($line == '' || $line[0] == '!')
                continue;
            
            if ($isHeader && $this->isNewStanza($line))
                $isHeader = false;
            
            if ($isHeader) {
                
                list($tag, $value) = preg_split('/:/', $line, 2, PREG_SPLIT_NO_EMPTY);
                $header->add($tag, $value);
                
            } else {
                
                //when it spots a new Stanza (e.g. [Term]) line
                if ($this->isNewStanza($line))
                {
                    if ( ! is_null($stanza))
                        $this->document->addStanza($stanza);
                    
                    $stanza = clone $this->stanzaClass;
                    if ( ! is_null($this->retainComments))
                        $stanza->retainTrailingComments($this->retainComments);
                    $stanza->setType(trim($line, ']['));
                }
                else
                {
                    list($tag, $value) = preg_split('/:/', $line, 2, PREG_SPLIT_NO_EMPTY);
                    $stanza->add($tag, $value);
                }
            }
        }
        
        if ( ! is_null($stanza))
            $this->document->addStanza($stanza);
        $this->document->setHeader($header);
    }
    
    protected function isNewStanza($line)
    {
        return (bool)preg_match('/^\[[\w]+\]$/', $line);
    }
    
    public function retainTrailingComments($retain = true)
    {
        $this->retainComments = (bool)$retain;
    }
}
