<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Settings;
use Illuminate\Support\Facades\Cache;

class RateLimit
{
    const RATE_LIMIT_INDEX = 'rate_limit';
    const RATE_LIMIT_TIME = 1;

    public $requestIdentifier;

    public $rateLimitDefault;

    public function __construct(Request $request)
    {
        $this->rateLimitDefault = (int)env('RATE_LIMIT_DEFAULT', 5);
        $this->requestIdentifier = $this->makeRequestIdentifier($request);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if( $this->isExceded() ) {
            return $this->limitExceededError();
        } else {
            if( !$this->exists() ) {
                $this->add();
            } else {
                $this->update();
            }
        }
        
        return $next($request);
    }

    private function add(){
        return Cache::add($this->requestIdentifier, 1, self::RATE_LIMIT_TIME * 60);
    }

    private function update(){
        Cache::increment($this->requestIdentifier);
    }

    /**
     * Check rate limits exists
     * @return Boolean
     */
    private function exists(){
        return Cache::has($this->requestIdentifier);
    }

    /**
     * Get rate limit value
     */
    private function get() {
        return Cache::get($this->requestIdentifier, 0);
    }

    /** 
     * Get rate limit value from settings. default is RATE_LIMIT_DEFAULT
     * @return Integer
    */
    private function getRateLimit() {
        return Settings::query()
        ->settings('rate-limit', 'limit')->first()->value ?? $this->rateLimitDefault;
    }

    /**
     * Check is request limit exceeded
     * @return Boolean
     */
    private function isExceded() {
        return $this->exists() ? (int)$this->get() >= (int)$this->getRateLimit() : false;
    }

    /**
     * Make request identifier
     * @return String
     */
    private function makeRequestIdentifier(Request $request) {
        return sha1($request->ip().'|'.$request->url().'|'.self::RATE_LIMIT_INDEX);
    } 

    private function limitExceededError() {
        return response()->json(['error' => 'Too many requests']);
    }
}
