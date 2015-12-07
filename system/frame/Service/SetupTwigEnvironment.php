<?php

/**
 * Frame SetupTwigEnvironment service
 *
 * @package frame
 * @author TJ Draper <tj@buzzingpixel.com>
 * @link https://buzzingpixel.com/frame-cms
 * @copyright Copyright (c) 2015, BuzzingPixel
 */

namespace Frame\Service;

class SetupTwigEnvironment
{
	/**
	 * Setup twig template environment
	 */
	public function set()
	{
		// Get the Twig loader
		$loader = new \Twig_Loader_Filesystem(USER_PATH . '/templates');

		// Setup the Twig environment
		$twig = new \Twig_Environment($loader);

		// Load any filters
		$twig = $this->loadFilters($twig);

		// Load any functions
		$twig = $this->loadFunctions($twig);

		// Load any tags
		$twig = $this->loadTags($twig);

		// Set twig to the frame object
		frame()->set('twig', $twig);
	}

	/**
	 * Load filters
	 *
	 * @param object $twig Twig instance
	 * @return object Twig instance
	 */
	private function loadFilters($twig)
	{
		$path = FRAME_PATH . '/TwigFilters/';
		$filters = scandir($path);
		unset($filters[0]);
		unset($filters[1]);

		$namespace = array(
			'\Frame',
			'TwigFilters'
		);

		foreach ($filters as $filterName) {
			if (! is_dir($path . $filterName)) {
				continue;
			}

			$namespace[] = $filterName;
			$namespace[] = $filterName . '_Filter';
			$namespace = implode('\\', $namespace);

			$filter = new \Twig_SimpleFilter(
				lcfirst($filterName),
				array(
					$namespace,
					'index'
				)
			);

			$twig->addFilter($filter);
		}

		return $twig;
	}

	/**
	 * Load twig functions
	 *
	 * @param object $twig Twig instance
	 * @return object Twig instance
	 */
	public function loadFunctions($twig)
	{
		$path = FRAME_PATH . '/TwigFunctions/';
		$functions = scandir($path);
		unset($functions[0]);
		unset($functions[1]);

		$namespace = array(
			'\Frame',
			'TwigFunctions'
		);

		foreach ($functions as $functionName) {
			if (! is_dir($path . $functionName)) {
				continue;
			}

			$namespace[] = $functionName;
			$namespace[] = $functionName . '_Function';
			$namespace = implode('\\', $namespace);

			$function = new \Twig_SimpleFunction(
				lcfirst($functionName),
				array(
					$namespace,
					'index'
				)
			);

			$twig->addFunction($function);
		}

		return $twig;
	}

	/**
	 * Load twig tags
	 *
	 * @param object $twig Twig instance
	 * @return object Twig instance
	 */
	public function loadTags($twig)
	{
		$path = FRAME_PATH . '/TwigTags/';
		$tags = scandir($path);
		unset($tags[0]);
		unset($tags[1]);

		$namespace = array(
			'\Frame',
			'TwigTags'
		);

		foreach ($tags as $tagName) {
			if (! is_dir($path . $tagName)) {
				continue;
			}

			$namespace[] = $tagName;
			$namespace[] = $tagName . '_TokenParser';
			$namespace = implode('\\', $namespace);

			$twig->addTokenParser(new $namespace());
		}

		return $twig;
	}
}
