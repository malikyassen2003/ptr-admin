<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Tightenco\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'auth' => [
                'user' => $request->user() 
                    ? $request->user()->only('id', 'name', 'email')
                    : null,
            ],
            'ziggy' => fn () => [
                'url' => $request->url(),
                'port' => $request->getPort(),
                'defaults' => config('ziggy.defaults', []),
                'routes' => (new Ziggy())->toArray(),
            ],
        ]);
    }
}