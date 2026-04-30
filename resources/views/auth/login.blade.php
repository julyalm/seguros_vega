@extends('layouts.auth')

@section('content')
  <div class="sv-login-wrapper">

    <!-- Panel izquierdo — Decorativo / Branding -->
    <div class="sv-login-brand">
      <!-- Patrón de fondo con formas doradas -->
      <div class="sv-login-brand__bg"></div>

      <div class="sv-login-brand__content">
        <img src="{{ asset('images/Logo vega-1.jpg') }}" alt="Seguros Vega" class="sv-login-brand__logo">

        <h1 class="sv-login-brand__headline">
          Tu protección, <br>nuestra prioridad
        </h1>

        <p class="sv-login-brand__sub">
          Gestiona tus pólizas de forma simple,<br>
          segura y profesional.
        </p>

        <!-- Servicios disponibles (decorativo) -->
        <div class="sv-login-brand__chips">
          <span class="sv-chip">🚗 Autos</span>
          <span class="sv-chip">🏠 Casa</span>
          <span class="sv-chip">❤️ GMM</span>
          <!--<span class="sv-chip">🐾 Mascotas</span>-->
          <!--<span class="sv-chip">💼 Daños</span>-->
        </div>
      </div>

      <!-- Línea decorativa dorada inferior -->
      <div class="sv-login-brand__footer-line"></div>
    </div>

    <!-- Panel derecho — Formulario -->
    <div class="sv-login-form-panel">

      <div class="sv-login-form-container">

        <!-- Logo pequeño para móvil -->
        <div class="sv-login-logo-mobile">
          <img src="{{ asset('images/Logo vega-1.jpg') }}" alt="Seguros Vega" style="max-width:160px">
        </div>

        <h2 class="sv-login-title">Iniciar Sesión</h2>
        <p class="sv-login-subtitle">Accede a tu panel de administración</p>

        <!-- Mensaje de error -->
        @if ($errors->any())
          <div class="sv-alert sv-alert--error">
            <svg width="18" viewBox="0 0 24 24" fill="currentColor">
              <path fill-rule="evenodd"
                d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z"
                clip-rule="evenodd" />
            </svg>
            {{ $errors->first() }}
          </div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="sv-login-form">
          @csrf

          <!-- Usuario -->
          <div class="sv-field">
            <label for="email" class="sv-field__label">Correo electrónico</label>
            <div class="sv-field__input-wrapper">
              <span class="sv-field__icon">
                <svg width="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M1.5 8.67v8.58a3 3 0 0 0 3 3h15a3 3 0 0 0 3-3V8.67l-8.928 5.493a3 3 0 0 1-3.144 0L1.5 8.67Z" />
                  <path
                    d="M22.5 6.908V6.75a3 3 0 0 0-3-3h-15a3 3 0 0 0-3 3v.158l9.714 5.978a1.5 1.5 0 0 0 1.572 0L22.5 6.908Z" />
                </svg>
              </span>
              <input type="email" id="email" name="email" class="sv-input" placeholder="correo@ejemplo.com"
                value="{{ old('email') }}" required autofocus>
            </div>
            @error('email')
              <span class="sv-field__error">{{ $message }}</span>
            @enderror
          </div>

          <!-- Contraseña -->
          <div class="sv-field" x-data="{ showPass: false }">
            <label for="password" class="sv-field__label">Contraseña</label>
            <div class="sv-field__input-wrapper">
              <span class="sv-field__icon">
                <svg width="18" viewBox="0 0 24 24" fill="currentColor">
                  <path fill-rule="evenodd"
                    d="M12 1.5a5.25 5.25 0 0 0-5.25 5.25v3a3 3 0 0 0-3 3v6.75a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3v-6.75a3 3 0 0 0-3-3v-3c0-2.9-2.35-5.25-5.25-5.25Zm3.75 8.25v-3a3.75 3.75 0 1 0-7.5 0v3h7.5Z"
                    clip-rule="evenodd" />
                </svg>
              </span>
              <input :type="showPass ? 'text' : 'password'" id="password" name="password"
                class="sv-input sv-input--with-toggle" placeholder="Tu contraseña" required>
              <button type="button" class="sv-field__toggle" @click="showPass = !showPass">
                <svg x-show="!showPass" width="18" viewBox="0 0 24 24" fill="currentColor">
                  <path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                  <path fill-rule="evenodd"
                    d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z"
                    clip-rule="evenodd" />
                </svg>
                <svg x-show="showPass" width="18" viewBox="0 0 24 24" fill="currentColor" style="display:none;">
                  <path
                    d="M3.53 2.47a.75.75 0 0 0-1.06 1.06l18 18a.75.75 0 1 0 1.06-1.06l-18-18ZM22.676 12.553a11.249 11.249 0 0 1-2.631 4.31l-3.099-3.099a5.25 5.25 0 0 0-6.71-6.71L7.759 4.577a11.217 11.217 0 0 1 4.242-.827c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113Z" />
                </svg>
              </button>
            </div>
            @error('password')
              <span class="sv-field__error">{{ $message }}</span>
            @enderror
          </div>

          <!-- Recordarme -->
          <div class="sv-login-options">
            <label class="sv-checkbox">
              <input type="checkbox" name="remember">
              <span class="sv-checkbox__box"></span>
              <span>Recordarme</span>
            </label>
          </div>

          <!-- Botón submit -->
          <button type="submit" class="sv-btn sv-btn--primary sv-btn--full sv-btn--lg">
            Iniciar sesión
          </button>
        </form>

        <!-- Footer del formulario -->
        <p class="sv-login-contact">
          ¿Problemas para acceder?
          <a href="mailto:segurosvega.emmanuel@gmail.com">Contacta al administrador</a>
        </p>
      </div>

      <!-- Pie legal -->
      <div class="sv-login-legal">
        © {{ date('Y') }} Seguros Vega · Todos los derechos reservados
      </div>
    </div>
  </div>
@endsection