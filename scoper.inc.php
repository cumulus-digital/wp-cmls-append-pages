<?php

declare( strict_types=1 );

use Isolated\Symfony\Component\Finder\Finder;

const SCOPER_PREFIX = 'CUMULUS\Wordpress\AppendPages';

$composer = \json_decode( \file_get_contents( 'composer.json' ), true );

$wp_functions = \str_getcsv( \file_get_contents( __DIR__ . '/.php-cs-fixer/wp-functions.csv' ) );
$wp_classes   = \str_getcsv( \file_get_contents( __DIR__ . '/.php-cs-fixer/wp-classes.csv' ) );
$wp_consts    = \str_getcsv( \file_get_contents( __DIR__ . '/.php-cs-fixer/wp-consts.csv' ) );

return [
	// The prefix configuration. If a non null value will be used, a random prefix will be generated.
	'prefix' => SCOPER_PREFIX,

	// By default when running php-scoper add-prefix, it will prefix all relevant code found in the current working
	// directory. You can however define which files should be scoped by defining a collection of Finders in the
	// following configuration key.
	//
	// For more see: https://github.com/humbug/php-scoper#finders-and-paths
	'finders' => [
		//Finder::create()->files()->in( 'vendor' ),
		Finder::create()
			->files()
			->ignoreVCS( true )
			->notName( '/LICENSE|.*\\.md|.*\\.dist|Makefile|composer\\.json|composer\\.lock/' )
			->exclude( [
				'doc',
				'test',
				'test_old',
				'tests',
				'Tests',
				'vendor-bin',
				'php-cs-fixer',
				'friendsofphp',
			] )
			->notPath( \array_keys( $composer['require-dev'] ) )
			->notPath( 'friendsofphp' )
			->notPath( 'bin' )
			->in( 'vendor' ),
		Finder::create()->append( [
			'composer.json',
			'composer.lock',
		] ),
	],

	// Whitelists a list of files. Unlike the other whitelist related features, this one is about completely leaving
	// a file untouched.
	// Paths are relative to the configuration file unless if they are already absolute
	/*
	'files-whitelist' => [
		//'src/a-whitelisted-file.php',
	],
	*/
	'exclude-files' => [
		'vendor/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php',
	],

	// When scoping PHP files, there will be scenarios where some of the code being scoped indirectly references the
	// original namespace. These will include, for example, strings or string manipulations. PHP-Scoper has limited
	// support for prefixing such strings. To circumvent that, you can define patchers to manipulate the file to your
	// heart contents.
	//
	// For more see: https://github.com/humbug/php-scoper#patchers
	'patchers' => [
		function ( string $filePath, string $prefix, string $contents ): string {
			// Change the contents here.

			// Fix TGMPA_Utils calls
			if ( $filePath === __DIR__ . '/vendor/tgmpa/tgm-plugin-activation/class-tgm-plugin-activation.php' ) {
				/*
				$contents = \str_replace(
					"array('TGMPA_Utils',",
					"array('{$prefix}\\TGMPA_Utils',",
					$contents
				);
				*/
			}

			return $contents;
		},
	],

	// PHP-Scoper's goal is to make sure that all code for a project lies in a distinct PHP namespace. However, you
	// may want to share a common API between the bundled code of your PHAR and the consumer code. For example if
	// you have a PHPUnit PHAR with isolated code, you still want the PHAR to be able to understand the
	// PHPUnit\Framework\TestCase class.
	//
	// A way to achieve this is by specifying a list of classes to not prefix with the following configuration key. Note
	// that this does not work with functions or constants neither with classes belonging to the global namespace.
	//
	// Fore more see https://github.com/humbug/php-scoper#whitelist
	/*
	'whitelist' => \array_merge( $wp_functions, $wp_consts, $wp_classes, [
		// 'PHPUnit\Framework\TestCase',   // A specific class
		// 'PHPUnit\Framework\*',          // The whole namespace
		// '*',                            // Everything
	] ),
	*/

	// If `true` then the user defined constants belonging to the global namespace will not be prefixed.
	//
	// For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace
	'expose-global-constants' => true,

	// If `true` then the user defined classes belonging to the global namespace will not be prefixed.
	//
	// For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace
	'expose-global-classes' => true,

	// If `true` then the user defined functions belonging to the global namespace will not be prefixed.
	//
	// For more see https://github.com/humbug/php-scoper#constants--constants--functions-from-the-global-namespace
	'expose-global-functions' => true,

	'exclude-classes'   => $wp_classes,
	'exclude-functions' => \array_merge(
		[
			'wp_count_terms',
		],
		\array_map( 'strtolower', $wp_functions )
	),
	'exclude-constants' => $wp_consts,
];
