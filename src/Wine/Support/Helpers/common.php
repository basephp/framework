<?php

if (! function_exists('format_bytes'))
{
	/**
	* Returns a readable bytes into string formated
	*
	* @param  string  $bytes
	* @param  array   $precision
	*/
	function format_bytes($bytes, $precision = 2)
	{
		$base = log($bytes, 1024);
		$suffixes = array('', 'KB', 'MB', 'GB', 'TB');

		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}
}


//--------------------------------------------------------------------


if (!function_exists('random_string'))
{
	/**
	* Generates a random string
	*
	* if $length = 'id' then generate a UUID format, otherwise if mb_strlen exist, use normal generator
	*
	* @source https://stackoverflow.com/questions/4356289/php-random-string-generator/31107425#31107425
	*
	* @param  int     $length (how long of a string to return)
	* @param  string  $characters (allowed characters to use)
	*
	* @return string  $str
	*/
	function random_string($length = 32, $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
	{
		if (function_exists('mb_strlen') && $length !== 'id')
		{
			$str = '';
			$max = mb_strlen($characters, '8bit') - 1;
			for ($i = 0; $i < $length; ++$i)
			{
				$str .= $characters[random_int(0, $max)];
			}

			return $str;
		}
		else
		{
			return uniqid( time() );
		}
	}
}


//--------------------------------------------------------------------


if (! function_exists('value'))
{
	/**
	* Return the default value of the given value.
	*
	* @param  mixed  $value
	* @return mixed
	*/
	function value($value)
	{
		return $value instanceof Closure ? $value() : $value;
	}
}


//--------------------------------------------------------------------


if (! function_exists('valid_ip'))
{
	/**
	* Check if the current IP is valid
	*
	* @param  string  $ip
	*/
	function valid_ip($ip)
	{
		if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false)
		{
			return false;
		}

		return true;
	}
}


//--------------------------------------------------------------------
