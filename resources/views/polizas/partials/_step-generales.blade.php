<div class="sv-form-section" x-data="generalesStep()">
  <h3 class="sv-form-section__title">Datos Generales de la Póliza</h3>
  <p class="sv-form-section__desc">Información principal y condiciones de contratación</p>

  <div class="sv-form-grid sv-form-grid--2">

    <!-- Número de póliza -->
    <div class="sv-field">
      <label class="sv-field__label">Número de póliza <span class="sv-required">*</span></label>
      <input type="text" class="sv-input sv-mono" placeholder="Ej: A-2024-00124">
    </div>

    <!-- ¿Es flotilla? -->
    <div class="sv-field">
      <label class="sv-field__label">¿Es una flotilla?</label>
      <div class="sv-toggle-group">
        <label class="sv-radio-pill" :class="{ 'selected': !esFlotilla }">
          <input type="radio" x-model="esFlotilla" :value="false"> No
        </label>
        <label class="sv-radio-pill" :class="{ 'selected': esFlotilla === true }">
          <input type="radio" x-model="esFlotilla" :value="true"> Sí
        </label>
      </div>
    </div>

    <!-- Si ES flotilla: ¿nueva o existente? -->
    <template x-if="esFlotilla">
      <div class="sv-field sv-field--highlighted">
        <label class="sv-field__label">Tipo de flotilla</label>
        <div class="sv-toggle-group">
          <label class="sv-radio-pill" :class="{ 'selected': !flotillaExistente }">
            <input type="radio" x-model="flotillaExistente" :value="false"> Nueva flotilla
          </label>
          <label class="sv-radio-pill" :class="{ 'selected': flotillaExistente }">
            <input type="radio" x-model="flotillaExistente" :value="true"> Flotilla existente
          </label>
        </div>
        <span class="sv-field__hint">Si es flotilla existente, el fin de vigencia se fijará igual a la póliza padre</span>
      </div>
    </template>

    <!-- Inciso (solo si es flotilla) -->
    <template x-if="esFlotilla">
      <div class="sv-field">
        <label class="sv-field__label">Inciso</label>
        <input type="text" class="sv-input sv-mono" placeholder="Ej: 001">
        <span class="sv-field__hint">Número de inciso dentro de la flotilla</span>
      </div>
    </template>

    <!-- Fecha inicio vigencia -->
    <div class="sv-field">
      <label class="sv-field__label">Fecha inicio vigencia <span class="sv-required">*</span></label>
      <input type="date" class="sv-input" x-model="fechaInicio" @change="calcularFin">
    </div>

    <!-- Fecha fin vigencia -->
    <div class="sv-field">
      <label class="sv-field__label">Fecha fin vigencia <span class="sv-required">*</span></label>
      <input type="date" class="sv-input" x-model="fechaFin"
             :readonly="flotillaExistente"
             :class="{ 'sv-input--readonly': flotillaExistente }">
      <span class="sv-field__hint" x-show="flotillaExistente" style="color:var(--sv-gold)">
        ⚑ Fecha fijada por la póliza padre de la flotilla
      </span>
    </div>

    <!-- Frecuencia de pago -->
    <div class="sv-field">
      <label class="sv-field__label">Frecuencia de pago <span class="sv-required">*</span></label>
      <select class="sv-select" x-model="frecuenciaPago">
        <option value="Anual">Anual (1 recibo)</option>
        <option value="Semestral">Semestral (2 recibos)</option>
        <option value="Trimestral">Trimestral (4 recibos)</option>
        <option value="Mensual">Mensual (12 recibos)</option>
      </select>
    </div>

    <!-- Prima total -->
    <div class="sv-field">
      <label class="sv-field__label">Prima total <span class="sv-required">*</span></label>
      <div class="sv-field__input-wrapper">
        <span class="sv-field__prefix">$</span>
        <input type="number" class="sv-input sv-input--with-prefix sv-mono"
               placeholder="0.00" step="0.01" min="0">
      </div>
    </div>

    <!-- Vendedor -->
    <div class="sv-field">
      <label class="sv-field__label">Vendedor <span class="sv-required">*</span></label>
      <select class="sv-select">
        <option value="">Seleccionar vendedor...</option>
        <option>Emmanuel Vega</option>
        <option>Carlos Mendoza</option>
        <option>Laura Torres</option>
      </select>
    </div>

  </div>
</div>

<script>
function generalesStep() {
  return {
    esFlotilla: false,
    flotillaExistente: false,
    frecuenciaPago: 'Anual',
    fechaInicio: '',
    fechaFin: '',
    calcularFin() {
      if (!this.fechaInicio || this.flotillaExistente) return;
      const d = new Date(this.fechaInicio);
      d.setFullYear(d.getFullYear() + 1);
      this.fechaFin = d.toISOString().split('T')[0];
    },
  }
}
</script>
