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

//serialize back into obo format
$doc = $parser->getDocument();
$serialized = $doc->serialize();

echo (microtime(true) - $start), ' seconds and ', memory_get_peak_usage(true),
     ' bytes memory taken to complete.', PHP_EOL, PHP_EOL, $serialized;
