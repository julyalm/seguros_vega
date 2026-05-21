{{-- ══ PASO FINAL: ARCHIVOS DE LA PÓLIZA ══════════════════════════════ --}}
<div class="sv-form-section">
  <h3 class="sv-form-section__title">Archivos de la Póliza</h3>
  <p class="sv-form-section__desc">
    Carga los documentos emitidos por la aseguradora para finalizar el registro.
  </p>

  {{-- ── Cargar Póliza ── --}}
  <div class="sv-field" style="margin-top: 8px;"
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
      },
      setFileDrop(f) {
        if (!f) return;
        const dt = new DataTransfer();
        dt.items.add(f);
        this.$refs.inputPoliza.files = dt.files;
        this.setFile(f);
      },
      reset() {
        this.fileName = '';
        this.fileSize = '';
        this.$refs.inputPoliza.value = '';
      }
    }">
    <label class="sv-field__label">Cargar Póliza (PDF)</label>

    {{-- Input siempre presente en el DOM --}}
    <input type="file" name="archivo_poliza" accept="application/pdf" style="display:none;"
      x-ref="inputPoliza"
      @change="setFile($event.target.files[0])">

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
      @drop.prevent="dragging = false; setFileDrop($event.dataTransfer.files[0])"
      @click="$refs.inputPoliza.click()">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="1.5" :style="dragging ? 'color: var(--sv-navy)' : 'color: var(--sv-gray-400)'">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
      </svg>
      <span style="font-size: 13px; color: var(--sv-gray-500); pointer-events: none;">
        <strong style="color: var(--sv-navy);">Selecciona un archivo</strong> o arrástralo aquí
      </span>
      <span style="font-size: 11px; color: var(--sv-gray-400); pointer-events: none;">Solo PDF · Máx. 10 MB</span>
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
      },
      setFileDrop(f) {
        if (!f) return;
        const dt = new DataTransfer();
        dt.items.add(f);
        this.$refs.inputRecibo.files = dt.files;
        this.setFile(f);
      },
      reset() {
        this.fileName = '';
        this.fileSize = '';
        this.$refs.inputRecibo.value = '';
      }
    }">
    <label class="sv-field__label">Cargar Recibo (PDF)</label>

    {{-- Input siempre presente en el DOM --}}
    <input type="file" name="archivo_recibo" accept="application/pdf" style="display:none;"
      x-ref="inputRecibo"
      @change="setFile($event.target.files[0])">

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
      @drop.prevent="dragging = false; setFileDrop($event.dataTransfer.files[0])"
      @click="$refs.inputRecibo.click()">
      <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="1.5" :style="dragging ? 'color: var(--sv-navy)' : 'color: var(--sv-gray-400)'">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
      </svg>
      <span style="font-size: 13px; color: var(--sv-gray-500); pointer-events: none;">
        <strong style="color: var(--sv-navy);">Selecciona un archivo</strong> o arrástralo aquí
      </span>
      <span style="font-size: 11px; color: var(--sv-gray-400); pointer-events: none;">Solo PDF · Máx. 10 MB</span>
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