<?php 
// Kunin ang header
include __DIR__ . '/../../includes/header.php'; 
include __DIR__ . '/../../includes/left.php';

// Dummy data para sa preview (Huwag kalimutan palitan ng iyong actual $records query)
$records = $records ?? []; 
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

<!-- <style>
    /* Reset & Base Styles */
    * { box-sizing: border-box; }
    body { 
        background-color: #f8fafc; 
        margin: 0; 
        padding: 0; 
        font-family: 'Inter', sans-serif;
        overflow-x: hidden;
    }
    
    /* Main Layout */
    .page-layout {
        display: flex;
        width: 100%;
        min-height: 100vh;
    }

    /* Sidebar Wrapper (Siguraduhing sapat ang width nito) */
    .sidebar-container {
        width: 260px;
        flex-shrink: 0;
        background: white;
        border-right: 1px solid #e2e8f0;
    }

    /* Main Content Area */
    .main-wrapper {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        min-width: 0; /* Importante para sa responsive table */
    }

    .content-area {
        padding: 30px 40px;
        flex-grow: 1;
    }

    /* Breadcrumb */
    .breadcrumb-ui { 
        font-size: 11px;
        font-weight: 800;
        color: #94a3b8;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 25px;
    }
    .breadcrumb-ui span { color: #0f172a; }

    /* Header Section */
    .registry-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 30px;
    }
    .registry-title { 
        color: #0f172a; 
        font-weight: 800; 
        font-size: 26px; 
        margin: 0; 
    }
    .registry-subtitle { 
        color: #64748b; 
        font-style: italic; 
        font-size: 13px; 
        margin: 5px 0 0 0; 
    }

    /* Buttons */
    .btn-group-actions { display: flex; gap: 8px; }
    .btn-custom-outline {
        border: 1px solid #e2e8f0;
        background: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #475569;
    }
    .btn-add-now {
        background-color: #0f172a;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    /* Search & Filters */
    .filter-bar {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
    }
    .search-wrapper {
        flex-grow: 1;
        background: #f1f5f9;
        border-radius: 10px;
        padding: 5px 20px;
        display: flex;
        align-items: center;
    }
    .search-wrapper i { color: #94a3b8; margin-right: 12px; }
    .search-input {
        border: none !important;
        background: transparent !important;
        box-shadow: none !important;
        font-size: 13px;
        font-weight: 600;
        width: 100%;
        color: #475569;
    }
    .filter-select {
        background: #f1f5f9;
        border: none;
        border-radius: 10px;
        font-size: 11px;
        font-weight: 800;
        padding: 12px 15px;
        width: 180px;
        text-transform: uppercase;
        color: #475569;
    }

    /* Data Grid Card */
    .card-ui { 
        background: white; 
        border-radius: 12px; 
        border: 1px solid #e2e8f0;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.02);
        overflow: hidden;
    }
    .grid-label {
        padding: 15px 25px;
        font-size: 12px;
        font-weight: 800;
        font-style: italic;
        color: #1e293b;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Table */
    .table thead th {
        background: white;
        font-size: 10px;
        font-weight: 700;
        color: #94a3b8;
        text-transform: uppercase;
        padding: 15px 25px;
        border-bottom: 1px solid #f1f5f9;
    }
    .table tbody td {
        padding: 18px 25px;
        font-size: 13px;
        color: #334155;
        border-bottom: 1px solid #f8fafc;
        vertical-align: middle;
    }

    /* Custom Row Styles */
    .emp-id { color: #64748b; font-family: monospace; }
    .emp-name { font-weight: 800; color: #0f172a; }
    .dept-main { color: #ef4444; font-weight: 800; font-size: 11px; display: block; }
    .designation { font-style: italic; font-size: 12px; color: #64748b; }
    .status-active {
        background: #dcfce7;
        color: #166534;
        font-size: 10px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 6px;
    }

    /* Footer */
    .footer-tassi {
        background: #f8fafc;
        padding: 20px 40px;
        border-top: 1px solid #e2e8f0;
        color: #64748b;
        font-size: 12px;
        text-align: right;
        font-weight: 600;
    }
    .footer-tassi span { color: #2563eb; }
</style>

<div class="page-layout">

    <div class="main-wrapper">
        <main class="content-area">
            <div class="breadcrumb-ui">
                DASHBOARD &nbsp; <i class="bi bi-chevron-right" style="font-size: 9px;"></i> &nbsp; <span>EMPLOYEE LIST</span>
            </div>

            <div class="registry-header">
                <div>
                    <h1 class="registry-title">PERSONNEL REGISTRY</h1>
                    <p class="registry-subtitle">TASSI Operational Real-time Database</p>
                </div>
                <div class="btn-group-actions">
                    <button class="btn-custom-outline">Excel</button>
                    <button class="btn-custom-outline">PDF</button>
                    <button class="btn-add-now">Add New Employee</button>
                </div>
            </div>

            <div class="filter-bar">
                <div class="search-wrapper">
                    <i class="bi bi-search"></i>
                    <input type="text" id="employeeSearch" class="search-input" placeholder="SEARCH BY ID, NAME, OR DEPARTMENT...">
                </div>
                <select class="form-select filter-select">
                    <option>All Departments</option>
                </select>
                <select class="form-select filter-select">
                    <option>All Status</option>
                </select>
            </div>

            <div class="card-ui">
                <div class="grid-label">DATA MANAGEMENT GRID</div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Card ID</th>
                                <th>Tassi ID</th>
                                <th>Full Name</th>
                                <th>Department / Designation</th>
                                <th>Date Hired</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="employeeTable">
                            <?php if(!empty($records)): ?>
                                <?php foreach ($records as $row): ?>
                                <tr>
                                    <td class="emp-id"><?= $row['card_id'] ?? '0000000000' ?></td>
                                    <td class="fw-bold"><?= $row['employee_code'] ?? '2026-000' ?></td>
                                    <td class="emp-name"><?= strtoupper($row['first_name'] . ' ' . $row['last_name']) ?></td>
                                    <td>
                                        <span class="dept-main"><?= strtoupper($row['department'] ?? 'TECHNICAL') ?></span>
                                        <span class="designation"><?= $row['designation'] ?? 'Staff' ?></span>
                                    </td>
                                    <td><?= $row['date_hired'] ?? '2026-03-25' ?></td>
                                    <td><span class="status-active">ACTIVE</span></td>
                                    <td class="text-center">
                                        <i class="bi bi-eye text-muted mx-1"></i>
                                        <i class="bi bi-pencil text-muted mx-1"></i>
                                        <i class="bi bi-trash text-muted mx-1"></i>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <?php for($i=0; $i<5; $i++): ?>
                                <tr>
                                    <td class="emp-id">0000000000</td>
                                    <td class="fw-bold">2026-03-XXX</td>
                                    <td class="emp-name">NO DATA LOADED</td>
                                    <td>
                                        <span class="dept-main">TECHNICAL</span>
                                        <span class="designation">Staff</span>
                                    </td>
                                    <td>2026-03-25</td>
                                    <td><span class="status-active">ACTIVE</span></td>
                                    <td class="text-center">---</td>
                                </tr>
                                <?php endfor; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <div class="p-4 border-top" style="font-size: 11px; font-weight: 800; color: #94a3b8;">
                    SHOWING 0 OF 0 RESULTS
                </div>
            </div>
        </main>

        <footer class="footer-tassi">
            © 2026 Trace Alarm & Security System, Inc. (TASSI) • <span>Republic of the Philippines</span>
        </footer>
    </div>
</div> -->

<?php //include __DIR__ . '/../../includes/footer.php'; ?>

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
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Office Attendance</h1>
            <p class="text-slate-500 text-sm font-medium italic">TASSI Operational Real-time Database</p>
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
            <input type="text" id="employeeSearch" 
                   class="w-full pl-12 pr-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-500/10 transition placeholder:text-slate-400"
                   placeholder="SEARCH BY ID, NAME, OR DEPARTMENT...">
        </div>
        <div class="flex gap-2 w-full md:w-auto">
            <input type="date" id="filterDate" class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 focus:outline-none cursor-pointer hover:bg-slate-100 transition">
            <select id="filterDept" class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 focus:outline-none cursor-pointer hover:bg-slate-100 transition leading-none">
                <option value="">All Departments</option>
                <option value="Admin">Admin</option>
                <option value="Finance">Finance</option>
                <option value="Sales">Sales</option>
                <option value="Technical">Technical</option>
                <option value="CIRD">CIRD</option>
                <option value="IT">IT</option>
                <option value="CMS">CMS</option>
                <option value="RDU">RDU</option>
                <option value="Shop">Shop</option>
            </select>
            <select id="filterStatus" class="bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-[10px] font-black uppercase tracking-widest text-slate-600 focus:outline-none cursor-pointer hover:bg-slate-100 transition leading-none">
                <option value="">All Status</option>
                <option value="PRESENT">Present</option>
                <option value="ABSENT">Absent</option>
                <option value="LATE">Late</option>
                <option value="HALF DAY">Half Day</option>
            </select>
            <button id="resetFilter" class="p-3 bg-slate-900 text-white rounded-xl hover:bg-red-600 transition shadow-md shadow-slate-200" title="Reset Filters">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-10">
        <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/30">
            <h3 class="font-bold text-sm text-slate-700 uppercase tracking-widest flex items-center gap-2 italic">
                <i data-lucide="table" class="w-4 h-4 text-red-600"></i> Office Attendance Logs
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <thead class="bg-slate-50/50 text-[10px] font-bold text-slate-400 uppercase border-b border-slate-100 tracking-widest">
                    <tr>
                        <th class="px-6 py-4 text-center">Card NO</th>
                        <th class="px-6 py-4">Tassi ID</th>
                        <th class="px-6 py-4">Full Name</th>
                        <th class="px-6 py-4">Department / Designation</th>
                        <th class="px-6 py-4 text-center">Attendance Date</th>
                        <th class="px-6 py-4 text-center">Time In</th>
                        <th class="px-6 py-4 text-center">Time Out</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <!-- <th class="px-6 py-4 text-right">Actions</th> -->
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if (!empty($records)): foreach ($records as $row): ?>
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600">
                            <?= $row['card_id'] ?? '0000000000' ?>
                        </td>
                        <td class="px-6 py-4 text-slate-900 mono text-s group-hover:text-red-600">
                            <?= $row['employee_code'] ?? '2026-000' ?>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-black text-slate-800 uppercase tracking-tight leading-none text-sm">
                                <?= strtoupper($row['first_name'] . ' ' . $row['last_name']) ?>
                            </p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-[10px] font-black text-red-600 uppercase tracking-tighter leading-none mb-1"> <?= strtoupper($row['department'] ?? 'TECHNICAL') ?> </p>
                            <p class="text-[11px] font-medium text-slate-900 italic leading-none"> <?= $row['designation'] ?? 'Staff' ?> </p>
                        </td>
                        <!-- <td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600">
                            <?= $row['attendance_date'] ?? '-' ?>
                        </td> -->
<td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600" 
    data-date="<?= $row['attendance_date'] ?? '' ?>"> 
    <?= !empty($row['attendance_date']) ? date('M j, Y', strtotime($row['attendance_date'])) : '-' ?>
</td>
                        <td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600">
                            <?= $row['check_in'] ?? '-' ?>
                        </td>
                        <td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600">
                            <?= $row['check_out'] ?? '-' ?>
                        </td>
                        <!-- <td class="px-6 py-4 text-center">
                            <?= $row['status'] ?? '-' ?>
                            <span class="bg-green-50 text-green-600 text-[10px] font-black px-2.5 py-1 rounded-full border border-green-100 uppercase tracking-tighter">ACTIVE</span>
                        </td> -->
<td class="px-6 py-4 text-center">
    <?php
        $status = strtolower($row['status'] ?? '');

        // Configuration para sa kulay at label ng bawat status
        switch ($status) {
            case 'present':
                $bg = 'bg-green-50';
                $text = 'text-green-600';
                $border = 'border-green-100';
                $icon = 'check-circle';
                break;
            case 'absent':
                $bg = 'bg-red-50';
                $text = 'text-red-600';
                $border = 'border-red-100';
                $icon = 'x-circle';
                break;
            case 'late':
                $bg = 'bg-orange-50';
                $text = 'text-orange-600';
                $border = 'border-orange-100';
                $icon = 'clock';
                break;
            case 'half_day':
                $bg = 'bg-blue-50';
                $text = 'text-blue-600';
                $border = 'border-blue-100';
                $icon = 'pie-chart';
                break;
            default:
                $bg = 'bg-slate-50';
                $text = 'text-slate-400';
                $border = 'border-slate-200';
                $icon = 'minus-circle';
                break;
        }
    ?>

    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border <?= $bg ?> <?= $text ?> <?= $border ?> shadow-sm">
        <i data-lucide="<?= $icon ?>" class="w-3 h-3"></i>
        <span class="text-[10px] font-black uppercase tracking-tighter"> <?= str_replace('_', ' ', $status ?: 'NO DATA') ?> </span>
    </div>
</td>
                        <!-- <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-1.5 opacity-40 group-hover:opacity-100 transition-opacity">
                                <button class="p-2 bg-white border border-slate-100 text-slate-400 hover:text-blue-600 hover:border-blue-200 rounded-lg transition shadow-sm">
                                    <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                </button>
                                <button class="p-2 bg-white border border-slate-100 text-slate-400 hover:text-emerald-600 hover:border-emerald-200 rounded-lg transition shadow-sm">
                                    <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                                </button>
                                <button class="p-2 bg-white border border-slate-100 text-slate-400 hover:text-red-600 hover:border-red-200 rounded-lg transition shadow-sm">
                                    <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                </button>
                            </div>
                        </td> -->
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        
                        <td colspan="7" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <i data-lucide="database-zap" class="w-12 h-12 mb-2 text-slate-900"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest italic text-slate-900">No Intelligence Records Found</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">
                Showing <span class="text-slate-900 font-black"><?= count($records ?? []) ?></span> Operational Records
            </p>
        </div>
    </div>

    <footer class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
        <span>© 2026 Trace Alarm & Security System, Inc. (TASSI)</span>
        <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
        <span class="text-slate-500">Republic of the Philippines</span>
    </footer>
</main>

<script>
    // Initialize Lucide Icons
    lucide.createIcons();

    const searchInput = document.getElementById('employeeSearch');
    const deptFilter   = document.getElementById('filterDept');
    const statusFilter = document.getElementById('filterStatus');
    const dateFilter   = document.getElementById('filterDate');
    const resetBtn     = document.getElementById('resetFilter');
    const tableRows    = document.querySelectorAll('tbody tr:not(.no-records)');

    function filterTable() {
        const searchTerm   = searchInput.value.toLowerCase();
        const selectedDept = deptFilter.value.toLowerCase();
        const selectedStat = statusFilter.value.toLowerCase();
        const selectedDate = dateFilter.value; // Format: YYYY-MM-DD

        tableRows.forEach(row => {
            // Data Extraction - Siguraduhin na tama ang cell index (0, 1, 2...)
            const cardNo   = row.cells[0].textContent.toLowerCase().trim();
            const tassiId  = row.cells[1].textContent.toLowerCase().trim();
            const fullName = row.cells[2].textContent.toLowerCase().trim();
            const dept     = row.cells[3].innerText.toLowerCase().trim();
            
            // Date Extraction mula sa data-date attribute na ginawa natin sa PHP
            const rowDate  = row.cells[4].getAttribute('data-date'); 
            
            // Status Extraction mula sa span badge
            const statusBadge = row.cells[7].querySelector('span');
            const statusText  = statusBadge ? statusBadge.textContent.toLowerCase().trim() : "";

            // Logic: Search Bar (Card No, Tassi ID, Full Name)
            const matchesSearch = cardNo.includes(searchTerm) || 
                                  tassiId.includes(searchTerm) || 
                                  fullName.includes(searchTerm);

            // Logic: Dropdown Filters
            const matchesDept = selectedDept === "" || dept.includes(selectedDept);
            const matchesStat = selectedStat === "" || statusText === selectedStat;
            const matchesDate = selectedDate === "" || rowDate === selectedDate;

            // Final Render: Dapat lahat ng condition ay TRUE
            if (matchesSearch && matchesDept && matchesStat && matchesDate) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // Event Listeners
    [searchInput, deptFilter, statusFilter, dateFilter].forEach(el => {
        el.addEventListener(el.tagName === 'INPUT' && el.type === 'text' ? 'input' : 'change', filterTable);
    });

    resetBtn.addEventListener('click', () => {
        searchInput.value = "";
        deptFilter.value = "";
        statusFilter.value = "";
        dateFilter.value = "";
        filterTable();
    });

    // --- EXCEL EXPORT (using SheetJS) ---
    document.getElementById('exportExcel').addEventListener('click', () => {
        const table = document.querySelector("table");
        const wb = XLSX.utils.table_to_book(table, { sheet: "Attendance Logs" });
        XLSX.writeFile(wb, "TASSI_Attendance_Report_${date}.xlsx");
    });

    // --- PDF EXPORT (using jsPDF & AutoTable) ---
    document.getElementById('exportPDF').addEventListener('click', () => {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'mm', 'a4'); // Landscape format

        // TASSI Branding in PDF
        doc.setFontSize(18);
        doc.text("TRACE ALARM & SECURITY SYSTEM, INC.", 14, 20);

        doc.setFontSize(10);
        doc.setTextColor(100);
        doc.text("ATTENDANCE LIST", 14, 28);

        doc.autoTable({
            html: 'table',
            startY: 35,
            theme: 'grid',
            headStyles: { fillColor: [220, 38, 38], textColor: [255, 255, 255], fontSize: 8 }, // TASSI Red
            styles: { fontSize: 7, cellPadding: 3 },
            margin: { top: 35 }
        });

        doc.save("TASSI_Attendance_Report_${date}.pdf");
    });
</script>