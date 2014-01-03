<?php

require_once 'vendor/autoload.php';

use PhpObo\LineReader,
    PhpObo\Parser;

$start = microtime(true);

//read in obo file
$handle = fopen('mp.obo', 'r');
$lineReader = new LineReader($handle);

//parse file
$parser = new Parser($lineReader);
$parser->retainTrailingComments(true);
$parser->getDocument()->mergeStanzas(false); //speed tip
$parser->parse();

//loop through Term stanzas to find obsolete terms
$obsoleteTerms = array_filter($parser->getDocument()->getStanzas('Term'), function($stanza) {
    return (isset($stanza['is_obsolete']) && $stanza['is_obsolete'] == 'true');
});

echo (microtime(true) - $start), ' seconds and ', memory_get_peak_usage(true),
     ' bytes memory taken to complete.', PHP_EOL, PHP_EOL;
foreach ($obsoleteTerms as $term)
    echo $term['id'] . ' ' . $term['name'] . PHP_EOL;
