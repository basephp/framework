<?php

namespace Base\Http;

/**
* The URL Class
*
*/
class Url
{

    /**
    * Allowed characters used in paths and query strings
    *
    * @const string
    */
    const CHAR_ALLOWED = 'a-zA-Z0-9_\-\.';


    /**
    * Root URL
    *
    */
    protected $rootUrl;


    /**
    * Current Host
    *
    */
    protected $host;


    /**
    * URL Path
    *
    */
    protected $path;


    /**
    * URL Query String
    *
    */
    protected $query;


    /**
    * HTTP Secure
    *
    */
    protected $secure = 'http';


    /**
    * Set the current host
    *
    */
    public function setHost($host)
    {
        $this->host = $host;
    }


    /**
    * Set HTTP Secure Mode
    *
    */
    public function setSecure($https = false)
    {
        $this->secure = (($https) ? 'https' : 'http');
    }


    /**
    * Set the URI string,
    * Then set up the rest of the parts
    *
    */
    public function setUri($uri = null)
    {
        // set the actual URL without query params
        $this->rootUrl = $this->secure.'://'.$this->host;

        if ($uri)
        {
            $parts = parse_url($uri);

            if ($parts)
            {
                $this->setParts($parts);
            }
        }
    }


    /**
    * Set the Path, Query and RootURL
    *
    */
    public function setParts(array $parts)
    {
        // set the page path
        $this->path = $this->filterPath($parts['path'] ?? '');

        // set the query string
        $this->query = $parts['query'] ?? '';
    }


    /**
    * Encodes any dangerous characters, and removes dot segments.
    * While dot segments have valid uses according to the spec,
    * this URI class does not allow them.
    *
    * @param $path
    *
    * @return mixed|string
    */
    protected function filterPath(string $path = '')
    {
        $orig = $path;

        // Decode/normalize percent-encoded chars so
        // we can always have matching for Routes, etc.
        $path = urldecode($path);

        // Fix up some leading slash edge cases...
        if (strpos($orig, './') === 0)
        {
            $path = '/' . $path;
        }

        if (strpos($orig, '../') === 0)
        {
            $path = '/' . $path;
        }

        $path = preg_replace_callback(
            '/(?:[^' . self::CHAR_ALLOWED . ':@&=\+\$,\/;%]+|%(?![A-Fa-f0-9]{2}))/', function(array $matches) {
                return rawurlencode($matches[0]);
            }, $path
        );

        return $path;
    }


    /**
    * Get only the Root URL
    *
    *
    */
    public function getRootUrl()
    {
        return $this->rootUrl;
    }


    /**
    * Get the full URL
    *
    *
    */
    public function getUrl()
    {
        return $this->rootUrl.$this->path;
    }


    /**
    * Get HTTP or HTTPS
    *
    *
    */
    public function getSecure()
    {
        return $this->secure;
    }


    /**
    * Get the URL Path
    *
    *
    */
    public function getPath()
    {
        return $this->path;
    }


    /**
    * Get the URL Query String
    *
    *
    */
    public function getQuery()
    {
        return $this->query;
    }


    /**
    * Get the HTTP HOST
    *
    *
    */
    public function getHost()
    {
        return $this->host;
    }


    /**
    * If this instance gets converted to a string,
    * Return the FULL URL path
    *
    */
    public function __toString()
    {
        return $this->getUrl();
    }

}
