@extends('layouts.app')

@section('title', 'Comisiones y Ventas — Seguros Vega')
@section('page-title', 'Comisiones y Ventas')

@section('content')

<div class="sv-commissions-container">
    
    <!-- ══ BARRA DE FILTROS ════════════════════════════════════ -->
    <div class="sv-card" style="margin-bottom: 24px;">
        <div class="sv-card__body">
            <form action="{{ route('comisiones.index') }}" method="GET" class="sv-filter-grid">
                <div class="sv-form-group">
                    <label class="sv-form-label">Desde</label>
                    <input type="date" name="desde" value="{{ request('desde', now()->startOfYear()->format('Y-m-d')) }}" class="sv-input">
                </div>
                <div class="sv-form-group">
                    <label class="sv-form-label">Hasta</label>
                    <input type="date" name="hasta" value="{{ request('hasta', now()->format('Y-m-d')) }}" class="sv-input">
                </div>
                @if($isAdmin)
                <div class="sv-form-group">
                    <label class="sv-form-label">Agente</label>
                    <select name="agent_id" class="sv-select">
                        <option value="">Todos los Agentes</option>
                        @foreach($agents as $agent)
                            <option value="{{ $agent->id }}" {{ request('agent_id') == $agent->id ? 'selected' : '' }}>
                                {{ $agent->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="sv-form-group">
                    <label class="sv-form-label">Aseguradora</label>
                    <select name="aseguradora_id" class="sv-select">
                        <option value="">Todas las Compañías</option>
                        @foreach($insurers as $ins)
                            <option value="{{ $ins->id }}" {{ request('aseguradora_id') == $ins->id ? 'selected' : '' }}>
                                {{ $ins->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sv-filter-actions">
                    <button type="submit" class="sv-btn sv-btn--primary">Filtrar</button>
                    <a href="{{ route('comisiones.index') }}" class="sv-btn sv-btn--outline">Limpiar</a>
                </div>
            </form>
        </div>
    </div>

    <!-- ══ TARJETAS KPI ═══════════════════════════════════════ -->
    <div class="sv-kpi-grid">
        <div class="sv-kpi-card">
            <div class="sv-kpi-card__icon --blue">
                <svg width="24" viewBox="0 0 24 24" fill="currentColor"><path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" /></svg>
            </div>
            <div class="sv-kpi-card__info">
                <span class="sv-kpi-card__label">Ventas Totales (Periodo)</span>
                <span class="sv-kpi-card__value">${{ number_format($stats['total_ventas'], 2) }}</span>
            </div>
        </div>

        <div class="sv-kpi-card">
            <div class="sv-kpi-card__icon --gold">
                <svg width="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 7.5a2.25 2.25 0 1 0 0 4.5 2.25 2.25 0 0 0 0-4.5Z" /><path fill-rule="evenodd" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v14.25c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 0 1 1.5 19.125V4.875ZM12 17.25a5.25 5.25 0 1 0 0-10.5 5.25 5.25 0 0 0 0 10.5ZM3.75 6.75a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75Zm0 3a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75Zm0 3a.75.75 0 0 0 0 1.5h.75a.75.75 0 0 0 0-1.5h-.75ZM19.5 6.75a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-1.5 0V7.5a.75.75 0 0 1 .75-.75Zm0 3a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-1.5 0v-.75a.75.75 0 0 1 .75-.75Zm0 3a.75.75 0 0 1 .75.75v.75a.75.75 0 0 1-1.5 0v-.75a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" /></svg>
            </div>
            <div class="sv-kpi-card__info">
                <span class="sv-kpi-card__label">Comisiones Totales</span>
                <span class="sv-kpi-card__value sv-text-gold">${{ number_format($stats['total_comisiones'], 2) }}</span>
            </div>
        </div>

        <div class="sv-kpi-card">
            <div class="sv-kpi-card__icon --green">
                <svg width="24" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M19.91 4.105a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.739a.75.75 0 0 1 1.04-.208Z" clip-rule="evenodd" /></svg>
            </div>
            <div class="sv-kpi-card__info">
                <span class="sv-kpi-card__label">Comisiones Pagadas</span>
                <span class="sv-kpi-card__value sv-text-green">${{ number_format($stats['comisiones_pagadas'], 2) }}</span>
            </div>
        </div>

        <div class="sv-kpi-card">
            <div class="sv-kpi-card__icon --red">
                <svg width="24" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" /></svg>
            </div>
            <div class="sv-kpi-card__info">
                <span class="sv-kpi-card__label">Comisiones Pendientes</span>
                <span class="sv-kpi-card__value sv-text-red">${{ number_format($stats['comisiones_pendientes'], 2) }}</span>
            </div>
        </div>
    </div>

    <!-- ══ GRÁFICO DE TENDENCIA ════════════════════════════════ -->
    <div class="sv-card" style="margin-bottom: 24px;">
        <div class="sv-card__header">
            <h3 class="sv-card__title">Tendencia de Ventas y Comisiones</h3>
        </div>
        <div class="sv-card__body" style="height: 350px;">
            <canvas id="commissionTrendChart"></canvas>
        </div>
    </div>

    <!-- ══ TABLA DETALLADA ════════════════════════════════════ -->
    <div class="sv-card">
        <div class="sv-card__header">
            <h3 class="sv-card__title">Desglose de Recibos y Comisiones</h3>
        </div>
        <div class="sv-table-wrapper">
            <table class="sv-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Póliza</th>
                        <th>Asegurado</th>
                        @if($isAdmin) <th>Agente</th> @endif
                        <th>Compañía</th>
                        <th class="sv-text-right">Monto</th>
                        <th class="sv-text-right">Comisión</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recibos as $recibo)
                    <tr class="sv-table__row">
                        <td>{{ $recibo->created_at->format('d/m/Y') }}</td>
                        <td><a href="{{ route('polizas.show', $recibo->poliza->id) }}" class="sv-link sv-mono">{{ $recibo->poliza->numero_poliza }}</a></td>
                        <td>{{ $recibo->poliza->asegurado->nombre }}</td>
                        @if($isAdmin) <td>{{ $recibo->poliza->user->name }}</td> @endif
                        <td>{{ $recibo->poliza->aseguradora->nombre }}</td>
                        <td class="sv-text-right sv-mono">${{ number_format($recibo->monto, 2) }}</td>
                        <td class="sv-text-right sv-mono sv-text-gold --bold">${{ number_format($recibo->comision, 2) }}</td>
                        <td>
                            @php $dispStatus = $recibo->status_display; @endphp
                            <span class="sv-badge {{ $dispStatus === 'pagado' ? 'sv-badge--active' : ($dispStatus === 'vencido' ? 'sv-badge--expired' : 'sv-badge--pending') }}">
                                {{ ucfirst($dispStatus) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="{{ $isAdmin ? 8 : 7 }}" class="sv-table__empty">No se encontraron registros para este periodo.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="sv-card__footer">
            {{ $recibos->links() }}
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    Chart.defaults.font.family = "'DM Sans', sans-serif";
    Chart.defaults.color = '#64748b';

    const ctx = document.getElementById('commissionTrendChart').getContext('2d');
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($monthlyStats, 'name')) !!},
            datasets: [
                {
                    label: 'Ventas ($)',
                    data: {!! json_encode(array_column($monthlyStats, 'ventas')) !!},
                    backgroundColor: 'rgba(0, 57, 115, 0.1)',
                    borderColor: '#003973',
                    borderWidth: 2,
                    type: 'line',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Comisiones ($)',
                    data: {!! json_encode(array_column($monthlyStats, 'comisiones')) !!},
                    backgroundColor: '#D5A440',
                    borderRadius: 4
                }
            ]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return '$' + value.toLocaleString(); }
                    }
                }
            }
        }
    });
});
</script>

<style>
.sv-filter-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 16px; align-items: flex-end; }
.sv-filter-actions { display: flex; gap: 8px; }

.sv-kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 24px; margin-bottom: 24px; }
.sv-kpi-card { 
    background: white; border-radius: 16px; padding: 20px; border: 1px solid #e2e8f0; 
    display: flex; align-items: center; gap: 16px; transition: transform 0.2s;
}
.sv-kpi-card:hover { transform: translateY(-3px); box-shadow: var(--sv-shadow-md); }
.sv-kpi-card__icon { 
    width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; 
}
.sv-kpi-card__icon.--blue { background: #eff6ff; color: #1e40af; }
.sv-kpi-card__icon.--gold { background: #fffbeb; color: #92400e; }
.sv-kpi-card__icon.--green { background: #f0fdf4; color: #166534; }
.sv-kpi-card__icon.--red { background: #fef2f2; color: #991b1b; }

.sv-kpi-card__label { display: block; font-size: 13px; color: var(--sv-gray-500); font-weight: 500; margin-bottom: 4px; }
.sv-kpi-card__value { display: block; font-size: 20px; font-weight: 700; color: var(--sv-navy); font-family: 'Rajdhani'; }

.sv-text-gold { color: #D5A440 !important; }
.sv-text-green { color: #10b981 !important; }
.sv-text-red { color: #ef4444 !important; }
.sv-text-right { text-align: right; }
.--bold { font-weight: 700; }
</style>
@endpush
