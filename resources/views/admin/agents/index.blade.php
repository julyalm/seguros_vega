@extends('layouts.app')

@section('title', 'Gestión de Agentes — Seguros Vega')
@section('page-title', 'Administración de Agentes')

@section('content')

{{-- El x-data envuelve TANTO la tarjeta como el modal para que showModal sea accesible desde ambos --}}
<div x-data="{ showModal: {{ $errors->any() ? 'true' : 'false' }} }">

  <div class="sv-card">
    <div class="sv-card__header">
      <div class="sv-card__header-left">
        <h3 class="sv-card__title">Lista de Agentes</h3>
        <p class="sv-card__subtitle">Administra los accesos y perfiles del equipo</p>
      </div>
      <button class="sv-btn sv-btn--primary" @click="showModal = true">
        <svg width="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right:8px">
          <path d="M6.25 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM3.25 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM19.75 7.5a.75.75 0 0 0-1.5 0v2.25H16a.75.75 0 0 0 0 1.5h2.25v2.25a.75.75 0 0 0 1.5 0v-2.25H22a.75.75 0 0 0 0-1.5h-2.25V7.5Z" />
        </svg>
        Nuevo Agente
      </button>
    </div>

    @if(session('success'))
      <div class="sv-alert sv-alert--success" style="margin: 0 24px 24px 24px;">
        {{ session('success') }}
      </div>
    @endif

    <!-- Tabla de Agentes -->
    <div class="sv-table-wrapper">
      <table class="sv-table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>F. Registro</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          @forelse($agents as $agent)
          <tr class="sv-table__row">
            <td>
              <div class="sv-table__user">
                <div class="sv-table__avatar">{{ substr($agent->name, 0, 2) }}</div>
                <span>{{ $agent->name }}</span>
              </div>
            </td>
            <td class="sv-mono">{{ $agent->email }}</td>
            <td>
              <span class="sv-badge sv-badge--active">Agente</span>
            </td>
            <td>{{ $agent->created_at->format('d/m/Y') }}</td>
            <td>
              <form action="{{ route('admin.agents.destroy', $agent->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar a este agente?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="sv-action-btn sv-action-btn--danger" style="color: var(--sv-red-600)">
                    <svg width="18" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5 0v8.25a.75.75 0 0 0 1.5 0v-8.25ZM12.75 9a.75.75 0 0 1 .75.75v8.25a.75.75 0 0 1-1.5 0v-8.25a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" /></svg>
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" style="text-align:center; color: var(--sv-gray-400); padding: 40px;">
              No hay agentes registrados aún.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal para Nuevo Agente -->
  <div class="sv-modal-overlay" x-show="showModal" style="display:none;" x-transition>
    <div class="sv-modal-content" @click.away="showModal = false">
      <div class="sv-modal-header">
        <h3 class="sv-modal-title">Registrar Nuevo Agente</h3>
        <button @click="showModal = false" class="sv-modal-close">&times;</button>
      </div>

      <form action="{{ route('admin.agents.store') }}" method="POST" class="sv-modal-body">
        @csrf

        {{-- Errores de validación --}}
        @if($errors->any())
          <div class="sv-alert sv-alert--danger" style="margin-bottom: 16px;">
            <ul style="margin: 0; padding-left: 18px;">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <div class="sv-form-grid sv-form-grid--1">
          <div class="sv-field">
            <label class="sv-field__label">Nombre completo</label>
            <input type="text" name="name" class="sv-input {{ $errors->has('name') ? 'sv-input--error' : '' }}"
                   value="{{ old('name') }}" required>
          </div>
          <div class="sv-field">
            <label class="sv-field__label">Correo electrónico</label>
            <input type="email" name="email" class="sv-input {{ $errors->has('email') ? 'sv-input--error' : '' }}"
                   value="{{ old('email') }}" required>
          </div>
          <div class="sv-field">
            <label class="sv-field__label">Contraseña</label>
            <input type="password" name="password" class="sv-input {{ $errors->has('password') ? 'sv-input--error' : '' }}" required>
          </div>
          <div class="sv-field">
            <label class="sv-field__label">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="sv-input" required>
          </div>
        </div>

        <div class="sv-modal-footer">
          <button type="button" class="sv-btn sv-btn--outline" @click="showModal = false">Cancelar</button>
          <button type="submit" class="sv-btn sv-btn--primary">Guardar Agente</button>
        </div>
      </form>
    </div>
  </div>

</div>{{-- /x-data --}}

<style>
/* Modal de Agentes */
.sv-modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center; padding: 20px; }
.sv-modal-content { background: white; width: 100%; max-width: 500px; border-radius: 16px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
.sv-modal-header { padding: 20px 24px; border-bottom: 1px solid var(--sv-gray-100); display: flex; align-items: center; justify-content: space-between; }
.sv-modal-title { font-family: 'Rajdhani'; font-weight: 700; color: var(--sv-navy); margin: 0; font-size: 20px; }
.sv-modal-close { background: none; border: none; font-size: 24px; cursor: pointer; color: var(--sv-gray-400); }
.sv-modal-body { padding: 24px; }
.sv-modal-footer { margin-top: 24px; display: flex; gap: 12px; justify-content: flex-end; }
.sv-alert--danger { background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; border-radius: 8px; padding: 10px 14px; font-size: 14px; }
.sv-input--error { border-color: #dc2626 !important; }
</style>

@endsection
