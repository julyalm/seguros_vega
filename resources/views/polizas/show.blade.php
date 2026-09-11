@extends('layouts.app')

@section('title', 'Detalle de Póliza — Seguros Vega')
@section('page-title', 'Expediente Detallado')

@section('content')

@php
    // Admin o el agente propietario pueden ver y resubir los documentos de la poliza.
    $canManageDocs = auth()->user()->role === 'admin' || $poliza->user_id === auth()->id();
@endphp

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

@if(session('warning'))
<div class="sv-alert sv-alert--warning" x-data="{ show: true }" x-show="show">
    <div class="sv-alert__content">
        <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
        <span>{{ session('warning') }}</span>
    </div>
    <button @click="show = false" class="sv-alert__close">
        <svg width="18" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
    </button>
</div>
@endif

@if($errors->any())
<div class="sv-alert sv-alert--error" x-data="{ show: true }" x-show="show">
    <div class="sv-alert__content">
        <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
        <span>{{ $errors->first() }}</span>
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
    docsModalOpen: {{ $errors->hasAny(['archivo_poliza', 'archivo_recibo']) ? 'true' : 'false' }},
    previewModalOpen: false,
    previewUrl: '',
    previewTitle: '',
    receiptUrl: '',
    receiptStatus: 'pendiente',
    receiptContracargo: 0,
    openReceiptEdit(url, status, contracargo) {
        this.receiptUrl = url;
        this.receiptStatus = status;
        this.receiptContracargo = contracargo;
        this.editReceiptModalOpen = true;
    },
    openPreview(url, title) {
        this.previewUrl = url;
        this.previewTitle = title;
        this.previewModalOpen = true;
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

        <!-- Card de Documentos -->
        <div class="sv-detail-card">
            <div class="sv-detail-card__header">
                <h3 class="sv-detail-card__title">
                    <svg width="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    Documentos
                </h3>
                @if($canManageDocs)
                <button type="button" @click="docsModalOpen = true" class="sv-btn sv-btn--sm sv-btn--gold">
                    <svg width="12" viewBox="0 0 24 24" fill="currentColor"><path d="M11.47 1.72a.75.75 0 0 1 1.06 0l3 3a.75.75 0 0 1-1.06 1.06l-1.72-1.72V15a.75.75 0 0 1-1.5 0V4.06L9.53 5.78a.75.75 0 0 1-1.06-1.06l3-3ZM3 16.5a.75.75 0 0 1 .75.75v2.25a.75.75 0 0 0 .75.75h15a.75.75 0 0 0 .75-.75V17.25a.75.75 0 0 1 1.5 0v2.25a2.25 2.25 0 0 1-2.25 2.25h-15A2.25 2.25 0 0 1 2.25 19.5v-2.25A.75.75 0 0 1 3 16.5Z"/></svg>
                    Resubir
                </button>
                @endif
            </div>
            <div class="sv-detail-card__body sv-doc-list">

                @php
                    $documentos = [
                        [
                            'titulo'   => 'Póliza',
                            'archivo'  => $poliza->file_path,
                            // URLs relativas a proposito: el navegador reusa el origen ya
                            // resuelto del documento en vez de re-parsear el host.
                            'preview'  => route('polizas.preview', $poliza->id, false),
                            'descarga' => route('polizas.download', $poliza->id, false),
                            'acento'   => 'var(--sv-navy)',
                        ],
                        [
                            'titulo'   => 'Recibo',
                            'archivo'  => $poliza->recibo_path,
                            'preview'  => route('polizas.preview.recibo', $poliza->id, false),
                            'descarga' => route('polizas.download.recibo', $poliza->id, false),
                            'acento'   => 'var(--sv-gold)',
                        ],
                    ];
                @endphp

                @foreach($documentos as $doc)
                <div class="sv-doc">
                    <div class="sv-doc__head">
                        <span class="sv-doc__title" style="color: {{ $doc['acento'] }};">{{ $doc['titulo'] }} PDF</span>
                        @if($doc['archivo'])
                        <span class="sv-badge sv-badge--active">Cargado</span>
                        @else
                        <span class="sv-badge sv-badge--pending">Sin archivo</span>
                        @endif
                    </div>

                    @if($doc['archivo'] && $canManageDocs)
                    <div class="sv-doc__preview">
                        <iframe src="{{ $doc['preview'] }}#toolbar=0&navpanes=0&view=FitH" title="Vista previa {{ $doc['titulo'] }}" loading="lazy"></iframe>
                        <button type="button" class="sv-doc__preview-overlay" @click="openPreview('{{ $doc['preview'] }}', '{{ $doc['titulo'] }} PDF')">
                            <span>Ampliar vista previa</span>
                        </button>
                    </div>
                    <div class="sv-doc__actions">
                        <button type="button" @click="openPreview('{{ $doc['preview'] }}', '{{ $doc['titulo'] }} PDF')" class="sv-btn sv-btn--outline sv-btn--sm sv-btn--icon">
                            <svg width="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <span>Vista previa</span>
                        </button>
                        <a href="{{ $doc['descarga'] }}" target="_blank" class="sv-btn sv-btn--outline sv-btn--sm sv-btn--icon">
                            <svg width="16" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.25a1.5 1.5 0 0 0 1.5 1.5h13.5a1.5 1.5 0 0 0 1.5-1.5V16.5a.75.75 0 0 1 1.5 0v2.25a3 3 0 0 1-3 3H5.25a3 3 0 0 1-3-3V16.5a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" /></svg>
                            <span>Descargar</span>
                        </a>
                    </div>
                    @else
                    <div class="sv-doc__empty">
                        <svg width="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                        <span>
                            @if(!$doc['archivo'])
                                Aún no se ha cargado el {{ mb_strtolower($doc['titulo']) }}.
                            @else
                                No tienes permiso para consultar este documento.
                            @endif
                        </span>
                    </div>
                    @endif
                </div>
                @endforeach

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
                <form action="{{ route('polizas.update', $poliza->id) }}" method="POST" class="sv-form-stack"
                    x-data="{
                        prima_neta: {{ $poliza->prima_neta }},
                        derechos: {{ $poliza->derechos }},
                        recargo: {{ $poliza->recargo }},
                        iva: {{ $poliza->iva }},
                        get prima_total() {
                            return (parseFloat(this.prima_neta || 0) +
                                    parseFloat(this.derechos || 0) +
                                    parseFloat(this.recargo || 0) +
                                    parseFloat(this.iva || 0)).toFixed(2);
                        },
                        sync: null,
                        sincronizarRecibos: 'auto',
                        calculandoSync: false,
                        timerSync: null,
                        async pedirPreviewSync() {
                            this.calculandoSync = true;
                            try {
                                const resp = await fetch('{{ route('polizas.recibos.preview-sync', $poliza->id) }}', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    },
                                    body: JSON.stringify({
                                        prima_neta: parseFloat(this.prima_neta || 0),
                                        derechos: parseFloat(this.derechos || 0),
                                        recargo: parseFloat(this.recargo || 0),
                                        iva: parseFloat(this.iva || 0)
                                    })
                                });
                                if (!resp.ok) { this.sync = null; return; }
                                this.sync = await resp.json();
                                // Si el reparto automatico no es viable, solo queda la via manual.
                                if (!this.sync.auto_disponible) this.sincronizarRecibos = 'manual';
                            } catch (e) {
                                this.sync = null;
                            } finally {
                                this.calculandoSync = false;
                            }
                        },
                        pedirPreviewSyncDebounced() {
                            clearTimeout(this.timerSync);
                            this.timerSync = setTimeout(() => this.pedirPreviewSync(), 400);
                        }
                    }"
                    x-init="
                        $watch('editAdminModalOpen', abierto => { if (abierto) pedirPreviewSync(); });
                        ['prima_neta', 'derechos', 'recargo', 'iva'].forEach(
                            campo => $watch(campo, () => pedirPreviewSyncDebounced())
                        );
                    ">
                    @csrf
                    @method('PUT')
                    <div class="sv-form-group">
                        <label class="sv-form-label">Número de Póliza</label>
                        <input type="text" name="numero_poliza" value="{{ $poliza->numero_poliza }}" class="sv-input" required>
                    </div>
                    <div class="sv-form-row">
                        <div class="sv-form-group">
                            <label class="sv-form-label">Prima Neta</label>
                            <input type="number" step="0.01" name="prima_neta" x-model="prima_neta" class="sv-input" required>
                        </div>
                        <div class="sv-form-group">
                            <label class="sv-form-label">Derechos</label>
                            <input type="number" step="0.01" name="derechos" x-model="derechos" class="sv-input" required>
                        </div>
                    </div>
                    <div class="sv-form-row">
                        <div class="sv-form-group">
                            <label class="sv-form-label">Recargos</label>
                            <input type="number" step="0.01" name="recargo" x-model="recargo" class="sv-input" required>
                        </div>
                        <div class="sv-form-group">
                            <label class="sv-form-label">I.V.A.</label>
                            <input type="number" step="0.01" name="iva" x-model="iva" class="sv-input" required>
                        </div>
                    </div>
                    <div class="sv-form-group">
                        <label class="sv-form-label">Comisión de Agente</label>
                        <input type="number" step="0.01" name="comision" value="{{ $poliza->comision }}" class="sv-input" required>
                    </div>

                    <div class="sv-total-preview">
                        <span class="sv-total-preview__label">Prima Total</span>
                        <span class="sv-total-preview__value sv-mono" x-text="'$' + prima_total"></span>
                    </div>
                    <p class="sv-form-hint">Se calcula automáticamente como Prima Neta + Derechos + Recargos + I.V.A. La comisión no se incluye en el total.</p>

                    @if(auth()->user()->role === 'admin')
                    <!-- Sincronización de recibos -->
                    <div x-show="calculandoSync" x-cloak class="sv-sync__loading">
                        <span class="sv-sync__spinner"></span>
                        Revisando el calendario de pagos…
                    </div>

                    <template x-if="!calculandoSync && sync && sync.requiere_sincronizacion">
                        <div class="sv-sync">
                            <div class="sv-sync__head">
                                <svg width="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                                <div>
                                    <strong>Los recibos dejarán de cuadrar con esta prima</strong>
                                    <p>
                                        Suma actual de recibos <b x-text="'$' + sync.suma_actual.toFixed(2)"></b>
                                        contra la nueva prima total <b x-text="'$' + sync.prima_total.toFixed(2)"></b>
                                        — diferencia de <b x-text="(sync.diferencia >= 0 ? '+$' : '-$') + Math.abs(sync.diferencia).toFixed(2)"></b>.
                                    </p>
                                </div>
                            </div>

                            <label class="sv-sync__option" :class="sincronizarRecibos === 'auto' && sync.auto_disponible ? '--selected' : ''"
                                :style="!sync.auto_disponible ? 'opacity:.5; cursor:not-allowed;' : ''">
                                <input type="radio" name="sincronizar_recibos" value="auto" x-model="sincronizarRecibos" :disabled="!sync.auto_disponible">
                                <span>
                                    <strong>Sincronizar automáticamente</strong>
                                    <small>Reparte la diferencia entre los recibos pendientes. Los recibos pagados no se modifican.</small>
                                </span>
                            </label>

                            <p x-show="!sync.auto_disponible" x-cloak class="sv-sync__blocked" x-text="sync.motivo_bloqueo"></p>

                            <label class="sv-sync__option" :class="sincronizarRecibos === 'manual' ? '--selected' : ''">
                                <input type="radio" name="sincronizar_recibos" value="manual" x-model="sincronizarRecibos">
                                <span>
                                    <strong>Editarlos manualmente después</strong>
                                    <small>Guarda solo la póliza; tú ajustas el Calendario de Pagos cuando quieras.</small>
                                </span>
                            </label>

                            <div x-show="sincronizarRecibos === 'auto' && sync.auto_disponible" x-cloak class="sv-sync__preview">
                                <div class="sv-sync__preview-title">Así quedaría el calendario</div>
                                <table class="sv-sync__table">
                                    <thead>
                                        <tr>
                                            <th>Recibo</th>
                                            <th>Estatus</th>
                                            <th class="--text-right">Actual</th>
                                            <th class="--text-right">Nuevo</th>
                                            <th class="--text-right">Cambio</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <template x-for="r in sync.recibos" :key="r.id">
                                            <tr :class="r.pagado ? '--locked' : ''">
                                                <td x-text="'#' + r.indice"></td>
                                                <td>
                                                    <span class="sv-sync__status" x-text="r.pagado ? 'pagado' : r.status"></span>
                                                </td>
                                                <td class="--text-right sv-mono" x-text="'$' + r.monto_actual.toFixed(2)"></td>
                                                <td class="--text-right sv-mono --bold" x-text="'$' + r.monto_nuevo.toFixed(2)"></td>
                                                <td class="--text-right sv-mono"
                                                    :class="r.delta > 0 ? '--up' : (r.delta < 0 ? '--down' : '--flat')"
                                                    x-text="r.delta === 0 ? '—' : (r.delta > 0 ? '+$' : '-$') + Math.abs(r.delta).toFixed(2)"></td>
                                            </tr>
                                        </template>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3">Suma de recibos</td>
                                            <td class="--text-right sv-mono --bold" x-text="'$' + sync.recibos.reduce((a, r) => a + r.monto_nuevo, 0).toFixed(2)"></td>
                                            <td class="--text-right">=</td>
                                        </tr>
                                    </tfoot>
                                </table>
                                <p class="sv-sync__note">Los recibos pagados conservan su importe. El último recibo pendiente absorbe el redondeo para que la suma cuadre exacto con la prima total.</p>
                            </div>
                        </div>
                    </template>
                    @endif

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

    <!-- Modal: Vista Previa de Documento -->
    <div x-show="previewModalOpen" x-cloak class="sv-modal-backdrop">
        <div class="sv-modal --width-lg sv-modal--preview" @click.outside="previewModalOpen = false" @keydown.escape.window="previewModalOpen = false">
            <div class="sv-modal__header">
                <h3 class="sv-modal__title" x-text="previewTitle"></h3>
                <div class="sv-modal__header-actions">
                    <a :href="previewUrl" target="_blank" class="sv-btn sv-btn--outline sv-btn--sm sv-btn--icon">
                        <svg width="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" /></svg>
                        <span>Abrir en pestaña</span>
                    </a>
                    <button type="button" @click="previewModalOpen = false" class="sv-modal__close">
                        <svg width="20" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                    </button>
                </div>
            </div>
            <div class="sv-modal__body sv-doc-viewer">
                <template x-if="previewModalOpen">
                    <iframe :src="previewUrl" :title="previewTitle"></iframe>
                </template>
            </div>
        </div>
    </div>

    @if($canManageDocs)
    <!-- Modal: Resubir Documentos -->
    <div x-show="docsModalOpen" x-cloak class="sv-modal-backdrop">
        <div class="sv-modal --width-md" @click.outside="docsModalOpen = false" @keydown.escape.window="docsModalOpen = false">
            <div class="sv-modal__header">
                <h3 class="sv-modal__title">Resubir Documentos</h3>
                <button type="button" @click="docsModalOpen = false" class="sv-modal__close">
                    <svg width="20" viewBox="0 0 24 24" fill="currentColor"><path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd"/></svg>
                </button>
            </div>
            <div class="sv-modal__body --scrollable">
                <form action="{{ route('polizas.update.documentos', $poliza->id) }}" method="POST" enctype="multipart/form-data" class="sv-form-stack"
                    x-data="{
                        maxKb: {{ $maxUploadKb }},
                        maxTexto: '{{ $maxUploadTexto }}',
                        archivos: { poliza: '', recibo: '' },
                        errores: { poliza: '', recibo: '' },
                        arrastrando: { poliza: false, recibo: false },
                        refDe(campo) {
                            return 'input' + campo.charAt(0).toUpperCase() + campo.slice(1);
                        },
                        peso(bytes) {
                            return bytes < 1024 * 1024
                                ? (bytes / 1024).toFixed(1) + ' KB'
                                : (bytes / (1024 * 1024)).toFixed(1) + ' MB';
                        },
                        seleccionar(campo, f) {
                            if (!f) return;
                            this.errores[campo] = '';

                            // Se corta aqui a proposito: si el archivo excede el limite
                            // del servidor, PHP descarta el POST (y con el, el token CSRF)
                            // y el usuario acaba viendo un 419 en vez de un mensaje util.
                            if (f.size / 1024 > this.maxKb) {
                                this.errores[campo] = 'Este archivo pesa ' + this.peso(f.size)
                                    + ' y el servidor acepta como máximo ' + this.maxTexto + '.';
                                this.limpiar(campo);
                                return;
                            }

                            if (!/\.pdf$/i.test(f.name)) {
                                this.errores[campo] = 'El archivo debe ser un PDF.';
                                this.limpiar(campo);
                                return;
                            }

                            this.archivos[campo] = f.name + ' - ' + this.peso(f.size);
                        },
                        soltar(campo, f) {
                            if (!f) return;
                            const dt = new DataTransfer();
                            dt.items.add(f);
                            this.$refs[this.refDe(campo)].files = dt.files;
                            this.seleccionar(campo, f);
                        },
                        limpiar(campo) {
                            this.archivos[campo] = '';
                            this.$refs[this.refDe(campo)].value = '';
                        }
                    }">
                    @csrf

                    <p class="sv-form-hint">
                        Puedes reemplazar solo uno de los dos o ambos: se actualiza únicamente lo que subas.
                        Formato PDF, máximo {{ $maxUploadTexto }} por archivo. El archivo anterior se elimina
                        del servidor al guardar.
                    </p>

                    <!-- Póliza -->
                    <div class="sv-form-group">
                        <label class="sv-form-label">Póliza (PDF) @if($poliza->file_path)<span class="sv-form-label__note">— reemplaza el archivo actual</span>@endif</label>
                        <input type="file" name="archivo_poliza" accept="application/pdf" style="display:none;"
                            x-ref="inputPoliza" @change="seleccionar('poliza', $event.target.files[0])">

                        <div x-show="!archivos.poliza" class="sv-dropzone" :class="arrastrando.poliza && '--active'"
                            @dragover.prevent="arrastrando.poliza = true"
                            @dragleave.prevent="arrastrando.poliza = false"
                            @drop.prevent="arrastrando.poliza = false; soltar('poliza', $event.dataTransfer.files[0])"
                            @click="$refs.inputPoliza.click()">
                            <svg width="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                            <span><strong>Selecciona un archivo</strong> o arrástralo aquí</span>
                        </div>

                        <div x-show="archivos.poliza" x-cloak class="sv-dropzone__file">
                            <span class="sv-dropzone__name" x-text="archivos.poliza"></span>
                            <button type="button" @click="limpiar('poliza')" class="sv-dropzone__remove">Quitar</button>
                        </div>

                        <p x-show="errores.poliza" x-cloak class="sv-form-error" x-text="errores.poliza"></p>
                    </div>

                    <!-- Recibo -->
                    <div class="sv-form-group">
                        <label class="sv-form-label">Recibo (PDF) @if($poliza->recibo_path)<span class="sv-form-label__note">— reemplaza el archivo actual</span>@endif</label>
                        <input type="file" name="archivo_recibo" accept="application/pdf" style="display:none;"
                            x-ref="inputRecibo" @change="seleccionar('recibo', $event.target.files[0])">

                        <div x-show="!archivos.recibo" class="sv-dropzone" :class="arrastrando.recibo && '--active'"
                            @dragover.prevent="arrastrando.recibo = true"
                            @dragleave.prevent="arrastrando.recibo = false"
                            @drop.prevent="arrastrando.recibo = false; soltar('recibo', $event.dataTransfer.files[0])"
                            @click="$refs.inputRecibo.click()">
                            <svg width="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/></svg>
                            <span><strong>Selecciona un archivo</strong> o arrástralo aquí</span>
                        </div>

                        <div x-show="archivos.recibo" x-cloak class="sv-dropzone__file">
                            <span class="sv-dropzone__name" x-text="archivos.recibo"></span>
                            <button type="button" @click="limpiar('recibo')" class="sv-dropzone__remove">Quitar</button>
                        </div>

                        <p x-show="errores.recibo" x-cloak class="sv-form-error" x-text="errores.recibo"></p>
                    </div>

                    @error('archivo_poliza')<span class="sv-form-error">{{ $message }}</span>@enderror
                    @error('archivo_recibo')<span class="sv-form-error">{{ $message }}</span>@enderror

                    <div class="sv-modal__footer">
                        <button type="button" @click="docsModalOpen = false" class="sv-btn sv-btn--outline">Cancelar</button>
                        <button type="submit" class="sv-btn sv-btn--primary" :disabled="!archivos.poliza && !archivos.recibo">Guardar Documentos</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
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

/* ── PRIMA TOTAL CALCULADA (modal financiero) ────────────── */
.sv-total-preview {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 16px 20px;
    border-radius: 12px;
    background: var(--sv-gray-50);
    border: 1.5px solid #e2e8f0;
}
.sv-total-preview__label {
    font-size: 12px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--sv-gray-500);
}
.sv-total-preview__value {
    font-size: 20px;
    font-weight: 800;
    color: var(--sv-navy);
}

/* ── DOCUMENTOS (vista previa + resubida) ────────────────── */
.sv-doc-list {
    display: flex;
    flex-direction: column;
    gap: 24px;
}
.sv-doc {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.sv-doc + .sv-doc {
    padding-top: 24px;
    border-top: 1px solid #f1f5f9;
}
.sv-doc__head {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
}
.sv-doc__title {
    font-size: 13px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}
.sv-doc__preview {
    position: relative;
    height: 200px;
    border-radius: 12px;
    border: 1px solid #e2e8f0;
    background: var(--sv-gray-50);
    overflow: hidden;
}
.sv-doc__preview iframe {
    width: 100%;
    height: 100%;
    border: 0;
    display: block;
}
.sv-doc__preview-overlay {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 12px;
    background: linear-gradient(to top, rgba(15,23,42,0.55), rgba(15,23,42,0) 45%);
    border: none;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.2s;
}
.sv-doc__preview:hover .sv-doc__preview-overlay { opacity: 1; }
.sv-doc__preview-overlay span {
    color: #fff;
    font-size: 12px;
    font-weight: 700;
    background: rgba(15,23,42,0.75);
    padding: 6px 14px;
    border-radius: 999px;
}
.sv-doc__actions {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.sv-doc__actions > * { flex: 1; justify-content: center; }
.sv-doc__empty {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 28px 16px;
    border: 1.5px dashed #e2e8f0;
    border-radius: 12px;
    background: var(--sv-gray-50);
    color: var(--sv-gray-400);
    font-size: 12px;
    font-weight: 600;
    text-align: center;
}

/* Modal de vista previa */
.sv-modal__header-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}
.sv-modal--preview { height: 88vh; }
.sv-doc-viewer {
    padding: 0;
    flex: 1;
    min-height: 0;
    background: var(--sv-gray-50);
}
.sv-doc-viewer iframe {
    width: 100%;
    height: 100%;
    min-height: 70vh;
    border: 0;
    display: block;
}

/* Zonas de carga */
.sv-form-hint {
    font-size: 12px;
    font-weight: 600;
    color: var(--sv-gray-500);
    line-height: 1.6;
    margin: 0;
}
.sv-form-label__note {
    font-weight: 600;
    color: var(--sv-gray-400);
    text-transform: none;
}
.sv-form-error {
    font-size: 12px;
    font-weight: 700;
    color: #b91c1c;
}
.sv-dropzone {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 10px;
    padding: 28px 20px;
    border: 2px dashed var(--sv-gray-300, #cbd5e1);
    border-radius: 12px;
    background: var(--sv-gray-50);
    color: var(--sv-gray-400);
    font-size: 13px;
    cursor: pointer;
    text-align: center;
    transition: border-color 0.2s, background 0.2s, color 0.2s;
}
.sv-dropzone strong { color: var(--sv-navy); }
.sv-dropzone.--active,
.sv-dropzone:hover {
    border-color: var(--sv-gold);
    background: #fffbf2;
    color: var(--sv-gray-600);
}
.sv-dropzone__file {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    padding: 14px 16px;
    border: 1.5px solid var(--sv-gold);
    border-radius: 12px;
    background: #fffbf2;
}
.sv-dropzone__name {
    font-size: 13px;
    font-weight: 700;
    color: var(--sv-navy);
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.sv-dropzone__remove {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 12px;
    font-weight: 700;
    color: #b91c1c;
    flex-shrink: 0;
}
.sv-dropzone__remove:hover { text-decoration: underline; }
.sv-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

/* ── SINCRONIZACIÓN DE RECIBOS (modal financiero) ────────── */
.sv-alert--warning {
    background: #fffbeb;
    border: 1px solid #f59e0b;
    color: #92400e;
}

.sv-sync__loading {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 12px;
    font-weight: 700;
    color: var(--sv-gray-500);
}
.sv-sync__spinner {
    width: 14px;
    height: 14px;
    border: 2px solid #e2e8f0;
    border-top-color: var(--sv-gold);
    border-radius: 50%;
    animation: svSpin 0.7s linear infinite;
    flex-shrink: 0;
}
@keyframes svSpin { to { transform: rotate(360deg); } }

.sv-sync {
    display: flex;
    flex-direction: column;
    gap: 12px;
    padding: 18px;
    border-radius: 14px;
    background: #fffbeb;
    border: 1.5px solid #fcd34d;
}
.sv-sync__head {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    color: #92400e;
}
.sv-sync__head svg { flex-shrink: 0; margin-top: 2px; }
.sv-sync__head strong {
    display: block;
    font-size: 13px;
    font-weight: 800;
    margin-bottom: 4px;
}
.sv-sync__head p {
    margin: 0;
    font-size: 12px;
    font-weight: 600;
    line-height: 1.6;
}
.sv-sync__head b { font-weight: 800; }

.sv-sync__option {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 12px 14px;
    border-radius: 10px;
    background: #fff;
    border: 1.5px solid #fde68a;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.sv-sync__option.--selected {
    border-color: var(--sv-gold);
    box-shadow: 0 0 0 3px rgba(213,164,64,0.15);
}
.sv-sync__option input { margin-top: 3px; flex-shrink: 0; accent-color: var(--sv-gold); }
.sv-sync__option strong {
    display: block;
    font-size: 13px;
    font-weight: 700;
    color: var(--sv-navy);
}
.sv-sync__option small {
    display: block;
    font-size: 11px;
    font-weight: 600;
    color: var(--sv-gray-500);
    line-height: 1.5;
    margin-top: 2px;
}
.sv-sync__blocked {
    margin: -4px 0 0;
    font-size: 11px;
    font-weight: 700;
    color: #b45309;
    padding-left: 4px;
}

.sv-sync__preview {
    background: #fff;
    border: 1px solid #fde68a;
    border-radius: 10px;
    padding: 14px;
}
.sv-sync__preview-title {
    font-size: 11px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--sv-gray-500);
    margin-bottom: 10px;
}
.sv-sync__table {
    width: 100%;
    border-collapse: collapse;
    font-size: 12px;
}
.sv-sync__table th {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    color: var(--sv-gray-400);
    padding: 0 8px 8px;
    text-align: left;
}
.sv-sync__table td {
    padding: 8px;
    border-top: 1px solid #f1f5f9;
    font-weight: 600;
    color: var(--sv-gray-600);
}
.sv-sync__table tfoot td {
    border-top: 1.5px solid #e2e8f0;
    font-weight: 800;
    color: var(--sv-navy);
}
.sv-sync__table .--text-right { text-align: right; }
.sv-sync__table tr.--locked td { opacity: 0.55; }
.sv-sync__table .--up { color: #047857; }
.sv-sync__table .--down { color: #b91c1c; }
.sv-sync__table .--flat { color: var(--sv-gray-400); }
.sv-sync__status {
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.03em;
}
.sv-sync__note {
    margin: 10px 0 0;
    font-size: 11px;
    font-weight: 600;
    color: var(--sv-gray-400);
    line-height: 1.5;
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
