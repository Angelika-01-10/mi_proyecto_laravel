<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ForoController;
use App\Http\Controllers\ComentarioController;
use App\Http\Controllers\VideoconferenciaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HistoriaPielController;
use App\Http\Controllers\AdminDermatologoController;


// Página de inicio
Route::get('/', function () {
    return redirect()->route('dashboard');
});

// Dashboard
Route::get('/dashboard', fn() => view('dashboard'))
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


// 🔐 Grupo general de rutas protegidas
Route::middleware('auth')->group(function () {

    // ==========================
    // 👑 RUTAS SOLO ADMINISTRADOR
    // ==========================
  Route::prefix('admin/dermatologos')->name('admin.dermatologos.')->group(function () {
    Route::get('/', [AdminDermatologoController::class, 'index'])->name('index');
    Route::get('/crear', [AdminDermatologoController::class, 'create'])->name('create');
    Route::post('/', [AdminDermatologoController::class, 'store'])->name('store');
    Route::delete('/{id}', [AdminDermatologoController::class, 'destroy'])->name('destroy');
});


    // ==========================
    // 👤 PERFIL
    // ==========================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });


    // ==========================
    // 💬 FOROS
    // ==========================
    Route::prefix('foros')->name('foros.')->group(function () {
        Route::get('/', [ForoController::class, 'index'])->name('index');
        Route::get('/crear', [ForoController::class, 'create'])->name('create');
        Route::post('/', [ForoController::class, 'store'])->name('store');
        Route::get('/{foro}', [ForoController::class, 'show'])->name('show');
        Route::post('/{foro}/comentarios', [ForoController::class, 'comentar'])->name('comentar');
        Route::get('/{foro}/edit', [ForoController::class, 'edit'])->name('edit');
        Route::put('/{foro}', [ForoController::class, 'update'])->name('update');
        Route::delete('/{foro}', [ForoController::class, 'destroy'])->name('destroy');
    });


    // ==========================
    // 💭 COMENTARIOS
    // ==========================
    Route::prefix('comentarios')->name('comentarios.')->group(function () {
        Route::get('/{comentario}/edit', [ComentarioController::class, 'edit'])->name('edit');
        Route::put('/{comentario}', [ComentarioController::class, 'update'])->name('update');
        Route::delete('/{comentario}', [ComentarioController::class, 'destroy'])->name('destroy');
    });


    // ==========================
    // 🎥 VIDEOCONFERENCIAS
    // ==========================
    Route::prefix('videoconferencias')->name('videoconferencias.')->group(function () {
        Route::get('/', [VideoconferenciaController::class, 'index'])->name('index');
        Route::get('/crear', [VideoconferenciaController::class, 'create'])->name('create');
        Route::post('/', [VideoconferenciaController::class, 'store'])->name('store');
        Route::get('/{videoconferencia}', [VideoconferenciaController::class, 'show'])->name('show');
        Route::get('/{videoconferencia}/edit', [VideoconferenciaController::class, 'edit'])->name('edit');
        Route::put('/{videoconferencia}', [VideoconferenciaController::class, 'update'])->name('update');
        Route::delete('/{videoconferencia}', [VideoconferenciaController::class, 'destroy'])->name('destroy');
        Route::post('/{videoconferencia}/inscribirse', [VideoconferenciaController::class, 'inscribirse'])->name('inscribirse');
        Route::post('/{videoconferencia}/notificar', [VideoconferenciaController::class, 'notificar'])->name('notificar');
    });


    // ==========================
    // 💬 CHAT DERMATOLÓGICO
    // ==========================
    Route::prefix('consejos')->name('chat.')->group(function () {
        Route::get('/', [ChatController::class, 'index'])->name('index');
        Route::post('/nueva-conversacion', [ChatController::class, 'crear'])->name('crear');
        Route::get('/conversacion/{conversacion}', [ChatController::class, 'mostrar'])->name('mostrar');
        Route::post('/conversacion/{conversacion}/mensaje', [ChatController::class, 'enviarMensaje'])->name('mensaje');
        Route::delete('/conversacion/{conversacion}', [ChatController::class, 'eliminar'])->name('eliminar');
        Route::get('/historial', [ChatController::class, 'historial'])->name('historial');
        Route::get('/conversacion/{conversacion}/mensajes', [ChatController::class, 'obtenerMensajes'])->name('mensajes');
    });


    // ==========================
    // 🔔 NOTIFICACIONES
    // ==========================
    Route::get('/notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::post('/notificaciones/marcar-leidas', [NotificacionController::class, 'marcarLeidas'])->name('notificaciones.marcarLeidas');


    // ==========================
    // 🧴 HISTORIAS DE PIEL
    // ==========================
    Route::prefix('historias')->name('historias.')->group(function () {
        Route::get('/', [HistoriaPielController::class, 'index'])->name('index');
        Route::get('/crear', [HistoriaPielController::class, 'create'])->name('create');
        Route::post('/', [HistoriaPielController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [HistoriaPielController::class, 'edit'])->name('edit');
        Route::put('/{id}', [HistoriaPielController::class, 'update'])->name('update');
        Route::delete('/{id}', [HistoriaPielController::class, 'destroy'])->name('destroy');
        Route::post('/enviar/{dermatologo}', [HistoriaPielController::class, 'asignar'])->name('asignar');
        Route::post('/{historia}/responder', [HistoriaPielController::class, 'responder'])->name('responder');
    });


    // ==========================
    // 🧑‍⚕️ PERFIL DERMATÓLOGO
    // ==========================
    Route::get('/dermatologo/perfil', [HistoriaPielController::class, 'crearPerfil'])->name('dermatologo.perfil');
    Route::post('/dermatologo/perfil', [HistoriaPielController::class, 'guardarPerfil'])->name('dermatologo.guardar-perfil');
    Route::get('/dermatologo/{id}/perfil', [HistoriaPielController::class, 'verPerfilDermatologo'])->name('dermatologo.ver-perfil');


    // Panel general
    Route::get('/admin', fn() => 'Panel de administración')->name('admin');
});


// 🔐 Autenticación
require __DIR__.'/auth.php';
