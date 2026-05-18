<?php 
// Kunin ang header
include __DIR__ . '/../../includes/header.php'; 
include __DIR__ . '/../../includes/left.php';

// Dummy data para sa preview (Huwag kalimutan palitan ng iyong actual $records query)
$records = $records ?? []; 
?>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

<!-- <style>
    body { background-color: #f8f9fa; margin: 0; padding: 0; }
    
    /* Ito ang nagpapadikit sa Sidebar at Content */
    .page-layout {
        display: flex;
        align-items: flex-start;
        padding: 20px;
        gap: 20px;
        width: 100%;
    }

    .main-wrapper {
        flex-grow: 1;
        min-width: 0; 
    }

    .content-area { 
        background: white;
        border-radius: 25px; 
        padding: 30px; 
        min-height: 90vh; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
    }

    .breadcrumb-ui { 
      font-size: 0.90rem;
      font-weight: 500;
      color: #6c757d;
    }

    .breadcrumb-ui a { 
        text-decoration: none; 
        color: #6c757d;
        transition: 0.2s;
    }

    .breadcrumb-ui a:hover { color: #e32133; }
    .breadcrumb-ui span { font-weight: 600; }

    .search-group {
        min-width: 400px;
        background: white;
        border-radius: 50px;
        border: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        padding: 5px 20px;
        transition: all 0.3s ease;
    }
    .search-group:focus-within {
        border-color: #e32133;
        box-shadow: 0 0 0 3px rgba(227, 33, 51, 0.1);
    }
    .search-input { 
        border: none !important; 
        box-shadow: none !important; 
        padding: 8px 10px;
        font-size: 0.95rem;
        flex-grow: 1;
    }
    .search-icon-wrapper { color: #adb5bd; font-size: 1.1rem; }

    .card-ui { 
        background: white; 
        border-radius: 15px; 
        box-shadow: 0 4px 12px rgba(0,0,0,0.05); 
        padding: 20px;
        position: relative;
    }
    .table-responsive { overflow: visible !important; }
    .table thead th {
        background-color: #e9f0f7;
        border: none;
        color: #333;
        font-weight: 600;
        padding: 15px;
        white-space: nowrap;
    }
    .table tbody td {
        border: 1px solid #dee2e6 !important; 
        padding: 5px;
    }    

    .status-badge { border-radius: 50px; padding: 5px 15px; font-size: 0.8rem; }
    .btn-actions { background-color: #2e4494; color: white; border-radius: 8px; border: none; padding: 6px 15px; }
    .btn-pill { border-radius: 50px !important; padding: 8px 25px; font-weight: 500; }
    
    .pagination .page-link {
        color: #6c757d;
        border-radius: 5px;
        margin: 0 2px;
        border: none;
    }
    .pagination .page-item.active .page-link {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
    }
    .dropdown-item:hover { background-color: #f8f9fa; }
</style> -->

<!-- <div class="page-layout">
    <div class="main-wrapper">
      <main class="content-area">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm no-print" style="border: 1px solid #f1f1f1;">
          <nav class="breadcrumb-ui">
            <a href="dashboard.php">Dashboard</a> / 
            <span style="color: #1c1e1b;">Leave</span>
          </nav>

          <div class="search-group">
            <span class="search-icon-wrapper"><i class="bi bi-search"></i></span>
            <input type="text" id="employeeSearch" class="form-control search-input" placeholder="Search by ID, Name, or Status">
          </div>

          <a href="leaves.php?a=create" class="btn btn-primary btn-pill">+ Add Leave</a>
        </div>

        <div class="card-ui">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold m-0">Leave Requests</h4>
            <div class="d-flex align-items-center gap-3">
              <i class="bi bi-filter filter-icon" id="toggleFilter" style="font-size: 1.5rem; cursor: pointer;"></i>
              <div class="dropdown">
                <button class="btn btn-primary btn-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">Export</button>
                <ul class="dropdown-menu shadow border-0">
                  <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="exportToPDF()"><i class="bi bi-file-pdf text-danger me-2"></i>PDF</a></li>
                  <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="exportToExcel()"><i class="bi bi-file-earmark-excel text-success me-2"></i>Excel</a></li>
                </ul>
              </div>
            </div>
          </div>

          <div id="filterPanel" class="bg-light p-3 mb-4 rounded border" style="display: none;">
            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label small fw-bold">Department</label>
                  <select id="filterDept" class="form-select form-select-sm shadow-sm">
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
              </div>
              <div class="col-md-4">
                <label class="form-label small fw-bold">Status</label>
                <select id="filterStatus" class="form-select form-select-sm shadow-sm">
                  <option value="">All Status</option>
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
              </div>
              <div class="col-md-4 d-flex align-items-end">
                <button id="resetFilter" class="btn btn-secondary btn-sm w-100 btn-pill">Reset Filter</button>
              </div>
            </div>
          </div>

      <?php if ($successMsg): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMsg) ?></div>
      <?php endif; ?>

      <?php if ($errorMsg): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMsg) ?></div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-hover align-middle">
          <thead>
            <tr class="text-center">
              <th>ID</th>
              <th>Employee</th>
              <th>Type</th>
              <th>From</th>
              <th>To</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
        <tbody>
          <?php foreach ($records as $row): ?>
            <tr>
              <td class="text-center"><?= $row['id'] ?></td>
              <td><?= $row['employee_code'].' - '.$row['first_name'] ?></td>
              <td><?= ucfirst($row['leave_type']) ?></td>
              <td class="text-center"><?= $row['start_date'] ?></td>
              <td class="text-center"><?= $row['end_date'] ?></td>
              <td class="text-center">
                <span class="badge bg-info"><?= ucfirst($row['status']) ?></span>
              </td>
              <td class="text-center">
                <div class="dropdown">
                  <button class="btn btn-actions dropdown-toggle px-3 shadow-sm" type="button" data-bs-toggle="dropdown">Actions</button>
                  <ul class="dropdown-menu dropdown-menu-end shadow border-0 py-2 bg-white">
                    <li><a class="dropdown-item d-flex align-items-center py-2 text-dark" href="leaves.php?a=edit&id=<?= $row['id'] ?>" class="btn btn-sm btn-primary"> Edit</a></li>
                    <li><a class="dropdown-item d-flex align-items-center py-2 text-dark" href="leaves.php?a=delete&id=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete leave request?')"> Delete </a></li>
                  </ul>
                </div> -->
                <!-- <a href="leaves.php?a=edit&id=<?= $row['id'] ?>" class="bi bi-pencil-square btn btn-sm btn-primary"> </a>
                <a href="leaves.php?a=delete&id=<?= $row['id'] ?>" class="bi bi-trash btn btn-sm btn-danger" onclick="return confirm('Delete leave request?')">  </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
                <div class="d-flex justify-content-between align-items-center mt-4">
                  <div class="text-muted small">
                      Showing <span id="showingCount">0</span> of <span id="totalCount">0</span> results
                  </div>
                  <nav>
                    <ul class="pagination pagination-sm mb-0" id="paginationControls"></ul>
                  </nav>
                </div>
    </main>
  </div>
</div> -->

<!-- <script>
    // Initialize Lucide Icons
    lucide.createIcons();

    const rowsPerPage = 10;
    let currentPage = 1;

    const filterDept = document.getElementById('filterDept');
    const filterStatus = document.getElementById('filterStatus');
    const searchInput = document.getElementById('employeeSearch');
    const tableBody = document.getElementById('employeeTable');
    const allRows = Array.from(tableBody.querySelectorAll('tr:not(#noResultsRow)'));

    function applyFilters() {
        const searchValue = searchInput.value.toLowerCase();
        const deptValue = filterDept.value.toLowerCase();
        const statusValue = filterStatus.value.toLowerCase();

        const filteredRows = allRows.filter(row => {
            const rowText = row.innerText.toLowerCase();
            const deptText = row.cells[3].innerText.toLowerCase(); 
            const statusText = row.cells[6].innerText.toLowerCase(); 

            return rowText.includes(searchValue) && 
                   (deptValue === "" || deptText.includes(deptValue)) && 
                   (statusValue === "" || statusText.trim() === statusValue);
        });

        const totalFiltered = filteredRows.length;
        const totalPages = Math.ceil(totalFiltered / rowsPerPage);
        if (currentPage > totalPages) currentPage = 1;

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;
        const pageRows = filteredRows.slice(start, end);

        allRows.forEach(row => row.style.display = 'none');
        pageRows.forEach(row => row.style.display = '');

        const noResultsRow = document.getElementById('noResultsRow');
        if (noResultsRow) noResultsRow.style.display = totalFiltered === 0 ? '' : 'none';

        document.getElementById('totalCount').innerText = totalFiltered;
        document.getElementById('showingCount').innerText = pageRows.length;
        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        const wrapper = document.getElementById('paginationControls');
        wrapper.innerHTML = "";
        if (totalPages <= 1) return;

        const createBtn = (label, targetPage, active = false, disabled = false) => {
            const li = document.createElement('li');
            li.className = `page-item ${active ? 'active' : ''} ${disabled ? 'disabled' : ''}`;
            li.innerHTML = `<a class="page-link" href="javascript:void(0)" onclick="changePage(${targetPage})">${label}</a>`;
            return li;
        };

        wrapper.appendChild(createBtn('Prev', currentPage - 1, false, currentPage === 1));
        for (let i = 1; i <= totalPages; i++) {
            wrapper.appendChild(createBtn(i, i, i === currentPage));
        }
        wrapper.appendChild(createBtn('Next', currentPage + 1, false, currentPage === totalPages));
    }

    function changePage(page) {
        currentPage = page;
        applyFilters();
    }

    function openArchiveModal(id, name) {
        document.getElementById('archiveEmployeeName').innerText = name;
        document.getElementById('confirmArchiveBtn').href = `employees.php?a=delete&id=${id}`;
        new bootstrap.Modal(document.getElementById('archiveEmployeeModal')).show();
    }

    function getFilteredDataForExport() {
        const dataRows = allRows.filter(row => row.style.display !== 'none');
        return dataRows.map(row => Array.from(row.cells).slice(0, -1).map(c => c.innerText.trim()));
    }

    function exportToExcel() {
        const data = getFilteredDataForExport();
        const headers = Array.from(document.querySelectorAll(".table thead th")).slice(0, -1).map(h => h.innerText);
        const worksheet = XLSX.utils.aoa_to_sheet([headers, ...data]);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Employees");
        XLSX.writeFile(workbook, "Employee_List.xlsx");
    }

    function exportToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('l', 'pt', 'a4');
        const headers = Array.from(document.querySelectorAll(".table thead th")).slice(0, -1).map(h => h.innerText.trim());
        const body = getFilteredDataForExport();
        doc.autoTable({ head: [headers], body: body, startY: 50, theme: 'striped', headStyles: { fillColor: [46, 68, 148] } });
        doc.text("ACTIVE EMPLOYEE LIST", 40, 35);
        doc.save("Employee_List.pdf");
    }

    searchInput.addEventListener('input', () => { currentPage = 1; applyFilters(); });
    filterDept.addEventListener('change', () => { currentPage = 1; applyFilters(); });
    filterStatus.addEventListener('change', () => { currentPage = 1; applyFilters(); });
    document.getElementById('toggleFilter').addEventListener('click', () => {
        const p = document.getElementById('filterPanel');
        p.style.display = p.style.display === 'none' ? 'block' : 'none';
    });
    document.getElementById('resetFilter').addEventListener('click', () => {
        searchInput.value = ""; filterDept.value = ""; filterStatus.value = "";
        currentPage = 1; applyFilters();
    });

    applyFilters();
</script> -->

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
            <i data-lucide="calendar-off" class="w-3 h-3 text-red-600"></i> Leave Requests
        </span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Leave Form List</h1>
            <p class="text-slate-500 text-sm font-medium italic">TASSI Human Resource Management System</p>
        </div>
       <div class="flex flex-wrap gap-3">
    <div class="flex items-center bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
        <button id="exportExcel" class="flex items-center gap-2 px-3 py-1.5 text-slate-600 rounded-lg text-[10px] font-bold transition-all duration-200 uppercase tracking-wider hover:bg-red-50">
            <i data-lucide="file-spreadsheet" class="w-3.5 h-3.5 text-green-600"></i> 
            <span>Excel</span>
        </button>
        
        <div class="w-px h-4 bg-slate-200 mx-1"></div>
        
        <button id="exportPDF" class="flex items-center gap-2 px-3 py-1.5 text-slate-600 rounded-lg text-[10px] font-bold transition-all duration-200 uppercase tracking-wider hover:bg-red-50">
            <i data-lucide="file-text" class="w-3.5 h-3.5 text-red-600"></i> 
            <span>PDF</span>
        </button>
    </div>
            <a href="leaves.php?a=create" class="flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-800 transition shadow-lg shadow-slate-200">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Add Leave Application
            </a>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-sm flex flex-col lg:flex-row gap-4 items-center">
    
    <div class="relative flex-1 w-full">
        <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
        <input type="text" id="employeeSearch"
               class="w-full pl-12 pr-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm transition-colors duration-200 hover:border-red-600 hover:bg-red-50 focus:outline-none focus:border-red-600 focus:bg-red-50"
               placeholder="SEARCH BY ID, NAME, OR DEPARTMENT...">
    </div>

    <div class="flex flex-wrap md:flex-nowrap gap-2 w-full lg:w-auto items-center">
        
        <div class="relative min-w-[180px] group flex-1 md:flex-none">
<input type="checkbox" id="dept-toggle" class="filter-dropdown absolute opacity-0 w-full h-full cursor-pointer z-20 peer" />               
 <div class="h-[46px] w-full bg-white border-2 border-slate-200 rounded-xl px-4 text-[10px] font-black uppercase tracking-widest text-slate-900 transition-all duration-200 flex items-center justify-between peer-hover:border-red-600 peer-hover:bg-red-50 peer-hover:text-red-600 peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:text-red-600">
                <span>All Departments</span>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 peer-checked:rotate-180"></i>
            </div>
            <div class="absolute z-50 top-full left-0 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden hidden peer-checked:block animate-in fade-in zoom-in-95 duration-150">
                <div class="bg-red-700 text-white px-4 py-3 text-[10px] font-black uppercase tracking-widest">Departments</div>
                <div class="max-h-60 overflow-y-auto">
                    <label for="dept-toggle" class="block px-4 py-3 text-[10px] font-black text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 transition-colors cursor-pointer uppercase">Admin</label>
                    <label for="dept-toggle" class="block px-4 py-3 text-[10px] font-black text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 transition-colors cursor-pointer uppercase">Finance</label>
                    <label for="dept-toggle" class="block px-4 py-3 text-[10px] font-black text-slate-700 hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer uppercase">IT</label>
                </div>
            </div>
        </div>

        <div class="relative min-w-[160px] group flex-1 md:flex-none">
      <input type="checkbox" id="status-toggle" class="filter-dropdown absolute opacity-0 w-full h-full cursor-pointer z-20 peer" />                
      <div class="h-[46px] w-full bg-white border-2 border-slate-200 rounded-xl px-4 text-[10px] font-black uppercase tracking-widest text-slate-900 transition-all duration-200 flex items-center justify-between peer-hover:border-red-600 peer-hover:bg-red-50 peer-hover:text-red-600 peer-checked:border-red-600 peer-checked:bg-red-50 peer-checked:text-red-600">
                <span>All Status</span>
                <i data-lucide="chevron-down" class="w-4 h-4 transition-transform duration-200 peer-checked:rotate-180"></i>
            </div>
            <div class="absolute z-50 top-full left-0 mt-2 w-full bg-white rounded-xl shadow-xl border border-slate-100 overflow-hidden hidden peer-checked:block animate-in fade-in zoom-in-95 duration-150">
                <div class="bg-red-700 text-white px-4 py-3 text-[10px] font-black uppercase tracking-widest">Select Status</div>
                <div class="max-h-60 overflow-y-auto">
                    <label for="status-toggle" class="block px-4 py-3 text-[10px] font-black text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 transition-colors cursor-pointer uppercase">Approved</label>
                    <label for="status-toggle" class="block px-4 py-3 text-[10px] font-black text-slate-700 hover:bg-red-50 hover:text-red-600 border-b border-slate-50 transition-colors cursor-pointer uppercase">Pending</label>
                    <label for="status-toggle" class="block px-4 py-3 text-[10px] font-black text-slate-700 hover:bg-red-50 hover:text-red-600 transition-colors cursor-pointer uppercase">Rejected</label>
                </div>
            </div>
        </div>

        <button id="resetFilter" class="h-[46px] w-[46px] flex items-center justify-center bg-slate-900 text-white rounded-xl hover:bg-red-800 transition shadow-md shadow-slate-200 shrink-0">
            <i data-lucide="rotate-ccw" class="w-4 h-4"></i>
        </button>
    </div>
</div>

   <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-10">
    <!-- Tinanggal ang content dito para mag-dikit ang Red Header sa rounded corner -->
    <div class="hidden"></div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-sm border-collapse">
            <!-- Eto yung "Tassi Red" style: Solid red background, white text -->
            <thead class="bg-[#991b1b] text-[10px] font-bold text-white uppercase tracking-widest">
                <tr>
                    <th class="px-6 py-4 text-center">Reference Number</th>
                    <th class="px-6 py-4">Employee Name</th>
                    <th class="px-6 py-4">Timestamp</th>
                    <th class="px-6 py-4 text-center">Date (From)</th>
                    <th class="px-6 py-4 text-center">Date (To)</th>
                    <th class="px-6 py-4 text-center">Total Days</th>
                    <th class="px-6 py-4 text-center">Reason</th>
                    <th class="px-6 py-4 text-center">Other Reason</th>
                    <th class="px-6 py-4 text-center">Status</th>
                    <th class="px-6 py-4">Remarks By</th>
                    <th class="px-6 py-4">Remarks</th>
                    <th class="px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
          <tbody id="leaveTableBody" class="divide-y divide-slate-50">
            <?php if (!empty($records)): foreach ($records as $row): ?>
              <tr class="hover:bg-slate-50/50 transition-colors group">
                <td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600">
                    #<?= str_pad($row['id'], 4, '0', STR_PAD_LEFT) ?>
                </td>
                <td class="px-6 py-4">
                  <p class="font-black text-slate-800 uppercase tracking-tight leading-none text-sm">
                    <?= strtoupper($row['last_name'] . ' ' . $row['first_name']) ?>
                  </p>
                </td>
                <!-- <td class="px-6 py-4">
                  <span class="text-[10px] font-black text-slate-600 uppercase bg-slate-100 px-2 py-1 rounded">
                    <?= $row['leave_type'] ?>
                  </span>
                </td> -->
                <td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600"> //Timestamp
                  <span class="text-[10px] font-black text-slate-600 uppercase bg-slate-100 px-2 py-1 rounded">
                    <!-- <?= $row['leave_type'] ?> -->
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="px-6 py-4 text-slate-900 mono text-s group-hover:text-red-600">
                      <?= date('M j, Y', strtotime($row['start_date'])) ?>
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="flex flex-col">
                    <span class="px-6 py-4 text-slate-900 mono text-s group-hover:text-red-600">
                      <?= date('M j, Y', strtotime($row['end_date'])) ?>
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600"> //Total Days
                  <span class="text-[10px] font-black text-slate-600 uppercase bg-slate-100 px-2 py-1 rounded">
                    <!-- <?= $row['leave_type'] ?> -->
                  </span>
                </td>
                <td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600"> //Reason
                  <span class="text-[10px] font-black text-slate-600 uppercase bg-slate-100 px-2 py-1 rounded">
                    <!-- <?= $row['leave_type'] ?> -->
                  </span>
                </td>
                <td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600"> //Other Reason
                  <span class="text-[10px] font-black text-slate-600 uppercase bg-slate-100 px-2 py-1 rounded">
                    <!-- <?= $row['other_reason'] ?> -->
                  </span>
                </td>
                <td class="px-6 py-4 text-center">
                  <?php
                    $status = strtoupper($row['status'] ?? 'PENDING');
                                
                    // Configuration para sa kulay at label ng bawat status
                    switch($status) {
                      case 'APPROVED':
                        $bg = 'bg-green-50';
                        $text = 'text-green-600';
                        $border = 'border-green-100';
                        $icon = 'check-circle';
                      break;
                      case 'REJECTED':
                        $bg = 'bg-red-50';
                        $text = 'text-red-600';
                        $border = 'border-red-100';
                        $icon = 'x-circle';
                      break;
                      default: // PENDING
                        $bg = 'bg-amber-50';
                        $text = 'text-amber-600';
                        $border = 'border-amber-100';
                        $icon = 'clock-3';
                      break;
                    }
                  ?>
                 <div class="inline-flex items-center gap-1.5 px-0 py-1 <?= $text ?>">
                    <i data-lucide="<?= $icon ?>" class="w-3 h-3"></i>
                    <span class="text-[10px] font-black uppercase tracking-tighter">
                        <?= str_replace('_', ' ', $status ?: 'NO DATA') ?>
                    </span>
                </div>
                </td>
                <td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600"> //Remarks By
                  <span class="text-[10px] font-black text-slate-600 uppercase">
                    <!-- <?= $row['leave_type'] ?> -->
                  </span>
                </td>
                <td class="px-6 py-4 text-center text-slate-900 mono text-s group-hover:text-red-600"> //Remarks
                  <span class="text-[10px] font-black text-slate-600 uppercase bg-slate-100 px-2 py-1 rounded">
                    <!-- <?= $row['leave_type'] ?> -->
                  </span>
                </td>
                <td class="px-6 py-4 text-right">
                  <div class="flex justify-end gap-2">
                    <a href="leaves.php?a=edit&id=<?= $row['id'] ?>" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Edit">
                      <i data-lucide="edit-3" class="w-4 h-4"></i>
                    </a>
                    <!-- <a href="leaves.php?a=delete&id=<?= $row['id'] ?>" 
                      class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition" 
                      onclick="return confirm('Delete leave request #<?= $row['id'] ?>?')" title="Delete">
                      <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </a> -->
                    <a href="leaves.php?a=edit&id=<?= $row['id'] ?>" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="File Details">
                      <i data-lucide="file" class="w-4 h-4"></i>
                    </a>
                    <a href="leaves.php?a=edit&id=<?= $row['id'] ?>" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition" title="Download">
                      <i data-lucide="download" class="w-4 h-4"></i>
                    </a>
                  </div>
                </td>
              </tr>
            <?php endforeach; else: ?>
                <tr>
                  <td colspan="6" class="px-6 py-20 text-center">
                    <div class="flex flex-col items-center opacity-20">
                      <i data-lucide="database-zap" class="w-12 h-12 mb-2 text-slate-900"></i>
                      <p class="text-[10px] font-black uppercase tracking-widest italic text-slate-900">No Leave Records Found</p>
                    </div>
                  </td>
                </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
      <!-- <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic"> Total
          <span id="recordCount" class="text-slate-900 font-black"><?= count($records ?? []) ?></span> Leave Log Records
        </p>
      </div> -->

    <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">
        Showing <span id="currentVisible" class="text-slate-900 font-black">0</span> 
        of <span id="totalRecords" class="text-slate-900 font-black">0</span> 
        Operational Records
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

<!-- <script>
  // Initialize Lucide Icons
  lucide.createIcons();

  const searchInput = document.getElementById('leaveSearch');
  const typeFilter   = document.getElementById('filterType');
  const statusFilter = document.getElementById('filterStatus');
  const resetBtn     = document.getElementById('resetFilter');
  const tableRows    = document.querySelectorAll('#leaveTableBody tr:not(.no-records)');

function filterTable() {
    const searchTerm   = searchInput.value.toLowerCase();
    const selectedType = typeFilter.value.toLowerCase();
    const selectedStat = statusFilter.value.toLowerCase();
    let visibleCount = 0;

    tableRows.forEach(row => {
        // Kunin ang data base sa tamang column index
        const refNumber    = row.cells[0].innerText.toLowerCase();
        const employeeName = row.cells[1].innerText.toLowerCase();
        const leaveType    = row.cells[6].innerText.toLowerCase(); // Remarks column ang may leave_type sa code mo
        const statusText   = row.cells[8].innerText.toLowerCase();

        // Pag-check kung nag-ma-match sa search bar
        const matchesSearch = refNumber.includes(searchTerm) || 
                              employeeName.includes(searchTerm) || 
                              leaveType.includes(searchTerm);

        // Pag-check sa dropdown filters
        const matchesType = selectedType === "" || leaveType.includes(selectedType);
        const matchesStat = selectedStat === "" || statusText.trim() === selectedStat;

        if (matchesSearch && matchesType && matchesStat) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });
    
    document.getElementById('recordCount').innerText = visibleCount;
}

  [searchInput, typeFilter, statusFilter].forEach(el => {
    el.addEventListener('input', filterTable);
    el.addEventListener('change', filterTable);
  });

  resetBtn.addEventListener('click', () => {
    searchInput.value = "";
    typeFilter.value = "";
    statusFilter.value = "";
    filterTable();
  });

  // --- EXCEL EXPORT (Clean - No Buttons) ---
  document.getElementById('exportExcel').addEventListener('click', () => {
    const originalTable = document.querySelector("table");
      
    // 1. Clone ang table para hindi madamay ang display sa screen
    const tempTable = originalTable.cloneNode(true);
      
    // 2. Loop sa lahat ng rows at tanggalin ang huling cell (Actions)
    tempTable.querySelectorAll('tr').forEach(row => {
      if (row.lastElementChild) {
          row.removeChild(row.lastElementChild);
      }
    });

    // 3. I-export ang malinis na table
    const wb = XLSX.utils.table_to_book(tempTable, { sheet: "Leave Reports" });
    const date = new Date().toISOString().slice(0, 10);
    XLSX.writeFile(wb, `TASSI_Leave_Report_${date}.xlsx`);
  });

  // --- PDF EXPORT (Consistent with Attendance Style) ---
  document.getElementById('exportPDF').addEventListener('click', () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4'); // Landscape format

    // TASSI Branding in PDF
    doc.setFontSize(18);
    // doc.setTextColor(30, 41, 59); // Slate-800
    doc.text("TRACE ALARM & SECURITY SYSTEM, INC.", 14, 20);
      
    doc.setFontSize(10);
    doc.setTextColor(100); // TASSI Red
    doc.text("LEAVE APPLICATIONS LIST", 14, 28);

    doc.autoTable({ 
      html: 'table', 
      startY: 35,
      theme: 'grid',
      headStyles: { fillColor: [220, 38, 38], textColor: [255, 255, 255], fontSize: 8 }, // TASSI Red
      // Index 0 hanggang 10 lang (Inalis ang Actions sa Index 11)
      columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10], 
      styles: { fontSize: 7, cellPadding: 3 },
      margin: { top: 35 }
    });
      
    doc.save("TASSI_Leave_Report_${date}.pdf");
  });
</script> -->

<script>
  // Initialize Lucide Icons
  lucide.createIcons();

  // --- CONFIGURATION ---
  const rowsPerPage = 12;
  let currentPage = 1;
  
  // Elements
  const tableBody = document.getElementById('leaveTableBody');
  const searchInput = document.getElementById('leaveSearch');
  const typeFilter = document.getElementById('filterType');
  const statusFilter = document.getElementById('filterStatus');
  const resetBtn = document.getElementById('resetFilter');
  
  // Kunin ang lahat ng rows na galing sa PHP (Static data source)
  const allRows = Array.from(tableBody.querySelectorAll('tr')).filter(row => !row.classList.contains('no-records-row'));

  function applyFilters() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    const selectedType = typeFilter.value.toLowerCase();
    const selectedStat = statusFilter.value.toUpperCase().trim();

    // 1. FILTERING LOGIC
    const filteredRows = allRows.filter(row => {
      const refNumber = row.cells[0].innerText.toLowerCase();
      const empName = row.cells[1].innerText.toLowerCase();
      // Sa table mo: index 8 ang Status. I-adjust kung iba ang pwesto ng Leave Type.
      const statusText = row.cells[8].innerText.toUpperCase().trim();

      const matchesSearch = searchTerm === "" || 
                            refNumber.includes(searchTerm) || 
                            empName.includes(searchTerm);
                            
      const matchesType = selectedType === "" || 
                          row.innerText.toLowerCase().includes(selectedType);

      const matchesStat = selectedStat === "" || statusText === selectedStat;

      return matchesSearch && matchesType && matchesStat;
    });

    const totalFiltered = filteredRows.length;
    const totalPages = Math.ceil(totalFiltered / rowsPerPage);

    // Siguraduhin na hindi lalampas ang current page sa total pages
    if (currentPage > totalPages && totalPages > 0) currentPage = 1;

    // 2. PAGINATION LOGIC (Slice)
    const startIdx = (currentPage - 1) * rowsPerPage;
    const endIdx = startIdx + rowsPerPage;
    const pageRows = filteredRows.slice(startIdx, endIdx);

    // 3. UI UPDATE (Display rows)
    allRows.forEach(row => row.style.display = 'none'); // Itago lahat
    pageRows.forEach(row => row.style.display = '');    // Ipakita lang ang nasa page

    // 4. UPDATE COUNTERS (Showing X of Y)
    // --- UPDATE COUNTERS (Exact format: Showing X of Y Operational Records) ---
    const totalRecordsDisplay = document.getElementById('totalRecords');
    const currentVisibleDisplay = document.getElementById('currentVisible');

    // Ito ang bilang ng rows na nasa current page lang (karaniwan ay 12 o mas mababa)
    const countOnPage = pageRows.length;

    // Ito ang kabuuang bilang ng records na tumugma sa filter
    const totalMatched = totalFiltered;

    if (currentVisibleDisplay) {
        currentVisibleDisplay.innerText = countOnPage;  
    }

    if (totalRecordsDisplay) {
        totalRecordsDisplay.innerText = totalMatched;
    }

    renderPagination(totalPages);
  }

  function renderPagination(totalPages) {
    const wrapper = document.getElementById('paginationControls');
    wrapper.innerHTML = "";
    
    if (totalPages <= 1) return;

    for (let i = 1; i <= totalPages; i++) {
      const btn = document.createElement('button');
      btn.innerText = i;
      btn.className = `w-8 h-8 rounded-lg text-[10px] font-black transition italic ${
        i === currentPage 
        ? 'bg-slate-900 text-white shadow-lg' 
        : 'bg-white border border-slate-200 text-slate-400 hover:bg-slate-50'
      }`;
      
      btn.onclick = () => {
        currentPage = i;
        applyFilters();
        // Scroll pabalik sa taas ng table
        window.scrollTo({ top: tableBody.offsetTop - 100, behavior: 'smooth' });
      };
      wrapper.appendChild(btn);
    }
  }

  // --- EVENT LISTENERS ---
  [searchInput, typeFilter, statusFilter].forEach(el => {
    el.addEventListener('input', () => { currentPage = 1; applyFilters(); });
  });

  resetBtn.addEventListener('click', () => {
    searchInput.value = "";
    typeFilter.value = "";
    statusFilter.value = "";
    currentPage = 1;
    applyFilters();
  });

  // --- EXPORTS ---
  document.getElementById('exportExcel').addEventListener('click', () => {
    const date = new Date().toISOString().slice(0, 10);
    const wb = XLSX.utils.table_to_book(document.querySelector("table"), { sheet: "Leaves" });
    XLSX.writeFile(wb, `TASSI_Leave_Report_${date}.xlsx`);
  });

  document.getElementById('exportPDF').addEventListener('click', () => {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4');
    doc.text("LEAVE APPLICATIONS LIST", 14, 20);
    doc.autoTable({ 
        html: 'table', 
        startY: 25,
        theme: 'grid',
        headStyles: { fillColor: [220, 38, 38] },
        columns: [0, 1, 3, 4, 5, 8] // Piliin lang ang importanteng columns
    });
    doc.save(`TASSI_Leave_Report.pdf`);
  });

  // INITIAL RUN
  const dropdowns = document.querySelectorAll('.filter-dropdown');

    dropdowns.forEach(current => {
        current.addEventListener('change', function() {
            // KAPAG BINUKSAN (CHECKED) ANG ISA...
            if (this.checked) {
                // ISARA LAHAT NG IBANG DROPDOWN MALIBAN SA PININDOT MO
                dropdowns.forEach(others => {
                    if (others !== this) {
                        others.checked = false;
                    }
                });
            }
        });
    });

    // 2. AUTO-CLOSE KAPAG NAG-CLICK SA LABAS
    // Para hindi maiwang nakabukas ang menu kapag nag-scroll o nag-click sa table
    document.addEventListener('click', function(e) {
        // Kapag ang clinick ay hindi yung dropdown button mismo
        if (!e.target.closest('.relative.group')) {
            dropdowns.forEach(d => d.checked = false);
        }
    });
  
</script>