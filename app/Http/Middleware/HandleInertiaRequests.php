<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            'routes' => [
                ["id" => 1, "path" => "/", "name" => "home", "acc" => [2, 3]],
                ["id" => 2, "path" => "/absen", "name" => "absen", "acc" => 3],
                ["id" => 3, "path" => "/user-management", "name" => "user management", "acc" => 1],
                ["id" => 4, "path" => "/chat", "name" => "chat", "acc" => 3],
                ["id" => 5, "path" => "/list-approval", "name" => "list approval", "acc" => 2],
                ["id" => 6, "path" => "/list-absen", "name" => "list absen", "acc" => 2],
                ["id" => 7, "path" => "/list-token", "name" => "token", "acc" => 1],
            ],
            'user' => fn () => DB::table('profiles as p')
                ->join('users as u', 'u.id', '=', 'p.user_id')
                ->join('roles as r', 'r.id', '=', 'p.role_id')
                ->where('p.user_id', auth()->user() ? auth()->user()->id : 0)
                ->select('p.*', 'r.role', 'u.username', 'u.email')
                ->first(),
            'flash' => [
                'message' => fn () => $request->session()->get('message'),
                'success' => fn () => $request->session()->get('success'),
                'checkToken' => fn () => $request->session()->get('check-token'),
            ],
        ]);
    }
}
