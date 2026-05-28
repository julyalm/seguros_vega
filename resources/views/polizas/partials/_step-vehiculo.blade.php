<div class="sv-form-section">
  <h3 class="sv-form-section__title">Datos del Vehículo</h3>
  <p class="sv-form-section__desc">Información técnica del automóvil asegurado</p>

  <!-- Bucle de vehículos -->
  <template x-for="(veh, i) in vehiculos" :key="i">
    <div
      style="margin-bottom: 32px; padding: 24px; border: 1px solid var(--sv-gray-200); border-radius: 12px; background: #fff;">
      <div
        style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--sv-gray-100); padding-bottom: 12px;">
        <h4 style="margin: 0; color: var(--sv-navy); font-weight: 700;">
          Vehículo #<span x-text="i + 1"></span>
        </h4>
        <span x-show="veh.marca" x-text="veh.marca + ' ' + veh.submarca"
          style="font-size: 13px; font-weight: 600; color: var(--sv-gold);"></span>
      </div>

      <div class="sv-form-grid sv-form-grid--2">

        <div class="sv-field">
          <label class="sv-field__label">Tipo <span class="sv-required">*</span></label>
          <select :name="`vehiculos[${i}][tipo]`" class="sv-select" x-model="veh.tipo" required>
            <option value="Auto">Auto</option>
            <option value="Pick-Up">Pick-Up</option>
            <option value="Tractos">Tractos</option>
            <option value="Equipo Pesado">Equipo Pesado</option>
          </select>
        </div>


        <div class="sv-field">
          <label class="sv-field__label">Modelo (Año) <span class="sv-required">*</span></label>
          <input type="number" :name="`vehiculos[${i}][modelo]`" class="sv-input sv-mono" placeholder="2024"
            x-model="veh.modelo" required>
        </div>

        <div class="sv-field">
          <label class="sv-field__label">Marca <span class="sv-required">*</span></label>
          <input type="text" :name="`vehiculos[${i}][marca]`" class="sv-input" placeholder="Ej: Nissan"
            x-model="veh.marca" required>
        </div>

        <div class="sv-field">
          <label class="sv-field__label">Submarca <span class="sv-required">*</span></label>
          <input type="text" :name="`vehiculos[${i}][submarca]`" class="sv-input" placeholder="Ej: Sentra"
            x-model="veh.submarca" required>
        </div>

        <div class="sv-field">
          <label class="sv-field__label">VIN (Serie) <span class="sv-required">*</span></label>
          <input type="text" :name="`vehiculos[${i}][vin]`" class="sv-input sv-mono" maxlength="17"
            placeholder="NÚMERO DE SERIE" x-model="veh.vin" required
            @input="veh.vin = $event.target.value.toUpperCase()"
            @blur="checkVinAvailability(i)"
            :style="veh.vinExists ? 'border-color: var(--sv-red-600); background: rgba(220,38,38,0.05);' : ''">
          <div x-show="veh.vinExists" style="margin-top: 8px; color: var(--sv-red-600); font-size: 11px; font-weight: 600; display: flex; align-items: center; gap: 4px;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            Este VIN ya está registrado en el sistema.
          </div>
        </div>

        <div class="sv-field">
          <label class="sv-field__label">Placas</label>
          <input type="text" :name="`vehiculos[${i}][placas]`" class="sv-input sv-mono" placeholder="ABC-1234"
            x-model="veh.placas" @input="veh.placas = $event.target.value.toUpperCase()">
        </div>

        <div class="sv-field">
          <label class="sv-field__label">Motor</label>
          <input type="text" :name="`vehiculos[${i}][motor]`" class="sv-input sv-mono" x-model="veh.motor">
        </div>


      </div>
    </div>
  </template>
</div>