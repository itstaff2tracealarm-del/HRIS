<?php 
    include __DIR__ . '/../../includes/header.php'; 
    include __DIR__ . '/../../includes/left.php'; 
?>

<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { background-color: #f8f9fa; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
    
    /* Layout */
    .content-area { background: white; border-radius: 25px; padding: 30px; min-height: 90vh; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }

    /* Custom Status Badges (Account Status) */
    .status-badge-active { background: #d1fae5; color: #065f46; border: 1px solid #34d399; font-size: 0.7rem; padding: 4px 12px; border-radius: 50px; font-weight: 700; }
    .status-badge-inactive { background: #fee2e2; color: #991b1b; border: 1px solid #f87171; font-size: 0.7rem; padding: 4px 12px; border-radius: 50px; font-weight: 700; }

    /* Live Status Indicators (Preview Card) */
    .dot-active { color: #10b981; fill: #10b981; } 
    .dot-offline { color: #94a3b8; fill: #94a3b8; }
    .text-active { color: #059669; font-weight: 800; }
    .text-offline { color: #64748b; font-weight: 800; }

    /* Select2 Overrides */
    .select2-container--default .select2-selection--single { 
        height: 50px;  border: 0;  border-radius: 1rem;  padding: 10px; background-color: #f8fafc; font-weight: 600;
    }
</style>

<main id="main-content" class="flex-1 overflow-y-auto p-6 lg:p-10 sidebar-transition bg-slate-50">

    <!-- Breadcrumbs -->
    <div class="flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
        <a href="dashboard.php" class="hover:text-red-600 transition flex items-center gap-1.5">
            <i data-lucide="layout-dashboard" class="w-3 h-3"></i> Dashboard
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="register_newuser.php?a=index" class="hover:text-red-600 transition flex items-center gap-1.5">
            <i data-lucide="users" class="w-3 h-3"></i> User Access List
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-900 flex items-center gap-1.5">
            <i data-lucide="user-round-pen" class="w-3 h-3 text-red-600"></i> User Account Edit
        </span>
    </div>    

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">User Account Edit</h1>
            <p class="text-slate-500 text-sm font-medium italic uppercase">Modifying data for: <?= htmlspecialchars($user['last_name'] . ', ' . $user['first_name']) ?></p>
        </div>
        <a href="register_newuser.php?a=index" class="btn btn-outline-secondary btn-sm rounded-pill px-4 font-bold uppercase text-[10px] tracking-widest shadow-sm border-slate-200">
            <i class="fas fa-arrow-left me-2"></i> Back to Lists
        </a>
    </div>

    <?php if (isset($_SESSION['user_error'])): ?>
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-6 p-4 d-flex align-items-center" role="alert">
            <i class="fas fa-exclamation-triangle me-3"></i> 
            <span class="small font-bold uppercase tracking-tight"><?= $_SESSION['user_error']; unset($_SESSION['user_error']); ?></span>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        
        <!-- LEFT COLUMN: The Form -->
        <div class="w-full lg:w-[65%] bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 lg:p-10">
            <form action="register_newuser.php?a=update" method="POST" id="editForm">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">

                <!-- Linked Employee -->
                <div class="mb-10">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-3 italic px-1">Linked Employee Record</label>
                    <div class="flex items-center justify-between p-6 bg-slate-50 border border-slate-100 rounded-2xl shadow-inner">
                        <span class="font-black text-slate-800 tracking-tight text-xl">
                            <?= htmlspecialchars(strtoupper($user['first_name'] . ' ' . $user['last_name'])) ?>
                        </span>
                        <span class="px-3 py-1 bg-slate-200 text-slate-500 rounded-lg text-[9px] font-black tracking-tighter">READ-ONLY</span>
                    </div>
                </div>

                <!-- Form Controls -->
                <div class="grid grid-cols-1 gap-8">
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-500 mb-2 px-1">Username</label>
                        <input type="text" name="username" class="w-full px-5 py-3.5 bg-slate-50 border-0 rounded-2xl font-bold text-slate-700 focus:ring-4 focus:ring-slate-100 outline-none transition-all" value="<?= htmlspecialchars($user['username']) ?>" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-500 mb-2 px-1">Access Level</label>
                            <select name="role" id="role_select" class="w-full px-5 py-3.5 bg-slate-50 border-0 rounded-2xl font-bold text-slate-700 outline-none appearance-none cursor-pointer">
                                <option value="it" <?= ($user['role'] == 'it') ? 'selected' : '' ?>>IT User</option>
                                <option value="hr" <?= ($user['role'] == 'hr') ? 'selected' : '' ?>>HR Staff</option>
                                <option value="admin" <?= ($user['role'] == 'admin') ? 'selected' : '' ?>>Administrator</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-500 mb-2 px-1">Account Restriction</label>
                            <select name="status" id="status_select" class="w-full px-5 py-3.5 bg-slate-50 border-0 rounded-2xl font-bold text-slate-700 outline-none appearance-none cursor-pointer">
                                <option value="1" <?= ($user['status'] == 1) ? 'selected' : '' ?>>Active (Full Access)</option>
                                <option value="0" <?= ($user['status'] == 0) ? 'selected' : '' ?>>Inactive (Revoked)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Security Box -->
                <div class="mt-12 p-8 rounded-[2rem] border-2 border-dashed border-slate-100 bg-white">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-900 mb-6 flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4 text-red-600"></i> Security Override
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-2">New Password</label>
                            <input type="password" id="password" name="password" class="w-full px-5 py-3 bg-slate-50 border-0 rounded-xl text-sm" placeholder="••••••••">
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-2">Verify Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="w-full px-5 py-3 bg-slate-50 border-0 rounded-xl text-sm" placeholder="••••••••">
                            <div id="password_match_msg" class="pw-status-msg px-1 mt-2"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-end">
                    <button type="submit" id="submitBtn" class="bg-slate-900 text-white shadow-xl shadow-slate-200 rounded-2xl px-12 py-3.5 font-black uppercase text-[11px] tracking-[0.2em] hover:bg-red-800 transition-all active:scale-95">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        <!-- RIGHT COLUMN: Preview Card -->
        <div class="w-full lg:w-[35%] lg:sticky lg:top-10">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-10 text-center">
                <div class="flex flex-col items-center mb-10">
                    <div id="preview-image-container" class="w-36 h-36 bg-slate-50 rounded-[3rem] mb-6 flex items-center justify-center border-4 border-white shadow-xl overflow-hidden group">
                        <?php if(!empty($user['photo'])): ?>
                            <img src="uploads/profile/<?= $user['photo'] ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <i data-lucide="user" class="w-16 h-16 text-slate-200"></i>
                        <?php endif; ?>
                    </div>
                    <div id="preview-account-badge">
                        <!-- Dynamic Account Status Badge (Active/Inactive) -->
                    </div>
                </div>

                <div class="h-[1px] bg-slate-100 w-full mb-8"></div>

                <div class="space-y-6 text-left px-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Access</span>
                        <span id="preview-role-text" class="text-[11px] font-black text-slate-700 uppercase">
                            <?= strtoupper($user['role']) ?>
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Session Status</span>
                        <div class="flex items-center gap-2">
                            <?php $isOnline = (isset($user['login_status']) && $user['login_status'] == 1); ?>
                            <i data-lucide="circle" id="status-dot" class="w-2 h-2 fill-current <?= $isOnline ? 'dot-active' : 'dot-offline' ?>"></i>
                            <span id="preview-login-status" class="text-[11px] font-black uppercase <?= $isOnline ? 'text-active' : 'text-offline' ?>"> 
                                <?= $isOnline ? 'Online Now' : 'Offline' ?> 
                            </span>
                        </div>
                    </div>

                    <div class="mt-8 p-6 bg-slate-50 rounded-3xl border border-slate-100 text-center">
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-2 text-center w-full">
                            Last Seen Activity
                        </span>
                        <span id="preview-last-login-text" class="text-[11px] font-bold text-slate-600 italic block text-center w-full">
                            <?= !empty($user['last_login']) ? date('M d, Y | h:i A', strtotime($user['last_login'])) : 'No Recorded Session' ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize Lucide Icons
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        function updatePreview() {
            // 1. Update Access Level Text
            let roleText = $('#role_select option:selected').text();
            $('#preview-role-text').text(roleText);

            // 2. Update Account Badge (Restriction Status)
            const accountStatus = $('#status_select').val();
            const badgeContainer = $('#preview-account-badge');
            if(accountStatus == "1") {
                badgeContainer.html('<span class="status-badge-active">ACTIVE ACCOUNT</span>');
            } else {
                badgeContainer.html('<span class="status-badge-inactive">ACCOUNT REVOKED</span>');
            }

            // NOTE: Online/Offline status is NOT changed here anymore.
            // It remains based on the initial database value ($user['is_logged_in']).
        }

        $('#role_select, #status_select').on('change', updatePreview);
        updatePreview(); // Run once on page load

        // Password Validation Logic
        function validatePassword() {
            const pass = $('#password').val();
            const conf = $('#confirm_password').val();
            const msg = $('#password_match_msg');
            const btn = $('#submitBtn');

            if (conf === "" && pass === "") {
                msg.text("");
                btn.prop('disabled', false);
                return;
            }

            if (pass === conf) {
                msg.html('<i class="fas fa-check-circle"></i> Passwords Match').css('color', '#10b981').css('font-size', '10px').css('font-weight', 'bold');
                btn.prop('disabled', false);
            } else {
                msg.html('<i class="fas fa-times-circle"></i> Passwords do not match').css('color', '#ef4444').css('font-size', '10px').css('font-weight', 'bold');
                btn.prop('disabled', true);
            }
        }

        $('#password, #confirm_password').on('keyup', validatePassword);
    });
</script>