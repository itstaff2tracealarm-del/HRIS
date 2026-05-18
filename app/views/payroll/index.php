<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/left.php'; ?>

<?php if ($_SESSION['role'] === 'admin'): ?>
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
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    </style>
</head>

<main id="main-content" class="flex-1 overflow-y-auto p-6 lg:p-10 sidebar-transition">
    <div class="flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
        <a href="dashboard.php" class="hover:text-red-600 transition flex items-center gap-1.5">
            <i data-lucide="layout-dashboard" class="w-3 h-3"></i> Dashboard
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-900 flex items-center gap-1.5">
            <i data-lucide="wallet" class="w-3 h-3 text-red-600"></i> Payroll List
        </span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Payroll List</h1>
            <p class="text-slate-500 text-sm font-medium italic">TASSI Payroll List</p>
        </div>

        <div class="flex flex-wrap gap-3">
            <div class="flex bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
                <button onclick="exportToExcel()" class="flex items-center gap-2 px-3 py-1.5 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-red-50 transition uppercase tracking-wider">
                    <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-green-600"></i> Excel
                </button>
                <div class="w-px h-4 bg-slate-200 self-center mx-1"></div>
                <button onclick="exportToPDF()" class="flex items-center gap-2 px-3 py-1.5 text-slate-600 rounded-lg text-[10px] font-bold hover:bg-red-50 transition uppercase tracking-wider">
                    <i data-lucide="file-text" class="w-3.5 h-3.5 text-red-600"></i> PDF
                </button>
            </div>
            <a href="payroll.php?a=create" class="flex items-center gap-2 px-6 py-2 bg-slate-900 text-white rounded-xl text-xs font-black hover:bg-red-800 transition shadow-lg uppercase tracking-widest shadow-slate-200">
                <i data-lucide="plus-circle" class="w-4 h-4"></i> Generate
            </a>
        </div>
    </div>

    <div class="bg-white border-2 border-slate-200 rounded-2xl p-4 mb-6 shadow-sm flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full">
            <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
            <input type="text" id="employeeSearch" class="w-full pl-12 pr-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-medium transition-all duration-200 placeholder:text-slate-400
                hover:border-red-600 hover:bg-red-50 focus:outline-none focus:border-red-600 focus:bg-red-50 focus:ring-4 focus:ring-red-600/10 leading-none shadow-sm" placeholder="Search ID, Name, or Dept...">
        </div>

        <div class="flex flex-wrap gap-2 w-full md:w-auto">
            <div class="relative min-w-[160px]">
                <button id="deptBtn" class="w-full flex items-center justify-between bg-white border-2 border-slate-200 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-900 hover:border-red-600 hover:bg-red-50 transition-all shadow-sm">
                    <span id="deptLabel">Departments</span>
                    <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400"></i>
                </button>
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
            </div>

            <div class="relative min-w-[140px]">
             <button id="statusBtn" class="w-full flex items-center justify-between bg-white border-2 border-slate-200 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-900 hover:border-red-600 hover:bg-red-50 transition-all shadow-sm">
                <span id="statusLabel">Status</span>
                <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400"></i>
            </button>
                <div id="customStatusMenu" class="hidden absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden font-black text-[10px] uppercase">
                    <div class="status-option px-4 py-3 bg-red-700 text-white hover:bg-red-800 cursor-pointer" data-value="">ALL STATUS</div>
                    <div class="status-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="PAID">PAID</div>
                    <div class="status-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 cursor-pointer" data-value="PENDING">PENDING</div>
                </div>
            </div>

            <div class="relative min-w-[140px]">
                <button id="monthBtn" class="w-full flex items-center justify-between bg-white border-2 border-slate-200 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-900 hover:border-red-600 hover:bg-red-50 transition-all shadow-sm">
                    <span id="monthLabel">Months</span>
                    <i data-lucide="chevron-down" class="w-3 h-3 text-slate-400"></i>
                </button>
                <div id="customMonthMenu" class="hidden absolute z-[110] w-full mt-2 bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden font-black text-[10px] uppercase">
                    <div class="month-option px-4 py-3 bg-red-700 text-white hover:bg-red-800 cursor-pointer" data-value="">ALL MONTHS</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Jan">JANUARY</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Feb">FEBRUARY</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Mar">MARCH</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Apr">APRIL</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="May">MAY</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Jun">JUNE</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Jul">JULY</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Aug">AUGUST</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Sep">SEPTEMBER</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Oct">OCTOBER</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 cursor-pointer" data-value="Nov">NOVEMBER</div>
                    <div class="month-option px-4 py-3 text-slate-700 hover:bg-red-50 hover:text-red-600 cursor-pointer" data-value="Dec">DECEMBER</div>
                </div>
            </div>

           <button id="resetFilter" onclick="resetFilters()" class="p-3 bg-slate-900 text-white rounded-xl hover:bg-red-800 transition shadow-md shadow-slate-200 active:scale-95" title="Reset/Refresh">
    <i data-lucide="refresh-cw" class="w-4 h-4"></i>
</button>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200 mb-10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gradient-to-r from-red-800 via-red-800 to-red-800 text-white uppercase text-[11px] tracking-widest font-bold shadow-lg">
                      <th class="px-6 py-4 text-center">ID</th>
                      <th class="px-6 py-4">TASSI ID Number</th>
                      <th class="px-6 py-4">Employee Name</th>
                      <th class="px-6 py-4">Department</th>
                      <th class="px-6 py-4 text-center">Period</th>
                      <th class="px-6 py-4 text-center">Status</th>
                      <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody id="payrollTableBody" class="divide-y divide-slate-50">
                    <tr id="noResultsRow" style="display: none;">
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-30">
                                <i data-lucide="database-zap" class="w-12 h-12 mb-4 text-slate-400"></i>
                                <p class="text-slate-400 font-bold uppercase tracking-[0.2em] text-xs">No records found matching your criteria</p>
                            </div>
                        </td>
                    </tr>

                    <?php if (!empty($records)): ?>
                      <?php foreach ($records as $row):
                        $mi = !empty($row['middle_name']) ? substr($row['middle_name'], 0, 1) . '.' : '';
                        $fullName = trim($row['first_name'] . ' ' . $mi . ' ' . $row['last_name']);
                        $period = date('M d', strtotime($row['period_start'])) . ' - ' . date('M d, Y', strtotime($row['period_end']));
                        $status = isset($row['status']) ? strtoupper($row['status']) : 'PENDING';
                        $statusClass = ($status === 'PAID') ? ' text-green-600' :  'text-amber-600';
                      ?>

                      <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 text-center text-slate-900 uppercase group-hover:text-red-600"> <?= $row['id'] ?> </td>
                        <td class="px-6 py-4 text-slate-900 uppercase group-hover:text-red-600"> <?= $row['employee_code'] ?> </td>
                        <td class="px-6 py-4">
                          <p class="font-black text-slate-800 uppercase tracking-tight leading-none text-sm group-hover:text-red-600 transition-colors">
                            <?= htmlspecialchars($fullName) ?>
                          </p>
                        </td>
                        <td class="px-6 py-4">
                          <p class="text-slate-900 uppercase group-hover:text-red-600">
                            <?= isset($row['department']) ? $row['department'] : 'N/A' ?>
                          </p>
                        </td>
                        <td class="px-6 py-4 text-center text-slate-900 uppercase group-hover:text-red-600"> <?= $period ?> </td>
                        <td class="px-6 py-5 text-center">
                            <span id="status-<?= $row['id'] ?>" class="px-4 py-1.5  text-[11px] font-black  uppercase tracking-widest <?= $statusClass ?>">
                                <?= $status ?>
                            </span>
                        </td>
                        <td class="px-8 py-5 text-center">
                       <!-- Siguraduhin na ang parent <tr> o <div> ay may class na "group" -->
<div class="flex gap-2 justify-center opacity-40 group-hover:opacity-100 transition-opacity duration-300 relative z-10">
    
    <?php if ($status === 'PAID'): ?>
        <button type="button" class="p-2.5 bg-slate-200 text-slate-400 rounded-xl cursor-not-allowed shadow-none" title="Already Paid">
            <i data-lucide="send" class="w-4 h-4"></i>
        </button>
    <?php else: ?>
        <!-- Pinalitan ang $row['CardID'] ng $row['card_no'] base sa model mo -->
        <a href="payroll.php?a=create&card_no=<?= urlencode($row['employee_code'] ?? '') ?>&start=<?= $row['period_start'] ?>&end=<?= $row['period_end'] ?>" 
           class="p-2.5 bg-emerald-500 text-white rounded-xl shadow-lg shadow-emerald-100 hover:bg-emerald-600 transition inline-flex items-center justify-center"
           title="Re-generate">
             <i data-lucide="plus-square" class="w-4 h-4"></i>
        </a>

        <button type="button" class="p-2.5 bg-indigo-500 text-white rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-600 transition"
                onclick="sendViberDirect('<?= $row['id'] ?>', '<?= addslashes($fullName) ?>', '<?= $period ?>', '<?= number_format($row['net_salary'], 2) ?>', '<?= isset($row['phone']) ? $row['phone'] : '' ?>')">
             <i data-lucide="send" class="w-4 h-4"></i>
        </button>
    <?php endif; ?>

    <span id="action-payslip-<?= $row['id'] ?>">
        <?php if (!empty($row['payslip_path'])): ?>
            <button type="button" class="p-2.5 bg-sky-500 text-white rounded-xl shadow-lg shadow-sky-100 hover:bg-sky-600 transition btn-view-payslip"
                    data-path="/phphr-main/phphr-main/public/<?= htmlspecialchars($row['payslip_path']) ?>"
                    data-name="<?= htmlspecialchars($fullName) ?>">
                <i data-lucide="image" class="w-4 h-4"></i>
            </button>
        <?php endif; ?>
    </span>
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
              Showing <span id="showingCount" class="text-slate-900 font-black">0</span> of <span id="totalCount" class="text-slate-900 font-black">0</span> Records
            </p>
            <div id="paginationControls" class="flex gap-1.5"></div>
        </div>
    </div>

    <footer class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
        <span>© 2026 Trace Alarm & Security System, Inc. (TASSI)</span>
    </footer>
</main>

<div id="viewPayslipModal" class="fixed inset-0 z-[100] hidden">
    <div class="fixed inset-0 bg-slate-950/90 backdrop-blur-md" onclick="closePayslipModal()"></div>
    <div class="relative flex items-center justify-center min-h-screen p-4 md:p-10">
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-6xl flex flex-col overflow-hidden max-h-[95vh]">
            <div class="px-8 py-5 border-b border-slate-100 flex justify-between items-center bg-white">
                <h5 class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">Payslip Preview: <span id="modalEmployeeName" class="text-sm font-black text-slate-900 uppercase"></span></h5>
                <button onclick="closePayslipModal()" class="p-3 hover:bg-slate-100 rounded-2xl transition group"><i data-lucide="x" class="w-5 h-5 text-slate-400 group-hover:text-red-600"></i></button>
            </div>
            <div class="p-4 md:p-8 bg-slate-50 overflow-y-auto flex-1 flex justify-center custom-scrollbar">
                <img src="" id="payslipFullImage" class="w-full h-auto object-contain shadow-2xl rounded-lg border-8 border-white" alt="Payslip">
            </div>
            <div class="px-8 py-5 border-t border-slate-100 flex justify-end gap-3 bg-white">
                <button onclick="printPayslipImage()" class="flex items-center gap-2 px-6 py-3 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition"><i data-lucide="printer" class="w-4 h-4"></i> Print</button>
                <a id="downloadPayslipBtn" href="" download class="flex items-center gap-2 px-6 py-3 bg-sky-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-sky-600 transition"> <i data-lucide="download" class="w-4 h-4"></i> Download</a>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();
    const rowsPerPage = 15;
    let currentPage = 1;

    let selectedDept = "";
    let selectedStatus = "";
    let selectedMonth = "";

    const tableBody = document.getElementById('payrollTableBody');
    const allRows = Array.from(tableBody.querySelectorAll('tr:not(#noResultsRow)'));

    function setupDropdown(btnId, menuId) {
        const btn = document.getElementById(btnId);
        const menu = document.getElementById(menuId);
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            ['customDeptMenu', 'customStatusMenu', 'customMonthMenu'].forEach(id => {
                if(id !== menuId) document.getElementById(id).classList.add('hidden');
            });
            menu.classList.toggle('hidden');
        });
    }

    setupDropdown('deptBtn', 'customDeptMenu');
    setupDropdown('statusBtn', 'customStatusMenu');
    setupDropdown('monthBtn', 'customMonthMenu');

    function setupOptionClicks(className, labelId, type) {
        document.querySelectorAll('.' + className).forEach(opt => {
            opt.addEventListener('click', () => {
                const val = opt.getAttribute('data-value');
                const text = opt.innerText;
                document.getElementById(labelId).innerText = text;
                
                if(type === 'dept') selectedDept = val;
                if(type === 'status') selectedStatus = val;
                if(type === 'month') selectedMonth = val;
                
                document.getElementById('custom' + type.charAt(0).toUpperCase() + type.slice(1) + 'Menu').classList.add('hidden');
                currentPage = 1;
                applyFilters();
            });
        });
    }

    setupOptionClicks('dept-option', 'deptLabel', 'dept');
    setupOptionClicks('status-option', 'statusLabel', 'status');
    setupOptionClicks('month-option', 'monthLabel', 'month');

    window.addEventListener('click', () => {
        ['customDeptMenu', 'customStatusMenu', 'customMonthMenu'].forEach(id => document.getElementById(id).classList.add('hidden'));
    });

    document.getElementById('employeeSearch').addEventListener('input', () => { currentPage = 1; applyFilters(); });

    function applyFilters() {
        const searchValue = document.getElementById('employeeSearch').value.toLowerCase();
        const deptValue = selectedDept.toLowerCase();
        const statusValue = selectedStatus.toUpperCase();
        const monthValue = selectedMonth;

        const filteredRows = allRows.filter(row => {
            const rowText = row.innerText.toLowerCase();
            const rowDept = row.cells[3] ? row.cells[3].innerText.toLowerCase() : '';
            const rowStatus = row.cells[5] ? row.cells[5].innerText.trim().toUpperCase() : '';           
            const rowPeriod = row.cells[4] ? row.cells[4].innerText : '';

            const textMatch = rowText.includes(searchValue);
            const deptMatch = deptValue === "" || rowDept.includes(deptValue);
            const statusMatch = statusValue === "" || rowStatus.includes(statusValue);
            const monthMatch = monthValue === "" || rowPeriod.includes(monthValue);
            return textMatch && deptMatch && statusMatch && monthMatch;
        });

        const totalFiltered = filteredRows.length;
        const totalPages = Math.ceil(totalFiltered / rowsPerPage);
        if (currentPage > totalPages && totalPages > 0) currentPage = totalPages;

        const start = (currentPage - 1) * rowsPerPage;
        const pageRows = filteredRows.slice(start, start + rowsPerPage);

        allRows.forEach(row => row.style.display = 'none');
        pageRows.forEach(row => row.style.display = '');

        document.getElementById('noResultsRow').style.display = (totalFiltered === 0 && allRows.length > 0) ? '' : 'none';
        document.getElementById('totalCount').innerText = allRows.length;
        document.getElementById('showingCount').innerText = totalFiltered;

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        const wrapper = document.getElementById('paginationControls');
        wrapper.innerHTML = "";
        if (totalPages <= 1) return;

        const createBtn = (content, page, isDisabled = false, isPageNum = false) => {
            const btn = document.createElement('button');
            btn.innerHTML = content;
            btn.className = `w-8 h-8 flex items-center justify-center rounded-lg border transition text-[10px] font-black ${
                isPageNum ? (page === currentPage ? "bg-slate-900 text-white shadow-lg italic" : "bg-white border-slate-200 text-slate-400 hover:bg-slate-50 italic")
                : (isDisabled ? "opacity-30 cursor-not-allowed" : "hover:bg-slate-100 bg-white border-slate-200 text-slate-400")
            }`;
            btn.onclick = () => { if(!isDisabled) { currentPage = page; applyFilters(); } };
            return btn;
        };

        wrapper.appendChild(createBtn('<i data-lucide="chevron-left" class="w-3 h-3"></i>', currentPage - 1, currentPage === 1));
        for (let i = 1; i <= totalPages; i++) wrapper.appendChild(createBtn(i, i, false, true));
        wrapper.appendChild(createBtn('<i data-lucide="chevron-right" class="w-3 h-3"></i>', currentPage + 1, currentPage === totalPages));  
        lucide.createIcons();
    }

    function resetFilters() {
        const btn = document.getElementById('resetFilter');
        btn.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4 animate-spin"></i>';
        window.location.reload();
    }

    function closePayslipModal() { document.getElementById('viewPayslipModal').classList.add('hidden'); }

    function printPayslipImage() {
        const imgSrc = document.getElementById('payslipFullImage').src;
        if (!imgSrc) return;
        const win = window.open('', '_blank');
        win.document.write(`<html><body style="margin:0; display:flex; justify-content:center;"><img src="${imgSrc}" style="max-width:100%;" onload="window.print(); window.close();"></body></html>`);
        win.document.close();
    }

    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.btn-view-payslip');
        if (btn) {
            document.getElementById('modalEmployeeName').innerText = btn.getAttribute('data-name');
            document.getElementById('payslipFullImage').src = btn.getAttribute('data-path');
            document.getElementById('downloadPayslipBtn').href = btn.getAttribute('data-path');
            document.getElementById('viewPayslipModal').classList.remove('hidden');
        }
    });

    function exportToExcel() {
        const data = [];
        data.push(["ID", "TASSI ID Number", "Employee Name", "Department", "Period", "Status"]);
        allRows.forEach(row => {
            const rowData = [
                row.cells[0].innerText.trim(),
                row.cells[1].innerText.trim(),
                row.cells[2].innerText.trim(),
                row.cells[3].innerText.trim(),
                row.cells[4].innerText.trim(),
                row.cells[5].innerText.trim()
            ];
            data.push(rowData);
        });
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.aoa_to_sheet(data);
        XLSX.utils.book_append_sheet(wb, ws, "Payroll List");
        XLSX.writeFile(wb, "TASSI_Payroll_Report.xlsx");
    }

    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4');
        doc.setFontSize(18);
        doc.text("TASSI PAYROLL REPORT", 14, 15);
        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text("Generated on: " + new Date().toLocaleString(), 14, 22);
        const tableData = allRows.map(row => [
            row.cells[0].innerText.trim(),
            row.cells[1].innerText.trim(),
            row.cells[2].innerText.trim(),
            row.cells[3].innerText.trim(),
            row.cells[4].innerText.trim(),
            row.cells[5].innerText.trim()
        ]);
        doc.autoTable({
            startY: 30,
            head: [["ID", "TASSI ID", "EMPLOYEE NAME", "DEPARTMENT", "PERIOD", "STATUS"]],
            body: tableData,
            theme: 'striped',
            headStyles: { fillColor: [153, 27, 27] },
            styles: { fontSize: 8, font: 'helvetica' },
            columnStyles: { 0: { halign: 'center' }, 4: { halign: 'center' }, 5: { halign: 'center' } }
        });
        doc.save("TASSI_Payroll_Report.pdf");
    }

    document.addEventListener('DOMContentLoaded', applyFilters);
</script>
<?php endif; ?>