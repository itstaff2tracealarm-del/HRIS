<?php 
include __DIR__ . '/../../includes/header.php'; 
include __DIR__ . '/../../includes/left.php';
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
            <i data-lucide="users" class="w-3 h-3 text-red-600"></i> Unified Attendance Logs
        </span>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Attendance Logs</h1>
            <p class="text-slate-500 text-sm font-medium italic">Combined Data: Field (MySQL) & Office (MSSQL)</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <div class="bg-white border-l-4 border-blue-500 p-4 rounded-xl shadow-sm w-32">
                <p class="text-[10px] font-bold text-slate-400 uppercase">Total Logs</p>
                <p class="text-xl font-black text-slate-800"><?= count($records) ?></p>
            </div>
            <div class="bg-white border-l-4 border-emerald-500 p-4 rounded-xl shadow-sm w-32">
                <p class="text-[10px] font-bold text-slate-400 uppercase">Field (SQL)</p>
                <p class="text-xl font-black text-slate-800">
                    <?php echo count(array_filter($records, function($r) { return $r['source'] === 'FIELD'; })); ?>
                </p>
            </div>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" id="logSearch" 
                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-red-500/10 transition placeholder:text-slate-400"
                   placeholder="SEARCH BY CARD NUMBER OR DATE...">
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <select id="filterSource" class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 focus:outline-none cursor-pointer hover:bg-slate-100 transition">
                <option value="">All Sources</option>
                <option value="FIELD">Field (MySQL)</option>
                <option value="OFFICE">Office (MSSQL)</option>
            </select>
            <select id="filterStatus" class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 focus:outline-none cursor-pointer hover:bg-slate-100 transition">
                <option value="">All Records</option>
                <option value="Verified">Verified Only</option>
                <option value="No Record">Missing Time-Out</option>
            </select>
            <button id="resetFilter" class="p-3 bg-slate-900 text-white rounded-xl hover:bg-red-600 transition shadow-md" title="Reset Filters">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200 mb-10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-white uppercase text-[11px] tracking-widest font-bold">
                        <th class="px-6 py-5 text-center">Card Number</th>
                        <th class="px-6 py-5">Employee Name</th>
                        <th class="px-6 py-5 text-center">Date</th>
                        <th class="px-6 py-5 text-center">Time In</th>
                        <th class="px-6 py-5 text-center">Time Out</th>
                        <th class="px-6 py-5 text-center">Source</th>
                        <th class="px-6 py-5 text-center">Status</th>
                    </tr>
                </thead>
                <tbody id="attendanceTableBody" class="divide-y divide-slate-100">
                    <?php if (!empty($records)): foreach ($records as $row): ?>
                    <tr class="hover:bg-slate-50/80 transition-all group log-row">
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center gap-3">
                                <span class="text-base text-slate-900 uppercase group-hover:text-red-600">
                                    <?= htmlspecialchars($row['card_id']) ?>
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-black text-slate-800 uppercase tracking-tight leading-none text-sm">
                               <?= htmlspecialchars($row['full_name']) ?>
                            </p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-base text-slate-900 uppercase group-hover:text-red-600">
                                <?= date('M d, Y', strtotime($row['date'])) ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-100">
                                <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                                <span class="mono font-black text-sm"><?= $row['time_in'] ?></span>
                            </div>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <?php if($row['time_out'] !== '-'): ?>
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-orange-50 text-orange-700 border border-orange-100">
                                    <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                    <span class="mono font-black text-sm"><?= $row['time_out'] ?></span>
                                </div>
                            <?php else: ?>
                                <span class="text-slate-300 font-bold italic text-[10px] uppercase tracking-tighter">No Record</span>
                            <?php endif; ?>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <?php 
                                $isField = ($row['source'] === 'FIELD');
                                $color = $isField ? 'bg-blue-600' : 'bg-purple-600';
                            ?>
                            <span class="<?= $color ?> text-white text-[12px] font-black px-3 py-1 rounded-full shadow-sm">
                                <?= $row['source'] ?>
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="text-emerald-600 text-[12px] font-black px-2.5 py-1 uppercase">Verified</div>
                        </td>
                    </tr>
                    <?php endforeach; endif; ?>
                    
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <i data-lucide="database-zap" class="w-12 h-12 mb-2"></i>
                                <p class="font-black uppercase tracking-widest text-sm">No Attendance Found</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Showing <span id="showingCount" class="text-slate-900 font-black">0</span> of <span id="totalFilteredCount" class="text-slate-900 font-black">0</span> Records
            </p>
            <div id="paginationButtons" class="flex gap-1.5"></div>
        </div>
    </div>

    <footer class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
        <span>© 2026 Trace Alarm & Security System, Inc. (TASSI)</span>
    </footer>
</main>

<script>
    lucide.createIcons();

    const rowsPerPage = 15;
    let currentPage = 1;

    const tableBody = document.getElementById('attendanceTableBody');
    // Kinukuha lahat ng rows MALIBAN sa noResultsRow para hindi siya maapektuhan ng filters
    const allRows = Array.from(tableBody.querySelectorAll('.log-row'));

    function applyFilters() {
        const searchValue = document.getElementById('logSearch').value.toLowerCase();
        const sourceValue = document.getElementById('filterSource').value.toLowerCase();
        const statusValue = document.getElementById('filterStatus').value.toLowerCase();

        const filteredRows = allRows.filter(row => {
            const rowText = row.innerText.toLowerCase();
            const sourceCol = row.cells[5].innerText.toLowerCase();
            const timeOutCol = row.cells[4].innerText.toLowerCase();

            const matchesSearch = rowText.includes(searchValue);
            const matchesSource = sourceValue === "" || sourceCol.includes(sourceValue);
            
            let matchesStatus = true;
            if(statusValue === "no record") {
                matchesStatus = timeOutCol.includes("no record");
            } else if(statusValue === "verified") {
                matchesStatus = !timeOutCol.includes("no record");
            }

            return matchesSearch && matchesSource && matchesStatus;
        });

        const totalFiltered = filteredRows.length;
        const totalPages = Math.ceil(totalFiltered / rowsPerPage);
        
        if (currentPage > totalPages && totalPages > 0) currentPage = 1;
        
        const start = (currentPage - 1) * rowsPerPage;
        const pageRows = filteredRows.slice(start, start + rowsPerPage);

        // UI Updates: Hide/Show Rows
        allRows.forEach(r => r.style.display = 'none');
        pageRows.forEach(r => r.style.display = '');

        // Update Counter text
        document.getElementById('totalFilteredCount').innerText = totalFiltered;
        document.getElementById('showingCount').innerText = pageRows.length;
        
        // Show/Hide "No Results" row
        const noResults = document.getElementById('noResultsRow');
        if(noResults) noResults.style.display = (totalFiltered === 0) ? 'table-row' : 'none';

        renderPagination(totalPages);
        lucide.createIcons();
    }

    function renderPagination(totalPages) {
        // Siguraduhin na ang ID ay tumutugma sa HTML wrapper mo (paginationControls o paginationButtons)
        const wrapper = document.getElementById('paginationButtons') || document.getElementById('paginationControls');
        if (!wrapper) return;
        
        wrapper.innerHTML = "";
        if (totalPages <= 1) return;

        // PREVIOUS BUTTON
        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '<i data-lucide="chevron-left" class="w-3 h-3"></i>';
        prevBtn.disabled = currentPage === 1;
        prevBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg border transition ${
            currentPage === 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-slate-100 bg-white border-slate-200 text-slate-400'
        }`;
        prevBtn.onclick = () => { if(currentPage > 1) { currentPage--; applyFilters(); } };
        wrapper.appendChild(prevBtn);

        // PAGE NUMBERS (Smart range: shows current, one before, and one after)
        for (let i = 1; i <= totalPages; i++) {
            if (i === 1 || i === totalPages || (i >= currentPage - 1 && i <= currentPage + 1)) {
                const btn = document.createElement('button');
                btn.innerText = i;
                btn.className = `w-8 h-8 rounded-lg text-[10px] font-black transition italic ${
                    i === currentPage ? 'bg-slate-900 text-white shadow-lg' : 'bg-white border border-slate-200 text-slate-400 hover:bg-slate-50'
                }`;
                btn.onclick = () => { currentPage = i; applyFilters(); };
                wrapper.appendChild(btn);
            } else if (i === currentPage - 2 || i === currentPage + 2) {
                // Maglagay ng dots (...) kung masyadong malayo ang page number
                const dots = document.createElement('span');
                dots.innerText = "...";
                dots.className = "text-slate-300 px-1 text-[10px]";
                wrapper.appendChild(dots);
            }
        }

        // NEXT BUTTON
        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '<i data-lucide="chevron-right" class="w-3 h-3"></i>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg border transition ${
            currentPage === totalPages ? 'opacity-30 cursor-not-allowed' : 'hover:bg-slate-100 bg-white border-slate-200 text-slate-400'
        }`;
        nextBtn.onclick = () => { if(currentPage < totalPages) { currentPage++; applyFilters(); } };
        wrapper.appendChild(nextBtn);
        
        lucide.createIcons();
    }

    // EVENT LISTENERS
    document.getElementById('logSearch').addEventListener('input', () => { currentPage = 1; applyFilters(); });
    document.getElementById('filterSource').addEventListener('change', () => { currentPage = 1; applyFilters(); });
    document.getElementById('filterStatus').addEventListener('change', () => { currentPage = 1; applyFilters(); });
    document.getElementById('resetFilter').addEventListener('click', () => {
        document.getElementById('logSearch').value = "";
        document.getElementById('filterSource').value = "";
        document.getElementById('filterStatus').value = "";
        currentPage = 1;
        applyFilters();
    });

    // Initial Load
    window.onload = applyFilters;
</script>