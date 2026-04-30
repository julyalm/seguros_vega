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

  {{-- ── Cargar Póliza ── --}}
  <div class="sv-field" style="margin-top: 32px;"
    x-data="{
      fileName: '',
      fileSize: '',
      dragging: false,
      setFile(f) {
        if (!f) return;
        this.fileName = f.name;
        this.fileSize = f.size < 1024 * 1024
          ? (f.size / 1024).toFixed(1) + ' KB'
          : (f.size / (1024 * 1024)).toFixed(1) + ' MB';
        const dt = new DataTransfer();
        dt.items.add(f);
        this.$el.querySelector('input[type=file]').files = dt.files;
      },
      reset() {
        this.fileName = '';
        this.fileSize = '';
        this.$el.querySelector('input[type=file]').value = '';
      }
    }">
    <label class="sv-field__label">Cargar Póliza (PDF)</label>

    {{-- Zona de drop --}}
    <div x-show="!fileName"
      :style="dragging
        ? 'border-color: var(--sv-navy); background: var(--sv-navy-light);'
        : 'border-color: var(--sv-gray-300); background: var(--sv-gray-50);'"
      style="display: flex; flex-direction: column; align-items: center; justify-content: center;
             gap: 10px; padding: 28px 20px; border: 2px dashed; border-radius: 12px;
             cursor: pointer; transition: border-color .2s, background .2s;"
      @dragover.prevent="dragging = true"
      @dragleave.prevent="dragging = false"
      @drop.prevent="dragging = false; setFile($event.dataTransfer.files[0])"
      @click="$el.querySelector('input[type=file]').click()">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="1.5" :style="dragging ? 'color: var(--sv-navy)' : 'color: var(--sv-gray-400)'">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
      </svg>
      <span style="font-size: 13px; color: var(--sv-gray-500); pointer-events: none;">
        <strong style="color: var(--sv-navy);">Selecciona un archivo</strong> o arrástralo aquí
      </span>
      <span style="font-size: 11px; color: var(--sv-gray-400); pointer-events: none;">Solo PDF · Máx. 10 MB</span>
      <input type="file" name="archivo_poliza" accept="application/pdf" style="display:none;"
        @change="setFile($event.target.files[0])">
    </div>

    {{-- Estado éxito --}}
    <div x-show="fileName" x-cloak
      style="display: flex; align-items: center; gap: 14px; padding: 16px 18px;
             background: #f0fdf4; border: 1.5px solid #22c55e; border-radius: 12px;">
      <div style="width: 40px; height: 40px; border-radius: 10px; background: #22c55e;
                  display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
      </div>
      <div style="flex: 1; min-width: 0;">
        <p style="margin:0; font-size:13px; font-weight:600; color:#15803d;
                  white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" x-text="fileName"></p>
        <p style="margin:2px 0 0; font-size:11px; color:#16a34a;" x-text="fileSize"></p>
      </div>
      <button type="button" title="Cambiar archivo" @click="reset()"
        style="background: none; border: none; cursor: pointer; color: #16a34a; padding: 4px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
        </svg>
      </button>
    </div>

    <span class="sv-field__hint" x-show="!fileName">Carga el documento emitido por la aseguradora para fácil acceso posterior.</span>
  </div>

  {{-- ── Cargar Recibo ── --}}
  <div class="sv-field" style="margin-top: 24px;"
    x-data="{
      fileName: '',
      fileSize: '',
      dragging: false,
      setFile(f) {
        if (!f) return;
        this.fileName = f.name;
        this.fileSize = f.size < 1024 * 1024
          ? (f.size / 1024).toFixed(1) + ' KB'
          : (f.size / (1024 * 1024)).toFixed(1) + ' MB';
        const dt = new DataTransfer();
        dt.items.add(f);
        this.$el.querySelector('input[type=file]').files = dt.files;
      },
      reset() {
        this.fileName = '';
        this.fileSize = '';
        this.$el.querySelector('input[type=file]').value = '';
      }
    }">
    <label class="sv-field__label">Cargar Recibo (PDF)</label>

    {{-- Zona de drop --}}
    <div x-show="!fileName"
      :style="dragging
        ? 'border-color: var(--sv-navy); background: var(--sv-navy-light);'
        : 'border-color: var(--sv-gray-300); background: var(--sv-gray-50);'"
      style="display: flex; flex-direction: column; align-items: center; justify-content: center;
             gap: 10px; padding: 28px 20px; border: 2px dashed; border-radius: 12px;
             cursor: pointer; transition: border-color .2s, background .2s;"
      @dragover.prevent="dragging = true"
      @dragleave.prevent="dragging = false"
      @drop.prevent="dragging = false; setFile($event.dataTransfer.files[0])"
      @click="$el.querySelector('input[type=file]').click()">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="1.5" :style="dragging ? 'color: var(--sv-navy)' : 'color: var(--sv-gray-400)'">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
      </svg>
      <span style="font-size: 13px; color: var(--sv-gray-500); pointer-events: none;">
        <strong style="color: var(--sv-navy);">Selecciona un archivo</strong> o arrástralo aquí
      </span>
      <span style="font-size: 11px; color: var(--sv-gray-400); pointer-events: none;">Solo PDF · Máx. 10 MB</span>
      <input type="file" name="archivo_recibo" accept="application/pdf" style="display:none;"
        @change="setFile($event.target.files[0])">
    </div>

    {{-- Estado éxito --}}
    <div x-show="fileName" x-cloak
      style="display: flex; align-items: center; gap: 14px; padding: 16px 18px;
             background: #f0fdf4; border: 1.5px solid #22c55e; border-radius: 12px;">
      <div style="width: 40px; height: 40px; border-radius: 10px; background: #22c55e;
                  display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
        </svg>
      </div>
      <div style="flex: 1; min-width: 0;">
        <p style="margin:0; font-size:13px; font-weight:600; color:#15803d;
                  white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" x-text="fileName"></p>
        <p style="margin:2px 0 0; font-size:11px; color:#16a34a;" x-text="fileSize"></p>
      </div>
      <button type="button" title="Cambiar archivo" @click="reset()"
        style="background: none; border: none; cursor: pointer; color: #16a34a; padding: 4px;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
        </svg>
      </button>
    </div>

    <span class="sv-field__hint" x-show="!fileName">Carga el recibo de pago. Solo se sube un archivo, independientemente de la cantidad de recibos.</span>
  </div>
</div>