What is PhpObo?
===============

PhpObo is a library for reading, creating and modifying files in the OBO format
(http://geneontology.org/GO.format.obo-1_2.shtml) using the PHP programming
language. It's not a massive, fully featured solution, but you are more than
welcome to extend it. PhpObo is capable of:

* Reading in (parsing) OBO files
* Creating and manipulating OBO documents using a PHP API
* Serializing generated/parsed OBO documents back out again

Installation
============

1. Download and Install the Composer dependency management tool (http://getcomposer.org)
2. Add PhpObo to your composer.json file as a dependency or have it autoload the
PhpObo directory if you downloaded the source archive previously
3. If you haven't done so already, you need to prime the Composer autoloader -
run this in your console: php composer.phar install
4. Look at the example files (*_example.php) for an idea of how to use the library.
You might want to get the Mammalian Phenotype (MP) OBO file from
http://www.obofoundry.org/ to run the examples (rename it to mp.obo)
