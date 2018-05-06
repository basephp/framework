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


    public function __construct($app)
    {
        $this->app = $app;
    }


    //--------------------------------------------------------------------


    public function boot()
    {
        // we will boot the child method if exist.
    }

}
