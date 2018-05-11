<?php namespace Base;

class ServiceProvider
{

    /**
     * The application instance.
     *
     * @var \Base\Application
     */
    protected $app;


    //--------------------------------------------------------------------


    /**
    * Inject the Application Instance
    *
    */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

}
