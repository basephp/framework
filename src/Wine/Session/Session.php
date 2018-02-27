<?php

namespace Wine\Session;

use \Wine\Session\ProviderInterface;
use \Wine\Support\Collection;


/**
* The Session Class
*
*/
class Session
{

    /*
     * Session provider
     *
     * Must implement /Wine/Session/ProviderInterface
     */
    protected $provider = null;


    /*
     * Set the maxlifetime of session
     *
     * @var int
     */
    protected $expiration = 3600;


    /*
     * Set the save path of where sessions will store themselves.
     *
     * @var string
     */
    protected $savePath = '';


    /*
     * Session Id
     *
     */
    protected $id = null;


    /*
     * Current session data
     *
     * @see \Wine\Support\Collection
     */
    protected $sessionData;


    /*
     * Bootup session class and set the provider
     *
     */
    public function __construct($provider, array $options = [])
    {
        $this->setOptions($options);

        $this->setProvider(new $provider);
        $this->setProviderSaveLocation();
    }


    /*
     * Get current user session id
     *
     */
    public function getId()
    {
        return $this->id;
    }


    /*
     * Get the current session data
     *
     */
    public function get()
    {
        return $this->sessionData;
    }


    /*
     * Start the Session!
     *
     */
    public function start()
    {
        $this->sessionData = new Collection();
        $this->id = md5(uniqid().time());
    }


    /*
     * Save the current state of the session
     *
     */
    public function save()
    {
        $this->provider->set($this->getId(), $this->sessionData->all());
    }


    /*
     * The garbage collector
     *
     */
    public function gc()
    {
        return $this->provider->gc($this->expiration);
    }


    /*
     * Set the session provider
     *
     */
    public function setOptions($options = [])
    {
        $this->expiration = ($options['expiration']) ?? 3600;

        $this->savePath = ($options['save_path']) ?? '';
    }


    /*
     * Set the session provider
     *
     */
    public function setProvider(ProviderInterface $provider)
    {
        $this->provider = $provider;
    }


    /*
     * Set the session provider
     *
     */
    public function setProviderSaveLocation()
    {
        $this->provider->setLocation($this->savePath);
    }


    /*
     * Get the session provider
     *
     */
    public function getProvider()
    {
        return $this->provider;
    }

}
