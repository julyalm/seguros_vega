<div class="sv-form-section">
  <h3 class="sv-form-section__title">Selecciona el Ramo</h3>
  <p class="sv-form-section__desc">¿A qué tipo de seguro pertenece esta póliza?</p>

  <div class="sv-ramo-grid">

    <label class="sv-ramo-card" :class="{ 'selected': ramo === 'Autos' }">
      <input type="radio" name="ramo" value="Autos" x-model="ramo" class="sv-ramo-card__input" required>
      <div class="sv-ramo-card__inner">
        <div class="sv-ramo-card__icon">🚗</div>
        <span class="sv-ramo-card__title">Autos</span>
        <span class="sv-ramo-card__desc">Seguro vehicular y flotillas</span>
      </div>
    </label>

    <label class="sv-ramo-card" :class="{ 'selected': ramo === 'GMM' }">
      <input type="radio" name="ramo" value="GMM" x-model="ramo" class="sv-ramo-card__input">
      <div class="sv-ramo-card__inner">
        <div class="sv-ramo-card__icon">❤️</div>
        <span class="sv-ramo-card__title">Gastos Médicos Mayores</span>
        <span class="sv-ramo-card__desc">Cobertura de salud integral</span>
      </div>
    </label>

    <label class="sv-ramo-card" :class="{ 'selected': ramo === 'Daños' }">
      <input type="radio" name="ramo" value="Daños" x-model="ramo" class="sv-ramo-card__input">
      <div class="sv-ramo-card__inner">
        <div class="sv-ramo-card__icon">🏠</div>
        <span class="sv-ramo-card__title">Daños</span>
        <span class="sv-ramo-card__desc">Hogar, empresa y propiedades</span>
      </div>
    </label>

  </div>
</div>
