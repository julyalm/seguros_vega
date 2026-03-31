<div class="sv-form-section">
  <h3 class="sv-form-section__title">Datos del Asegurado</h3>
  <p class="sv-form-section__desc">Información personal del titular de la póliza</p>

  <div class="sv-form-grid sv-form-grid--2">

    <!-- Nombre completo -->
    <div class="sv-field sv-field--full">
      <label class="sv-field__label">Nombre completo <span class="sv-required">*</span></label>
      <input type="text" class="sv-input" placeholder="Ej: María González López">
    </div>

    <!-- RFC -->
    <div class="sv-field">
      <label class="sv-field__label">RFC <span class="sv-required">*</span></label>
      <input type="text" class="sv-input sv-mono" placeholder="GOML850312AB1" maxlength="13">
      <span class="sv-field__hint">13 caracteres incluyendo homoclave</span>
    </div>

    <!-- Fecha de nacimiento -->
    <div class="sv-field">
      <label class="sv-field__label">Fecha de nacimiento <span class="sv-required">*</span></label>
      <input type="date" class="sv-input">
    </div>

    <!-- Género -->
    <div class="sv-field">
      <label class="sv-field__label">Género <span class="sv-required">*</span></label>
      <select class="sv-select">
        <option value="">Seleccionar...</option>
        <option value="M">Masculino</option>
        <option value="F">Femenino</option>
        <option value="NB">No binario</option>
        <option value="NE">Prefiero no especificar</option>
      </select>
    </div>

    <!-- Dirección -->
    <div class="sv-field sv-field--full">
      <label class="sv-field__label">Dirección <span class="sv-required">*</span></label>
      <input type="text" class="sv-input" placeholder="Calle, Número, Colonia, Municipio, Estado, C.P.">
    </div>

    <!-- Email -->
    <div class="sv-field">
      <label class="sv-field__label">Correo electrónico <span class="sv-required">*</span></label>
      <input type="email" class="sv-input" placeholder="correo@ejemplo.com">
    </div>

    <!-- Teléfono -->
    <div class="sv-field">
      <label class="sv-field__label">Teléfono <span class="sv-required">*</span></label>
      <input type="tel" class="sv-input sv-mono" placeholder="55 1234 5678" maxlength="10">
    </div>

  </div>
</div>
