<?php

namespace Modules\Core\Providers;

use Modules\Core\View\Components\ConfigForm;
use View;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use App\Models\User;
use Modules\Core\Captcha\Captcha;
use Modules\Core\Auth\Guards\AdminGuard;
use Modules\Core\Http\Middleware\UseGuard;
use Modules\Core\Http\Composers\GlobalComposer;
use Modules\Core\Models\Frontend\UserInvitation;
use Modules\Core\Config\Repository as ConfigRepository;
use Modules\Core\Captcha\Facades\Captcha as CaptchaFacade;
use Modules\Core\Module\ModuleServiceProvider as ServiceProvider;
use Modules\Core\Listeners\Frontend\UserEventListener;
use Modules\Core\Listeners\Frontend\UserInvitationEventListener;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Database\Eloquent\Factory;
use Illuminate\Validation\Factory as ValidationFactory;
use Illuminate\Support\Facades\Blade;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Core';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'core';

    /**
     * @var string
     */
    protected $modulePath = __DIR__ . '/../..';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerModelObservers();
        $this->registerCaptcha();
        $this->registerValidators();
//        $this->registerTranslations();
        $this->registerViews();
//        $this->registerFactories();
//        $this->registerModelRelations();
        $this->registerBlade();;
        $this->loadMigrationsFrom($this->modulePath . '/database/migrations');
        $this->loadSeedsFrom($this->modulePath . '/database/seeds');

    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerConfig();
        $this->registerHelpers();
    }

    protected function registerModelObservers()
    {
        User::observe(UserEventListener::class);
        UserInvitation::observe(UserInvitationEventListener::class);
    }

    protected function registerCaptcha()
    {
        // Bind captcha
        $this->app->bind('captcha', function ($app) {
            return new Captcha(
                $app['Illuminate\Filesystem\Filesystem'],
                $app['Illuminate\Contracts\Config\Repository'],
                $app['Intervention\Image\ImageManager'],
                $app['Illuminate\Session\Store'],
                $app['Illuminate\Hashing\BcryptHasher'],
                $app['Illuminate\Support\Str']
            );
        });
        AliasLoader::getInstance()->alias('Captcha', CaptchaFacade::class);
    }

    protected function registerValidators()
    {
        /** @var ValidationFactory $validator */
        $validator = $this->app['validator'];

        $validator->extend('captcha', function ($attribute, $value, $parameters) {
            return \Captcha::check($value);
        });

        $validator->extend('captcha_api', function ($attribute, $value, $parameters) {
            return \Captcha::check_api($value, $parameters[0]);
        });
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = $this->modulePath . '/resources/views';

        $this->publishes([
            $sourcePath => $viewPath,
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

        $this->configureComposer();
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (\Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }

        return $paths;
    }

    /**
     * Configure the admin authentication guard.
     *
     * @return void
     */
    protected function configureComposer()
    {
        View::composer('*', GlobalComposer::class);
    }

    /**
     * Register an additional directory of factories.
     *
     * @return void
     */
    public function registerFactories()
    {
        if (!app()->environment('production') && $this->app->runningInConsole()) {
            app(Factory::class)->load(module_path($this->moduleName, 'database/factories'));
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        // 覆盖使用新的config设置
        $this->app->extend('config', function ($config) {
            $newConfig = new ConfigRepository($config->all());
            $newConfig->loadSettingsFromCachedFile();

            return $newConfig;
        });

        // 合并默认模块参数
        $this->mergeConfigFrom(
            $this->modulePath . '/config/config.php', 'core::'
        );

        $this->publishes([
            $this->modulePath . '/config/captcha.php' => config_path('captcha.php'),
        ], 'config');
        $this->mergeConfigFrom(
            $this->modulePath . '/config/captcha.php', 'captcha'
        );

        if (!$this->app->configurationIsCached()) {
            config([
                'auth.guards' => array_merge([
                    'admin_web' => [
                        'driver' => 'session',
                        'provider' => 'admin_users',
                    ],

                    'admin' => [
                        'driver' => 'admin',
                        'provider' => 'admin_users',
                    ],
                ], config('auth.guards', [])),
            ]);

            config([
                'auth.providers' => array_merge([
                    'admin_users' => [
                        'driver' => 'eloquent',
                        'model' => \App\Models\AdminUser::class,
                    ],
                ], config('auth.providers', [])),
            ]);
        }

        $this->configureGuard();
        $this->configureMiddleware();
    }

    /**
     * Register Blade Components
     *
     * @return void
     */
    protected function registerBlade()
    {
        Blade::component(ConfigForm::class, 't-config-form');
    }

    /**
     * Configure the admin authentication guard.
     *
     * @return void
     */
    protected function configureGuard()
    {
        Auth::resolved(function ($auth) {
            $auth->viaRequest('admin', new AdminGuard($auth, config('core::admin.guard_expiration')));
        });
    }

    /**
     * Configure the Admin middleware and priority.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        $kernel = $this->app->make(Kernel::class);

        $kernel->prependToMiddlewarePriority(UseGuard::class);
    }

    public function registerHelpers()
    {
        $rdi = new RecursiveDirectoryIterator($this->modulePath . '/src/Helpers/Global');
        $it = new RecursiveIteratorIterator($rdi);

        while ($it->valid()) {
            if (
                !$it->isDot() &&
                $it->isFile() &&
                $it->isReadable() &&
                $it->current()->getExtension() === 'php' &&
                strpos($it->current()->getFilename(), 'Helper')
            ) {
                require $it->key();
            }

            $it->next();
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

}
