namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middlewareGroups = [
        'web' => [
            // ...
        ],

        'api' => [
            // ...
        ],
    ];

    protected $routeMiddleware = [
        // ...
        'admin' => \App\Http\Middleware\CheckAdminRole::class,
        'production' => \App\Http\Middleware\CheckProductionRole::class,
        'warehouse' => \App\Http\Middleware\CheckWarehouseRole::class,
    ];
}