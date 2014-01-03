<?php

namespace PhpObo;

interface IOboDocument
{
    public function getHeader();
    public function getStanzas();
    public function getStanzaTags();
    public function setStanzaTags(array $tags);
    public function getTermTags();
    public function setTermTags(array $tags);
    public function getTypedefTags();
    public function setTypedefTags(array $tags);
    public function getInstanceTags();
    public function setInstanceTags(array $tags);
    public function setHeader(IOboTVPair $header);
    public function addStanza(IOboTVPair $stanza);
}
