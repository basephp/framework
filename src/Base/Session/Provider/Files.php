<?php

namespace Base\Session\Provider;

use Base\Session\ProviderInterface;
use Base\Support\Filesystem;


/**
* The Session Files Provider
*
*/
class Files Implements ProviderInterface
{

    /**
     * The location where sessions should be stored.
     *
     * @var string
     */
    protected $location;


    /**
    * Destroys the session.
    *
    */
    public function destroy(string $key = null)
    {
        Filesystem::delete($this->location.$key);
    }


    /**
    * Set the session
    * If the directory doesn't exist, create it.
    *
    */
    public function set($key = '', array $data = [])
    {
        if (!Filesystem::isDirectory($this->location))
        {
            Filesystem::makeDirectory($this->location, 0775, true);
        }

        // save the session file
        Filesystem::put($this->location.$key, serialize($data));
    }


    /**
    * Get the user session
    *
    */
    public function get(string $key = null)
    {
        return unserialize(Filesystem::get($this->location.$key));
    }


    /**
    * Garbage Collector - clears all old sessions
    *
    */
    public function gc(int $maxlifetime)
    {
		$ts = time() - $maxlifetime;

        if (Filesystem::isDirectory($this->location))
        {
            $files = Filesystem::files($this->location);

            foreach($files as $file)
            {
                $fpath = $this->location.DIRECTORY_SEPARATOR.$file;
                $mtime = Filesystem::lastModified($fpath);

                if ($mtime < $ts)
                {
                    Filesystem::delete($fpath);
                }
            }
        }
    }


    /**
    * Set the save location
    *
    */
    public function setLocation(string $location)
    {
        $this->location = $location.((substr($location,-1)=='/') ? '' : '/');
    }

}
