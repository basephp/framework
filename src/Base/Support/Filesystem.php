<?php

namespace Base\Support;


class Filesystem
{

    /**
    * Check if a file or directory exist.
    *
    * @param  string  $path
    * @return bool
    */
    public static function exists($path)
    {
        return file_exists($path);
    }


	/**
	* Get or set UNIX mode of a file or directory.
	*
	* @param  string  $path
	* @param  int     $mode
	* @return mixed
	*/
	public static function chmod($path, $mode = null)
	{
		if ($mode) return chmod($path, $mode);

		return substr(sprintf('%o', fileperms($path)), -4);
	}


	/**
	* Delete the file at a given path.
	*
	* @param  string  $path
	* @return bool
	*/
	public static function delete($path)
	{
		try
		{
			@unlink($path);
		}
		catch (ErrorException $e)
		{
			return false;
		}

		return true;
	}


	/**
	* Get the contents of a file.
	*
	* @param  string      $path
	* @return string|bool
	*/
	public static function get($path)
	{
		if (self::isFile($path))
		{
			return file_get_contents($path);
		}

		return false;
	}


	/**
	* Write the contents of a file.
	*
	* @param  string  $path
	* @param  string  $contents
	* @param  bool    $lock
	* @return int
	*/
	public static function put($path, $contents, $lock = false)
	{
		return file_put_contents($path, $contents, $lock ? LOCK_EX : 0);
	}


	/**
	* Move a file to a new location.
	*
	* @param  string  $path
	* @param  string  $target
	* @return bool
	*/
	public static function move($path, $target)
	{
		return rename($path, $target);
	}


	/**
	* Copy a file to a new location.
	*
	* @param  string  $path
	* @param  string  $target
	* @return bool
	*/
	public static function copy($path, $target)
	{
		return copy($path, $target);
	}


	/**
	* Extract the file name from a file path.
	*
	* @param  string  $path
	* @return string
	*/
	public static function name($path)
	{
		return pathinfo($path, PATHINFO_FILENAME);
	}


	/**
	* Get all the files within a directory (and by extension if requested)
	*
	* @param  string  $path
	* @param  string  $extension
	* @return array
	*/
	public static function getFiles($path, $extension = '')
	{
		if ($files = array_diff(scandir($path), array('.', '..')))
		{
			if ($extension != '')
			{
				foreach($files as $index=>$file)
				{
					if (self::extension($file) !== $extension)
					{
						unset($files[$index]);
					}
				}
			}

			return $files;
		}

		return [];
	}


	/**
	* Extract the trailing name component from a file path.
	*
	* @param  string  $path
	* @return string
	*/
	public static function basename($path)
	{
		return pathinfo($path, PATHINFO_BASENAME);
	}


	/**
	* Extract the parent directory from a file path.
	*
	* @param  string  $path
	* @return string
	*/
	public static function dirname($path)
	{
		return pathinfo($path, PATHINFO_DIRNAME);
	}


	/**
	* Extract the file extension from a file path.
	*
	* @param  string  $path
	* @return string
	*/
	public static function extension($path)
	{
		return pathinfo($path, PATHINFO_EXTENSION);
	}


	/**
	* Get the file type of a given file.
	*
	* @param  string  $path
	* @return string
	*/
	public static function type($path)
	{
		return filetype($path);
	}


	/**
	* Get the mime-type of a given file.
	*
	* @param  string  $path
	* @return string|false
	*/
	public static function mimeType($path)
	{
		return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
	}


	/**
	* Get the file size of a given file.
	*
	* @param  string  $path
	* @return int
	*/
	public static function size($path)
	{
		return filesize($path);
	}


	/**
	* Get the file's last modification time.
	*
	* @param  string  $path
	* @return int
	*/
	public static function lastModified($path)
	{
		return filemtime($path);
	}


    /**
    * Create a new directory.
    *
    * @param  string  $path
    * @param  string  $chmod
    * @param  string  $recursive
    * @return bool
    */
    public static function makeDirectory($path, $chmod = 0775, $recursive = false)
    {
        return mkdir($path, $chmod, $recursive);
    }


	/**
	* Determine if the given path is a directory.
	*
	* @param  string  $directory
	* @return bool
	*/
	public static function isDirectory($directory)
	{
		return is_dir($directory);
	}


	/**
	* Determine if the given path is readable.
	*
	* @param  string  $path
	* @return bool
	*/
	public static function isReadable($path)
	{
		return is_readable($path);
	}


	/**
	* Determine if the given path is writable.
	*
	* @param  string  $path
	* @return bool
	*/
	public static function isWritable($path)
	{
		return is_writable($path);
	}


	/**
	* Determine if the given path is a file.
	*
	* @param  string  $file
	* @return bool
	*/
	public static function isFile($file)
	{
		return is_file($file);
	}


	/**
	* Find path names matching a given pattern.
	*
	* @param  string  $pattern
	* @param  int     $flags
	* @return array
	*/
	public static function glob($pattern, $flags = 0)
	{
		return glob($pattern, $flags);
	}

}
