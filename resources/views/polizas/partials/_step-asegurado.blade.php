<div class="sv-form-section">
  <h3 class="sv-form-section__title">Datos del Asegurado</h3>
  <p class="sv-form-section__desc">Información personal y domicilios</p>

  <!-- ══ DETECCIÓN DE ASEGURADO ════════════════════════════ -->


  <div class="sv-form-grid sv-form-grid--2">
    <div class="sv-field">
      <label class="sv-field__label">RFC <span class="sv-required">*</span></label>
      <div style="position: relative;">
        <input type="text" name="asegurado_rfc" class="sv-input sv-mono" 
               placeholder="GOML850312AB1" maxlength="13" 
                x-model="asegurado.rfc"
                @input="asegurado.rfc = $event.target.value.toUpperCase(); lookupRfc();"
               required>
        <div x-show="buscandoRfc" style="position: absolute; right: 10px; top: 10px;">
          <svg class="sv-spinner" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle><path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"></path></svg>
        </div>
      </div>
      <div x-show="rfcNotFound" style="margin-top: 8px; color: var(--sv-red-600); font-size: 12px; display: flex; align-items: center; gap: 4px;">
        <svg width="14" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd" /></svg>
        <span>No se encontró el asegurado. Verifica o regístralo como nuevo.</span>
      </div>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Nombre completo <span class="sv-required">*</span></label>
      <input type="text" name="asegurado_nombre" class="sv-input" 
             placeholder="Ej: María González López" required 
             x-model="asegurado.nombre" :readonly="buscandoRfc">
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Correo electrónico</label>
      <input type="email" name="asegurado_email" class="sv-input" 
             placeholder="correo@ejemplo.com" x-model="asegurado.email">
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Teléfono</label>
      <input type="tel" name="asegurado_telefono" class="sv-input sv-mono" 
             placeholder="10 dígitos" maxlength="10" x-model="asegurado.telefono">
    </div>
  </div>

  <!-- ══ SECCIÓN DE DIRECCIÓN ══════════════════════════════ -->
  <h4 class="sv-form-section__subtitle" style="margin-top: 32px; margin-bottom: 16px; border-bottom: 1px solid var(--sv-gray-200); padding-bottom: 8px; color: var(--sv-navy); font-size: 14px; text-transform: uppercase;">Dirección de la Póliza</h4>

  <!-- Selector de Direcciones Guardadas por Alias -->
  <template x-if="asegurado.direcciones.length > 0">
    <div x-data="{ term: '' }" class="sv-field sv-field--full" style="margin-bottom: 32px;">
      <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; padding-bottom: 12px; border-bottom: 1px solid var(--sv-gray-200);">
        <div>
          <label class="sv-field__label" style="margin: 0; font-size: 15px; color: var(--sv-navy); font-weight: 700;">Direcciones Guardadas</label>
          <p style="margin: 2px 0 0; font-size: 12px; color: var(--sv-gray-500);">Elige una dirección guardada por su alias</p>
        </div>
        <button type="button" class="sv-btn sv-btn--sm" :class="isNewAddress ? 'sv-btn--primary' : 'sv-btn--outline'" @click="isNewAddress = !isNewAddress">
          <svg x-show="!isNewAddress" width="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 4px;"><path d="M12 5v14M5 12h14"/></svg>
          <span x-text="isNewAddress ? 'Ver direcciones guardadas' : 'Nueva dirección'"></span>
        </button>
      </div>

      <div x-show="!isNewAddress" x-transition>
        <!-- Buscador de Alias -->
        <div x-show="asegurado.direcciones.length > 2" style="margin-bottom: 16px;">
          <div style="position: relative; max-width: 320px;">
            <input type="text" class="sv-input" style="padding-left: 36px; border-radius: 20px;" placeholder="Buscar alias (Ej. Oficina)..." x-model="term">
            <svg style="position: absolute; left: 12px; top: 10px; color: var(--sv-gray-400)" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
          </div>
        </div>

        <!-- Tarjetas de Alias -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 16px;">
          <template x-for="dir in asegurado.direcciones.filter(d => !term || (d.alias || 'Dirección Guardada').toLowerCase().includes(term.toLowerCase()))" :key="dir.id">
            <label class="sv-address-card" :class="{'active': asegurado.direccion_id === dir.id}" @click="asegurado.direccion_id = dir.id">
              <input type="radio" name="direccion_id" :value="dir.id" x-model="asegurado.direccion_id" style="display: none;" :disabled="isNewAddress">
              
              <div class="sv-address-card__header">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" style="opacity: 0.8"><path d="M11.25 3v4.046a3 3 0 0 0-4.277 4.204H3.75a.75.75 0 0 0 0 1.5h3.223a3 3 0 0 0 4.277 4.204V21a.75.75 0 0 0 1.5 0v-4.046a3 3 0 0 0 4.277-4.204h3.223a.75.75 0 0 0 0-1.5h-3.223a3 3 0 0 0-4.277-4.204V3a.75.75 0 0 0-1.5 0Z"/></svg>
                <span x-text="dir.alias || 'Dirección Guardada'" class="sv-address-card__alias"></span>
              </div>
              
              <div class="sv-address-card__body">
                <div x-text="`${dir.calle} #${dir.num_exterior}${dir.num_interior ? ` Int ${dir.num_interior}` : ''}`"></div>
                <div x-text="`${dir.colonia}, ${dir.municipio}`"></div>
                <div x-text="`CP ${dir.codigo_postal}, ${dir.estado}`"></div>
              </div>
              
              <div class="sv-address-card__check" style="pointer-events: none;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor">
                   <!-- Icono Desmarcado -->
                   <path x-show="asegurado.direccion_id !== dir.id" fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25Zm-1.72 6.97a.75.75 0 1 0-1.06 1.06L10.94 12l-1.72 1.72a.75.75 0 1 0 1.06 1.06L12 13.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L13.06 12l1.72-1.72a.75.75 0 1 0-1.06-1.06L12 10.94l-1.72-1.72Z" clip-rule="evenodd"/>
                   <!-- Icono Marcado -->
                   <path x-show="asegurado.direccion_id === dir.id" fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm13.36-1.814a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/>
                </svg>
              </div>
            </label>
          </template>
          
          <div x-show="asegurado.direcciones.filter(d => !term || (d.alias || 'Dirección Guardada').toLowerCase().includes(term.toLowerCase())).length === 0" style="grid-column: 1 / -1; padding: 24px; text-align: center; color: var(--sv-gray-500); background: var(--sv-gray-50); border-radius: 12px; border: 1px dashed var(--sv-gray-300);">
            No se encontraron direcciones con ese alias.
          </div>
        </div>
      </div>

      <!-- Estilos para las tarjetas -->
      <style>
        .sv-address-card {
           position: relative;
           display: flex;
           flex-direction: column;
           cursor: pointer;
           border: 2px solid var(--sv-gray-200);
           border-radius: 12px;
           padding: 16px;
           background: #fff;
           transition: all 0.2s ease;
           box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }
        .sv-address-card:hover { border-color: var(--sv-gold); box-shadow: 0 4px 12px rgba(0,0,0,0.05); transform: translateY(-2px); }
        .sv-address-card.active { border-color: var(--sv-gold); background: #fcf9f2; }
        .sv-address-card__header { display: flex; align-items: center; gap: 8px; color: var(--sv-navy); margin-bottom: 8px; }
        .sv-address-card.active .sv-address-card__header { color: #b58500; }
        .sv-address-card__alias { font-weight: 700; font-size: 15px; }
        .sv-address-card__body { font-size: 13px; color: var(--sv-gray-600); line-height: 1.5; }
        .sv-address-card__check { position: absolute; top: 16px; right: 16px; color: var(--sv-gray-300); }
        .sv-address-card.active .sv-address-card__check { color: var(--sv-gold); }
      </style>
    </div>
  </template>

  <!-- Formulario de Dirección (SEPOMEX) -->
  <div x-show="isNewAddress" x-transition class="sv-form-grid sv-form-grid--3">
    
    <div class="sv-field">
      <label class="sv-field__label">Código Postal <span class="sv-required">*</span></label>
      <input type="text" name="asegurado_cp" class="sv-input sv-mono" 
             placeholder="5 dígitos" maxlength="5" 
             x-model="asegurado.cp" @input="fetchSepomex()"
             :required="isNewAddress">
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Estado <span class="sv-required">*</span></label>
      <input type="text" name="asegurado_estado" class="sv-input" 
             x-model="asegurado.estado" :required="isNewAddress">
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Municipio <span class="sv-required">*</span></label>
      <input type="text" name="asegurado_municipio" class="sv-input" 
             x-model="asegurado.municipio" :required="isNewAddress">
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Colonia <span class="sv-required">*</span></label>
      <template x-if="asegurado.colonias.length > 0">
        <select name="asegurado_colonia" class="sv-select" x-model="asegurado.colonia" :required="isNewAddress">
          <template x-for="col in asegurado.colonias" :key="col">
            <option :value="col" x-text="col"></option>
          </template>
        </select>
      </template>
      <template x-if="asegurado.colonias.length === 0">
        <input type="text" name="asegurado_colonia" class="sv-input" 
               placeholder="Escribe la colonia" x-model="asegurado.colonia" :required="isNewAddress">
      </template>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Calle <span class="sv-required">*</span></label>
      <input type="text" name="asegurado_calle" class="sv-input" 
             placeholder="Ej: Av. Insurgentes" x-model="asegurado.calle" :required="isNewAddress">
    </div>

    <div class="sv-field">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
                <label class="sv-field__label">Num Ext <span class="sv-required">*</span></label>
                <input type="text" name="asegurado_num_ext" class="sv-input sv-mono" 
                       placeholder="123" x-model="asegurado.num_ext" :required="isNewAddress">
            </div>
            <div>
                <label class="sv-field__label">Num Int</label>
                <input type="text" name="asegurado_num_int" class="sv-input sv-mono" 
                       placeholder="A-1" x-model="asegurado.num_int">
            </div>
        </div>
    </div>

    <div class="sv-field">
      <label class="sv-field__label">Etiqueta (Alias)</label>
      <input type="text" name="direccion_alias" class="sv-input" 
             placeholder="Ej: Casa, Oficina" x-model="asegurado.alias">
    </div>

  </div>
</div>

<style>
/* Estilos para el Switch */
.sv-switch { position: relative; display: inline-block; width: 44px; height: 24px; }
.sv-switch input { opacity: 0; width: 0; height: 0; }
.sv-switch__slider { 
    position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; 
    background-color: var(--sv-gray-300); transition: .4s; border-radius: 24px; 
}
.sv-switch__slider:before { 
    position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; 
    background-color: white; transition: .4s; border-radius: 50%; 
}
input:checked + .sv-switch__slider { background-color: var(--sv-gold); }
input:checked + .sv-switch__slider:before { transform: translateX(20px); }

/* Estilos para el Spinner */
.sv-spinner { animation: sv-rotate 1s linear infinite; vertical-align: middle; }
@keyframes sv-rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
@keyframes sv-shake {
  0%, 100% { transform: translateX(0); }
  20%       { transform: translateX(-6px); }
  40%       { transform: translateX(6px); }
  60%       { transform: translateX(-4px); }
  80%       { transform: translateX(4px); }
}
</style>
