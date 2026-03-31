<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Seguros Vega — Admin')</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&display=swap" rel="stylesheet">
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  @stack('styles')
</head>
<body class="sv-app-body" 
      x-data="{ sidebarOpen: window.innerWidth > 1024 }"
      @resize.window="sidebarOpen = window.innerWidth > 1024">

  <!-- ══ SIDEBAR ══════════════════════════════════════════ -->
  <aside class="sv-sidebar" :class="{ 'collapsed': !sidebarOpen }">

    <!-- Logo -->
    <div class="sv-sidebar__logo">
      <img src="{{ asset('images/seguros-vega-logo-dark.png') }}"
           alt="Seguros Vega" class="sv-sidebar__logo-img">
    </div>

    <!-- Separador dorado -->
    <div class="sv-sidebar__divider"></div>

    <!-- Navegación -->
    <nav class="sv-sidebar__nav">
      <a href="{{ route('dashboard') }}"
         class="sv-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <span class="sv-nav-item__icon">
          <!-- Heroicon: home -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20">
            <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z"/>
            <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z"/>
          </svg>
        </span>
        <span class="sv-nav-item__label">Dashboard</span>
      </a>

      <a href="{{ route('polizas.index') ?? '#' }}"
         class="sv-nav-item {{ request()->routeIs('polizas.*') ? 'active' : '' }}">
        <span class="sv-nav-item__icon">
          <!-- Heroicon: document-text -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20">
            <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z" clip-rule="evenodd"/>
            <path d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z"/>
          </svg>
        </span>
        <span class="sv-nav-item__label">Pólizas</span>
      </a>

      <!-- Solo visible para Admin -->
      {{-- @role('admin') --}}
      <div class="sv-nav-section-label">Administración</div>

      <a href="#" class="sv-nav-item">
        <span class="sv-nav-item__icon">
          <!-- Heroicon: users -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20">
            <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z"/>
          </svg>
        </span>
        <span class="sv-nav-item__label">Agentes</span>
      </a>

      <a href="#" class="sv-nav-item">
        <span class="sv-nav-item__icon">
          <!-- Heroicon: chart-bar -->
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20">
            <path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z"/>
          </svg>
        </span>
        <span class="sv-nav-item__label">Reportes</span>
      </a>
      {{-- @endrole --}}
    </nav>

    <!-- Usuario en pie del sidebar -->
    <div class="sv-sidebar__footer">
      <div class="sv-sidebar__user">
        <div class="sv-user-avatar">EM</div>
        <div class="sv-user-info">
          <span class="sv-user-name">Emmanuel Vega</span>
          <span class="sv-user-role">Administrador</span>
        </div>
      </div>
      <a href="{{ route('logout') ?? '#' }}" class="sv-sidebar__logout"
         title="Cerrar sesión">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="18">
          <path fill-rule="evenodd" d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm5.03 4.72a.75.75 0 0 1 0 1.06l-1.72 1.72h10.94a.75.75 0 0 1 0 1.5H10.81l1.72 1.72a.75.75 0 1 1-1.06 1.06l-3-3a.75.75 0 0 1 0-1.06l3-3a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd"/>
        </svg>
      </a>
    </div>
  </aside>

  <!-- ══ CONTENIDO PRINCIPAL ═══════════════════════════════ -->
  <main class="sv-main">

    <!-- TOPBAR -->
    <header class="sv-topbar">
      <div class="sv-topbar__left">
        <!-- Toggle sidebar (mobile) -->
        <button @click="sidebarOpen = !sidebarOpen" class="sv-topbar__toggle">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="22">
            <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75H12a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/>
          </svg>
        </button>
        <div class="sv-topbar__breadcrumb">
          <span class="sv-topbar__page-title">@yield('page-title', 'Dashboard')</span>
        </div>
      </div>
      <div class="sv-topbar__right">
        <!-- Notificaciones (decorativo) -->
        <button class="sv-topbar__icon-btn" title="Notificaciones">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20">
            <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9a2.25 2.25 0 1 0 4.496 0 25.057 25.057 0 0 1-4.496 0Z" clip-rule="evenodd"/>
          </svg>
          <span class="sv-notif-dot"></span>
        </button>
        <!-- Fecha actual -->
        <span class="sv-topbar__date">
          {{ now()->locale('es')->isoFormat('dddd, D [de] MMMM YYYY') }}
        </span>
      </div>
    </header>

    <!-- CONTENIDO DE CADA VISTA -->
    <div class="sv-content">
      @yield('content')
    </div>
  </main>

  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
</body>
</html>
