<x-app-layout>
    <div class="space-y-6">
        <div>
            <h1 class="text-3xl font-bold text-slate-900">Technicians</h1>
            <p class="mt-2 text-slate-500">Overview of technician assignments and active maintenance tasks.</p>
        </div>

        <div class="grid gap-6 md:grid-cols-2">
            <div class="ds-card">
                <div class="ds-card-body items-center text-center">
                    <div class="ds-avatar">
                        <div class="w-24 rounded-full bg-slate-200" aria-hidden="true"></div>
                    </div>
                    <h2 class="ds-card-title mt-4">Andi Pratama</h2>
                    <p class="text-slate-500">Gedung A</p>

                    <div class="mt-4 flex w-full justify-around">
                        <div class="text-center">
                            <div class="text-xl font-bold text-slate-800">5</div>
                            <div class="text-sm text-slate-500">Active Tasks</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-slate-800">12</div>
                            <div class="text-sm text-slate-500">Completed</div>
                        </div>
                    </div>

                    <div class="ds-card-actions mt-6 w-full">
                        <a href="{{ route('technicians.show', ['id' => 1]) }}" class="ds-btn ds-btn-primary w-full">Open Dashboard</a>
                    </div>
                </div>
            </div>

            <div class="ds-card">
                <div class="ds-card-body items-center text-center">
                    <div class="ds-avatar">
                        <div class="w-24 rounded-full bg-slate-200" aria-hidden="true"></div>
                    </div>
                    <h2 class="ds-card-title mt-4">Budi Santoso</h2>
                    <p class="text-slate-500">Gedung B</p>

                    <div class="mt-4 flex w-full justify-around">
                        <div class="text-center">
                            <div class="text-xl font-bold text-slate-800">3</div>
                            <div class="text-sm text-slate-500">Active Tasks</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-slate-800">25</div>
                            <div class="text-sm text-slate-500">Completed</div>
                        </div>
                    </div>

                    <div class="ds-card-actions mt-6 w-full">
                        <a href="{{ route('technicians.show', ['id' => 2]) }}" class="ds-btn ds-btn-primary w-full">Open Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
