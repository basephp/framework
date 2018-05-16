<?php

namespace Base\Http;

use \Base\View\View;

/**
* \Base\Http\Response
*
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
    * Store the current view instance
    *
    * @var \Base\View\View
    */
    protected $view = null;


    /**
    * HTTP status codes
    *
    * @var array
    */
    protected $statusCodes = [
        // 1xx: Informational
        100	 => 'Continue',
        101	 => 'Switching Protocols',
        102	 => 'Processing',
        103  => 'Early Hints',
        // 2xx: Success
        200	 => 'OK',
        201	 => 'Created',
        202	 => 'Accepted',
        203	 => 'Non-Authoritative Information',
        204	 => 'No Content',
        205	 => 'Reset Content',
        206	 => 'Partial Content',
        207	 => 'Multi-Status',
        208	 => 'Already Reported',
        226	 => 'IM Used',
        // 3xx: Redirection
        300	 => 'Multiple Choices',
        301	 => 'Moved Permanently',
        302	 => 'Found',
        303	 => 'See Other',
        304	 => 'Not Modified',
        305	 => 'Use Proxy',
        306	 => 'Switch Proxy',
        307	 => 'Temporary Redirect',
        308	 => 'Permanent Redirect',
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
        418	 => "I'm a teapot",
        // 419 (Authentication Timeout) is a non-standard status code with unknown origin
        421	 => 'Misdirected Request',
        422	 => 'Unprocessable Entity',
        423	 => 'Locked',
        424	 => 'Failed Dependency',
        426	 => 'Upgrade Required',
        428	 => 'Precondition Required',
        429	 => 'Too Many Requests',
        431	 => 'Request Header Fields Too Large',
        451	 => 'Unavailable For Legal Reasons',
        499  => 'Client Closed Request',
        // 5xx: Server error
        500	 => 'Internal Server Error',
        501	 => 'Not Implemented',
        502	 => 'Bad Gateway',
        503	 => 'Service Unavailable',
        504	 => 'Gateway Timeout',
        505	 => 'HTTP Version Not Supported',
        506	 => 'Variant Also Negotiates',
        507	 => 'Insufficient Storage',
        508	 => 'Loop Detected',
        510	 => 'Not Extended',
        511	 => 'Network Authentication Required',
        599  => 'Network Connect Timeout Error',
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
        // check if we have already sent headers
        // ignore if we have...
        if (headers_sent()) return false;

        // setting a basephp header.
        $this->setHeader('X-Framework', 'BasePHP/'.app()->version().', basephp.org');

        // send the HTTP Status header
        header(sprintf('HTTP/1.1 %s %s', $this->statusCode, $this->reason), true, $this->statusCode);

        // send all the additional headers
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
        // if the body content is an array,
        // then we should apply the JSON header automatically.
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
    * Create the View instance (or return an existing instance)
    *
    * @return Base\View\View
    */
    public function view()
    {
        if (!$this->view)
        {
            return $this->view = new View();
        }

        return $this->view;
    }

}
