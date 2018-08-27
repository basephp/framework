<?php

namespace Base\Http\Middleware;

class HttpFrameGuard
{ 

    /**
     * Add response header to protect on iframe/framing
     *
     * @param  Base\Http\Request  $request
     * @param  $next
     * @return  Base\Http\Response  $response
     */
    public function handle($request, $next)
    {
        $response = $next($request);

        $response->setHeader('X-Frame-Options', 'SAMEORIGIN');

        return $response;
    }

}
