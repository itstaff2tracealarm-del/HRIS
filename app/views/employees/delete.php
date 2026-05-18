<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/left.php'; ?>

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
            <i data-lucide="archive" class="w-3 h-3 text-red-600"></i> Inactive Records
        </span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Restricted Inactive</h1>
            <p class="text-slate-500 text-sm font-medium italic">TASSI Inactive Employee Database</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <div class="flex bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
                <button onclick="exportToExcel()" class="flex items-center gap-2 px-3 py-1.5 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-red-50 hover:border-red-600 transition-all duration-200 leading-none shadow-sm">
                    <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-green-600"></i> Excel
                </button>
                <div class="w-px h-4 bg-slate-200 self-center mx-1"></div>
                <button onclick="exportToPDF()" class="flex items-center gap-2 px-3 py-1.5 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-red-50 hover:border-red-600 transition-all duration-200 leading-none shadow-sm">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-red-600"></i> PDF
                </button>
            </div>
            <a href="employees.php?a=index" class="flex items-center gap-2 px-6 py-2 bg-slate-900 text-white rounded-xl text-xs font-black hover:bg-red-900 transition shadow-lg uppercase tracking-widest shadow-slate-200">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Active
            </a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-sm flex flex-col md:flex-row gap-4 items-center">
    <div class="relative flex-1 w-full">
        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none"></i>
        
        <input type="text" id="employeeSearch" 
               class="w-full pl-12 pr-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-red-600 focus:ring-4 focus:ring-red-600/10 hover:bg-red-50 hover:border-red-600 transition-all duration-200 placeholder:text-slate-400 leading-none shadow-sm"
               placeholder="Search by ID, Name, or Department...">
    </div> 
        <div class="flex gap-2 w-full md:w-auto">
            <div class="relative w-full md:w-64">
    <div id="customDeptBtn" class="flex items-center justify-between bg-white border-2 border-slate-200 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-900 cursor-pointer hover:bg-red-50 hover:border-red-600 transition-all duration-200 leading-none shadow-sm">
        <span id="deptLabel">ALL DEPARTMENTS</span>
        <i data-lucide="chevron-down" class="w-4 h-4 text-slate-900"></i>
    </div>

    <div id="customDeptMenu" class="hidden absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden font-black text-[10px] uppercase">
        <div class="status-option px-4 py-3 bg-red-700 text-white hover:bg-red-800 cursor-pointer" data-value="">ALL DEPARTMENTS</div>
        
        <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Admin">ADMIN</div>
        <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Finance">FINANCE</div>
        <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Sales">SALES</div>
        <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Technical">TECHNICAL</div>
        <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 cursor-pointer" data-value="IT">IT</div>
    </div>
    
    <input type="hidden" id="filterDept" value="">
</div>
            <button id="resetFilter" class="p-3 bg-slate-900 text-white rounded-xl hover:bg-red-800 transition shadow-md shadow-slate-200" title="Reset Filters">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <!-- <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-10">
        <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/30">
            <h3 class="font-bold text-sm text-slate-700 uppercase tracking-widest flex items-center gap-2 italic">
                <i data-lucide="archive" class="w-4 h-4 text-red-600"></i> Data Management Grid (Inactive)
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase border-b border-slate-100 tracking-widest">
                    <tr> -->
    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200 mb-10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-red-800 via-red-800 to-red-800 text-white uppercase text-[11px] tracking-widest font-bold shadow-lg">
                    <th class="px-6 py-4 text-center">Card Number</th>
                        <th class="px-6 py-4">TASSI ID Number</th>
                        <th class="px-6 py-4">Full Name</th>
                        <th class="px-6 py-4">Department / Designation</th>
                        <th class="px-6 py-4">Date Hired</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="employeeTable" class="divide-y divide-slate-50">
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i data-lucide="database-zap" class="w-12 h-12 mb-4 text-slate-400"></i>
                                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-xs">No records found matching your criteria</p>
                            </div>
                        </td>
                    </tr>

                    <?php if (count($employees) > 0): ?>
                        <?php foreach ($employees as $emp): ?>
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4 text-center text-base text-slate-900 uppercase group-hover:text-red-600"><?= $emp['CardID'] ?></td>
                            <td class="px-6 py-4 text-base text-slate-900 uppercase group-hover:text-red-600"><?= htmlspecialchars($emp['employee_code']) ?></td>
                            <td class="px-6 py-4">
                                <p class="font-black text-slate-800 uppercase tracking-tight leading-none text-sm">
                                    <?= htmlspecialchars(strtoupper($emp['last_name']) . ', ' . strtoupper($emp['first_name']) . ' ' . (!empty($emp['middle_name']) ? strtoupper($emp['middle_name'][0]) . '.' : '')) ?>
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-[10px] font-black text-black-600 uppercase tracking-tighter leading-none mb-1 group-hover:text-red-600 transition-colors"><?= htmlspecialchars($emp['department'] ?? '-') ?></p>
                                <p class="text-[11px] font-medium text-slate-900 group-hover:text-red-600 transition-colors"><?= htmlspecialchars($emp['designation']) ?></p>
                            </td>
                            <!-- <td class="px-6 py-4 text-center text-base text-slate-900 uppercase group-hover:text-red-600"><?= htmlspecialchars($emp['date_of_joining']) ?></td> -->
                            <td class="px-6 py-4 text-base text-slate-900 uppercase group-hover:text-red-600"><?= htmlspecialchars(date('M d, Y', strtotime($emp['date_of_joining']))) ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="text-red-600 text-[12px] font-black uppercase">Inactive</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex justify-center gap-1.5 opacity-40 group-hover:opacity-100 transition-opacity">
                                    <button onclick="openRestoreModal(<?= $emp['id'] ?>, '<?= addslashes($emp['first_name'] . ' ' . $emp['last_name']) ?>')" 
                                            class="p-2 bg-emerald-500 text-white rounded-xl shadow-lg shadow-emerald-100 hover:bg-emerald-600 transition" title="Restore Profile">
                                        <!-- class="p-2 bg-white border border-slate-100 text-slate-400 hover:text-emerald-600 hover:border-emerald-200 rounded-lg transition shadow-sm" -->
                                            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button onclick="openDeleteModal(<?= $emp['id'] ?>, '<?= addslashes($emp['first_name'] . ' ' . $emp['last_name']) ?>')" 
                                            class="p-2 bg-red-500 text-white rounded-xl shadow-lg shadow-red-100 hover:bg-red-600 transition" title="Delete Record">
                                        <!-- class="p-2 bg-white border border-slate-100 text-slate-400 hover:text-red-600 hover:border-red-200 rounded-lg transition shadow-sm" -->
                                            <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Showing <span id="showingCount" class="text-slate-900 font-black">0</span> of <span id="totalCount" class="text-slate-900 font-black">0</span> Inactive Records
            </p>
            <div id="paginationControls" class="flex gap-1.5"></div>
        </div>
    </div>
    <footer class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
        <span>© 2026 Trace Alarm & Security System, Inc. (TASSI)</span>
        <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
        <span class="text-slate-500">Republic of the Philippines</span>
    </footer>    
</main>

<!-- MODAL FOR RESTORE AND DELETE ACTIONS -->
<!-- <div id="restoreModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 transition-opacity bg-slate-950/90 backdrop-blur-md" onclick="closeModal('restoreModal')"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-[2.5rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.4)] sm:max-w-md sm:w-full border border-slate-200">
            <div class="h-2 bg-emerald-500"></div>
            <div class="p-10">
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-emerald-500 shadow-2xl shadow-emerald-100 mb-6 -rotate-3">
                        <i data-lucide="user-plus" class="h-10 w-10 text-white"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tighter leading-none mb-4">
                        Restore <span class="text-emerald-500">Record</span>
                    </h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">
                        Reactivating profile for:
                        <span id="restoreEmployeeName" class="block text-xl text-slate-900 font-black mt-2 tracking-tight uppercase"></span>
                    </p>
                </div>
                <div class="space-y-3">
                    <a id="confirmRestoreBtn" href="#" 
                       class="group relative flex items-center justify-center w-full px-6 py-5 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-[0.3em] overflow-hidden transition-all hover:bg-emerald-600 hover:shadow-2xl hover:shadow-emerald-200">
                        <span class="relative z-10 flex items-center gap-3">
                            Confirm Restore
                            <i data-lucide="check-circle" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                        </span>
                    </a>
                    <button onclick="closeModal('restoreModal')" 
                            class="w-full px-6 py-5 bg-white text-slate-400 text-xs font-black uppercase tracking-[0.3em] rounded-2xl border border-slate-100 hover:bg-slate-50 hover:text-slate-900 transition-all">
                        Cancel Action
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- <div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 transition-opacity bg-red-950/90 backdrop-blur-xl" onclick="closeModal('deleteModal')"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-[2.5rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.6)] sm:max-w-md sm:w-full border-4 border-red-100">
            <div class="flex items-center justify-center gap-4 py-3 bg-red-600 text-white font-black text-[10px] uppercase tracking-[0.4em]">
                <i data-lucide="alert-triangle" class="w-4 h-4"></i> Critical Warning <i data-lucide="alert-triangle" class="w-4 h-4"></i>
            </div>
            <div class="p-10">
                <div class="text-center mb-10">
                    <div class="mx-auto flex items-center justify-center h-24 w-24 rounded-full bg-red-50 mb-8 relative">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-20"></span>
                        <i data-lucide="trash-2" class="h-12 w-12 text-red-600 relative z-10"></i>
                    </div>
                    <h3 class="text-3xl font-black text-red-600 uppercase tracking-tighter leading-none mb-4">Final Deletion</h3>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed px-4">
                        This action <span class="text-red-600 font-black underline decoration-2">cannot be undone</span>. All data for <span id="deleteEmployeeName" class="text-slate-900 font-black"></span> will be erased.
                    </p>
                </div>
                <div class="space-y-3">
                    <a id="confirmDeleteBtn" href="#" 
                       class="flex items-center justify-center gap-3 w-full px-6 py-5 bg-red-600 text-white rounded-2xl text-xs font-black uppercase tracking-[0.3em] hover:bg-red-700 transition-all shadow-xl shadow-red-200">
                        Delete Forever
                    </a>
                    <button onclick="closeModal('deleteModal')" 
                            class="w-full px-6 py-5 bg-slate-100 text-slate-500 text-xs font-black uppercase tracking-[0.3em] rounded-2xl hover:bg-slate-200 hover:text-slate-900 transition-all">
                        Cancel Action
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> -->
<!-- END OF MODALS -->

<div id="restoreModal" class="fixed inset-0 z-[100] hidden overflow-hidden">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-md transition-opacity" onclick="closeModal('restoreModal')"></div>   
    <div class="relative flex items-center justify-center min-h-screen p-4 z-10 pointer-events-none">
        <div class="relative inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-[2.5rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.4)] sm:max-w-md sm:w-full border border-slate-200 pointer-events-auto">
            <div class="h-2 bg-emerald-600"></div>
            
            <div class="p-10 text-center">
                <div class="mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-emerald-600 shadow-2xl shadow-emerald-100 mb-6 -rotate-3 transition-transform hover:rotate-0">
                        <i data-lucide="rotate-ccw" class="h-10 w-10 text-white"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tighter leading-none mb-4">
                        Restore <span class="text-emerald-600">Record</span>
                    </h3>
                    <div class="h-1 w-12 bg-emerald-200 mx-auto rounded-full mb-6"></div>
                    <p class="text-slate-600 text-sm font-medium leading-relaxed">
                        You are about to reactivate the profile for:
                        <span id="restoreEmployeeName" class="block text-xl text-slate-900 font-black mt-2 tracking-tight uppercase"></span>
                    </p>
                </div>
                <div class="space-y-3">
                    <a id="confirmRestoreBtn" href="#" class="group relative flex items-center justify-center w-full px-6 py-5 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-[0.3em] overflow-hidden transition-all hover:bg-emerald-600 hover:shadow-2xl hover:shadow-emerald-200">
                        <span class="relative z-10 flex items-center gap-3">
                            Confirm Restore
                            <i data-lucide="check-circle" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                        </span>
                    </a>
                    <button onclick="closeModal('restoreModal')" class="w-full px-6 py-5 bg-white text-slate-400 text-xs font-black uppercase tracking-[0.3em] rounded-2xl border border-slate-100 hover:bg-slate-50 hover:text-slate-900 transition-all">
                        Cancel Action
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="deleteModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-md" onclick="closeDeleteModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4 z-10 pointer-events-none">
        <div class="relative inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-[2.5rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.4)] sm:max-w-md sm:w-full border border-slate-200 pointer-events-auto">
            <div class="h-2 bg-red-600"></div>

            <div class="p-10 text-center">
                <div class="mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-red-600 shadow-2xl shadow-red-100 mb-6 -rotate-3 transition-transform hover:rotate-0">
                        <i data-lucide="trash-2" class="h-10 w-10 text-white"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tighter leading-none mb-4">
                        Confirm <span class="text-red-600">Deletion</span>
                    </h3>
                    <div class="h-1 w-12 bg-slate-200 mx-auto rounded-full mb-6"></div>
                    <p class="text-slate-600 text-sm font-medium leading-relaxed">
                        You are about to permanently remove:
                        <span id="deleteEmployeeName" class="block text-xl text-slate-900 font-black mt-2 tracking-tight uppercase"></span>
                    </p>
                </div>
                <div class="bg-red-50 rounded-2xl p-5 mb-8 border border-red-100 flex items-start gap-4">
                    <div class="bg-white p-2 rounded-lg shadow-sm">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-red-600"></i>
                    </div>
                    <p class="text-[11px] text-red-600 font-bold uppercase tracking-wide leading-normal">
                        Critical: This action is irreversible. All records will be wiped from the registry.
                    </p>
                </div>
                <div class="space-y-3">
                    <a id="confirmDeleteBtn" href="#" class="group relative flex items-center justify-center w-full px-6 py-5 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-[0.3em] overflow-hidden transition-all hover:bg-red-600 hover:shadow-2xl hover:shadow-red-200">
                        <span class="relative z-10 flex items-center gap-3">
                            Confirm Deletion
                            <i data-lucide="zap" class="w-4 h-4 group-hover:scale-125 transition-transform"></i>
                        </span>
                    </a>
                    <button onclick="closeDeleteModal()" class="w-full px-6 py-5 bg-white text-slate-400 text-xs font-black uppercase tracking-[0.3em] rounded-2xl border border-slate-100 hover:bg-slate-50 hover:text-slate-900 transition-all">
                        Cancel Action
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // --- Configuration ---
    lucide.createIcons();
    const rowsPerPage = 15; // Binalik ko sa 15 gaya ng dati mong script
    let currentPage = 1;

    const filterDept = document.getElementById('filterDept');
    // Note: Sa archive, pwedeng wala tayong filterStatus sa UI, pero handle natin para sa logic consistency
    const filterStatus = document.getElementById('filterStatus'); 
    const searchInput = document.getElementById('employeeSearch');
    const tableBody = document.getElementById('employeeTable');
    const allRows = Array.from(tableBody.querySelectorAll('tr:not(#noResultsRow)'));

    // --- Core Logic: Filtering & Pagination ---
    function applyFilters() {
        const searchValue = searchInput.value.toLowerCase();
        const deptValue = filterDept ? filterDept.value.toLowerCase() : "";
        const statusValue = filterStatus ? filterStatus.value.toLowerCase() : "";

        // 1. Get ALL matches based on filter
        const filteredRows = allRows.filter(row => {
            const rowText = row.innerText.toLowerCase();
            const deptText = row.cells[3].innerText.toLowerCase(); 
            // Index 5 ang status sa archive list natin (Card ID, TASSI ID, Name, Dept, Hired, Status, Actions)
            const statusText = row.cells[5] ? row.cells[5].innerText.toLowerCase() : ""; 

            const matchesSearch = rowText.includes(searchValue);
            const matchesDept = deptValue === "" || deptText.includes(deptValue);
            const matchesStatus = statusValue === "" || statusText.trim() === statusValue;

            return matchesSearch && matchesDept && matchesStatus;
        });

        const totalFiltered = filteredRows.length;
        const totalPages = Math.ceil(totalFiltered / rowsPerPage);
        
        if (currentPage > totalPages) currentPage = 1;

        const start = (currentPage - 1) * rowsPerPage;
        const pageRows = filteredRows.slice(start, start + rowsPerPage);

        // 2. UI Update
        allRows.forEach(row => row.style.display = 'none');
        pageRows.forEach(row => row.style.display = '');

        const noResultsRow = document.getElementById('noResultsRow');
        if (noResultsRow) noResultsRow.style.display = totalFiltered === 0 ? '' : 'none';

        document.getElementById('totalCount').innerText = totalFiltered;
        document.getElementById('showingCount').innerText = pageRows.length;
        
        renderPagination(totalPages);
    }

// Custom Dept Dropdown Logic
const deptBtn = document.getElementById('customDeptBtn');
const deptMenu = document.getElementById('customDeptMenu');
const deptLabel = document.getElementById('deptLabel');
const deptHiddenInput = document.getElementById('filterDept');
const deptOptions = document.querySelectorAll('.dept-option');

// Open/Close Dept Menu
deptBtn.addEventListener('click', (e) => {
    e.stopPropagation();
    // Isara muna yung Status menu kung nakabukas para hindi magpatong
    document.getElementById('customStatusMenu')?.classList.add('hidden');
    deptMenu.classList.toggle('hidden');
});

// Selection Logic
deptOptions.forEach(option => {
    option.addEventListener('click', () => {
        const val = option.getAttribute('data-value');
        const text = option.innerText;

        deptLabel.innerText = text;
        deptHiddenInput.value = val;
        
        deptMenu.classList.add('hidden');
        
        // Tawagin ang filter function mo
        if (typeof applyFilters === 'function') {
            currentPage = 1;
            applyFilters();
        }
    });
});

// Isara kapag nag-click kahit saan sa labas
window.addEventListener('click', () => {
    deptMenu.classList.add('hidden');
});

    function renderPagination(totalPages) {
        const wrapper = document.getElementById('paginationControls');
        wrapper.innerHTML = "";
        if (totalPages <= 1) return;

        // Prev Button
        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '<i data-lucide="chevron-left" class="w-3 h-3"></i>';
        prevBtn.disabled = currentPage === 1;
        prevBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg border transition ${currentPage === 1 ? 'opacity-30 cursor-not-allowed' : 'hover:bg-slate-100'}`;
        prevBtn.onclick = () => { if(currentPage > 1) { currentPage--; applyFilters(); } };
        wrapper.appendChild(prevBtn);

        // Number Buttons
        for (let i = 1; i <= totalPages; i++) {
            const btn = document.createElement('button');
            btn.innerText = i;
            btn.className = `w-8 h-8 rounded-lg text-[10px] font-black transition italic ${i === currentPage ? 'bg-slate-900 text-white shadow-lg' : 'bg-white border border-slate-200 text-slate-400 hover:bg-slate-50'}`;
            btn.onclick = () => { currentPage = i; applyFilters(); };
            wrapper.appendChild(btn);
        }

        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '<i data-lucide="chevron-right" class="w-3 h-3"></i>';
        nextBtn.disabled = currentPage === totalPages;
        nextBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg border transition ${currentPage === totalPages ? 'opacity-30 cursor-not-allowed' : 'hover:bg-slate-100'}`;
        nextBtn.onclick = () => { if(currentPage < totalPages) { currentPage++; applyFilters(); } };
        wrapper.appendChild(nextBtn);
        
        lucide.createIcons(); // Refresh icons para sa prev/next
    }

    // --- Smart Export Logic ---
    function getFilteredDataForExport() {
        const searchValue = searchInput.value.toLowerCase();
        const deptValue = filterDept ? filterDept.value.toLowerCase() : "";
        const statusValue = filterStatus ? filterStatus.value.toLowerCase() : "";

        return allRows.filter(row => {
            const rowText = row.innerText.toLowerCase();
            const deptText = row.cells[3].innerText.toLowerCase(); 
            const statusText = row.cells[5] ? row.cells[5].innerText.toLowerCase() : ""; 

            return rowText.includes(searchValue) && 
                   (deptValue === "" || deptText.includes(deptValue)) &&
                   (statusValue === "" || statusText.trim() === statusValue);
        }).map(row => {
            // Kunin ang text content, linisin ang Newlines sa Department cell
            return [
                row.cells[0].innerText.trim(),
                row.cells[1].innerText.trim(),
                row.cells[2].innerText.trim(),
                row.cells[3].innerText.replace(/\n/g, ' - ').trim(),
                row.cells[4].innerText.trim(),
                row.cells[5].innerText.trim()
            ];
        });
    }

    function exportToExcel() {
        const dataToExport = getFilteredDataForExport();
        const headers = ["Card ID", "TASSI ID", "Full Name", "Dept / Desig", "Date Hired", "Status"];
        
        if (dataToExport.length === 0) {
            alert("Walang data na pwedeng i-export.");
            return;
        }

        const worksheet = XLSX.utils.aoa_to_sheet([headers, ...dataToExport]);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Inactive Personnel");
        XLSX.writeFile(workbook, "TASSI_Inactive_List.xlsx");
    }

    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'pt', 'a4');
        const headers = ["CARD ID", "TASSI ID", "FULL NAME", "DEPT / DESIGNATION", "DATE HIRED", "STATUS"];
        const body = getFilteredDataForExport();

        if (body.length === 0) {
            alert("Walang data na pwedeng i-export.");
            return;
        }

        doc.autoTable({
            head: [headers],
            body: body,
            startY: 50,
            theme: 'striped',
            headStyles: { fillColor: [15, 23, 42] },
            styles: { fontSize: 8 }
        });
        doc.setFontSize(14);
        doc.text("TASSI PORTAL - INACTIVE PERSONNEL REGISTRY", 40, 35);
        doc.save("TASSI_Inactive_Records.pdf");
    }

    // // --- Modals ---
    // function openRestoreModal(id, name) {
    //     document.getElementById('restoreEmployeeName').innerText = name;
    //     document.getElementById('confirmRestoreBtn').href = `employees.php?a=restore&id=${id}`;
    //     document.getElementById('restoreModal').classList.remove('hidden');
    // }

    // function openDeleteModal(id, name) {
    //     document.getElementById('deleteEmployeeName').innerText = name;
    //     document.getElementById('confirmDeleteBtn').href = `employees.php?a=force_delete&id=${id}`;
    //     document.getElementById('deleteModal').classList.remove('hidden');
    // }

    // function closeModal(modalId) {
    //     document.getElementById(modalId).classList.add('hidden');
    // }

// --- TASSI Unified Modal Controller ---
function openRestoreModal(id, name) {
    document.getElementById('restoreEmployeeName').innerText = name;
    document.getElementById('confirmRestoreBtn').href = `employees.php?a=restore&id=${id}`;
    document.getElementById('restoreModal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons(); // Para lumitaw ang icons
}

function openDeleteModal(id, name) {
    document.getElementById('deleteEmployeeName').innerText = name;
    document.getElementById('confirmDeleteBtn').href = `employees.php?a=force_delete&id=${id}`;
    document.getElementById('deleteModal').classList.remove('hidden');
    if (typeof lucide !== 'undefined') lucide.createIcons();
}

// Para sa "X" button at "Abort" buttons mo
function closeRestoreModal() {
    document.getElementById('restoreModal').classList.add('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
}

// Generic function para sa backdrop clicks
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}

    // --- Event Listeners ---
    searchInput.addEventListener('input', () => { currentPage = 1; applyFilters(); });
    if(filterDept) filterDept.addEventListener('change', () => { currentPage = 1; applyFilters(); });
    if(filterStatus) filterStatus.addEventListener('change', () => { currentPage = 1; applyFilters(); });

    document.getElementById('resetFilter').addEventListener('click', () => {
        searchInput.value = "";
        if(filterDept) filterDept.value = "";
        if(filterStatus) filterStatus.value = "";
        currentPage = 1;
        applyFilters();
    });

    // Initialize
    applyFilters();
</script>