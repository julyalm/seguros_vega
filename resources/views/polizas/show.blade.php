@extends('layouts.app')

@section('title', 'Detalle de Póliza — Seguros Vega')
@section('page-title', 'Expediente Detallado')

@section('content')

<!-- Success/Error Messages -->
@if(session('success'))
<div class="sv-alert sv-alert--success" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)">
    <div class="sv-alert__content">
        <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span>{{ session('success') }}</span>
    </div>
    <button @click="show = false" class="sv-alert__close">
        <svg width="18" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
    </button>
</div>
@endif

@if(session('error'))
<div class="sv-alert sv-alert--error" x-data="{ show: true }" x-show="show">
    <div class="sv-alert__content">
        <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
        <span>{{ session('error') }}</span>
    </div>
    <button @click="show = false" class="sv-alert__close">
        <svg width="18" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
    </button>
</div>
@endif

<div x-data="{ 
    editAdminModalOpen: false, 
    editReceiptModalOpen: false, 
    editInsuredModalOpen: false, 
    editVehiclesModalOpen: false, 
    editPaymentsModalOpen: false, 
    editValidityModalOpen: false,
    receiptUrl: '',
    receiptStatus: 'pendiente',
    receiptContracargo: 0,
    openReceiptEdit(url, status, contracargo) {
        this.receiptUrl = url;
        this.receiptStatus = status;
        this.receiptContracargo = contracargo;
        this.editReceiptModalOpen = true;
    }
}">

<!-- ══ CABECERA DE LA PÓLIZA ════════════════════════════════════ -->
<div class="sv-detail-header">
    <div class="sv-detail-header__glass"></div>
    
    <!-- Logo Aseguradora -->
    <div class="sv-detail-header__logo">
        @if($poliza->aseguradora->logo)
            <img src="{{ asset('storage/'.$poliza->aseguradora->logo) }}" alt="{{ $poliza->aseguradora->nombre }}">
        @else
            <span>{{ $poliza->aseguradora->inicial ?? 'V' }}</span>
        @endif
    </div>

    <!-- Info Principal -->
    <div class="sv-detail-header__info">
        <div class="sv-detail-header__title-row">
            <h2 class="sv-detail-header__title">Póliza <span class="sv-mono">{{ $poliza->numero_poliza }}</span></h2>
            <span class="sv-tag @if($poliza->ramo === 'Autos') sv-tag--blue @elseif($poliza->ramo === 'GMM') sv-tag--pink @else sv-tag--green @endif">
                {{ $poliza->ramo }}
            </span>
        </div>
        <p class="sv-detail-header__subtitle">
            <span class="sv-detail-header__divider">Compañía: <strong>{{ $poliza->aseguradora->nombre }}</strong></span>
            <span>Frecuencia: <strong>{{ $poliza->frecuencia_pago }}</strong></span>
        </p>
    </div>

    <!-- Estatus y Descarga -->
    <div class="sv-detail-header__actions">
        @php $isExpired = $poliza->fecha_fin->isPast(); @endphp
        <span class="sv-badge {{ $isExpired ? 'sv-badge--expired' : 'sv-badge--active' }} sv-badge--large">
            {{ $isExpired ? 'Vencida' : 'Activa' }}
        </span>

        @if($poliza->file_path)
        <a href="{{ route('polizas.download', $poliza->id) }}" target="_blank" class="sv-btn sv-btn--outline sv-btn--sm sv-btn--icon">
            <svg width="18" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" /></svg>
            <span>Póliza PDF</span>
        </a>
        @endif

        @if($poliza->recibo_path)
        <a href="{{ route('polizas.download.recibo', $poliza->id) }}" target="_blank" class="sv-btn sv-btn--outline sv-btn--sm sv-btn--icon" style="border-color: var(--sv-gold); color: var(--sv-gold);">
            <svg width="18" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M7.875 1.5C6.839 1.5 6 2.34 6 3.375v2.99c-.426.053-.851.11-1.274.174-1.454.218-2.476 1.483-2.476 2.917v6.294a3 3 0 0 0 3 3h.27l-.155 1.705A1.875 1.875 0 0 0 7.232 22.5h9.536a1.875 1.875 0 0 0 1.867-2.045l-.155-1.705h.27a3 3 0 0 0 3-3V9.456c0-1.434-1.022-2.7-2.476-2.917A48.716 48.716 0 0 0 18 6.366V3.375c0-1.036-.84-1.875-1.875-1.875h-8.25ZM16.5 6.205v-2.83A.375.375 0 0 0 16.125 3h-8.25a.375.375 0 0 0-.375.375v2.83a49.353 49.353 0 0 1 9 0Zm-.217 8.265c.03.021.06.041.09.063l.7.518.483.357a.75.75 0 0 0 .884-1.203l-.483-.357-.738-.545A3 3 0 0 0 15 12.75H9a3 3 0 0 0-2.219.984l-.738.545-.483.357a.75.75 0 0 0 .884 1.203l.483-.357.7-.518c.03-.022.06-.042.09-.063V19.5h6v-5.03Z" clip-rule="evenodd" /></svg>
            <span>Recibo PDF</span>
        </a>
        @endif
    </div>
</div>

    <!-- COLUMNAS PRINCIPALES -->
    <div class="sv-detail-grid --main-cols">
        <!-- COLUMNA IZQUIERDA -->
        <div class="sv-column">

        <!-- Tarjeta Asegurado -->
        <div class="sv-detail-card">
            <div class="sv-detail-card__header">
                <h3 class="sv-detail-card__title">
                    <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                    Información del Asegurado
                </h3>
                @if(auth()->user()->role === 'admin')
                <button type="button" @click="editInsuredModalOpen = true" class="sv-btn sv-btn--sm sv-btn--gold">
                    <svg width="12" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.158 3.712 3.712 1.158-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/></svg>
                    Editar
                </button>
                @endif
            </div>
            <div class="sv-detail-card__body sv-detail-grid --cols-2">
                <div class="sv-detail-item">
                    <span class="sv-detail-item__label">Nombre Completo</span>
                    <strong class="sv-detail-item__value">{{ $poliza->asegurado->nombre }}</strong>
                </div>
                <div class="sv-detail-item">
                    <span class="sv-detail-item__label">RFC</span>
                    <strong class="sv-detail-item__value sv-mono">{{ $poliza->asegurado->rfc }}</strong>
                </div>
                <div class="sv-detail-item">
                    <span class="sv-detail-item__label">Teléfono</span>
                    <strong class="sv-detail-item__value">{{ $poliza->asegurado->telefono ?? 'No registrado' }}</strong>
                </div>
                <div class="sv-detail-item">
                    <span class="sv-detail-item__label">Email</span>
                    <strong class="sv-detail-item__value">{{ $poliza->asegurado->email ?? 'No registrado' }}</strong>
                </div>
            </div>
        </div>

        <!-- Tarjeta Flotilla / Vehículos -->
        @if($poliza->ramo === 'Autos')
        <div class="sv-detail-card">
            <div class="sv-detail-card__header">
                <div>
                    <h3 class="sv-detail-card__title">
                        <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 01-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 00-3.213-9.193 2.056 2.056 0 00-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 00-10.026 0 1.106 1.106 0 00-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" /></svg>
                        Unidades Aseguradas
                    </h3>
                    @if($poliza->es_flotilla)
                    <span class="sv-tag sv-tag--navy" style="margin-top: 4px;">Flotilla</span>
                    @endif
                </div>
                @if(auth()->user()->role === 'admin')
                <button type="button" @click="editVehiclesModalOpen = true" class="sv-btn sv-btn--sm sv-btn--gold">
                    <svg width="12" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.158 3.712 3.712 1.158-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/></svg>
                    Editar
                </button>
                @endif
            </div>
            <div class="sv-table-wrapper">
                <table class="sv-table">
                    <thead>
                        <tr>
                            <th>Inciso</th>
                            <th>Descripción</th>
                            <th>Serie</th>
                            <th>Placas</th>
                            <th>Vigencia</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($poliza->vehiculos as $veh)
                        <tr class="sv-table__row">
                            <td class="sv-mono --bold">#{{ $veh->inciso }}</td>
                            <td class="--bold --navy">{{ $veh->marca }} {{ $veh->submarca }} {{ $veh->modelo }}</td>
                            <td class="sv-mono --small">{{ $veh->vin }}</td>
                            <td>{{ $veh->placas }}</td>
                            <td>{{ $poliza->fecha_inicio->format('d/m/Y') }} – {{ $poliza->fecha_fin->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                        @foreach($poliza->children as $child)
                            @foreach($child->vehiculos as $veh)
                            <tr class="sv-table__row --inclusion">
                                <td class="sv-mono --bold">#{{ $veh->inciso }}</td>
                                <td>
                                    <div class="sv-inclusion-info">
                                        <span class="--bold --navy">{{ $veh->marca }} {{ $veh->submarca }} {{ $veh->modelo }}</span>
                                        <span class="sv-inclusion-tag">Inclusión: {{ $child->tipo_movimiento ?? 'Unidad' }}</span>
                                    </div>
                                </td>
                                <td class="sv-mono --small">{{ $veh->vin }}</td>
                                <td>{{ $veh->placas }}</td>
                                <td class="--bold --gold">{{ $child->fecha_inicio->format('d/m/Y') }} – {{ $poliza->fecha_fin->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Plan de Recibos -->
        <div class="sv-detail-card">
            <div class="sv-detail-card__header">
                <h3 class="sv-detail-card__title">
                    <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5m-9-6h.008v.008H12v-.008zM12 15h.008v.008H12V15zm0 2.25h.008v.008H12v-.008zM9.75 15h.008v.008H9.75V15zm0 2.25h.008v.008H9.75v-.008zM7.5 15h.008v.008H7.5V15zm0 2.25h.008v.008H7.5v-.008zm6.75-4.5h.008v.008h-.008v-.008zm0 2.25h.008v.008h-.008V15zm0 2.25h.008v.008h-.008v-.008zm2.25-4.5h.008v.008H16.5v-.008zm0 2.25h.008v.008H16.5V15z" /></svg>
                    Calendario de Pagos
                </h3>
                @if(auth()->user()->role === 'admin')
                <button type="button" @click="editPaymentsModalOpen = true" class="sv-btn sv-btn--sm sv-btn--gold">
                    <svg width="12" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.158 3.712 3.712 1.158-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/></svg>
                    Editar
                </button>
                @endif
            </div>
            <div class="sv-table-wrapper">
                <table class="sv-table">
                    <thead>
                        <tr>
                            <th># Recibo</th>
                            <th>Vencimiento</th>
                            <th class="--text-right">Monto</th>
                            <th class="--text-right">Status / Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($poliza->recibos as $recibo)
                        <tr class="sv-table__row">
                            <td class="--bold --navy">Recibo {{ $recibo->indice_recibo }}</td>
                            <td>
                                <div class="--bold">{{ \Carbon\Carbon::parse($recibo->fecha_vencimiento)->format('d/m/Y') }}</div>
                                @if($recibo->contracargo > 0)
                                <div class="--danger --small --bold">- ${{number_format($recibo->contracargo, 2)}} (Contracargo)</div>
                                @endif
                                <div class="sv-receipt-dates --small">
                                    Vigencia: {{ \Carbon\Carbon::parse($recibo->fecha_inicio_vigencia)->format('d/m/y') }} - {{ \Carbon\Carbon::parse($recibo->fecha_fin_vigencia)->format('d/m/y') }}
                                </div>
                            </td>
                            <td class="--text-right">
                                <div class="--bold --navy sv-mono" style="font-size: 16px;">${{ number_format($recibo->monto, 2) }}</div>
                                <div class="sv-receipt-breakdown">
                                    <span>Neta: ${{ number_format($recibo->prima_neta, 2) }}</span>
                                    <span>IVA: ${{ number_format($recibo->iva, 2) }}</span>
                                    @if($recibo->derechos > 0 || $recibo->recargo > 0)
                                        <span>Otros: ${{ number_format($recibo->derechos + $recibo->recargo, 2) }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="--text-right">
                                @if(auth()->user()->role === 'admin')
                                <button type="button" @click="openReceiptEdit('{{ route('recibos.status', $recibo->id) }}', '{{ $recibo->status === 'vencido' ? 'pendiente' : $recibo->status }}', {{ $recibo->contracargo }})" class="sv-status-btn">
                                @endif
                                <span class="sv-badge @if($recibo->status_display === 'pagado') sv-badge--active @elseif($recibo->status_display === 'vencido') sv-badge--expired @else sv-badge--pending @endif --fixed-width">
                                    {{ ucfirst($recibo->status_display) }}
                                </span>
                                @if(auth()->user()->role === 'admin')
                                </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- COLUMNA DERECHA -->
    <div class="sv-column --narrow">

        <!-- Card de Vigencia -->
        <div class="sv-detail-card --no-overflow">
            <div class="sv-detail-card__header">
                <h3 class="sv-detail-card__title">
                    <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Periodo de Vigencia
                </h3>
                @if(auth()->user()->role === 'admin')
                <button type="button" @click="editValidityModalOpen = true" class="sv-btn sv-btn--sm sv-btn--gold">
                    <svg width="12" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.158 3.712 3.712 1.158-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/></svg>
                    Editar
                </button>
                @endif
            </div>
            <div class="sv-detail-card__body">
                <div class="sv-timeline">
                    <div class="sv-timeline__labels">
                        <div class="sv-timeline__label --start">
                            <span>Inicio</span>
                            <strong>{{ $poliza->fecha_inicio->format('d/m/Y') }}</strong>
                        </div>
                        <div class="sv-timeline__label --end">
                            <span>Término</span>
                            <strong>{{ $poliza->fecha_fin->format('d/m/Y') }}</strong>
                        </div>
                    </div>
                    @php 
                        $totalDays = max(1, $poliza->fecha_inicio->diffInDays($poliza->fecha_fin));
                        $elapsedDays = max(0, $poliza->fecha_inicio->diffInDays(now()));
                        $progress = min(100, ($elapsedDays / $totalDays) * 100);
                        $daysLeft = now()->diffInDays($poliza->fecha_fin, false);
                    @endphp
                    <div class="sv-timeline__bar">
                        <div class="sv-timeline__progress" style="width: {{ $progress }}%"></div>
                        <div class="sv-timeline__marker" style="left: {{ $progress }}%"></div>
                    </div>
                </div>

                <div class="sv-detail-highlight @if($daysLeft < 30) --danger @else --success @endif">
                    <span class="sv-detail-highlight__label">Tiempo restante:</span>
                    <span class="sv-detail-highlight__value">{{ max(0, (int)$daysLeft) }} días</span>
                </div>
            </div>
        </div>

        <!-- Card de Costos -->
        <div class="sv-financial-card">
            <div class="sv-financial-card__header">
                <h3 class="sv-financial-card__title">
                    <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    Resumen Financiero
                </h3>
                @if(auth()->user()->role === 'admin')
                <button type="button" @click="editAdminModalOpen = true" class="sv-btn sv-btn--sm sv-btn--glass">
                    <svg width="12" viewBox="0 0 24 24" fill="currentColor"><path d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.158 3.712 3.712 1.158-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z"/></svg>
                    Editar
                </button>
                @endif
            </div>
            <div class="sv-financial-card__body">
                <div class="sv-cost-item">
                    <span>Prima Neta</span>
                    <span class="sv-mono">${{ number_format($poliza->prima_neta, 2) }}</span>
                </div>
                <div class="sv-cost-item">
                    <span>Derechos</span>
                    <span class="sv-mono">${{ number_format($poliza->derechos, 2) }}</span>
                </div>
                <div class="sv-cost-item">
                    <span>Recargos</span>
                    <span class="sv-mono">${{ number_format($poliza->recargo, 2) }}</span>
                </div>
                <div class="sv-cost-item --divider">
                    <span>I.V.A.</span>
                    <span class="sv-mono">${{ number_format($poliza->iva, 2) }}</span>
                </div>
                <div class="sv-cost-item --accent">
                    <span>Comisión de Agente</span>
                    <span class="sv-mono">${{ number_format($poliza->comision, 2) }}</span>
                </div>
                
                <div class="sv-total-box">
                    <div class="sv-total-box__label">PRIMA TOTAL</div>
                    <div class="sv-total-box__value">${{ number_format($poliza->prima_total, 2) }}</div>
                </div>
            </div>
        </div>

    </div>














    <!-- MODALS -->

    <!-- Modal: Admin Edit Policy Info -->
    <div x-show="editAdminModalOpen" x-cloak class="sv-modal-backdrop">
        <div class="sv-modal" @click.outside="editAdminModalOpen = false" @keydown.escape.window="editAdminModalOpen = false">
            <div class="sv-modal__header">
                <h3 class="sv-modal__title">Editar Datos de Póliza</h3>
                <button type="button" @click="editAdminModalOpen = false" class="sv-modal__close">
                    <svg width="20" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div class="sv-modal__body">
                <form action="{{ route('polizas.update', $poliza->id) }}" method="POST" class="sv-form-stack">
                    @csrf
                    @method('PUT')
                    <div class="sv-form-group">
                        <label class="sv-form-label">Número de Póliza</label>
                        <input type="text" name="numero_poliza" value="{{ $poliza->numero_poliza }}" class="sv-input" required>
                    </div>
                    <div class="sv-form-row">
                        <div class="sv-form-group">
                            <label class="sv-form-label">Prima Neta</label>
                            <input type="number" step="0.01" name="prima_neta" value="{{ $poliza->prima_neta }}" class="sv-input" required>
                        </div>
                        <div class="sv-form-group">
                            <label class="sv-form-label">Derechos</label>
                            <input type="number" step="0.01" name="derechos" value="{{ $poliza->derechos }}" class="sv-input" required>
                        </div>
                    </div>
                    <div class="sv-form-row">
                        <div class="sv-form-group">
                            <label class="sv-form-label">Recargos</label>
                            <input type="number" step="0.01" name="recargo" value="{{ $poliza->recargo }}" class="sv-input" required>
                        </div>
                        <div class="sv-form-group">
                            <label class="sv-form-label">I.V.A.</label>
                            <input type="number" step="0.01" name="iva" value="{{ $poliza->iva }}" class="sv-input" required>
                        </div>
                    </div>
                    <div class="sv-form-group">
                        <label class="sv-form-label">Comisión de Agente</label>
                        <input type="number" step="0.01" name="comision" value="{{ $poliza->comision }}" class="sv-input" required>
                    </div>
                    <div class="sv-modal__footer">
                        <button type="button" @click="editAdminModalOpen = false" class="sv-btn sv-btn--outline">Cancelar</button>
                        <button type="submit" class="sv-btn sv-btn--primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Receipt Status -->
    <div x-show="editReceiptModalOpen" x-cloak class="sv-modal-backdrop">
        <div class="sv-modal --width-sm" @click.outside="editReceiptModalOpen = false" @keydown.escape.window="editReceiptModalOpen = false">
            <div class="sv-modal__header">
                <h3 class="sv-modal__title">Status de Recibo</h3>
                <button type="button" @click="editReceiptModalOpen = false" class="sv-modal__close">
                    <svg width="20" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div class="sv-modal__body">
                <form :action="receiptUrl" method="POST" class="sv-form-stack">
                    @csrf
                    @method('PUT')
                    <div class="sv-form-group">
                        <label class="sv-form-label">Nuevo Status</label>
                        <select name="status" x-model="receiptStatus" class="sv-select">
                            <option value="pendiente">Pendiente</option>
                            <option value="pagado">Pagado</option>
                            <option value="vencido">Vencido</option>
                        </select>
                    </div>
                    <div class="sv-form-group">
                        <label class="sv-form-label">Monto de Contracargo</label>
                        <div class="sv-input-with-icon">
                            <span>$</span>
                            <input type="number" step="0.01" min="0" name="contracargo" x-model="receiptContracargo" class="sv-input">
                        </div>
                    </div>
                    <div class="sv-modal__footer">
                        <button type="button" @click="editReceiptModalOpen = false" class="sv-btn sv-btn--outline">Cancelar</button>
                        <button type="submit" class="sv-btn sv-btn--primary">Actualizar Recibo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Insured Information -->
    <div x-show="editInsuredModalOpen" x-cloak class="sv-modal-backdrop">
        <div class="sv-modal" @click.outside="editInsuredModalOpen = false" @keydown.escape.window="editInsuredModalOpen = false">
            <div class="sv-modal__header">
                <h3 class="sv-modal__title">Editar Asegurado</h3>
                <button type="button" @click="editInsuredModalOpen = false" class="sv-modal__close">
                    <svg width="20" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div class="sv-modal__body">
                <form action="{{ route('polizas.update.insured', $poliza->id) }}" method="POST" class="sv-form-stack">
                    @csrf
                    @method('PUT')
                    <div class="sv-form-group">
                        <label class="sv-form-label">Nombre Completo</label>
                        <input type="text" name="nombre" value="{{ $poliza->asegurado->nombre }}" class="sv-input" required>
                    </div>
                    <div class="sv-form-group">
                        <label class="sv-form-label">RFC</label>
                        <input type="text" name="rfc" value="{{ $poliza->asegurado->rfc }}" class="sv-input" required maxlength="20">
                    </div>
                    <div class="sv-form-row">
                        <div class="sv-form-group">
                            <label class="sv-form-label">Email</label>
                            <input type="email" name="email" value="{{ $poliza->asegurado->email }}" class="sv-input">
                        </div>
                        <div class="sv-form-group">
                            <label class="sv-form-label">Teléfono</label>
                            <input type="text" name="telefono" value="{{ $poliza->asegurado->telefono }}" class="sv-input" maxlength="20">
                        </div>
                    </div>
                    <div class="sv-modal__footer">
                        <button type="button" @click="editInsuredModalOpen = false" class="sv-btn sv-btn--outline">Cancelar</button>
                        <button type="submit" class="sv-btn sv-btn--primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Vehicles -->
    <div x-show="editVehiclesModalOpen" x-cloak class="sv-modal-backdrop">
        <div class="sv-modal --width-lg" @click.outside="editVehiclesModalOpen = false" @keydown.escape.window="editVehiclesModalOpen = false">
            <div class="sv-modal__header">
                <h3 class="sv-modal__title">Editar Unidades Aseguradas</h3>
                <button type="button" @click="editVehiclesModalOpen = false" class="sv-modal__close">
                    <svg width="20" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div class="sv-modal__body --scrollable">
                <form action="{{ route('polizas.update.vehicles', $poliza->id) }}" method="POST" class="sv-form-stack">
                    @csrf
                    @method('PUT')
                    
                    @foreach($poliza->vehiculos as $index => $vehiculo)
                    <div class="sv-form-section">
                        <h4 class="sv-form-section__title">Vehículo #{{ $vehiculo->inciso }}</h4>
                        <input type="hidden" name="vehiculos[{{ $index }}][id]" value="{{ $vehiculo->id }}">
                        
                        <div class="sv-form-row">
                            <div class="sv-form-group --flex-1">
                                <label class="sv-form-label">Inciso</label>
                                <input type="number" name="vehiculos[{{ $index }}][inciso]" value="{{ $vehiculo->inciso }}" class="sv-input" required min="1">
                            </div>
                            <div class="sv-form-group --flex-2">
                                <label class="sv-form-label">Marca</label>
                                <input type="text" name="vehiculos[{{ $index }}][marca]" value="{{ $vehiculo->marca }}" class="sv-input" required>
                            </div>
                            <div class="sv-form-group --flex-2">
                                <label class="sv-form-label">Submarca</label>
                                <input type="text" name="vehiculos[{{ $index }}][submarca]" value="{{ $vehiculo->submarca }}" class="sv-input" required>
                            </div>
                        </div>

                        <div class="sv-form-row">
                            <div class="sv-form-group">
                                <label class="sv-form-label">Año (Modelo)</label>
                                <input type="number" name="vehiculos[{{ $index }}][modelo]" value="{{ $vehiculo->modelo }}" class="sv-input" required>
                            </div>
                            <div class="sv-form-group">
                                <label class="sv-form-label">Serie (VIN)</label>
                                <input type="text" name="vehiculos[{{ $index }}][vin]" value="{{ $vehiculo->vin }}" class="sv-input">
                            </div>
                        </div>

                        <div class="sv-form-row">
                            <div class="sv-form-group">
                                <label class="sv-form-label">Motor</label>
                                <input type="text" name="vehiculos[{{ $index }}][motor]" value="{{ $vehiculo->motor }}" class="sv-input">
                            </div>
                            <div class="sv-form-group">
                                <label class="sv-form-label">Placas</label>
                                <input type="text" name="vehiculos[{{ $index }}][placas]" value="{{ $vehiculo->placas }}" class="sv-input">
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="sv-modal__footer">
                        <button type="button" @click="editVehiclesModalOpen = false" class="sv-btn sv-btn--outline">Cancelar</button>
                        <button type="submit" class="sv-btn sv-btn--primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Payments -->
    <div x-show="editPaymentsModalOpen" x-cloak class="sv-modal-backdrop">
        <div class="sv-modal --width-md" @click.outside="editPaymentsModalOpen = false" @keydown.escape.window="editPaymentsModalOpen = false">
            <div class="sv-modal__header">
                <h3 class="sv-modal__title">Editar Calendario de Pagos</h3>
                <button type="button" @click="editPaymentsModalOpen = false" class="sv-modal__close">
                    <svg width="20" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div class="sv-modal__body --scrollable">
                <form action="{{ route('polizas.update.payments', $poliza->id) }}" method="POST" class="sv-form-stack">
                    @csrf
                    @method('PUT')
                    
                    @foreach($poliza->recibos as $index => $recibo)
                    <div class="sv-form-section" x-data="{ 
                        neta: {{ $recibo->prima_neta }}, 
                        derechos: {{ $recibo->derechos }}, 
                        recargo: {{ $recibo->recargo }}, 
                        iva: {{ $recibo->iva }},
                        get total() { 
                            return (parseFloat(this.neta) + parseFloat(this.derechos) + parseFloat(this.recargo) + parseFloat(this.iva)).toFixed(2);
                        }
                    }">
                        <h4 class="sv-form-section__title">Recibo #{{ $recibo->indice_recibo }}</h4>
                        <input type="hidden" name="recibos[{{ $index }}][id]" value="{{ $recibo->id }}">
                        
                        <div class="sv-form-row">
                            <div class="sv-form-group --flex-1">
                                <label class="sv-form-label">Vencimiento</label>
                                <input type="date" name="recibos[{{ $index }}][fecha_vencimiento]" value="{{ \Carbon\Carbon::parse($recibo->fecha_vencimiento)->format('Y-m-d') }}" class="sv-input" required>
                            </div>
                            <div class="sv-form-group --flex-1">
                                <label class="sv-form-label">Gracia (Días)</label>
                                <input type="number" name="recibos[{{ $index }}][periodo_gracia]" value="{{ $recibo->periodo_gracia }}" class="sv-input" required min="0">
                            </div>
                            <div class="sv-form-group --flex-1">
                                <label class="sv-form-label">Monto Total</label>
                                <input type="number" step="0.01" name="recibos[{{ $index }}][monto]" :value="total" class="sv-input --bold" readonly style="background: #f8fafc; color: var(--sv-navy);">
                            </div>
                        </div>

                        <div class="sv-form-row">
                            <div class="sv-form-group">
                                <label class="sv-form-label">Vigencia Inicio</label>
                                <input type="date" name="recibos[{{ $index }}][fecha_inicio_vigencia]" value="{{ \Carbon\Carbon::parse($recibo->fecha_inicio_vigencia)->format('Y-m-d') }}" class="sv-input" required>
                            </div>
                            <div class="sv-form-group">
                                <label class="sv-form-label">Vigencia Fin</label>
                                <input type="date" name="recibos[{{ $index }}][fecha_fin_vigencia]" value="{{ \Carbon\Carbon::parse($recibo->fecha_fin_vigencia)->format('Y-m-d') }}" class="sv-input" required>
                            </div>
                        </div>

                        <div class="sv-form-row">
                            <div class="sv-form-group">
                                <label class="sv-form-label">Prima Neta</label>
                                <input type="number" step="0.01" name="recibos[{{ $index }}][prima_neta]" x-model="neta" class="sv-input" required>
                            </div>
                            <div class="sv-form-group">
                                <label class="sv-form-label">Derechos</label>
                                <input type="number" step="0.01" name="recibos[{{ $index }}][derechos]" x-model="derechos" class="sv-input" required>
                            </div>
                        </div>

                        <div class="sv-form-row">
                            <div class="sv-form-group">
                                <label class="sv-form-label">Recargo</label>
                                <input type="number" step="0.01" name="recibos[{{ $index }}][recargo]" x-model="recargo" class="sv-input" required>
                            </div>
                            <div class="sv-form-group">
                                <label class="sv-form-label">I.V.A.</label>
                                <input type="number" step="0.01" name="recibos[{{ $index }}][iva]" x-model="iva" class="sv-input" required>
                            </div>
                        </div>
                    </div>
                    @endforeach

                    <div class="sv-modal__footer">
                        <button type="button" @click="editPaymentsModalOpen = false" class="sv-btn sv-btn--outline">Cancelar</button>
                        <button type="submit" class="sv-btn sv-btn--primary">Actualizar Calendario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Validity Period -->
    <div x-show="editValidityModalOpen" x-cloak class="sv-modal-backdrop">
        <div class="sv-modal --width-sm" @click.outside="editValidityModalOpen = false" @keydown.escape.window="editValidityModalOpen = false">
            <div class="sv-modal__header">
                <h3 class="sv-modal__title">Periodo de Vigencia</h3>
                <button type="button" @click="editValidityModalOpen = false" class="sv-modal__close">
                    <svg width="20" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div class="sv-modal__body">
                <form action="{{ route('polizas.update.validity', $poliza->id) }}" method="POST" class="sv-form-stack">
                    @csrf
                    @method('PUT')
                    
                    <div class="sv-form-group">
                        <label class="sv-form-label">Fecha de Inicio</label>
                        <input type="date" name="fecha_inicio" value="{{ $poliza->fecha_inicio->format('Y-m-d') }}" class="sv-input" required>
                    </div>
                    <div class="sv-form-group">
                        <label class="sv-form-label">Fecha de Término</label>
                        <input type="date" name="fecha_fin" value="{{ $poliza->fecha_fin->format('Y-m-d') }}" class="sv-input" required>
                    </div>
                    
                    <div class="sv-modal__footer">
                        <button type="button" @click="editValidityModalOpen = false" class="sv-btn sv-btn--outline">Cancelar</button>
                        <button type="submit" class="sv-btn sv-btn--primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>

<style>
[x-cloak] { display: none !important; }

/* ── ALERTS ──────────────────────────────────────────────── */
.sv-alert {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
    box-shadow: var(--sv-shadow-md);
    animation: slideDown 0.3s ease-out;
}
.sv-alert--success {
    background: #ecfdf5;
    border: 1px solid #10b981;
    color: #065f46;
}
.sv-alert--error {
    background: #fef2f2;
    border: 1px solid #ef4444;
    color: #991b1b;
}
.sv-alert__content {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
}
.sv-alert__close {
    background: none;
    border: none;
    cursor: pointer;
    opacity: 0.6;
    transition: opacity 0.2s;
}
.sv-alert__close:hover { opacity: 1; }

@keyframes slideDown {
    from { transform: translateY(-10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

/* ── GENERAL LAYOUT ──────────────────────────────────────── */
.sv-column {
    display: flex;
    flex-direction: column;
    gap: 32px;
}
.sv-column.--narrow { flex: 0 0 32%; }

/* ── DETAIL CARDS ────────────────────────────────────────── */
.sv-detail-card {
    background: white;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}
.sv-detail-card:hover { transform: translateY(-2px); box-shadow: var(--sv-shadow-md); }
.sv-detail-card.--no-overflow { overflow: visible; }

.sv-detail-card__header {
    padding: 20px 24px;
    background: var(--sv-gray-50);
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.sv-detail-card__title {
    font-family: 'Rajdhani', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--sv-navy);
    display: flex;
    align-items: center;
    gap: 10px;
}
.sv-detail-card__body { padding: 24px; }

/* ── DETAIL ITEMS & GRIDS ────────────────────────────────── */
.sv-detail-grid { display: grid; gap: 20px; }
.sv-detail-grid.--cols-2 { grid-template-columns: 1fr 1fr; }

.sv-detail-item {
    background: #f8fafc;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid #edf2f7;
    transition: background 0.2s;
}
.sv-detail-item:hover { background: #f1f5f9; }
.sv-detail-item__label {
    display: block;
    font-size: 11px;
    color: var(--sv-gray-500);
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.1em;
    margin-bottom: 6px;
}
.sv-detail-item__value {
    display: block;
    font-size: 15px;
    color: var(--sv-navy);
    font-weight: 600;
}

/* ── TIMELINE & HIGHLIGHTS ───────────────────────────────── */
.sv-timeline { margin-bottom: 24px; }
.sv-timeline__labels {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
}
.sv-timeline__label { display: flex; flex-direction: column; gap: 2px; }
.sv-timeline__label.--end { text-align: right; }
.sv-timeline__label span { font-size: 11px; font-weight: 700; text-transform: uppercase; color: var(--sv-gray-500); }
.sv-timeline__label strong { font-size: 15px; color: var(--sv-navy); }
.sv-timeline__label.--end strong { color: var(--sv-red); }

.sv-timeline__bar {
    height: 6px;
    background: #e2e8f0;
    border-radius: 10px;
    position: relative;
    overflow: visible;
}
.sv-timeline__progress {
    height: 100%;
    background: linear-gradient(90deg, var(--sv-navy), var(--sv-gold));
    border-radius: 10px;
    transition: width 1s ease-out;
}
.sv-timeline__marker {
    position: absolute;
    top: 50%;
    width: 14px;
    height: 14px;
    background: white;
    border: 3px solid var(--sv-gold);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    box-shadow: 0 0 10px rgba(213,164,64,0.4);
}

.sv-detail-highlight {
    padding: 18px;
    border-radius: 14px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1.5px solid transparent;
}
.sv-detail-highlight.--success { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
.sv-detail-highlight.--danger { background: #fef2f2; border-color: #fecaca; color: #b91c1c; }
.sv-detail-highlight__label { font-weight: 600; font-size: 14px; }
.sv-detail-highlight__value { font-weight: 800; font-size: 18px; }

/* ── FINANCIAL CARD ──────────────────────────────────────── */
.sv-financial-card {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 20px;
    padding: 28px;
    color: white;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.2);
    position: relative;
    overflow: hidden;
}
.sv-financial-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(213,164,64,0.05), transparent 70%);
    pointer-events: none;
}
.sv-financial-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    padding-bottom: 16px;
}
.sv-financial-card__title {
    margin: 0;
    font-family: 'Rajdhani', sans-serif;
    font-size: 20px;
    font-weight: 700;
    color: var(--sv-gold);
    display: flex;
    align-items: center;
    gap: 8px;
}
.sv-cost-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
    color: rgba(255,255,255,0.7);
}
.sv-cost-item.--divider {
    border-bottom: 1px dashed rgba(255,255,255,0.2);
    padding-bottom: 12px;
    margin-bottom: 16px;
}
.sv-cost-item.--accent { color: var(--sv-gold); font-weight: 700; }
.sv-total-box {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 14px;
    padding: 20px;
    text-align: center;
    margin-top: 10px;
}
.sv-total-box__label { font-size: 12px; letter-spacing: 0.1em; color: rgba(255,255,255,0.5); margin-bottom: 4px; }
.sv-total-box__value { font-size: 32px; font-weight: 800; color: var(--sv-gold); text-shadow: 0 4px 10px rgba(0,0,0,0.3); }

/* ── UTILS & TABLE TWEAKS ────────────────────────────────── */
.sv-tag { padding: 4px 12px; border-radius: 100px; font-size: 11px; font-weight: 700; text-transform: uppercase; }
.sv-tag--blue { background: #dbeafe; color: #1e40af; }
.sv-tag--pink { background: #fce7f3; color: #9d174d; }
.sv-tag--green { background: #dcfce7; color: #166534; }

.sv-btn--gold { background: var(--sv-gold); color: white; border-color: var(--sv-gold-dark); }
.sv-btn--gold:hover { background: var(--sv-gold-dark); transform: scale(1.02); }
.sv-btn--glass { background: rgba(255,255,255,0.1); border-color: rgba(255,255,255,0.2); color: white; }
.sv-btn--glass:hover { background: rgba(255,255,255,0.2); }
.sv-btn--icon { gap: 8px; }

.--bold { font-weight: 700; }
.--navy { color: var(--sv-navy); }
.--gold { color: var(--sv-gold-dark); }
.--small { font-size: 11px; }
.--text-right { text-align: right; }
.--fixed-width { width: 90px; }

.sv-table__subtitle { font-size: 10px; font-weight: 700; }
.sv-table__subtitle.--danger { color: #dc2626; }
.sv-table__row.--inclusion { background: #fafafa; }
.sv-inclusion-tag { display: block; font-size: 9px; font-weight: 800; color: var(--sv-gold-dark); text-transform: uppercase; margin-top: 2px; }

.sv-status-btn { background: none; border: none; cursor: pointer; padding: 0; opacity: 0.9; transition: opacity 0.2s, transform 0.1s; }
.sv-status-btn:hover { opacity: 1; transform: translateY(-1px); }

/* ── LAYOUT & GRID ────────────────────────────────────────── */
.sv-detail-grid.--main-cols {
    display: grid;
    grid-template-columns: 1fr 340px;
    gap: 32px;
    align-items: start;
}
@media (max-width: 1100px) {
    .sv-detail-grid.--main-cols { grid-template-columns: 1fr; }
}

.sv-column {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

/* ── DETAIL CARDS ────────────────────────────────────────── */
.sv-detail-card {
    background: white;
    border-radius: 18px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
    overflow: hidden;
    transition: all 0.3s ease;
}
.sv-detail-card:hover { transform: translateY(-4px); box-shadow: var(--sv-shadow-md); }
.sv-detail-card.--no-overflow { overflow: visible; }

.sv-detail-card__header {
    padding: 20px 24px;
    background: #fcfcfd;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.sv-detail-card__title {
    font-family: 'Rajdhani', sans-serif;
    font-size: 18px;
    font-weight: 700;
    color: var(--sv-navy);
    display: flex;
    align-items: center;
    gap: 10px;
    margin: 0;
}
.sv-detail-card__body { padding: 24px; }

/* ── DETAIL ITEMS & GRIDS ────────────────────────────────── */
.sv-detail-grid { display: grid; gap: 20px; }
.sv-detail-grid.--cols-2 { grid-template-columns: 1fr 1fr; }
@media (max-width: 600px) { .sv-detail-grid.--cols-2 { grid-template-columns: 1fr; } }

.sv-detail-item {
    background: #f8fafc;
    padding: 16px;
    border-radius: 12px;
    border: 1px solid #edf2f7;
    transition: all 0.2s;
}
.sv-detail-item:hover { background: #f1f5f9; border-color: var(--sv-gold-light); }
.sv-detail-item__label {
    display: block;
    font-size: 11px;
    color: var(--sv-gray-500);
    text-transform: uppercase;
    font-weight: 700;
    letter-spacing: 0.1em;
    margin-bottom: 6px;
}
.sv-detail-item__value {
    display: block;
    font-size: 15px;
    color: var(--sv-navy);
    font-weight: 600;
}

/* ── TAGS & BADGES ───────────────────────────────────────── */
.sv-tag {
    padding: 4px 12px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
.sv-tag--blue { background: #e0f2fe; color: #0369a1; }
.sv-tag--pink { background: #fce7f3; color: #be185d; }
.sv-tag--green { background: #dcfce7; color: #15803d; }
.sv-tag--navy { background: var(--sv-navy); color: white; }

.sv-badge {
    padding: 6px 14px;
    border-radius: 100px;
    font-size: 12px;
    font-weight: 700;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.sv-badge--active { background: #dcfce7; color: #15803d; }
.sv-badge--expired { background: #fee2e2; color: #b91c1c; }
.sv-badge--pending { background: #fef3c7; color: #b45309; }
.sv-badge--large { padding: 10px 20px; font-size: 14px; }
.--fixed-width { min-width: 90px; justify-content: center; }

/* ── BUTTONS ────────────────────────────────────────────── */
.sv-btn--gold {
    background: var(--sv-gold);
    color: white;
    border: none;
}
.sv-btn--gold:hover {
    background: var(--sv-gold-dark);
    box-shadow: 0 4px 12px rgba(213,164,64,0.3);
}
.sv-btn--sm { padding: 6px 14px; font-size: 12px; border-radius: 8px; }
.sv-btn--glass {
    background: rgba(255,255,255,0.1);
    color: white;
    backdrop-filter: blur(4px);
    border: 1px solid rgba(255,255,255,0.2);
}
.sv-btn--glass:hover { background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.4); }
.sv-btn--icon { gap: 8px; }

.sv-status-btn {
    background: none;
    border: none;
    padding: 0;
    cursor: pointer;
    transition: transform 0.2s;
}
.sv-status-btn:hover { transform: scale(1.05); }

/* ── TABLE STYLES ────────────────────────────────────────── */
.sv-table-wrapper { width: 100%; overflow-x: auto; }
.sv-table { width: 100%; border-collapse: collapse; }
.sv-table th {
    padding: 12px 24px;
    text-align: left;
    font-size: 11px;
    font-weight: 800;
    color: var(--sv-gray-500);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    border-bottom: 2px solid #f1f5f9;
}
.sv-table td { padding: 16px 24px; font-size: 14px; color: var(--sv-navy); border-bottom: 1px solid #f1f5f9; }
.sv-table__row:hover { background: #f8fafc; }
.sv-table__row.--inclusion { background: #fffdf5; }
.sv-inclusion-tag {
    display: inline-block;
    padding: 2px 8px;
    background: var(--sv-gold-light);
    color: var(--sv-gold-dark);
    border-radius: 4px;
    font-size: 10px;
    font-weight: 800;
}

/* ── TIMELINE & HIGHLIGHTS ───────────────────────────────── */
.sv-timeline { margin-bottom: 24px; }
.sv-timeline__labels { display: flex; justify-content: space-between; margin-bottom: 12px; }
.sv-timeline__label { display: flex; flex-direction: column; }
.sv-timeline__label span { font-size: 10px; font-weight: 800; color: var(--sv-gray-400); text-transform: uppercase; }
.sv-timeline__label strong { font-size: 14px; color: var(--sv-navy); }
.sv-timeline__label.--end { text-align: right; }
.sv-timeline__label.--end strong { color: var(--sv-red); }

.sv-timeline__bar {
    height: 8px;
    background: #f1f5f9;
    border-radius: 10px;
    position: relative;
    box-shadow: inset 0 2px 4px rgba(0,0,0,0.05);
}
.sv-timeline__progress {
    height: 100%;
    background: linear-gradient(90deg, var(--sv-navy), var(--sv-gold));
    border-radius: 10px;
    transition: width 1s ease-out;
}
.sv-timeline__marker {
    position: absolute;
    top: 50%;
    width: 16px;
    height: 16px;
    background: white;
    border: 4px solid var(--sv-gold);
    border-radius: 50%;
    transform: translate(-50%, -50%);
    box-shadow: 0 0 12px rgba(213,164,64,0.5);
}

.sv-detail-highlight {
    padding: 20px;
    border-radius: 16px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border: 1px solid transparent;
}
.sv-detail-highlight.--success { background: #f0fdf4; border-color: #bbf7d0; color: #166534; }
.sv-detail-highlight.--danger { background: #fff1f2; border-color: #fecdd3; color: #9f1239; }
.sv-detail-highlight__value { font-size: 20px; font-weight: 800; }

/* ── FINANCIAL CARD ──────────────────────────────────────── */
.sv-financial-card {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
    border-radius: 24px;
    padding: 32px;
    color: white;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.3);
    position: relative;
}
.sv-financial-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    border-bottom: 1px solid rgba(255,255,255,0.1);
    padding-bottom: 16px;
}
.sv-financial-card__title {
    font-family: 'Rajdhani', sans-serif;
    font-size: 20px;
    font-weight: 700;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
    color: var(--sv-gold);
}
.sv-cost-item {
    display: flex;
    justify-content: space-between;
    margin-bottom: 12px;
    font-size: 14px;
    opacity: 0.9;
}
.sv-cost-item.--divider {
    border-top: 1px solid rgba(255,255,255,0.1);
    padding-top: 12px;
}
.sv-cost-item.--accent { color: var(--sv-gold); font-weight: 600; opacity: 1; }
.sv-total-box {
    margin-top: 24px;
    background: rgba(255,255,255,0.05);
    padding: 20px;
    border-radius: 16px;
    text-align: center;
    border: 1px solid rgba(255,255,255,0.1);
}
.sv-total-box__label { font-size: 11px; font-weight: 800; color: var(--sv-gold); letter-spacing: 0.2em; margin-bottom: 4px; }
.sv-total-box__value { font-size: 32px; font-weight: 800; font-family: 'Rajdhani', sans-serif; }

/* ── MODALS ──────────────────────────────────────────────── */
.sv-modal-backdrop {
    position: fixed;
    inset: 0;
    background: rgba(0, 57, 115, 0.4);
    backdrop-filter: blur(8px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    animation: fadeIn 0.3s ease;
}
.sv-modal {
    background: white;
    border-radius: 24px;
    width: 600px;
    max-width: 100%;
    max-height: 90vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);
    animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    position: relative;
    border: 1px solid #e2e8f0;
}
.sv-modal.--width-sm { width: 420px; }
.sv-modal.--width-md { width: 720px; }
.sv-modal.--width-lg { width: 900px; }

.sv-modal__header {
    padding: 24px 32px;
    border-bottom: 1px solid #f1f5f9;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.sv-modal__title {
    margin: 0;
    font-family: 'Rajdhani', sans-serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--sv-navy);
}
.sv-modal__close {
    background: #f1f5f9;
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--sv-gray-500);
    cursor: pointer;
    transition: all 0.2s;
}
.sv-modal__close:hover { background: #e2e8f0; color: var(--sv-navy); transform: rotate(90deg); }

.sv-modal__body { padding: 32px; overflow-y: auto; }
.sv-modal__body.--scrollable { max-height: 60vh; }
.sv-modal__footer {
    padding: 24px 32px;
    border-top: 1px solid #f1f5f9;
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    margin-top: 16px;
}

/* ── FORM STYLES ─────────────────────────────────────────── */
.sv-form-stack { display: flex; flex-direction: column; gap: 20px; }
.sv-form-row { display: flex; gap: 20px; }
@media (max-width: 600px) { .sv-form-row { flex-direction: column; gap: 20px; } }
.sv-form-row > * { flex: 1; }
.--flex-1 { flex: 1 !important; }
.--flex-3 { flex: 3 !important; }

.sv-form-group { display: flex; flex-direction: column; gap: 8px; }
.sv-form-label {
    font-size: 13px;
    font-weight: 700;
    color: var(--sv-gray-600);
}
.sv-input, .sv-select {
    padding: 12px 16px;
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    font-size: 14px;
    transition: all 0.2s;
    background: #fff;
}
.sv-input:focus, .sv-select:focus {
    border-color: var(--sv-gold);
    box-shadow: 0 0 0 4px rgba(213,164,64,0.1);
    outline: none;
}

.sv-input-with-icon { position: relative; }
.sv-input-with-icon span {
    position: absolute;
    left: 14px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--sv-gray-400);
    font-weight: 700;
    pointer-events: none;
}
.sv-input-with-icon input { padding-left: 32px; width: 100%; }

.sv-form-section {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    padding: 20px;
    border-radius: 14px;
    margin-bottom: 24px;
}
.sv-form-section__title {
    margin: 0 0 16px 0;
    font-size: 14px;
    font-weight: 800;
    color: var(--sv-navy);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sv-form-section__title::before {
    content: '';
    width: 4px;
    height: 16px;
    background: var(--sv-gold);
    border-radius: 4px;
}

/* ── UTILITIES & ANIMATIONS ──────────────────────────────── */
.sv-mono { font-family: 'DM Mono', 'Courier New', monospace; letter-spacing: -0.02em; }
.--bold { font-weight: 700; }
.--navy { color: var(--sv-navy); }
.--gold { color: var(--sv-gold); }
.--danger { color: var(--sv-red); }
.--small { font-size: 12px; }
.--text-right { text-align: right; }

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideDown {
    from { transform: translateY(-10px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.sv-receipt-breakdown {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    font-size: 10px;
    color: var(--sv-gray-400);
    font-weight: 700;
    text-transform: uppercase;
    margin-top: 4px;
}
.sv-receipt-dates {
    font-size: 11px;
    color: var(--sv-gray-400);
    font-weight: 600;
    margin-top: 2px;
}
</style>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    console.log('Alpine.js initialized successfully');
});

// Debug: Check if Alpine is loaded
window.addEventListener('load', () => {
    if (typeof Alpine !== 'undefined') {
        console.log('Alpine.js is loaded');
    } else {
        console.error('Alpine.js is NOT loaded');
    }
});
</script>
@endpush

@endsection
