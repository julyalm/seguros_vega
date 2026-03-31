@extends('layouts.app')

@section('title', 'Nueva Póliza — Seguros Vega')
@section('page-title', 'Nueva Póliza')

@section('content')

<div class="sv-wizard" x-data="polizaWizard()">

  <!-- ══ PROGRESS STEPS ═══════════════════════════════════ -->
  <div class="sv-wizard__steps">
    <div class="sv-wizard__steps-inner">

      <template x-for="(step, i) in steps" :key="i">
        <div class="sv-wizard__step"
             :class="{
               'active': currentStep === i,
               'completed': currentStep > i,
               'disabled': currentStep < i
             }">
          <div class="sv-wizard__step-circle" @click="if(currentStep > i) goTo(i)">
            <!-- Ícono de check si completado -->
            <template x-if="currentStep > i">
              <svg width="16" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
              </svg>
            </template>
            <!-- Número si no completado -->
            <template x-if="currentStep <= i">
              <span x-text="i + 1"></span>
            </template>
          </div>
          <span class="sv-wizard__step-label" x-text="step.label"></span>
          <!-- Línea conectora -->
          <div class="sv-wizard__step-line" x-show="i < steps.length - 1"></div>
        </div>
      </template>

    </div>
  </div>

  <!-- ══ CONTENIDO DEL PASO ════════════════════════════════ -->
  <div class="sv-wizard__body">

    <!-- PASO 1: RAMO -->
    <div x-show="currentStep === 0" class="sv-wizard__panel">
      @include('polizas.partials._step-ramo')
    </div>

    <!-- PASO 2: DATOS DEL ASEGURADO -->
    <div x-show="currentStep === 1" class="sv-wizard__panel">
      @include('polizas.partials._step-asegurado')
    </div>

    <!-- PASO 3: GENERALES -->
    <div x-show="currentStep === 2" class="sv-wizard__panel">
      @include('polizas.partials._step-generales')
    </div>

    <!-- PASO 4: RECIBOS -->
    <div x-show="currentStep === 3" class="sv-wizard__panel">
      @include('polizas.partials._step-recibos')
    </div>

    <!-- PASO 5: VEHÍCULO (solo si ramo = Autos) -->
    <div x-show="currentStep === 4 && form.ramo === 'Autos'" class="sv-wizard__panel">
      @include('polizas.partials._step-vehiculo')
    </div>

    <!-- PASO 5B: GMM datos extra -->
    <div x-show="currentStep === 4 && form.ramo === 'GMM'" class="sv-wizard__panel">
      @include('polizas.partials._step-gmm')
    </div>

    <!-- PASO 6: ASEGURADORA -->
    <div x-show="currentStep === 5" class="sv-wizard__panel">
      @include('polizas.partials._step-aseguradora')
    </div>

  </div>

  <!-- ══ NAVEGACIÓN DEL WIZARD ═════════════════════════════ -->
  <div class="sv-wizard__nav">
    <button
      class="sv-btn sv-btn--outline"
      @click="prev()"
      x-show="currentStep > 0"
    >
      ← Anterior
    </button>
    <div style="flex:1"></div>
    <button
      class="sv-btn sv-btn--outline sv-btn--ghost"
      @click="cancelar()"
    >
      Cancelar
    </button>
    <button
      class="sv-btn sv-btn--primary"
      @click="next()"
      x-show="currentStep < steps.length - 1"
    >
      Siguiente →
    </button>
    <button
      class="sv-btn sv-btn--success"
      @click="guardar()"
      x-show="currentStep === steps.length - 1"
    >
      ✓ Guardar Póliza
    </button>
  </div>

</div>

@endsection

@push('scripts')
<script>
function polizaWizard() {
  return {
    currentStep: 0,
    steps: [
      { label: 'Ramo' },
      { label: 'Asegurado' },
      { label: 'Generales' },
      { label: 'Recibos' },
      { label: 'Detalle' },
      { label: 'Aseguradora' },
    ],
    form: {
      ramo: null,
      esFlotilla: false,
      flotillaExistente: false,
      frecuenciaPago: 'Anual',
    },
    next() {
      if (this.currentStep < this.steps.length - 1) {
        // Saltar paso 5 si ramo no es Autos ni GMM
        if (this.currentStep === 3 && this.form.ramo === 'Daños') {
          this.currentStep = 5;
        } else {
          this.currentStep++;
        }
      }
    },
    prev() {
      if (this.currentStep > 0) {
        if (this.currentStep === 5 && this.form.ramo === 'Daños') {
          this.currentStep = 3;
        } else {
          this.currentStep--;
        }
      }
    },
    goTo(index) { this.currentStep = index; },
    cancelar() { window.location.href = '/dashboard'; },
    guardar() {
      // En maquetado: mostrar alert de éxito
      alert('✓ Póliza registrada correctamente (maquetado)');
    },
  }
}
</script>
@endpush
