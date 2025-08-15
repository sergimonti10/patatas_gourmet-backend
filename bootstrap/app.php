  <?php

    use Illuminate\Foundation\Application;
    use Illuminate\Foundation\Configuration\Exceptions;
    use Illuminate\Foundation\Configuration\Middleware;
    use Illuminate\Http\Middleware\HandleCors;


    return Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__ . '/../routes/web.php',
            api: __DIR__ . '/../routes/api.php',
            commands: __DIR__ . '/../routes/console.php',
            health: '/up',
        )
        ->withMiddleware(function (Middleware $middleware) {
            // $middleware->validateCsrfTokens(
            //     except: ['stripe/*', 'api/*']
            // );
            // $middleware->statefulApi();
            $middleware->use([
                HandleCors::class,
            ]);

            // lo demás que ya tienes
            $middleware->statefulApi();
            $middleware->validateCsrfTokens(
                except: ['stripe/*', 'api/*']
            );
        })
        ->withExceptions(function (Exceptions $exceptions) {
            //
        })->create();
