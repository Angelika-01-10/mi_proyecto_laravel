<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        // Traemos todas las notificaciones del usuario autenticado
        $notificaciones = auth()->user()->notifications;

        return view('notificaciones.index', compact('notificaciones'));
    }

    public function marcarLeidas()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', '✅ Todas las notificaciones fueron marcadas como leídas.');
    }
}
