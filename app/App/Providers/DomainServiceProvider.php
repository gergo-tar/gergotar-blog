<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\View\Compilers\BladeCompiler;
use Livewire\Livewire;

class DomainServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function boot(): void
    {
        $this->loadApplication();
    }

    /**
     * Load all views from domain directories.
     */
    protected function loadApplication(): void
    {
        $domainsPath = base_path('app/App');

        if (file_exists($domainsPath) && is_dir($domainsPath)) {
            $directories = glob($domainsPath . '/*', GLOB_ONLYDIR);

            foreach ($directories as $directory) {
                // Do not scan the Http, Providers, and View directories.
                if (in_array(basename($directory), ['Http', 'Providers', 'View'])) {
                    continue;
                }
                $this->loadRoutesFromDirectory($directory);
                $this->loadViewsFromDirectory($directory);
                $this->loadLivewireComponents($directory);
                $this->loadTranslationsFromDirectory($directory);
            }
        }
    }

    /**
     * Load livewire components from domain directories.
     *
     * @param  string  $domain  The domain directory.
     */
    protected function loadLivewireComponents(string $domain): void
    {
        if (! class_exists(Livewire::class)) {
            return;
        }

        $this->callAfterResolving(BladeCompiler::class, function () use ($domain) {
            $livewirePath = "{$domain}/Livewire";
            if (file_exists($livewirePath) && is_dir($livewirePath)) {
                $domain = basename($domain);

                // Iterate trough all files and register the Livewire components.
                foreach (glob($livewirePath . '/*') as $file) {
                    $this->loadLivewireComponent($file, $domain);
                }
            }
        });
    }

    /**
     * Load routes from a specific domain directory.
     *
     * @param  string  $domain  The domain directory path.
     */
    protected function loadRoutesFromDirectory(string $domain): void
    {
        $middlewares = ['web', 'api'];

        foreach ($middlewares as $middleware) {
            $routesPath = "{$domain}/routes/{$middleware}.php";

            // Try to load the routes file with the domain name as the prefix.
            // Example: app/Domain/Blog/routes/blog.web.php
            if (! file_exists($routesPath)) {
                $prefix = strtolower(basename($domain));
                $routesPath = "{$domain}/routes/{$prefix}.{$middleware}.php";
            }

            if (file_exists($routesPath)) {
                $this->loadRoutesFromFile($routesPath, $middleware);
            }
        }
    }

    /**
     * Load views from a specific domain directory.
     *
     * @param  string  $domain  The domain directory.
     */
    protected function loadViewsFromDirectory(string $domain): void
    {
        $viewsPath = "{$domain}/resources/views";

        if (file_exists($viewsPath) && is_dir($viewsPath)) {
            View::addNamespace(basename($domain), $viewsPath);
        }
    }

    /**
     * Load a Livewire component.
     *
     * @param  string  $file  The file path.
     * @param  string  $domain  The domain name.
     */
    private function loadLivewireComponent(string $file, string $domain): void
    {
        if (is_dir($file)) {
            foreach (glob($file . '/*') as $file) {
                $this->loadLivewireComponent($file, $domain);
            }
        }

        // Get full path except the base path
        $file = str_replace(base_path(), '', $file);
        // Get the class name
        $class = ucfirst(str_replace('/', '\\', substr($file, 1, -4)));
        $class = str_replace('App\\App', 'App', $class);

        Livewire::component($this->setLivewireComponentName($class, $domain), $class);
    }

    /**
     * Load routes from a specific file with a given type.
     *
     * @param  string  $path  The path to the routes file.
     * @param  string  $type  The type of routes file (web or api).
     */
    private function loadRoutesFromFile(string $path, string $type): void
    {
        $middleware = $type === 'web' ? 'web' : 'api';

        /** @var Router $router */
        $router = $this->app['router'];
        $router->group([
            'middleware' => $middleware,
        ], function () use ($path) {
            require $path;
        });
    }

    /**
     * Load translations from a specific domain directory.
     *
     * @param  string  $domain  The domain directory.
     */
    private function loadTranslationsFromDirectory(string $domain): void
    {
        $translationsPath = "{$domain}/resources/lang";
        if (file_exists($translationsPath) && is_dir($translationsPath)) {
            $this->loadTranslationsFrom($translationsPath, basename($domain));
        }
    }

    /**
     * Set the Livewire component name.
     *
     * @param  string  $class  The Livewire Component's class name.
     * @param  string  $domain  The domain name.
     */
    private function setLivewireComponentName(string $class, string $domain): string
    {
        $domain = strtolower($domain);
        // The component name should be in kebab case.
        $componentName = Str::kebab(class_basename($class));

        $component = strtolower(str_replace('\\', '.', $class));
        // // Remove everything before Domain name
        $component = substr($component, strpos($component, $domain) + strlen($domain) + 1);
        // // Remove livewire from the name
        $component = str_replace('livewire.', '', $component);
        // Add domain name to the component.
        $component = "{$domain}.{$component}";

        // Change the component name to kebab case.
        return substr($component, 0, strrpos($component, '.')) . '.' . $componentName;
    }
}
