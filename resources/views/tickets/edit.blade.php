<x-app-layout>

    <div class="max-w-4xl">

        <h1 class="mb-8 text-3xl font-bold">

            Edit Ticket

        </h1>

        <form action="{{ route('tickets.update',$ticket) }}" method="POST">

            @csrf
            @method('PUT')

            <div class="mb-5">

                <label class="mb-2 block font-medium">

                    Asset

                </label>

                <select
                    name="asset_id"
                    class="w-full rounded-xl border p-3">

                    @foreach($assets as $asset)

                    <option
                        value="{{ $asset->id }}"
                        {{ $ticket->asset_id==$asset->id?'selected':'' }}>

                        {{ $asset->asset_code }}
                        -
                        {{ $asset->asset_name }}

                    </option>

                    @endforeach

                </select>

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">

                    Reported By

                </label>

                <input
                    type="text"
                    name="reported_by"
                    value="{{ $ticket->reported_by }}"
                    class="w-full rounded-xl border p-3">

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">

                    Issue

                </label>

                <textarea
                    name="issue"
                    rows="4"
                    class="w-full rounded-xl border p-3">{{ $ticket->issue }}</textarea>

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">

                    Priority

                </label>

                <select
                    name="priority"
                    class="w-full rounded-xl border p-3">

                    <option {{ $ticket->priority=='Low'?'selected':'' }}>Low</option>

                    <option {{ $ticket->priority=='Medium'?'selected':'' }}>Medium</option>

                    <option {{ $ticket->priority=='High'?'selected':'' }}>High</option>

                </select>

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">

                    Status

                </label>

                <select
                    name="status"
                    class="w-full rounded-xl border p-3">

                    <option {{ $ticket->status=='Open'?'selected':'' }}>
                        Open
                    </option>

                    <option {{ $ticket->status=='In Progress'?'selected':'' }}>
                        In Progress
                    </option>

                    <option {{ $ticket->status=='Completed'?'selected':'' }}>
                        Completed
                    </option>

                </select>

            </div>

            <button
                class="rounded-2xl bg-emerald-600 px-6 py-3 text-white">

                Update Ticket

            </button>

        </form>

    </div>

</x-app-layout>