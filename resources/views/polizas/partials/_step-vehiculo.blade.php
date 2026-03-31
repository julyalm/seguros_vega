<div class="sv-form-section">
  <h3 class="sv-form-section__title">Datos del Vehículo</h3>
  <p class="sv-form-section__desc">Información técnica del automóvil asegurado</p>

  <div class="sv-form-grid sv-form-grid--2">

    <div class="sv-field">
      <label class="sv-field__label">Tipo de vehículo <span class="sv-required">*</span></label>
      <select class="sv-select">
        <option value="">Seleccionar...</option>
        <option>Sedán</option>
        <option>SUV / Camioneta</option>
        <option>Pick-up</option>
        <option>Deportivo</option>
        <option>Carga ligera</option>
        <option>Motocicleta</option>
        <option>Trailer / Carga pesada</option>
      </select>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Modelo (año) <span class="sv-required">*</span></label>
      <input type="number" class="sv-input sv-mono" placeholder="{{ date('Y') }}"
             min="1990" max="{{ date('Y') + 1 }}">
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Marca <span class="sv-required">*</span></label>
      <input type="text" class="sv-input" placeholder="Ej: Nissan, Toyota, Ford...">
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Vehículo (submarca) <span class="sv-required">*</span></label>
      <input type="text" class="sv-input" placeholder="Ej: Sentra, Camry, Explorer...">
    </div>

    <div class="sv-field sv-field--full">
      <label class="sv-field__label">Número de serie (VIN) <span class="sv-required">*</span></label>
      <input type="text" class="sv-input sv-mono" placeholder="17 caracteres" maxlength="17">
      <span class="sv-field__hint">Vehicle Identification Number — 17 caracteres</span>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Número de motor</label>
      <input type="text" class="sv-input sv-mono" placeholder="Ej: QG18-123456">
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Placas</label>
      <input type="text" class="sv-input sv-mono" placeholder="Ej: ABC-123-D">
    </div>

  </div>
</div>
