<?php
    // Siguraduhin na may session values
    $displayUser = $_SESSION['user_name'] ?? $_SESSION['full_name'] ?? 'User';
    $displayRole = $_SESSION['user_role'] ?? $_SESSION['role'] ?? 'Staff';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>REDCELL | Unified Command</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono&display=swap');
        body { font-family: 'Inter', sans-serif; overflow: hidden; }
        .mono { font-family: 'JetBrains Mono', monospace; }
        /* Smooth transition for sidebar and main content */
        .sidebar-transition { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .hide-scrollbar::-webkit-scrollbar { display: none; }

        /* Custom Dropdown Styling */
        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 110%;
            width: 180px;
            background-color: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            z-index: 100;
            padding: 8px;
            transform-origin: top right;
            animation: dropdownFade 0.2s ease-out;
        }
        .dropdown-menu.show {
            display: block;
        }
        @keyframes dropdownFade {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }

        /* Modal Backdrop */
        #logoutModal { display: none; }
        #logoutModal.flex { display: flex; }
    </style>

    <script>
        // Session Check Logic
        function checkLiveSession() {
            fetch('check_session.php')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'expired') {
                        window.location.href = 'index.php?error=session_expired';
                    }
                })
                .catch(err => console.error('Check failed:', err.message));
        }
        setInterval(checkLiveSession, 5000);

        // Sidebar Toggle Logic
        function toggleSidebar() {
            const sidebar = document.getElementById('main-sidebar');
            if (sidebar) {
                sidebar.classList.toggle('collapsed');
            }
        }

        // Dropdown Toggle Function
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // --- DAGDAG: Modal Controls ---
        function showLogoutModal(event) {
            event.preventDefault();
            document.getElementById('logoutModal').classList.add('flex');
            document.getElementById('userDropdown').classList.remove('show'); // Close dropdown
        }

        function hideLogoutModal() {
            document.getElementById('logoutModal').classList.remove('flex');
        }

        function proceedLogout() {
            window.location.href = "/phphr-main/phphr-main/public/index.php?a=logout";
        }
        // --- END NG DAGDAG ---

        // Close dropdown when clicking outside
        window.addEventListener('click', function(e) {
            const dropdown = document.getElementById('userDropdown');
            const profileBtn = document.getElementById('profileBtn');
            if (dropdown && !profileBtn.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.remove('show');
            }
        });
    </script>
</head>
<body class="bg-[#fcfcfc] text-slate-800 h-screen flex flex-col">

    <!-- DAGDAG: Custom Logout Modal -->
    <div id="logoutModal" class="fixed inset-0 z-[9999] bg-slate-900/40 backdrop-blur-sm items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full p-6 text-center animate-in fade-in zoom-in duration-200">
            <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="log-out" class="w-8 h-8"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-900 mb-2">Ready to Leave?</h3>
            <p class="text-slate-500 mb-6 text-sm">Are you sure you want to end your current session?</p>
            <div class="flex gap-3">
                <button onclick="hideLogoutModal()" class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 text-slate-600 font-semibold hover:bg-slate-50 transition-colors">Cancel</button>
                <button onclick="proceedLogout()" class="flex-1 px-4 py-2.5 rounded-xl bg-red-600 text-white font-semibold hover:bg-red-700 shadow-lg shadow-red-200 transition-all">Logout</button>
            </div>
        </div>
    </div>
    <!-- END NG DAGDAG -->

    <nav class="h-16 border-b border-slate-200 bg-white flex items-center px-4 shrink-0 z-50 sticky top-0 shadow-sm">
        
        <div class="flex items-center w-64 shrink-0">
            <button onclick="toggleSidebar()" class="p-2 mr-3 rounded-lg hover:bg-slate-100 text-slate-600 transition-colors">
                <i data-lucide="menu" class="w-5 h-5"></i>
            </button>
            
            <div class="flex items-center gap-2">
                <div class="bg-red-600 p-1.5 rounded-lg shadow-sm">
                    <i data-lucide="shield-alert" class="text-white w-4 h-4"></i>
                </div>
                <span class="font-bold text-lg tracking-tight text-slate-900 uppercase italic">TASSI<span class="text-red-600">PORTAL</span></span>
            </div>
        </div>

        <div class="flex-1 px-8 hidden md:block border-l border-slate-100 ml-2">
            <div class="relative max-w-md">
                <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400"></i>
                <input type="text" placeholder="Search system resources..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-red-500/20 focus:border-red-600 transition-all">
            </div>
        </div>

        <div class="flex items-center gap-4 ml-auto">
            
            <div class="text-right hidden sm:block border-r border-slate-200 pr-4">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">
                    <?= htmlspecialchars($displayRole) ?>
                </p>
                <p class="text-xs font-semibold text-slate-700">
                    <?= htmlspecialchars($displayUser) ?>
                </p>
            </div>  

            <div class="flex items-center gap-2">
                <button class="p-2 text-slate-400 hover:text-red-600 transition relative">
                    <i data-lucide="bell" class="w-5 h-5"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-600 rounded-full border-2 border-white"></span>
                </button>
                
                <div class="relative">
                    <div id="profileBtn" onclick="toggleUserDropdown()" class="w-9 h-9 rounded-full bg-slate-900 flex items-center justify-center text-white cursor-pointer hover:bg-red-700 transition shadow-md border-2 border-white">
                        <span class="text-xs font-bold uppercase"><?= strtoupper(substr($displayUser, 0, 1)) ?></span>
                    </div>

                    <div id="userDropdown" class="dropdown-menu">
                        <div class="px-3 py-2 border-b border-slate-100 mb-1">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">My Account</p>
                        </div>
                        <!-- Binago: Tinawag ang showLogoutModal function -->
                        <a href="#" onclick="showLogoutModal(event)" class="flex items-center gap-2 px-3 py-2 text-xs font-bold text-red-600 hover:bg-red-50 rounded-lg transition">
                            <i data-lucide="log-out" class="w-4 h-4"></i> Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden relative">

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();
    </script>
</body>
</html>