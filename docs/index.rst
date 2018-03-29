BasePHP
====================

A modern minimalist PHP framework.


Request
-------------------------------

The ``Request`` class is used to collect all the incoming requests from the browser and url segments. This class is available through-out the application, and is loaded automatically in Controllers and Middleware.

.. note:: The following variables are parsed ``$_SERVER``, ``$_GET``, ``$_POST``, ``$_COOKIE``, ``$_FILES``

Method Reference
------------

+------------------------------+
| Method                       |
+==============================+
| **input($name, $default)**   |
+------------------------------+
| **get($name, $default)**     |
+------------------------------+
| **get($name, $default)**     |
+------------------------------+
| **get($name, $default)**     |
+------------------------------+
| **method()**                 |
+------------------------------+
| **isMethod($name)**          |
+------------------------------+
| **isAjax()**                 |
+------------------------------+
| **isConsole()**              |
+------------------------------+


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
