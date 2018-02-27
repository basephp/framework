<?php

namespace Wine\Http;

use \Wine\Support\Facades\View;

/**
* The Response Class
*
*/
class Response
{

	/**
	* Headers
	*
	* @var array
	*/
	protected $headers = [];


	/**
	* Content Type
	*
	* @var string
	*/
	protected $contentType = 'text/html';


	/**
	* Body (used for storing proper output)
	*
	* @var mixed
	*/
	protected $body = '';


	/**
	* output (used for storing unintended output)
	*
	* @var string
	*/
	protected $output = '';


	/**
	* Get the content type for this response
	*
	* @return string
	*/
	public function getContentType()
	{
		return $this->contentType;
	}


    /**
    * Set the cookie for our response
    *
    */
    public function setCookie($options)
    {
        /*$expire = ($options['expire']) ?? 0;

        if ( ! is_numeric($expire))
		{
			$expire = time() - 86500;
		}
		else
		{
			$expire = ($expire > 0) ? time() + $expire : 0;
		}

        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);*/
    }


	/**
	* Sets a header for the response
	*
	* @param string $name
	* @param string $value
	*
	* @return $this
	*/
	public function setHeader(string $name, $value)
	{
		$this->headers[$name] = $value;

		return $this;
	}


	/**
	* Adding to the body (without replacing existing)
	*
	* @param  mixed $body
	* @return $this
	*/
	public function setOutput($output = '')
	{
		$this->output = $output;

		return $this;
	}


	/**
	* Set the response body (replacing any existing body)
	*
	* @param  mixed $body
	* @return $this
	*/
	public function setBody($body)
	{
		if (is_array($body))
		{
			$this->setContentType('application/json');

			$this->body = json_encode($body, 1);
		}
		else
		{
			$this->body = $body;
		}

		return $this;
	}


	/**
	* Send the body to the browser
	*
	*/
	public function send()
	{
        // ob_start();

		if ($this->output != '')
		{
			$this->setContentType('text/html');
		}

        $this->sendHeaders();

		if ($this->output != '') echo $this->output;

		echo $this->body;

        // $size = ob_get_length();

        // Set the content length of the response.
        // header("Content-Length: $size");

        // Close the connection.
        // header("Connection: close");

        // ob_end_flush();
        // ob_flush();
        // flush();
	}


	/**
	* Sets the Content Type header for this response with the mime type
	* and, optionally, the charset.
	*
	* @param string $mime
	* @param string $charset
	*
	*/
	public function setContentType(string $mime, string $charset = 'UTF-8')
	{
		$this->contentType = $mime;

		if ((strpos($mime, 'charset=') < 1) && ! empty($charset))
		{
			$mime .= '; charset=' . $charset;
		}

		$this->setHeader('Content-Type', $mime);

		return $this;
	}


	/**
	* Get the response headers that have been set
	*
	*
	* @return array
	*/
	public function getHeaders()
	{
		return $this->headers;
	}


	/**
	* Get the response body
	*
	* @return mixed
	*/
	public function getBody()
	{
		return $this->body;
	}


	/**
	* Send the headers
	*
	*/
	protected function sendHeaders()
	{
		if (headers_sent()) return false;

		// this will need work (just a quick fix for now...)
		header(sprintf('HTTP/1.1 %s OK', 200, ''), true, 200);

		foreach ($this->getHeaders() as $name => $values)
		{
			header($name.': '.$values);
		}
	}


	/**
	* ..
	*
	*/
	public function view()
	{
		return View::self();
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
