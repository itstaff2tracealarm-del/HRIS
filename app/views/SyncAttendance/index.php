<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/left.php'; ?>

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;700&family=Inter:wght@400;600;900&display=swap');
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        .sidebar-transition { transition: all 0.3s ease; }
        
        .img-thumb {
            width: 40px; height: 40px; object-fit: cover;
            border-radius: 8px; cursor: pointer; transition: transform 0.2s;
            border: 1px solid #e2e8f0;
        }
        .img-thumb:hover { transform: scale(1.1); }

        /* Flatpickr Styling */
       /* Updated Flatpickr Global Styling */
        .flatpickr-calendar { 
            border-radius: 16px !important; 
            border: none !important; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15) !important;
            z-index: 999999 !important;
            /* Inalis ang scale(0.9) para pantay ang alignment ng dropdown */
            transform: scale(1) !important; 
            background: #ffffff !important;
            padding: 4px !important;
        }

        /* Header Part (Month & Year) */
        .flatpickr-months .flatpickr-month { 
            background: #991b1b !important; /* TASSI Red */
            color: white !important; 
            fill: white !important; 
            border-radius: 12px 12px 0 0;
            padding: 8px 0 !important;
        }

        /* --- ITO YUNG FIX SA MONTH DROPDOWN UI --- */
        .flatpickr-monthDropdown-months {
            background: rgba(255, 255, 255, 0.15) !important;
            color: white !important;
            font-size: 12px !important;
            font-weight: 900 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            padding: 4px 10px !important;
            border-radius: 6px !important;
            border: 1px solid rgba(255, 255, 255, 0.2) !important;
            cursor: pointer !important;
        }

        /* Para sa dropdown selection items */
        .flatpickr-monthDropdown-month {
            background-color: #ffffff !important;
            color:black !important; /* Dark Slate */
            font-weight: 600 !important;
        }

        /* Weekdays & Days */
        .flatpickr-weekdays { background: #991b1b !important; padding-bottom: 5px !important; }
        span.flatpickr-weekday { color: rgba(255,255,255,0.7) !important; font-weight: 800 !important; font-size: 10px !important; }

        .flatpickr-day.selected, .flatpickr-day.selected:hover { 
            background: #991b1b !important; 
            border-color: #991b1b !important;
            color: white !important;
            border-radius: 8px !important;
        }

        .flatpickr-day:hover {
            background: #f1f5f9 !important;
            border-radius: 8px !important;
        }

        /* Footer Section */
        .flatpickr-footer {
            display: flex; 
            justify-content: space-between;
            padding: 10px 12px; 
            border-top: 1px solid #f1f5f9;
            background: #fff; 
            border-radius: 0 0 12px 12px;
        }
    </style>
</head>

<main id="main-content" class="flex-1 overflow-y-auto p-6 lg:p-10 sidebar-transition">
    <div class="flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
        <a href="dashboard.php" class="hover:text-red-600 transition flex items-center gap-1.5">
            <i data-lucide="layout-dashboard" class="w-3 h-3"></i> Dashboard
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-900 flex items-center gap-1.5">
            <i data-lucide="refresh-cw" class="w-3 h-3 text-red-600"></i> Sync Attendance
        </span>
    </div>

    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Technical Attendance</h1>
            <p class="text-slate-500 text-sm font-medium italic">TASSI Local to Cloud Data Sync</p>
        </div>
        
        <form method="post" action="syncAttendance.php?a=sync">
            <button type="submit" class="flex items-center gap-2 px-6 py-3 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-800 transition shadow-lg shadow-slate-200">
                <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Start Sync Now
            </button>
        </form>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl p-4 mb-6 shadow-sm">
        <div class="flex flex-col md:flex-row gap-4 items-stretch">
            <div class="relative flex-1">
                <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" id="employeeSearch" class="w-full h-full pl-12 pr-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-sm font-medium focus:outline-none focus:border-red-600
                    focus:ring-4 focus:ring-red-600/10 placeholder:text-slate-400 hover:bg-red-50 hover:border-red-600 transition-all duration-200 shadow-sm" placeholder="SEARCH BY ID, CARD NUMBER...">
            </div>
            <div class="relative w-full md:w-72">
                <i data-lucide="calendar" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 z-10"></i>
                <input type="text" id="dateFilter" placeholder="SELECT DATE" class="w-full h-full pl-12 pr-4 py-3 bg-white border-2 border-slate-200 rounded-xl text-[10px] font-black uppercase tracking-widest
                    text-slate-700 placeholder:text-slate-700 focus:outline-none focus:border-red-600 focus:ring-4 focus:ring-red-600/10 hover:bg-red-50 hover:border-red-600 transition-all duration-200 shadow-sm cursor-pointer">
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200 mb-10 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-red-800 text-white uppercase text-[11px] tracking-widest font-bold">
                        <th class="px-6 py-4 text-center">ID</th>
                        <th class="px-6 py-4 text-center">Card Number</th>
                        <th class="px-6 py-4 text-left">Time In</th>
                        <th class="px-6 py-4 text-left">Time Out</th>
                    </tr>
                </thead>
                <tbody id="attendanceTableBody" class="divide-y divide-slate-50 text-center font-medium text-slate-600"></tbody>
            </table>
        </div>
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
    </footer>
</main>

<div id="globalImageModal" class="fixed inset-0 z-[9999] hidden bg-slate-900/90 backdrop-blur-sm flex items-center justify-center p-4">
    <button onclick="closeImageModal()" class="absolute top-6 right-6 text-white hover:text-red-500">
        <i data-lucide="x" class="w-10 h-10"></i>
    </button>
    <div class="max-w-4xl w-full flex flex-col items-center">
        <div id="modalTitle" class="mb-4"></div> 
        <img id="modalImageSource" src="" class="max-h-[80vh] w-auto rounded-2xl shadow-2xl border-4 border-white/10">
    </div>
</div>

<script src="assets/js/create_sync.js"></script>

<script>
    lucide.createIcons();

    const rowsPerPage = 15;
    let currentPage = 1;
    const tableBody = document.getElementById('attendanceTableBody');
    const searchInput = document.getElementById('employeeSearch');
    const dateInput = document.getElementById('dateFilter');

    function applyFilters() {
        const allRows = Array.from(tableBody.querySelectorAll('tr'));
        const searchTerm = searchInput.value.toLowerCase().trim();
        const filterDate = dateInput.value; // YYYY-MM-DD

        const filteredRows = allRows.filter(row => {
            const textMatch = searchTerm === "" || row.innerText.toLowerCase().includes(searchTerm);
            
            // Mas accurate na date matching: 
            // Kinukuha lang ang Date part (YYYY-MM-DD) mula sa text ng row
            const dateMatch = filterDate === "" || row.innerText.includes(filterDate);

            return textMatch && dateMatch;
        });

        const totalFiltered = filteredRows.length;
        const totalPages = Math.ceil(totalFiltered / rowsPerPage);

        if (currentPage > totalPages && totalPages > 0) currentPage = 1;

        const startIdx = (currentPage - 1) * rowsPerPage;
        const pageRows = filteredRows.slice(startIdx, startIdx + rowsPerPage);

        allRows.forEach(row => row.style.display = 'none');
        pageRows.forEach(row => row.style.display = '');

        document.getElementById('totalRecords').innerText = totalFiltered;
        document.getElementById('currentVisible').innerText = pageRows.length;

        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        const wrapper = document.getElementById('paginationControls');
        wrapper.innerHTML = "";
        if (totalPages <= 1) return;

        const createBtn = (content, disabled, onClick, active = false) => {
            const btn = document.createElement('button');
            btn.innerHTML = content;
            btn.disabled = disabled;
            btn.className = `w-8 h-8 flex items-center justify-center rounded-lg text-[10px] font-black transition ${
                active ? 'bg-slate-900 text-white' : 'bg-white border border-slate-200 text-slate-400 hover:bg-slate-100'
            } ${disabled ? 'opacity-30 cursor-not-allowed' : ''}`;
            btn.onclick = onClick;
            return btn;
        };

        wrapper.appendChild(createBtn('<i data-lucide="chevron-left" class="w-3 h-3"></i>', currentPage === 1, () => { currentPage--; applyFilters(); }));
        
        for (let i = 1; i <= totalPages; i++) {
            wrapper.appendChild(createBtn(i, false, () => { currentPage = i; applyFilters(); }, i === currentPage));
        }

        wrapper.appendChild(createBtn('<i data-lucide="chevron-right" class="w-3 h-3"></i>', currentPage === totalPages, () => { currentPage++; applyFilters(); }));
        lucide.createIcons();
    }

    // Flatpickr Instance
    flatpickr("#dateFilter", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "F j, Y",
        disableMobile: true,
        onChange: function() {
            currentPage = 1;
            applyFilters();
        },
        onOpen: function(selectedDates, dateStr, instance) {
            const updatePos = () => {
                const inputRect = instance.altInput.getBoundingClientRect();
                instance.calendarContainer.style.position = "fixed";
                instance.calendarContainer.style.top = (inputRect.bottom + 5) + "px";
                instance.calendarContainer.style.left = inputRect.left + "px";
            };
            updatePos();
            document.getElementById('main-content').addEventListener('scroll', updatePos);
            instance._stickyHandler = updatePos;
        },
        onClose: function(selectedDates, dateStr, instance) {
            document.getElementById('main-content').removeEventListener('scroll', instance._stickyHandler);
        },
        onReady: function(selectedDates, dateStr, instance) {
    const footer = document.createElement("div");
    footer.className = "flatpickr-footer";
    footer.innerHTML = `
        <button class="btn-clear" style="color:#ef4444; font-size:10px; font-weight:900; border:none; background:none; cursor:pointer; padding:4px 8px; border-radius:6px; transition:all 0.2s;">CLEAR</button>
        <button class="btn-today" style="color:#0f172a; font-size:10px; font-weight:900; border:none; background:none; cursor:pointer; padding:4px 8px; border-radius:6px; transition:all 0.2s;">TODAY</button>
    `;

    // Hover effect para sa CLEAR
    const clearBtn = footer.querySelector('.btn-clear');
    clearBtn.onmouseover = () => { clearBtn.style.backgroundColor = '#fee2e2'; };
    clearBtn.onmouseout = () => { clearBtn.style.backgroundColor = 'transparent'; };
    clearBtn.onclick = () => { instance.clear(); instance.close(); applyFilters(); };

    // Hover effect para sa TODAY
    const todayBtn = footer.querySelector('.btn-today');
    todayBtn.onmouseover = () => { todayBtn.style.backgroundColor = '#f1f5f9'; todayBtn.style.color = '#ef4444'; };
    todayBtn.onmouseout = () => { todayBtn.style.backgroundColor = 'transparent'; todayBtn.style.color = '#0f172a'; };
    todayBtn.onclick = () => { instance.setDate(new Date()); instance.close(); applyFilters(); };

    instance.calendarContainer.appendChild(footer);
}
    });

    // Observer for dynamic table rows
    const observer = new MutationObserver(() => {
        applyFilters();
        lucide.createIcons();
    });
    observer.observe(tableBody, { childList: true });

    searchInput.addEventListener('input', () => { currentPage = 1; applyFilters(); });

    // Image Modal functions
    function openImageModal(src, title, location) {
        document.getElementById('modalImageSource').src = src;
        document.getElementById('modalTitle').innerHTML = `
            <div class="text-center">
                <div class="text-white font-black uppercase tracking-widest text-lg">${title || 'LOG'}</div>
                <div class="text-red-500 text-[12px] mt-1 italic font-bold">📍 ${location || 'No Location'}</div>
            </div>`;
        document.getElementById('globalImageModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeImageModal() {
        document.getElementById('globalImageModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    document.getElementById('globalImageModal').addEventListener('click', function(e) {
        if (e.target === this) closeImageModal();
    });
</script>