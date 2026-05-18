<?php 
include __DIR__ . '/../../includes/header.php'; 
include __DIR__ . '/../../includes/left.php';

// Kunin ang filter values para sa input fields
$startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
$endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-t');
?>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>

<main class="flex-1 p-6 lg:p-10">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">Payroll Attendance Summary</h1>
        <p class="text-slate-500 text-sm font-medium italic">Computed unique days of attendance for payroll processing.</p>
    </div>

    <!-- GET Filter Form -->
    <div class="bg-white border border-slate-200 rounded-2xl p-6 mb-8 shadow-sm">
        <form method="GET" action="" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">Start Date</label>
                <input type="date" name="start_date" value="<?= $startDate ?>" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-red-500/20 outline-none">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-[10px] font-black uppercase text-slate-400 mb-2">End Date</label>
                <input type="date" name="end_date" value="<?= $endDate ?>" 
                       class="w-full bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-red-500/20 outline-none">
            </div>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-black text-[10px] uppercase tracking-widest px-8 py-4 rounded-xl shadow-lg transition-all">
                GENERATE REPORT
            </button>
            <a href="?" class="bg-slate-900 text-white p-4 rounded-xl hover:bg-slate-800 transition shadow-lg">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </a>
        </form>
    </div>

    <!-- Summary Table -->
    <div class="bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-900 text-white uppercase text-[11px] tracking-widest font-bold">
                    <th class="px-8 py-5">Employee Name</th>
                    <th class="px-8 py-5 text-center">Field Logs (MySQL)</th>
                    <th class="px-8 py-5 text-center">Office Logs (MSSQL)</th>
                    <th class="px-8 py-5 text-center bg-red-900">Total Unique Days</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php if (!empty($summaryReport)): foreach ($summaryReport as $name => $data): ?>
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-8 py-5">
                        <p class="font-black text-slate-800 uppercase text-sm leading-none"><?= htmlspecialchars($name) ?></p>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Verified Identity</span>
                    </td>
                    <td class="px-8 py-5 text-center font-bold text-blue-600">
                        <?= $data['field_count'] ?>
                    </td>
                    <td class="px-8 py-5 text-center font-bold text-purple-600">
                        <?= $data['office_count'] ?>
                    </td>
                    <td class="px-8 py-5 text-center bg-red-50/30">
                        <span class="inline-block px-4 py-2 bg-red-600 text-white text-sm font-black rounded-lg shadow-md">
                            <?= $data['total_unique_days'] ?> Days
                        </span>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr>
                    <td colspan="4" class="px-8 py-20 text-center text-slate-300 italic">No data found for the selected date range.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>lucide.createIcons();</script>