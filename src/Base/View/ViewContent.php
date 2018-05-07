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
    public function minify()
    {
        return $this->replace('\\s+', ' ');
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
    * Find and replace within a single view.
    *
    * @param string $find
    * @param string $replace
    * @return string
    */
    public function replace($find = '', $replace = '')
    {
        return preg_replace('/'.$find.'/', $replace, $this->content);
    }


    /**
    * Return the object as a string (for output)
    *
    * @return string
    */
    public function __tostring()
    {
        return $this->content;
    }

}
