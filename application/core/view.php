<?php

namespace Application\Core;

/**
 * 
 * Class View, render visible content.
 *
*/
class View
{

    /**
	 * 
     * Render content included into template.
	 * 
	 * @param string $content
	 * @param string $template
	 * @param array $data []
	 * 
     */
	public function render(string $content, string $template, array $data = [])
	{
		if ($data != [])
		{
			extract($data);
		}
		// It necessary if we want to include that.
		$content = $content . '.php';
		$frontCacheControl = time();

		include 'application/views/' . $template . '.php';
	}

	public function readView(string $content, array $data = [])
	{
		if ($data != [])
		{
			extract($data);
		}
		$path = 'application/views/' . $content . '.php';
		
		return (file_exists($path) ? file_get_contents($path) : false);
	}

	public function obfuscateHTMLEntities(string $content)
	{
		return str_replace(array("\n", "\r"), ' ', $content);
	}
}

?>