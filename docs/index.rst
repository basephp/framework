BasePHP
====================

A modern minimalist PHP framework.


Request
-------------------------------

The ``Request`` class is used to collect all the incoming requests from the browser and url segments. This class is available through-out the application, and is loaded automatically in Controllers and Middleware.

^^^^^^^^^^^^

.. code:: php
    input($name, $default)

^^^^^^^^^^^^


**get($name, $default)**

^^^^^^^^^^^^


**post($name, $default)**

^^^^^^^^^^^^


**method()**

^^^^^^^^^^^^


**isMethod($methodName)**

^^^^^^^^^^^^


**isAjax()**

^^^^^^^^^^^^


**isConsole()**




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
