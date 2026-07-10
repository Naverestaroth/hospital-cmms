<x-app-layout>

    <div class="max-w-4xl">

        <h1 class="mb-6 text-3xl font-bold">

            Create Vendor

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

        <form action="{{ route('documents.store') }}" method="POST">

            @csrf

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    document Code
                </label>

                <input

                    type="text"

                    name="vendor_code"

                    value="{{ old('vendor_code') }}"

                    class="w-full rounded-xl border border-slate-300 p-3">

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    document Name
                </label>

                <input

                    type="text"

                    name="documet_name"

                    value="{{ old('document_name') }}"

                    class="w-full rounded-xl border border-slate-300 p-3">

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    Contact Person
                </label>

                <input

                    type="text"

                    name="contact_person"

                    value="{{ old('contact_person') }}"

                    class="w-full rounded-xl border border-slate-300 p-3">

            </div>

            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    Phone
                </label>

                <input

                    type="text"

                    name="phone"

                    value="{{ old('phone') }}"

                    class="w-full rounded-xl border border-slate-300 p-3">

            </div>


            <div class="mb-5">

                <label class="mb-2 block font-medium">
                    Email
                </label>

                <input

                    type="email"

                    name="email"

                    value="{{ old('email') }}"

                    class="w-full rounded-xl border border-slate-300 p-3">

            <div class="mt-8">

                <button

                    class="rounded-2xl bg-emerald-600 px-6 py-3 font-semibold text-white hover:bg-emerald-700">
                    Save Vendor

                </button>

            </div>
        </form>
    </div>

</x-app-layout>