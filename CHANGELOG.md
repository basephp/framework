# Release Notes


## v1.2.0 (08/27/2018)

### Added
* Added `referrer()` method on `Http\Request` class for getting the `HTTP_REFERER` from the header.
* Added `APP_GLOBALS` env variable, for security of sensitive data, this gives you control to clear globals. By default, globals will not be enabled.
* Added `basephp\database` component into the composer require.

### Changed
* Changed session `Files` provider to use `Filesystem` for all disk/file actions.


## v1.1.4 (08/26/2018)

### Added
* Added `HttpFrameGuard` middleware built-in for adding `X-Frame-Options : SAMEORIGIN` to the response header.

### Changed
* Fixed – `Http\Server` class `__get()` method to check the property exist otherwise return `null` (prevents undefined property notice)


## v1.1.3 (08/26/2018)

### Added
* Added `path()` core function for retrieving real paths in the application. Similar to `storage_path()`, in-fact, it can be a replacement. Example: `path('storage','path/to/somewhere')`

### Changed
* Changed `config()` now accepts an array for easy access to set properties. Example: `config(['item'=>'value'])`.
* Changed `getConfigFiles()` to `getFiles()` since it is being used more so than just config location.
* Changed The use of `/` into `DIRECTORY_SEPARATOR` where needed.
* Removed the `middleware` property from the `Application` instance.
* Fixed – Issue with request type reset to default when nested, they now only look at the last level for `web`, `console` and `ajax` request modes.


## v1.1.2 (08/26/2018)

### Added
* Added `setBaseConsoleMode()` and `getBaseConsoleMode()` on `Application` Instance.


## v1.1.1 (08/26/2018)

### Changed
* Fixed – Middleware failed to run due to array indexing from `1.1.0` release.


## v1.1.0 (08/26/2018)

### Added
* Added `print_d()` common function for printing `print_r` data within `<pre></pre>`.
* Added `getServiceProviderList()` method in the `Application` class.
* Added `getActiveServiceProviderList()` method in the `Application` class.
* Added `getActiveMiddlewareList()` method on `Router` class.
* Added `getRequestTypes()` and `setRequestTypes()` on routes for checking if request is `web`, `console`, and `ajax` is allowed.
* Added `web()`, `console()`, and `ajax()` in `RouterCollection` class. Router matching now allows routes to be grouped into web/console/ajax request types.
* Added `web()`, `console()`, and `ajax()` in `Route` class. As above, this allows specific routes (not groups) to have a request type

### Changed
* Changed dev routes for `_basephp` and `_phpinfo` (formerly `_php`) will be disabled on `production` mode.
* Passing the `Application` instance directly into the `Router` class instead of loading in inconsistent places.
* Removed `addMiddleware()`, `getMiddlewares()` and `getMiddleware()` from `Application` class.
* Fixed – Prevent any middleware from loading multiple times (once per middleware request).
* Fixed – Console request from being forced to always equal the default `/` route.
* Fixed – Prevent `argv` access before the `isConsole()`.


## v1.0.0 (08/15/2018)
* Official Release for version 1
