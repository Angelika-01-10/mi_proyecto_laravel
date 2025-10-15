<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Videoconferencia;
use App\Policies\VideoconferenciaPolicy;
use App\Services\ChatGPTService;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
    Videoconferencia::class => VideoconferenciaPolicy::class,
    Foro::class => ForoPolicy::class,
    Comentario::class => ComentarioPolicy::class,
];

    

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies(); // 🔹 Esto es necesario
    }

   public function register()
    {
        $this->app->singleton(ChatGPTService::class, function ($app) {
            return new ChatGPTService();
        });
    }

}
