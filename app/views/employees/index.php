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
            <i data-lucide="users" class="w-3 h-3 text-red-600"></i> Employee List
        </span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Employee Master Record</h1>
            <p class="text-slate-500 text-sm font-medium italic">TASSI Employee Master Record List</p>
        </div>
        <div class="flex flex-wrap gap-3 "> 
            <div class="flex bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
                <button onclick="exportToExcel()" class="flex items-center gap-2 px-3 py-1.5 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-red-50 hover:border-red-600 transition-all duration-200 leading-none shadow-sm">
                    <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-green-600"></i> Excel
                </button>
                <div class="w-px h-4 bg-slate-200 self-center mx-1"></div>
                <button onclick="exportToPDF()" class="flex items-center gap-2 px-3 py-1.5 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-red-50 hover:border-red-900 transition-all duration-300 leading-none shadow-sm">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-red-600"></i> PDF
                </button>
            </div>
            <a href="employees.php?a=create" class="flex items-center gap-2 px-6 py-2 bg-slate-900 text-white rounded-xl text-xs font-black hover:bg-red-900 transition shadow-lg uppercase tracking-widest shadow-slate-200">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Add New Employee
            </a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" id="employeeSearch" 
                class="w-full pl-12 pr-4 py-3 bg-white border-2 border-slate-100 rounded-xl text-sm font-medium transition-all duration-200 placeholder:text-slate-400 
                    hover:border-red-600 hover:bg-red-50 
                    focus:outline-none focus:border-red-600 focus:bg-red-50 focus:ring-4 focus:ring-red-600/10 
                    leading-none shadow-sm"                  
                    placeholder="Search by ID, Name, or Department...">
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <div id="customDeptBtn" class="flex items-center justify-between bg-white border-2 border-white-600 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-900 cursor-pointer hover:bg-red-50 hover:border-red-600 transition-all duration-200 leading-none shadow-sm">     
                    <span id="deptLabel">ALL DEPARTMENTS</span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-[#991b1b]"></i>
                </div>

                <div id="customDeptMenu" class="hidden absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden font-black text-[10px] uppercase">
                    <div class="dept-option px-4 py-3 bg-red-700 text-white hover:bg-red-800 cursor-pointer" data-value="">ALL DEPARTMENTS</div>       
                <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Admin">ADMIN</div>
                    <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Finance">FINANCE</div>
                    <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Sales">SALES</div>
                    <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Technical">TECHNICAL</div>
                    <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="CIRD">CIRD</div>
                    <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="IT">IT</div>
                    <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="CMS">CMS</div>
                    <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="RDU">RDU</div>
                    <div class="dept-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 cursor-pointer" data-value="Shop">SHOP</div>
                </div>
    
                <input type="hidden" id="filterDept" value="">
            </div>
            <div class="relative w-full md:w-48"> <div id="customStatusBtn" class="flex items-center justify-between bg-white border-2 border-slate-200 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-900 cursor-pointer hover:bg-red-50 hover:border-red-600 transition-all duration-200 leading-none shadow-sm">
                <span id="statusLabel">ALL STATUS</span>
                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-900"></i>
            </div>

            <div id="customStatusMenu" class="hidden absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden font-black text-[10px] uppercase">
                <div class="status-option px-4 py-3 bg-red-700 text-white hover:bg-red-800 cursor-pointer" data-value="">ALL STATUS</div>        
                <div class="status-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Active">ACTIVE</div>
                <div class="status-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 cursor-pointer" data-value="Inactive">INACTIVE</div>
            </div>
    
            <input type="hidden" id="filterStatus" value="">
        </div>
            <button id="resetFilter" class="p-3 bg-slate-900 text-white rounded-xl hover:bg-red-900 transition shadow-md shadow-slate-200" title="Reset Filters">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200 mb-10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <thead class="bg-gradient-to-r from-red-800 via-red-800 to-red-800 text-white uppercase text-[11px] tracking-widest font-bold shadow-lg">
                        <th class="px-6 py-4 text-center">Card Number</th>
                        <th class="px-6 py-4">TASSI ID Number</th>
                        <th class="px-6 py-4">Employee Name</th>
                        <th class="px-6 py-4">Department / Designation</th>
                        <th class="px-6 py-4">Date Hired</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="employeeTable" class="divide-y divide-slate-100">
    <?php if (!empty($employees)): foreach ($employees as $emp): ?>
    <tr class="hover:bg-slate-50/50 transition-colors group">
        <td class="px-6 py-4 text-center text-base text-slate-900 uppercase group-hover:text-red-600 transition-colors">
            <?= $emp['CardID'] ?>
        </td>
        <td class="px-6 py-4 text-base text-slate-900 uppercase group-hover:text-red-600 transition-colors">
            <?= htmlspecialchars($emp['employee_code']) ?>
        </td>
        <td class="px-6 py-4">
            <p class="font-black text-slate-800 uppercase tracking-tight leading-none text-sm">
                <?= htmlspecialchars(strtoupper($emp['last_name']) . ', ' . strtoupper($emp['first_name']) . ' ' . (!empty($emp['middle_name']) ? strtoupper($emp['middle_name'][0]) . '.' : '')) ?>
            </p>
        </td>
        <td class="px-6 py-4">
            <p class="text-[10px] font-black text-black-600 uppercase tracking-tighter leading-none mb-1 group-hover:text-red-600 transition-colors"> 
                <?= htmlspecialchars($emp['department'] ?? '-') ?> 
            </p>
            <p class="text-[11px] font-medium text-slate-900 group-hover:text-red-600 transition-colors">
                <?= htmlspecialchars($emp['designation']) ?>
            </p>
        </td>
        <!-- <td class="px-6 py-4 text-center text-base text-slate-900 uppercase group-hover:text-red-600 transition-colors">
            <?= htmlspecialchars($emp['date_of_joining']) ?>
        </td> -->
        <td class="px-6 py-4 text-base text-slate-900 uppercase group-hover:text-red-600 transition-colors">
            <?php 
                if (!empty($emp['date_of_joining']) && $emp['date_of_joining'] != '0000-00-00') {
                    echo date('M j, Y', strtotime($emp['date_of_joining'])); 
                } else {
                    echo "—";
                }
            ?>
        </td>
        <td class="px-6 py-4 text-center">
            <?php if($emp['status']): ?>
                <span class="text-emerald-600 text-[12px] font-black px-2.5 py-1 uppercase">Active</span>
            <?php else: ?>
                <span class="bg-red-50 text-red-600 text-[12px] font-black px-2.5 py-1 rounded-full border border-red-100 uppercase">Inactive</span>
            <?php endif; ?>
        </td>

        <td class="px-6 py-4 text-center">
            <div class="flex justify-center gap-1.5 opacity-40 group-hover:opacity-100 transition-opacity duration-300">
                <a href="employees.php?a=profile&id=<?= $emp['id'] ?>" 
                class="p-2.5 bg-blue-500 text-white rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-600 transition" title="View Profile">
                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                </a>
                <a href="employees.php?a=edit&id=<?= $emp['id'] ?>" 
                class="p-2.5 bg-emerald-500 text-white rounded-xl shadow-lg shadow-emerald-100 hover:bg-emerald-600 transition" title="Edit Profile">
                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                </a>
                <?php if($emp['status']): ?>
                    <button disabled class="p-2.5 bg-slate-200 text-slate-400 rounded-xl cursor-not-allowed shadow-none" title="Deactivate status first to archive">
                        <i data-lucide="lock" class="w-3.5 h-3.5"></i>
                    </button>
                <?php else: ?>
                    <button onclick="openArchiveModal(<?= $emp['id'] ?>, '<?= addslashes($emp['first_name'] . ' ' . $emp['last_name']) ?>')" 
                            class="p-2.5 bg-red-500 text-white rounded-xl shadow-lg shadow-red-100 hover:bg-red-600 transition" title="Archive Profile">
                        <i data-lucide="archive" class="w-3.5 h-3.5"></i>
                    </button>
                <?php endif; ?>
            </div>
            <div class="flex justify-center gap-1.5 opacity-40 group-hover:opacity-100 transition-opacity duration-300">
        <!-- BAGONG BUTTON: Para sa Attendance/DTR View -->
       <a href="../public/mergeAttendance.php?a=view_attendance&card_id=<?= $emp['CardID'] ?>"
           class="p-2.5 bg-violet-600 text-white rounded-xl shadow-lg shadow-violet-100 hover:bg-violet-700 transition" title="View Attendance/DTR">
            <i data-lucide="calendar-days" class="w-3.5 h-3.5"></i>
        </a>

        <a href="employees.php?a=profile&id=<?= $emp['id'] ?>" 
           class="p-2.5 bg-blue-500 text-white rounded-xl shadow-lg shadow-blue-100 hover:bg-blue-600 transition" title="View Profile">
            <i data-lucide="eye" class="w-3.5 h-3.5"></i>
        </a>
        
        <!-- ... (retain other buttons like edit and archive) ... -->
    </div>
        </td>
    </tr>
    <?php endforeach; endif; ?>
</tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                Showing <span id="showingCount" class="text-slate-900 font-black">0</span> of <span id="totalCount" class="text-slate-900 font-black">0</span> Records
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

<!-- ARCHIVE MODAL -->
<div id="archiveModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-md transition-opacity" onclick="closeArchiveModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4 z-10 pointer-events-none">
        <div class="relative inline-block overflow-hidden text-left align-middle transition-all transform bg-white rounded-[2.5rem] shadow-[0_32px_64px_-12px_rgba(0,0,0,0.4)] sm:max-w-md sm:w-full border border-slate-200 pointer-events-auto">
            <div class="h-2 bg-red-600"></div>
            <div class="p-10">
                <div class="text-center mb-10">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-red-600 shadow-2xl shadow-red-200 mb-6 -rotate-3 transition-transform hover:rotate-0">
                        <i data-lucide="shield-alert" class="h-10 w-10 text-white"></i>
                    </div>
                    <h3 class="text-3xl font-black text-slate-900 uppercase tracking-tighter leading-none mb-4">
                        Confirm <span class="text-red-600">Archiving</span>
                    </h3>
                    <div class="h-1 w-12 bg-slate-200 mx-auto rounded-full mb-6"></div>
                    <p class="text-slate-500 text-sm font-medium leading-relaxed">
                        You are initiating a permanent status change for:
                        <span id="archiveEmployeeName" class="block text-xl text-slate-900 font-black mt-2 tracking-tight uppercase"></span>
                    </p>
                </div>
                <div class="bg-slate-50 rounded-2xl p-5 mb-8 border border-slate-100 flex items-start gap-4">
                    <div class="bg-white p-2 rounded-lg shadow-sm">
                        <i data-lucide="info" class="w-4 h-4 text-red-600"></i>
                    </div>
                    <p class="text-[11px] text-slate-400 font-bold uppercase tracking-wide leading-normal">
                        Note: Archived records are hidden from active lists but remain in the database for compliance and history.
                    </p>
                </div>
                <div class="space-y-3">
                    <a id="confirmArchiveBtn" href="#" class="group relative flex items-center justify-center w-full px-6 py-5 bg-slate-900 text-white rounded-2xl text-xs font-black uppercase tracking-[0.3em] overflow-hidden transition-all hover:bg-red-600 hover:shadow-2xl hover:shadow-red-200">
                        <span class="relative z-10 flex items-center gap-3">
                            Authorize Move
                            <i data-lucide="arrow-right" class="w-4 h-4 group-hover:translate-x-1 transition-transform"></i>
                        </span>
                    </a>
                    <button onclick="closeArchiveModal()" class="w-full px-6 py-5 bg-white text-slate-400 text-xs font-black uppercase tracking-[0.3em] rounded-2xl border border-slate-100 hover:bg-slate-50 hover:text-slate-900 transition-all">
                        Cancel Request
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: ARCHIVE MODAL -->

<script>
    lucide.createIcons();

    const rowsPerPage = 15;
    let currentPage = 1;

    const tableBody = document.getElementById('employeeTable');
    // Kinukuha lahat ng rows MALIBAN sa noResultsRow para hindi siya maapektuhan ng filters
    const allRows = Array.from(tableBody.querySelectorAll('tr:not(#noResultsRow)'));

    function applyFilters() {
        const searchValue = document.getElementById('employeeSearch').value.toLowerCase();
        const deptValue = document.getElementById('filterDept').value.toLowerCase();
        const statusValue = document.getElementById('filterStatus').value.toLowerCase();

        const filteredRows = allRows.filter(row => {
            const text = row.innerText.toLowerCase();
            const deptText = row.cells[3].innerText.toLowerCase();
            const statusText = row.cells[5].innerText.toLowerCase();

            return text.includes(searchValue) && 
                   (deptValue === "" || deptText.includes(deptValue)) &&
                   (statusValue === "" || statusText.trim().toLowerCase() === statusValue);
        });

        const totalFiltered = filteredRows.length;
        const totalPages = Math.ceil(totalFiltered / rowsPerPage);
        
        if (currentPage > totalPages && totalPages > 0) currentPage = 1;
        
        const start = (currentPage - 1) * rowsPerPage;
        const pageRows = filteredRows.slice(start, start + rowsPerPage);

        // Itago lahat ng data rows
        allRows.forEach(r => r.style.display = 'none');
        // Ipakita lang yung para sa current page
        pageRows.forEach(r => r.style.display = '');

        // Update counts
        document.getElementById('totalCount').innerText = totalFiltered;
        document.getElementById('showingCount').innerText = pageRows.length;
        
        // Logic para sa "No Records Found" row
        const noResults = document.getElementById('noResultsRow');
        if (noResults) {
            noResults.style.display = (totalFiltered === 0) ? 'table-row' : 'none';
        }

        renderPagination(totalPages);
        lucide.createIcons(); // Re-render icons for the "No Records Found" if it shows
    }

    // Custom Dropdown Logic
    const deptBtn = document.getElementById('customDeptBtn');
    const deptMenu = document.getElementById('customDeptMenu');
    const deptLabel = document.getElementById('deptLabel');
    const deptHiddenInput = document.getElementById('filterDept');
    const deptOptions = document.querySelectorAll('.dept-option');

    // Buksan o isara yung menu
    deptBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        deptMenu.classList.toggle('hidden');
    });

    // Pag may pinili sa listahan
    deptOptions.forEach(option => {
        option.addEventListener('click', () => {
            const val = option.getAttribute('data-value');
            const text = option.innerText;

            deptLabel.innerText = text; // Update label sa button
            deptHiddenInput.value = val; // Update yung hidden input para sa filter logic
            
            deptMenu.classList.add('hidden'); // Isara ang menu
            
            // Refresh filter
            currentPage = 1;
            if (typeof applyFilters === 'function') {
                applyFilters();
            }
        });
    });

    // Custom Status Dropdown Logic
    const statusBtn = document.getElementById('customStatusBtn');
    const statusMenu = document.getElementById('customStatusMenu');
    const statusLabel = document.getElementById('statusLabel');
    const statusHiddenInput = document.getElementById('filterStatus');
    const statusOptions = document.querySelectorAll('.status-option');

    // Open/Close Status Menu
    statusBtn.addEventListener('click', (e) => {
        e.stopPropagation();
        // Isara muna yung Department menu kung nakabukas
        document.getElementById('customDeptMenu')?.classList.add('hidden');
        statusMenu.classList.toggle('hidden');
    });

    // Selection Logic
    statusOptions.forEach(option => {
        option.addEventListener('click', () => {
            const val = option.getAttribute('data-value');
            const text = option.innerText;

            statusLabel.innerText = text;
            statusHiddenInput.value = val;
            
            statusMenu.classList.add('hidden');
            
            // Tawagin ang filter function mo
            currentPage = 1;
            if (typeof applyFilters === 'function') {
                applyFilters();
            }
        });
    });

    // Isara kapag nag-click sa labas
    window.addEventListener('click', () => {
        statusMenu.classList.add('hidden');
    });
    // Isara yung dropdown pag nag-click sa labas ng button o menu
    window.addEventListener('click', (e) => {
        if (!deptBtn.contains(e.target)) {
            deptMenu.classList.add('hidden');
        }
    });

    // Siguraduhin na ma-update ang label pag nag-reset
    document.getElementById('resetFilter')?.addEventListener('click', () => {
        deptLabel.innerText = "ALL DEPARTMENTS";
        deptHiddenInput.value = "";
        // Ang applyFilters ay matatawag na ng main reset script mo
    });
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

    function openArchiveModal(id, name) {
        document.getElementById('archiveEmployeeName').innerText = name;
        document.getElementById('confirmArchiveBtn').href = `employees.php?a=delete&id=${id}`;
        document.getElementById('archiveModal').classList.remove('hidden');
    }

    function closeArchiveModal() {
        document.getElementById('archiveModal').classList.add('hidden');
    }

    function exportToExcel() {
        const headers = ["Card ID", "TASSI ID", "Name", "Department", "Designation", "Hired Date", "Status"];
        const visibleRows = allRows.filter(r => r.style.display !== 'none');
        const data = visibleRows.map(row => [
            row.cells[0].innerText.trim(),
            row.cells[1].innerText.trim(),
            row.cells[2].innerText.trim(),
            row.cells[3].innerText.split('\n')[0].trim(),
            row.cells[3].innerText.split('\n')[1]?.trim() || '',
            row.cells[4].innerText.trim(),
            row.cells[5].innerText.trim()
        ]);
        const worksheet = XLSX.utils.aoa_to_sheet([headers, ...data]);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Personnel");
        XLSX.writeFile(workbook, "TASSI_Employee_Registry.xlsx");
    }

    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'pt', 'a4');
        const visibleRows = allRows.filter(r => r.style.display !== 'none');
        const data = visibleRows.map(row => [
            row.cells[0].innerText.trim(),
            row.cells[1].innerText.trim(),
            row.cells[2].innerText.trim(),
            row.cells[3].innerText.replace('\n', ' - '),
            row.cells[4].innerText.trim(),
            row.cells[5].innerText.trim()
        ]);
        doc.autoTable({
            head: [["CARD ID", "TASSI ID", "NAME", "DEPT/DESIG", "HIRED", "STATUS"]],
            body: data,
            styles: { fontSize: 8, font: 'helvetica' },
            headStyles: { fillColor: [15, 23, 42] }
        });
        doc.save("TASSI_Personnel_List.pdf");
    }

    document.getElementById('employeeSearch').addEventListener('input', () => { currentPage = 1; applyFilters(); });
    document.getElementById('filterDept').addEventListener('change', () => { currentPage = 1; applyFilters(); });
    document.getElementById('filterStatus').addEventListener('change', () => { currentPage = 1; applyFilters(); });
    document.getElementById('resetFilter').addEventListener('click', () => {
        document.getElementById('employeeSearch').value = "";
        document.getElementById('filterDept').value = "";
        document.getElementById('filterStatus').value = "";
        currentPage = 1;
        applyFilters();
    });

    // Initial load
    applyFilters();
</script>