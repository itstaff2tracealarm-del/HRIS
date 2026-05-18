<?php 
  $currentPage = basename($_SERVER['PHP_SELF']);
  $userRole = strtolower($_SESSION['user_role'] ?? $_SESSION['role'] ?? 'staff'); 
?>

<style>
    /* --- SIDEBAR BASE - HARD LOCKED --- */
    #main-sidebar {
        background-color: #ffffff;
        /* I-lock ang width gamit ang min at max para laging pareho ang sukat sa lahat ng page */
        width: 260px !important;
        min-width: 260px !important;
        max-width: 260px !important;
        height: 100vh;
        border-right: 2px solid black;
        display: flex;
        flex-direction: column;
        padding: 1.5rem 0.75rem;
        transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: sticky;
        top: 0;
        z-index: 1000; /* Tinaasan para hindi matabunan ng ibang elements */
        overflow-x: hidden;
        /* Mahalaga ito para hindi lumaki ang sidebar dahil sa padding */
        box-sizing: border-box !important;
    }

    #main-sidebar.collapsed {
        width: 85px !important;
        min-width: 85px !important;
        max-width: 85px !important;
    }

    #main-sidebar.collapsed .sidebar-text,
    #main-sidebar.collapsed .nav-text-label,
    #main-sidebar.collapsed .custom-arrow,
    #main-sidebar.collapsed .badge-count {
        display: none !important;
    }

    /* --- NAV LINKS --- */
    .nav-text-label {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.12em;
        color: #8f9cad;
        font-weight: 700;
        margin: 1.5rem 0 0.5rem 1rem;
        white-space: nowrap;
    }

    .custom-nav-link {
        display: flex;
        align-items: center;
        padding: 0.75rem 1rem;
        color: #475569;
        text-decoration: none;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 0.75rem;
        transition: all 0.2s ease;
        margin-bottom: 0.25rem;
        gap: 15px;
        white-space: nowrap;
        position: relative;
        border: 2px solid transparent;
        box-sizing: border-box !important;
    }

    #main-sidebar.collapsed .custom-nav-link {
        justify-content: center;
        padding: 0.75rem 0;
        gap: 0;
    }

    /* Sinisiguro natin ang container size para hindi mapiga sa ibang page */
    #main-sidebar .custom-nav-link i {
        width: 24px !important;
        height: 24px !important;
        min-width: 24px !important;
        min-height: 24px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        flex-shrink: 0 !important;
    }

    /* Hard-lock sa sukat at kapal ng SVG */
    #main-sidebar .custom-nav-link i svg,
    #main-sidebar .custom-nav-link svg {
        width: 20px !important;
        height: 20px !important;
        min-width: 20px !important;
        min-height: 20px !important;
        stroke-width: 3.2px !important; 
        display: block !important;
        color: inherit !important; 
    }

    #main-sidebar .custom-nav-link i[data-lucide="refresh-cw"] svg,
    #main-sidebar .custom-nav-link svg.lucide-refresh-cw {
        stroke-width: 3.8px !important;
    }

    .custom-nav-link:hover i,
    .custom-nav-link.active i {
        color: #dc2626 !important;
    }

    .custom-nav-link:hover {
        background-color: #f8fafc;
        color: #dc2626;
    }

    .custom-nav-link.active {
        background-color: #fef2f2;
        color: #dc2626;
        border-color: transparent;
    }

    /* --- DROPDOWN LOGIC --- */
    .dropdown-wrapper {
        position: relative;
    }

    .sub-menu-container {
        margin-left: 1.5rem;
        border-left: 2px solid #f1f5f9;
        padding-left: 1rem;
        margin-top: 0.25rem;
        margin-bottom: 0.5rem;
    }

    .sub-menu-container.hidden {
        display: none;
    }

    /* 🔥 FLOATING MENU PARA SA COLLAPSED STATE 🔥 */
    #main-sidebar.collapsed .dropdown-wrapper:hover .sub-menu-container {
        display: block !important;
        position: absolute;
        left: 75px;
        top: 0;
        background: white;
        width: 210px;
        border: 2px solid black;
        border-radius: 12px;
        box-shadow: 8px 8px 0px rgba(0,0,0,1);
        margin-left: 0;
        padding: 10px;
        z-index: 1000;
    }

    .sub-link {
        display: block;
        padding: 0.65rem 0.85rem;
        font-size: 0.85rem;
        color: #64748b;
        text-decoration: none;
        border-radius: 8px;
        transition: 0.2s;
        margin-bottom: 2px;
        font-weight: 500;
    }

    .sub-link:hover, .sub-link.active {
        background-color: #fef2f2;
        color: #dc2626;
        font-weight: 700;
    }

    .custom-arrow {
        transition: transform 0.2s ease;
    }
    .rotate-180 {
        transform: rotate(180deg);
    }
</style>

<aside id="main-sidebar">
    <p class="nav-text-label">Core Features</p>
    
    <a href="dashboard.php" class="custom-nav-link <?= $currentPage == 'dashboard.php' ? 'active' : '' ?>" title="Dashboard Overview">
        <i data-lucide="layout-grid"></i>
        <span class="sidebar-text">Dashboard</span>
    </a>

    <a href="#" class="custom-nav-link d-flex justify-content-between" title="System Messages">
        <div class="flex items-center gap-[15px]">
            <i data-lucide="mail"></i>
            <span class="sidebar-text">Messages</span>
        </div>
        <span class="badge-count bg-red-600 text-white text-[10px] px-2 py-0.5 rounded-full">13</span>
    </a>

    <p class="nav-text-label">Human Resources</p>

    <div class="dropdown-wrapper">
        <?php 
            $isEmpPage = (strpos($currentPage, 'employees.php') !== false);
            $currentAction = $_GET['a'] ?? '';
        ?>
        <button onclick="toggleSidebarDropdown('empMenu', this)" class="w-full custom-nav-link flex justify-between <?= $isEmpPage ? 'active' : '' ?>" title="Employee Management">
            <div class="flex items-center gap-[15px]">
                <i data-lucide="users"></i>
                <span class="sidebar-text">Employees</span>
            </div>
            <i data-lucide="chevron-down" class="custom-arrow w-3 h-3 <?= $isEmpPage ? 'rotate-180' : '' ?>"></i>
        </button>
        <div id="empMenu" class="<?= $isEmpPage ? '' : 'hidden' ?> sub-menu-container">
            <a href="employees.php?a=index" class="sub-link <?= ($currentAction == 'index' || $currentAction == '') ? 'active' : '' ?>">Active List</a>
            <a href="employees.php?a=archived" class="sub-link <?= ($currentAction == 'archived') ? 'active' : '' ?>">Inactive List</a>
            <a href="employees.php?a=falcobiolist" class="sub-link <?= ($currentAction == 'falcobiolist') ? 'active' : '' ?>">Falco List</a>
        </div>
    </div>

    <a href="mergeAttendance.php" class="custom-nav-link <?= $currentPage == 'mergeAttendance.php' ? 'active' : '' ?>">
        <i data-lucide="clock"></i>
        <span class="sidebar-text">Office Attendance</span>
    </a>

    <a href="leaves.php" class="custom-nav-link <?= $currentPage == 'leaves.php' ? 'active' : '' ?>">
        <i data-lucide="calendar-days"></i>
        <span class="sidebar-text">Leaves</span>
    </a>

    <p class="nav-text-label">Integrations</p>
    <a href="syncAttendance.php" class="custom-nav-link <?= $currentPage == 'syncAttendance.php' ? 'active' : '' ?>">
        <i data-lucide="refresh-cw"></i>
        <span class="sidebar-text">Technical Attendance</span>
    </a>

    <?php if ($userRole === 'admin' || $userRole === 'it'): ?>
        <p class="nav-text-label">Administration</p>
        <a href="payroll.php" class="custom-nav-link <?= $currentPage == 'payroll.php' ? 'active' : '' ?>">
            <i data-lucide="wallet"></i>
            <span class="sidebar-text">Payroll System</span>
        </a>
        <a href="register_newuser.php?a=index" class="custom-nav-link <?= strpos($currentPage, 'register_newuser.php') !== false ? 'active' : '' ?>">
            <i data-lucide="user-cog"></i>
            <span class="sidebar-text">Manage Users</span>
        </a>
    <?php endif; ?>
</aside>

<script>
    function toggleSidebarDropdown(id, btn) {
        const sidebar = document.getElementById('main-sidebar');
        const menu = document.getElementById(id);
        const arrow = btn.querySelector('.custom-arrow');

        if (!sidebar.classList.contains('collapsed')) {
            menu.classList.toggle('hidden');
            if (arrow) arrow.classList.toggle('rotate-180');
        }
    }

    function initSidebarIcons() {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        } else {
            setTimeout(initSidebarIcons, 100);
        }
    }

    document.addEventListener('DOMContentLoaded', initSidebarIcons);
    window.onload = initSidebarIcons;
</script>
<script src="https://unpkg.com/lucide@latest"></script>