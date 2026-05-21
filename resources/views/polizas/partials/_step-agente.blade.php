{{-- ══ PASO 1: ASEGURADORA Y AGENTE ══════════════════════════════════ --}}
<div class="sv-form-section">
  <h3 class="sv-form-section__title">Aseguradora y Agente</h3>
  <p class="sv-form-section__desc">Selecciona la compañía aseguradora y el agente que gestionará esta póliza.</p>

  {{-- ── Aseguradora ── --}}
  <div style="margin-bottom: 36px;">
    <label class="sv-field__label" style="display:block; margin-bottom:12px;">
      Compañía Aseguradora <span style="color:var(--sv-danger)">*</span>
    </label>

    <div class="sv-aseguradora-grid" x-show="!parentPolicy">
      @foreach($aseguradoras as $aseg)
        <label class="sv-aseg-card"
          :class="{
            'sv-aseg-card--selected': agente_aseguradora_id == {{ $aseg->id }},
            'sv-aseg-card--disabled': parentPolicy && parentPolicy.aseguradora_id != {{ $aseg->id }}
          }">
          <input type="radio" name="aseguradora_id" value="{{ $aseg->id }}"
            class="sv-aseg-card__input"
            x-model="agente_aseguradora_id"
            :disabled="parentPolicy"
            :checked="parentPolicy && parentPolicy.aseguradora_id == {{ $aseg->id }}">
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
    </div>

    {{-- Herencia de aseguradora en flotilla --}}
    <template x-if="parentPolicy">
      <div>
        <input type="hidden" name="aseguradora_id" :value="parentPolicy.aseguradora_id">
        <div style="padding: 20px; background: var(--sv-navy-light); border: 1px solid var(--sv-navy-dark);
                    border-radius: 12px; display: flex; align-items: center; gap: 16px;">
          <div style="background: white; width: 48px; height: 48px; border-radius: 50%;
                      display: flex; align-items: center; justify-content: center;
                      font-weight: 700; color: var(--sv-navy); font-size: 18px;
                      border: 2px solid var(--sv-gold);"
               x-text="parentPolicy?.aseguradora?.inicial || 'M'">
          </div>
          <div style="flex: 1;">
            <h4 style="margin: 0; color: var(--sv-navy); font-size: 14px;">Aseguradora Heredada</h4>
            <p style="margin: 4px 0 0; font-size: 12px; color: var(--sv-gray-600);">
              Esta inclusión se registrará bajo la misma compañía de la flotilla maestra.
            </p>
          </div>
        </div>
      </div>
    </template>
  </div>

  {{-- ── Agente Responsable ── --}}
  <div>
    <label class="sv-field__label" style="display:block; margin-bottom:4px;">
      Agente Responsable <span style="color:var(--sv-danger)">*</span>
    </label>
    <p class="sv-form-section__desc" style="margin-top:0; margin-bottom:14px; font-size:12px;">
      El agente seleccionado verá esta póliza en su dashboard, independientemente de quién la capture.
    </p>

    @if(auth()->user()->role === 'admin')
      {{-- Admin: puede asignar a cualquier agente --}}
      <div class="sv-field">
        <div style="position: relative;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
               stroke-width="2" style="position:absolute; left:14px; top:50%; transform:translateY(-50%);
               color:var(--sv-gray-400); pointer-events:none;">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
          </svg>
          <select name="agente_id" class="sv-field__input" x-model="agente_id"
                  style="padding-left:40px;"
                  required>
            <option value="">— Selecciona un agente —</option>
            @foreach($agentes as $agente)
              <option value="{{ $agente->id }}">
                {{ $agente->name }}
              </option>
            @endforeach
          </select>
        </div>
        <span class="sv-field__hint">El agente asignado podrá consultar y gestionar esta póliza desde su perfil.</span>
      </div>

      {{-- Tarjeta visual del agente seleccionado --}}
      <template x-if="agente_id">
        <div style="margin-top: 12px; padding: 14px 18px; background: var(--sv-navy-light);
                    border: 1px solid var(--sv-navy-dark); border-radius: 12px;
                    display: flex; align-items: center; gap: 12px;">
          <div style="width: 36px; height: 36px; border-radius: 50%; background: var(--sv-navy);
                      display: flex; align-items: center; justify-content: center;
                      color: white; font-weight: 700; font-size: 14px; flex-shrink: 0;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
              <path fill-rule="evenodd"
                d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
                clip-rule="evenodd"/>
            </svg>
          </div>
          <div>
            <p style="margin:0; font-size:13px; font-weight:600; color:var(--sv-navy);">
              Agente asignado
            </p>
            <p style="margin:2px 0 0; font-size:12px; color:var(--sv-gray-600);">
              Esta póliza aparecerá en el dashboard del agente seleccionado.
            </p>
          </div>
        </div>
      </template>

    @else
      {{-- Agente regular: se auto-asigna --}}
      <input type="hidden" name="agente_id" value="{{ auth()->id() }}">
      <div style="padding: 16px 18px; background: var(--sv-navy-light); border: 1px solid var(--sv-navy-dark);
                  border-radius: 12px; display: flex; align-items: center; gap: 14px;">
        <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--sv-navy);
                    display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="white">
            <path fill-rule="evenodd"
              d="M7.5 6a4.5 4.5 0 1 1 9 0 4.5 4.5 0 0 1-9 0ZM3.751 20.105a8.25 8.25 0 0 1 16.498 0 .75.75 0 0 1-.437.695A18.683 18.683 0 0 1 12 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 0 1-.437-.695Z"
              clip-rule="evenodd"/>
          </svg>
        </div>
        <div>
          <p style="margin:0; font-size:13px; font-weight:600; color:var(--sv-navy);">
            {{ auth()->user()->name }}
            <span style="font-size:11px; font-weight:400; color:var(--sv-gray-500); margin-left:6px;">(tú)</span>
          </p>
          <p style="margin:2px 0 0; font-size:12px; color:var(--sv-gray-600);">
            Esta póliza quedará asignada a tu perfil de agente.
          </p>
        </div>
      </div>
    @endif
  </div>
</div>
