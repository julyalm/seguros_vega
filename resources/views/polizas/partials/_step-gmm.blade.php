<div class="sv-form-section">
  <h3 class="sv-form-section__title">Datos GMM — Gastos Médicos Mayores</h3>
  <p class="sv-form-section__desc">Información específica del seguro de gastos médicos</p>

  <div class="sv-form-grid sv-form-grid--2">

    <div class="sv-field">
      <label class="sv-field__label">Número de Endoso</label>
      <input type="text" class="sv-input sv-mono" placeholder="Ej: END-2024-001">
      <span class="sv-field__hint">Número de endoso de modificación si aplica</span>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Suma asegurada</label>
      <div class="sv-field__input-wrapper">
        <span class="sv-field__prefix">$</span>
        <input type="number" class="sv-input sv-input--with-prefix sv-mono" placeholder="0.00">
      </div>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Deducible</label>
      <div class="sv-field__input-wrapper">
        <span class="sv-field__prefix">$</span>
        <input type="number" class="sv-input sv-input--with-prefix sv-mono" placeholder="0.00">
      </div>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Coaseguro (%)</label>
      <input type="number" class="sv-input sv-mono" placeholder="10" min="0" max="100">
    </div>

    <div class="sv-field sv-field--full">
      <label class="sv-field__label">Plan / Tipo de cobertura</label>
      <select class="sv-select">
        <option value="">Seleccionar...</option>
        <option>Individual</option>
        <option>Familiar</option>
        <option>Colectivo</option>
      </select>
    </div>

  </div>
</div>
