# BasePHP

BasePHP is a small framework for minimalist.


Routing
---------------

`Base\Routing\Router`

You can configure your routing within `routes/web.php` by default.

### add()

You can add new routes by using the `add()` method.

```php
// basic routing
// The App\Controllers\Page::index() is invoked.
Route::add('/','Page');

// Setting a specific method on controller
Route::add('/','Page::myMethod');

// Running no method, closure function
Route::add('/',function(){
    // do something
});
```


### group()

You can group specific routes together, which will be useful when using Middleware.

```php
Route::group('/admin',function(){
	Route::add('/edit/{id}','Page');
});
```


Controllers
---------------

`App\Controllers`

Controllers are the base of your application. Controllers are instantiated when
the proper router is matched.

**Example Controller**

```php
class MyController extends \Base\Routing\Controller
{
	public function index()
	{
        // do something
	}
}
```

You can access `Request` and `Response` classes automatically within controllers that extend `\Base\Routing\Controller` using `$this->request` and `$this->response`.

For example, if you want to retrieve the `$_GET` query variable:

```php
$name = $this->request->input('name','My Default Name');
```

See [REQUEST]


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

### ipAddress()

Returns the user's valid IP Address.

```php
$request->ipAddress()
```

### userAgent()

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
