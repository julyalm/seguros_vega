@forelse($polizas as $poliza)
<tr class="sv-table__row">
  <td>
    <span class="sv-mono">{{ $poliza->numero_poliza }}</span>
    @if(request('search'))
      @php
        $searchTerm = strtolower(request('search'));
        $matchedVin = $poliza->vehiculos
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
      <div class="sv-table__avatar">{{ strtoupper(substr($poliza->asegurado->nombre, 0, 2)) }}</div>
      <span>{{ $poliza->asegurado->nombre }}</span>
    </div>
  </td>
  <td>
    <span class="sv-badge @if($poliza->ramo === 'Autos') sv-badge--ramo-autos @elseif($poliza->ramo === 'GMM') sv-badge--ramo-gmm @else sv-badge--ramo-danos @endif">
      {{ $poliza->ramo }}
    </span>
  </td>
  <td>{{ $poliza->aseguradora->nombre }}</td>
  <td>{{ $poliza->fecha_fin->format('d/m/Y') }}</td>
  <td class="sv-mono" style="font-weight: 700; color: var(--sv-navy);">${{ number_format($poliza->prima_total, 2) }}</td>
  @if(auth()->user()->role === 'admin')
  <td>
    <div style="display: flex; align-items: center; gap: 8px;">
        <div style="width: 24px; height: 24px; border-radius: 50%; background: var(--sv-gray-100); color: var(--sv-gray-600); display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 700;">{{ strtoupper(substr($poliza->user->name, 0, 1)) }}</div>
        <span class="sv-tag sv-tag--outline" style="font-size: 11px;">{{ $poliza->user->name }}</span>
    </div>
  </td>
  @endif
  <td>
    @php $isExpired = $poliza->fecha_fin->isPast(); @endphp
    <span class="sv-badge {{ !$poliza->is_active ? 'sv-badge--gray' : ($isExpired ? 'sv-badge--expired' : 'sv-badge--active') }}">
      {{ !$poliza->is_active ? 'Inactiva' : ($isExpired ? 'Vencida' : 'Activa') }}
    </span>
  </td>
  <td>
    <div class="sv-table__actions" style="display: flex; align-items: center; gap: 8px;">
     <form action="{{ route('polizas.toggle-active', $poliza->id) }}" method="POST" style="margin: 0;">
        @csrf
        <button type="submit" class="sv-action-btn" style="color: {{ $poliza->is_active ? 'var(--sv-gold)' : 'var(--sv-gray-400)' }}; background: transparent; border: none; padding: 0; cursor: pointer; display: flex;" title="{{ $poliza->is_active ? 'Desactivar Póliza' : 'Activar Póliza' }}">
           @if($poliza->is_active)
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
      <a href="{{ route('polizas.show', $poliza) }}" class="sv-action-btn" title="Ver detalle">
        <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 15a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z"/><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 0 1 0-1.113ZM17.25 12a5.25 5.25 0 1 1-10.5 0 5.25 5.25 0 0 1 10.5 0Z" clip-rule="evenodd"/></svg>
      </a>
    </div>
  </td>
</tr>
@empty
<tr>
  <td colspan="10" style="padding: 60px; text-align: center;">
    <div style="color: var(--sv-gray-300); margin-bottom: 12px;">
        <svg width="48" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 6a3 3 0 0 0-6 0v.375c0 .103.084.187.188.187h5.624a.187.187 0 0 0 .188-.187V6Z"/><path fill-rule="evenodd" d="M7.5 4.875V6A4.5 4.5 0 0 0 3 10.5v8.625C3 20.433 4.067 21.5 5.375 21.5h13.25c1.308 0 2.375-1.067 2.375-2.375V10.5A4.5 4.5 0 0 0 16.5 6V4.875c0-1.45-1.175-2.625-2.625-2.625h-3.75C8.675 2.25 7.5 3.425 7.5 4.875ZM4.5 10.5a3 3 0 0 1 3-3h9a3 3 0 0 1 3 3v8.625c0 .483-.392.875-.875.875H5.375a.875.875 0 0 1-.875-.875V10.5Z" clip-rule="evenodd"/></svg>
    </div>
    <p style="color: var(--sv-gray-500); font-weight: 500;">No se encontraron pólizas con los criterios seleccionados.</p>
    <a href="{{ route('polizas.index') }}" class="sv-btn sv-btn--sm sv-btn--outline" style="margin-top: 12px;">Ver todas las pólizas</a>
  </td>
</tr>
@endforelse
