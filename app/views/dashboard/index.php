<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/left.php'; ?>

<main id="main-content" class="flex-1 bg-[#f8fafc] overflow-y-auto p-6 lg:p-10 sidebar-transition">
    
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Central Intelligence</h1>
            <p class="text-slate-500 text-sm font-medium italic">TASSI Operational Real-time Dashboard</p>
        </div>
        <div class="flex items-center gap-2 text-xs font-bold uppercase tracking-widest text-slate-400">
            <i data-lucide="calendar" class="w-4 h-4"></i>
            <span><?= date('F j, Y') ?></span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-10">
        <!-- <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-blue-500/50 transition-all shadow-sm group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded uppercase">Total Force</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mono"><?= number_format($totalEmployees) ?></h3>
            <p class="text-slate-500 text-xs mt-1 font-semibold uppercase tracking-tighter">Registered Personnel</p>
        </div> -->
        <!-- Total Force -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition-all group border-b-4 border-b-blue-500">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg group-hover:bg-blue-600 group-hover:text-white transition-colors">
                    <i data-lucide="users" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded uppercase tracking-tighter">Total Force</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mono"><?= number_format($totalEmployees) ?></h3>
            <p class="text-slate-500 text-xs mt-1 font-semibold uppercase tracking-tighter">Registered Personnel</p>
        </div>

        <!-- <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-green-500/50 transition-all shadow-sm group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-green-50 text-green-600 rounded-lg group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded uppercase">Live Status</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mono"><?= number_format($employeesInside) ?></h3>
            <p class="text-slate-500 text-xs mt-1 font-semibold uppercase tracking-tighter">Personnel Inside</p>
        </div> -->
        <!-- Live Status -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition-all group border-b-4 border-b-green-500">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-green-50 text-green-600 rounded-lg group-hover:bg-green-600 group-hover:text-white transition-colors">
                    <i data-lucide="log-in" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-green-600 bg-green-50 px-2 py-1 rounded uppercase tracking-tighter">Live Status</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mono"><?= number_format($employeesInside) ?></h3>
            <div class="flex items-center gap-2 mt-1">
                <p class="text-slate-500 text-xs font-semibold uppercase tracking-tighter">Personnel Inside</p>
                <span class="text-[10px] text-green-500 font-bold italic">● Active</span>
            </div>
        </div>

        <!-- <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-orange-500/50 transition-all shadow-sm group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-orange-50 text-orange-600 rounded-lg group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded uppercase">Daily Logs</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mono"><?= number_format($todayAttendance) ?></h3>
            <p class="text-slate-500 text-xs mt-1 font-semibold uppercase tracking-tighter">Attended Today</p>
        </div> -->
        <!-- Daily Logs -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition-all group border-b-4 border-b-orange-500">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-orange-50 text-orange-600 rounded-lg group-hover:bg-orange-600 group-hover:text-white transition-colors">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-orange-600 bg-orange-50 px-2 py-1 rounded uppercase tracking-tighter">Daily Logs</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mono"><?= number_format($todayAttendance) ?></h3>
            <p class="text-slate-500 text-xs mt-1 font-semibold uppercase tracking-tighter">Attended Today</p>
        </div>

        <!-- <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:border-red-500/50 transition-all shadow-sm group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-red-50 text-red-600 rounded-lg group-hover:bg-red-600 group-hover:text-white transition-colors">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded uppercase">Finance</span>
            </div>
            <h3 class="text-3xl font-black text-slate-900 mono"><?= number_format($totalPayroll) ?></h3>
            <p class="text-slate-500 text-xs mt-1 font-semibold uppercase tracking-tighter">Payroll Active</p>
        </div> -->
        <!-- Finance -->
        <div class="bg-white border border-slate-200 rounded-2xl p-6 hover:shadow-lg transition-all group border-b-4 border-b-red-500">
            <div class="flex justify-between items-start mb-4">
                <div class="p-2 bg-red-50 text-red-600 rounded-lg group-hover:bg-red-600 group-hover:text-white transition-colors">
                    <i data-lucide="wallet" class="w-5 h-5"></i>
                </div>
                <span class="text-[10px] font-bold text-red-600 bg-red-50 px-2 py-1 rounded uppercase tracking-tighter">Finance</span>
            </div>
            <!-- Tinanggal ang ₱ symbol, niretain ang number_format para sa commas -->
            <h3 class="text-3xl font-black text-slate-900 mono"><?= number_format($totalPayroll) ?></h3>
            <p class="text-slate-500 text-xs mt-1 font-semibold uppercase tracking-tighter">Active Payroll Cycle</p>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-10">
        <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <h3 class="font-bold text-slate-800 flex items-center gap-2 italic">
                    <i data-lucide="trending-up" class="w-4 h-4 text-blue-600"></i> Attendance Trend
                </h3>
            </div>
            <div class="h-[300px]">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2 italic">
                <i data-lucide="building-2" class="w-4 h-4 text-red-600"></i> Department
            </h3>
            <div class="grid grid-cols-2 gap-3">
                <?php 
                $depts = ['HR' => 12, 'IT' => 18, 'Security' => 25, 'Ops' => 16, 'Admin' => 9, 'Logistics' => 14];
                foreach($depts as $name => $count): 
                ?>
                <div class="bg-slate-50 border border-slate-100 p-4 rounded-xl text-center group hover:bg-red-600 transition-all">
                    <p class="text-[10px] font-bold text-slate-400 group-hover:text-red-100 uppercase tracking-widest"><?= $name ?></p>
                    <p class="text-xl font-black text-slate-800 group-hover:text-white mono"><?= $count ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-10">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="font-bold text-sm text-slate-700 uppercase tracking-widest flex items-center gap-2">
                <i data-lucide="door-open" class="w-4 h-4 text-red-600"></i>
                Recent Door Activity
            </h3>
            <button class="text-[10px] font-bold text-blue-600 uppercase hover:underline">View All Logs</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase border-b border-slate-100">
                    <tr>
                        <th class="px-6 py-4">Date & Time</th>
                        <th class="px-6 py-4">Employee Name</th>
                        <th class="px-6 py-4">Access Point / Door</th>
                        <th class="px-6 py-4 text-right">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if($logs): while($row=sqlsrv_fetch_array($logs,SQLSRV_FETCH_ASSOC)): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4 text-xs font-medium text-slate-500 mono">
                            <?= $row['TrDateTime']->format('M j, Y H:i:s') ?>
                        </td>
                        <td class="px-6 py-4 font-bold text-slate-700 uppercase tracking-tight">
                            <?= htmlspecialchars($row['Name']) ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="flex items-center gap-2 text-xs text-slate-600 font-semibold uppercase">
                                <i data-lucide="map-pin" class="w-3 h-3 text-red-500"></i>
                                <?= htmlspecialchars($row['TrController']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <span class="bg-green-50 text-green-600 text-[10px] font-black px-2 py-1 rounded-full border border-green-100 uppercase">Success</span>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400 italic">No recent logs detected.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="bg-slate-900 rounded-2xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl relative overflow-hidden">
        <div class="absolute right-0 top-0 opacity-10 pointer-events-none">
            <i data-lucide="shield-alert" class="w-64 h-64 text-white -mr-10 -mt-10"></i>
        </div>
        
        <div class="relative z-10">
            <h3 class="text-white font-black text-xl uppercase italic">Quick Operations</h3>
            <p class="text-slate-400 text-sm font-medium">Instantly access system critical modules</p>
        </div>
        
        <div class="flex flex-wrap gap-3 relative z-10">
            <a href="employees.php" class="flex items-center gap-2 px-6 py-3 bg-white text-slate-900 rounded-xl text-xs font-bold hover:bg-blue-600 hover:text-white transition shadow-lg">
                <i data-lucide="users" class="w-4 h-4"></i> Personnel List
            </a>
            <a href="mergeAttendance.php" class="flex items-center gap-2 px-6 py-3 bg-white/10 text-white border border-white/20 rounded-xl text-xs font-bold hover:bg-white/20 transition backdrop-blur-md">
                <i data-lucide="monitor" class="w-4 h-4"></i> Live Monitor
            </a>
            <a href="payroll.php" class="flex items-center gap-2 px-6 py-3 bg-red-600 text-white rounded-xl text-xs font-bold hover:bg-red-700 transition shadow-lg shadow-red-900/20">
                <i data-lucide="banknote" class="w-4 h-4"></i> Process Payroll
            </a>
        </div>
    </div>
<?php //include __DIR__ . '/../../includes/footer.php'; ?>
</main>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Modern Chart.js Styling
    const ctx = document.getElementById('attendanceChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(37, 99, 235, 0.2)');
    gradient.addColorStop(1, 'rgba(37, 99, 235, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Logins',
                data: [45, 52, 48, 70, 65, 40, 35],
                borderColor: '#2563eb',
                borderWidth: 3,
                backgroundColor: gradient,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#2563eb',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { borderDash: [5, 5], color: '#e2e8f0' } },
                x: { grid: { display: false } }
            }
        }
    });

    // Re-trigger Icons for Dynamic Content
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
</script>

</div> 
</body>
</html>