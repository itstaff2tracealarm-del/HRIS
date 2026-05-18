<?php 
    include __DIR__ . '/../../includes/header.php'; 
    include __DIR__ . '/../../includes/left.php'; 
?>

<!-- Select2 & Fonts -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body { background-color: #f8fafc; margin: 0; padding: 0; font-family: 'Inter', sans-serif; }
    
    /* Input Overrides */
    .custom-input { 
        width: 100%; padding: 14px 20px; background-color: #f1f5f9; border: 0; 
        border-radius: 1rem; font-weight: 600; color: #1e293b; outline: none; transition: all 0.2s;
    }
    .custom-input:focus { background-color: #fff; ring: 4px; ring-color: #f1f5f9; box-shadow: 0 0 0 4px #f1f5f9; }

    /* Select2 Overrides para magmukhang Tailwind */
    .select2-container--default .select2-selection--single { 
        height: 52px; border: 0; border-radius: 1rem; padding: 12px; background-color: #f1f5f9; font-weight: 700;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 50px; }

    /* Preview Card Styles */
    .preview-img-container {
        width: 130px; height: 130px; border-radius: 2.5rem; background: #f8fafc;
        border: 4px solid white; box-shadow: 0 10px 25px rgba(0,0,0,0.05);
        display: flex; align-items: center; justify-content: center; overflow: hidden;
    }
    .preview-img-container img { width: 100%; height: 100%; object-fit: cover; }
    
    .pw-status-msg { font-[10px] font-black uppercase tracking-tight mt-2; }
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
            <i data-lucide="user-plus" class="w-3 h-3 text-red-600"></i> Register New User
        </span>
    </div>    

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Register New User</h1>
                <p class="text-slate-500 text-sm font-medium italic uppercase">Grant system access to active employees
            </div>
        </div>
        <a href="register_newuser.php?a=index" class="btn btn-outline-secondary btn-sm rounded-pill px-4 font-bold uppercase text-[10px] tracking-widest shadow-sm border-slate-200">
            <i class="fas fa-arrow-left me-2"></i> Back to Lists
        </a>
    </div>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        
        <!-- LEFT COLUMN: Registration Form -->
        <div class="w-full lg:w-[65%] bg-white rounded-[2.5rem] border border-slate-100 shadow-sm p-8 lg:p-10">
            <form action="register_newuser.php?a=store" method="POST" id="registrationForm">
                <input type="hidden" name="first_name" id="hidden_first_name" required>
                <input type="hidden" name="last_name" id="hidden_last_name" required>

                <!-- Employee Selection -->
                <div class="mb-10">
                    <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-500 mb-3 px-1 italic">
                        Select Employee Record <span class="text-red-600">*</span>
                    </label>
                    <select id="employee_select" name="employee_name" class="select2-search" required>
                        <option value="" disabled selected hidden>-- Search Employee Name --</option>
                        <?php if (!empty($employees)): foreach ($employees as $emp): 
                            $fullName = $emp['first_name'] . ' ' . $emp['last_name'];
                            $photo = $emp['ID_filename'] ?? '';
                        ?>
                            <option value="<?= htmlspecialchars($fullName) ?>" 
                                    data-fname="<?= htmlspecialchars($emp['first_name']) ?>" 
                                    data-lname="<?= htmlspecialchars($emp['last_name']) ?>"
                                    data-photo="<?= htmlspecialchars($photo) ?>">
                                <?= htmlspecialchars($fullName) ?>
                            </option>
                        <?php endforeach; endif; ?>
                    </select>
                </div>

                <!-- Account Credentials -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-500 mb-2 px-1">Username</label>
                        <input type="text" name="username" class="custom-input" placeholder="e.g. trace_user" required autocomplete="off">
                    </div>
                    <div>
                        <label class="block text-[11px] font-black uppercase tracking-[0.2em] text-slate-500 mb-2 px-1">Access Level</label>
                        <select name="role" id="role_select" class="custom-input appearance-none cursor-pointer" required>
                            <option value="" disabled selected hidden>-- Select Role --</option>
                            <option value="it">IT User</option>
                            <option value="hr">HR Staff</option>
                            <option value="admin">Administrator</option>
                        </select>
                    </div>
                </div>

                <!-- Security Section -->
                <div class="mt-12 p-8 rounded-[2rem] border-2 border-dashed border-slate-100 bg-white">
                    <h3 class="text-[11px] font-black uppercase tracking-[0.2em] text-slate-900 mb-6 flex items-center gap-2">
                        <i data-lucide="shield-check" class="w-4 h-4 text-red-600"></i> Security Setup
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-2">Password</label>
                            <input type="password" id="password" name="password" class="w-full px-5 py-3 bg-slate-50 border-0 rounded-xl text-sm" placeholder="••••••••" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold uppercase text-slate-400 mb-2">Confirm Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="w-full px-5 py-3 bg-slate-50 border-0 rounded-xl text-sm" placeholder="••••••••" required>
                            <div id="password_match_msg" class="pw-status-msg px-1"></div>
                        </div>
                    </div>
                </div>

                <div class="mt-10 flex justify-end gap-3">
                    <button type="reset" class="px-8 py-3.5 font-black uppercase text-[11px] tracking-[0.2em] text-slate-400 hover:text-slate-600 transition-all">
                        Clear Form
                    </button>
                    <button type="submit" id="submitBtn" class="bg-slate-900 text-white shadow-xl shadow-slate-200 rounded-2xl px-12 py-3.5 font-black uppercase text-[11px] tracking-[0.2em] hover:bg-red-800 transition-all active:scale-95">
                        Register Account
                    </button>
                </div>
            </form>
        </div>

        <!-- RIGHT COLUMN: Identity Preview -->
        <div class="w-full lg:w-[35%] lg:sticky lg:top-10">
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden p-10 text-center">
                <div class="flex flex-col items-center mb-10">
                    <div id="preview-image-container" class="preview-img-container mb-6">
                        <i data-lucide="user" class="w-16 h-16 text-slate-200"></i>
                    </div>
                    <h2 id="preview-name" class="text-xl font-black text-slate-900 uppercase tracking-tight">Select Employee</h2>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Identity Preview</p>
                </div>

                <div class="h-[1px] bg-slate-100 w-full mb-8"></div>

                <div class="space-y-6 text-left px-2">
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Assigned Level</span>
                        <span id="preview-role-text" class="text-[11px] font-black text-slate-700 uppercase">---</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Initial Status</span>
                        <div class="flex items-center gap-2">
                            <i data-lucide="circle" class="w-2 h-2 fill-green-500 text-green-500"></i>
                            <span class="text-[11px] font-black text-green-600 uppercase">Ready</span>
                        </div>
                    </div>
                </div>

                <div class="mt-10 p-6 bg-slate-50 rounded-3xl border border-slate-100 italic">
                    <p class="text-[10px] text-slate-400 font-medium leading-relaxed">
                        Please ensure the username follows the company standard (e.g., trace_user) before proceeding.
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    lucide.createIcons();

    $(document).ready(function() {
        lucide.createIcons();

        $('#employee_select').select2({ width: '100%' });

        // Update Preview based on Selection
        $('#employee_select').on('change', function() {
            const selected = $(this).find(':selected');
            const fname = selected.data('fname');
            const lname = selected.data('lname');
            const photo = selected.data('photo');

            if (fname) {
                $('#hidden_first_name').val(fname);
                $('#hidden_last_name').val(lname);
                $('#preview-name').text(fname + ' ' + lname);

                const imgContainer = $('#preview-image-container');
                if(photo && photo !== "") {
                    imgContainer.html(`<img src="uploads/profile/${photo}" class="w-full h-full object-cover">`);
                } else {
                    imgContainer.html(`<i data-lucide="user" class="w-16 h-16 text-slate-200"></i>`);
                    lucide.createIcons();
                }
            }
        });

        $('#role_select').on('change', function() {
            let roleText = $(this).find(':selected').text();
            $('#preview-role-text').text(roleText);
        });

        // Password Match Validation
        function validatePassword() {
            const pass = $('#password').val();
            const conf = $('#confirm_password').val();
            const msg = $('#password_match_msg');
            const btn = $('#submitBtn');

            if (conf === "") { msg.text(""); return; }

            if (pass === conf) {
                msg.html('<i class="fas fa-check-circle"></i> Passwords Match').css('color', '#10b981');
                btn.prop('disabled', false);
            } else {
                msg.html('<i class="fas fa-times-circle"></i> Passwords do not match').css('color', '#ef4444');
                btn.prop('disabled', true);
            }
        }

        $('#password, #confirm_password').on('keyup', validatePassword);

        // Handle Reset
        $('button[type="reset"]').on('click', function() {
            setTimeout(() => {
                $('#employee_select').val(null).trigger('change');
                $('#preview-name').text('Select Employee');
                $('#preview-role-text').text('---');
                $('#preview-image-container').html('<i data-lucide="user" class="w-16 h-16 text-slate-200"></i>');
                $('#password_match_msg').text('');
                lucide.createIcons();
            }, 10);
        });
    });
</script>