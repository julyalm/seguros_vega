<div class="sv-form-section">
  <h3 class="sv-form-section__title">Datos GMM — Gastos Médicos Mayores</h3>
  <p class="sv-form-section__desc">Información específica del seguro de salud</p>

  <div class="sv-form-grid sv-form-grid--2">

    <div class="sv-field">
      <label class="sv-field__label">Endoso</label>
      <input type="text" name="gmm_endoso" class="sv-input sv-mono">
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Suma Asegurada</label>
      <div class="sv-field__input-wrapper">
        <span class="sv-field__prefix">$</span>
        <input type="number" name="gmm_suma" class="sv-input sv-input--with-prefix sv-mono" step="0.01">
      </div>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Deducible</label>
      <div class="sv-field__input-wrapper">
        <span class="sv-field__prefix">$</span>
        <input type="number" name="gmm_deducible" class="sv-input sv-input--with-prefix sv-mono">
      </div>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Coaseguro (%)</label>
      <input type="number" name="gmm_coaseguro" class="sv-input sv-mono" max="100">
    </div>

    <div class="sv-field sv-field--full">
      <label class="sv-field__label">Plan / Red Médica</label>
      <input type="text" name="gmm_plan" class="sv-input" placeholder="Ej: Platinum, Red Amplia...">
    </div>

  </div>
</div>
