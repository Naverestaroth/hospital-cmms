<x-app-layout>

    <div class="max-w-4xl">

        <h1 class="mb-8 text-3xl font-bold">
            Schedule Preventive Maintenance
        </h1>

        <form action="{{ route('preventives.store') }}" method="POST">

            @csrf

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    Asset
                </label>

                <select
                    name="asset_id"
                    class="w-full rounded-xl border border-slate-300 p-3">

                    @foreach($assets as $asset)

                    <option value="{{ $asset->id }}">
                        {{ $asset->asset_code }} - {{ $asset->asset_name }}
                    </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    Schedule Date
                </label>

                <input
                    type="date"
                    name="schedule_date"
                    class="w-full rounded-xl border border-slate-300 p-3">

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    Technician
                </label>

                <input
                    type="text"
                    name="technician"
                    class="w-full rounded-xl border border-slate-300 p-3">

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    Status
                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border border-slate-300 p-3">

                    <option>Scheduled</option>

                    <option>Completed</option>

                </select>

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    Notes
                </label>

                <textarea
                    name="notes"
                    rows="4"
                    class="w-full rounded-xl border border-slate-300 p-3"></textarea>

            </div>

            <button
                class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">

                Schedule Maintenance

            </button>

        </form>

    </div>

</x-app-layout>