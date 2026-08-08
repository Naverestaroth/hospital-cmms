<x-app-layout>

    <div class="space-y-6 max-w-5xl">

        @if(session('success'))
        <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-4 text-emerald-800">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="rounded-xl bg-red-50 border border-red-200 p-4 text-red-700">
            {{ session('error') }}
        </div>
        @endif

        @if(session('info'))
        <div class="rounded-xl bg-blue-50 border border-blue-200 p-4 text-blue-800">
            {{ session('info') }}
        </div>
        @endif

        <!-- Back Button & Top Bar -->
        <div class="flex items-center justify-between">
            <a href="{{ route('tickets.index') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">
                ← Back to Tickets
            </a>
            
            <a href="{{ route('tickets.edit', $ticket) }}" class="rounded-xl border border-slate-300 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50">
                Edit Ticket
            </a>
        </div>

        <!-- Ticket Summary Header Banner -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3">
                    <span class="text-xs font-mono font-bold text-slate-400 uppercase">Ticket ID</span>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $ticket->ticket_code }}</h1>
                </div>
                <p class="mt-1 text-sm font-medium text-slate-700">
                    {{ $ticket->asset?->asset_name ?? 'N/A' }} 
                    <span class="text-slate-400">• Room: {{ $ticket->room ?? $ticket->asset?->room ?? 'Unassigned' }}</span>
                </p>
            </div>

            <div class="flex items-center gap-3">
                <!-- Priority Badge -->
                @if($ticket->priority === 'High')
                    <span class="rounded-full bg-red-50 border border-red-200 px-3 py-1 text-xs font-semibold text-red-700">High Priority</span>
                @elseif($ticket->priority === 'Medium')
                    <span class="rounded-full bg-amber-50 border border-amber-200 px-3 py-1 text-xs font-semibold text-amber-700">Medium Priority</span>
                @else
                    <span class="rounded-full bg-slate-100 border border-slate-200 px-3 py-1 text-xs font-semibold text-slate-700">Low Priority</span>
                @endif

                <!-- Status Badge -->
                @php
                    $statusClasses = match($ticket->status) {
                        'Waiting Approval' => 'bg-amber-50 text-amber-800 border-amber-200',
                        'Approved', 'Open' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'Assigned', 'Accepted' => 'bg-purple-50 text-purple-700 border-purple-200',
                        'In Progress' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                        'Waiting Sparepart', 'Waiting Vendor', 'Waiting User' => 'bg-amber-100 text-amber-900 border-amber-300',
                        'Repair Completed', 'Waiting Corrective Report', 'Corrective Report Completed' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
                        'Closed' => 'bg-slate-100 text-slate-700 border-slate-300',
                        'Rejected', 'Cancelled' => 'bg-red-50 text-red-700 border-red-200',
                        default => 'bg-slate-100 text-slate-700 border-slate-200',
                    };
                @endphp
                <span class="rounded-full border px-4 py-1.5 text-xs font-bold {{ $statusClasses }}">
                    {{ $ticket->status }}
                </span>
            </div>
        </div>

        <!-- Approval Action Panel (Workflow Step) -->
        @if($ticket->status === 'Waiting Approval')
            <div class="rounded-3xl border border-amber-200 bg-amber-50/70 p-5 shadow-sm space-y-3">
                <div class="flex items-center gap-2 text-amber-900">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h2 class="text-sm font-bold">Ticket Approval Workflow Step</h2>
                </div>
                <p class="text-xs text-amber-800">
                    This ticket is waiting for approval. Approve to move it into "Open" status so technicians can self-assign or be assigned.
                </p>

                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <!-- Approve Ticket Button -->
                    <form action="{{ route('tickets.approve', $ticket) }}" method="POST">
                        @csrf
                        <button type="submit" class="rounded-xl bg-emerald-600 px-5 py-2.5 text-xs font-semibold text-white hover:bg-emerald-700 shadow-sm transition">
                            ✓ Approve Ticket
                        </button>
                    </form>

                    <!-- Reject Ticket Button / Form -->
                    <form action="{{ route('tickets.reject', $ticket) }}" method="POST" class="flex items-center gap-2">
                        @csrf
                        <input type="text" name="rejection_reason" placeholder="Reason for rejection..." class="rounded-xl border border-red-300 bg-white px-3 py-2 text-xs text-slate-700 focus:outline-none" required>
                        <button type="submit" class="rounded-xl bg-red-600 px-4 py-2 text-xs font-semibold text-white hover:bg-red-700 shadow-sm transition">
                            ✗ Reject
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Workflow Action Bar (Active after Approval) -->
        @if($ticket->status !== 'Waiting Approval' && $ticket->status !== 'Rejected' && $ticket->status !== 'Cancelled')
            <div class="rounded-3xl border border-blue-200 bg-blue-50/60 p-5 shadow-sm">
                <h2 class="text-xs font-bold uppercase tracking-wider text-blue-900 mb-3">Workflow Actions & Status Management</h2>
                
                <div class="flex flex-wrap items-center gap-3">
                    
                    <!-- Technician Self Assignment (Available after Approval: Open or Approved or Assigned) -->
                    @if(in_array($ticket->status, ['Approved', 'Open', 'Assigned']))
                        <form action="{{ route('tickets.self-assign', $ticket) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <select name="technician_id" class="rounded-xl border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-700" required>
                                <option value="">-- Pick your name (Self Assign) --</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}">{{ $tech->name }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="rounded-xl bg-purple-600 px-4 py-2 text-xs font-semibold text-white hover:bg-purple-700 shadow-sm">
                                + Self Assign
                            </button>
                        </form>
                    @endif

                    <!-- Accept Ticket (if Assigned) -->
                    @if($ticket->status === 'Assigned')
                        <form action="{{ route('tickets.accept', $ticket) }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700 shadow-sm">
                                Accept Ticket
                            </button>
                        </form>
                    @endif

                    <!-- Progress Updates (if Accepted, In Progress, or Waiting States) -->
                    @if(in_array($ticket->status, ['Accepted', 'In Progress', 'Waiting Sparepart', 'Waiting Vendor', 'Waiting User']))
                        @if($ticket->status !== 'In Progress')
                            <form action="{{ route('tickets.update-status', $ticket) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="In Progress">
                                <button type="submit" class="rounded-xl bg-indigo-600 px-4 py-2 text-xs font-semibold text-white hover:bg-indigo-700">
                                    Set to In Progress
                                </button>
                            </form>
                        @endif

                        <!-- Waiting States Dropdown -->
                        <form action="{{ route('tickets.update-status', $ticket) }}" method="POST" class="flex items-center gap-2">
                            @csrf
                            <select name="status" class="rounded-xl border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-700" required>
                                <option value="">-- Waiting State --</option>
                                <option value="Waiting Sparepart">Waiting Sparepart</option>
                                <option value="Waiting Vendor">Waiting Vendor</option>
                                <option value="Waiting User">Waiting User</option>
                            </select>
                            <button type="submit" class="rounded-xl border border-amber-300 bg-amber-100 px-3 py-1.5 text-xs font-semibold text-amber-900 hover:bg-amber-200">
                                Update
                            </button>
                        </form>

                        <!-- Repair Completed -->
                        <form action="{{ route('tickets.update-status', $ticket) }}" method="POST">
                            @csrf
                            <input type="hidden" name="status" value="Repair Completed">
                            <button type="submit" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700 shadow-sm">
                                ✓ Mark Repair Completed
                            </button>
                        </form>
                    @endif

                    <!-- Corrective Report Action (Fill or View) -->
                    @if($ticket->corrective)
                        <a href="{{ route('correctives.show', $ticket->corrective) }}" class="rounded-xl bg-amber-600 px-4 py-2 text-xs font-semibold text-white hover:bg-amber-700 shadow-sm transition">
                            👁 View Corrective Report
                        </a>
                    @elseif(in_array($ticket->status, ['Repair Completed', 'Waiting Corrective Report']))
                        <a href="{{ route('correctives.create', ['ticket_id' => $ticket->id]) }}" class="rounded-xl bg-emerald-600 px-4 py-2 text-xs font-semibold text-white hover:bg-emerald-700 shadow-sm transition">
                            + Fill Corrective Report
                        </a>
                    @endif

                    <!-- Close Ticket -->
                    @if($ticket->status !== 'Closed' && in_array($ticket->status, ['Repair Completed', 'Waiting Corrective Report', 'Corrective Report Completed']))
                        <form action="{{ route('tickets.close', $ticket) }}" method="POST">
                            @csrf
                            <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-xs font-semibold text-white hover:bg-slate-900 shadow-sm">
                                Close Ticket
                            </button>
                        </form>
                    @endif

                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <!-- Left Column: Details, Work Performed, Technicians -->
            <div class="md:col-span-2 space-y-6">
                
                <!-- Ticket Information Card -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3">Equipment & Ticket Information</h2>
                    
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <!-- Equipment Identification (Asset Name, Brand, Type, Serial Number) -->
                        <div class="col-span-2 rounded-2xl bg-slate-50 border border-slate-200/80 p-4 space-y-1">
                            <span class="text-xs font-semibold uppercase tracking-wider text-slate-400 block">Equipment Identification</span>
                            <div class="text-base font-bold text-slate-900">{{ $ticket->asset?->asset_name ?? 'N/A' }}</div>
                            <div class="text-xs text-slate-600 font-medium">
                                {{ $ticket->asset?->brand ?? 'No Brand' }} • {{ $ticket->asset?->type ?? 'N/A' }}
                            </div>
                            <div class="text-xs font-mono text-slate-500">
                                SN: {{ $ticket->asset?->serial_number ?? 'No Serial Number' }}
                            </div>
                        </div>

                        <div>
                            <span class="text-xs text-slate-400 block">Reported By</span>
                            <span class="font-medium text-slate-800">{{ $ticket->reported_by }}</span>
                            <span class="text-xs text-slate-400">({{ $ticket->creator_type ?? 'User' }})</span>
                        </div>

                        <div>
                            <span class="text-xs text-slate-400 block">Room / Location</span>
                            <span class="font-medium text-slate-800">{{ $ticket->room ?? $ticket->asset?->room ?? 'N/A' }}</span>
                        </div>

                        <div>
                            <span class="text-xs text-slate-400 block">Approved Status</span>
                            <span class="font-medium text-slate-800">{{ $ticket->approved_at ? 'Approved' : 'Pending Approval' }}</span>
                            @if($ticket->approved_at)
                                <span class="text-[10px] text-slate-400 block">{{ $ticket->approved_at->format('d M Y H:i') }}</span>
                            @endif
                        </div>

                        <div>
                            <span class="text-xs text-slate-400 block">Created Timestamp</span>
                            <span class="font-medium text-slate-800">{{ $ticket->created_at->format('d M Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100">
                        <span class="text-xs text-slate-400 block mb-1">Issue Description</span>
                        <div class="rounded-2xl bg-slate-50 border border-slate-200/80 p-4 text-sm text-slate-800 whitespace-pre-wrap">
                            {{ $ticket->issue }}
                        </div>
                    </div>

                    @if($ticket->rejection_reason)
                        <div class="pt-3 border-t border-red-100">
                            <span class="text-xs text-red-500 font-bold block mb-1">Rejection Reason</span>
                            <div class="rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-800">
                                {{ $ticket->rejection_reason }}
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Work Performed (Troubleshooting Actions) Card -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-5">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Work Performed (Troubleshooting Actions)</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Record troubleshooting actions. The accumulated summary automatically pre-fills the Corrective Report Solution field.</p>
                        </div>
                    </div>

                    <!-- 1. Current Accumulated Summary -->
                    <div class="space-y-2">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider block">Current Summary</span>
                        <div class="rounded-2xl bg-blue-50/50 border border-blue-100 p-4 text-xs text-slate-800 whitespace-pre-wrap leading-relaxed">
                            {{ $ticket->work_performed ?: 'No work performed actions recorded yet.' }}
                        </div>
                    </div>

                    @if($ticket->status !== 'Closed')
                        <!-- 2. Add New Troubleshooting Entry Form -->
                        <form action="{{ route('tickets.update-work-performed', $ticket) }}" method="POST" class="space-y-3 pt-2 border-t border-slate-100">
                            @csrf
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">Add Troubleshooting Entry</label>
                            <textarea
                                name="work_performed"
                                rows="3"
                                class="w-full rounded-2xl border border-slate-300 p-3.5 text-xs text-slate-800 focus:border-blue-500 focus:outline-none placeholder-slate-400"
                                placeholder="e.g. Pembersihan heater printer. / Penggantian fuse. / Testing selesai."
                                required></textarea>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition shadow-sm">
                                    + Add Entry
                                </button>
                            </div>
                        </form>
                    @endif

                    <!-- 3. Dedicated Work Performed History Timeline -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">Work Performed History</span>
                            <span class="text-[11px] text-slate-400">{{ $ticket->workLogs->count() }} log entry(s)</span>
                        </div>

                        @if($ticket->workLogs->count() > 0)
                            <div class="space-y-3">
                                @foreach($ticket->workLogs as $log)
                                    <div class="rounded-2xl border border-slate-200/80 bg-slate-50/60 p-3.5 text-xs space-y-1">
                                        <div class="flex items-center justify-between text-slate-500 border-b border-slate-200/60 pb-1.5">
                                            <span class="font-bold text-slate-800 flex items-center gap-1.5">
                                                <span class="h-1.5 w-1.5 rounded-full bg-blue-600"></span>
                                                {{ $log->performed_by }}
                                            </span>
                                            <span class="font-mono text-[10px] text-slate-400">
                                                {{ $log->created_at->format('d M Y H:i') }} ({{ $log->created_at->format('H:i') }})
                                            </span>
                                        </div>
                                        <div class="text-slate-800 whitespace-pre-wrap leading-relaxed pt-1 font-medium">
                                            {{ $log->content }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="rounded-2xl border border-dashed border-slate-200 bg-slate-50/50 p-4 text-center text-xs text-slate-400">
                                No granular troubleshooting history entries logged yet.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Equipment Completeness Card -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900">Equipment Completeness (Kelengkapan Alat)</h2>
                    </div>

                    @if($ticket->status !== 'Closed')
                        <form action="{{ route('tickets.update-movement', $ticket) }}" method="POST" class="space-y-3">
                            @csrf
                            <textarea
                                name="equipment_completeness"
                                rows="2"
                                class="w-full rounded-2xl border border-slate-300 p-3 text-xs text-slate-800 focus:border-blue-500 focus:outline-none placeholder-slate-400"
                                placeholder="e.g. Power Cord, Probe, Battery, Sensor, Stand">{{ old('equipment_completeness', $ticket->equipment_completeness) }}</textarea>
                            
                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-xs font-semibold text-white hover:bg-blue-700 transition shadow-sm">
                                    Save Completeness
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="rounded-2xl bg-slate-50 border border-slate-200/80 p-4 text-xs text-slate-800 whitespace-pre-wrap">
                            {{ $ticket->equipment_completeness ?: 'No equipment completeness recorded.' }}
                        </div>
                    @endif
                </div>

                <!-- Equipment Movement Card (Optional) -->
                @php
                    $isInWorkshop = empty($ticket->returned_date) && (!empty($ticket->sent_to_workshop_date) || !empty($ticket->sent_by));
                    $locationBadge = $isInWorkshop ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200';
                    $locationText = $isInWorkshop ? 'In Workshop' : 'In Room';
                @endphp
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div>
                            <h2 class="text-base font-bold text-slate-900">Equipment Movement (Perpindahan Alat)</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Optional details when equipment is moved to IPSRS Workshop for repair.</p>
                        </div>
                        <span class="rounded-full border px-3 py-1 text-xs font-bold {{ $locationBadge }}">
                            {{ $locationText }}
                        </span>
                    </div>

                    @if($ticket->status !== 'Closed')
                        <form action="{{ route('tickets.update-movement', $ticket) }}" method="POST" class="space-y-4">
                            @csrf
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Container 1: Equipment Sent to Workshop -->
                                <div class="rounded-2xl border border-amber-200 bg-amber-50/40 p-4 space-y-3">
                                    <span class="text-xs font-bold uppercase tracking-wider text-amber-900 block border-b border-amber-200/60 pb-1.5">1. Sent to Workshop</span>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Date</label>
                                        <input type="date" name="sent_to_workshop_date" value="{{ old('sent_to_workshop_date', $ticket->sent_to_workshop_date?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-300 p-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Handed Over By (Penyerah)</label>
                                        <input type="text" name="sent_by" value="{{ old('sent_by', $ticket->sent_by) }}" placeholder="e.g. Nurse Ratna" class="w-full rounded-xl border border-slate-300 p-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Received By (Penerima)</label>
                                        <input type="text" name="received_by_workshop" value="{{ old('received_by_workshop', $ticket->received_by_workshop) }}" placeholder="e.g. Susanto (IPSRS)" class="w-full rounded-xl border border-slate-300 p-2 text-xs">
                                    </div>
                                </div>

                                <!-- Container 2: Equipment Returned -->
                                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/40 p-4 space-y-3">
                                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-900 block border-b border-emerald-200/60 pb-1.5">2. Returned to Room</span>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Date</label>
                                        <input type="date" name="returned_date" value="{{ old('returned_date', $ticket->returned_date?->format('Y-m-d')) }}" class="w-full rounded-xl border border-slate-300 p-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Handed Over By (Penyerah)</label>
                                        <input type="text" name="returned_by" value="{{ old('returned_by', $ticket->returned_by) }}" placeholder="e.g. Susanto (IPSRS)" class="w-full rounded-xl border border-slate-300 p-2 text-xs">
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-semibold text-slate-700 mb-1">Received By (Penerima)</label>
                                        <input type="text" name="received_by_user" value="{{ old('received_by_user', $ticket->received_by_user) }}" placeholder="e.g. Nurse Ratna" class="w-full rounded-xl border border-slate-300 p-2 text-xs">
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end">
                                <button type="submit" class="rounded-xl bg-amber-600 px-5 py-2.5 text-xs font-semibold text-white hover:bg-amber-700 transition shadow-sm">
                                    Save Equipment Movement Details
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
                            <div class="rounded-2xl bg-amber-50/70 border border-amber-200 p-4 space-y-1">
                                <span class="font-bold text-amber-900 block">Sent to Workshop</span>
                                <div>Date: <strong>{{ $ticket->sent_to_workshop_date ? $ticket->sent_to_workshop_date->format('d M Y') : '—' }}</strong></div>
                                <div>Handed Over By: <strong>{{ $ticket->sent_by ?: '—' }}</strong></div>
                                <div>Received By: <strong>{{ $ticket->received_by_workshop ?: '—' }}</strong></div>
                            </div>
                            <div class="rounded-2xl bg-emerald-50/70 border border-emerald-200 p-4 space-y-1">
                                <span class="font-bold text-emerald-900 block">Returned to Room</span>
                                <div>Date: <strong>{{ $ticket->returned_date ? $ticket->returned_date->format('d M Y') : '—' }}</strong></div>
                                <div>Handed Over By: <strong>{{ $ticket->returned_by ?: '—' }}</strong></div>
                                <div>Received By: <strong>{{ $ticket->received_by_user ?: '—' }}</strong></div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Assigned Technicians -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900">Assigned Technicians</h2>
                        <span class="text-xs font-semibold text-slate-500">{{ $ticket->technicians->count() }} technician(s)</span>
                    </div>

                    <div class="mt-4">
                        @if($ticket->technicians->count() > 0)
                            <div class="flex flex-wrap gap-2">
                                @foreach($ticket->technicians as $tech)
                                    <div class="inline-flex items-center gap-2 rounded-xl bg-slate-100 border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-800">
                                        <span class="inline-block h-2 w-2 rounded-full bg-blue-500"></span>
                                        <span>{{ $tech->name }}</span>
                                        <span class="text-xs text-slate-400 font-normal">({{ ucfirst($tech->pivot->assignment_type ?? 'assigned') }})</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-400 italic">No technicians assigned yet.</p>
                        @endif
                    </div>
                </div>

            </div>

            <!-- Right Column: 3 Stacked Cards (Current Activity, Workflow Progress Stepper, Scrollable Timeline) -->
            <div class="space-y-6">
                
                <!-- CARD 1: Current Activity -->
                @php
                    $lastActivity = $ticket->activities->last();
                    $lastUpdated = $lastActivity ? $lastActivity->created_at : $ticket->updated_at;
                    $currentNotes = $lastActivity?->notes ?? $ticket->issue;
                @endphp
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <div class="flex items-center gap-2">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <h2 class="text-base font-bold text-slate-900">Current Activity</h2>
                        </div>
                        <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Live Status</span>
                    </div>

                    <div class="space-y-3">
                        <!-- Current Status -->
                        <div>
                            <span class="text-xs text-slate-400 block mb-1">Current Status</span>
                            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-bold {{ $statusClasses }}">
                                {{ $ticket->status }}
                            </span>
                        </div>

                        <!-- Current Assigned Technicians -->
                        <div>
                            <span class="text-xs text-slate-400 block mb-1">Assigned Technician(s)</span>
                            @if($ticket->technicians->count() > 0)
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($ticket->technicians as $tech)
                                        <span class="inline-flex items-center rounded-lg bg-slate-100 border border-slate-200/80 px-2.5 py-1 text-xs font-semibold text-slate-800">
                                            {{ $tech->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-xs text-slate-400 italic">None assigned</span>
                            @endif
                        </div>

                        <!-- Last Updated -->
                        <div>
                            <span class="text-xs text-slate-400 block mb-0.5">Last Update</span>
                            <span class="text-xs font-semibold text-slate-800">
                                {{ $lastUpdated ? $lastUpdated->format('d M Y • H:i') : '—' }}
                            </span>
                        </div>

                        <!-- Current Notes / Action Remarks -->
                        <div>
                            <span class="text-xs text-slate-400 block mb-1">Current Notes / Remarks</span>
                            <div class="rounded-2xl bg-slate-50 border border-slate-200/80 p-3 text-xs text-slate-700 whitespace-pre-wrap leading-relaxed">
                                {{ $currentNotes ?: 'No notes provided.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: Workflow Progress Stepper -->
                @php
                    $steps = [
                        ['id' => 'Waiting Approval', 'label' => 'Waiting Approval', 'order' => 1],
                        ['id' => 'Open', 'label' => 'Open', 'order' => 2],
                        ['id' => 'Assigned', 'label' => 'Assigned', 'order' => 3],
                        ['id' => 'Accepted', 'label' => 'Accepted', 'order' => 4],
                        ['id' => 'In Progress', 'label' => 'In Progress', 'order' => 5],
                        ['id' => 'Repair Completed', 'label' => 'Repair Completed', 'order' => 6],
                        ['id' => 'Waiting Corrective Report', 'label' => 'Corrective Report', 'order' => 7],
                        ['id' => 'Closed', 'label' => 'Closed', 'order' => 8],
                    ];

                    $statusOrderMap = [
                        'Waiting Approval' => 1,
                        'Approved' => 2,
                        'Open' => 2,
                        'Assigned' => 3,
                        'Accepted' => 4,
                        'In Progress' => 5,
                        'Waiting Sparepart' => 5,
                        'Waiting Vendor' => 5,
                        'Waiting User' => 5,
                        'Repair Completed' => 6,
                        'Waiting Corrective Report' => 7,
                        'Corrective Report Completed' => 7,
                        'Closed' => 8,
                        'Rejected' => -1,
                        'Cancelled' => -1,
                    ];

                    $currentOrder = $statusOrderMap[$ticket->status] ?? 1;
                    $isTerminalError = in_array($ticket->status, ['Rejected', 'Cancelled']);
                @endphp
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900">Workflow Progress</h2>
                        <span class="text-xs font-semibold text-slate-400">Step {{ max(1, $currentOrder) }} of 8</span>
                    </div>

                    @if($isTerminalError)
                        <div class="rounded-2xl bg-red-50 border border-red-200 p-3 text-xs text-red-800 font-medium">
                            Ticket status is <strong>{{ $ticket->status }}</strong>. Workflow halted.
                        </div>
                    @endif

                    <div class="relative pl-3 space-y-4">
                        @foreach($steps as $index => $step)
                            @php
                                $stepOrder = $step['order'];
                                $isCompleted = !$isTerminalError && ($stepOrder < $currentOrder);
                                $isCurrent = !$isTerminalError && ($stepOrder === $currentOrder);
                                $isFuture = $isTerminalError || ($stepOrder > $currentOrder);
                            @endphp

                            <div class="flex items-center gap-3">
                                <!-- Stepper Icon Circle -->
                                @if($isCompleted)
                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500 text-white font-bold text-xs shadow-sm">
                                        ✓
                                    </div>
                                @elseif($isCurrent)
                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-amber-500 text-white font-bold text-xs shadow-sm ring-4 ring-amber-100">
                                        🟡
                                    </div>
                                @else
                                    <div class="flex h-6 w-6 items-center justify-center rounded-full bg-slate-100 border border-slate-300 text-slate-400 font-semibold text-xs">
                                        ○
                                    </div>
                                @endif

                                <!-- Stepper Step Label -->
                                <div class="flex-1">
                                    <span class="text-xs font-semibold {{ $isCompleted ? 'text-slate-800' : ($isCurrent ? 'text-amber-900 font-bold' : 'text-slate-400') }}">
                                        {{ $step['label'] }}
                                    </span>

                                    <!-- Show sub-status badge if in intermediate waiting state -->
                                    @if($isCurrent && in_array($ticket->status, ['Waiting Sparepart', 'Waiting Vendor', 'Waiting User']))
                                        <span class="ml-1.5 inline-block rounded-full bg-amber-100 px-2 py-0.5 text-[10px] font-bold text-amber-800 border border-amber-200">
                                            {{ $ticket->status }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- CARD 3: Activity Timeline (Fixed height scrollable card) -->
                <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                    <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                        <h2 class="text-base font-bold text-slate-900">Activity Timeline</h2>
                        <span class="text-xs font-semibold text-slate-500">{{ $ticket->activities->count() }} event(s)</span>
                    </div>

                    <!-- Scrollable Container with Fixed Height -->
                    <div class="max-h-[500px] overflow-y-auto pr-2 space-y-4 scrollbar-thin">
                        @forelse($ticket->activities->reverse() as $activity)
                            <div class="relative pl-6 border-l-2 border-blue-500/30 pb-3 last:pb-0">
                                <div class="absolute -left-[5px] top-1.5 h-2.5 w-2.5 rounded-full bg-blue-600 ring-4 ring-white"></div>
                                <div class="text-xs font-semibold text-slate-800">{{ $activity->action }}</div>
                                <div class="text-xs text-slate-500 mt-0.5">By: {{ $activity->performed_by }}</div>
                                @if($activity->notes)
                                    <div class="text-xs text-slate-600 bg-slate-50 border border-slate-100 rounded-lg p-2 mt-1 whitespace-pre-wrap">{{ $activity->notes }}</div>
                                @endif
                                <div class="text-[10px] text-slate-400 mt-1">{{ $activity->created_at->format('d M Y • H:i') }}</div>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic">No activities logged yet.</p>
                        @endforelse
                    </div>
                </div>

            </div>

        </div>

        <!-- Requirement 3 & 4: Ticket Closed / Corrective Report Summary Card -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Corrective Report Status Summary</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Relationship summary between Ticket {{ $ticket->ticket_code }} and technical documentation.</p>
                </div>
            </div>

            @if($ticket->corrective)
                <!-- Corrective Report EXISTS -->
                <div class="rounded-2xl border border-emerald-200 bg-emerald-50/60 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1 text-xs">
                        <div class="flex items-center gap-2">
                            <span class="rounded-full bg-emerald-600 text-white p-1 text-[10px] font-bold">✓</span>
                            <span class="font-bold text-emerald-900 text-sm">Corrective Report has already been created.</span>
                        </div>
                        <div class="text-emerald-800 pl-6 space-y-0.5">
                            <div>Report Number: <strong class="font-mono">CR-{{ str_pad($ticket->corrective->id, 6, '0', STR_PAD_LEFT) }}</strong></div>
                            <div>Created Date: <strong>{{ $ticket->corrective->created_at->format('d M Y • H:i') }}</strong></div>
                            <div>Technician: <strong>{{ is_array($ticket->corrective->technician) ? implode(', ', $ticket->corrective->technician) : ($ticket->corrective->technician ?: 'N/A') }}</strong></div>
                        </div>
                    </div>

                    <div>
                        <a href="{{ route('correctives.show', $ticket->corrective) }}" class="rounded-xl bg-emerald-700 px-5 py-2.5 text-xs font-semibold text-white hover:bg-emerald-800 transition shadow-sm whitespace-nowrap inline-block">
                            👁 View Corrective Report
                        </a>
                    </div>
                </div>
            @else
                <!-- NO Corrective Report Exists -->
                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="space-y-1 text-xs">
                        <div class="font-bold text-slate-800 text-sm">Status: No Corrective Report has been created for this Ticket.</div>
                        <p class="text-slate-500">
                            Once repair is completed, create the technical documentation to close out this maintenance request.
                        </p>
                    </div>

                    <div>
                        <a href="{{ route('correctives.create', ['ticket_id' => $ticket->id]) }}" class="rounded-xl bg-blue-600 px-5 py-2.5 text-xs font-semibold text-white hover:bg-blue-700 transition shadow-sm whitespace-nowrap inline-block">
                            + Create Corrective Report
                        </a>
                    </div>
                </div>
            @endif

    </div>

</x-app-layout>