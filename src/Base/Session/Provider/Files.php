<?php

namespace Base\Session\Provider;

use \Base\Session\ProviderInterface;
use \Base\Support\Facades\Filesystem;


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
    *
    */
    public function set($key = '', array $data = [])
    {
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

        $files = Filesystem::getFiles($this->location);

        foreach($files as $file)
        {
            $mtime = filemtime($this->location.'/'.$file);

            if ($mtime < $ts)
            {
                unlink($this->location.'/'.$file);
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