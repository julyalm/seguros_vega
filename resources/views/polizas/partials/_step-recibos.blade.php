<div class="sv-form-section">
  <div class="sv-receipt-header">
    <div class="sv-receipt-header__info">
      <h3 class="sv-form-section__title">Plan de Pagos y Recibos</h3>
      <p class="sv-form-section__desc">Configura y personaliza el calendario de cobros para esta póliza.</p>
    </div>
    <div x-show="fechaInicio" class="sv-receipt-header__actions">
      <div class="sv-gracia-selector">
        <label>Días de Gracia</label>
        <div class="sv-gracia-input-wrapper">
          <input type="number" x-model="periodoGracia" @input="recibos.forEach(r => updateReciboDeadline(r))" 
                 class="sv-input-minimal">
          <span class="sv-unit">días</span>
        </div>
      </div>
      <button type="button" class="sv-btn sv-btn--outline sv-btn--sm sv-btn--icon-only" 
              @click="if(confirm('¿Reiniciar todos los recibos?')) initRecibos()" title="Reiniciar Recibos">
        <svg width="18" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M4.755 10.059a7.5 7.5 0 0 1 12.548-3.364l1.903 1.903h-3.183a.75.75 0 1 0 0 1.5h4.992a.75.75 0 0 0 .75-.75V4.356a.75.75 0 0 0-1.5 0v3.18l-1.9-1.9A9 9 0 0 0 3.306 9.67a.75.75 0 1 0 1.45.388Zm15.408 3.352a.75.75 0 0 0-.919.53 7.5 7.5 0 0 1-12.548 3.364l-1.902-1.903h3.183a.75.75 0 0 0 0-1.5H2.984a.75.75 0 0 0-.75.75v4.992a.75.75 0 0 0 1.5 0v-3.18l1.9 1.9a9 9 0 0 0 15.059-4.035.75.75 0 0 0-.53-.918Z" clip-rule="evenodd" /></svg>
      </button>
    </div>
  </div>

  <div class="sv-receipt-preview" x-show="recibos.length > 0" x-transition>
    
    <!-- Resumen rápido -->
    <div class="sv-receipt-stats">
        <div class="sv-stat-card">
            <span class="sv-stat-card__label">Frecuencia</span>
            <div class="sv-stat-card__value sv-stat-card__value--navy" x-text="frecuenciaPago"></div>
        </div>
        <div class="sv-stat-card">
            <span class="sv-stat-card__label">Total Pagos</span>
            <div class="sv-stat-card__value" x-text="recibos.length"></div>
        </div>
        <div class="sv-stat-card">
            <span class="sv-stat-card__label">Acumulado</span>
            <div class="sv-stat-card__value sv-stat-card__value--gold" x-text="'$' + totalRecibos"></div>
        </div>
        <div class="sv-stat-card" :class="parseFloat(totalRecibos) > parseFloat(prima_total) ? 'sv-stat-card--danger' : (totalRecibos != prima_total ? 'sv-stat-card--error' : 'sv-stat-card--success')">
            <span class="sv-stat-card__label">Diferencia</span>
            <div class="sv-stat-card__value" x-text="'$' + (totalRecibos - prima_total).toFixed(2)"></div>
            <div x-show="parseFloat(totalRecibos) > parseFloat(prima_total)" class="sv-stat-card__error-msg">
                La suma no puede ser mayor al total
            </div>
        </div>
    </div>

    <!-- Tabla de recibos simplificada -->
    <div class="sv-receipt-table-wrapper">
        <table class="sv-receipt-table">
            <thead>
                <tr>
                    <th class="sv-col-idx">#</th>
                    <th class="sv-col-vigencia">Vigencia (Inicio - Fin)</th>
                    <th class="sv-col-vencimiento">Límite de Pago</th>
                    <th class="sv-col-total" style="text-align: right;">Monto Total</th>
                    <th class="sv-col-actions">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <template x-for="(recibo, index) in recibos" :key="index">
                    <tr :class="{'sv-row-even': index % 2 !== 0}">
                        <td class="sv-col-idx">
                            <span class="sv-receipt-badge" x-text="recibo.indice"></span>
                            <input type="hidden" :name="'recibos['+index+'][indice_recibo]'" :value="recibo.indice">
                        </td>
                        <td class="sv-col-vigencia">
                            <div class="sv-date-group">
                                <input type="date" x-model="recibo.fecha_inicio_vigencia" @input="updateReciboDeadline(recibo)" 
                                       class="sv-input-ghost sv-input-ghost--date" :name="'recibos['+index+'][fecha_inicio_vigencia]'">
                                <span class="sv-separator">/</span>
                                <input type="date" x-model="recibo.fecha_fin_vigencia"
                                       class="sv-input-ghost sv-input-ghost--date" :name="'recibos['+index+'][fecha_fin_vigencia]'">
                            </div>
                        </td>
                        <td class="sv-col-vencimiento">
                            <div class="sv-vencimiento-wrapper" style="max-width: 140px;">
                                <input type="date" x-model="recibo.fecha_vencimiento" 
                                       class="sv-input-ghost sv-input-ghost--vencimiento" :name="'recibos['+index+'][fecha_vencimiento]'">
                            </div>
                            <input type="hidden" :name="'recibos['+index+'][periodo_gracia]'" :value="periodoGracia">
                        </td>
                        <td class="sv-col-total">
                            <div class="sv-receipt-total" style="justify-content: flex-end; gap: 8px; align-items: center;">
                                <div style="display: flex; align-items: baseline; gap: 2px;">
                                    <span class="sv-currency">$</span>
                                    <span class="sv-amount" x-text="(parseFloat(recibo.prima_neta || 0) + parseFloat(recibo.derechos || 0) + parseFloat(recibo.recargo || 0) + parseFloat(recibo.iva || 0)).toFixed(2)"></span>
                                </div>
                                <button type="button" class="sv-btn-icon sv-btn-icon--sm" @click="openBreakdown(index)" title="Editar Desglose">
                                    <svg width="14" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.199Z" /></svg>
                                </button>
                            </div>
                            
                            <!-- Hidden inputs for form submission -->
                            <input type="hidden" :name="'recibos['+index+'][prima_neta]'" :x-model="recibo.prima_neta" :value="recibo.prima_neta">
                            <input type="hidden" :name="'recibos['+index+'][derechos]'" :x-model="recibo.derechos" :value="recibo.derechos">
                            <input type="hidden" :name="'recibos['+index+'][recargo]'" :x-model="recibo.recargo" :value="recibo.recargo">
                            <input type="hidden" :name="'recibos['+index+'][iva]'" :x-model="recibo.iva" :value="recibo.iva">
                            <input type="hidden" :name="'recibos['+index+'][monto]'" :value="(parseFloat(recibo.prima_neta || 0) + parseFloat(recibo.derechos || 0) + parseFloat(recibo.recargo || 0) + parseFloat(recibo.iva || 0)).toFixed(2)">
                        </td>
                        <td class="sv-col-actions" style="text-align: center;">
                            <button type="button" class="sv-btn-icon" @click="propagateRecibo(index)" x-show="index < recibos.length - 1" title="Propagar a los siguientes">
                                <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path d="M11.644 1.59a.75.75 0 0 1 .712 0l9.75 5.25a.75.75 0 0 1 0 1.32l-9.75 5.25a.75.75 0 0 1-.712 0l-9.75-5.25a.75.75 0 0 1 0-1.32l9.75-5.25Z" /><path d="m3.265 10.602 7.667 4.128a1.5 1.5 0 0 0 1.436 0l7.667-4.128a.75.75 0 1 1 .71 1.32l-7.667 4.129a3 3 0 0 1-2.872 0L2.555 11.922a.75.75 0 1 1 .71-1.32Z" /><path d="m3.265 14.352 7.667 4.128a1.5 1.5 0 0 0 1.436 0l7.667-4.128a.75.75 0 1 1 .71 1.32l-7.667 4.129a3 3 0 0 1-2.872 0L2.555 15.672a.75.75 0 1 1 .71-1.32Z" /></svg>
                            </button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>

    <!-- Alerta informativa premium -->
    <div class="sv-receipt-alert">
        <div class="sv-receipt-alert__icon">
            <svg width="20" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 9a.75.75 0 0 0-1.5 0v7.5a.75.75 0 0 0 1.5 0V9ZM12 7.5a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" clip-rule="evenodd" /></svg>
        </div>
        <p class="sv-receipt-alert__text">
            <strong>Mantenimiento:</strong> Haz clic en el ícono del lápiz <svg width="12" style="display:inline; margin: 0 2px" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-12.15 12.15a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32L19.513 8.199Z" /></svg> para ajustar el desglose de impuestos de cada recibo. El total se validará automáticamente.
        </p>
    </div>

  </div>

  <!-- Estado vacío -->
  <div x-show="!fechaInicio" class="sv-receipt-empty">
    <div class="sv-receipt-empty__icon">
        <svg width="48" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 0 1 7.5 3v1.5h9V3A.75.75 0 0 1 18 3v1.5h.75a3 3 0 0 1 3 3v11.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V7.5a3 3 0 0 1 3-3H6V3a.75.75 0 0 1 .75-.75Zm13.5 9H3.75v7.5a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5v-7.5Z" clip-rule="evenodd" /></svg>
    </div>
    <p>Ingresa una <strong>Fecha de Inicio</strong> para generar el calendario de pagos.</p>
  </div>

</div>

<!-- Modal de Desglose de Primas -->
<div class="sv-modal-overlay" x-show="showBreakdownModal" style="display:none;" x-transition>
  <div class="sv-modal-card" @click.away="showBreakdownModal = false">
    <div class="sv-modal-card__header">
      <div>
        <h3 class="sv-modal-card__title">Desglose del Recibo <span x-text="editingRecibo?.indice"></span></h3>
        <p class="sv-modal-card__subtitle">Ajusta los componentes de la prima para este periodo</p>
      </div>
      <button type="button" @click="showBreakdownModal = false" class="sv-modal-card__close">&times;</button>
    </div>
    
    <div class="sv-modal-card__body">
      <template x-if="editingRecibo">
        <div class="sv-form-grid sv-form-grid--2">
          <div class="sv-field">
            <label class="sv-field__label">Prima Neta</label>
            <div class="sv-input-group">
                <span class="sv-input-group__text">$</span>
                <input type="number" step="0.01" x-model="editingRecibo.prima_neta" class="sv-input">
            </div>
          </div>
          <div class="sv-field">
            <label class="sv-field__label">Recargos</label>
            <div class="sv-input-group">
                <span class="sv-input-group__text">$</span>
                <input type="number" step="0.01" x-model="editingRecibo.recargo" class="sv-input">
            </div>
          </div>
          <div class="sv-field">
            <label class="sv-field__label">Derechos</label>
            <div class="sv-input-group">
                <span class="sv-input-group__text">$</span>
                <input type="number" step="0.01" x-model="editingRecibo.derechos" class="sv-input">
            </div>
          </div>
          <div class="sv-field">
            <label class="sv-field__label">IVA</label>
            <div class="sv-input-group">
                <span class="sv-input-group__text">$</span>
                <input type="number" step="0.01" x-model="editingRecibo.iva" class="sv-input">
            </div>
          </div>
          
          <div class="sv-field sv-field--full" style="grid-column: 1 / -1; margin-top: 12px; padding: 16px; background: var(--sv-navy-light); border-radius: 12px;">
             <div style="display: flex; justify-content: space-between; align-items: center;">
                <span style="font-weight: 700; color: var(--sv-navy);">Total del Recibo</span>
                <span style="font-size: 20px; font-weight: 800; color: var(--sv-navy);" 
                      x-text="'$' + (parseFloat(editingRecibo.prima_neta || 0) + parseFloat(editingRecibo.derechos || 0) + parseFloat(editingRecibo.recargo || 0) + parseFloat(editingRecibo.iva || 0)).toFixed(2)"></span>
             </div>
          </div>
        </div>
      </template>
    </div>
    
    <div class="sv-modal-card__footer">
      <button type="button" class="sv-btn sv-btn--primary sv-btn--full" @click="showBreakdownModal = false">Aplicar Cambios</button>
    </div>
  </div>
</div>

<style>
/* Header Styles */
.sv-receipt-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-bottom: 24px;
    gap: 20px;
}
.sv-receipt-header__info { flex: 1; }
.sv-receipt-header__actions { 
    display: flex; 
    gap: 16px; 
    align-items: center;
    background: white;
    padding: 8px 16px;
    border-radius: 12px;
    border: 1px solid var(--sv-gray-200);
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.sv-gracia-selector {
    display: flex;
    flex-direction: column;
    gap: 4px;
}
.sv-gracia-selector label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    color: var(--sv-gray-500);
    letter-spacing: 0.05em;
}
.sv-gracia-input-wrapper {
    display: flex;
    align-items: center;
    background: var(--sv-gray-50);
    border: 1px solid var(--sv-gray-200);
    border-radius: 6px;
    padding: 0 8px;
}
.sv-input-minimal {
    border: none !important;
    background: transparent !important;
    width: 40px !important;
    padding: 4px 0 !important;
    font-family: var(--sv-font-mono);
    font-weight: 700;
    color: var(--sv-navy);
    text-align: center;
    -moz-appearance: textfield;
}
.sv-input-minimal::-webkit-inner-spin-button { -webkit-appearance: none; }
.sv-unit { font-size: 11px; color: var(--sv-gray-400); font-weight: 600; }

/* Stats Grid */
.sv-receipt-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
.sv-stat-card {
    background: white;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid var(--sv-gray-100);
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}
.sv-stat-card__label {
    display: block;
    font-size: 11px;
    font-weight: 700;
    color: var(--sv-gray-400);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 4px;
}
.sv-stat-card__value {
    font-size: 20px;
    font-weight: 800;
    color: var(--sv-gray-900);
}
.sv-stat-card__value--navy { color: var(--sv-navy); }
.sv-stat-card__value--gold { color: var(--sv-gold); }
.sv-stat-card--error { border-color: var(--sv-red-200); background: #fffafb; }
.sv-stat-card--error .sv-stat-card__value { color: var(--sv-red-600); }
.sv-stat-card--danger { border-color: var(--sv-red-600); background: #fff5f5; box-shadow: 0 0 0 1px var(--sv-red-200); }
.sv-stat-card--danger .sv-stat-card__value { color: var(--sv-red-700); }
.sv-stat-card__error-msg { font-size: 9px; font-weight: 800; color: var(--sv-red-600); text-transform: uppercase; margin-top: 4px; }
.sv-stat-card--success { border-color: var(--sv-green-200); background: #fafffb; }
.sv-stat-card--success .sv-stat-card__value { color: var(--sv-green-600); }

/* Table Styles */
.sv-receipt-table-wrapper {
    background: white;
    border: 1px solid var(--sv-gray-200);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05);
}
.sv-receipt-table {
    width: 100%;
    border-collapse: collapse;
}
.sv-receipt-table th {
    background: var(--sv-navy);
    color: white;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 12px 16px;
    text-align: left;
}
.sv-receipt-table td {
    padding: 12px 16px;
    border-bottom: 1px solid var(--sv-gray-100);
    vertical-align: middle;
}
.sv-row-even { background: var(--sv-gray-25); }

.sv-receipt-badge {
    display: block;
    width: 24px;
    height: 24px;
    line-height: 24px;
    text-align: center;
    background: var(--sv-navy-light);
    color: var(--sv-navy);
    border-radius: 6px;
    font-weight: 800;
    font-size: 12px;
}

.sv-date-group {
    display: flex;
    align-items: center;
    gap: 8px;
    background: white;
    border: 1px solid var(--sv-gray-200);
    padding: 4px 8px;
    border-radius: 8px;
}
.sv-input-ghost {
    border: none;
    background: transparent;
    font-family: var(--sv-font-mono);
    font-size: 12px;
    color: var(--sv-gray-700);
    outline: none;
    width: auto;
}
.sv-input-ghost--date { width: 115px; }
.sv-separator { color: var(--sv-gray-300); font-weight: 300; }

.sv-vencimiento-wrapper {
    background: var(--sv-gold-light);
    border: 1px dashed var(--sv-gold);
    border-radius: 8px;
    padding: 4px 8px;
}
.sv-input-ghost--vencimiento {
    color: var(--sv-gold-dark);
    font-weight: 700;
}

.sv-receipt-total {
    text-align: right;
    display: flex;
    justify-content: flex-end;
    align-items: baseline;
    gap: 2px;
}
.sv-currency { font-size: 12px; font-weight: 600; color: var(--sv-gray-400); }
.sv-amount { font-size: 16px; font-weight: 800; color: var(--sv-navy); }

.sv-btn-icon {
    border: none;
    background: transparent;
    color: var(--sv-gray-400);
    cursor: pointer;
    padding: 8px;
    border-radius: 8px;
    transition: all 0.2s;
}
.sv-btn-icon--sm { padding: 4px; }
.sv-btn-icon:hover {
    background: var(--sv-navy-light);
    color: var(--sv-navy);
}

/* Modal Styles */
.sv-modal-overlay { 
    position: fixed; 
    inset: 0; 
    background: rgba(0, 51, 102, 0.4); 
    backdrop-filter: blur(4px);
    z-index: 9999; 
    display: flex; 
    align-items: center; 
    justify-content: center; 
    padding: 20px; 
}
.sv-modal-card { 
    background: white; 
    width: 100%; 
    max-width: 500px; 
    border-radius: 20px; 
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); 
    overflow: hidden;
    animation: svModalIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
@keyframes svModalIn {
    from { transform: translateY(20px) scale(0.95); opacity: 0; }
    to { transform: translateY(0) scale(1); opacity: 1; }
}
.sv-modal-card__header { 
    padding: 24px; 
    border-bottom: 1px solid var(--sv-gray-100); 
    display: flex; 
    align-items: flex-start; 
    justify-content: space-between; 
}
.sv-modal-card__title { font-weight: 800; color: var(--sv-navy); margin: 0; font-size: 20px; }
.sv-modal-card__subtitle { font-size: 13px; color: var(--sv-gray-500); margin: 4px 0 0 0; }
.sv-modal-card__close { background: var(--sv-gray-100); border: none; font-size: 20px; cursor: pointer; color: var(--sv-gray-500); width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
.sv-modal-card__close:hover { background: var(--sv-red-100); color: var(--sv-red-600); }
.sv-modal-card__body { padding: 24px; }
.sv-modal-card__footer { padding: 0 24px 24px 24px; }

/* Alert */
.sv-receipt-alert {
    margin-top: 24px;
    display: flex;
    gap: 12px;
    background: #fff9eb;
    border: 1px solid #ffeeba;
    padding: 16px;
    border-radius: 12px;
    color: var(--sv-gold-dark);
}
.sv-receipt-alert__text { font-size: 13px; margin: 0; line-height: 1.5; }

/* Empty State */
.sv-receipt-empty {
    padding: 60px;
    text-align: center;
    background: var(--sv-gray-25);
    border: 2px dashed var(--sv-gray-200);
    border-radius: 16px;
    color: var(--sv-gray-500);
}
.sv-receipt-empty__icon {
    color: var(--sv-gray-200);
    margin-bottom: 16px;
}
</style>
