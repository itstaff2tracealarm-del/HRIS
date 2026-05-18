<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/left.php'; ?>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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
        <!-- <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="employees.php?a=index" class="hover:text-red-600 transition flex items-center gap-1.5">
            <i data-lucide="users" class="w-3 h-3"></i> Employee List -->
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-900 flex items-center gap-1.5">
            <i data-lucide="fingerprint" class="w-3 h-3 text-blue-600"></i> Falco Biometric
        </span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Biometric Registry</h1>
            <p class="text-slate-500 text-sm font-medium italic">Falco Access Control & Attendance Database</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="employees.php?a=index" class="flex items-center gap-2 px-6 py-2 bg-slate-900 text-white rounded-xl text-xs font-black hover:bg-red-800 transition shadow-lg uppercase tracking-widest shadow-slate-200">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Main List
            </a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
            <input type="text" id="falcoSearch" 
                   class="w-full pl-12 pr-4 py-3 bg-white border-2 border-white-100 rounded-xl text-sm font-medium focus:outline-none focus:border-red-600 focus:ring-4 focus:ring-red-600/10 hover:bg-red-50 hover:border-red-600 transition-all duration-200 placeholder:text-slate-400 leading-none shadow-sm"
                   placeholder="SEARCH BY CARD NO, STAFF ID, OR NAME...">
        </div>
        <button id="resetFilter" class="p-3 bg-slate-900 text-white rounded-xl hover:bg-red-800 transition shadow-md shadow-slate-200" title="Reset Search">
            <i data-lucide="refresh-cw" class="w-4 h-4"></i>
        </button>
    </div>

       <div class="bg-white border border-slate-100 rounded-2xl ">     
    </div>

      <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-10">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <thead class="bg-[#991b1b] text-[11px] font-bold text-white uppercase tracking-widest">
                  <tr>
                        <th class="px-6 py-4 text-center">Card No</th>
                        <th class="px-6 py-4">Staff ID</th>
                        <th class="px-6 py-4">Full Name</th>
                        <th class="px-6 py-4">Department</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <!-- <th class="px-6 py-4 text-right">Actions</th> -->
                    </tr>
                </thead>
                <tbody id="falcoTableBody" class="divide-y divide-slate-50">
                    <?php if (!empty($falcoEmployees)): foreach ($falcoEmployees as $f): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 text-center text-base text-slate-900 uppercase group-hover:text-red-600"><?= $f['CardNo'] ?></td>
                        <td class="px-6 py-4 text-base text-slate-900 uppercase group-hover:text-red-600"><?= htmlspecialchars($f['StaffNo']) ?></td>
                        <td class="px-6 py-4">
                            <p class="font-black text-slate-800 uppercase tracking-tight leading-none text-sm group-hover:text-red-600 transition-colors">
                                <?= htmlspecialchars($f['Name']) ?>
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[12px] font-black text-black-600 uppercase tracking-tighter leading-none mb-1 group-hover:text-red-600 transition-colors"><?= htmlspecialchars($f['Department'] ?? 'UNASSIGNED') ?></p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class=" text-emerald-600 text-[12px] font-black  uppercase">Linked</span>
                        </td>
                        <!-- <td class="px-6 py-4 text-right">
                            <a href="employees.php?a=edit_falco&card=<?= $f['CardNo'] ?>" 
                               class="inline-flex items-center gap-2 px-4 py-1.5 bg-white border border-slate-200 text-slate-600 rounded-lg text-[10px] font-black hover:bg-blue-600 hover:text-white hover:border-blue-600 transition uppercase tracking-widest shadow-sm">
                                <i data-lucide="edit-3" class="w-3 h-3"></i> Edit
                            </a>
                        </td> -->
                    </tr>
                    <?php endforeach; else: ?>
                    <tr id="noDataRow">
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-20 text-slate-400">
                                <i data-lucide="fingerprint" class="w-12 h-12 mb-2"></i>
                                <p class="text-xs font-black uppercase tracking-widest italic">No Biometric Records Found</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="6" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-20 text-slate-400">
                                <i data-lucide="search-x" class="w-12 h-12 mb-2"></i>
                                <p class="text-xs font-black uppercase tracking-widest italic">No Matching Records</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Showing <span id="showingCount" class="text-slate-900 font-black">0</span> of <span id="totalCount" class="text-slate-900 font-black">0</span> Records
            </p>
            <div id="paginationControls" class="flex gap-1.5"></div>
        </div>
        <?php //include __DIR__ . '/../../includes/footer.php'; ?>
    </div>
    <footer class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
        <span>© 2026 Trace Alarm & Security System, Inc. (TASSI)</span>
        <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
        <span class="text-slate-500">Republic of the Philippines</span>
    </footer>    
</main>

<script>
    // Initialize icons
    lucide.createIcons();
    
    // Config
    const rowsPerPage = 15;
    let currentPage = 1;

    const searchInput = document.getElementById('falcoSearch');
    const tableBody = document.getElementById('falcoTableBody');
    const allRows = Array.from(tableBody.querySelectorAll('tr:not(#noDataRow):not(#noResultsRow)'));

    // --- 2. FILTERING LOGIC ---
    function applyFilters() {
        const searchValue = searchInput.value.toLowerCase().trim();

        const filteredRows = allRows.filter(row => {
            return row.innerText.toLowerCase().includes(searchValue);
        });

        const totalFiltered = filteredRows.length;
        const totalPages = Math.ceil(totalFiltered / rowsPerPage) || 1;

        if (currentPage > totalPages) currentPage = 1;

        const start = (currentPage - 1) * rowsPerPage;
        const pageRows = filteredRows.slice(start, start + rowsPerPage);

        allRows.forEach(row => row.style.display = 'none');
        pageRows.forEach(row => row.style.display = '');

        const noResultsRow = document.getElementById('noResultsRow');
        if (noResultsRow) {
            noResultsRow.style.display = (totalFiltered === 0 && allRows.length > 0) ? '' : 'none';
        }

        document.getElementById('totalCount').innerText = totalFiltered;
        document.getElementById('showingCount').innerText = pageRows.length;
        
        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        const wrapper = document.getElementById('paginationControls');
        wrapper.innerHTML = "";
        if (totalPages <= 1) return;

        // Prev
        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '<i data-lucide="chevron-left" class="w-3 h-3"></i>';
        prevBtn.disabled = currentPage === 1;
        prevBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg border transition ${currentPage === 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-slate-100 bg-white border-slate-200 text-slate-400'}`;
        prevBtn.onclick = () => { if(currentPage > 1) { currentPage--; applyFilters(); } };
        wrapper.appendChild(prevBtn);

        // Numbers
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.className = `w-8 h-8 rounded-lg text-[10px] font-black transition italic ${i === currentPage ? 'bg-slate-900 text-white shadow-lg' : 'bg-white border border-slate-200 text-slate-400 hover:bg-slate-50'}`;
            btn.onclick = () => { currentPage = i; applyFilters(); };
            wrapper.appendChild(btn);
        }

        // Next
        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '<i data-lucide="chevron-right" class="w-3 h-3"></i>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg border transition ${currentPage === totalPages ? 'opacity-30 cursor-not-allowed' : 'hover:bg-slate-100 bg-white border-slate-200 text-slate-400'}`;
        nextBtn.onclick = () => { if(currentPage < totalPages) { currentPage++; applyFilters(); } };
        wrapper.appendChild(nextBtn);
        
        lucide.createIcons();
    }

    searchInput.addEventListener('input', () => { currentPage = 1; applyFilters(); });
    document.getElementById('resetFilter').addEventListener('click', () => {
        searchInput.value = "";
        currentPage = 1;
        applyFilters();
    });

    applyFilters();
</script>