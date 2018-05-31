<?php namespace Base;

use Exception;
use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use Base\Routing\Router;
use Base\Http\Request;
use Base\Http\Response;
use Base\Support\Collection;
use Base\Support\Filesystem;


/**
* The Application Class
*
*/
class Application
{
    /**
    * The Version of BasePHP
    *
    */
    const VERSION = '1.0.0-beta';


    /**
    * The current application instance
    *
    * @var static
    */
    protected static $instance;


    /**
    * The application services instances
    *
    * @var array
    */
    protected $instances = [];


    /**
    * The application middleware instances
    *
    * @var array
    */
    protected $middleware = [];


    /**
    * Core Service Poviders to be loaded.
    *
    * @var array
    */
    protected $providers = [
        \Base\Routing\Providers\RouterServiceProvider::class,
        \Base\Http\Providers\HttpServiceProvider::class
    ];


    /**
    * Loaded service probiders
    *
    * @var array
    */
    protected $activeProviders = [];


    /**
    * Instantiate the Application
    *
    * @see public/index.php
    */
    public function __construct( $rootPath = '' )
    {
        $rootPath = rtrim($rootPath, '\/');

        $this->register('config', $config = new Collection([
            'path' => [
            	'root' => $rootPath,
            	'app' => $rootPath.'/app',
            	'config' => $rootPath.'/config',
            	'views' => $rootPath.'/views',
                'storage' => $rootPath.'/storage',
            	'routes' => $rootPath.'/routes'
            ]
        ]));
    }


    /**
    * Get the version of the application.
    *
    * @return string
    */
    public function version()
    {
        return static::VERSION;
    }


    /**
    * Begin our application
    *
    * @see public/index.php
    */
    public function initialize()
    {
        self::setInstance($this);

        $this->setDotEnv();
        $this->setConfigurations();
        $this->setAppSettings();

        // build the service provider list
        $this->buildServiceProviders();

        // register and boot the service providers
        $this->registerServiceProviders();
        $this->bootServiceProviders();

        // create the storage directory "storage/framework"
        $this->storageDirectory();

        // run our application
        $this->run();
    }


    /**
    * Check if storage/framework exists.
    *
    */
    protected function storageDirectory()
    {
        if (Filesystem::isWritable(storage_path()))
        {
            $framework = storage_path('framework');

            if (!Filesystem::isDirectory($framework))
            {
                Filesystem::makeDirectory($framework, 0775, true);
            }
        }
        else
        {
            throw new Exception('Storage Path: '.storage_path().' is not writable.');
        }
    }


    /**
    * Run the application
    *
    */
    protected function run()
    {
        if ($this->request->isConsole())
        {
            // keep the script running even when console goes away.
            ignore_user_abort(true);
        }

        $this->router->match( $this->request );

        // do all the magic...
        $this->router->run( $this );

        // let's make a few modifications before we send to the browser
        if ($body = $this->response->getBody())
        {
            $currentUsage = memory_get_usage();

            $time = (float) number_format(microtime(true) - APP_START, 4);
            $memory = format_bytes($currentUsage,3);

            $body = str_replace('{APP_TIME}', $time, $body);
            $body = str_replace('{APP_MEMORY}', $memory, $body);

            $this->response->setBody($body);
        }

        // send the response to the browser
        $this->response->send();
    }


    /**
    * Set the DotEnv settings (variables from ".env")
    *
    */
    protected function setDotEnv()
    {
        try
        {
            (new Dotenv($this->config->get('path.root'), '/.env'))->load();
        }
        catch (InvalidPathException $e)
        {

        }
    }


    /**
    * Set the application paths and load up configuration files
    *
    */
    protected function setConfigurations()
    {
        if ($files = $this->getConfigFiles('path.config'))
        {
            foreach ($files as $key => $filename)
            {
                $this->config->set(basename($filename, '.php'), require $this->config->get('path.config').'/'.($filename));
            }
        }
    }


    /**
    * Load Service Providers from the configs
    *
    */
    protected function buildServiceProviders()
    {
        foreach($this->config as $configName)
        {
            if (isset($configName['providers']) && is_array($configName['providers']))
            {
                $this->providers = array_merge($this->providers, $configName['providers']);
            }
        }
    }


    /**
    * "register" and Load Service Providers.
    * Save all the "active" providers within $activeProviders
    */
    protected function registerServiceProviders()
    {
        foreach($this->providers as $provider)
        {
            // check if the provider class exists
            if (class_exists($provider))
            {
                // register this service provider, and Instantiate it.
                $service = new $provider($this);

                // check if the service provider has a boot method.
                if (method_exists($service, 'register')) {
                    $service->register();
                }

                // save the active provider so that we can call it later.
                $this->activeProviders[] = $service;
            }
        }
    }


    /**
    * "boot" the Service Poviders
    * After we have registered all the providers,
    * We boot them up with any additional logic
    */
    protected function bootServiceProviders()
    {
        foreach($this->activeProviders as $provider)
        {
            // check if the service provider has a boot method.
            if (method_exists($provider, 'boot')) {
                $provider->boot();
            }
        }
    }


    /**
    * get all the configuration files located in the app/config
    *
    * @return array
    */
    public function getConfigFiles($path)
    {
        return Filesystem::getAll($this->config->get($path), 'php');
    }


    /**
    * Set the app settines for internal php configurations
    *
    */
    protected function setAppSettings()
    {
        // set the application time zone
        date_default_timezone_set($this->config->get('app.timezone','UTC'));

        // set the application character encoding
        mb_internal_encoding($this->config->get('app.encoding','UTF-8'));
    }


    /**
    * Register an instance to share within this application
    *
    * @param  string  $name
    * @param  mixed   $instance
    * @return mixed
    */
    public function register($name, $instance)
    {
        return $this->instances[$name] = $instance;
    }


    /**
    * Register new middleware into the application
    *
    * @param  string  $name
    * @param  mixed   $instance
    * @return mixed
    */
    public function addMiddleware($name, $instance)
    {
        return $this->middleware[$name] = $instance;
    }


    /**
    * get middleware from the application
    *
    * @param  string  $name
    * @param  mixed   $instance
    * @return mixed
    */
    public function getMiddlewares()
    {
        return $this->middleware;
    }


    /**
    * get middleware from the application
    *
    * @param  string  $name
    * @param  mixed   $instance
    * @return mixed
    */
    public function getMiddleware($name)
    {
        return ($this->middleware[$name]) ?? null;
    }


    /**
    * Get the current instance
    *
    * @return static
    */
    public static function getInstance()
    {
        return static::$instance;
    }


    /**
    * Set the application instance
    *
    * @return static
    */
    public static function setInstance($app)
    {
        return static::$instance = $app;
    }


    /**
    * Get an instance
    *
    * @param  string  $key
    * @return mixed
    */
    public function __get($key)
    {
        return $this->instances[$key] ?? null;
    }

}
