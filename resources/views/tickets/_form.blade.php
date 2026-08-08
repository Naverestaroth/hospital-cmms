<!-- Shared Ticket Form Partial: resources/views/tickets/_form.blade.php -->
@php
    $ticket = $ticket ?? null;
    $assignedTechIds = isset($ticket) ? $ticket->technicians->pluck('id')->toArray() : [];
@endphp

<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        
        <!-- Creator Type -->
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
                Created By Role <span class="text-red-500">*</span>
            </label>
            <select name="creator_type" class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:border-blue-500 focus:outline-none">
                <option value="User" {{ old('creator_type', $ticket?->creator_type) === 'User' ? 'selected' : '' }}>Hospital User / Staff</option>
                <option value="Technician" {{ old('creator_type', $ticket?->creator_type) === 'Technician' ? 'selected' : '' }}>IPSRS Technician</option>
            </select>
        </div>

        <!-- Reported By -->
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
                Reported By (Name) <span class="text-red-500">*</span>
            </label>
            <input
                type="text"
                name="reported_by"
                value="{{ old('reported_by', $ticket?->reported_by) }}"
                class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:border-blue-500 focus:outline-none"
                placeholder="e.g. Nurse Ratna / Dr. Andi"
                required>
        </div>

        <!-- Dynamic Room Dropdown -->
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
                Room / Location <span class="text-red-500">*</span>
            </label>
            <select
                id="room_select"
                name="room"
                class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:border-blue-500 focus:outline-none"
                required>
                <option value="">-- Select Room --</option>
                @foreach ($rooms as $room)
                    <option value="{{ $room }}" {{ old('room', $ticket?->room ?? $ticket?->asset?->room) === $room ? 'selected' : '' }}>
                        {{ $room }}
                    </option>
                @endforeach
            </select>
            <p class="mt-1 text-xs text-slate-400">Rooms are loaded dynamically from Asset inventory.</p>
        </div>

        <!-- Asset Selector -->
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
                Asset / Equipment <span class="text-red-500">*</span>
            </label>
            <select
                id="asset_select"
                name="asset_id"
                class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:border-blue-500 focus:outline-none"
                required>
                <option value="">-- Select Asset --</option>
                @foreach ($assets as $asset)
                    <option value="{{ $asset->id }}" data-room="{{ $asset->room }}" {{ old('asset_id', $ticket?->asset_id) == $asset->id ? 'selected' : '' }}>
                        {{ $asset->asset_name }} — {{ $asset->brand ?? 'No Brand' }} • {{ $asset->type ?? 'N/A' }} (SN: {{ $asset->serial_number ?? 'No SN' }})
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Priority -->
        <div>
            <label class="mb-2 block text-sm font-semibold text-slate-700">
                Priority <span class="text-red-500">*</span>
            </label>
            <select name="priority" class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:border-blue-500 focus:outline-none" required>
                <option value="Low" {{ old('priority', $ticket?->priority) === 'Low' ? 'selected' : '' }}>Low (Routine)</option>
                <option value="Medium" {{ old('priority', $ticket?->priority ?? 'Medium') === 'Medium' ? 'selected' : '' }}>Medium (Urgent)</option>
                <option value="High" {{ old('priority', $ticket?->priority) === 'High' ? 'selected' : '' }}>High (Emergency / Critical)</option>
            </select>
        </div>

    </div>

    <!-- Issue Description -->
    <div>
        <label class="mb-2 block text-sm font-semibold text-slate-700">
            Issue Description <span class="text-red-500">*</span>
        </label>
        <textarea
            name="issue"
            rows="4"
            class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:border-blue-500 focus:outline-none"
            placeholder="Describe the defect, problem symptoms, or assistance required..."
            required>{{ old('issue', $ticket?->issue) }}</textarea>
    </div>

    <!-- Equipment Completeness -->
    <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 space-y-2">
        <label class="block text-sm font-semibold text-slate-800">
            Equipment Completeness (Kelengkapan Alat)
        </label>
        <p class="text-xs text-slate-500">Record accessories or components accompanying the equipment (e.g. Power Cord, Probe, Battery, Sensor, Stand).</p>
        <textarea
            name="equipment_completeness"
            rows="2"
            class="w-full rounded-xl border border-slate-300 p-3 text-sm focus:border-blue-500 focus:outline-none bg-white"
            placeholder="e.g. Power Cord, Probe, Battery, Stand">{{ old('equipment_completeness', $ticket?->equipment_completeness) }}</textarea>
    </div>

    <!-- Equipment Movement Section (Optional) -->
    <div class="rounded-2xl border border-amber-200 bg-amber-50/50 p-5 space-y-4">
        <div>
            <h3 class="text-sm font-bold text-slate-900">Equipment Movement (Perpindahan Alat ke Workshop)</h3>
            <p class="text-xs text-slate-500 mt-0.5">Optional. Fill if equipment is moved to IPSRS Workshop for repair.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Container 1: Sent to Workshop -->
            <div class="rounded-xl border border-amber-200 bg-white p-4 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-amber-900 block">1. Equipment Sent to Workshop</span>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Date</label>
                    <input type="date" name="sent_to_workshop_date" value="{{ old('sent_to_workshop_date', $ticket?->sent_to_workshop_date?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-300 p-2.5 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Handed Over By (Penyerah)</label>
                    <input type="text" name="sent_by" value="{{ old('sent_by', $ticket?->sent_by) }}" placeholder="e.g. Nurse Ratna" class="w-full rounded-xl border border-slate-300 p-2.5 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Received By (Penerima)</label>
                    <input type="text" name="received_by_workshop" value="{{ old('received_by_workshop', $ticket?->received_by_workshop) }}" placeholder="e.g. Susanto (IPSRS)" class="w-full rounded-xl border border-slate-300 p-2.5 text-xs">
                </div>
            </div>

            <!-- Container 2: Returned to Room -->
            <div class="rounded-xl border border-emerald-200 bg-white p-4 space-y-3">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-900 block">2. Equipment Returned</span>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Date</label>
                    <input type="date" name="returned_date" value="{{ old('returned_date', $ticket?->returned_date?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-300 p-2.5 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Handed Over By (Penyerah)</label>
                    <input type="text" name="returned_by" value="{{ old('returned_by', $ticket?->returned_by) }}" placeholder="e.g. Susanto (IPSRS)" class="w-full rounded-xl border border-slate-300 p-2.5 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Received By (Penerima)</label>
                    <input type="text" name="received_by_user" value="{{ old('received_by_user', $ticket?->received_by_user) }}" placeholder="e.g. Nurse Ratna" class="w-full rounded-xl border border-slate-300 p-2.5 text-xs">
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Technicians -->
    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
        <label class="mb-2 block text-sm font-semibold text-slate-800">
            Assign Technician(s) (Optional)
        </label>
        <p class="mb-3 text-xs text-slate-500">Select IPSRS technicians to assign to this ticket.</p>
        
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 md:grid-cols-4">
            @foreach ($technicians as $tech)
                <label class="flex items-center gap-2 rounded-xl border border-slate-200 bg-white p-3 text-sm text-slate-700 transition hover:bg-slate-100 cursor-pointer">
                    <input
                        type="checkbox"
                        name="technician_ids[]"
                        value="{{ $tech->id }}"
                        class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                        {{ in_array($tech->id, old('technician_ids', $assignedTechIds)) ? 'checked' : '' }}>
                    <span class="font-medium text-slate-800">{{ $tech->name }}</span>
                </label>
            @endforeach
        </div>
    </div>
</div>
