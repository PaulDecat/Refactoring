<?php
return (new PhpCsFixer\Config())
	->setRules([
		'@PSR12' => true,
		'array_syntax' => ['syntax' => 'short'],
		'no_unused_imports' => true,
	])
	->setRiskyAllowed(true)
	->setUsingCache(false)
;
