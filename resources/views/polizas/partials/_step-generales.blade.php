<div class="sv-form-section">
  <h3 class="sv-form-section__title">Información de la Póliza</h3>
  <p class="sv-form-section__desc">Detalles técnicos y costos de la cobertura</p>

  <div class="sv-form-grid sv-form-grid--2">
    <input type="hidden" name="es_flotilla" value="0">
    <input type="hidden" name="flotilla_existente" value="0">


    <div class="sv-field">
      <label class="sv-field__label">Número de Póliza <span class="sv-required">*</span></label>
      <input type="text" name="numero_poliza" class="sv-input sv-mono" 
             placeholder="Ej: ABC-123456" required x-model="numeroPoliza" :readonly="parentPolicy"
             @blur="checkPolicyAvailability()"
             :style="polizaExiste ? 'border-color: var(--sv-red-600); background: rgba(220,38,38,0.05);' : ''">
      <div x-show="polizaExiste" style="margin-top: 8px; color: var(--sv-red-600); font-size: 12px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        Este número de póliza ya está registrado en el sistema.
      </div>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Fecha de Inicio <span class="sv-required">*</span></label>
      <input type="date" name="fecha_inicio" class="sv-input" required 
             x-model="fechaInicio" @input="updateExpiration()">
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Fecha de Vencimiento <span class="sv-required">*</span></label>
      <input type="date" name="fecha_fin" class="sv-input" required 
             x-model="fechaFin" :readonly="parentPolicy">
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Frecuencia de Pago <span class="sv-required">*</span></label>
      <select name="frecuencia_pago" class="sv-select" x-model="frecuenciaPago" required :disabled="parentPolicy">
        <option value="Anual">Anual</option>
        <option value="Semestral">Semestral</option>
        <option value="Trimestral">Trimestral</option>
        <option value="Mensual">Mensual</option>
      </select>
      <template x-if="parentPolicy">
        <input type="hidden" name="frecuencia_pago" :value="frecuenciaPago">
      </template>
    </div>

  </div>

  <h4 class="sv-form-section__subtitle" style="margin-top: 32px; margin-bottom: 16px; border-bottom: 1px solid var(--sv-gray-200); padding-bottom: 8px; color: var(--sv-navy); font-size: 14px; text-transform: uppercase; letter-spacing: 0.05em;">Desglose de Costos</h4>
  
  <div class="sv-form-grid sv-form-grid--3">
    
    <div class="sv-field">
      <label class="sv-field__label">Prima Neta <span class="sv-required">*</span></label>
      <div class="sv-input-group">
        <span class="sv-input-group__text">$</span>
        <input type="number" name="prima_neta" step="0.01" class="sv-input" 
               placeholder="0.00" required x-model="prima_neta" :readonly="parentPolicy">
      </div>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Derechos</label>
      <div class="sv-input-group">
        <span class="sv-input-group__text">$</span>
        <input type="number" name="derechos" step="0.01" class="sv-input" 
               placeholder="0.00" x-model="derechos" :readonly="parentPolicy">
      </div>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Recargo Fraccionado</label>
      <div class="sv-input-group">
        <span class="sv-input-group__text">$</span>
        <input type="number" name="recargo" step="0.01" class="sv-input" 
               placeholder="0.00" x-model="recargo" :readonly="parentPolicy">
      </div>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">IVA <span class="sv-required">*</span></label>
      <div class="sv-input-group">
        <span class="sv-input-group__text">$</span>
        <input type="number" name="iva" step="0.01" class="sv-input" 
               placeholder="0.00" required x-model="iva" :readonly="parentPolicy">
      </div>
    </div>

    <div class="sv-field" style="background: var(--sv-navy-light); border-radius: 8px; padding: 12px; border: 1px solid var(--sv-navy-dark);">
      <label class="sv-field__label" style="color: var(--sv-navy); font-weight: 700;">Prima Total</label>
      <div class="sv-input-group">
        <span class="sv-input-group__text" style="background: white;">$</span>
        <input type="number" name="prima_total" step="0.01" class="sv-input" 
               style="background: white; font-weight: 700;" readonly :value="prima_total">
      </div>
      <span class="sv-field__hint" style="color: var(--sv-navy)">Cálculo automático</span>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Monto de Comisión</label>
      <div class="sv-input-group">
        <span class="sv-input-group__text" style="background: var(--sv-gold-light);">$</span>
        <input type="number" name="comision" step="0.01" class="sv-input" 
               placeholder="0.00" x-model="comision" :readonly="parentPolicy">
      </div>
      <span class="sv-field__hint">Monto pactado para el agente</span>
    </div>

  </div>
</div>

