<x-app-layout>

    <div class="space-y-6">

        <h1 class="text-3xl font-bold">

            Reports

        </h1>

        <div class="grid grid-cols-3 gap-6">

            <div class="rounded-2xl bg-white p-6 shadow">

                <h2>Assets</h2>

                <p class="mt-3 text-4xl font-bold">

                    {{ $assetCount }}

                </p>

            </div>

            <div class="rounded-2xl bg-white p-6 shadow">

                <h2>Tickets</h2>

                <p class="mt-3 text-4xl font-bold">

                    {{ $ticketCount }}

                </p>

            </div>

            <div class="rounded-2xl bg-white p-6 shadow">

                <h2>Preventive</h2>

                <p class="mt-3 text-4xl font-bold">

                    {{ $preventiveCount }}

                </p>

            </div>

            <div class="rounded-2xl bg-white p-6 shadow">

                <h2>Corrective</h2>

                <p class="mt-3 text-4xl font-bold">

                    {{ $correctiveCount }}

                </p>

            </div>

            <div class="rounded-2xl bg-white p-6 shadow">

                <h2>Spareparts</h2>

                <p class="mt-3 text-4xl font-bold">

                    {{ $sparepartCount }}

                </p>

            </div>

            <div class="rounded-2xl bg-white p-6 shadow">

                <h2>Vendors</h2>

                <p class="mt-3 text-4xl font-bold">

                    {{ $vendorCount }}

                </p>

            </div>

        </div>

    </div>
   
    <a

        href="{{ route('reports.assets.pdf') }}"

        class="rounded-xl bg-red-600 px-5 py-3 text-white">

        Export PDF

    </a>

</x-app-layout>