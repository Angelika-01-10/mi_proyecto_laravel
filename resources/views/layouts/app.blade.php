<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dermatología a la Mano')</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/hero.css') }}" rel="stylesheet">
    @yield('styles') 
    <style>
        /* CSS básico para que funcione sin Tailwind */
        * { box-sizing: border-box; }
        
        .header-nav {
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .nav-container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 1rem;
        }
        
        .nav-flex {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 64px;
        }
        
        .logo-section {
            display: flex;
            align-items: center;
        }
        
        .logo-img {
            height: 64px;
            width: 64px;
            margin-right: 12px;
        }
        
        .logo-text {
            font-size: 1.25rem;
            font-weight: bold;
            color: #be185d;
        }
        
        .desktop-menu {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .user-welcome {
            background: #fdf2f8;
            color: #6b7280;
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 0.875rem;
        }
        
        .user-name {
            font-weight: 600;
            color: #be185d;
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }
        
        .nav-link {
            color: #374151;
            text-decoration: none;
            padding: 8px 12px;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 6px;
            transition: all 0.2s;
        }
        
        .nav-link:hover {
            color: #be185d;
            background: #fdf2f8;
        }
        
        .logout-btn {
            background: transparent;
            border: 2px solid #be185d;
            color: #be185d;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            margin-left: 1rem;
        }
        
        .logout-btn:hover {
            background: #be185d;
            color: white;
        }
        
        .mobile-menu-btn {
            display: none;
            padding: 8px;
            border-radius: 6px;
            background: transparent;
            border: none;
            color: #374151;
            cursor: pointer;
        }
        
        .mobile-menu-btn:hover {
            background: #fdf2f8;
        }
        
        .mobile-menu {
            display: none;
            margin-top: 8px;
            background: #fdf2f8;
            border-radius: 8px;
            padding: 1rem;
        }
        
        .mobile-menu.show {
            display: block;
        }
        
        .mobile-user-info {
            font-size: 0.875rem;
            color: #6b7280;
            border-bottom: 1px solid #f3e8ff;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        
        .mobile-nav-link {
            display: block;
            color: #374151;
            text-decoration: none;
            padding: 8px 0;
        }
        
        .mobile-nav-link:hover {
            color: #be185d;
        }
        
        .mobile-logout {
            padding-top: 12px;
            border-top: 1px solid #f3e8ff;
            margin-top: 12px;
        }
        
        .mobile-logout-btn {
            width: 100%;
            background: transparent;
            border: 2px solid #be185d;
            color: #be185d;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .mobile-logout-btn:hover {
            background: #be185d;
            color: white;
        }
        
        .main-content {
            max-width: 1280px;
            margin: 0 auto;
            padding: 1rem;
        }
        
        .footer {
            background: #e5e7eb;
            text-align: center;
            padding: 1rem;
            margin-top: 2rem;
        }
        
        @media (max-width: 768px) {
            .desktop-menu { display: none; }
            .mobile-menu-btn { display: block; }
        }
        
        @media (min-width: 769px) {
            .mobile-menu { display: none !important; }
        }
        
    </style>
</head>


<body style="background-color: #f3f4f6; margin: 0; font-family: system-ui, -apple-system, sans-serif;">
    <!-- Header -->
    <header class="header-nav">
        <nav class="nav-container">
            <div class="nav-flex">
                
                <!-- Logo -->
                <div class="logo-section">
                    <img src="{{ asset('images/Logotipo.png') }}" alt="Dermatología a la Mano" class="logo-img">
                    <span class="logo-text">Dermatología a la Mano</span>
                </div>

                <!-- Desktop Menu -->
                <div class="desktop-menu">
                    <!-- User Welcome -->
                    @auth
                        <div class="user-welcome">
                            ❤️ Bienvenido, <span class="user-name">{{ Auth::user()->name }}</span>
                        </div>
                    @endauth
                    
                    <!-- Navigation Links -->
                    <div class="nav-links">
                        <a href="{{ url('/') }}" class="nav-link">Inicio</a>
                        <a href="{{ route('foros.index') }}" class="nav-link">Foros</a>
                        <a href="{{ route('videoconferencias.index') }}" class="nav-link">Videoconferencias</a>
                       <a href="{{ route('chat.index') }}" class="nav-link">Consejos</a>
                        <a href="{{ route('historias.index') }}" class="nav-link">Historia de mi piel</a>
                        @auth
                       @if(Auth::user()->hasRole('administrador'))
                     <a href="{{ route('admin.dermatologos.index') }}" class="nav-link">
                    Crear Dermatólogo
                    </a>
                     @endif
                   @endauth


                      {{-- 🔔 Notificaciones --}}
        @auth
            <div class="relative">
                <a href="{{ route('notificaciones.index') }}" class="nav-link" style="position: relative;">
                    🔔
                    @if(Auth::user()->unreadNotifications->count() > 0)
                        <span style="
                            position: absolute;
                            top: -5px;
                            right: -10px;
                            background: #dc2626;
                            color: white;
                            font-size: 0.7rem;
                            font-weight: bold;
                            padding: 2px 6px;
                            border-radius: 9999px;">
                            {{ Auth::user()->unreadNotifications->count() }}
                        </span>
                    @endif
                </a>
            </div>
        @endauth
  
                        
                        @auth
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="logout-btn">
                                    Cerrar Sesión
                                </button>
                            </form>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <div>
                    <button id="menu-btn" class="mobile-menu-btn">
                        <svg style="height: 24px; width: 24px;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div id="mobile-menu" class="mobile-menu">
                <!-- User info móvil -->
                @auth
                    <div class="mobile-user-info">
                        ❤️ Hola, <span class="user-name">{{ Auth::user()->name }}</span>
                    </div>
                @endauth
                
                <!-- Navigation móvil -->
                <a href="{{ url('/') }}" class="mobile-nav-link">Inicio</a>
                <a href="#" class="mobile-nav-link">Foro</a>
                <a href="{{ route('videoconferencias.index') }}" class="mobile-nav-link">Videoconferencias</a>
                <a href="#" class="mobile-nav-link">Consejos</a>
                <a href="#" class="mobile-nav-link">Contacto</a>
                
                @auth
                    <div class="mobile-logout">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="mobile-logout-btn">
                                Cerrar Sesión
                            </button>
                        </form>
                    </div>
                @endauth
            </div>
        </nav>
    </header>


    <main class="main-content">
    @yield('hero')
    @yield('content')

</main>



    

    <footer class="footer">
        &copy; {{ date('Y') }} Dermatología a la Mano
    </footer>

    <!-- Script para menú móvil -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuBtn = document.getElementById('menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            
            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener('click', function() {
                    mobileMenu.classList.toggle('show');
                });
            }
        });
    </script>
</body>
</html>