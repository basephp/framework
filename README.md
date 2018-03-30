# BasePHP

BasePHP is a small framework for minimalist.


Controllers
---------------

**Example Controller**

```php
namespace App\Controllers;

use \Base\Routing\Controller;

class MyController extends Controller
{
	public function index()
	{
        // do something
	}
}
```

You can access `Request` and `Response` classes automatically within controllers that extend `\Base\Routing\Controller` using `$this->request` and `$this->response`.


Request
---------------

`\Base\Http\Request`

The `Request` class is instantiated by default.

### input()

Input uses `$_GET` and `$_POST` variables. When running in command line,
it will look within the `$_SERVER['argv']`

```php
$request->input($name, $default)
```

### get()

Returns the global variable `$_GET`

```php
$request->get($name, $default)
```

### post()

Returns the global variable `$_POST`

```php
$request->post($name, $default)
```

### cookie()

Returns the global variable `$_COOKIE`

```php
$request->cookie($name, $default)
```

### method()

Returns the global variable `$_SERVER['REQUEST_METHOD']`

```php
$request->method()
```

### isMethod()

Checks whether the request method is `GET` or `POST`.

```php
$request->isMethod($name)
```

### isAjax()

Checks whether the request is coming from `xmlhttprequest`

```php
$request->isAjax()
```

### isConsole()

Checks whether the request is coming from command line `cli`

```php
$request->isConsole()
```

Returns the user's valid IP Address.

```php
$request->ipAddress()
```

Returns the user's user agent from `$_SERVER['HTTP_USER_AGENT']`

```php
$request->userAgent()
```


Response
---------------

`\Base\Http\Response`

The `Response` class is instantiated by default.

### setCookie()

```php
$response->setCookie($options)
```

### setContentType()

```php
$response->setContentType($mime, $charset)
```

Filesystem
---------------

`\Base\Facades\Filesystem`

The Filesystem class is static and can be accessed anywhere.

### exists()

```php
Filesystem::exists($name)
```

### delete()

```php
Filesystem::delete($path)
```

### get()

```php
Filesystem::get($path)
```

### put()

```php
Filesystem::put($path)
```

### copy()

```php
Filesystem::copy($path)
```

### move()

```php
Filesystem::move($path)
```

### name()

```php
Filesystem::name($path)
```

### getFiles()

```php
Filesystem::getFiles($path, $extension)
```

### size()

```php
Filesystem::size($file)
```

### isDirectory()

```php
Filesystem::isDirectory($path)
```

### isReadable()

```php
Filesystem::isReadable($path)
```

### isWritable()

```php
Filesystem::isWritable($path)
```

### isFile()

```php
Filesystem::isFile($path)
```

### glob()

```php
Filesystem::glob($path)
```

### lastModified()

```php
Filesystem::lastModified($path)
```
