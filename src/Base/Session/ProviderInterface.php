<?php

namespace Base\Session;


/**
* The Session Provider Interface
*
*/
Interface ProviderInterface
{

    /**
    * Destroys the session.
    *
    */
    public function destroy(string $key);


    /**
    * Set the session
    *
    */
    public function set($key = null, array $data = []);


    /**
    * Get the user session
    *
    */
    public function get(string $key = null);


    /**
    * Garbage Collector - clears all old sessions
    *
    */
    public function gc(int $maxlifetime);


    /**
    * Sets the location
    *
    */
    public function setLocation(string $location);

}
