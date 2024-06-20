  <?php

    use Illuminate\Foundation\Application;
    use Illuminate\Foundation\Configuration\Exceptions;
    use Illuminate\Foundation\Configuration\Middleware;

    header('Access-Control-Allow-Origin: https://patatas-gourmet-frontend-ehv2-pau80ngzr-sergimonti10s-projects.vercel.app');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

    return Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__ . '/../routes/web.php',
            api: __DIR__ . '/../routes/api.php',
            commands: __DIR__ . '/../routes/console.php',
            health: '/up',
        )
        ->withMiddleware(function (Middleware $middleware) {
            $middleware->validateCsrfTokens(
                except: ['stripe/*', 'api/*']
            );
            $middleware->statefulApi();
        })
        ->withExceptions(function (Exceptions $exceptions) {
            //
        })->create();
