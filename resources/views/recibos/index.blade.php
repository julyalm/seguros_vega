@extends('layouts.app')

@section('title', 'Listado de Recibos — Seguros Vega')
@section('page-title', 'Gestión de Recibos')

@section('content')

<!-- ══ TABLA DE RECIBOS COMPLETA ══════════════════════════════ -->
<div class="sv-card" x-data="{ status: '{{ request('status', 'all') }}', is_active: '{{ request('is_active', 'all') }}' }">
  <div class="sv-card__header">
    <div class="sv-card__header-left">
      <h3 class="sv-card__title">Catálogo de Recibos</h3>
      <p class="sv-card__subtitle">Listado, filtros y gestión de pagos</p>
    </div>
  </div>

  <!-- Filtros Avanzados -->
  <form action="{{ route('recibos.index') }}" method="GET" class="sv-filters-bar" id="filterForm">

    <div class="sv-filters-bar__top" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <!-- Búsqueda -->
        <div class="sv-search-input" style="flex: 2; min-width: 250px;">
          <svg width="16" viewBox="0 0 24 24" fill="currentColor" class="sv-search-input__icon">
            <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd"/>
          </svg>
          <input type="text" name="search" id="recibos-search" value="{{ request('search') }}"
                 placeholder="Buscar asegurado, póliza o no. de serie (Autos)..." class="sv-search-input__field" style="padding: 6px 6px 6px 32px; font-size: 13px;">
        </div>

        <!-- Filtro de estado visual -->
        <input type="hidden" name="status" x-model="status">
        <div class="sv-filter-tabs" style="margin: 0;">
          <button type="button" @click="status = 'all'; $nextTick(() => $el.closest('form').submit())"
                  class="sv-filter-tab" style="padding: 6px 12px; font-size: 12px;" :class="{ 'active': status === 'all' }">Todos</button>
          <button type="button" @click="status = 'pendiente'; $nextTick(() => $el.closest('form').submit())"
                  class="sv-filter-tab" style="padding: 6px 12px; font-size: 12px;" :class="{ 'active': status === 'pendiente' }">Pendientes</button>
          <button type="button" @click="status = 'pagado'; $nextTick(() => $el.closest('form').submit())"
                  class="sv-filter-tab" style="padding: 6px 12px; font-size: 12px;" :class="{ 'active': status === 'pagado' }">Pagados</button>
          <button type="button" @click="status = 'vencido'; $nextTick(() => $el.closest('form').submit())"
                  class="sv-filter-tab" style="padding: 6px 12px; font-size: 12px;" :class="{ 'active': status === 'vencido' }">Vencidos</button>
        </div>

        <!-- Fechas y Activo -->
        <div style="display: flex; align-items: center; gap: 8px;">
            <select name="is_active" class="sv-select sv-select--compact" x-model="is_active" @change="$el.closest('form').submit()">
              <option value="all">Filtro: Activos / Inac.</option>
              <option value="yes">Activos</option>
              <option value="no">Inactivos</option>
            </select>

            <span style="font-size: 11px; color: var(--sv-gray-500); margin-left: 4px;">Vence:</span>
            <input type="date" name="desde" value="{{ request('desde') }}" class="sv-input sv-input--compact" style="width: 110px;" @change="$el.closest('form').submit()">
            <span style="font-size: 11px; color: var(--sv-gray-500);">a</span>
            <input type="date" name="hasta" value="{{ request('hasta') }}" class="sv-input sv-input--compact" style="width: 110px;" @change="$el.closest('form').submit()">

            <!-- Botón de Limpiar -->
            <a href="{{ route('recibos.index') }}" class="sv-btn sv-btn--sm sv-btn--outline" style="padding: 4px 8px; height: 28px;" title="Limpiar todos los filtros">
              <svg width="14" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.919Z" clip-rule="evenodd" /></svg>
            </a>

            <!-- Botón Exportar -->
            <button type="submit" name="export" value="excel" class="sv-btn sv-btn--sm sv-btn--outline" style="height: 28px; padding: 4px 12px; gap: 4px; font-size: 12px; border-color: var(--sv-navy); color: var(--sv-navy);" title="Descargar como Excel (CSV)">
              <svg width="14" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd"/></svg>
              Exportar Excel
            </button>
        </div>
    </div>
  </form>

  @if(session('success'))
    <div style="margin: 16px; padding: 12px; background: #e6f6ee; color: #0d8343; border-radius: 6px; font-weight: 500; font-size: 13px;">
      {{ session('success') }}
    </div>
  @endif

  <!-- Tabla -->
  <div class="sv-table-wrapper" x-data="{
      editModalOpen: false,
      editUrl: '',
      editStatus: '',
      editContracargo: 0,

      openEditModal(url, status, contracargo) {
          this.editUrl = url;
          this.editStatus = status;
          this.editContracargo = contracargo;
          this.editModalOpen = true;
      }
  }">
    <table class="sv-table">
      <thead>
        <tr>
          <th style="padding: 10px 8px;">Póliza</th>
          <th style="padding: 10px 8px;">Asegurado</th>
          <th style="padding: 10px 8px;">Recibo</th>
          <th style="padding: 10px 8px;">Inicio</th>
          <th style="padding: 10px 8px;">Fin</th>
          <th style="padding: 10px 8px;">
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'fecha_vencimiento', 'direction' => request('direction', 'asc') === 'asc' ? 'desc' : 'asc']) }}" class="sv-table-sort">
                Vencimiento
                @if(request('sort', 'fecha_vencimiento') === 'fecha_vencimiento')
                    <svg width="14" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="{{ request('direction', 'asc') === 'asc' ? 'M11.47 7.72a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 1 1-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 0 1-1.06-1.06l7.5-7.5Z' : 'M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z' }}" clip-rule="evenodd" /></svg>
                @endif
            </a>
          </th>
          <th>
            <a href="{{ request()->fullUrlWithQuery(['sort' => 'monto', 'direction' => request('direction') === 'asc' ? 'desc' : 'asc']) }}" class="sv-table-sort">
                Monto
                @if(request('sort') === 'monto')
                    <svg width="14" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="{{ request('direction') === 'asc' ? 'M11.47 7.72a.75.75 0 0 1 1.06 0l7.5 7.5a.75.75 0 1 1-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 0 1-1.06-1.06l7.5-7.5Z' : 'M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z' }}" clip-rule="evenodd" /></svg>
                @endif
            </a>
          </th>
          <th style="padding: 10px 8px;">C. Cargo</th>
          <th>Estatus</th>
          <th>Activo</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="recibos-tbody">
        @forelse($recibos as $recibo)
        <tr class="sv-table__row {{ !$recibo->is_active ? 'opacity-50' : '' }}" style="{{ !$recibo->is_active ? 'opacity: 0.6; background-color: #fafafa;' : '' }}">
          <td>
            <span class="sv-mono">{{ $recibo->poliza->numero_poliza }}</span>
            @if(request('search'))
              @php
                $searchTerm = strtolower(request('search'));
                $matchedVin = $recibo->poliza->vehiculos
                  ->first(fn($v) => str_contains(strtolower($v->vin ?? ''), $searchTerm));
              @endphp
              @if($matchedVin)
                <div style="margin-top: 4px;">
                  <span class="sv-vin-match-badge">
                    <svg width="10" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 1 1 6 0h3a.75.75 0 0 0 .75-.75V15Z"/><path d="M8.25 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0ZM15.75 6.75a.75.75 0 0 0-.75.75v11.25c0 .087.015.17.042.248a3 3 0 0 1 5.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 0 0-3.732-10.104 1.837 1.837 0 0 0-1.47-.725H15.75Z"/><path d="M19.5 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/></svg>
                    Serie: {{ strtoupper($matchedVin->vin) }}
                  </span>
                </div>
              @endif
            @endif
          </td>
          <td>
            <div class="sv-table__user">
              <div class="sv-table__avatar" style="width: 24px; height: 24px; font-size: 9px;">{{ strtoupper(substr($recibo->poliza->asegurado->nombre, 0, 2)) }}</div>
              <span style="font-size: 13px;">{{ $recibo->poliza->asegurado->nombre }}</span>
            </div>
          </td>
          <td style="text-align: center;"><span class="sv-tag sv-tag--gray">{{ $recibo->indice_recibo }}</span></td>
          <td>{{ $recibo->fecha_inicio_vigencia ? $recibo->fecha_inicio_vigencia->format('d/m/Y') : '-' }}</td>
          <td>{{ $recibo->fecha_fin_vigencia ? $recibo->fecha_fin_vigencia->format('d/m/Y') : '-' }}</td>
          <td>
            <span class="sv-mono {{ $recibo->status_display === 'vencido' ? 'text-red-600 font-bold' : '' }}">
                {{ $recibo->fecha_vencimiento ? $recibo->fecha_vencimiento->format('d/m/Y') : '-' }}
            </span>
          </td>
          <td class="sv-mono" style="font-weight: 700; color: var(--sv-navy);">${{ number_format($recibo->monto, 2) }}</td>
          <td class="sv-mono text-red-600">${{ number_format($recibo->contracargo, 2) }}</td>
          <td>
            <span class="sv-badge
              @if($recibo->status_display === 'pagado') sv-badge--active
              @elseif($recibo->status_display === 'vencido') sv-badge--expired
              @else sv-badge--gray @endif">
              {{ ucfirst($recibo->status_display) }}
            </span>
          </td>
          <td>
            <!-- Toggle Active Switch -->
             <form action="{{ route('recibos.toggle-active', $recibo->id) }}" method="POST">
                @csrf
                <button type="submit" class="sv-btn sv-btn--sm" style="padding: 4px; background: transparent; border: none; cursor: pointer; color: {{ $recibo->is_active ? 'var(--sv-gold)' : 'var(--sv-gray-400)' }}" title="{{ $recibo->is_active ? 'Desactivar Recibo' : 'Activar Recibo' }}">
                   @if($recibo->is_active)
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
          </td>
          <td>
            <div class="sv-table__actions">
              <button
                @click="openEditModal('{{ route('recibos.status', $recibo->id) }}', '{{ $recibo->status === 'vencido' ? 'pendiente' : $recibo->status }}', {{ $recibo->contracargo }})"
                class="sv-action-btn"
                title="Modificar Estatus y Contracargo">
                <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.158 3.712 3.712 1.158-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/><path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/></svg>
              </button>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="11" style="padding: 60px; text-align: center;">
            <div style="color: var(--sv-gray-300); margin-bottom: 12px;">
                <svg width="48" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 4.125c0-1.036.84-1.875 1.875-1.875h15.75c1.036 0 1.875.84 1.875 1.875V17.382l-2.073-1.036a1.5 1.5 0 0 0-1.343.08l-2.583 1.55-2.583-1.55a1.5 1.5 0 0 0-1.55 0l-2.583 1.55-2.583-1.55a1.5 1.5 0 0 0-1.344-.08L2.25 17.382V4.125ZM12 9.497a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H12.75a.75.75 0 0 1-.75-.75Zm0 3.75a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 0 1.5h-2.25a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/></svg>
            </div>
            <p style="color: var(--sv-gray-500); font-weight: 500;">No se encontraron recibos.</p>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>

    <!-- Modal: Edición de Recibo -->
    <div x-show="editModalOpen" x-cloak class="sv-modal-backdrop">
        <div class="sv-modal --width-sm" @click.outside="editModalOpen = false" @keydown.escape.window="editModalOpen = false">
            <div class="sv-modal__header">
                <h3 class="sv-modal__title">Actualizar Recibo</h3>
                <button type="button" @click="editModalOpen = false" class="sv-modal__close">
                    <svg width="20" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div class="sv-modal__body">
                <form :action="editUrl" method="POST" class="sv-form-stack">
                    @csrf
                    @method('PUT')
                    <div class="sv-form-group">
                        <label class="sv-form-label">Nuevo Estatus</label>
                        <select name="status" x-model="editStatus" class="sv-select">
                            <option value="pendiente">Pendiente (Calcula Vencimiento Automático)</option>
                            <option value="pagado">Pagado</option>
                            <option value="vencido">Vencido (Forzado)</option>
                        </select>
                    </div>
                    <div class="sv-form-group">
                        <label class="sv-form-label">Monto de Contracargo</label>
                        <div class="sv-input-with-icon">
                            <span>$</span>
                            <input type="number" step="0.01" min="0" name="contracargo" x-model="editContracargo" class="sv-input">
                        </div>
                    </div>
                    <div class="sv-modal__footer">
                        <button type="button" @click="editModalOpen = false" class="sv-btn sv-btn--outline">Cancelar</button>
                        <button type="submit" class="sv-btn sv-btn--primary">Actualizar Recibo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
  </div>

  <!-- Paginación -->
  <div class="sv-table-footer">
    <span class="sv-table-footer__info" id="recibos-count">
      Mostrando {{ $recibos->firstItem() ?? 0 }}–{{ $recibos->lastItem() ?? 0 }} de {{ $recibos->total() }} recibos
    </span>
    <div class="sv-pagination" id="recibos-pagination">
      {{ $recibos->links() }}
    </div>
  </div>
</div>

<style>
.sv-filters-bar { padding: 12px 16px; background: var(--sv-gray-50); border-bottom: 1px solid var(--sv-gray-200); }
.sv-table th { padding: 10px 8px !important; font-size: 11px !important; letter-spacing: 0.5px !important; }
.sv-table td { padding: 10px 8px !important; font-size: 13px !important; }
.sv-table-sort { display: flex; align-items: center; gap: 4px; color: inherit; text-decoration: none; transition: color 0.2s; }
.sv-table-sort:hover { color: var(--sv-gold-dark); }
.sv-input--compact { padding: 4px 8px; font-size: 12px; height: 28px; line-height: 1; }
.sv-select--compact { padding: 4px 24px 4px 8px; font-size: 12px; height: 28px; }
.text-red-600 { color: #dc2626; }
.font-bold { font-weight: 700; }
.opacity-50 { opacity: 0.5; }
[x-cloak] { display: none !important; }

.sv-vin-match-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #fffbeb;
    border: 1px solid #fbbf24;
    color: #92400e;
    font-size: 10px;
    font-weight: 700;
    font-family: 'Courier New', monospace;
    padding: 2px 7px;
    border-radius: 20px;
    letter-spacing: 0.3px;
    white-space: nowrap;
}

/* ── MODALS ──────────────────────────────────────────────── */
.sv-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 57, 115, 0.4);
    backdrop-filter: blur(8px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: fadeIn 0.3s ease;
}
.sv-modal {
    background: white;
    border-radius: 24px;
    width: 600px;
    max-width: 100%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    border: 1px solid #e2e8f0;
}
.sv-modal.--width-sm { width: 420px; }
.sv-modal.--width-md { width: 720px; }
.sv-modal.--width-lg { width: 900px; }

.sv-modal__header {
    padding: 24px 32px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.sv-modal__title {
    margin: 0;
    font-family: 'Rajdhani', sans-serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--sv-navy);
}
.sv-modal__close {
    background: #f1f5f9;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--sv-gray-500);
    cursor: pointer;
    transition: all 0.2s;
}
.sv-modal__close:hover { background: #e2e8f0; color: var(--sv-navy); transform: rotate(90deg); }

.sv-modal__body { padding: 32px; overflow-y: auto; }
.sv-modal__body.--scrollable { max-height: 60vh; }
.sv-modal__footer {
    padding: 24px 32px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 16px;
}

/* ── FORM STYLES ─────────────────────────────────────────── */
.sv-form-stack { display: flex; flex-direction: column; gap: 20px; }
.sv-form-row { display: flex; gap: 20px; }
@media (max-width: 600px) { .sv-form-row { flex-direction: column; gap: 20px; } }
.sv-form-row > * { flex: 1; }

.sv-form-group { display: flex; flex-direction: column; gap: 8px; }
.sv-form-label {
    font-size: 13px;
    font-weight: 700;
    color: var(--sv-gray-600);
}
.sv-input, .sv-select {
    padding: 12px 16px;
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    font-size: 14px;
    transition: all 0.2s;
    background: #fff;
}
.sv-input:focus, .sv-select:focus {
    border-color: var(--sv-gold);
    box-shadow: 0 0 0 4px rgba(213,164,64,0.1);
    outline: none;
}

.sv-input-with-icon { position: relative; }
.sv-input-with-icon span {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--sv-gray-400);
    font-weight: 700;
    pointer-events: none;
}
.sv-input-with-icon input { padding-left: 32px; width: 100%; }

/* ── ANIMATIONS ──────────────────────────────────────────── */
@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
</style>

@endsection

@push('scripts')
<script>
(function () {
    const input      = document.getElementById('recibos-search');
    const tbody      = document.getElementById('recibos-tbody');
    const pagination = document.getElementById('recibos-pagination');
    const counter    = document.getElementById('recibos-count');
    const form       = document.getElementById('filterForm');
    let   timer      = null;
    let   controller = null; // AbortController para cancelar peticiones en vuelo

    function buildParams() {
        const data = new FormData(form);
        data.set('search', input.value);   // usar el valor actual del input
        data.set('partial', '1');
        return new URLSearchParams(data);
    }

    async function doSearch() {
        if (controller) controller.abort();
        controller = new AbortController();

        // Indicador visual de carga
        tbody.style.opacity = '0.4';

        try {
            const res  = await fetch(`{{ route('recibos.index') }}?${buildParams()}`, {
                signal: controller.signal,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();

            // Actualizar filas
            tbody.innerHTML = data.rows;
            tbody.style.opacity = '1';

            // Reinicializar Alpine en el nuevo contenido para que funcionen los @click
            if (typeof Alpine !== 'undefined') Alpine.initTree(tbody);

            // Actualizar paginación
            pagination.innerHTML = data.pagination;

            // Actualizar contador
            counter.textContent = `Mostrando ${data.from}–${data.to} de ${data.total} recibos`;

            // Actualizar la URL sin recargar (para que el botón Limpiar funcione bien)
            const url = new URL(window.location);
            if (input.value) { url.searchParams.set('search', input.value); }
            else             { url.searchParams.delete('search'); }
            history.replaceState({}, '', url);

        } catch (e) {
            if (e.name !== 'AbortError') tbody.style.opacity = '1';
        }
    }

    input.addEventListener('input', function () {
        clearTimeout(timer);
        timer = setTimeout(doSearch, 350);
    });
})();
</script>
@endpush
