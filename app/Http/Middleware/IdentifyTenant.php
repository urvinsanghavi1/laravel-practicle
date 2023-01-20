<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\TenantManager;

class IdentifyTenant
{
    protected $tenantManager;
    

    public function __construct()
    {
        $this->tenantManager = app(TenantManager::class);
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
        \View::share('subdomain', \Route::current()->parameter('subdomain'));
        
        $request->route()->forgetParameter('subdomain');

        // We don't need to identify tenant while running tests
        if(app()->environment() === 'testing') return $next($request);

        $tenantManager = $this->tenantManager->loadTenant($request->getHost());
        // dd($tenantManager);
        if(!$tenantManager->getTenant()) {
            logger()->error('IdentifyTenant: Tenant not found.', [
                'host' => $request->getHost()
            ]);
            
            abort(404, 'Our system do not recognize this domain. Please contact your manager.');
        }

        try {
            $tenantManager->switchTenant($tenantManager->getTenant());
        } catch(\Exception $exception) {
            logger()->error('IdentifyTenant: Unable to switch database.', [
                'host' => $request->getHost(),
                'exception' => $exception->getMessage()
            ]);

            abort(404, 'unable to identify tenant');
        }

        return $next($request);
    }
}
