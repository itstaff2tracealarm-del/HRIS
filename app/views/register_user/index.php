<?php 
    include __DIR__ . '/../../includes/header.php'; 
    include __DIR__ . '/../../includes/left.php'; 
?>

<!-- Google Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    :root {
        --tassi-red: #911a1a;
        --tassi-navy: #0f172a;
        --bg-slate: #f8fafc;
        --slate-400: #94a3b8;
        --slate-500: #64748b;
        --slate-200: #e2e8f0;
        --slate-100: #f1f5f9;
        --hover-light: #f9fafb;
    }

    body { font-family: 'Inter', sans-serif; background-color: var(--bg-slate); margin: 0; }

    /* Custom Scrollbar for Table */
    .overflow-x-auto::-webkit-scrollbar { height: 6px; }
    .overflow-x-auto::-webkit-scrollbar-track { background: transparent; }
    .overflow-x-auto::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .overflow-x-auto::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

    /* Action Buttons Transitions */
    .action-btn-trigger { opacity: 0.6; transition: all 0.2s ease; }
    tr:hover .action-btn-trigger { opacity: 1; }
</style>

<main id="main-content" class="flex-1 overflow-y-auto p-6 lg:p-10 sidebar-transition bg-slate-50">

    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
        <a href="dashboard.php" class="hover:text-red-600 transition flex items-center gap-1.5">
            <i data-lucide="layout-dashboard" class="w-3 h-3"></i> Dashboard
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-900 flex items-center gap-1.5">
            <i data-lucide="users" class="w-3 h-3 text-red-600"></i> User Access Lists
        </span>
    </div>

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">User Access Lists</h1>
            <p class="text-slate-500 text-sm font-medium italic">TASSI User Management System</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="register_newuser.php?a=create" class="flex items-center gap-2 px-5 py-2.5 bg-slate-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-red-800 transition shadow-lg shadow-slate-200">
                <i data-lucide="plus" class="w-3.5 h-3.5"></i> Create New Access
            </a>
        </div>
    </div>

    <!-- Main Table Container -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-10">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm border-collapse">
                <!-- Table Header -->
                <thead class="bg-[#991b1b] text-[10px] font-bold text-white uppercase tracking-widest">
                    <tr>
                        <th class="px-6 py-4">User Information</th>
                        <th class="px-6 py-4">Username</th>
                        <th class="px-6 py-4 text-center">Access Level</th>
                        <th class="px-6 py-4 text-center">Live Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                
                <!-- Table Body -->
                <tbody id="userTableBody" class="divide-y divide-slate-50">
                    <?php if (!empty($users)): foreach ($users as $user): ?>
                    <tr class="user-row hover:bg-slate-50/50 transition-colors group">
                        <!-- User Info -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <?php if (!empty($user['photo'])): ?>
                                        <img src="uploads/profile/<?= $user['photo'] ?>" class="w-10 h-10 rounded-xl object-cover border-2 border-slate-100 group-hover:border-red-200 transition-colors" alt="Profile">
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-black text-slate-400 group-hover:bg-red-50 group-hover:text-red-600 transition-colors">
                                            <?= strtoupper(substr($user['first_name'] ?? 'U', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p class="font-black text-slate-800 uppercase tracking-tight text-sm group-hover:text-red-600 transition-colors">
                                        <?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? '')) ?>
                                    </p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                                        <?= strtoupper($user['department'] ?? 'No Dept') ?> • <?= strtoupper($user['role'] ?? 'Staff') ?>
                                    </p>
                                </div>
                            </div>
                        </td>

                        <!-- Username -->
                        <td class="px-6 py-4">
                            <span class="text-[10px] font-black text-slate-600 uppercase">
                                <?= htmlspecialchars($user['username'] ?? '---') ?>
                            </span>
                        </td>

                        <!-- Access Level -->
                        <td class="px-6 py-4 text-center">
                            <?php 
                                $role = strtolower($user['role'] ?? 'user');
                                $roleClasses = ($role == 'admin') ? 'text-red-600 bg-red-50' : 'text-slate-600 bg-slate-100';
                            ?>
                            <span class="text-[10px] font-black uppercase tracking-tighter px-3 py-1 rounded-full border border-transparent <?= $roleClasses ?>">
                                <?= strtoupper($role) ?>
                            </span>
                        </td>

                        <!-- Status -->
                        <!-- <td class="px-6 py-4 text-center">
                            <?php $isOnline = ((int)($user['login_status'] ?? 0) === 1); ?>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border <?= $isOnline ? 'bg-green-50 text-green-600 border-green-100' : 'bg-slate-50 text-slate-400 border-slate-100' ?>">
                                <span class="w-1.5 h-1.5 rounded-full <?= $isOnline ? 'bg-green-600 animate-pulse' : 'bg-slate-400' ?>"></span>
                                <span class="text-[9px] font-black uppercase tracking-widest">
                                    <?= $isOnline ? 'ACTIVE' : 'OFFLINE' ?>
                                </span>
                            </div>
                        </td> -->
                        <!-- Sa loob ng iyong foreach ($users as $user) loop -->
<td class="px-6 py-4 text-center">
    <?php 
        // Tinitingnan kung ang login_status ay 1 (Active) o 0 (Offline)
        $isOnline = (isset($user['login_status']) && (int)$user['login_status'] === 1); 
    ?>
    <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full border transition-all duration-300 
        <?= $isOnline ? 'bg-green-50 text-green-600 border-green-100' : 'bg-slate-50 text-slate-400 border-slate-100' ?>">
        
        <!-- Status Dot na may pulse effect kapag online -->
        <span class="relative flex h-2 w-2">
            <?php if ($isOnline): ?>
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
            <?php endif; ?>
            <span class="relative inline-flex rounded-full h-2 w-2 <?= $isOnline ? 'bg-green-600' : 'bg-slate-400' ?>"></span>
        </span>
        
        <span class="text-[10px] font-bold uppercase tracking-wider">
            <?= $isOnline ? 'Active Now' : 'Offline' ?>
        </span>
    </div>
</td>

                        <!-- Actions (Centered) -->
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-1">
                                <a href="register_newuser.php?a=edit&id=<?= $user['id'] ?>" class="action-btn-trigger p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg" title="Edit">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                </a>
                                <!-- <a href="register_newuser.php?a=delete&id=<?= $user['id'] ?>" class="action-btn-trigger p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg" onclick="return confirm('Are you sure?')" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </a> -->
                            <button type="button" 
                                    class="action-btn-trigger p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg" 
                                    onclick="openDeleteModal('<?= $user['id'] ?>', '<?= htmlspecialchars($user['first_name'] . ' ' . $user['last_name']) ?>')"
                                    title="Delete">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="5" class="px-6 py-20 text-center">
                            <div class="flex flex-col items-center opacity-20">
                                <i data-lucide="user-x" class="w-12 h-12 mb-2 text-slate-900"></i>
                                <p class="text-[10px] font-black uppercase tracking-widest italic text-slate-900">No User Records Found</p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Footer Pagination -->
        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest italic">
                Showing <span id="showingCount" class="text-slate-900 font-black">0</span> 
                of <span class="text-slate-900 font-black"><?= count($users) ?></span> 
                User Access Records
            </p>
            <div id="paginationControls" class="flex gap-1.5">
                <!-- Buttons generated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- TASSI Footer -->
    <footer class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
        <span>© 2026 Trace Alarm & Security System, Inc. (TASSI)</span>
        <div class="w-1 h-1 bg-slate-300 rounded-full"></div>
        <span class="text-slate-500">Republic of the Philippines</span>
    </footer>
</main>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 z-[9999] hidden">
    <!-- Backdrop: Nilagyan ko ng onclick para mas madaling i-close -->
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDeleteModal()"></div>
    
    <!-- Modal Content -->
    <div class="flex min-h-full items-center justify-center p-4">
        <div class="relative bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden p-10 transform transition-all">
            <div class="flex flex-col items-center text-center">
                <!-- Icon -->
                <div class="w-20 h-20 bg-red-50 rounded-3xl flex items-center justify-center mb-6">
                    <i data-lucide="trash-2" class="w-10 h-10 text-red-600"></i>
                </div>
                
                <h3 class="text-2xl font-black text-slate-900 uppercase tracking-tight mb-2">Revoke Access?</h3>
                <p class="text-sm text-slate-500 font-medium italic mb-8 leading-relaxed">
                    You are about to remove <span id="deleteUserName" class="text-red-600 font-black not-italic"></span> from the system. This user will no longer be able to log in.
                </p>

                <!-- Actions -->
                <div class="flex gap-3 w-full">
                    <button onclick="closeDeleteModal()" class="flex-1 px-6 py-4 bg-slate-100 text-slate-500 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-slate-200 transition">
                        Cancel
                    </button>
                    <!-- Mas maganda kung Form ito, pero kung direct link ang system mo, okay na ito: -->
                    <a id="confirmDeleteBtn" href="#" class="flex-1 px-6 py-4 bg-red-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] hover:bg-red-700 transition shadow-lg shadow-red-200 text-center">
                        Confirm Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    // Initialize Lucide
    lucide.createIcons();

    // Pagination Settings
    const rowsPerPage = 10; 
    let currentPage = 1;
    const tableBody = document.getElementById('userTableBody');
    const allRows = Array.from(tableBody.getElementsByClassName('user-row'));

    function updateTable() {
        const totalRows = allRows.length;
        const totalPages = Math.ceil(totalRows / rowsPerPage);
        
        // Safety check for empty table
        if(totalRows === 0) return;

        const start = (currentPage - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        allRows.forEach((row, index) => {
            if (index >= start && index < end) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });

        // Update Text
        const currentlyShowing = allRows.filter(r => r.style.display !== "none").length;
        document.getElementById('showingCount').innerText = currentlyShowing;
        
        renderPagination(totalPages);
    }

    function renderPagination(totalPages) {
        const wrapper = document.getElementById('paginationControls');
        wrapper.innerHTML = "";

        if (totalPages <= 1) return;

        // Previous Button
        const prevBtn = document.createElement('button');
        prevBtn.innerHTML = '<i data-lucide="chevron-left" class="w-4 h-4"></i>';
        prevBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg border transition ${currentPage === 1 ? 'opacity-30 cursor-not-allowed text-slate-300' : 'bg-white border-slate-200 text-slate-600 hover:text-red-600 shadow-sm'}`;
        prevBtn.onclick = () => { if(currentPage > 1) { currentPage--; updateTable(); }};
        wrapper.appendChild(prevBtn);

        // Page Numbers
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
                updateTable();
                window.scrollTo({ top: 0, behavior: 'smooth' }); // Optional: scroll to top
            };
            wrapper.appendChild(btn);
        }

        // Next Button
        const nextBtn = document.createElement('button');
        nextBtn.innerHTML = '<i data-lucide="chevron-right" class="w-4 h-4"></i>';
        nextBtn.className = `w-8 h-8 flex items-center justify-center rounded-lg border transition ${currentPage === totalPages ? 'opacity-30 cursor-not-allowed text-slate-300' : 'bg-white border-slate-200 text-slate-600 hover:text-red-600 shadow-sm'}`;
        nextBtn.onclick = () => { if(currentPage < totalPages) { currentPage++; updateTable(); }};
        wrapper.appendChild(nextBtn);

        lucide.createIcons();
    }

    // Run on load
    updateTable();

function openDeleteModal(userId, userName) {
    const modal = document.getElementById('deleteModal');
    const nameSpan = document.getElementById('deleteUserName');
    const deleteBtn = document.getElementById('confirmDeleteBtn');

    nameSpan.innerText = userName;
    // Siguraduhin na tama ang URL path mo dito
    deleteBtn.href = `register_newuser.php?a=delete&id=${userId}`;

    modal.classList.remove('hidden');
    // Smooth fade in (Optional: add CSS transitions)
    document.body.style.overflow = 'hidden'; 
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto'; 
}

// Close on 'Escape' key
document.addEventListener('keydown', function(event) {
    if (event.key === "Escape") {
        closeDeleteModal();
    }
});
</script>