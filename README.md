# BasePHP

BasePHP is a small framework for minimalist.

Request
---------------

### input()

Input uses `$_GET` and `$_POST` variables. When running in command line,
it will look within the `$_SERVER['argv']` array.

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
$request->method($name)
```

### isMethod()

```php
$request->isMethod()
```

### isAjax()

```php
$request->isAjax()
```

### isConsole()

```php
$request->isConsole()
```

Response
---------------

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

The Filesystem class is static accessible by using `\Base\Facades\Filesystem`.

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
