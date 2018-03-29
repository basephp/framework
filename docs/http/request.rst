HTTP Requests
====================

The ``\Base\Http\Requests`` class is used for all inputs; including ``$_SERVER``, ``$_GET``, ``$_POST``, ``$_COOKIE``, ``$_FILES``. This class is available through-out the application.


Methods
-------------------------------

.. code:: php

    $request->input($name, $default);
