<?php

namespace Base\Routing;

/**
* The URL Class
*
*/
class Url
{

	/**
	* Allowed characters used in paths and query strings
	*
	* @const string
	*/
	const CHAR_ALLOWED = 'a-zA-Z0-9_\-\.~';


	/**
	* ...
	*
	*/
	protected $url;


	/**
	* ...
	*
	*/
	protected $path;


	/**
	* ...
	*
	*/
	protected $query;


	/**
	* ...
	*
	*
	*/
	public function setUrl($url = null)
	{
		if ($url)
		{
			$this->url = $url;

			$parts = parse_url($url);

			if ($parts)
			{
				$this->setParts($parts);
			}
		}
	}


	/**
	* ...
	*
	*
	*/
	public function setParts(array $parts)
	{
		if (!empty($parts['path']))
		{
			$this->path = $this->filterPath($parts['path']);
		}

		if (!empty($parts['query']))
		{
			$this->query = $parts['query'];
		}
	}


	/**
	* Encodes any dangerous characters, and removes dot segments.
	* While dot segments have valid uses according to the spec,
	* this URI class does not allow them.
	*
	* @param $path
	*
	* @return mixed|string
	*/
	protected function filterPath(string $path = '')
	{
		$orig = $path;

		// Decode/normalize percent-encoded chars so
		// we can always have matching for Routes, etc.
		$path = urldecode($path);

		// Fix up some leading slash edge cases...
		if (strpos($orig, './') === 0)
		{
			$path = '/' . $path;
		}

		if (strpos($orig, '../') === 0)
		{
			$path = '/' . $path;
		}

		$path = preg_replace_callback(
			'/(?:[^' . self::CHAR_ALLOWED . ':@&=\+\$,\/;%]+|%(?![A-Fa-f0-9]{2}))/', function(array $matches) {
				return rawurlencode($matches[0]);
			}, $path
		);

		return $path;
	}


	/**
	* ...
	*
	*
	*/
	public function getUrl()
	{
		return $this->url;
	}


	/**
	* ...
	*
	*
	*/
	public function getPath()
	{
		return $this->path;
	}


	/**
	* ...
	*
	*
	*/
	public function getQuery()
	{
		return $this->query;
	}


	/**
	* Return current instance of self.
	*
	*
	*/
	public function self()
	{
		return $this;
	}


	/**
	* If this instance gets converted to a string,
	* Return the full URL path
	*
	*
	*/
	public function __toString()
	{
		return $this->getUrl();
	}

}
