<x-app-layout>

    <div class="max-w-4xl space-y-6">

        <div>
            <h1 class="text-3xl font-bold text-slate-900">
                Create Maintenance Ticket
            </h1>
            <p class="mt-2 text-slate-500">
                Submit a new service ticket. Newly created tickets will enter "Waiting Approval" status for Coordinator approval.
            </p>
        </div>

        @if ($errors->any())
        <div class="rounded-2xl border border-red-200 bg-red-50 p-4 text-red-700">
            <div class="font-semibold mb-1">Please correct the following errors:</div>
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
            <form action="{{ route('tickets.store') }}" method="POST" class="space-y-6">
                @csrf

                <!-- Shared Form Fields Partial -->
                @include('tickets._form')

                <!-- Form Action Buttons -->
                <div class="flex items-center justify-between pt-4 border-t border-slate-100">
                    <a href="{{ route('tickets.index') }}" class="rounded-2xl border border-slate-300 px-6 py-3 font-semibold text-slate-700 transition hover:bg-slate-100">
                        Cancel
                    </a>

                    <button type="submit" class="rounded-2xl bg-blue-600 px-6 py-3 font-semibold text-white transition hover:bg-blue-700 shadow-md">
                        Submit Ticket
                    </button>
                </div>

            </form>
        </div>

    </div>

    <!-- Client-side Room -> Asset dynamic filtering script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const roomSelect = document.getElementById('room_select');
            const assetSelect = document.getElementById('asset_select');
            if (!roomSelect || !assetSelect) return;

            const assetOptions = Array.from(assetSelect.options);

            roomSelect.addEventListener('change', function () {
                const selectedRoom = this.value;
                assetSelect.innerHTML = '';

                // Default empty option
                const defaultOpt = document.createElement('option');
                defaultOpt.value = '';
                defaultOpt.textContent = '-- Select Asset --';
                assetSelect.appendChild(defaultOpt);

                assetOptions.forEach(opt => {
                    if (!opt.value) return;
                    const optRoom = opt.getAttribute('data-room');
                    if (!selectedRoom || optRoom === selectedRoom) {
                        assetSelect.appendChild(opt.cloneNode(true));
                    }
                });
            });
        });
    </script>

</x-app-layout>