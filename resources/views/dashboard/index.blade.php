@extends('layouts.app')

@section('title', 'Dashboard — Seguros Vega')
@section('page-title', 'Resumen General')

@section('content')

<!-- ══ KPI CARDS ═══════════════════════════════════════════ -->
<div class="sv-kpi-grid">
  <!-- (Mismas tarjetas que antes) -->
  <div class="sv-kpi-card">
    <div class="sv-kpi-card__icon sv-kpi-card__icon--navy">
      <svg width="24" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625Z" clip-rule="evenodd"/></svg>
    </div>
    <div class="sv-kpi-card__body">
      <span class="sv-kpi-card__label">Total Pólizas</span>
      <span class="sv-kpi-card__value">{{ $kpis['total'] }}</span>
      <span class="sv-kpi-card__trend sv-kpi-card__trend--up">Registradas</span>
    </div>
  </div>

  <div class="sv-kpi-card">
    <div class="sv-kpi-card__icon sv-kpi-card__icon--green">
      <svg width="24" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12.516 2.17a.75.75 0 0 0-1.032 0 11.209 11.209 0 0 1-7.877 3.08.75.75 0 0 0-.722.515A12.74 12.74 0 0 0 2.25 9.75c0 5.942 4.064 10.933 9.563 12.348a.749.749 0 0 0 .374 0c5.499-1.415 9.563-6.406 9.563-12.348 0-1.39-.223-2.73-.635-3.985a.75.75 0 0 0-.722-.516l-.143.001c-2.996 0-5.717-1.17-7.734-3.08Z" clip-rule="evenodd"/></svg>
    </div>
    <div class="sv-kpi-card__body">
      <span class="sv-kpi-card__label">Activas</span>
      <span class="sv-kpi-card__value">{{ $kpis['activas'] }}</span>
      <span class="sv-kpi-card__trend sv-kpi-card__trend--up">{{ $kpis['total'] > 0 ? round(($kpis['activas']/$kpis['total'])*100) : 0 }}%</span>
    </div>
  </div>

  <div class="sv-kpi-card">
    <div class="sv-kpi-card__icon sv-kpi-card__icon--red">
      <svg width="24" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/></svg>
    </div>
    <div class="sv-kpi-card__body">
      <span class="sv-kpi-card__label">Vencidas</span>
      <span class="sv-kpi-card__value">{{ $kpis['vencidas'] }}</span>
      <span class="sv-kpi-card__trend sv-kpi-card__trend--down">Pendientes</span>
    </div>
  </div>

  <div class="sv-kpi-card">
    <div class="sv-kpi-card__icon sv-kpi-card__icon--gold">
      <svg width="24" viewBox="0 0 24 24" fill="currentColor"><path d="M10.464 8.746c.227-.18.497-.311.786-.394v2.795a2.252 2.252 0 0 1-.786-.393c-.394-.313-.546-.681-.546-1.004 0-.323.152-.691.546-1.004ZM12.75 15.662v-2.824c.347.085.664.228.921.421.427.32.579.686.579.991 0 .305-.152.671-.579.991a2.534 2.534 0 0 1-.921.42Z"/><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v.816a3.836 3.836 0 0 0-1.72.756c-.712.566-1.112 1.35-1.112 2.178 0 .829.4 1.612 1.113 2.178.502.4 1.102.647 1.719.756v2.978a2.536 2.536 0 0 1-.921-.421l-.879-.66a.75.75 0 0 0-.9 1.2l.879.66c.533.4 1.169.645 1.821.75V18a.75.75 0 0 0 1.5 0v-.81a4.124 4.124 0 0 0 1.821-.749c.745-.559 1.179-1.344 1.179-2.191 0-.847-.434-1.632-1.179-2.191a4.122 4.122 0 0 0-1.821-.75V8.354c.29.082.559.213.786.393l.415.33a.75.75 0 0 0 .933-1.175l-.415-.33a3.836 3.836 0 0 0-1.719-.755V6Z" clip-rule="evenodd"/></svg>
    </div>
    <div class="sv-kpi-card__body">
      <span class="sv-kpi-card__label">Nuevas (Mes)</span>
      <span class="sv-kpi-card__value">{{ $kpis['nuevas_mes'] }}</span>
      <span class="sv-kpi-card__trend sv-kpi-card__trend--up">Crecimiento</span>
    </div>
  </div>
</div>

<!-- ══ RECIENTES ══════════════════════════════════════════ -->
<div class="sv-card" style="margin-top: 28px;">
  <div class="sv-card__header">
    <div class="sv-card__header-left">
      <h3 class="sv-card__title">Actividad Reciente</h3>
      <p class="sv-card__subtitle">Últimas 5 pólizas registradas</p>
    </div>
    <div class="sv-card__header-right" style="display: flex; gap: 12px;">
      <a href="{{ route('polizas.index') }}" class="sv-btn sv-btn--outline sv-btn--sm">Ver todas</a>
      <a href="{{ route('polizas.create') }}" class="sv-btn sv-btn--primary sv-btn--sm">Nueva Póliza</a>
    </div>
  </div>

  <div class="sv-table-wrapper">
    <table class="sv-table">
      <thead>
        <tr>
          <th>No. Póliza</th>
          <th>Asegurado</th>
          <th>Ramo</th>
          <th>Vencimiento</th>
          <th>Status</th>
          <th style="text-align: right;">Acciones</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentPolizas as $poliza)
        <tr class="sv-table__row">
          <td><span class="sv-mono">{{ $poliza->numero_poliza }}</span></td>
          <td>{{ $poliza->asegurado->nombre }}</td>
          <td>{{ $poliza->ramo }}</td>
          <td>{{ $poliza->fecha_fin->format('d/m/Y') }}</td>
          <td>
            @php $isExpired = $poliza->fecha_fin->isPast(); @endphp
            <span class="sv-badge {{ $isExpired ? 'sv-badge--expired' : 'sv-badge--active' }}">
              {{ $isExpired ? 'Vencida' : 'Activa' }}
            </span>
          </td>
          <td style="text-align: right;">
            <a href="{{ route('polizas.show', $poliza) }}" class="sv-action-btn" title="Ver detalle">
              <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd"/></svg>
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="padding: 24px; text-align: center; color: var(--sv-gray-500);">No hay actividad reciente.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
