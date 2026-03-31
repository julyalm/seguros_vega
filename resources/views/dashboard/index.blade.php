@extends('layouts.app')

@section('title', 'Dashboard — Seguros Vega')
@section('page-title', 'Dashboard')

@section('content')

<!-- ══ KPI CARDS ═══════════════════════════════════════════ -->
<div class="sv-kpi-grid">

  <div class="sv-kpi-card">
    <div class="sv-kpi-card__icon sv-kpi-card__icon--navy">
      <svg width="24" viewBox="0 0 24 24" fill="currentColor">
        <path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" clip-rule="evenodd"/>
      </svg>
    </div>
    <div class="sv-kpi-card__body">
      <span class="sv-kpi-card__label">Total Pólizas</span>
      <span class="sv-kpi-card__value">148</span>
      <span class="sv-kpi-card__trend sv-kpi-card__trend--up">+12 este mes</span>
    </div>
  </div>

  <div class="sv-kpi-card">
    <div class="sv-kpi-card__icon sv-kpi-card__icon--green">
      <svg width="24" viewBox="0 0 24 24" fill="currentColor">
        <path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Z" clip-rule="evenodd"/>
      </svg>
    </div>
    <div class="sv-kpi-card__body">
      <span class="sv-kpi-card__label">Activas</span>
      <span class="sv-kpi-card__value">132</span>
      <span class="sv-kpi-card__trend sv-kpi-card__trend--up">89% del total</span>
    </div>
  </div>

  <div class="sv-kpi-card">
    <div class="sv-kpi-card__icon sv-kpi-card__icon--red">
      <svg width="24" viewBox="0 0 24 24" fill="currentColor">
        <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
      </svg>
    </div>
    <div class="sv-kpi-card__body">
      <span class="sv-kpi-card__label">Vencidas</span>
      <span class="sv-kpi-card__value">16</span>
      <span class="sv-kpi-card__trend sv-kpi-card__trend--down">Requieren atención</span>
    </div>
  </div>

  <div class="sv-kpi-card">
    <div class="sv-kpi-card__icon sv-kpi-card__icon--gold">
      <svg width="24" viewBox="0 0 24 24" fill="currentColor">
        <path d="M10.464 8.746c.227-.18.497-.311.786-.394v2.795a2.252 2.252 0 0 1-.786-.393c-.394-.313-.546-.681-.546-1.004 0-.323.152-.691.546-1.004ZM12.75 15.662v-2.824c.347.085.664.228.921.421.427.32.579.686.579.991 0 .305-.152.671-.579.991a2.534 2.534 0 0 1-.921.42Z"/>
        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v.816a3.836 3.836 0 0 0-1.72.756c-.712.566-1.112 1.35-1.112 2.178 0 .829.4 1.612 1.113 2.178.502.4 1.102.647 1.719.756v2.978a2.536 2.536 0 0 1-.921-.421l-.879-.66a.75.75 0 0 0-.9 1.2l.879.66c.533.4 1.169.645 1.821.75V18a.75.75 0 0 0 1.5 0v-.81a4.124 4.124 0 0 0 1.821-.749c.745-.559 1.179-1.344 1.179-2.191 0-.847-.434-1.632-1.179-2.191a4.122 4.122 0 0 0-1.821-.75V8.354c.29.082.559.213.786.393l.415.33a.75.75 0 0 0 .933-1.175l-.415-.33a3.836 3.836 0 0 0-1.719-.755V6Z" clip-rule="evenodd"/>
      </svg>
    </div>
    <div class="sv-kpi-card__body">
      <span class="sv-kpi-card__label">Nuevas este mes</span>
      <span class="sv-kpi-card__value">12</span>
      <span class="sv-kpi-card__trend sv-kpi-card__trend--up">↑ vs mes anterior</span>
    </div>
  </div>

</div>

<!-- ══ TABLA DE PÓLIZAS ══════════════════════════════════════ -->
<div class="sv-card" style="margin-top: 28px;">
  <div class="sv-card__header">
    <div class="sv-card__header-left">
      <h3 class="sv-card__title">Mis Pólizas</h3>
      <p class="sv-card__subtitle">Gestión y seguimiento de pólizas activas</p>
    </div>
    <a href="{{ route('polizas.create') }}" class="sv-btn sv-btn--primary">
      <svg width="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right:8px">
        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd"/>
      </svg>
      Nueva Póliza
    </a>
  </div>

  <!-- Filtros -->
  <div class="sv-table-filters">
    <!-- Búsqueda -->
    <div class="sv-search-input">
      <svg width="16" viewBox="0 0 24 24" fill="currentColor" class="sv-search-input__icon">
        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd"/>
      </svg>
      <input type="text" placeholder="Buscar por asegurado, póliza..." class="sv-search-input__field">
    </div>

    <!-- Filtro de estado -->
    <div class="sv-filter-tabs">
      <button class="sv-filter-tab active" data-filter="all">Todas</button>
      <button class="sv-filter-tab" data-filter="active">Activas</button>
      <button class="sv-filter-tab" data-filter="expired">Vencidas</button>
    </div>

    <!-- Filtro por agente (solo admin) -->
    {{-- @role('admin') --}}
    <select class="sv-select sv-select--compact">
      <option value="">Todos los agentes</option>
      <option>Emmanuel Vega</option>
      <option>Carlos Mendoza</option>
      <option>Laura Torres</option>
    </select>
    {{-- @endrole --}}

    <!-- Filtro por ramo -->
    <select class="sv-select sv-select--compact">
      <option value="">Todos los ramos</option>
      <option>Autos</option>
      <option>GMM</option>
      <option>Daños</option>
    </select>
  </div>

  <!-- Tabla -->
  <div class="sv-table-wrapper">
    <table class="sv-table">
      <thead>
        <tr>
          <th>No. Póliza</th>
          <th>Asegurado</th>
          <th>Ramo</th>
          <th>Aseguradora</th>
          <th>Inicio vigencia</th>
          <th>Fin vigencia</th>
          <th>Prima</th>
          <th>Agente</th>
          <th>Status</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        {{-- FILA DE EJEMPLO — repetir con @foreach en implementación real --}}
        <tr class="sv-table__row">
          <td><span class="sv-mono">A-2024-00124</span></td>
          <td>
            <div class="sv-table__user">
              <div class="sv-table__avatar">MG</div>
              <span>María González López</span>
            </div>
          </td>
          <td><span class="sv-badge sv-badge--ramo-autos">Autos</span></td>
          <td>Qualitas</td>
          <td>01/01/2024</td>
          <td>01/01/2025</td>
          <td class="sv-mono">$8,450.00</td>
          <td>Emmanuel V.</td>
          <td><span class="sv-badge sv-badge--active">Activa</span></td>
          <td>
            <div class="sv-table__actions">
              <button class="sv-action-btn" title="Ver detalle">
                <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd"/></svg>
              </button>
              <button class="sv-action-btn" title="Editar">
                <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z"/></svg>
              </button>
              <button class="sv-action-btn sv-action-btn--danger" title="Eliminar">
                <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M16.5 4.478v.227a48.816 48.816 0 0 1 3.878.512.75.75 0 1 1-.256 1.478l-.209-.035-1.005 13.07a3 3 0 0 1-2.991 2.77H8.084a3 3 0 0 1-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 0 1-.256-1.478A48.567 48.567 0 0 1 7.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 0 1 3.369 0c1.603.051 2.815 1.387 2.815 2.951Zm-6.136-1.452a51.196 51.196 0 0 1 3.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 0 0-6 0v-.113c0-.794.609-1.428 1.364-1.452Zm-.355 5.945a.75.75 0 1 0-1.5.058l.347 9a.75.75 0 1 0 1.499-.058l-.346-9Zm5.48.058a.75.75 0 1 0-1.498-.058l-.347 9a.75.75 0 0 0 1.5.058l.345-9Z" clip-rule="evenodd"/></svg>
              </button>
            </div>
          </td>
        </tr>

        <tr class="sv-table__row">
          <td><span class="sv-mono">G-2023-00089</span></td>
          <td>
            <div class="sv-table__user">
              <div class="sv-table__avatar" style="background:var(--sv-navy)">RH</div>
              <span>Roberto Hernández M.</span>
            </div>
          </td>
          <td><span class="sv-badge sv-badge--ramo-gmm">GMM</span></td>
          <td>Chubb</td>
          <td>15/03/2023</td>
          <td>15/03/2024</td>
          <td class="sv-mono">$24,800.00</td>
          <td>Emmanuel V.</td>
          <td><span class="sv-badge sv-badge--expired">Vencida</span></td>
          <td>
            <div class="sv-table__actions">
              <button class="sv-action-btn" title="Ver"><svg width="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd"/></svg></button>
              <button class="sv-action-btn" title="Editar"><svg width="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.2Z"/></svg></button>
            </div>
          </td>
        </tr>

      </tbody>
    </table>
  </div>

  <!-- Paginación -->
  <div class="sv-table-footer">
    <span class="sv-table-footer__info">Mostrando 1–15 de 148 pólizas</span>
    <div class="sv-pagination">
      <button class="sv-pagination__btn" disabled>
        <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 0 1 0-1.06l7.5-7.5a.75.75 0 1 1 1.06 1.06L9.31 12l6.97 6.97a.75.75 0 1 1-1.06 1.06l-7.5-7.5Z" clip-rule="evenodd"/></svg>
      </button>
      <button class="sv-pagination__btn sv-pagination__btn--active">1</button>
      <button class="sv-pagination__btn">2</button>
      <button class="sv-pagination__btn">3</button>
      <span class="sv-pagination__dots">…</span>
      <button class="sv-pagination__btn">10</button>
      <button class="sv-pagination__btn">
        <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 0 1 0 1.06l-7.5 7.5a.75.75 0 0 1-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 0 1 1.06-1.06l7.5 7.5Z" clip-rule="evenodd"/></svg>
      </button>
    </div>
  </div>
</div>

@endsection
