<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\TestResponse;
use Wave\Http\Middleware\InstallMiddleware;

function runInstallationCheck(string $uri = '/dashboard'): TestResponse
{
    return TestResponse::fromBaseResponse(app(InstallMiddleware::class)->handle(
        Request::create($uri),
        fn () => response('Application continued')
    ));
}

it('checks for an installed user with one existence query', function () {
    User::factory()->create();
    DB::enableQueryLog();
    DB::flushQueryLog();
    try {
        runInstallationCheck()->assertOk()->assertSee('Application continued');
        $queries = DB::getQueryLog();
        expect($queries)->toHaveCount(1)
            ->and(strtolower($queries[0]['query']))->toContain('select exists');
    } finally {
        DB::disableQueryLog();
        DB::flushQueryLog();
    }
});

it('redirects to installation when no user exists', function () {
    runInstallationCheck()->assertRedirect(route('wave.install'));
});

it('does not query users when already visiting the installer', function (string $uri) {
    DB::enableQueryLog();
    DB::flushQueryLog();
    try {
        runInstallationCheck($uri)->assertOk();
        expect(DB::getQueryLog())->toBeEmpty();
    } finally {
        DB::disableQueryLog();
        DB::flushQueryLog();
    }
})->with(['/install', '/install?complete=true']);

it('preserves the base user model treatment of soft-deleted accounts', function () {
    $user = User::factory()->create();
    $user->delete();
    expect(User::query()->exists())->toBeFalse();
    // InstallMiddleware intentionally keeps its existing Wave\User query scope.
    runInstallationCheck()->assertOk();
});

it('redirects to installation when the users table is missing', function () {
    $default = config('database.default');
    config([
        'database.connections.install_middleware_empty' => ['driver' => 'sqlite', 'database' => ':memory:', 'prefix' => ''],
        'database.default' => 'install_middleware_empty',
    ]);
    try {
        runInstallationCheck()->assertRedirect(route('wave.install'));
    } finally {
        config(['database.default' => $default]);
        DB::purge('install_middleware_empty');
    }
});
