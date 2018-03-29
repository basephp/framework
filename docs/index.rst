BasePHP
====================

A modern minimalist PHP framework.


Request
-------------------------------

The ``Request`` class is used to collect all the incoming requests from the browser and url segments. This class is available through-out the application, and is loaded automatically in Controllers and Middleware.

Methods
~~~~~~~~~~~

Input: ``$_GET`` and ``$_POST``

.. code:: php

    $request->input($name, $default)


Get: ``$_GET``

.. code:: php

    $request->get($name, $default)

Get: ``$_POST``

.. code:: php

    $request->post($name, $default)


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
