@forelse($recibos as $recibo)
<tr class="sv-table__row {{ !$recibo->is_active ? 'opacity-50' : '' }}" style="{{ !$recibo->is_active ? 'opacity: 0.6; background-color: #fafafa;' : '' }}">
  <td>
    <span class="sv-mono">{{ $recibo->poliza->numero_poliza }}</span>
    @if(request('search'))
      @php
        $searchTerm = strtolower(request('search'));
        $matchedVin = $recibo->poliza->vehiculos
          ->first(fn($v) => str_contains(strtolower($v->vin ?? ''), $searchTerm));
      @endphp
      @if($matchedVin)
        <div style="margin-top: 4px;">
          <span class="sv-vin-match-badge">
            <svg width="10" viewBox="0 0 24 24" fill="currentColor" style="flex-shrink:0"><path d="M3.375 4.5C2.339 4.5 1.5 5.34 1.5 6.375V13.5h12V6.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM13.5 15h-12v2.625c0 1.035.84 1.875 1.875 1.875h.375a3 3 0 1 1 6 0h3a.75.75 0 0 0 .75-.75V15Z"/><path d="M8.25 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0ZM15.75 6.75a.75.75 0 0 0-.75.75v11.25c0 .087.015.17.042.248a3 3 0 0 1 5.958.464c.853-.175 1.522-.935 1.464-1.883a18.659 18.659 0 0 0-3.732-10.104 1.837 1.837 0 0 0-1.47-.725H15.75Z"/><path d="M19.5 19.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0Z"/></svg>
            Serie: {{ strtoupper($matchedVin->vin) }}
          </span>
        </div>
      @endif
    @endif
  </td>
  <td>
    <div class="sv-table__user">
      <div class="sv-table__avatar" style="width: 24px; height: 24px; font-size: 9px;">{{ strtoupper(substr($recibo->poliza->asegurado->nombre, 0, 2)) }}</div>
      <span style="font-size: 13px;">{{ $recibo->poliza->asegurado->nombre }}</span>
    </div>
  </td>
  <td style="text-align: center;"><span class="sv-tag sv-tag--gray">{{ $recibo->indice_recibo }}</span></td>
  <td>{{ $recibo->fecha_inicio_vigencia ? $recibo->fecha_inicio_vigencia->format('d/m/Y') : '-' }}</td>
  <td>{{ $recibo->fecha_fin_vigencia ? $recibo->fecha_fin_vigencia->format('d/m/Y') : '-' }}</td>
  <td>
    <span class="sv-mono {{ $recibo->status_display === 'vencido' ? 'text-red-600 font-bold' : '' }}">
        {{ $recibo->fecha_vencimiento ? $recibo->fecha_vencimiento->format('d/m/Y') : '-' }}
    </span>
  </td>
  <td class="sv-mono" style="font-weight: 700; color: var(--sv-navy);">${{ number_format($recibo->monto, 2) }}</td>
  <td class="sv-mono text-red-600">${{ number_format($recibo->contracargo, 2) }}</td>
  <td>
    <span class="sv-badge
      @if($recibo->status_display === 'pagado') sv-badge--active
      @elseif($recibo->status_display === 'vencido') sv-badge--expired
      @else sv-badge--gray @endif">
      {{ ucfirst($recibo->status_display) }}
    </span>
  </td>
  <td>
    <form action="{{ route('recibos.toggle-active', $recibo->id) }}" method="POST">
       @csrf
       <button type="submit" class="sv-btn sv-btn--sm" style="padding: 4px; background: transparent; border: none; cursor: pointer; color: {{ $recibo->is_active ? 'var(--sv-gold)' : 'var(--sv-gray-400)' }}" title="{{ $recibo->is_active ? 'Desactivar Recibo' : 'Activar Recibo' }}">
          @if($recibo->is_active)
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="6" width="20" height="12" rx="6" fill="currentColor" fill-opacity="0.2" stroke="transparent"/>
            <circle cx="16" cy="12" r="4" fill="currentColor" />
          </svg>
          @else
          <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
            <rect x="2" y="6" width="20" height="12" rx="6" />
            <circle cx="8" cy="12" r="4" fill="currentColor" />
          </svg>
          @endif
       </button>
    </form>
  </td>
  <td>
    <div class="sv-table__actions">
      <button
        @click="openEditModal('{{ route('recibos.status', $recibo->id) }}', '{{ $recibo->status === 'vencido' ? 'pendiente' : $recibo->status }}', {{ $recibo->contracargo }})"
        class="sv-action-btn"
        title="Modificar Estatus y Contracargo">
        <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.158 3.712 3.712 1.158-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/><path d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z"/></svg>
      </button>
    </div>
  </td>
</tr>
@empty
<tr>
  <td colspan="11" style="padding: 60px; text-align: center;">
    <div style="color: var(--sv-gray-300); margin-bottom: 12px;">
        <svg width="48" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M2.25 4.125c0-1.036.84-1.875 1.875-1.875h15.75c1.036 0 1.875.84 1.875 1.875V17.382l-2.073-1.036a1.5 1.5 0 0 0-1.343.08l-2.583 1.55-2.583-1.55a1.5 1.5 0 0 0-1.55 0l-2.583 1.55-2.583-1.55a1.5 1.5 0 0 0-1.344-.08L2.25 17.382V4.125ZM12 9.497a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H12.75a.75.75 0 0 1-.75-.75Zm0 3.75a.75.75 0 0 1 .75-.75h2.25a.75.75 0 0 1 0 1.5h-2.25a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd"/></svg>
    </div>
    <p style="color: var(--sv-gray-500); font-weight: 500;">No se encontraron recibos.</p>
  </td>
</tr>
@endforelse
