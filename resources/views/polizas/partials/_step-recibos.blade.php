<div class="sv-form-section" x-data="recibosStep()">
  <h3 class="sv-form-section__title">Recibos de Pago</h3>
  <p class="sv-form-section__desc">
    Según la frecuencia de pago seleccionada, se generarán
    <strong x-text="cantidadRecibos"></strong> recibo(s)
  </p>

  <!-- Grid de recibos generados dinámicamente -->
  <div class="sv-recibos-list">
    <template x-for="(recibo, index) in recibos" :key="index">
      <div class="sv-recibo-item">
        <div class="sv-recibo-item__header">
          <span class="sv-recibo-item__num" x-text="`Recibo ${index + 1}`"></span>
          <span class="sv-recibo-item__fecha" x-text="recibo.fechaVencimiento"></span>
        </div>
        <div class="sv-form-grid sv-form-grid--3">

          <!-- Monto -->
          <div class="sv-field">
            <label class="sv-field__label">Monto</label>
            <div class="sv-field__input-wrapper">
              <span class="sv-field__prefix">$</span>
              <input type="number" class="sv-input sv-input--with-prefix sv-mono"
                     x-model="recibo.monto" placeholder="0.00" step="0.01">
            </div>
          </div>

          <!-- Pago de comisiones -->
          <div class="sv-field">
            <label class="sv-field__label">Comisión (%)</label>
            <input type="number" class="sv-input sv-mono"
                   x-model="recibo.comision" placeholder="0.00" step="0.1" min="0" max="100">
          </div>

          <!-- Status -->
          <div class="sv-field">
            <label class="sv-field__label">Status</label>
            <select class="sv-select" x-model="recibo.status">
              <option value="pendiente">Pendiente</option>
              <option value="pagado">Pagado</option>
              <option value="vencido">Vencido</option>
            </select>
          </div>

          <!-- Contracargos -->
          <div class="sv-field sv-field--full">
            <label class="sv-field__label">Contracargos</label>
            <div class="sv-field__input-wrapper">
              <span class="sv-field__prefix">$</span>
              <input type="number" class="sv-input sv-input--with-prefix sv-mono"
                     x-model="recibo.contracargo" placeholder="0.00" step="0.01">
            </div>
          </div>

        </div>
      </div>
    </template>
  </div>
</div>

<script>
function recibosStep() {
  const frecuenciaMap = { Anual: 1, Semestral: 2, Trimestral: 4, Mensual: 12 };
  // En implementación real, leer la frecuencia del store compartido del wizard
  const freq = 'Anual';
  const n = frecuenciaMap[freq] || 1;
  return {
    cantidadRecibos: n,
    recibos: Array.from({ length: n }, (_, i) => ({
      monto: '',
      comision: '',
      status: 'pendiente',
      contracargo: '0',
      fechaVencimiento: `Recibo ${i + 1}`,
    })),
  }
}
</script>
