<?php

namespace Wine\View;


/**
* This is the View class
*
*/
class View
{

	/**
	* Data on single view
	*
	* @var array
	*/
	protected $data = [];


	/**
	* Data on shared views
	*
	* @var array
	*/
	protected $sharedData = [];


	/**
	* Share the data with all views?
	*
	* @var bool
	*/
	protected $shared = true;


	/**
	* Path where view files are stored
	*
	* @var string
	*/
	protected $viewPath = '';


	/**
	* Instantiate the view class
	*
	*/
	public function __construct()
	{
		$this->viewPath = config('path.views');
	}


	/**
	* Build the view files and create its content for output
	*
	* @param string  $view
	* @return string $output
	*/
	public function render(string $view)
	{
		$output = $this->load($this->viewPath . '/' . str_replace('.php','',$view) . '.php');

		return $output;
	}


	/**
	* Sets the view data (one global array)
	*
	* @param array $data
	* @return $this
	*/
	public function setData(array $data = [], $shared = true)
	{
		$this->data = $data;
		$this->shared = $shared;

		if ($shared === true) {
			$this->sharedData = array_merge($this->sharedData, $data);
		}

		return $this;
	}


	/**
	* Load the view file
	*
	* @param string $path
	* @return string
	*/
	public function load(string $__path)
	{
		$obLevel = ob_get_level();

		ob_start();

		extract( (($this->shared === true) ? $this->sharedData : $this->data) , EXTR_SKIP);

		try {
			include($__path);
		}
		catch (Exception $e) {
			$this->handleViewException($e, $obLevel);
		}

		return ltrim(ob_get_clean());
	}


	/**
	* Handle a view exception.
	*
	* @param  \Exception  $e
	* @param  int  $obLevel
	* @return void
	*
	* @throws \Exception
	*/
	protected function handleViewException(Exception $e, $obLevel)
	{
		while (ob_get_level() > $obLevel) {
			ob_end_clean();
		}

		throw $e;
	}


	/**
	* Return current instance of self.
	*
	*/
	public function self()
	{
		return $this;
	}

}
