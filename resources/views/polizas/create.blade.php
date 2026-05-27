@extends('layouts.app')

@section('title', 'Nueva Póliza — Seguros Vega')
@section('page-title', 'Nueva Póliza')

@section('content')

<form action="{{ route('polizas.store') }}" method="POST" enctype="multipart/form-data"
      class="sv-wizard" x-data="polizaWizard()" x-init="init()" @submit.prevent="submitForm($event)" novalidate>
  @csrf

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
            <template x-if="currentStep > i">
              <svg width="16" viewBox="0 0 24 24" fill="currentColor">
                <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 0 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.207Z" clip-rule="evenodd"/>
              </svg>
            </template>
            <template x-if="currentStep <= i">
              <span x-text="i + 1"></span>
            </template>
          </div>
          <span class="sv-wizard__step-label" x-text="step.label"></span>
          <div class="sv-wizard__step-line" x-show="i < steps.length - 1"></div>
        </div>
      </template>

    </div>
  </div>

  <!-- ══ CONTENIDO DEL PASO ════════════════════════════════ -->
  <div class="sv-wizard__body">

    @if($errors->any())
      <div class="sv-alert sv-alert--error" style="margin-bottom: 24px;">
        <svg width="18" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0">
          <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
        </svg>
        <div>
          <strong>Revisa los siguientes errores:</strong>
          <ul style="margin: 4px 0 0 20px; font-size: 13px;">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

  <!-- ══ BANNER DE ERRORES POR PASO ════════════════════════════ -->
  <div id="sv-step-errors"
       x-show="showStepErrors"
       x-transition:enter="transition ease-out duration-200"
       x-transition:enter-start="opacity-0 transform -translate-y-2"
       x-transition:enter-end="opacity-100 transform translate-y-0"
       x-transition:leave="transition ease-in duration-150"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       style="display:none; margin-bottom: 20px; padding: 14px 18px;
              background: #fef2f2; border: 1.5px solid #fca5a5;
              border-radius: 12px; animation: sv-shake .3s ease;">
    <div style="display: flex; align-items: flex-start; gap: 10px;">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"
           style="color: #dc2626; flex-shrink: 0; margin-top: 1px;">
        <path fill-rule="evenodd" d="M9.401 3.003c1.155-2 4.043-2 5.197 0l7.355 12.748c1.154 2-.29 4.5-2.599 4.5H4.645c-2.309 0-3.752-2.5-2.598-4.5L9.4 3.003ZM12 8.25a.75.75 0 0 1 .75.75v3.75a.75.75 0 0 1-1.5 0V9a.75.75 0 0 1 .75-.75Zm0 8.25a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Z" clip-rule="evenodd"/>
      </svg>
      <div style="flex: 1;">
        <p style="margin: 0 0 6px; font-size: 13px; font-weight: 700; color: #991b1b;">
          Corrige los siguientes errores antes de continuar:
        </p>
        <ul style="margin: 0; padding-left: 18px;">
          <template x-for="err in stepErrors" :key="err">
            <li style="font-size: 12px; color: #dc2626; margin-bottom: 3px;" x-text="err"></li>
          </template>
        </ul>
      </div>
      <button type="button" @click="clearStepErrors()"
              style="background: none; border: none; cursor: pointer; color: #dc2626; padding: 0; line-height: 1;">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>
  </div>

  <div x-show="steps[currentStep]?.label === 'Agente'">@include('polizas.partials._step-agente')</div>
    <div x-show="steps[currentStep]?.label === 'Ramo'">@include('polizas.partials._step-ramo')</div>
    <div x-show="steps[currentStep]?.label === 'Asegurado'">@include('polizas.partials._step-asegurado')</div>
    <div x-show="steps[currentStep]?.label === 'Generales'">@include('polizas.partials._step-generales')</div>
    <div x-show="steps[currentStep]?.label === 'Recibos'">@include('polizas.partials._step-recibos')</div>
    <div x-show="steps[currentStep]?.label === 'Detalle' && ramo === 'Autos'">@include('polizas.partials._step-vehiculo')</div>
    <div x-show="steps[currentStep]?.label === 'Detalle' && ramo === 'GMM'">@include('polizas.partials._step-gmm')</div>
    <div x-show="steps[currentStep]?.label === 'Archivos'">@include('polizas.partials._step-aseguradora')</div>

  </div>

  <!-- ══ NAVEGACIÓN DEL WIZARD ═════════════════════════════ -->
  <div class="sv-wizard__nav">
    <button type="button" class="sv-btn sv-btn--outline" @click="prev()" x-show="currentStep > 0">← Anterior</button>
    <div style="flex:1"></div>
    <a href="{{ route('dashboard') }}" class="sv-btn sv-btn--outline sv-btn--ghost">Cancelar</a>

    <button type="button" class="sv-btn sv-btn--primary" @click="next()" x-show="currentStep < steps.length - 1"
            :disabled="steps[currentStep]?.label === 'Recibos' && parseFloat(totalRecibos) > parseFloat(prima_total)"
            :title="steps[currentStep]?.label === 'Recibos' && parseFloat(totalRecibos) > parseFloat(prima_total) ? 'La suma no puede exceder la prima total' : ''">
      Siguiente →
    </button>
    <button type="submit" class="sv-btn sv-btn--success" x-show="currentStep === steps.length - 1" :disabled="isSubmitting">
      <template x-if="!isSubmitting">
        <span>✓ Guardar Póliza</span>
      </template>
      <template x-if="isSubmitting">
        <span>Cargando...</span>
      </template>
    </button>
  </div>

</form>

@endsection

@push('scripts')
<script>
function polizaWizard() {
  return {
    currentStep: 0,
    ramo: null,
    agente_id: '{{ auth()->user()->role === "admin" ? "" : auth()->id() }}',
    agente_aseguradora_id: null,
    esFlotilla: false,
    flotillaExistente: false,
    frecuenciaPago: 'Anual',
    fechaInicio: '',
    fechaFin: '',
    numeroPoliza: '',
    parentPolicy: null,
    buscandoPadre: false,
    padreNotFound: false,
    cantVehiculos: 1,
    vehiculos: [],
    buscandoRfc: false,
    rfcNotFound: false,
    isSubmitting: false,
    polizaExiste: false,
    verificandoPoliza: false,
    isNewAddress: true,
    stepErrors: [],      // array of error strings for current step
    showStepErrors: false,
    asegurado: {
      id: null,
      nombre: '',
      rfc: '',
      nacimiento: '',
      genero: '',
      email: '',
      telefono: '',
      direcciones: [],
      direccion_id: null,
      cp: '',
      estado: '',
      municipio: '',
      colonia: '',
      calle: '',
      num_ext: '',
      num_int: '',
      alias: 'Principal',
      colonias: []
    },
    // Premium breakdown
    prima_neta: 0,
    derechos: 0,
    recargo: 0,
    iva: 0,
    comision: 0,
    get prima_total() {
      return (parseFloat(this.prima_neta || 0) +
              parseFloat(this.derechos || 0) +
              parseFloat(this.recargo || 0) +
              parseFloat(this.iva || 0)).toFixed(2);
    },
    init() {
      this.initVehicles();
      this.$watch('frecuenciaPago', () => this.recibos = []);
      this.$watch('prima_neta', () => { this.recibos = []; this.recalcComision(); });
      this.$watch('iva', () => this.recibos = []);
      this.$watch('derechos', () => this.recibos = []);
      this.$watch('recargo', () => this.recibos = []);
      this.$watch('fechaInicio', () => this.recibos = []);
      this.$watch('flotillaExistente', () => this.recibos = []);
      // Recalcular comisión si cambia el tipo del primer vehículo
      this.$watch('vehiculos', () => this.recalcComision(), { deep: true });
    },
    // Calcula la comisión según el tipo del primer vehículo (ramo AUTO)
    // Pick-Up / Tractos / Equipo Pesado → 8%  |  Auto → 10%
    recalcComision() {
      if (this.ramo !== 'Autos' || this.parentPolicy) return;
      const tipo = this.vehiculos[0]?.tipo;
      if (!tipo) return;
      const pNeta = parseFloat(this.prima_neta || 0);
      if (['Pick-Up', 'Tractos', 'Equipo Pesado'].includes(tipo)) {
        this.comision = parseFloat((pNeta * 0.08).toFixed(2));
      } else if (tipo === 'Auto') {
        this.comision = parseFloat((pNeta * 0.10).toFixed(2));
      }
    },
    updateExpiration() {
      if (!this.fechaInicio) {
        this.fechaFin = '';
        return;
      }
      const start = new Date(this.fechaInicio + 'T12:00:00');
      start.setFullYear(start.getFullYear() + 1);
      this.fechaFin = start.toISOString().split('T')[0];
    },
    checkPolicyAvailability() {
      if (!this.numeroPoliza) {
        this.polizaExiste = false;
        return;
      }
      this.verificandoPoliza = true;
      fetch(`/api/polizas/check-availability/${this.numeroPoliza}`)
        .then(res => res.json())
        .then(json => {
          this.polizaExiste = json.exists;
        })
        .catch(err => console.error(err))
        .finally(() => this.verificandoPoliza = false);
    },
    checkVinAvailability(index) {
      const veh = this.vehiculos[index];
      if (!veh.vin || veh.vin.length < 5) {
        veh.vinExists = false;
        return;
      }
      fetch(`/api/vehiculos/check-vin/${veh.vin}`)
        .then(res => res.json())
        .then(json => {
          veh.vinExists = json.exists;
        })
        .catch(err => console.error(err));
    },
    // Receipt detailed management
    periodoGracia: 30,
    recibos: [],
    showBreakdownModal: false,
    editingRecibo: null,

    openBreakdown(index) {
       this.editingRecibo = this.recibos[index];
       this.showBreakdownModal = true;
    },

    // Watchers manually called from @change or init
    initRecibos() {
      if (!this.fechaInicio) {
        this.recibos = [];
        return;
      }

      const freqs = { 'Anual': 1, 'Semestral': 2, 'Trimestral': 4, 'Mensual': 12 };
      const baseFreq = (this.flotillaExistente && this.parentPolicy)
                       ? (this.parentPolicy.frecuencia_pago || 'Anual')
                       : this.frecuenciaPago;

      const cantOriginal = freqs[baseFreq] || 1;

      // Filter parent receipts by start date if inclusion
      let filteredVencimientos = [];
      if (this.parentPolicy && this.parentPolicy.vencimientos_pendientes) {
        filteredVencimientos = this.parentPolicy.vencimientos_pendientes.filter(v => v >= this.fechaInicio);
      }

      const cant = (this.flotillaExistente && this.parentPolicy)
                   ? filteredVencimientos.length
                   : cantOriginal;

      const pNeta = parseFloat(this.prima_neta || 0);
      const pIva = parseFloat(this.iva || 0);
      const pRecargo = parseFloat(this.recargo || 0);
      const pDerechos = parseFloat(this.derechos || 0);

      const netaFracc = Math.round((pNeta / cantOriginal) * 100) / 100;
      const ivaFracc = Math.round((pIva / cantOriginal) * 100) / 100;
      const recFracc = Math.round((pRecargo / cantOriginal) * 100) / 100;

      const interval = 12 / cantOriginal;
      const newRecibos = [];

      let accumNeta = 0;
      let accumIva = 0;
      let accumRec = 0;

      for (let i = 0; i < cant; i++) {
        const start = new Date(this.fechaInicio + 'T12:00:00');
        start.setMonth(start.getMonth() + (i * interval));
        const end = new Date(start);
        end.setMonth(end.getMonth() + interval);

        let currentNeta = netaFracc;
        let currentIva = ivaFracc;
        let currentRec = recFracc;

        // If it's the last one, we calculate the remainder to bridge rounding gaps
        if (i === cant - 1) {
          currentNeta = parseFloat((pNeta - accumNeta).toFixed(2));
          currentIva = parseFloat((pIva - accumIva).toFixed(2));
          currentRec = parseFloat((pRecargo - accumRec).toFixed(2));
        } else {
          accumNeta = parseFloat((accumNeta + currentNeta).toFixed(2));
          accumIva = parseFloat((accumIva + currentIva).toFixed(2));
          accumRec = parseFloat((accumRec + currentRec).toFixed(2));
        }

        let der = (i === 0 && !this.parentPolicy) ? pDerechos : 0;

        const r = {
          indice: i + 1,
          prima_neta: currentNeta,
          derechos: der,
          recargo: currentRec,
          iva: currentIva,
          fecha_inicio_vigencia: start.toISOString().split('T')[0],
          fecha_fin_vigencia: end.toISOString().split('T')[0],
        };

        this.updateReciboDeadline(r);
        newRecibos.push(r);
      }

      this.recibos = newRecibos;
    },

    updateReciboDeadline(recibo) {
      if (!recibo.fecha_inicio_vigencia) return;
      const date = new Date(recibo.fecha_inicio_vigencia + 'T12:00:00');
      date.setDate(date.getDate() + parseInt(this.periodoGracia || 0));
      recibo.fecha_vencimiento = date.toISOString().split('T')[0];
    },

    propagateRecibo(index) {
      const source = this.recibos[index];
      for (let i = index + 1; i < this.recibos.length; i++) {
        this.recibos[i].prima_neta = source.prima_neta;
        this.recibos[i].derechos = source.derechos;
        this.recibos[i].recargo = source.recargo;
        this.recibos[i].iva = source.iva;
        // Don't propagate dates usually as they are sequential
      }
    },

    get totalRecibos() {
       return this.recibos.reduce((acc, r) => acc + (parseFloat(r.prima_neta) + parseFloat(r.derechos) + parseFloat(r.recargo) + parseFloat(r.iva)), 0).toFixed(2);
    },
    get steps() {
      const baseSteps = [
        { label: 'Agente' },
        { label: 'Ramo' },
        { label: 'Asegurado' },
      ];

      // Insertar "Detalle" ANTES de "Generales" para Autos y GMM
      if (this.ramo === 'Autos' || this.ramo === 'GMM') {
        baseSteps.push({ label: 'Detalle' });
      }

      baseSteps.push({ label: 'Generales' });
      baseSteps.push({ label: 'Recibos' });
      baseSteps.push({ label: 'Archivos' });
      return baseSteps;
    },
    isIncisoUsed(val) {
      if (!val || !this.parentPolicy) return false;
      return this.incisosUsados.some(i => String(i) === String(val));
    },
    lookupParent() {
      let num = document.querySelector('input[name="numero_poliza_padre"]')?.value;
      if (!num) return;

      this.buscandoPadre = true;
      this.padreNotFound = false;

      fetch(`/api/polizas/lookup-parent/${num}`)
        .then(res => {
          if (!res.ok) throw new Error();
          return res.json();
        })
        .then(json => {
          if (json.status === 'success') {
            const p = json.data;
            this.parentPolicy = p;
            this.incisosUsados = p.incisos_usados || [];
            this.fechaFin = p.fecha_fin;
            this.frecuenciaPago = p.frecuencia_pago;
            this.ramo = p.ramo;
            this.numeroPoliza = p.numero_poliza;
            this.prima_neta = p.prima_neta;
            this.derechos = p.derechos;
            this.recargo = p.recargo;
            this.iva = p.iva;
            this.comision = p.comision;
            this.cantVehiculos = 1;
            this.initVehicles();
            this.recibos = [];
          }
        })
        .catch(() => {
          this.padreNotFound = true;
          this.parentPolicy = null;
        })
        .finally(() => this.buscandoPadre = false);
    },
    get proratedSugerido() {
       if (!this.parentPolicy || !this.fechaInicio) return null;
       const start = new Date(this.fechaInicio + 'T12:00:00');
       const end = new Date(this.parentPolicy.fecha_fin + 'T12:00:00');
       const diffTime = end - start;
       const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
       return diffDays > 0 ? diffDays : 0;
    },
    initVehicles() {
      const count = Math.max(1, parseInt(this.cantVehiculos) || 1);
      const currentCount = this.vehiculos.length;

      if (count > currentCount) {
        for (let i = currentCount; i < count; i++) {
          this.vehiculos.push({
            inciso: i + 1,
            tipo: 'Auto',
            modelo: new Date().getFullYear(),
            marca: '',
            submarca: '',
            vin: '',
            vinExists: false,
            motor: '',
            placas: ''
          });
        }
      } else if (count < currentCount) {
        this.vehiculos = this.vehiculos.slice(0, count);
      }
    },
    lookupRfc() {
      if (this.asegurado.rfc.length < 11) return;
      this.buscandoRfc = true;
      this.rfcNotFound = false;

      fetch(`/api/asegurados/${this.asegurado.rfc}`)
        .then(response => {
          if (!response.ok) throw new Error('No encontrado');
          return response.json();
        })
        .then(json => {
          if (json.status === 'success') {
            const d = json.data;
            this.asegurado.id = d.id;
            this.asegurado.nombre = d.nombre || '';
            this.asegurado.nacimiento = d.fecha_nacimiento || '';
            this.asegurado.genero = d.genero || '';
            this.asegurado.email = d.email || '';
            this.asegurado.telefono = d.telefono || '';
            this.asegurado.direcciones = d.direcciones || [];

            if (this.asegurado.direcciones.length > 0) {
              this.isNewAddress = false;
              this.asegurado.direccion_id = this.asegurado.direcciones[0].id;
            } else {
              this.isNewAddress = true;
            }
            this.rfcNotFound = false;
          }
        })
        .catch(err => {
          this.rfcNotFound = true;
          this.asegurado.id = null;
          this.asegurado.nombre = '';
          this.asegurado.direcciones = [];
          this.isNewAddress = true;
        })
        .finally(() => this.buscandoRfc = false);
    },
    fetchSepomex() {
      if (this.asegurado.cp.length !== 5) return;
      fetch(`/api/sepomex/${this.asegurado.cp}`)
        .then(res => res.json())
        .then(res => {
          if (res.status === 'success') {
            this.asegurado.estado = res.data.estado;
            this.asegurado.municipio = res.data.municipio;
            this.asegurado.colonias = res.data.colonias;
            if (this.asegurado.colonias.length > 0) this.asegurado.colonia = this.asegurado.colonias[0];
          }
        })
        .catch(err => console.error(err));
    },
    // ── Validación por paso ──────────────────────────────────────────────
    validateStep() {
      const errors = [];
      const label = this.steps[this.currentStep]?.label;

      // ── Paso 0: Agente ──
      if (label === 'Agente') {
        if (!this.parentPolicy && !this.agente_aseguradora_id)
          errors.push('Debes seleccionar una aseguradora antes de continuar.');
        @if(auth()->user()->role === 'admin')
        if (!this.agente_id)
          errors.push('Debes seleccionar el agente responsable de esta póliza.');
        @endif
      }

      // ── Paso 1: Ramo ──
      if (label === 'Ramo') {
        if (!this.ramo) errors.push('Debes seleccionar un ramo para continuar.');
        if (this.esFlotilla && this.flotillaExistente) {
          const numPadre = document.querySelector('input[name="numero_poliza_padre"]')?.value?.trim();
          if (!numPadre) errors.push('Ingresa el número de la póliza padre de la flotilla.');
          if (this.padreNotFound) errors.push('La póliza padre no fue encontrada. Verifica el número.');
        }
      }

      // ── Paso 2: Asegurado ──
      if (label === 'Asegurado') {
        if (!this.asegurado.rfc || this.asegurado.rfc.length < 11)
          errors.push('El RFC debe tener al menos 11 caracteres (persona moral sin homoclave).');
        if (!this.asegurado.nombre?.trim())
          errors.push('El nombre completo del asegurado es obligatorio.');
        if (this.isNewAddress) {
          if (!this.asegurado.cp?.trim())     errors.push('El código postal es obligatorio.');
          if (!this.asegurado.estado?.trim()) errors.push('El estado es obligatorio.');
          if (!this.asegurado.municipio?.trim()) errors.push('El municipio es obligatorio.');
          if (!this.asegurado.colonia?.trim()) errors.push('La colonia es obligatoria.');
          if (!this.asegurado.calle?.trim())  errors.push('La calle es obligatoria.');
          if (!this.asegurado.num_ext?.trim()) errors.push('El número exterior es obligatorio.');
        } else {
          if (!this.asegurado.direccion_id)
            errors.push('Selecciona una dirección guardada o ingresa una nueva.');
        }
      }

      // ── Paso 3: Generales ──
      if (label === 'Generales') {
        if (!this.numeroPoliza?.trim())
          errors.push('El número de póliza es obligatorio.');
        if (this.polizaExiste)
          errors.push('El número de póliza ya está registrado en el sistema.');
        if (!this.fechaInicio)
          errors.push('La fecha de inicio es obligatoria.');
        if (!this.fechaFin)
          errors.push('La fecha de vencimiento es obligatoria.');
        if (!this.prima_neta || parseFloat(this.prima_neta) <= 0)
          errors.push('La prima neta debe ser mayor a cero.');
        if (this.iva === '' || this.iva === null || isNaN(parseFloat(this.iva)))
          errors.push('El IVA es obligatorio (puede ser 0).');
      }

      // ── Paso 4: Recibos ──
      if (label === 'Recibos') {
        if (this.recibos.length === 0)
          errors.push('Debes generar al menos un recibo antes de continuar.');
      }

      // ── Paso Detalle: Autos ──
      if (label === 'Detalle' && this.ramo === 'Autos') {
        this.vehiculos.forEach((v, i) => {
          const num = i + 1;
          if (!v.marca?.trim())     errors.push(`Vehículo #${num}: la marca es obligatoria.`);
          if (!v.submarca?.trim())  errors.push(`Vehículo #${num}: la submarca es obligatoria.`);
          if (!v.modelo)            errors.push(`Vehículo #${num}: el modelo (año) es obligatorio.`);
          if (!v.vin?.trim())       errors.push(`Vehículo #${num}: el VIN / número de serie es obligatorio.`);
          if (v.vinExists)          errors.push(`Vehículo #${num}: el VIN ya está registrado en el sistema.`);
          if (this.isIncisoUsed(v.inciso))
            errors.push(`Vehículo #${num}: el inciso ${v.inciso} ya existe en la flotilla.`);
        });
        // Check duplicate incisos within this submission
        const incisos = this.vehiculos.map(v => String(v.inciso));
        if (new Set(incisos).size !== incisos.length)
          errors.push('Los incisos de los vehículos deben ser únicos entre sí.');
      }

      // ── Último paso: Archivos (sin validación obligatoria) ──
      // Los archivos son opcionales, no se valida nada aquí.

      this.stepErrors = errors;
      this.showStepErrors = errors.length > 0;
      return errors.length === 0;
    },

    clearStepErrors() {
      this.stepErrors = [];
      this.showStepErrors = false;
    },

    next() {
      if (this.currentStep >= this.steps.length - 1) return;

      if (!this.validateStep()) {
        // Scroll al banner de errores
        this.$nextTick(() => {
          document.getElementById('sv-step-errors')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
        return;
      }

      // Inicializar recibos al salir del paso Generales
      if (this.steps[this.currentStep]?.label === 'Generales') {
        if (this.recibos.length === 0) this.initRecibos();
      }

      this.clearStepErrors();
      this.currentStep++;
    },
    prev() {
      if (this.currentStep > 0) {
        this.clearStepErrors();
        this.currentStep--;
      }
    },
    goTo(index) {
      this.clearStepErrors();
      this.currentStep = index;
    },
    submitForm(e) {
      if (this.isSubmitting) return;

      // Validar el último paso antes de enviar
      if (!this.validateStep()) {
        this.$nextTick(() => {
          document.getElementById('sv-step-errors')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        });
        return;
      }

      this.isSubmitting = true;
      let formData = new FormData(e.target);
      fetch(e.target.action, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: formData
      })
      .then(async r => {
        const d = await r.json();
        if (r.ok && d.status === 'success') window.location.href = d.redirect;
        else {
          this.stepErrors = [d.message || 'Error al guardar la póliza. Intenta de nuevo.'];
          this.showStepErrors = true;
          this.isSubmitting = false;
        }
      })
      .catch(e => {
        console.error(e);
        this.stepErrors = ['Error de conexión. Verifica tu red e intenta de nuevo.'];
        this.showStepErrors = true;
        this.isSubmitting = false;
      });
    }
  }
}
</script>
@endpush
