@extends('layouts.app')

@section('title', 'Listado de Pólizas — Seguros Vega')
@section('page-title', 'Gestión de Pólizas')

@section('content')

<!-- ══ TABLA DE PÓLIZAS COMPLETA ══════════════════════════════ -->
<div class="sv-card" x-data="{ status: '{{ request('status', 'all') }}' }">
  <div class="sv-card__header">
    <div class="sv-card__header-left">
      <h3 class="sv-card__title">Catálogo de Pólizas</h3>
      <p class="sv-card__subtitle">Listado completo, filtros y gestión</p>
    </div>
    <a href="{{ route('polizas.create') }}" class="sv-btn sv-btn--primary">
      <svg width="16" viewBox="0 0 24 24" fill="currentColor" style="margin-right:8px">
        <path fill-rule="evenodd" d="M12 3.75a.75.75 0 0 1 .75.75v6.75h6.75a.75.75 0 0 1 0 1.5h-6.75v6.75a.75.75 0 0 1-1.5 0v-6.75H4.5a.75.75 0 0 1 0-1.5h6.75V4.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd"/>
      </svg>
      Nueva Póliza
    </a>
  </div>

  <!-- Filtros Avanzados -->
  <form action="{{ route('polizas.index') }}" method="GET" class="sv-filters-bar" id="filterForm">
    
    <div class="sv-filters-bar__top">
        <!-- Búsqueda -->
        <div class="sv-search-input" style="flex: 1;">
          <svg width="16" viewBox="0 0 24 24" fill="currentColor" class="sv-search-input__icon">
            <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd"/>
          </svg>
          <input type="text" name="search" value="{{ request('search') }}" 
                 placeholder="Buscar por asegurado, póliza, aseguradora..." class="sv-search-input__field">
        </div>

        <!-- Ramo -->
        <select name="ramo" class="sv-select sv-select--compact" @change="$el.closest('form').submit()">
          <option value="all">Todos los Ramos</option>
          <option value="Autos" {{ request('ramo') === 'Autos' ? 'selected' : '' }}>Autos</option>
          <option value="GMM" {{ request('ramo') === 'GMM' ? 'selected' : '' }}>GMM</option>
          <option value="Daños" {{ request('ramo') === 'Daños' ? 'selected' : '' }}>Daños</option>
        </select>

        <!-- Aseguradora -->
        <select name="aseguradora_id" class="sv-select sv-select--compact" @change="$el.closest('form').submit()">
          <option value="all">Todas las Aseguradoras</option>
          @foreach($aseguradoras as $as)
            <option value="{{ $as->id }}" {{ request('aseguradora_id') == $as->id ? 'selected' : '' }}>{{ $as->nombre }}</option>
          @endforeach
        </select>

        @if(auth()->user()->role === 'admin')
        <!-- Agente (Solo Admin) -->
        <select name="agente_id" class="sv-select sv-select--compact" @change="$el.closest('form').submit()">
          <option value="all">Todos los Agentes</option>
          @foreach($agentes as $ag)
            <option value="{{ $ag->id }}" {{ request('agente_id') == $ag->id ? 'selected' : '' }}>{{ $ag->name }}</option>
          @endforeach
        </select>
        @endif
    </div>

    <div class="sv-filters-bar__bottom" style="display: flex; align-items: center; gap: 16px; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--sv-gray-100);">
        
        <!-- Filtro de estado -->
        <input type="hidden" name="status" x-model="status">
        <div class="sv-filter-tabs">
          <button type="button" @click="status = 'all'; $nextTick(() => $el.closest('form').submit())" 
                  class="sv-filter-tab" :class="{ 'active': status === 'all' }">Todas</button>
          <button type="button" @click="status = 'active'; $nextTick(() => $el.closest('form').submit())" 
                  class="sv-filter-tab" :class="{ 'active': status === 'active' }">Activas</button>
          <button type="button" @click="status = 'expired'; $nextTick(() => $el.closest('form').submit())" 
                  class="sv-filter-tab" :class="{ 'active': status === 'expired' }">Vencidas</button>
        </div>

        <div style="flex: 1;"></div>

        <!-- Rango de Fechas -->
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="font-size: 12px; color: var(--sv-gray-500);">Vence entre:</span>
            <input type="date" name="desde" value="{{ request('desde') }}" class="sv-input sv-input--compact" style="width: 140px;" @change="$el.closest('form').submit()">
            <span style="font-size: 12px; color: var(--sv-gray-500);">y</span>
            <input type="date" name="hasta" value="{{ request('hasta') }}" class="sv-input sv-input--compact" style="width: 140px;" @change="$el.closest('form').submit()">
        </div>

        <!-- Botón de Limpiar -->
        <a href="{{ route('polizas.index') }}" class="sv-btn sv-btn--sm sv-btn--outline" title="Limpiar todos los filtros">
          <svg width="14" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.919Z" clip-rule="evenodd" /></svg>
        </a>
    </div>
  </form>

  <!-- Tabla -->
  <div class="sv-table-wrapper">
    <table class="sv-table">
      <thead>
        <tr>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'numero_poliza', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="sv-table-sort">
                No. Póliza
                @if(request('sort') === 'numero_poliza')
                    <svg width="14" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="{{ request('direction') === 'asc' ? 'M11.47 7.72a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 1 1-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 0 1-1.06-1.06l7.5-7.5Z' : 'M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z' }}" clip-rule="evenodd" /></svg>
                @endif
            </a>
          </th>
          <th>Asegurado</th>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'ramo', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="sv-table-sort">
                Ramo
                @if(request('sort') === 'ramo')
                    <svg width="14" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="{{ request('direction') === 'asc' ? 'M11.47 7.72a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 1 1-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 0 1-1.06-1.06l7.5-7.5Z' : 'M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z' }}" clip-rule="evenodd" /></svg>
                @endif
            </a>
          </th>
          <th>Aseguradora</th>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'fecha_fin', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="sv-table-sort">
                Vencimiento
                @if(request('sort') === 'fecha_fin' || !request('sort'))
                    <svg width="14" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="{{ request('direction', 'desc') === 'asc' ? 'M11.47 7.72a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 1 1-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 0 1-1.06-1.06l7.5-7.5Z' : 'M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z' }}" clip-rule="evenodd" /></svg>
                @endif
            </a>
          </th>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'prima_total', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="sv-table-sort">
                Prima
                @if(request('sort') === 'prima_total')
                    <svg width="14" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="{{ request('direction') === 'asc' ? 'M11.47 7.72a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 1 1-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 0 1-1.06-1.06l7.5-7.5Z' : 'M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z' }}" clip-rule="evenodd" /></svg>
                @endif
            </a>
          </th>
          @if(auth()->user()->role === 'admin') <th>Agente</th> @endif
          <th>Status</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($polizas as $poliza)
        <tr class="sv-table__row">
          <td><span class="sv-mono">{{ $poliza->numero_poliza }}</span></td>
          <td>
            <div class="sv-table__user">
              <div class="sv-table__avatar">{{ strtoupper(substr($poliza->asegurado->nombre, 0, 2)) }}</div>
              <span>{{ $poliza->asegurado->nombre }}</span>
            </div>
          </td>
          <td>
            <span class="sv-badge @if($poliza->ramo === 'Autos') sv-badge--ramo-autos @elseif($poliza->ramo === 'GMM') sv-badge--ramo-gmm @else sv-badge--ramo-danos @endif">
              {{ $poliza->ramo }}
            </span>
          </td>
          <td>{{ $poliza->aseguradora->nombre }}</td>
          <td>{{ $poliza->fecha_fin->format('d/m/Y') }}</td>
          <td class="sv-mono" style="font-weight: 700; color: var(--sv-navy);">${{ number_format($poliza->prima_total, 2) }}</td>
          @if(auth()->user()->role === 'admin') 
          <td>
            <div style="display: flex; align-items: center; gap: 8px;">
                <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--sv-gray-100); color: var(--sv-gray-600); display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700;">{{ strtoupper(substr($poliza->user->name, 0, 1)) }}</div>
                <span class="sv-tag sv-tag--outline" style="font-size: 11px;">{{ $poliza->user->name }}</span>
            </div>
          </td> 
          @endif
          <td>
            @php $isExpired = $poliza->fecha_fin->isPast(); @endphp
            <span class="sv-badge {{ !$poliza->is_active ? 'sv-badge--gray' : ($isExpired ? 'sv-badge--expired' : 'sv-badge--active') }}">
              {{ !$poliza->is_active ? 'Inactiva' : ($isExpired ? 'Vencida' : 'Activa') }}
            </span>
          </td>
          <td>
            <div class="sv-table__actions" style="display: flex; align-items: center; gap: 8px;">
             <form action="{{ route('polizas.toggle-active', $poliza->id) }}" method="POST" style="margin: 0;">
                @csrf
                <button type="submit" class="sv-action-btn" style="color: {{ $poliza->is_active ? 'var(--sv-gold)' : 'var(--sv-gray-400)' }}; background: transparent; border: none; padding: 0; cursor: pointer; display: flex;" title="{{ $poliza->is_active ? 'Desactivar Póliza' : 'Activar Póliza' }}">
                   @if($poliza->is_active)
                   <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                     <rect x="2" y="6" width="20" height="12" rx="6" fill="currentColor" fill-opacity="0.2" stroke="transparent"/>
                     <circle cx="16" cy="12" r="4" fill="currentColor" />
                   </svg>
                   @else
                   <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                     <rect x="2" y="6" width="20" height="12" rx="6" />
                     <circle cx="8" cy="12" r="4" fill="currentColor" />
                   </svg>
                   @endif
                </button>
             </form>
              <a href="{{ route('polizas.show', $poliza) }}" class="sv-action-btn" title="Ver detalle">
                <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd"/></svg>
              </a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="10" style="padding: 60px; text-align: center;">
            <div style="color: var(--sv-gray-300); margin-bottom: 12px;">
                <svg width="48" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 6a3 3 0 0 0-6 0v.375c0 .103.084.187.188.187h5.624a.187.187 0 0 0 .188-.187V6Z"/><path fill-rule="evenodd" d="M7.5 4.875V6A4.5 4.5 0 0 0 3 10.5v8.625C3 20.433 4.067 21.5 5.375 21.5h13.25c1.308 0 2.375-1.067 2.375-2.375V10.5A4.5 4.5 0 0 0 16.5 6V4.875c0-1.45-1.175-2.625-2.625-2.625h-3.75C8.675 2.25 7.5 3.425 7.5 4.875ZM4.5 10.5a3 3 0 0 1 3-3h9a3 3 0 0 1 3 3v8.625c0 .483-.392.875-.875.875H5.375a.875.875 0 0 1-.875-.875V10.5Z" clip-rule="evenodd"/></svg>
            </div>
            <p style="color: var(--sv-gray-500); font-weight: 500;">No se encontraron pólizas con los criterios seleccionados.</p>
            <a href="{{ route('polizas.index') }}" class="sv-btn sv-btn--sm sv-btn--outline" style="margin-top: 12px;">Ver todas las pólizas</a>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Paginación -->
  <div class="sv-table-footer">
    <span class="sv-table-footer__info">
      Mostrando {{ $polizas->firstItem() ?? 0 }}–{{ $polizas->lastItem() ?? 0 }} de {{ $polizas->total() }} pólizas
    </span>
    <div class="sv-pagination">
      {{ $polizas->links() }}
    </div>
  </div>
</div>

<style>
.sv-filters-bar { padding: 16px; background: var(--sv-gray-50); border-bottom: 1px solid var(--sv-gray-200); }
.sv-filters-bar__top { display: flex; gap: 12px; }
.sv-table-sort { display: flex; align-items: center; gap: 4px; color: inherit; text-decoration: none; transition: color 0.2s; }
.sv-table-sort:hover { color: var(--sv-gold-dark); }
.sv-input--compact { padding: 4px 8px; font-size: 12px; }
</style>

@endsection
