<?php

namespace Base\View;


/**
* This is the ViewContent class
*
* @see /Base/View/View
*/
class ViewContent
{

    /**
    * Content from this view
    *
    * @var array
    */
    protected $content = '';


    /**
    * Instantiate the view class and set it's contents
    *
    * @return $this
    */
    public function __construct(string $content = '')
    {
        $this->content = $content;

        return $this;
    }


    /**
    * Minify the view content
    *
    * @return string
    */
    public function minify($whitespaceOnly = false)
    {
        $search = [
            // strip whitespaces after tags, except space
            '/\>[^\S ]+/s',
            // strip whitespaces before tags, except space
            '/[^\S ]+\</s',
            // shorten multiple whitespace sequences
            '/(\s)+/s',
        ];

        // add this for protection aganst accidental content removal.
        if ($whitespaceOnly == false)
        {
            // Remove HTML comments
            $search[] = '/<!--(.|\s)*?-->/';

            // Remove any additional white space.
            $search[] = '/\\s+/';
        }

        $this->replace($search, ['>','<','\\1','',' ']);

        return $this;
    }


    /**
    * Find and replace within a single view.
    *
    * @param string $find
    * @param string $replace
    * @return string
    */
    public function replace($find, $replace = '')
    {
        $this->content = preg_replace($find, $replace, $this->content);

        return $this;
    }


    /**
    * Get the string length of this view
    *
    * @return string
    */
    public function length()
    {
        return strlen($this->content);
    }


    /**
    * Return the object as a string (for output)
    *
    * @return string
    */
    public function __tostring()
    {
        return $this->content ?? '';
    }

}
