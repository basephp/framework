<?php namespace Base\Support;

class ServiceProvider
{

    /**
     * The application instance.
     *
     * @var \Base\Application
     */
    protected $app;


    //--------------------------------------------------------------------


    public function __construct($app)
    {
        $this->app = $app;
    }


    //--------------------------------------------------------------------


    public function boot()
    {
        // we will boot the child class if exist.
    }

}
