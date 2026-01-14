<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use App\Models\IpRule;
use Symfony\Component\HttpFoundation\Response;

class CheckIpRules
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! Schema::hasTable('ip_rules')) {
            return $next($request);
        }

        $ip = $request->ip();

        $blockedRule = IpRule::active()
            ->where('type', 'blocked')
            ->where('ip_address', $ip)
            ->first();

        if ($blockedRule) {
            abort(403);
        }

        return $next($request);
    }
}
