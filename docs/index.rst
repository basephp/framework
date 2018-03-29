BasePHP
====================

A modern minimalist PHP framework.


Request
-------------------------------

The ``Request`` class is used to collect all the incoming requests from the browser and url segments. This class is available through-out the application, and is loaded automatically in Controllers and Middleware.

.. note:: The following variables are parsed ``$_SERVER``, ``$_GET``, ``$_POST``, ``$_COOKIE``, ``$_FILES``

Method Reference
------------

These methods are available:

+------------------------+------------+----------+----------+
| Method | Description |
+========================+============+==========+==========+
| input()      | ``$_GET`` and ``$_POST`` |
| get()        | ``$_GET`` |
| post()       | ``$_POST`` |
| cookie()     | ``$_COOKIE`` |
| method()     | ``$_SERVER['REQUEST_METHOD']`` |
| isMethod()   | Check whether the request is a specific method. |
| isAjax()     | Check whether the request is coming from ``xmlhttprequest`` |
| isConsole()  | Check whether the request is ``cli`` |
+------------------------+-----------------------+----------+


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
