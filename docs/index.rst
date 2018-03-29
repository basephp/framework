BasePHP
====================

A modern minimalist PHP framework.


Request
-------------------------------

The ``Request`` class is used to collect all the incoming requests from the browser and url segments. This class is available through-out the application, and is loaded automatically in Controllers and Middleware.

.. note:: The following variables are parsed ``$_SERVER``, ``$_GET``, ``$_POST``, ``$_COOKIE``, ``$_FILES``

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


Method Reference
------------

input()    ``$_GET`` and ``$_POST``
get()      ``$_GET``
post()     ``$_POST``



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
