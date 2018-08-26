# Release Notes


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
