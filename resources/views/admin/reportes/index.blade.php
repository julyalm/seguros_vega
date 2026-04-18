@extends('layouts.app')

@section('title', 'Reportes — Seguros Vega')
@section('page-title', 'Inteligencia y Operaciones')

@section('content')

<div x-data="{ view: 'visual' }" class="sv-reports-container">
  
  <!-- ══ ENCABEZADO Y TOGGLE ════════════════════════════════ -->
  <div class="sv-card" style="margin-bottom: 24px;">
    <div class="sv-card__header">
      <div class="sv-card__header-left">
        <h3 class="sv-card__title">Panel de Reportes</h3>
        <p class="sv-card__subtitle">Visualiza el rendimiento o descarga el corte detallado</p>
      </div>
      <div class="sv-view-toggle">
        <button @click="view = 'visual'" :class="{ 'active': view === 'visual' }" class="sv-view-toggle__btn">
          <svg width="18" viewBox="0 0 24 24" fill="currentColor" style="margin-right:8px"><path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75ZM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 0 1-1.875-1.875V8.625ZM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 0 1 3 19.875v-6.75Z" /></svg>
          Vista Visual
        </button>
        <button @click="view = 'data'" :class="{ 'active': view === 'data' }" class="sv-view-toggle__btn">
          <svg width="18" viewBox="0 0 24 24" fill="currentColor" style="margin-right:8px"><path fill-rule="evenodd" d="M1.5 6a2.25 2.25 0 0 1 2.25-2.25h16.5A2.25 2.25 0 0 1 22.5 6v12a2.25 2.25 0 0 1-2.25 2.25H3.75A2.25 2.25 0 0 1 1.5 18V6ZM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0 0 21 18v-1.94l-2.69-2.689a1.5 1.5 0 0 0-2.12 0l-.88.879.97.97a.75.75 0 1 1-1.06 1.06l-5.16-5.159a1.5 1.5 0 0 0-2.12 0L3 16.061Zm10.125-7.81a1.125 1.125 0 1 1 2.25 0 1.125 1.125 0 0 1-2.25 0Z" clip-rule="evenodd" /></svg>
          Corte de Caja
        </button>
      </div>
    </div>
  </div>

  <!-- ══ VISTA VISUAL (GRÁFICOS) ═══════════════════════════ -->
  <div x-show="view === 'visual'" x-transition class="sv-reports-visual">
    <div class="sv-reports-grid">
      
      <!-- Gráfico de Dona: Por Ramo -->
      <div class="sv-card">
        <div class="sv-card__header">
          <h4 class="sv-card__title">Participación por Ramo</h4>
        </div>
        <div class="sv-card__body" style="height: 300px;">
          <canvas id="ramoChart"></canvas>
        </div>
      </div>

      <!-- Gráfico de Barras: Top Agentes -->
      <div class="sv-card">
        <div class="sv-card__header">
          <h4 class="sv-card__title">Producción por Agente (Primas)</h4>
        </div>
        <div class="sv-card__body" style="height: 300px;">
          <canvas id="agentChart"></canvas>
        </div>
      </div>

      <!-- Gráfico de Líneas: Tendencia Mensual -->
      <div class="sv-card sv-card--full" style="grid-column: span 2;">
        <div class="sv-card__header">
          <h4 class="sv-card__title">Evolución de Primas ({{ date('Y') }})</h4>
        </div>
        <div class="sv-card__body" style="height: 350px;">
          <canvas id="trendChart"></canvas>
        </div>
      </div>

    </div>
  </div>

  <!-- ══ VISTA DE DATOS (CORTE) ════════════════════════════ -->
  <div x-show="view === 'data'" x-transition class="sv-reports-data">
    <div class="sv-card">
        <div class="sv-card__header">
            <div class="sv-card__header-left">
                <h3 class="sv-card__title">Detalle de Operaciones</h3>
                <p class="sv-card__subtitle">Registro cronológico de emisiones y primas</p>
            </div>
            <button class="sv-btn sv-btn--outline" onclick="alert('Exportando a CSV...')">
                Descargar CSV
            </button>
        </div>

        <div class="sv-table-wrapper">
            <table class="sv-table">
                <thead>
                    <tr>
                        <th>Fecha</th>
                        <th>Póliza</th>
                        <th>Asegurado</th>
                        <th>Ramo</th>
                        <th>Agente</th>
                        <th>Prima Total</th>
                        <th>Comisión (Est.)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($operaciones as $op)
                    <tr class="sv-table__row">
                        <td>{{ $op->created_at->format('d/m/Y') }}</td>
                        <td><span class="sv-mono">{{ $op->numero_poliza }}</span></td>
                        <td>{{ $op->asegurado->nombre }}</td>
                        <td><span class="sv-badge">{{ $op->ramo }}</span></td>
                        <td>{{ $op->user->name }}</td>
                        <td class="sv-mono">${{ number_format($op->prima_total, 2) }}</td>
                        <td class="sv-mono" style="color: var(--sv-green-600)">${{ number_format($op->prima_total * 0.10, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot style="background: var(--sv-navy-light); font-weight: 700;">
                    <tr>
                        <td colspan="5" style="text-align: right; padding: 16px;">TOTALES ({{ $totales['conteo'] }} pólizas):</td>
                        <td class="sv-mono">${{ number_format($totales['prima'], 2) }}</td>
                        <td class="sv-mono" style="color: var(--sv-green-600)">${{ number_format($totales['comision'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
  </div>

</div>

<style>
/* Estilos específicos para el toggle de reportes */
.sv-view-toggle { background: var(--sv-gray-100); padding: 4px; border-radius: 12px; display: flex; gap: 4px; }
.sv-view-toggle__btn { 
    border: none; background: none; padding: 8px 16px; border-radius: 8px; 
    font-family: 'DM Sans'; font-weight: 500; font-size: 14px; 
    cursor: pointer; color: var(--sv-gray-500); display: flex; align-items: center;
    transition: all 0.2s;
}
.sv-view-toggle__btn.active { background: white; color: var(--sv-navy); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
.sv-view-toggle__btn:hover:not(.active) { background: var(--sv-gray-200); }

.sv-reports-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 24px; }
@media (max-width: 1024px) { .sv-reports-grid { grid-template-columns: 1fr; } .sv-card--full { grid-column: span 1 !important; } }
</style>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // Configuración común
    Chart.defaults.font.family = "'DM Sans', sans-serif";
    Chart.defaults.color = '#64748b';

    // 1. Gráfico de Ramo
    new Chart(document.getElementById('ramoChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode($ramoStats->pluck('ramo')) !!},
            datasets: [{
                data: {!! json_encode($ramoStats->pluck('count')) !!},
                backgroundColor: ['#003973', '#D5A440', '#10b981', '#ef4444'],
                borderWidth: 0
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } }
        }
    });

    // 2. Gráfico de Agentes
    new Chart(document.getElementById('agentChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($agentStats->pluck('name')) !!},
            datasets: [{
                label: 'Primas Emitidas ($)',
                data: {!! json_encode($agentStats->pluck('polizas_sum_prima_total')) !!},
                backgroundColor: '#D5A440',
                borderRadius: 6
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });

    // 3. Gráfico de Tendencia
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyStats, 'name')) !!},
            datasets: [{
                label: 'Venta Mensual ($)',
                data: {!! json_encode(array_column($monthlyStats, 'total')) !!},
                borderColor: '#003973',
                backgroundColor: 'rgba(0, 57, 115, 0.1)',
                fill: true,
                tension: 0.4,
                pointRadius: 4
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
});
</script>
@endpush
