<?php

namespace Wine\Session\Middleware;

use \Wine\Routing\Middleware;
use \Wine\Session\Session;

/**
 * Starts the session handlers
 *
 */
class StartSession extends Middleware
{

    /**
     * handle our sessions
     */
    public $session = null;


    /**
     * session id
     */
    public $sessionId = null;


    /**
     * handle our request
     */
    public function request()
    {
        if ($provider = config('session.provider'))
        {
            $this->session = new Session($provider, [
                'expiration' => config('session.expiration'),
                'save_path' => config('session.save_path')
            ]);

            // get the session id (if exist)
            $this->sessionId = $this->request->cookie(config('session.cookie')) ?? null;

            // start the session with the sessionId
            $this->session->start($this->sessionId);

            // set the session up on the request
            $this->request->session = $this->session->get();
        }
    }


    /**
     * handle our response
     */
    public function response()
    {
        if (!is_null($this->session))
        {
            // save the users cookie to the response
            $this->response->setCookie([
                'name' => config('session.cookie'),
                'value' => $this->session->getId(),
                'expire' => config('session.expiration')
            ]);

            // save any changes to the session
            $this->session->save();

            // run the garbage collection
            // but will only run on specific amount of users
            $this->session->gc();
        }
    }

}
