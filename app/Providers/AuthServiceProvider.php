<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Videoconferencia;
use App\Policies\VideoconferenciaPolicy;
use App\Models\Foro;
use App\Policies\ForoPolicy;
use App\Models\Comentario;
use App\Policies\ComentarioPolicy;
use App\Services\ChatGPTService;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Videoconferencia::class => VideoconferenciaPolicy::class,
        Foro::class => ForoPolicy::class,
        Comentario::class => ComentarioPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();
    }

    public function register(): void
    {
        $this->app->singleton(ChatGPTService::class, function ($app) {
            return new ChatGPTService();
        });
    }
}
