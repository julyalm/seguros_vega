<div class="sv-form-section">
  <h3 class="sv-form-section__title" x-text="parentPolicy ? 'Archivo de Inclusión' : 'Aseguradora y Archivo'"></h3>
  <p class="sv-form-section__desc"
    x-text="parentPolicy ? 'Carga el documento de la inclusión para finalizar' : 'Finaliza seleccionando la compañía y cargando el documento'">
  </p>

  <div class="sv-aseguradora-grid" x-show="!parentPolicy">
    @foreach($aseguradoras as $aseg)
      <label class="sv-aseg-card"
        :class="parentPolicy && parentPolicy.aseguradora_id == {{ $aseg->id }} ? 'sv-aseg-card--selected' : ''">
        <input type="radio" name="aseguradora_id" value="{{ $aseg->id }}" class="sv-aseg-card__input" required
          :disabled="parentPolicy" :checked="parentPolicy && parentPolicy.aseguradora_id == {{ $aseg->id }}">
        <div class="sv-aseg-card__inner">
          <div class="sv-aseg-card__avatar" style="background: {{ $aseg->color }}">
            {{ $aseg->inicial }}
          </div>
          <span class="sv-aseg-card__name">{{ $aseg->nombre }}</span>
          <div class="sv-aseg-card__check">
            <svg width="16" viewBox="0 0 24 24" fill="currentColor">
              <path fill-rule="evenodd"
                d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z"
                clip-rule="evenodd" />
            </svg>
          </div>
        </div>
      </label>
    @endforeach
    <template x-if="parentPolicy">
      <input type="hidden" name="aseguradora_id" :value="parentPolicy.aseguradora_id">
    </template>
  </div>

  <!-- Alerta de Herencia de Aseguradora -->
  <template x-if="parentPolicy">
    <div
      style="margin-bottom: 32px; padding: 20px; background: var(--sv-navy-light); border: 1px solid var(--sv-navy-dark); border-radius: 12px; display: flex; align-items: center; gap: 16px;">
      <div
        style="background: white; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: var(--sv-navy); font-size: 18px; border: 2px solid var(--sv-gold);"
        x-text="parentPolicy?.aseguradora?.inicial || 'M'">
      </div>
      <div style="flex: 1;">
        <h4 style="margin: 0; color: var(--sv-navy); font-size: 14px;">Aseguradora Heredada</h4>
        <p style="margin: 4px 0 0; font-size: 12px; color: var(--sv-gray-600);">Esta inclusión se registrará bajo la misma
          compañía de la flotilla maestro.</p>
      </div>
    </div>
  </template>

  <div class="sv-field" style="margin-top: 32px;">
    <label class="sv-field__label">Cargar Póliza (PDF)</label>
    <div class="sv-field__input-wrapper">
      <input type="file" name="archivo_poliza" class="sv-input" accept="application/pdf">
    </div>
    <span class="sv-field__hint">Carga el documento emitido por la aseguradora para fácil acceso posterior.</span>
  </div>
</div>