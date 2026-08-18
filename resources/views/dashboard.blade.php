<x-app-layout>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@500;600&display=swap" rel="stylesheet">


    <div class="main-container max-w-[1500px] mx-auto space-y-6 relative z-10">


        {{-- ── VITALS KPI STRIP (4 CARDS) ─────────────────────────── --}}
        <div class="vitals-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- KPI 1: Total Assets --}}
            <div class="vital-card glass c-green p-5 rounded-[18px] relative overflow-hidden transition-all duration-300 hover:-translate-y-1">
                <div class="vital-top flex justify-between items-start mb-3">
                    <span class="vital-label text-[11px] font-bold tracking-wider text-[#8CA0A8] uppercase">Total Assets</span>
                    <div class="vital-icon w-8 h-8 rounded-lg flex items-center justify-center bg-[#E8F6EF] text-[#2E9E6D]">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </div>
                </div>
                <div class="vital-value mono text-3xl font-bold text-[#0B1E26] tracking-tight" data-count="{{ $assetCount }}">0</div>
                <div class="vital-desc text-xs text-[#5B7480] mt-1.5 font-medium">Hospital equipment inventory</div>
                <svg class="vital-wave absolute bottom-0 left-0 right-0 w-full h-8 opacity-80 pointer-events-none" viewBox="0 0 200 34" preserveAspectRatio="none"><path d="M0 24 L40 24 L48 8 L56 30 L64 24 L90 24 L98 14 L106 24 L200 24" stroke="#2E9E6D" stroke-width="1.6" fill="none"/></svg>
            </div>

            {{-- KPI 2: Active Tickets --}}
            <div class="vital-card glass c-amber p-5 rounded-[18px] relative overflow-hidden transition-all duration-300 hover:-translate-y-1">
                <div class="vital-top flex justify-between items-start mb-3">
                    <span class="vital-label text-[11px] font-bold tracking-wider text-[#8CA0A8] uppercase">Active Tickets</span>
                    <div class="vital-icon w-8 h-8 rounded-lg flex items-center justify-center bg-[#FDF3E1] text-[#DB9A34]">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a4 4 0 1 1-5.4 5.4L3 18v3h3l6.3-6.3"/></svg>
                    </div>
                </div>
                <div class="vital-value mono text-3xl font-bold text-[#0B1E26] tracking-tight" data-count="{{ $activeOpenTicketsCount }}">0</div>
                <div class="vital-desc text-xs text-[#DB9A34] mt-1.5 font-semibold">Requires technician action</div>
                <svg class="vital-wave absolute bottom-0 left-0 right-0 w-full h-8 opacity-80 pointer-events-none" viewBox="0 0 200 34" preserveAspectRatio="none"><path d="M0 24 L60 24 L70 6 L80 28 L90 24 L200 24" stroke="#DB9A34" stroke-width="1.6" fill="none"/></svg>
            </div>

            {{-- KPI 3: Corrective This Month --}}
            <div class="vital-card glass c-blue p-5 rounded-[18px] relative overflow-hidden transition-all duration-300 hover:-translate-y-1">
                <div class="vital-top flex justify-between items-start mb-3">
                    <span class="vital-label text-[11px] font-bold tracking-wider text-[#8CA0A8] uppercase">Corrective — This Month</span>
                    <div class="vital-icon w-8 h-8 rounded-lg flex items-center justify-center bg-[#E9F1F8] text-[#3E7CB1]">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z"/></svg>
                    </div>
                </div>
                <div class="vital-value mono text-3xl font-bold text-[#0B1E26] tracking-tight" data-count="{{ $thisMonthCorrective }}">0</div>
                <div class="vital-desc text-xs text-[#3E7CB1] mt-1.5 font-semibold">Completed repair reports</div>
                <svg class="vital-wave absolute bottom-0 left-0 right-0 w-full h-8 opacity-80 pointer-events-none" viewBox="0 0 200 34" preserveAspectRatio="none"><path d="M0 26 L100 26 L110 10 L120 26 L200 26" stroke="#3E7CB1" stroke-width="1.6" fill="none"/></svg>
            </div>

            {{-- KPI 4: Preventive This Month --}}
            <div class="vital-card glass c-violet p-5 rounded-[18px] relative overflow-hidden transition-all duration-300 hover:-translate-y-1">
                <div class="vital-top flex justify-between items-start mb-3">
                    <span class="vital-label text-[11px] font-bold tracking-wider text-[#8CA0A8] uppercase">Preventive — This Month</span>
                    <div class="vital-icon w-8 h-8 rounded-lg flex items-center justify-center bg-[#EFEDFC] text-[#7C6FE0]">
                        <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/></svg>
                    </div>
                </div>
                <div class="vital-value mono text-3xl font-bold text-[#0B1E26] tracking-tight" data-count="{{ $thisMonthPreventive }}">0</div>
                <div class="vital-desc text-xs text-[#7C6FE0] mt-1.5 font-semibold">Scheduled work records</div>
                <svg class="vital-wave absolute bottom-0 left-0 right-0 w-full h-8 opacity-80 pointer-events-none" viewBox="0 0 200 34" preserveAspectRatio="none"><path d="M0 27 L200 27" stroke="#7C6FE0" stroke-width="1.6" fill="none"/></svg>
            </div>
        </div>

        {{-- ── 3-COLUMN BENTO GRID (EXACT COMPONENT REFERENCE INTEGRATION) ──── --}}
        <div class="bento grid grid-cols-1 lg:grid-cols-12 gap-5 items-start">

            {{-- COLUMN 1 (3.8 Cols) --}}
            <div class="lg:col-span-4 bento-col flex flex-col gap-5">

                {{-- CARD 1: Equipment Status Component (Operations-First) --}}
                <div class="feature-card glass rounded-[24px] p-5 flex flex-col gap-4" id="card1-equipment-status">
                    <a href="{{ route('assets.index', ['status' => 'Dalam Perbaikan']) }}"
                       title="View devices needing attention"
                       class="inset-box bench-top flex justify-between items-center p-3.5 rounded-2xl transition-all duration-200 hover:bg-white/70 block">
                        <div>
                            <div class="bench-title font-bold text-sm text-[#0B1E26]">Devices Down</div>
                            <div class="text-[11.5px] text-[#5B7480] font-medium mt-0.5">{{ number_format($equipmentAvailabilityPct, 1) }}% operational</div>
                        </div>
                        <div class="bench-value mono font-bold text-2xl {{ $devicesDown === 0 ? 'text-[#2E9E6D]' : ($devicesDown >= 20 ? 'text-[#E2574C]' : 'text-[#0B1E26]') }} flex items-center gap-1">
                            <span id="card1-kpi-count" data-target="{{ $devicesDown }}">0</span>
                        </div>
                    </a>

                    <div class="bench-rows flex flex-col gap-2.5">
                        <div class="bench-row dashed flex items-center h-5">
                            <span class="bench-tag text-[11px] font-semibold text-[#5B7480] w-20 flex-shrink-0">This week</span>
                            <div class="bench-track flex-1 h-0.5 rounded relative bg-[repeating-linear-gradient(90deg,#E2EBEE_0_5px,transparent_5px_9px)]">
                                <span class="bench-dot absolute top-1/2 w-2.5 h-2.5 rounded-full -translate-x-1/2 -translate-y-1/2 bg-[#E2574C] border-2 border-white shadow-sm" style="left:{{ $weeklyPeakPct }}%"></span>
                            </div>
                            <span class="mono text-[10px] font-bold text-[#5B7480] ml-2 w-8 text-right">{{ $ticketsThisWeek }}</span>
                        </div>
                        <div class="bench-row dashed flex items-center h-5">
                            <span class="bench-tag text-[11px] font-semibold text-[#5B7480] w-20 flex-shrink-0">Avg/day</span>
                            <div class="bench-track flex-1 h-0.5 rounded relative bg-[repeating-linear-gradient(90deg,#E2EBEE_0_5px,transparent_5px_9px)]">
                                <span class="bench-dot absolute top-1/2 w-2.5 h-2.5 rounded-full -translate-x-1/2 -translate-y-1/2 bg-[#7C6FE0] border-2 border-white shadow-sm" style="left:{{ $monthlyAvgPct }}%"></span>
                            </div>
                            <span class="mono text-[10px] font-bold text-[#5B7480] ml-2 w-8 text-right">{{ $monthlyAvgPerDay }}</span>
                        </div>

                        {{-- Dalam Perbaikan (In Repair) Bar --}}
                        <a href="{{ route('assets.index', ['status' => 'Dalam Perbaikan']) }}"
                           class="bench-row filled h-8.5 rounded-full flex items-center px-3 justify-between bg-gradient-to-r from-[#0E5E6F] to-[#00B8A9] shadow-md relative overflow-hidden cursor-pointer hover:shadow-lg transition-shadow block">
                            <span class="bench-tag text-xs font-semibold text-white z-10">Dalam Perbaikan</span>
                            <div class="flex items-center gap-2 z-10">
                                <span class="mono text-xs font-bold text-white">{{ $countDalamPerbaikan }}</span>
                                <span class="bench-handle w-5 h-5 rounded-full bg-white shadow-md flex-shrink-0"></span>
                            </div>
                        </a>

                        {{-- Tidak Berfungsi (Non-operational) Bar --}}
                        <a href="{{ route('assets.index', ['status' => 'Rusak']) }}"
                           class="bench-row filled h-8.5 rounded-full flex items-center px-3 justify-between bg-gradient-to-r from-[#E2574C] to-[#DB9A34] shadow-md relative overflow-hidden cursor-pointer hover:shadow-lg transition-shadow block">
                            <span class="bench-tag text-xs font-semibold text-white z-10">Tidak Berfungsi</span>
                            <div class="flex items-center gap-2 z-10">
                                <span class="mono text-xs font-bold text-white">{{ $countTidakBerfungsi }}</span>
                                <span class="bench-handle w-5 h-5 rounded-full bg-white shadow-md flex-shrink-0"></span>
                            </div>
                        </a>
                    </div>

                    {{-- Status Message Card (Action-Oriented) --}}
                    <a href="{{ route('assets.index', ['status' => 'Dalam Perbaikan']) }}"
                       title="View assets needing attention"
                       class="feedback-pill inset-box p-3 rounded-2xl flex items-start gap-3 text-xs text-[#5B7480] leading-snug transition-all duration-200 hover:bg-white/70 block">
                        <div class="flex items-start gap-3">
                            <span class="feedback-icon w-7 h-7 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5 {{ $statusColor }}">
                                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2">
                                    @if($statusIcon === 'check')
                                        <path d="M20 6L9 17l-5-5"/>
                                    @elseif($statusIcon === 'warning')
                                        <path d="M12 9v4m0 4h.01M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/>
                                    @else
                                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0zM12 9v4m0 4h.01"/>
                                    @endif
                                </svg>
                            </span>
                            <div class="flex-1 min-w-0">
                                <div class="font-bold text-[#0B1E26] text-xs">{{ $statusHeadline }}</div>
                                <div class="text-[11.5px] mt-0.5">{{ $statusDetail }}</div>
                            </div>
                        </div>
                    </a>

                    <div>
                        <div class="feature-heading disp text-lg font-bold text-[#0B1E26]">Equipment Status</div>
                        <div class="feature-desc text-xs text-[#5B7480] mt-0.5">Updated {{ now()->format('H:i') }} · {{ $countBerfungsi }}/{{ $totalAssetCount }} operational</div>
                    </div>
                </div>

                {{-- CARD 2: Control Centre Component (Recent Activities) --}}
                <div class="feature-card glass rounded-[24px] p-5 flex flex-col gap-4">
                    <div class="inset-box p-3 rounded-2xl space-y-2.5">
                        @forelse($recentActivities->take(3) as $act)
                            <div class="activity-item flex items-start gap-2.5 pb-2.5 border-b border-[#E2EBEE]/70 last:border-b-0 last:pb-0">
                                <div class="activity-dot w-2 h-2 rounded-full bg-[#00B8A9] mt-1 flex-shrink-0 shadow-[0_0_6px_rgba(0,184,169,0.5)]"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="activity-title text-xs font-semibold text-[#0B1E26]">
                                        {{ $act->action }} <span class="tick mono text-[11px] text-[#0E5E6F] font-bold">{{ $act->ticket?->ticket_code }}</span>
                                    </div>
                                    <div class="activity-desc text-[11.5px] text-[#5B7480] truncate mt-0.5">{{ $act->notes ?: 'Action recorded' }}</div>
                                </div>
                                <div class="activity-time mono text-[10px] text-[#8CA0A8] whitespace-nowrap">{{ $act->created_at->diffForHumans() }}</div>
                            </div>
                        @empty
                            <p class="text-xs text-[#8CA0A8] italic text-center py-2">No recent system activities.</p>
                        @endforelse
                    </div>

                    <div>
                        <div class="feature-heading disp text-lg font-bold text-[#0B1E26]">Control Centre</div>
                        <div class="feature-desc text-xs text-[#5B7480] mt-0.5">The lifeblood of your operation — both visible at a glance and thoroughly detailed.</div>
                    </div>
                </div>

            </div>

            {{-- COLUMN 2 (4.2 Cols) --}}
            <div class="lg:col-span-4 bento-col flex flex-col gap-5">

                {{-- CARD 3: Goals Component --}}
                <div class="feature-card glass rounded-[24px] p-5 flex flex-col gap-4">
                    <div class="inset-box goal-top flex justify-between items-center p-3.5 rounded-2xl">
                        <div class="goal-label font-bold text-sm text-[#0B1E26]">Preventive PM Completion</div>
                        <div class="goal-target mono font-bold text-xs text-[#5B7480]">Target: {{ $thisMonthPreventive + 20 }}</div>
                    </div>

                    <div class="inset-box goal-progress-box p-3.5 rounded-2xl flex flex-col gap-2">
                        <div class="goal-progress-head flex justify-between text-xs">
                            <span class="val mono font-bold text-[#0B1E26]">{{ $thisMonthPreventive }} completed</span>
                            <span class="goal-date text-[#8CA0A8]">{{ date('M Y') }}</span>
                        </div>
                        <div class="goal-track relative h-2.5 rounded-full bg-white/70 border border-[#E2EBEE]">
                            @php $goalPct = min(100, max(5, round(($thisMonthPreventive / max(1, $thisMonthPreventive + 20)) * 100))); @endphp
                            <div class="goal-fill h-full rounded-full bg-gradient-to-r from-[#0E5E6F] to-[#00B8A9]" style="width: {{ $goalPct }}%"></div>
                            <span class="goal-handle absolute top-1/2 w-4 h-4 rounded-full bg-[#00B8A9] border-2 border-white shadow-md -translate-x-1/2 -translate-y-1/2" style="left: {{ $goalPct }}%"></span>
                        </div>
                    </div>

                    <div>
                        <div class="feature-heading disp text-lg font-bold text-[#0B1E26]">Goals</div>
                        <div class="feature-desc text-xs text-[#5B7480] mt-0.5">Track progress toward preventive maintenance targets and rally the team to meet them.</div>
                    </div>
                </div>

                {{-- CARD 4: Dashboards Component (Fully Functional Analytics Widget) --}}
                <div class="feature-card glass rounded-[24px] p-5 flex flex-col gap-4" id="card4-widget">
                    <div class="inset-box big-number-box p-4 rounded-2xl flex flex-col gap-2">
                        <a href="{{ route('tickets.index', ['status' => 'open', 'month' => $initialAnalyticsData['month'], 'year' => $initialAnalyticsData['year']]) }}"
                           id="card4-open-link"
                           title="Click to view open tickets"
                           class="big-number mono text-4xl font-bold text-[#0B1E26] hover:opacity-75 transition-opacity block w-max cursor-pointer">
                            <span id="card4-open-val">{{ number_format($initialAnalyticsData['open_tickets']) }}</span>
                        </a>
                        <div class="big-number-label text-xs text-[#5B7480] font-medium">Open maintenance tickets</div>
                        <div class="month-tabs flex gap-1.5 mt-1" id="card4-month-tabs">
                            @foreach($availableMonths as $m)
                                <button type="button"
                                        data-month="{{ $m['month'] }}"
                                        data-year="{{ $m['year'] }}"
                                        class="month-tab text-[11px] font-semibold px-3 py-1 rounded-full border transition-all duration-300 cursor-pointer {{ $m['is_current'] ? 'active text-white bg-[#0A4A57] border-transparent shadow-sm' : 'text-[#5B7480] bg-white/50 border-[#E2EBEE] hover:bg-white/80' }}">
                                    {{ $m['name'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="inset-box rating-box p-4 rounded-2xl relative overflow-hidden flex flex-col gap-3">
                        <div class="rating-glow absolute w-60 h-36 rounded-full bg-[#7C6FE0]/25 blur-2xl right-[-50px] bottom-[-60px] pointer-events-none"></div>
                        <div class="rating-head flex justify-between items-start">
                            <div>
                                <div class="rating-label text-[10.5px] font-bold text-[#5B7480] uppercase tracking-wider">TICKET STATUS OVERVIEW</div>
                                <div class="rating-value mono text-xl font-bold text-[#0B1E26] mt-0.5">
                                    <span id="card4-total-val">{{ number_format($initialAnalyticsData['total_requests']) }}</span>
                                    <span class="rating-max text-xs text-[#8CA0A8] font-normal">total requests</span>
                                </div>
                            </div>
                            <div class="rating-meta flex gap-3 text-right">
                                <div>
                                    <span class="meta-label block text-[10px] text-[#8CA0A8]">Active</span>
                                    <span class="meta-val mono text-xs font-bold text-[#0B1E26]" id="card4-active-val">{{ number_format($initialAnalyticsData['active_tickets']) }}</span>
                                </div>
                                <div>
                                    <span class="meta-label block text-[10px] text-[#8CA0A8]">Closed</span>
                                    <span class="meta-val mono text-xs font-bold text-[#2E9E6D]" id="card4-closed-val">{{ number_format($initialAnalyticsData['closed_tickets']) }}</span>
                                </div>
                            </div>
                        </div>

                        {{-- Rating Histogram bar UI pattern --}}
                        <div class="rating-bar flex items-end gap-1.5 h-10 relative z-10" id="card4-histogram">
                            @foreach($initialAnalyticsData['histogram'] as $bar)
                                <div data-label="{{ $bar['period_label'] }}"
                                     data-created="{{ $bar['created_count'] }}"
                                     data-open="{{ $bar['open_count'] }}"
                                     data-closed="{{ $bar['closed_count'] }}"
                                     data-drill="{{ $bar['drill_url'] }}"
                                     onclick="window.location.href='{{ $bar['drill_url'] }}'"
                                     class="bar-item flex-1 rounded-t cursor-pointer transition-all duration-500 {{ $bar['created_count'] > 0 ? 'on bg-gradient-to-b from-[#FF9166] to-[#E2574C] shadow-[0_0_6px_rgba(226,87,76,0.4)]' : 'bg-[#E2EBEE]' }}"
                                     style="height: {{ $bar['height_pct'] }}%;">
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <div class="feature-heading disp text-lg font-bold text-[#0B1E26]">Dashboards</div>
                        <div class="feature-desc text-xs text-[#5B7480] mt-0.5">Dig into what's driving ticket volume and how it compares to previous months.</div>
                    </div>
                </div>

            </div>

            {{-- COLUMN 3 (4.0 Cols) --}}
            <div class="lg:col-span-4 bento-col flex flex-col gap-5">

                {{-- CARD 5: Total Usable Assets Component (Donut Chart & Legend) --}}
                <div class="feature-card glass rounded-[24px] p-5 flex flex-col items-center text-center gap-4">
                    <div class="stat-pill-row flex gap-2 w-full">
                        {{-- 1. Total Assets --}}
                        <div class="stat-pill flex-1 inset-box p-2.5 rounded-2xl text-center" title="Total Hospital Assets">
                            <div class="stat-pill-icon w-6 h-6 rounded-full bg-white/80 flex items-center justify-center mx-auto mb-1.5 text-[#0A4A57] shadow-sm">
                                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            </div>
                            <div class="stat-pill-val mono font-bold text-sm text-[#0B1E26]">{{ number_format($totalAssetCount) }}</div>
                            <div class="stat-pill-lab text-[10px] text-[#5B7480]">Total Assets</div>
                        </div>

                        {{-- 2. Total Maintenance --}}
                        <div class="stat-pill flex-1 inset-box p-2.5 rounded-2xl text-center" title="Total Maintenance Records">
                            <div class="stat-pill-icon w-6 h-6 rounded-full bg-white/80 flex items-center justify-center mx-auto mb-1.5 text-[#0A4A57] shadow-sm">
                                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a4 4 0 1 1-5.4 5.4L3 18v3h3l6.3-6.3"/></svg>
                            </div>
                            <div class="stat-pill-val mono font-bold text-sm text-[#0B1E26]">{{ number_format($maintenanceCount) }}</div>
                            <div class="stat-pill-lab text-[10px] text-[#5B7480]">Total Maintenance</div>
                        </div>

                        {{-- 3. Total Technicians (Count) --}}
                        <div class="stat-pill flex-1 inset-box p-2.5 rounded-2xl text-center" title="Total Registered Technicians">
                            <div class="stat-pill-icon w-6 h-6 rounded-full bg-white/80 flex items-center justify-center mx-auto mb-1.5 text-[#0A4A57] shadow-sm">
                                <svg viewBox="0 0 24 24" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></svg>
                            </div>
                            <div class="stat-pill-val mono font-bold text-sm text-[#0B1E26]">{{ number_format($totalTechs) }}</div>
                            <div class="stat-pill-lab text-[10px] text-[#5B7480]">Total Technicians</div>
                        </div>
                    </div>

                    {{-- Donut Chart: Usable / Maintenance / Down --}}
                    <div class="w-full flex justify-center my-1">
                        <div id="usable-assets-donut-chart" class="w-full max-w-[210px]"></div>
                    </div>

                    {{-- Legend Pills --}}
                    <div class="flex items-center justify-center gap-2 text-[11px] font-semibold text-[#0B1E26] flex-wrap">
                        <div class="flex items-center gap-1.5 bg-emerald-50 text-emerald-800 border border-emerald-200/80 px-2.5 py-1 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            <span>Usable: <b class="mono">{{ number_format($countUsable) }}</b></span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-amber-50 text-amber-800 border border-amber-200/80 px-2.5 py-1 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                            <span>Maintenance: <b class="mono">{{ number_format($countMaintenance) }}</b></span>
                        </div>
                        <div class="flex items-center gap-1.5 bg-red-50 text-red-800 border border-red-200/80 px-2.5 py-1 rounded-full">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                            <span>Down: <b class="mono">{{ number_format($countDown) }}</b></span>
                        </div>
                    </div>

                    <div>
                        <div class="feature-heading disp text-lg font-bold text-[#0B1E26]">Total Usable Assets</div>
                        <div class="feature-desc text-xs text-[#5B7480] mt-0.5">Real-time status breakdown of hospital equipment operational availability.</div>
                    </div>
                </div>

                {{-- CARD 6: Document Center List Component --}}
                <div class="feature-card glass rounded-[24px] p-5 flex flex-col gap-4 justify-between">
                    <div class="inset-box p-3.5 rounded-2xl space-y-3">
                        <div class="flex items-center justify-between border-b border-[#E2EBEE]/80 pb-2">
                            <div class="insight-title font-bold text-xs text-[#0B1E26] flex items-center gap-1.5">
                                <svg viewBox="0 0 24 24" class="w-4 h-4 text-[#0A4A57]" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <line x1="16" y1="13" x2="8" y2="13"/>
                                    <line x1="16" y1="17" x2="8" y2="17"/>
                                </svg>
                                <span>Document List</span>
                            </div>
                            <a href="{{ route('documents.index') }}" class="text-[11px] font-bold text-[#0E5E6F] hover:underline flex items-center gap-1">
                                View All
                                <svg viewBox="0 0 24 24" class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                            </a>
                        </div>

                        @if(isset($recentDocuments) && $recentDocuments->count() > 0)
                            <div class="space-y-2">
                                @foreach($recentDocuments as $doc)
                                    <div class="flex items-center justify-between gap-2.5 p-2.5 rounded-xl bg-white/80 border border-white/90 shadow-sm hover:bg-white transition">
                                        <div class="min-w-0 flex-1">
                                            <div class="flex items-center gap-2">
                                                <h4 class="text-xs font-bold text-[#0B1E26] truncate" title="{{ $doc->title }}">
                                                    {{ $doc->title }}
                                                </h4>
                                                <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-slate-100 text-slate-700 border border-slate-200 flex-shrink-0">
                                                    {{ $doc->document_type }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-1.5 mt-1 text-[10px] text-[#5B7480]">
                                                <span class="mono">{{ $doc->document_code }}</span>
                                                <span>•</span>
                                                <span>Updated {{ $doc->updated_at ? $doc->updated_at->diffForHumans() : '-' }}</span>
                                            </div>
                                        </div>

                                        <a href="{{ route('documents.show', $doc) }}"
                                           class="px-2.5 py-1 rounded-lg bg-[#0A4A57] text-white text-[10px] font-bold hover:bg-[#073640] transition flex-shrink-0 flex items-center gap-1"
                                           title="View Document Details">
                                            <span>Open</span>
                                            <svg viewBox="0 0 24 24" class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Empty State --}}
                            <div class="py-5 text-center text-[#5B7480] space-y-1.5">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center mx-auto text-slate-400">
                                    <svg viewBox="0 0 24 24" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                </div>
                                <p class="text-xs font-bold text-[#0B1E26]">No documents available</p>
                                <p class="text-[10px] text-[#5B7480]">Upload SOPs or equipment manuals in Document Center.</p>
                                <a href="{{ route('documents.create') }}" class="inline-block mt-1 px-3 py-1 rounded-xl bg-[#0A4A57] text-white text-[10px] font-bold hover:bg-[#073640] transition">
                                    + Add Document
                                </a>
                            </div>
                        @endif
                    </div>

                    <div>
                        <div class="feature-heading disp text-lg font-bold text-[#0B1E26]">Document Center</div>
                        <div class="feature-desc text-xs text-[#5B7480] mt-0.5">Quick access to official hospital SOPs, equipment manuals, and compliance documentation.</div>
                    </div>
                </div>

                {{-- CARD 7: Quick Actions Component (Proportional Background & Unclipped Radial Wheel) --}}
                <div class="feature-card glass rounded-[24px] p-5 flex flex-col items-center text-center gap-3 relative overflow-visible h-full justify-between" x-data="{ isOpen: true }">

                    <!-- Header Section -->
                    <div class="w-full border-b border-[#E2EBEE]/70 pb-2.5 flex items-center justify-between z-10">
                        <div class="text-left">
                            <h3 class="disp text-base font-bold text-[#0B1E26]">Quick Actions</h3>
                            <p class="text-xs text-[#5B7480] mt-0.5">Navigasi cepat hospital CMMS</p>
                        </div>
                        <span class="mono text-[10px] font-bold text-[#0E5E6F] bg-[#E9F1F8] px-2.5 py-1 rounded-full border border-[#0E5E6F]/20">
                            Radial Menu
                        </span>
                    </div>

                    <!-- Fixed Size Radial Wheel Cluster Area (Proportional Background) -->
                    <div class="radial-menu-cluster relative w-full h-44 flex items-center justify-center overflow-visible my-auto">

                        <!-- Outer Decorative Guide Rings -->
                        <div class="orbit-ring absolute rounded-full border border-dashed border-[#0B1E26]/20 w-44 h-44 pointer-events-none transition-transform duration-700"
                             :class="{ 'scale-100 opacity-100 rotate-45': isOpen, 'scale-75 opacity-40': !isOpen }"></div>
                        <div class="orbit-ring absolute rounded-full border border-dashed border-[#00B8A9]/25 w-32 h-32 pointer-events-none transition-transform duration-700"
                             :class="{ 'scale-100 opacity-100 -rotate-45': isOpen, 'scale-75 opacity-40': !isOpen }"></div>

                        <!-- 1. Radial Action: Tickets (Top Center) -->
                        <a href="{{ route('tickets.index') }}"
                           class="radial-action-item absolute z-20 flex items-center gap-1.5 bg-white/95 backdrop-blur border border-white rounded-full px-3 py-1 text-xs font-bold text-[#0B1E26] shadow-md hover:scale-110 hover:bg-white hover:text-[#00B8A9] transition-all duration-500 whitespace-nowrap cursor-pointer"
                           :style="isOpen ? 'transform: translate(0, -78px) scale(1); opacity: 1;' : 'transform: translate(0, 0) scale(0.6); opacity: 0.3; pointer-events: none;'"
                           title="Tickets Management">
                            <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-[#0A4A57] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/></svg>
                            <span>Tickets</span>
                        </a>

                        <!-- 2. Radial Action: Technicians (Top Right) -->
                        <a href="{{ route('technicians.index') }}"
                           class="radial-action-item absolute z-20 flex items-center gap-1.5 bg-white/95 backdrop-blur border border-white rounded-full px-3 py-1 text-xs font-bold text-[#0B1E26] shadow-md hover:scale-110 hover:bg-white hover:text-[#00B8A9] transition-all duration-500 whitespace-nowrap cursor-pointer"
                           :style="isOpen ? 'transform: translate(70px, -40px) scale(1); opacity: 1;' : 'transform: translate(0, 0) scale(0.6); opacity: 0.3; pointer-events: none;'"
                           title="Technicians Directory">
                            <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-[#0A4A57] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21v-1a8 8 0 0 1 16 0v1"/></svg>
                            <span>Technicians</span>
                        </a>

                        <!-- 3. Radial Action: Assets (Bottom Right) -->
                        <a href="{{ route('assets.index') }}"
                           class="radial-action-item absolute z-20 flex items-center gap-1.5 bg-white/95 backdrop-blur border border-white rounded-full px-3 py-1 text-xs font-bold text-[#0B1E26] shadow-md hover:scale-110 hover:bg-white hover:text-[#00B8A9] transition-all duration-500 whitespace-nowrap cursor-pointer"
                           :style="isOpen ? 'transform: translate(70px, 40px) scale(1); opacity: 1;' : 'transform: translate(0, 0) scale(0.6); opacity: 0.3; pointer-events: none;'"
                           title="Asset Inventory">
                            <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-[#0A4A57] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                            <span>Assets</span>
                        </a>

                        <!-- 4. Radial Action: Reports (Bottom Center) -->
                        <a href="{{ route('reports') }}"
                           class="radial-action-item absolute z-20 flex items-center gap-1.5 bg-white/95 backdrop-blur border border-white rounded-full px-3.5 py-1 text-xs font-bold text-[#0B1E26] shadow-md hover:scale-110 hover:bg-white hover:text-[#00B8A9] transition-all duration-500 whitespace-nowrap cursor-pointer"
                           :style="isOpen ? 'transform: translate(0, 78px) scale(1); opacity: 1;' : 'transform: translate(0, 0) scale(0.6); opacity: 0.3; pointer-events: none;'"
                           title="Reports & Analytics">
                            <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-[#0A4A57] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3v18h18"/><path d="M7 15l4-5 3 3 5-7"/></svg>
                            <span>Reports</span>
                        </a>

                        <!-- 5. Radial Action: Documents (Bottom Left) -->
                        <a href="{{ route('documents.index') }}"
                           class="radial-action-item absolute z-20 flex items-center gap-1.5 bg-white/95 backdrop-blur border border-white rounded-full px-3 py-1 text-xs font-bold text-[#0B1E26] shadow-md hover:scale-110 hover:bg-white hover:text-[#00B8A9] transition-all duration-500 whitespace-nowrap cursor-pointer"
                           :style="isOpen ? 'transform: translate(-70px, 40px) scale(1); opacity: 1;' : 'transform: translate(0, 0) scale(0.6); opacity: 0.3; pointer-events: none;'"
                           title="Document Center">
                            <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-[#0A4A57] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/></svg>
                            <span>Documents</span>
                        </a>

                        <!-- 6. Radial Action: Preventive (Top Left) -->
                        <a href="{{ route('preventives.index') }}"
                           class="radial-action-item absolute z-20 flex items-center gap-1.5 bg-white/95 backdrop-blur border border-white rounded-full px-3 py-1 text-xs font-bold text-[#0B1E26] shadow-md hover:scale-110 hover:bg-white hover:text-[#00B8A9] transition-all duration-500 whitespace-nowrap cursor-pointer"
                           :style="isOpen ? 'transform: translate(-70px, -40px) scale(1); opacity: 1;' : 'transform: translate(0, 0) scale(0.6); opacity: 0.3; pointer-events: none;'"
                           title="Preventive Schedules">
                            <svg viewBox="0 0 24 24" class="w-3.5 h-3.5 text-[#0A4A57] flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="17" rx="2"/><path d="M8 2v4M16 2v4M3 10h18"/></svg>
                            <span>Preventive</span>
                        </a>

                        <!-- Center Main Trigger Button (Tombol Utama di Tengah) -->
                        <button type="button"
                                @click="isOpen = !isOpen"
                                class="absolute z-30 w-13 h-13 rounded-full bg-gradient-to-br from-[#0E5E6F] to-[#0A4A57] text-white flex items-center justify-center shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 cursor-pointer border-2 border-white"
                                :title="isOpen ? 'Tutup Roda Pilihan' : 'Buka Roda Pilihan'">
                            <svg viewBox="0 0 24 24"
                                 class="w-6 h-6 transition-transform duration-300"
                                 :class="{ 'rotate-45': isOpen }"
                                 fill="none" stroke="currentColor" stroke-width="2.5">
                                <path d="M12 5v14M5 12h14"/>
                            </svg>
                        </button>

                    </div>

                </div>

            </div>

        </div>

        {{-- ── ROW 3: DETAILED OPERATIONS (WORKLOAD, PREVENTIVE, RAW AUDIT) ──────── --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            {{-- Technician Workload Panel --}}
            <div class="lg:col-span-4 feature-card glass rounded-[24px] p-5 space-y-4">
                <div class="panel-head flex items-center justify-between border-b border-[#E2EBEE]/70 pb-3">
                    <div>
                        <h2 class="disp text-base font-bold text-[#0B1E26]">Technician Workload</h2>
                        <p class="text-xs text-[#5B7480]">Active workload across Tickets, PM & Corrective.</p>
                    </div>
                    <span class="mono text-xs font-semibold text-[#8CA0A8] bg-white/60 px-2.5 py-0.5 rounded-full border border-white/80">{{ $techniciansWorkload->count() }} tech(s)</span>
                </div>

                <div class="space-y-3 max-h-[300px] overflow-y-auto pr-1 scrollbar-thin">
                    @forelse($techniciansWorkload as $tech)
                        @php
                            $workload = $tech->total_workload ?? 0;
                            $pct = round(($workload / $maxActiveTickets) * 100);
                            $isOnDuty = strcasecmp($tech->duty_status, 'On Duty') === 0;
                        @endphp
                        {{-- Background/latar widget mengikuti perubahan workload/data, bukan status duty --}}
                        <div class="inset-box p-3 rounded-xl space-y-1.5 transition-all duration-300 {{ $workload > 0 ? 'bg-gradient-to-r from-white via-[#F0F9FA] to-[#E6F4F6] border-[#BCE3E8]/70 shadow-sm' : 'bg-slate-50/50 border-slate-200/60' }}">
                            <div class="flex items-center justify-between text-xs">
                                {{-- Warna nama technician: hitam = On Duty, abu-abu = Off Duty --}}
                                <div class="flex items-center gap-2 {{ $isOnDuty ? 'text-[#0B1E26] font-bold' : 'text-slate-400 font-medium' }}">
                                    <span class="w-2 h-2 rounded-full {{ $isOnDuty ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                                    <span>{{ $tech->name }}</span>
                                    <span class="text-[10px] px-1.5 py-0.2 rounded font-normal {{ $isOnDuty ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $isOnDuty ? 'On Duty' : 'Off Duty' }}
                                    </span>
                                </div>
                                <span class="mono font-bold {{ $workload > 0 ? 'text-[#0A4A57]' : 'text-slate-400' }}">
                                    {{ $workload }} Tasks
                                </span>
                            </div>
                            <div class="relative h-2 w-full rounded-full bg-white/80 overflow-hidden border border-slate-200/80">
                                <div class="h-full rounded-full transition-all duration-500 {{ $workload > 0 ? 'bg-gradient-to-r from-[#0E5E6F] to-[#00B8A9]' : 'bg-slate-200' }}" style="width: {{ $workload > 0 ? max(8, $pct) : 0 }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <p class="text-xs text-[#8CA0A8] italic text-center py-4">No active technician workloads.</p>
                    @endforelse
                </div>
            </div>

            {{-- Upcoming Preventive Checks Panel --}}
            <div class="lg:col-span-4 feature-card glass rounded-[24px] p-5 space-y-4">
                <div class="panel-head flex items-center justify-between border-b border-[#E2EBEE]/70 pb-3">
                    <div>
                        <h2 class="disp text-base font-bold text-[#0B1E26]">Upcoming Preventive</h2>
                        <p class="text-xs text-[#5B7480]">Scheduled maintenance checks.</p>
                    </div>
                    <a href="{{ route('preventives.index') }}" class="text-xs font-bold text-[#2E9E6D] hover:text-[#00B8A9]">View All →</a>
                </div>

                <div class="space-y-2.5 max-h-[300px] overflow-y-auto pr-1 scrollbar-thin">
                    @forelse($upcomingPreventives as $prev)
                        @php $schDate = $prev->schedule_date ? \Carbon\Carbon::parse($prev->schedule_date) : null; @endphp
                        <div class="inset-box p-2.5 rounded-xl flex items-center justify-between text-xs">
                            <div class="space-y-0.5 truncate pr-2">
                                <span class="font-bold text-[#0B1E26] block truncate">{{ $prev->asset_name }}</span>
                                <span class="text-[#5B7480] text-[11px]">Room: {{ $prev->room ?? 'N/A' }}</span>
                            </div>
                            <span class="mono text-[10px] font-bold text-[#2E9E6D] bg-[#E8F6EF] px-2.5 py-0.5 rounded-full border border-[#2E9E6D]/20 whitespace-nowrap">
                                {{ $schDate ? $schDate->format('d M Y') : 'Scheduled' }}
                            </span>
                        </div>
                    @empty
                        <p class="text-xs text-[#8CA0A8] italic text-center py-4">No upcoming preventive checks.</p>
                    @endforelse
                </div>
            </div>

            {{-- Status Overview Panel --}}
            @php
                $countDalamPerbaikan = $countDalamPerbaikan ?? 0;
                $countProsesPenghapusan = $countProsesPenghapusan ?? ($countDisposal ?? 0);
            @endphp
            <div class="lg:col-span-4 feature-card glass rounded-[24px] p-5 space-y-4">
                <div class="panel-head flex items-center justify-between border-b border-[#E2EBEE]/70 pb-3">
                    <div>
                        <h2 class="disp text-base font-bold text-[#0B1E26]">Status Overview</h2>
                        <p class="text-xs text-[#5B7480]">Distribusi status aset dari database.</p>
                    </div>
                    <span class="mono text-xs font-bold text-[#0E5E6F] bg-[#E9F1F8] px-2.5 py-0.5 rounded-full border border-[#0E5E6F]/30">
                        {{ number_format($totalAssetCount) }} Total
                    </span>
                </div>

                <div class="space-y-3">
                    <!-- Status: Berfungsi -->
                    <a href="{{ route('assets.index', ['status' => 'berfungsi']) }}" class="inset-box p-3.5 rounded-2xl flex items-center justify-between transition-all hover:bg-white/80 block shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-sm">
                                <svg viewBox="0 0 24 24" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M20 6L9 17l-5-5"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-bold text-sm text-[#0B1E26] block">Berfungsi</span>
                                <span class="text-[11px] text-[#5B7480]">Asset operasional normal</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="mono font-bold text-lg text-[#2E9E6D]">{{ number_format($countBerfungsi) }}</span>
                            <span class="text-[10px] text-[#8CA0A8] block">unit</span>
                        </div>
                    </a>

                    <!-- Status: Rusak -->
                    <a href="{{ route('assets.index', ['status' => 'rusak']) }}" class="inset-box p-3.5 rounded-2xl flex items-center justify-between transition-all hover:bg-white/80 block shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-red-100 text-red-700 flex items-center justify-center font-bold text-sm">
                                <svg viewBox="0 0 24 24" class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-bold text-sm text-[#0B1E26] block">Rusak</span>
                                <span class="text-[11px] text-[#5B7480]">Tidak berfungsi / rusak</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="mono font-bold text-lg text-[#E2574C]">{{ number_format($countTidakBerfungsi) }}</span>
                            <span class="text-[10px] text-[#8CA0A8] block">unit</span>
                        </div>
                    </a>

                    <!-- Status: Maintenance -->
                    <a href="{{ route('assets.index', ['status' => 'dalam perbaikan']) }}" class="inset-box p-3.5 rounded-2xl flex items-center justify-between transition-all hover:bg-white/80 block shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center font-bold text-sm">
                                <svg viewBox="0 0 24 24" class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2.5">
                                    <path d="M14.7 6.3a4 4 0 1 1-5.4 5.4L3 18v3h3l6.3-6.3"/>
                                </svg>
                            </div>
                            <div>
                                <span class="font-bold text-sm text-[#0B1E26] block">Maintenance</span>
                                <span class="text-[11px] text-[#5B7480]">Dalam proses perbaikan</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="mono font-bold text-lg text-[#DB9A34]">{{ number_format($countDalamPerbaikan) }}</span>
                            <span class="text-[10px] text-[#8CA0A8] block">unit</span>
                        </div>
                    </a>

                    @if($countProsesPenghapusan > 0)
                        <!-- Status: Proses Penghapusan / Tidak Digunakan -->
                        <a href="{{ route('assets.index', ['status' => 'proses penghapusan']) }}" class="inset-box p-3.5 rounded-2xl flex items-center justify-between transition-all hover:bg-white/80 block shadow-sm">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-sm">
                                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" stroke-width="2.5">
                                        <path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                    </svg>
                                </div>
                                <div>
                                    <span class="font-bold text-sm text-[#0B1E26] block">Proses Penghapusan</span>
                                    <span class="text-[11px] text-[#5B7480]">Tidak digunakan / disposal</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="mono font-bold text-lg text-slate-700">{{ number_format($countProsesPenghapusan) }}</span>
                                <span class="text-[10px] text-[#8CA0A8] block">unit</span>
                            </div>
                        </a>
                    @endif
                </div>
            </div>

        </div>

    </div>

    {{-- ═══════════════════════════════════════════════════════════
         EXACT REFERENCE CSS STYLES & GENERAL-PURPOSE .glass SURFACE
    ═══════════════════════════════════════════════════════════════ --}}
    <style>
        .disp { font-family: 'Space Grotesk', sans-serif; }
        .mono { font-family: 'IBM Plex Mono', monospace; }

        /* ===================================================================
           1. BACKGROUND BLOBS (EXACT USER SPECIFICATION)
           =================================================================== */
        /* ===================================================================
           1. BACKGROUND BLOBS (Moved to app.css for centralized Ambient Mesh)
           =================================================================== */

        /* ===================================================================
           2. .glass — GENERAL-PURPOSE GLASS SURFACE (EXACT USER SPECIFICATION)
           =================================================================== */
        .glass{
          background: linear-gradient(165deg, rgba(255,255,255,0.6), rgba(255,255,255,0.24));
          -webkit-backdrop-filter: blur(34px) saturate(200%);
          backdrop-filter: blur(34px) saturate(200%);
          border: 1px solid rgba(255,255,255,0.75);
          box-shadow:
            inset 0 1.5px 0 rgba(255,255,255,1),           /* top rim highlight */
            inset 0 0 0 1px rgba(255,255,255,0.18),         /* faint inner edge */
            inset 0 -18px 30px -24px rgba(255,255,255,0.6), /* soft internal glow */
            0 1px 2px rgba(11,30,38,0.06),                  /* contact shadow */
            0 24px 48px -20px rgba(11,30,38,0.22);          /* floating shadow */
          position: relative;
        }
        .glass::before{
          content: '';
          position: absolute; inset: 0; border-radius: inherit;
          background: linear-gradient(125deg,
            rgba(255,255,255,0.65) 0%,
            rgba(255,255,255,0.05) 26%,
            rgba(255,255,255,0) 45%);
          pointer-events: none;
        }
        .glass::after{
          content: '';
          position: absolute; left: 8%; right: 8%; top: 0; height: 1px;
          background: linear-gradient(90deg, transparent, rgba(255,255,255,0.95), transparent);
          pointer-events: none;
        }

        /* Inset container UI component */
        .inset-box{
            background: rgba(255,255,255,0.55);
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.7);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.9);
        }

        /* Live Sync Beacon animation */
        .beacon {
            position: relative; width: 9px; height: 9px; display: inline-block;
        }
        .beacon::before, .beacon::after {
            content: ''; position: absolute; inset: 0; border-radius: 50%; background: #00B8A9;
        }
        .beacon::after {
            animation: ping 1.8s cubic-bezier(.3,.6,.7,1) infinite;
        }
        @keyframes ping {
            0% { transform: scale(1); opacity: 0.7; }
            75%, 100% { transform: scale(3.2); opacity: 0; }
        }
    </style>

    {{-- ApexCharts Library & Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


    {{-- Animated KPI Counter JS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.vital-value[data-count]').forEach(el => {
                const target = parseInt(el.dataset.count, 10) || 0;
                const duration = 1000;
                const start = performance.now();
                const format = n => n.toLocaleString();

                function tick(now) {
                    const progress = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = format(Math.round(eased * target));
                    if (progress < 1) requestAnimationFrame(tick);
                }
                requestAnimationFrame(tick);
            });
        });
    </script>

    {{-- Total Usable Assets Donut Chart Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const seriesData = [{{ $countUsable }}, {{ $countMaintenance }}, {{ $countDown }}];
            const labelsData = ['Usable', 'Maintenance', 'Down'];
            const colorsData = ['#22C55E', '#F59E0B', '#EF4444'];
            const totalCount = {{ $totalAssetCount }};

            const options = {
                series: seriesData,
                labels: labelsData,
                colors: colorsData,
                chart: {
                    type: 'donut',
                    height: 200,
                    fontFamily: 'Inter, ui-sans-serif, system-ui, -apple-system, sans-serif',
                    animations: { enabled: true, speed: 800 },
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            const index = config.dataPointIndex;
                            const statusFilters = ['berfungsi', 'dalam perbaikan', 'rusak'];
                            if (statusFilters[index]) {
                                window.location.href = "{{ route('assets.index') }}?status=" + encodeURIComponent(statusFilters[index]);
                            }
                        }
                    }
                },
                stroke: { width: 3, colors: ['#ffffff'] },
                dataLabels: { enabled: false },
                legend: { show: false },
                tooltip: {
                    enabled: true,
                    y: { formatter: function(val) { return val + " asset(s)"; } }
                },
                plotOptions: {
                    pie: {
                        expandOnClick: true,
                        donut: {
                            size: '72%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: { show: true, fontSize: '10px', fontWeight: 600, color: '#5B7480', offsetY: -6 },
                                value: { show: true, fontSize: '20px', fontWeight: 700, color: '#0B1E26', offsetY: 4, formatter: function () { return totalCount.toLocaleString(); } },
                                total: { show: true, showAlways: true, label: 'Total Assets', fontSize: '10px', fontWeight: 600, color: '#8CA0A8', formatter: function () { return totalCount.toLocaleString(); } }
                            }
                        }
                    }
                }
            };

            const usableChart = new ApexCharts(document.querySelector("#usable-assets-donut-chart"), options);
            usableChart.render();
        });
    </script>

    {{-- Donut Chart Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const statusKeys = @json(array_column($assetStatusData, 'key'));
            const seriesData = @json(array_column($assetStatusData, 'count'));
            const labelsData = @json(array_column($assetStatusData, 'label'));
            const colorsData = @json(array_column($assetStatusData, 'color'));
            const totalCount = {{ $totalAssetCount }};

            const options = {
                series: seriesData,
                labels: labelsData,
                colors: colorsData,
                chart: {
                    type: 'donut',
                    height: 220,
                    fontFamily: 'Inter, ui-sans-serif, system-ui, -apple-system, sans-serif',
                    animations: { enabled: true, speed: 800 },
                    events: {
                        dataPointSelection: function(event, chartContext, config) {
                            const selectedKey = statusKeys[config.dataPointIndex];
                            if (selectedKey) {
                                window.location.href = "{{ route('assets.index') }}?status=" + encodeURIComponent(selectedKey);
                            }
                        }
                    }
                },
                stroke: { width: 3, colors: ['#ffffff'] },
                dataLabels: { enabled: false },
                legend: { show: false },
                tooltip: {
                    enabled: true,
                    y: { formatter: function(val) { return val + " asset(s)"; } }
                },
                plotOptions: {
                    pie: {
                        expandOnClick: true,
                        donut: {
                            size: '72%',
                            background: 'transparent',
                            labels: {
                                show: true,
                                name: { show: true, fontSize: '11px', fontWeight: 600, color: '#5B7480', offsetY: -6 },
                                value: { show: true, fontSize: '22px', fontWeight: 700, color: '#0B1E26', offsetY: 4, formatter: function () { return totalCount.toLocaleString(); } },
                                total: { show: true, showAlways: true, label: 'Total Assets', fontSize: '10px', fontWeight: 600, color: '#8CA0A8', formatter: function () { return totalCount.toLocaleString(); } }
                            }
                        }
                    }
                }
            };

            const chart = new ApexCharts(document.querySelector("#asset-status-chart"), options);
            chart.render();
        });
    </script>

    {{-- Horizontal Bar Chart Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rawStatusData = @json($rawAssetStatusData);
            if (!rawStatusData || rawStatusData.length === 0) return;

            const categories = rawStatusData.map(item => item.status);
            const seriesCounts = rawStatusData.map(item => item.count);
            const computedHeight = Math.max(260, categories.length * 26);

            const options = {
                series: [{ name: 'Assets', data: seriesCounts }],
                chart: {
                    type: 'bar',
                    height: computedHeight,
                    fontFamily: 'Inter, ui-sans-serif, system-ui, -apple-system, sans-serif',
                    toolbar: { show: false },
                    animations: { enabled: true, speed: 600 }
                },
                plotOptions: {
                    bar: { horizontal: true, barHeight: '70%', borderRadius: 6, dataLabels: { position: 'top' } }
                },
                colors: ['#7C6FE0'],
                dataLabels: {
                    enabled: true,
                    textAnchor: 'start',
                    offsetX: 6,
                    style: { fontSize: '10px', fontWeight: '700', colors: ['#0B1E26'] },
                    formatter: function (val) { return val.toLocaleString(); }
                },
                xaxis: {
                    categories: categories,
                    labels: { style: { colors: '#8CA0A8', fontSize: '10px', fontWeight: 500 } }
                },
                yaxis: {
                    labels: { style: { colors: '#0B1E26', fontSize: '11px', fontWeight: 600 }, maxWidth: 180 }
                },
                grid: { borderColor: '#E2EBEE', strokeDashArray: 3 },
                tooltip: { enabled: true }
            };

            const rawChart = new ApexCharts(document.querySelector("#raw-asset-status-chart"), options);
            rawChart.render();
        });
    </script>
    {{-- Floating Liquid Glass Tooltip for Card 4 Histogram Hover --}}
    <div id="card4-tooltip" class="glass fixed z-50 px-3.5 py-2 rounded-xl text-xs shadow-xl pointer-events-none transition-opacity duration-200 opacity-0 border border-white/90" style="display: none;">
        <div class="font-bold text-[#0B1E26] text-[11px]" id="card4-tooltip-title">Week 1</div>
        <div class="flex gap-2.5 text-[10.5px] mt-1 text-[#5B7480] font-mono">
            <div>Created: <b class="text-[#0B1E26]" id="card4-tooltip-created">0</b></div>
            <div>Open: <b class="text-[#DB9A34]" id="card4-tooltip-open">0</b></div>
            <div>Closed: <b class="text-[#2E9E6D]" id="card4-tooltip-closed">0</b></div>
        </div>
    </div>

    {{-- CARD 4 Interactive Analytics Controller Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const card4 = document.getElementById('card4-widget');
            if (!card4) return;

            const monthTabs = card4.querySelectorAll('.month-tab');
            const openValEl = document.getElementById('card4-open-val');
            const openLinkEl = document.getElementById('card4-open-link');
            const totalValEl = document.getElementById('card4-total-val');
            const activeValEl = document.getElementById('card4-active-val');
            const closedValEl = document.getElementById('card4-closed-val');
            const histogramEl = document.getElementById('card4-histogram');
            const tooltipEl = document.getElementById('card4-tooltip');

            // Smooth number transition helper
            function animateNumber(el, targetVal) {
                if (!el) return;
                const startVal = parseInt(el.textContent.replace(/,/g, ''), 10) || 0;
                if (startVal === targetVal) return;
                const duration = 600;
                const startTime = performance.now();

                function step(now) {
                    const elapsed = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    const current = Math.round(startVal + (targetVal - startVal) * eased);
                    el.textContent = current.toLocaleString();
                    if (progress < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            }

            // Hover tooltip bindings
            function bindBarEvents(bar) {
                bar.addEventListener('mouseenter', function (e) {
                    if (!tooltipEl) return;
                    document.getElementById('card4-tooltip-title').textContent = this.dataset.label || '';
                    document.getElementById('card4-tooltip-created').textContent = this.dataset.created || '0';
                    document.getElementById('card4-tooltip-open').textContent = this.dataset.open || '0';
                    document.getElementById('card4-tooltip-closed').textContent = this.dataset.closed || '0';
                    tooltipEl.style.display = 'block';
                    tooltipEl.style.opacity = '1';
                });

                bar.addEventListener('mousemove', function (e) {
                    if (!tooltipEl) return;
                    tooltipEl.style.left = (e.clientX + 14) + 'px';
                    tooltipEl.style.top = (e.clientY - 46) + 'px';
                });

                bar.addEventListener('mouseleave', function () {
                    if (!tooltipEl) return;
                    tooltipEl.style.opacity = '0';
                    setTimeout(() => { if (tooltipEl.style.opacity === '0') tooltipEl.style.display = 'none'; }, 200);
                });
            }

            // Bind initial bars
            histogramEl.querySelectorAll('.bar-item').forEach(bindBarEvents);

            // Month tab click handlers
            monthTabs.forEach(tab => {
                tab.addEventListener('click', function () {
                    const month = this.dataset.month;
                    const year = this.dataset.year;

                    // Update month tab active state
                    monthTabs.forEach(t => {
                        t.classList.remove('active', 'text-white', 'bg-[#0A4A57]', 'border-transparent', 'shadow-sm');
                        t.classList.add('text-[#5B7480]', 'bg-white/50', 'border-[#E2EBEE]');
                    });
                    this.classList.add('active', 'text-white', 'bg-[#0A4A57]', 'border-transparent', 'shadow-sm');
                    this.classList.remove('text-[#5B7480]', 'bg-white/50', 'border-[#E2EBEE]');

                    // Update Open Maintenance Tickets drill-down link
                    if (openLinkEl) {
                        openLinkEl.href = "{{ route('tickets.index') }}?status=open&month=" + month + "&year=" + year;
                    }

                    // Fetch month analytics via AJAX
                    fetch("{{ route('dashboard.ticket-analytics') }}?month=" + month + "&year=" + year)
                        .then(res => res.json())
                        .then(data => {
                            // Smooth number transitions
                            animateNumber(openValEl, data.open_tickets);
                            animateNumber(totalValEl, data.total_requests);
                            animateNumber(activeValEl, data.active_tickets);
                            animateNumber(closedValEl, data.closed_tickets);

                            // Smooth histogram bar height transitions
                            histogramEl.innerHTML = '';
                            data.histogram.forEach(barData => {
                                const bar = document.createElement('div');
                                bar.dataset.label = barData.period_label;
                                bar.dataset.created = barData.created_count;
                                bar.dataset.open = barData.open_count;
                                bar.dataset.closed = barData.closed_count;
                                bar.dataset.drill = barData.drill_url;
                                bar.onclick = function () { window.location.href = barData.drill_url; };

                                const isFilled = barData.created_count > 0;
                                bar.className = 'bar-item flex-1 rounded-t cursor-pointer transition-all duration-500 ' +
                                    (isFilled ? 'on bg-gradient-to-b from-[#FF9166] to-[#E2574C] shadow-[0_0_6px_rgba(226,87,76,0.4)]' : 'bg-[#E2EBEE]');

                                bar.style.height = '0%';
                                histogramEl.appendChild(bar);
                                bindBarEvents(bar);

                                setTimeout(() => {
                                    bar.style.height = barData.height_pct + '%';
                                }, 40);
                            });
                        })
                        .catch(err => console.error('Error loading ticket analytics:', err));
                });
            });
    {{-- CARD 1 Equipment Status Animation Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const kpiEl = document.getElementById('card1-kpi-count');

            if (kpiEl) {
                const targetVal = parseInt(kpiEl.dataset.target, 10) || 0;
                const duration = 800;
                const startTime = performance.now();

                function step(now) {
                    const elapsed = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    const current = Math.round(targetVal * eased);
                    kpiEl.textContent = current;
                    if (progress < 1) requestAnimationFrame(step);
                }
                requestAnimationFrame(step);
            }
        });
    </script>

</x-app-layout>
