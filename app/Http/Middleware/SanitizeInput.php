<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Arr;

class SanitizeInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // $input = $request->all();
    
        // array_walk_recursive($input, function (&$value, $key) {
        //     // Exclude certain keys from sanitization
        //     $excludedKeys = ['css_code', 'another_css_key'];
        //     if (in_array($key, $excludedKeys)) {
        //         return;
        //     }
    
        //     // Check if the input is an empty string
        //     if ($value == '') {
        //         $value = null;
        //     } else {
        //         // Apply existing sanitization
        //         $value = trim(strip_tags($value));
        //     }
        // });
    
        // $request->merge($input);
    
        return $next($request);
    }
    
}
