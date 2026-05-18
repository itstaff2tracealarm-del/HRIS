<?php 
// Kunin ang header at navigation
include __DIR__ . '/../../includes/header.php'; 
include __DIR__ . '/../../includes/left.php';

// Siguraduhin na ang $records ay array para hindi mag-error ang count
$records = $records ?? []; 
?>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Inter:wght@400;600;900&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .sidebar-transition { transition: all 0.3s ease; }
    </style>
</head>

<main id="main-content" class="flex-1 overflow-y-auto p-6 lg:p-10 sidebar-transition">
    
    <div class="flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
        <a href="dashboard.php" class="hover:text-red-600 transition flex items-center gap-1.5">
            <i data-lucide="layout-dashboard" class="w-3 h-3"></i> Dashboard
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-900 flex items-center gap-1.5">
            <i data-lucide="combine" class="w-3 h-3 text-red-600"></i> Merged Attendance
        </span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Merged Attendance Log</h1>
            <p class="text-slate-500 text-sm font-medium italic">MySQL (Field) + MSSQL (Office) Unified Registry</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <div class="flex bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
                <button id="exportExcel" class="flex items-center gap-2 px-3 py-1.5 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50 transition uppercase tracking-wider">
                    <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-green-600"></i> Excel
                </button>
                <div class="w-px h-4 bg-slate-200 self-center mx-1"></div>
                <button id="exportPDF" class="flex items-center gap-2 px-3 py-1.5 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-slate-50 transition uppercase tracking-wider">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-red-600"></i> PDF
                </button>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" id="attendanceSearch" 
                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/10 transition placeholder:text-slate-400"
                   placeholder="SEARCH BY CARD NO OR SOURCE...">
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <input type="date" id="filterDate" class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 focus:outline-none cursor-pointer hover:bg-slate-100 transition">
            
            <select id="filterSource" class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 focus:outline-none cursor-pointer hover:bg-slate-100 transition leading-none">
                <option value="">All Sources</option>
                <option value="FIELD">Field (MySQL)</option>
                <option value="OFFICE">Office (MSSQL)</option>
            </select>

            <button id="resetFilter" class="p-3 bg-slate-900 text-white rounded-xl hover:bg-red-600 transition shadow-md shadow-slate-200" title="Reset Filters">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-10">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/30">
            <h3 class="font-bold text-sm text-slate-700 uppercase tracking-widest flex items-center gap-2 italic">
                <i data-lucide="database" class="w-4 h-4 text-red-600"></i> Operational Data Grid
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse" id="mergedTable">
                <thead class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase border-b border-slate-100 tracking-widest">
                    <tr>
                        <th class="px-6 py-4 text-center">Card Number</th>
                        <th class="px-6 py-4 text-center">Date</th>
                        <th class="px-6 py-4 text-center">Time In</th>
                        <th class="px-6 py-4 text-center">Time Out</th>
                        <th class="px-6 py-4 text-center">Data Source</th>
                        <th class="px-6 py-4 text-center">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($records)): foreach ($records as $row): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 text-center text-slate-900 mono font-medium group-hover:text-red-600">
                            <?= htmlspecialchars($row['card_id'] ?? '---') ?>
                        </td>

                        <td class="px-6 py-4 text-center text-slate-600 font-semibold" data-date="<?= $row['date'] ?>">
                            <?= date('M d, Y', strtotime($row['date'])) ?>
                        </td>

                        <td class="px-6 py-4 text-center mono font-bold <?= $row['time_in'] != '-' ? 'text-emerald-600' : 'text-slate-300' ?>">
                            <?= $row['time_in'] ?>
                        </td>

                        <td class="px-6 py-4 text-center mono font-bold <?= $row['time_out'] != '-' ? 'text-orange-600' : 'text-slate-300' ?>">
                            <?= $row['time_out'] ?>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <?php 
                                $isField = ($row['source'] == 'FIELD');
                                $colorClass = $isField ? 'bg-blue-50 text-blue-600 border-blue-100' : 'bg-purple-50 text-purple-600 border-purple-100';
                                $icon = $isField ? 'map-pin' : 'building-2';
                            ?>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border <?= $colorClass ?> shadow-sm">
                                <i data-lucide="<?= $icon ?>" class="w-3 h-3"></i>
                                <span class="text-[9px] font-black uppercase tracking-tighter"><?= $row['source'] ?></span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <span class="bg-green-50 text-green-600 text-[10px] font-black px-2.5 py-1 rounded-full border border-green-100 uppercase">
                                <?= $row['status'] ?? 'RECORDED' ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <i data-lucide="database-zap" class="w-12 h-12 mb-2 text-slate-900"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest italic text-slate-900">Waiting for Data Sync...</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">
                Showing <span class="text-slate-900 font-black"><?= count($records) ?></span> Combined Logs
            </p>
        </div>
    </div>

    <footer class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
        <span>© 2026 Trace Alarm & Security System, Inc. (TASSI)</span>
        <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
        <span class="text-slate-500">Payroll Integration Module</span>
    </footer>
</main>

<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    const searchInput = document.getElementById('attendanceSearch');
    const sourceFilter = document.getElementById('filterSource');
    const dateFilter   = document.getElementById('filterDate');
    const resetBtn     = document.getElementById('resetFilter');
    const tableRows    = document.querySelectorAll('tbody tr:not(.no-records)');

    function filterTable() {
        const searchTerm   = searchInput.value.toLowerCase();
        const selectedSrc  = sourceFilter.value.toLowerCase();
        const selectedDate = dateFilter.value;

        tableRows.forEach(row => {
            const cardNo = row.cells[0].textContent.toLowerCase().trim();
            const date   = row.cells[1].getAttribute('data-date');
            const source = row.cells[4].innerText.toLowerCase().trim();

            const matchesSearch = cardNo.includes(searchTerm) || source.includes(searchTerm);
            const matchesSrc    = selectedSrc === "" || source.includes(selectedSrc);
            const matchesDate   = selectedDate === "" || date === selectedDate;

            if (matchesSearch && matchesSrc && matchesDate) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // Event Listeners
    [searchInput, sourceFilter, dateFilter].forEach(el => {
        el.addEventListener('input', filterTable);
        el.addEventListener('change', filterTable);
    });

    resetBtn.addEventListener('click', () => {
        searchInput.value = "";
        sourceFilter.value = "";
        dateFilter.value = "";
        filterTable();
    });

    // --- EXPORT TO EXCEL ---
    document.getElementById('exportExcel').addEventListener('click', () => {
        const table = document.getElementById("mergedTable");
        const wb = XLSX.utils.table_to_book(table, { sheet: "Attendance Logs" });
        XLSX.writeFile(wb, "TASSI_Merged_Attendance.xlsx");
    });

    // --- EXPORT TO PDF ---
    document.getElementById('exportPDF').addEventListener('click', () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');

        doc.setFontSize(16);
        doc.text("TASSI MERGED ATTENDANCE REPORT", 14, 15);
        doc.setFontSize(9);
        doc.setTextColor(100);
        doc.text("Generated on: " + new Date().toLocaleString(), 14, 22);

        doc.autoTable({
            html: '#mergedTable',
            startY: 30,
            theme: 'grid',
            headStyles: { fillColor: [220, 38, 38], textColor: [255, 255, 255], fontSize: 8 },
            styles: { fontSize: 7 }
        });

        doc.save("TASSI_Attendance_Log.pdf");
    });
</script>