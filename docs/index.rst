BasePHP
====================

A modern minimalist PHP framework.


Request
-------------------------------

The ``Request`` class is used to collect all the incoming requests from the browser and url segments. This class is available through-out the application, and is loaded automatically in Controllers and Middleware.

^^^^^^^^^^^^

**input()**

``input($name, $default)``

Input method is used for ``$_GET`` and ``$_POST`` variables.

^^^^^^^^^^^^


.. code-block:: php

    get($name, $default)

Get method is used for ``$_GET`` variable.

^^^^^^^^^^^^


``post($name, $default)``

Post method is used for ``$_POST`` variable.

^^^^^^^^^^^^


``method()``

Returns the method type from ``$_SERVER['REQUEST_METHOD']``

^^^^^^^^^^^^


``isMethod($methodName)``

Returns the ``boolean`` (true/false) if the request method equals.

^^^^^^^^^^^^


``isAjax()``

Returns the ``boolean`` (true/false) if the request is ``xmlhttprequest``

^^^^^^^^^^^^


``isConsole()``

Returns the ``boolean`` (true/false) if the request is ``cli``




Response
-------------------------------

The ``Response`` class is used to change the headers, content type and send the output to the browser.

Example
~~~~~~~~~~~



Routing
-------------------------------
...

Session
-------------------------------
...

Support
-------------------------------
...

View
-------------------------------
...
