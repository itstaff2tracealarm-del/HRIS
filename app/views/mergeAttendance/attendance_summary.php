<?php 
include __DIR__ . '/../../includes/header.php'; 
include __DIR__ . '/../../includes/left.php';

// Kunin ang initial dates mula sa URL para "persistent" ang filter
$urlStart = $_GET['start_date'] ?? date('Y-m-01'); // Default: First day of month
$urlEnd   = $_GET['end_date'] ?? date('Y-m-t');    // Default: Last day of month
?>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Inter:wght@400;600;900&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .mono { font-family: 'JetBrains Mono', monospace; }
    </style>
</head>

<main class="flex-1 overflow-y-auto p-6 lg:p-10">
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Attendance Summary</h1>
            <p class="text-slate-500 text-sm font-medium italic">Ready for Payroll GET Extraction</p>
        </div>
        
        <div class="flex gap-4">
            <div class="bg-slate-900 p-5 rounded-2xl shadow-lg min-w-[140px]">
                <p class="text-[9px] font-bold text-slate-400 uppercase mb-1">Days Present</p>
                <p id="totalUniqueDays" class="text-2xl font-black text-white">0</p>
            </div>
            <div class="bg-red-600 p-5 rounded-2xl shadow-lg min-w-[140px]">
                <p class="text-[9px] font-bold text-red-200 uppercase mb-1">Total Hours</p>
                <p id="totalRenderedHours" class="text-2xl font-black text-white">0.00</p>
            </div>
        </div>
    </div>

    <form method="GET" action="" class="bg-white border border-slate-200 rounded-3xl p-6 mb-8 shadow-sm">
        <input type="hidden" name="a" value="attendanceSummary"> <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
            <div class="md:col-span-1">
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Employee Search</label>
                <input type="text" id="summarySearch" placeholder="Name or Card No..."
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none">
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">From</label>
                <input type="date" name="start_date" id="dateFrom" value="<?= $urlStart ?>" 
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none">
            </div>
            <div>
                <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">To</label>
                <input type="date" name="end_date" id="dateTo" value="<?= $urlEnd ?>" 
                       class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-bold outline-none">
            </div>
            <div class="flex gap-2">
                <button type="submit" class="flex-1 bg-slate-900 text-white py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-red-600 transition">
                    Filter Data
                </button>
            </div>
        </div>
    </form>

    <div class="bg-white rounded-[2rem] shadow-xl border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-900 text-white uppercase text-[11px] tracking-widest font-bold">
                    <th class="px-8 py-6">Employee</th>
                    <th class="px-8 py-6 text-center">Date</th>
                    <th class="px-8 py-6 text-center">Hours</th>
                    <th class="px-8 py-6 text-center">Action</th>
                </tr>
            </thead>
            <tbody id="summaryTableBody" class="divide-y divide-slate-100">
                <?php if (!empty($records)): foreach ($records as $row): ?>
                <tr class="log-row group hover:bg-slate-50/50 transition-all" 
                    data-name="<?= strtolower(htmlspecialchars($row['full_name'])) ?>" 
                    data-card="<?= htmlspecialchars($row['card_id']) ?>"
                    data-date="<?= $row['date'] ?>">
                    
                    <td class="px-8 py-5">
                        <div class="flex flex-col">
                            <span class="font-black text-slate-800 uppercase text-sm leading-tight"><?= htmlspecialchars($row['full_name']) ?></span>
                            <span class="text-[10px] font-bold text-slate-400 italic"><?= htmlspecialchars($row['card_id']) ?></span>
                        </div>
                    </td>
                    <td class="px-8 py-5 text-center font-bold text-slate-600 text-sm">
                        <?= date('M d, Y', strtotime($row['date'])) ?>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <?php 
                            $hrs = 0;
                            if($row['time_out'] !== '-' && $row['time_in'] !== '-') {
                                $t1 = strtotime($row['time_in']); $t2 = strtotime($row['time_out']);
                                if($t2 > $t1) $hrs = round(($t2 - $t1) / 3600, 2);
                            }
                        ?>
                        <span class="computed-hrs font-black text-slate-900" data-val="<?= $hrs ?>">
                            <?= number_format($hrs, 2) ?>
                        </span>
                    </td>
                    <td class="px-8 py-5 text-center">
                        <a href="payroll.php?a=computeFromUrl&card_no=<?= $row['card_id'] ?>&start=<?= $urlStart ?>&end=<?= $urlEnd ?>" 
                           class="inline-flex items-center gap-2 bg-blue-50 text-blue-600 px-4 py-2 rounded-lg text-[10px] font-black uppercase hover:bg-blue-600 hover:text-white transition shadow-sm border border-blue-100">
                            <i data-lucide="send" class="w-3 h-3"></i> Use for Payroll
                        </a>
                    </td>
                </tr>
                <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div>
</main>

<script>
    lucide.createIcons();

    function processComputation() {
        const search = document.getElementById('summarySearch').value.toLowerCase();
        const from = document.getElementById('dateFrom').value;
        const to = document.getElementById('dateTo').value;
        
        const rows = document.querySelectorAll('.log-row');
        let totalHrs = 0;
        let uniqueDates = new Set();

        rows.forEach(row => {
            const name = row.dataset.name;
            const card = row.dataset.card.toLowerCase();
            const date = row.dataset.date;
            const hrs = parseFloat(row.querySelector('.computed-hrs').dataset.val) || 0;

            const matchesSearch = name.includes(search) || card.includes(search);
            let matchesDate = true;
            if (from) matchesDate = matchesDate && (date >= from);
            if (to) matchesDate = matchesDate && (date <= to);

            if (matchesSearch && matchesDate) {
                row.style.display = "";
                totalHrs += hrs;
                uniqueDates.add(date);
            } else {
                row.style.display = "none";
            }
        });

        document.getElementById('totalUniqueDays').innerText = uniqueDates.size;
        document.getElementById('totalRenderedHours').innerText = totalHrs.toFixed(2);
    }

    document.getElementById('summarySearch').addEventListener('input', processComputation);
    window.onload = processComputation;
</script>