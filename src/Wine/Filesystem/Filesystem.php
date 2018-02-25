<?php

namespace Wine\Filesystem;


class Filesystem
{

	/**
	* Check if a file or directory exist.
	*
	* @param  string  $path
	* @return bool
	*/
	public function exists($path)
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
	public function chmod($path, $mode = null)
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
	public function delete($path)
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
	public function get($path)
	{
		if ($this->isFile($path))
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
	public function put($path, $contents, $lock = false)
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
	public function move($path, $target)
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
	public function copy($path, $target)
	{
		return copy($path, $target);
	}


	/**
	* Extract the file name from a file path.
	*
	* @param  string  $path
	* @return string
	*/
	public function name($path)
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
	public function getFiles($path, $extension = '')
	{
		if ($files = array_diff(scandir($path), array('.', '..')))
		{
			if ($extension != '')
			{
				foreach($files as $index=>$file)
				{
					if ($this->extension($file) !== $extension)
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
	public function basename($path)
	{
		return pathinfo($path, PATHINFO_BASENAME);
	}


	/**
	* Extract the parent directory from a file path.
	*
	* @param  string  $path
	* @return string
	*/
	public function dirname($path)
	{
		return pathinfo($path, PATHINFO_DIRNAME);
	}


	/**
	* Extract the file extension from a file path.
	*
	* @param  string  $path
	* @return string
	*/
	public function extension($path)
	{
		return pathinfo($path, PATHINFO_EXTENSION);
	}


	/**
	* Get the file type of a given file.
	*
	* @param  string  $path
	* @return string
	*/
	public function type($path)
	{
		return filetype($path);
	}


	/**
	* Get the mime-type of a given file.
	*
	* @param  string  $path
	* @return string|false
	*/
	public function mimeType($path)
	{
		return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $path);
	}


	/**
	* Get the file size of a given file.
	*
	* @param  string  $path
	* @return int
	*/
	public function size($path)
	{
		return filesize($path);
	}


	/**
	* Get the file's last modification time.
	*
	* @param  string  $path
	* @return int
	*/
	public function lastModified($path)
	{
		return filemtime($path);
	}


	/**
	* Determine if the given path is a directory.
	*
	* @param  string  $directory
	* @return bool
	*/
	public function isDirectory($directory)
	{
		return is_dir($directory);
	}


	/**
	* Determine if the given path is readable.
	*
	* @param  string  $path
	* @return bool
	*/
	public function isReadable($path)
	{
		return is_readable($path);
	}


	/**
	* Determine if the given path is writable.
	*
	* @param  string  $path
	* @return bool
	*/
	public function isWritable($path)
	{
		return is_writable($path);
	}


	/**
	* Determine if the given path is a file.
	*
	* @param  string  $file
	* @return bool
	*/
	public function isFile($file)
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
	public function glob($pattern, $flags = 0)
	{
		return glob($pattern, $flags);
	}

}
