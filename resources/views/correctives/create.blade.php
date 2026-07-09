<x-app-layout>

    <div class="max-w-4xl">

        <h1 class="mb-6 text-3xl font-bold">

            Create Corrective Maintenance Ticket

        </h1>

        @if ($errors->any())

        <div class="mb-6 rounded-xl bg-red-100 p-4 text-red-700">

            <ul>

                @foreach ($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif

        <form action="{{ route('tickets.store') }}" method="POST">

            @csrf




    </div>

    <div class="mb-5">

        <label class="mb-2 block font-medium">
            ticket
        </label>

        <select
            name="ticket_id"
            class="w-full rounded-xl border border-slate-300 p-3">

            @foreach ($tickets as $ticket)

            <option value="{{ $ticket->id }}">
                {{ $ticket->ticket_code }} -
                {{ $ticket->asset->asset_name }}
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
            value="{{ old('reported_by') }}"
            class="w-full rounded-xl border border-slate-300 p-3"
            placeholder="Example : Nurse A">

    </div>

    <div class="mb-5">

        <label class="mb-2 block font-medium">
            Issue
        </label>

        <textarea
            name="issue"
            rows="4"
            class="w-full rounded-xl border border-slate-300 p-3"
            placeholder="Describe the issue...">{{ old('issue') }}
        </textarea>

    </div>

    <div class="mb-5">

        <label class="mb-2 block font-medium">
            Priority
        </label>

        <select
            name="priority"
            class="w-full rounded-xl border border-slate-300 p-3">

            <option value="Low" {{ old('priority')=='Low' ? 'selected' : '' }}>Low</option>
            <option value="Medium" {{ old('priority')=='Medium' ? 'selected' : '' }}>Medium</option>
            <option value="High" {{ old('priority')=='High' ? 'selected' : '' }}>High</option>

        </select>

    </div>

    <div class="mt-8">

        <button
            class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">

            Create Corrective Maintenance Ticket

        </button>

    </div>
    </form>

</x-app-layout>