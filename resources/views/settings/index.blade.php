<x-app-layout>

    <div class="space-y-6" x-data="{
        activeTab: '{{ $errors->any() ? 'user_role' : (request('tab') ?? (Auth::user()->isDeveloper() ? 'admin_tools' : 'profile')) }}',
        showModal: false,
        showAddUser: {{ $errors->any() ? 'true' : 'false' }},
        editProfileModalOpen: false,
        selectedTargets: []
    }">


        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    Settings & Administration
                </h1>

                <p class="mt-2 text-slate-500">
                    Pengaturan akun, peran pengguna, dan utilitas sistem CMMS.
                </p>
            </div>

            @if(Auth::user()->role === 'kepala_ipsrs')
                <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-300 bg-emerald-50 px-3.5 py-1 text-xs font-bold text-emerald-800 self-start sm:self-auto shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                    Role: Kepala IPSRS
                </span>
            @elseif(Auth::user()->role === 'teknisi')
                <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-300 bg-blue-50 px-3.5 py-1 text-xs font-bold text-blue-800 self-start sm:self-auto shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                    Role: Teknisi
                </span>
            @elseif(Auth::user()->isDeveloper())
                <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-3.5 py-1 text-xs font-bold text-amber-700 self-start sm:self-auto shadow-sm">
                    <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                    Mode: Developer Admin
                </span>
            @endif
        </div>

        <!-- Navigation Tabs -->
        <div class="flex items-center gap-2 overflow-x-auto border-b border-slate-200/80 pb-3 text-sm scrollbar-thin">
            
            @if(Auth::user()->isDeveloper())
                <!-- Admin Tools (Developer Only) -->
                <button type="button" 
                        @click="activeTab = 'admin_tools'"
                        :class="activeTab === 'admin_tools' ? 'bg-slate-900 text-white shadow-sm font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-medium'"
                        class="rounded-xl px-4 py-2 flex items-center gap-2 transition cursor-pointer">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" :class="activeTab === 'admin_tools' ? 'text-emerald-400' : 'text-slate-500'" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14.7 6.3a4 4 0 1 1-5.4 5.4L3 18v3h3l6.3-6.3"/>
                    </svg>
                    Admin Tools
                </button>
            @endif

            <!-- Profile (Accessible to all) -->
            <button type="button" 
                    @click="activeTab = 'profile'"
                    :class="activeTab === 'profile' ? 'bg-slate-900 text-white shadow-sm font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-medium'"
                    class="rounded-xl px-4 py-2 flex items-center gap-1.5 whitespace-nowrap transition cursor-pointer">
                <svg viewBox="0 0 24 24" class="w-4 h-4" :class="activeTab === 'profile' ? 'text-emerald-400' : 'text-slate-500'" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="8" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/>
                </svg>
                Profile
            </button>

            @if(Auth::user()->isDeveloper())
                <!-- Notification (Developer Only) -->
                <button type="button" 
                        @click="activeTab = 'notification'"
                        :class="activeTab === 'notification' ? 'bg-slate-900 text-white shadow-sm font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-medium'"
                        class="rounded-xl px-4 py-2 flex items-center gap-1.5 whitespace-nowrap transition cursor-pointer">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" :class="activeTab === 'notification' ? 'text-emerald-400' : 'text-slate-500'" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                    </svg>
                    Notification
                </button>
            @endif

            <!-- Appearance (Tahap Final) -->
            <button type="button" disabled class="rounded-xl bg-slate-100 px-4 py-2 font-medium text-slate-400 cursor-not-allowed opacity-60 flex items-center gap-1.5 whitespace-nowrap" title="Fitur ini akan dibuat pada tahap final">
                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 2a7 7 0 1 0 10 10"/></svg>
                Appearance (Tahap Final)
            </button>

            @if(!Auth::user()->isTeknisi())
                <!-- User & Role (Accessible for Kepala IPSRS and Developer) -->
                <button type="button" 
                        @click="activeTab = 'user_role'"
                        :class="activeTab === 'user_role' ? 'bg-slate-900 text-white shadow-sm font-semibold' : 'bg-slate-100 text-slate-600 hover:bg-slate-200 font-medium'"
                        class="rounded-xl px-4 py-2 flex items-center gap-1.5 whitespace-nowrap transition cursor-pointer">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" :class="activeTab === 'user_role' ? 'text-emerald-400' : 'text-slate-500'" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    User & Role
                </button>
            @endif
        </div>


        <!-- Notification Alerts -->
        @if (session('success'))
            <div class="w-full rounded-2xl border border-emerald-300 bg-emerald-50 p-4 text-sm font-semibold text-emerald-900 shadow-sm flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                        <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/>
                        </svg>
                    </div>
                    <span class="break-words leading-relaxed flex-1 text-emerald-900 text-sm font-bold">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="w-full rounded-2xl border border-red-300 bg-red-50 p-4 text-sm font-semibold text-red-900 shadow-sm flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-8 h-8 rounded-xl bg-red-100 text-red-700 flex items-center justify-center flex-shrink-0">
                        <svg viewBox="0 0 24 24" class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0zM12 9v4m0 4h.01"/>
                        </svg>
                    </div>
                    <span class="break-words leading-relaxed flex-1 text-red-900 text-sm font-bold">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="w-full rounded-2xl border border-emerald-300 bg-emerald-50 p-4 text-sm font-semibold text-emerald-900 shadow-sm flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0 flex-1">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                        <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4L12 14.01l-3-3"/>
                        </svg>
                    </div>
                    <span class="break-words leading-relaxed flex-1 text-emerald-900 text-sm font-bold">Password akun berhasil diperbarui.</span>
                </div>
            </div>
        @endif

        <!-- TAB CONTENT: PROFILE -->
        <div x-show="activeTab === 'profile'" x-cloak class="space-y-6">
            <!-- Profile & Role Badge Component -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
                            </svg>
                            Profil Akun
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Informasi identitas dan peran akun pengguna di sistem CMMS.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        @if(Auth::user()->role === 'kepala_ipsrs')
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-300 bg-emerald-50 px-3.5 py-1 text-xs font-bold text-emerald-800 shadow-sm">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                Kepala IPSRS
                            </span>
                        @elseif(Auth::user()->role === 'teknisi')
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-blue-300 bg-blue-50 px-3.5 py-1 text-xs font-bold text-blue-800 shadow-sm">
                                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                                Teknisi
                            </span>
                        @elseif(Auth::user()->isDeveloper())
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-300 bg-amber-50 px-3.5 py-1 text-xs font-bold text-amber-800 shadow-sm">
                                <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                                Developer Admin
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 rounded-full border border-slate-300 bg-slate-50 px-3.5 py-1 text-xs font-bold text-slate-700 shadow-sm">
                                <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                {{ ucfirst(Auth::user()->role) }}
                            </span>
                        @endif

                        <button type="button" 
                                @click="editProfileModalOpen = true"
                                class="rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-50 transition shadow-sm flex items-center gap-1.5 cursor-pointer">
                            <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                            </svg>
                            Edit
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Nama Pengguna</label>
                        <p class="text-base font-semibold text-slate-900">{{ Auth::user()->name }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Email Login</label>
                        <p class="text-base font-semibold text-slate-900">{{ Auth::user()->email }}</p>
                        <span class="text-[11px] text-slate-400 font-medium block mt-0.5">Digunakan untuk autentikasi login</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Email Google</label>
                        <p class="text-base font-semibold {{ Auth::user()->google_email ? 'text-slate-900' : 'text-slate-400 italic' }}">
                            {{ Auth::user()->google_email ?: 'Belum diatur' }}
                        </p>
                        <span class="text-[11px] text-slate-400 font-medium block mt-0.5">Terpisah dari email login</span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Nomor Telepon</label>
                        <p class="text-base font-semibold {{ Auth::user()->phone ? 'text-slate-900' : 'text-slate-400 italic' }}">
                            {{ Auth::user()->phone ?: 'Belum diatur' }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Jabatan</label>
                        <p class="text-base font-semibold text-slate-900">
                            {{ Auth::user()->role === 'teknisi' ? 'Teknisi' : (Auth::user()->role === 'kepala_ipsrs' ? 'Kepala IPSRS' : 'Administrator') }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Role Akun</label>
                        <p class="text-base font-semibold text-emerald-700">
                            {{ Auth::user()->role === 'kepala_ipsrs' ? 'Kepala IPSRS' : (Auth::user()->role === 'teknisi' ? 'Teknisi' : ucfirst(Auth::user()->role)) }}
                        </p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Status Akun</label>
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-300 bg-emerald-50 px-2.5 py-0.5 text-xs font-bold text-emerald-800">
                            <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-1">Last Login</label>
                        <p class="text-xs font-semibold text-slate-700">Sesi Aktif Saat Ini</p>
                    </div>
                </div>
            </div>


            <!-- Edit Profile Modal (Match Izin & Lembur Modal Layout & Size) -->
            <div
                x-show="editProfileModalOpen"
                x-cloak
                :class="$root.collapsed ? 'pl-0 md:pl-20' : 'pl-0 md:pl-[19.5rem] lg:pl-[21rem]'"
                class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 transition-all">
                <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="editProfileModalOpen = false"></div>
                <div class="relative z-10 w-full max-w-2xl max-h-[90vh] overflow-y-auto rounded-3xl bg-white p-6 shadow-2xl border border-slate-100 space-y-6">
                    <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Edit Profil Akun</h3>
                            <p class="text-xs text-slate-500">Perbarui data nama pengguna, email Google, dan nomor telepon akun Anda.</p>
                        </div>
                        <button type="button" @click="editProfileModalOpen = false" class="text-slate-400 hover:text-slate-700 font-bold">✕</button>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Nama Pengguna (Editable) -->
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Pengguna *</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <!-- Email Login (Read-Only) -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Email Login (Tidak Dapat Diubah)</label>
                                <input type="email" value="{{ Auth::user()->email }}" disabled readonly
                                       class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-xs text-slate-500 cursor-not-allowed font-medium">
                            </div>

                            <!-- Role Akun (Read-Only) -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-500 mb-1">Role Akun (Tidak Dapat Diubah)</label>
                                <input type="text" value="{{ Auth::user()->role === 'kepala_ipsrs' ? 'Kepala IPSRS' : ucfirst(Auth::user()->role) }}" disabled readonly
                                       class="w-full rounded-xl border border-slate-200 bg-slate-100 px-3 py-2 text-xs text-slate-500 cursor-not-allowed font-semibold">
                            </div>

                            <!-- Email Google (Editable) -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Email Google (Terpisah dari Email Login)</label>
                                <input type="email" name="google_email" value="{{ old('google_email', Auth::user()->google_email) }}" placeholder="contoh: akun@gmail.com"
                                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-emerald-500 focus:ring-emerald-500">
                            </div>

                            <!-- Nomor Telepon (Editable) -->
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-1">Nomor Telepon</label>
                                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" placeholder="contoh: 081234567890"
                                       class="w-full rounded-xl border border-slate-200 px-3 py-2 text-xs focus:border-emerald-500 focus:ring-emerald-500">
                            </div>
                        </div>

                        <div class="pt-4 flex justify-end gap-2 border-t border-slate-100">
                            <button type="button" @click="editProfileModalOpen = false" class="px-4 py-2 rounded-xl border border-slate-200 text-xs font-semibold text-slate-600 hover:bg-slate-50">Batal</button>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-sm transition">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>


            <!-- Change Password Component -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <svg viewBox="0 0 24 24" class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                            </svg>
                            Ganti Password
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Perbarui password akun Anda dengan memasukkan password lama dan password baru.
                        </p>
                    </div>
                </div>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
                    @csrf
                    @method('put')

                    <div>
                        <label for="current_password" class="block text-sm font-medium text-slate-700 mb-1">Password Lama</label>
                        <input type="password" id="current_password" name="current_password" required autocomplete="current-password"
                               class="block w-full rounded-2xl border-slate-300 py-3 px-4 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm" />
                        @if($errors->updatePassword->get('current_password'))
                            <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $errors->updatePassword->first('current_password') }}</p>
                        @endif
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password Baru</label>
                            <input type="password" id="password" name="password" required autocomplete="new-password"
                                   class="block w-full rounded-2xl border-slate-300 py-3 px-4 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm" />
                            @if($errors->updatePassword->get('password'))
                                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $errors->updatePassword->first('password') }}</p>
                            @endif
                        </div>

                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1">Konfirmasi Password Baru</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required autocomplete="new-password"
                                   class="block w-full rounded-2xl border-slate-300 py-3 px-4 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm" />
                            @if($errors->updatePassword->get('password_confirmation'))
                                <p class="mt-1.5 text-xs text-red-600 font-medium">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="rounded-2xl bg-emerald-600 hover:bg-emerald-700 px-6 py-3 font-semibold text-white transition text-sm shadow-sm flex items-center gap-2 cursor-pointer">
                            <span>Simpan Password Baru</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- TAB CONTENT: USER & ROLE -->
        <div x-show="activeTab === 'user_role'" x-cloak class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <svg viewBox="0 0 24 24" class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                            </svg>
                            User & Role Management
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Informasi peranan (role) dan otorisasi wewenang operasional pengguna di sistem CMMS.
                        </p>
                    </div>

                    @if(Auth::user()->role === 'kepala_ipsrs')
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-emerald-300 bg-emerald-50 px-3.5 py-1 text-xs font-bold text-emerald-800 shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                            Role: Kepala IPSRS (Operational Admin)
                        </span>
                    @elseif(Auth::user()->isDeveloper())
                        <span class="inline-flex items-center gap-1.5 rounded-full border border-amber-300 bg-amber-50 px-3.5 py-1 text-xs font-bold text-amber-800 shadow-sm">
                            <span class="h-2 w-2 rounded-full bg-amber-500"></span>
                            Role: Developer / Superadmin
                        </span>
                    @endif
                </div>

                <div class="space-y-4">
                    <!-- Registered Accounts List Table -->
                    <div class="rounded-2xl border border-slate-200 bg-white overflow-hidden shadow-sm">
                        <div class="px-4 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-700">Daftar Akun Pengguna Terdaftar</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-semibold text-slate-500">Total: {{ \App\Models\User::count() }} User</span>
                                <button type="button" @click="showAddUser = true" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-3 py-1 rounded-md" >+ Tambah Pengguna</button>
                            </div>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs text-slate-600">
                                <thead class="bg-slate-100/70 text-[11px] uppercase tracking-wider text-slate-500 font-bold border-b border-slate-200">
                                    <tr>
                                        <th class="px-4 py-3">Nama</th>
                                        <th class="px-4 py-3">Email Login</th>
                                        <th class="px-4 py-3">Email Google</th>
                                        <th class="px-4 py-3">No. Telepon</th>
                                        <th class="px-4 py-3">Role</th>
                                        <th class="px-4 py-3">Status</th>
                                        @if(Auth::user()->isDeveloper())
                                            <th class="px-4 py-3 text-right">Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach(\App\Models\User::all() as $u)
                                        <tr class="hover:bg-slate-50/80 transition">
                                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $u->name }}</td>
                                            <td class="px-4 py-3 font-medium text-slate-700">{{ $u->email }}</td>
                                            <td class="px-4 py-3 text-slate-500">{{ $u->google_email ?: '-' }}</td>
                                            <td class="px-4 py-3 text-slate-500">{{ $u->phone ?: '-' }}</td>
                                            <td class="px-4 py-3">
                                                @if($u->role === 'kepala_ipsrs')
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[11px] font-bold text-emerald-700 border border-emerald-200">Kepala IPSRS</span>
                                                @elseif($u->role === 'teknisi')
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-2.5 py-0.5 text-[11px] font-bold text-blue-700 border border-blue-200">Teknisi</span>
                                                @elseif($u->isDeveloper())
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-[11px] font-bold text-amber-700 border border-amber-200">Developer Admin</span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2.5 py-0.5 text-[11px] font-bold text-slate-700 border border-slate-200">{{ ucfirst($u->role) }}</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                                </span>
                                            </td>
                                            @if(Auth::user()->isDeveloper())
                                                <td class="px-4 py-3 text-right">
                                                    @if($u->id !== Auth::user()->id)
                                                        <form action="{{ route('settings.users.destroy', $u->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun {{ addslashes($u->name) }} ({{ $u->email }})?');" class="inline-block">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex items-center gap-1 text-xs font-semibold text-rose-600 hover:text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 px-2.5 py-1 rounded-lg transition" title="Hapus Akun Pengguna">
                                                                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2">
                                                                    <path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/><line x1="10" y1="11" x2="10" y2="17"/><line x1="14" y1="11" x2="14" y2="17"/>
                                                                </svg>
                                                                Hapus
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-[11px] font-semibold text-slate-400 italic">Akun Anda</span>
                                                    @endif
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50/50 p-4">
                        <h3 class="text-sm font-bold text-slate-900 mb-2">Detail Pengguna Saat Ini</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs">
                            <div>
                                <span class="text-slate-400 font-medium block">NAMA</span>
                                <span class="font-bold text-slate-800 text-sm">{{ Auth::user()->name }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 font-medium block">EMAIL</span>
                                <span class="font-bold text-slate-800 text-sm">{{ Auth::user()->email }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 font-medium block">WEWENANG OTORISASI</span>
                                <span class="font-bold text-emerald-700 text-sm">
                                    {{ Auth::user()->role === 'kepala_ipsrs' ? 'Administrator Operasional CMMS' : 'Full Developer Access' }}
                                </span>
                            </div>
                        </div>
                    </div>


                    <div class="space-y-3">
                        <h3 class="text-sm font-bold text-slate-900">Hak Akses & Permission Role</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                            <div class="flex items-center gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50/60 p-3 text-emerald-900">
                                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                                <span class="font-semibold">Manajemen Asset, Vendor, Sparepart & Dokumen</span>
                            </div>

                            <div class="flex items-center gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50/60 p-3 text-emerald-900">
                                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                                <span class="font-semibold">Pemantauan Ticket, Maintenance & Equipment Movements</span>
                            </div>

                            <div class="flex items-center gap-2.5 rounded-xl border border-emerald-200 bg-emerald-50/60 p-3 text-emerald-900">
                                <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                                <span class="font-semibold">Akses Laporan PDF & Riwayat Pemeliharaan</span>
                            </div>

                            @if(Auth::user()->role === 'kepala_ipsrs')
                                <div class="flex items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-100 p-3 text-slate-500">
                                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12"/></svg>
                                    <span>Fitur Developer & Reset Database (Dibatasi Khusus Developer)</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2.5 rounded-xl border border-amber-200 bg-amber-50/60 p-3 text-amber-900">
                                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5"/></svg>
                                    <span class="font-semibold">Wipe Input Data & Utility Developer Admin</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->isDeveloper())
        <!-- TAB CONTENT: NOTIFICATION (Developer Only) -->
        <div x-show="activeTab === 'notification'" x-cloak class="space-y-6">
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-4">
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <svg viewBox="0 0 24 24" class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                            </svg>
                            System Notification Preferences
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Pengaturan notifikasi sistem CMMS untuk pengembang.
                        </p>
                    </div>
                </div>

                <div class="rounded-2xl bg-amber-50/60 border border-amber-200 p-4 text-xs text-amber-900">
                    Semua notifikasi sistem berjalan secara normal dan aktif di lingkungan pengembangan.
                </div>
            </div>
        </div>

        <!-- TAB CONTENT: ADMIN TOOLS (Developer Only) -->
        <div x-show="activeTab === 'admin_tools'" x-cloak class="space-y-6">
            <!-- Admin Tools: Wipe Input Data Component -->
            <div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
                <div class="flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900 flex items-center gap-2">
                            <svg viewBox="0 0 24 24" class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                            Wipe Input Data
                        </h2>
                        <p class="mt-1 text-sm text-slate-500">
                            Bersihkan atau reset data input yang ditentukan dari database CMMS.
                        </p>
                    </div>

                    <span class="rounded-full border border-red-200 bg-red-50 px-3.5 py-1 text-xs font-bold text-red-700 shadow-sm">
                        Fitur Destruktif Developer
                    </span>
                </div>

                <form action="{{ route('settings.wipe') }}" method="POST" id="wipe-form" @submit.prevent>
                    @csrf

                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <label class="text-xs font-bold uppercase tracking-wider text-slate-400 block">
                                Pilih Kategori Data Input Yang Ingin Dibersihkan:
                            </label>
                            <div class="flex items-center gap-3 text-xs">
                                <button type="button" @click="selectedTargets = ['assets', 'tickets', 'preventive', 'corrective', 'schedules', 'movements', 'documents', 'spareparts', 'vendors']" class="text-blue-600 hover:underline font-semibold">
                                    Pilih Semua
                                </button>
                                <span class="text-slate-300">•</span>
                                <button type="button" @click="selectedTargets = []" class="text-slate-500 hover:underline font-semibold">
                                    Batalkan Semua
                                </button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

                            <!-- Checkbox Assets -->
                            <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                                   :class="selectedTargets.includes('assets') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                <input type="checkbox" name="targets[]" value="assets" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block">Assets / Equipment</span>
                                    <span class="text-xs text-slate-500">Peralatan medis & non-medis rumah sakit</span>
                                </div>
                            </label>

                            <!-- Checkbox Schedules -->
                            <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                                   :class="selectedTargets.includes('schedules') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                <input type="checkbox" name="targets[]" value="schedules" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block">Jadwal & History Teknisi</span>
                                    <span class="text-xs text-slate-500">Input jadwal Excel & history shift/izin</span>
                                </div>
                            </label>

                            <!-- Checkbox Tickets -->
                            <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                                   :class="selectedTargets.includes('tickets') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                <input type="checkbox" name="targets[]" value="tickets" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block">Data Tickets</span>
                                    <span class="text-xs text-slate-500">Laporan tiket kerusakan & perbaikan</span>
                                </div>
                            </label>

                            <!-- Checkbox Equipment Movements -->
                            <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                                   :class="selectedTargets.includes('movements') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                <input type="checkbox" name="targets[]" value="movements" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block">Equipment Movements</span>
                                    <span class="text-xs text-slate-500">Riwayat & mutasi perpindahan peralatan</span>
                                </div>
                            </label>

                            <!-- Checkbox Document Center -->
                            <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                                   :class="selectedTargets.includes('documents') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                <input type="checkbox" name="targets[]" value="documents" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block">Document Center</span>
                                    <span class="text-xs text-slate-500">Arsip dokumen, manual & sertifikat</span>
                                </div>
                            </label>

                            <!-- Checkbox Preventive Maintenance -->
                            <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                                   :class="selectedTargets.includes('preventive') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                <input type="checkbox" name="targets[]" value="preventive" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block">Data Preventive</span>
                                    <span class="text-xs text-slate-500">Jadwal Preventive Maintenance</span>
                                </div>
                            </label>

                            <!-- Checkbox Corrective Maintenance -->
                            <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                                   :class="selectedTargets.includes('corrective') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                <input type="checkbox" name="targets[]" value="corrective" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block">Data Corrective</span>
                                    <span class="text-xs text-slate-500">Laporan Corrective Maintenance</span>
                                </div>
                            </label>

                            <!-- Checkbox Spareparts -->
                            <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                                   :class="selectedTargets.includes('spareparts') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                <input type="checkbox" name="targets[]" value="spareparts" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block">Data Spareparts</span>
                                    <span class="text-xs text-slate-500">Stok inventaris suku cadang</span>
                                </div>
                            </label>

                            <!-- Checkbox Vendors -->
                            <label class="flex items-start gap-3 rounded-2xl border p-4 transition cursor-pointer"
                                   :class="selectedTargets.includes('vendors') ? 'border-red-300 bg-red-50/40 ring-2 ring-red-500/20' : 'border-slate-200 hover:border-slate-300 hover:bg-slate-50'">
                                <input type="checkbox" name="targets[]" value="vendors" class="mt-1 rounded border-slate-300 text-red-600 focus:ring-red-500" x-model="selectedTargets">
                                <div>
                                    <span class="font-bold text-slate-900 text-sm block">Data Vendors</span>
                                    <span class="text-xs text-slate-500">Daftar vendor penyedia jasa/peralatan</span>
                                </div>
                            </label>

                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <p class="text-xs text-slate-500 font-medium">
                            * Konfirmasi akan ditampilkan sebelum proses eksekusi pembersihan data.
                        </p>

                        <button
                            type="button"
                            @click="if (selectedTargets.length === 0) { alert('Silakan pilih setidaknya satu kategori data yang ingin dibersihkan.'); } else { showModal = true; }"
                            class="rounded-2xl bg-red-600 px-6 py-3.5 font-semibold text-white transition hover:bg-red-700 shadow-sm flex items-center justify-center gap-2 text-sm self-end sm:self-auto">
                            <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            </svg>
                            Wipe Input
                        </button>
                    </div>

                    <!-- Confirmation Modal -->
                    <div x-show="showModal"
                         x-cloak
                         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm pointer-events-auto">
                        <div class="w-full max-w-sm rounded-3xl bg-white p-5 shadow-2xl space-y-4 border border-slate-200 pointer-events-auto relative z-[10000]"
                             @click.away="showModal = false">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-2xl bg-red-100 text-red-600 flex items-center justify-center text-lg font-bold flex-shrink-0">
                                    ⚠️
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-slate-900">Konfirmasi Wipe Input</h3>
                                    <p class="text-xs text-slate-500">Tindakan ini menghapus data permanen.</p>
                                </div>
                            </div>

                            <div class="rounded-2xl bg-red-50 border border-red-200 p-3.5 text-xs text-red-800 space-y-1.5">
                                <p class="font-bold">Apakah Anda yakin ingin membersihkan data input?</p>
                                <p class="text-[11px] text-red-700">Kategori data berikut akan di-reset dari database:</p>
                                <ul class="list-disc list-inside font-mono font-semibold space-y-0.5 text-[11px]">
                                    <template x-for="target in selectedTargets" :key="target">
                                        <li x-text="target.toUpperCase()"></li>
                                    </template>
                                </ul>
                            </div>

                            <div class="flex items-center justify-end gap-2.5 pt-1">
                                <button
                                    type="button"
                                    @click="showModal = false"
                                    class="rounded-xl border border-slate-200 px-3.5 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                                    Batal
                                </button>

                                <button
                                    type="button"
                                    @click="showModal = false; document.getElementById('wipe-form').submit()"
                                    class="rounded-xl bg-red-600 px-4 py-2 text-xs font-bold text-white hover:bg-red-700 shadow-sm transition flex items-center gap-1.5 cursor-pointer">
                                    Ya, Wipe Data Sekarang
                                </button>
                            </div>
                        </div>
                    </div>

                </form>

            </div>
        </div>
        @endif

    <!-- Add User Modal (top-level, outside overflow-hidden containers) -->
    <div x-show="showAddUser" x-cloak
         class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg p-6 relative z-[10000] border border-slate-100"
             @click.away="showAddUser = false">
            <h2 class="text-xl font-bold text-slate-900 mb-4 flex items-center gap-2">
                <svg viewBox="0 0 24 24" class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/>
                </svg>
                Tambah Pengguna Baru
            </h2>

            @if($errors->any())
                <div class="rounded-2xl border border-red-300 bg-red-50 p-3 mb-4">
                    <p class="text-sm font-bold text-red-800 mb-1">Gagal menambah pengguna:</p>
                    <ul class="list-disc list-inside text-xs text-red-700 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('settings.users.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1" for="add_user_name">Nama</label>
                    <input type="text" name="name" id="add_user_name" required value="{{ old('name') }}"
                           class="block w-full rounded-2xl border-slate-300 py-3 px-4 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1" for="add_user_email">Email Login</label>
                    <input type="email" name="email" id="add_user_email" required value="{{ old('email') }}"
                           class="block w-full rounded-2xl border-slate-300 py-3 px-4 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1" for="add_user_password">Password</label>
                    <input type="password" name="password" id="add_user_password" required minlength="8"
                           class="block w-full rounded-2xl border-slate-300 py-3 px-4 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm" />
                    <p class="mt-1 text-xs text-slate-400">Minimal 8 karakter</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1" for="add_user_role">Role</label>
                    <select name="role" id="add_user_role" required
                            class="block w-full rounded-2xl border-slate-300 py-3 px-4 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm">
                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="kepala_ipsrs" {{ old('role') == 'kepala_ipsrs' ? 'selected' : '' }}>Kepala IPSRS</option>
                        <option value="teknisi" {{ old('role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                        <option value="developer" {{ old('role') == 'developer' ? 'selected' : '' }}>Developer</option>
                    </select>

                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1" for="add_user_google_email">Google Email (optional)</label>
                    <input type="email" name="google_email" id="add_user_google_email" value="{{ old('google_email') }}"
                           class="block w-full rounded-2xl border-slate-300 py-3 px-4 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1" for="add_user_phone">No. Telepon (optional)</label>
                    <input type="text" name="phone" id="add_user_phone" value="{{ old('phone') }}"
                           class="block w-full rounded-2xl border-slate-300 py-3 px-4 focus:border-emerald-500 focus:ring-emerald-500 text-sm shadow-sm" />
                </div>
                <div class="pt-3 border-t border-slate-100 flex justify-end space-x-2">
                    <button type="button" @click="showAddUser = false"
                            class="rounded-2xl bg-slate-100 hover:bg-slate-200 px-5 py-2.5 font-semibold text-slate-700 transition text-sm cursor-pointer">Batal</button>
                    <button type="submit"
                            class="rounded-2xl bg-emerald-600 hover:bg-emerald-700 px-5 py-2.5 font-semibold text-white transition text-sm shadow-sm cursor-pointer">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    </div>

</x-app-layout>