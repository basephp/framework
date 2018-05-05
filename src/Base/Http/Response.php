<?php

namespace Base\Http;

use \Base\Support\Facades\View;

/**
* \Base\Http\Response
*
*
* Public Methods:
* ------------------------------------------
*   $response->getContentType()
*   $response->getBody()
*   $response->getOutput()
*   $response->getHeaders()
*   $response->getStatusCode()
*   $response->getStatusReason()
*
*   $response->setContentType(mime, charset)
*   $response->setStatusCode(number, reason)
*   $response->setBody(content)
*   $response->setOutput(content)
*   $response->setHeader(name, value)
*   $response->setCookie(options)
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
    * Default status code
    *
    * @var int
    */
    protected $statusCode = 200;


    /**
	 * The current reason phrase for this response.
	 * If null, will use the default provided for the status code.
	 *
	 * @var string
	 */
	protected $reason;


    /**
	 * HTTP status codes
	 *
	 * @var array
	 */
	protected $statusCodes = [
		// 1xx: Informational
		100	 => 'Continue',
		101	 => 'Switching Protocols',
        102	 => 'Processing', // http://www.iana.org/go/rfc2518
        103  => 'Early Hints', // http://www.ietf.org/rfc/rfc8297.txt
		// 2xx: Success
		200	 => 'OK',
		201	 => 'Created',
		202	 => 'Accepted',
		203	 => 'Non-Authoritative Information', // 1.1
		204	 => 'No Content',
		205	 => 'Reset Content',
		206	 => 'Partial Content',
		207	 => 'Multi-Status', // http://www.iana.org/go/rfc4918
		208	 => 'Already Reported', // http://www.iana.org/go/rfc5842
		226	 => 'IM Used', // 1.1; http://www.ietf.org/rfc/rfc3229.txt
		// 3xx: Redirection
		300	 => 'Multiple Choices',
		301	 => 'Moved Permanently',
		302	 => 'Found', // Formerly 'Moved Temporarily'
		303	 => 'See Other', // 1.1
		304	 => 'Not Modified',
		305	 => 'Use Proxy', // 1.1
		306	 => 'Switch Proxy', // No longer used
		307	 => 'Temporary Redirect', // 1.1
		308	 => 'Permanent Redirect', // 1.1; Experimental; http://www.ietf.org/rfc/rfc7238.txt
		// 4xx: Client error
		400	 => 'Bad Request',
		401	 => 'Unauthorized',
		402	 => 'Payment Required',
		403	 => 'Forbidden',
		404	 => 'Not Found',
		405	 => 'Method Not Allowed',
		406	 => 'Not Acceptable',
		407	 => 'Proxy Authentication Required',
		408	 => 'Request Timeout',
		409	 => 'Conflict',
		410	 => 'Gone',
		411	 => 'Length Required',
		412	 => 'Precondition Failed',
		413	 => 'Request Entity Too Large',
		414	 => 'Request-URI Too Long',
		415	 => 'Unsupported Media Type',
		416	 => 'Requested Range Not Satisfiable',
		417	 => 'Expectation Failed',
		418	 => "I'm a teapot", // April's Fools joke; http://www.ietf.org/rfc/rfc2324.txt
		// 419 (Authentication Timeout) is a non-standard status code with unknown origin
		421	 => 'Misdirected Request', // http://www.iana.org/go/rfc7540 Section 9.1.2
		422	 => 'Unprocessable Entity', // http://www.iana.org/go/rfc4918
		423	 => 'Locked', // http://www.iana.org/go/rfc4918
		424	 => 'Failed Dependency', // http://www.iana.org/go/rfc4918
		426	 => 'Upgrade Required',
		428	 => 'Precondition Required', // 1.1; http://www.ietf.org/rfc/rfc6585.txt
		429	 => 'Too Many Requests', // 1.1; http://www.ietf.org/rfc/rfc6585.txt
		431	 => 'Request Header Fields Too Large', // 1.1; http://www.ietf.org/rfc/rfc6585.txt
        451	 => 'Unavailable For Legal Reasons', // http://tools.ietf.org/html/rfc7725
        499  => 'Client Closed Request', // http://lxr.nginx.org/source/src/http/ngx_http_request.h#0133
		// 5xx: Server error
		500	 => 'Internal Server Error',
		501	 => 'Not Implemented',
		502	 => 'Bad Gateway',
		503	 => 'Service Unavailable',
		504	 => 'Gateway Timeout',
		505	 => 'HTTP Version Not Supported',
		506	 => 'Variant Also Negotiates', // 1.1; http://www.ietf.org/rfc/rfc2295.txt
		507	 => 'Insufficient Storage', // http://www.iana.org/go/rfc4918
		508	 => 'Loop Detected', // http://www.iana.org/go/rfc5842
		510	 => 'Not Extended', // http://www.ietf.org/rfc/rfc2774.txt
		511	 => 'Network Authentication Required', // http://www.ietf.org/rfc/rfc6585.txt
        599  => 'Network Connect Timeout Error', // https://httpstatuses.com/599
	];


    /**
    * Set the cookie for our response
    *
    */
    public function setCookie($options)
    {
        $expire = ($options['expire']) ?? 0;
        $value  = ($options['value']) ?? '';
        $name   = ($options['name']) ?? 'unnamed';

        if ( ! is_numeric($expire))
        {
            $expire = time() - 86500;
        }
        else
        {
            $expire = ($expire > 0) ? time() + $expire : 0;
        }

        $secure = false;
        $httponly = false;
        $path = '/';
        $domain = '';

        setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);

        return $this;
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
        $this->body = $body;

        return $this;
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
    * Set the status code
    *
    */
    public function setStatusCode(int $code, string $reason = '')
	{
		$this->statusCode = $code;

		if ( ! empty($reason))
		{
			$this->reason = $reason;
		}
		else
		{
			$this->reason = ($statusCodes[$code]) ?? '';
		}

		return $this;
	}


    /**
    * Get the http status code
    *
    * @return int
    */
    public function getStatusCode()
    {
        return $this->statusCode;
    }


    /**
    * Get http status code reason
    *
    * @return string
    */
    public function getStatusReason()
    {
        return $this->reason;
    }


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
    * get the putput
    *
    * @return string $output
    */
    public function getOutput()
    {
        return $this->output;
    }


    /**
    * Send the headers
    *
    */
    protected function sendHeaders()
    {
        if (headers_sent()) return false;

        // HTTP Status
		header(sprintf('HTTP/1.1 %s %s', $this->statusCode, $this->reason), true, $this->statusCode);

        foreach ($this->getHeaders() as $name => $values)
        {
            header($name.': '.$values);
        }
    }


    /**
    * Send the body to the browser
    *
    */
    public function send()
    {
        if (is_array($this->body))
        {
            $this->setContentType('application/json');

            $this->body = safe_json_encode($this->body,1);
        }

        if ($this->output != '')
        {
            $this->setContentType('text/html');
        }

        $this->setHeader('Content-Length', strlen($this->output.$this->body));

        $this->sendHeaders();

        if ($this->output != '') echo $this->output;

        if ($this->body != '') echo $this->body;
    }


    /**
    * Redirect the browser offsite or locally.
    * Set the HTTP status code automatically.
    */
    public function redirect($uri, $code = 302)
    {
        if ($uri != '')
        {
            $this->setStatusCode($code);

            // are we redirecting to another domain?
            if (preg_match('#https?://#i',$uri))
            {
                header('Location: '.$uri);
                exit;
            }

            // redirecting locally.
            header('Location: '.app()->request->url->getRootUrl().((substr($uri,0,1)==='/') ? '' : '/').$uri);
            exit;
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
