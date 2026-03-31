<div class="sv-form-section">
  <h3 class="sv-form-section__title">Aseguradora</h3>
  <p class="sv-form-section__desc">Selecciona la compañía aseguradora para esta póliza</p>

  <div class="sv-aseguradora-grid">

    @php
    $aseguradoras = [
      ['nombre' => 'Qualitas',  'color' => '#003366', 'inicial' => 'Q'],
      ['nombre' => 'Chubb',     'color' => '#E31837', 'inicial' => 'C'],
      ['nombre' => 'HDI',       'color' => '#005F9E', 'inicial' => 'H'],
      ['nombre' => 'ANA',       'color' => '#004990', 'inicial' => 'A'],
      ['nombre' => 'Afirme',    'color' => '#007934', 'inicial' => 'AF'],
    ];
    @endphp

    @foreach($aseguradoras as $aseg)
    <label class="sv-aseg-card">
      <input type="radio" name="aseguradora" value="{{ $aseg['nombre'] }}" class="sv-aseg-card__input">
      <div class="sv-aseg-card__inner">
        <div class="sv-aseg-card__avatar" style="background: {{ $aseg['color'] }}">
          {{ $aseg['inicial'] }}
        </div>
        <span class="sv-aseg-card__name">{{ $aseg['nombre'] }}</span>
        <div class="sv-aseg-card__check">
          <svg width="16" viewBox="0 0 24 24" fill="currentColor">
            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
          </svg>
        </div>
      </div>
    </label>
    @endforeach

  </div>
</div>
