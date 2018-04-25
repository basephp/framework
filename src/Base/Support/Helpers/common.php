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


if (! function_exists('utf8ize'))
{
	/**
	* Converts an array/object/string into UTF-8
	*
	* @param  mixed  $mixed
    * @return  mixed  $mixed
	*/
    function utf8ize($mixed)
    {
        if (is_object($mixed))
        {
            foreach($mixed as $key => $value)
            {
                $mixed->$key = utf8ize($value);
            }
        }
        else if (is_array($mixed))
        {
            foreach($mixed as $key => $value)
            {
                $mixed[$key] = utf8ize($value);
            }
        }
        else if (is_string($mixed))
        {
            return utf8_encode($mixed);
        }

        return $mixed;
    }
}


//--------------------------------------------------------------------


if (! function_exists('safe_json_encode'))
{
    /**
    *
    *
    */
    function safe_json_encode($value, $options = 0, $depth = 512, $error = false)
    {
        $encoded = json_encode($value, $options, $depth);

        switch (json_last_error())
        {
            case JSON_ERROR_NONE:
                return $encoded;
            case JSON_ERROR_DEPTH:
                return 'Maximum stack depth exceeded';
            case JSON_ERROR_STATE_MISMATCH:
                return 'Underflow or the modes mismatch';
            case JSON_ERROR_CTRL_CHAR:
                return 'Unexpected control character found';
            case JSON_ERROR_SYNTAX:
                return 'Syntax error, malformed JSON';
            case JSON_ERROR_UTF8:
                if ($error == true) return 'Failed to convert to UTF-8';
                return safe_json_encode(utf8ize($value), $options, $depth, true);
            default:
                return 'Unknown error';
        }
    }
}


//--------------------------------------------------------------------
