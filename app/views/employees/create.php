<?php 
    include __DIR__ . '/../../includes/header.php';
    include __DIR__ . '/../../includes/left.php';

    // Check if admin
    $isAdmin = (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin');
?>

<head>
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* GLOBAL FIX FOR SCROLLING */
        html, body { 
            background-color: #f8f9fa; 
            margin: 0; 
            padding: 0; 
            min-height: 100%;
            overflow-y: auto; 
        }
        
        .page-layout {
            display: flex;
            align-items: flex-start;
            padding: 20px;
            gap: 20px;
            width: 100%;
            box-sizing: border-box;
        }

        .main-wrapper {
            flex-grow: 1;
            min-width: 0; 
        }

        .content-area { 
            background: #ffffff;
            border-radius: 30px; 
            padding: 40px; 
            height: auto;
            min-height: 85vh; 
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border: 1px solid rgba(0,0,0,0.03);
            margin-bottom: 20px;
        }
        
        /* LAYOUT CONTAINER */
        .profile-container { 
            display: flex; 
            gap: 30px; 
            align-items: flex-start; 
            flex-wrap: nowrap;
        }
        
        .inner-sidebar { 
            flex: 0 0 300px; 
            background: #ffffff; 
            padding: 25px; 
            border-radius: 20px; 
            box-shadow: 0 4px 15px rgba(0,0,0,0.02); 
            /* border: 1px solid #eee; */
        }
        
        .tab-btn {
            width: 100%; text-align: left; border: none; padding: 14px 20px; border-radius: 12px; 
            margin-bottom: 8px; background: #f8f9fa; font-weight: 700; color: #64748b; cursor: pointer; transition: 0.3s;
            font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em;
            display: flex; align-items: center; gap: 10px;
        }
        .tab-btn i { width: 16px; height: 16px; }
        .tab-btn:hover { background: #f1f5f9; color: #1e293b; }
        .tab-btn.active { background: #e32133 !important; color: #ffffff !important; box-shadow: 0 4px 12px rgba(227, 33, 51, 0.2); }
        
        .profile-card { 
            flex-grow: 1; 
            background: #ffffff; 
            border-radius: 25px; 
            padding: 40px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
            position: relative; 
            min-height: 600px;
            border: 1px solid #f1f1f1;
        }

        /* AVATAR STYLES */
        .avatar-wrapper { 
            width: 150px; height: 150px; background: #f1f4f9; border-radius: 50%; 
            margin: 0 auto 30px; overflow: hidden; border: 5px solid #fff; 
            box-shadow: 0 8px 20px rgba(0,0,0,0.1); position: relative; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
        }
        /* .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; display: none; }
        
        .avatar-initials {
            width: 100%; height: 100%; background: #2e4494; color: #fff;
            display: flex; align-items: center; justify-content: center;
            font-size: 60px; font-weight: 800; text-transform: uppercase;
        } */
/* AVATAR STYLES */
.avatar-wrapper img { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    display: none; /* Hayaan nating naka-none muna, JS ang magpapakita */
    position: absolute;
    top: 0;
    left: 0;
}

/* Idagdag mo ito para siguradong laging nasa gitna ang initials */
.avatar-initials {
    width: 100%; 
    height: 100%; 
    background: #2e4494; 
    color: #fff;
    display: flex; 
    align-items: center; 
    justify-content: center;
    font-size: 60px; 
    font-weight: 800; 
    text-transform: uppercase;
    position: relative; /* Para hindi matabunan ng absolute img kung sakali */
}
        .upload-overlay {
            position: absolute; bottom: 0; width: 100%; background: rgba(0,0,0,0.6);
            color: #fff; font-size: 9px; text-align: center; padding: 10px 0;
            transition: 0.3s; font-weight: 800; opacity: 0;
        }
        .avatar-wrapper:hover .upload-overlay { opacity: 1; }

        /* FORM STYLE UTILITIES */
        .form-label-custom { color: #64748b; font-size: 0.7rem; font-weight: 800; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; display: block; }
        .form-input-custom { 
            width: 100%; border: 2px solid #f1f5f9; background: #f8fafc; padding: 12px 16px; 
            border-radius: 12px; margin-bottom: 20px; font-weight: 600; color: #1e293b;
            font-size: 0.9rem; transition: all 0.3s ease; box-sizing: border-box;
            text-transform: uppercase;
        }
        .form-input-custom:focus {
            outline: none; background: #fff; border-color: #2e4494; box-shadow: 0 0 0 4px rgba(46, 68, 148, 0.05);
        }

        /* TAB ANIMATION */
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* .uppercase-input { text-transform: uppercase; } */

        .side-action-btn { 
            display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 14px; border-radius: 12px; font-weight: 800; 
            text-align: center; text-decoration: none; transition: 0.3s; margin-top: 12px; 
            border: none; cursor: pointer; font-size: 0.8rem; text-transform: uppercase;
        }
        
        .btn-save-main { background: #2e4494; color: #fff; }
        .btn-save-main:hover { background: #1e2d63; color: #fff; }
        .btn-cancel-secondary { background: #f1f5f9; color: #64748b; }
        .btn-cancel-secondary:hover { background: #5a6268; color: #fff; }

        .tab-title { border-bottom: 2px solid #e32133; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; color: #2e4494; font-weight: 800; }
    </style>
</head>

<div class="page-layout">
    <div class="main-wrapper">
        <main id="main-content" class="flex-1 overflow-y-auto p-6 lg:p-10">
            <div class="flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                <a href="dashboard.php" class="hover:text-red-600 transition flex items-center gap-1.5">
                    <i data-lucide="layout-dashboard" class="w-3 h-3"></i> Dashboard
                </a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <a href="employees.php" class="hover:text-red-600 transition flex items-center gap-1.5">
                    <i data-lucide="users" class="w-3 h-3"></i> Employee List
                </a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900 flex items-center gap-1.5">
                    <i data-lucide="user-plus" class="w-3 h-3 text-red-600"></i> Add New Employee
                </span>
            </div>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div id="success-toast" class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center justify-between animate-in fade-in slide-in-from-top-4 duration-300">
                    <div class="flex items-center gap-3">
                        <div class="bg-emerald-500 p-2 rounded-xl">
                            <i data-lucide="check-circle" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-emerald-900">System Confirmation</p>
                            <p class="text-sm text-emerald-700 font-medium"><?= $_SESSION['success_message'] ?></p>
                        </div>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-600 transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['add_employee_error'])): ?>
                <div id="error-toast" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-2xl flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-red-500 p-2 rounded-xl">
                            <i data-lucide="alert-circle" class="w-5 h-5 text-white"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-red-900">System Error</p>
                            <p class="text-sm text-red-700 font-medium"><?= $_SESSION['add_employee_error'] ?></p>
                        </div>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-red-400 hover:text-red-600 transition">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <?php unset($_SESSION['add_employee_error']); ?>
            <?php endif; ?>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Employee Master Record</h1>
                    <p class="text-slate-500 text-sm font-medium italic">TASSI Operational Real-time Database • Create Mode</p>
                </div>
            </div>

            <form method="POST" action="employees.php?a=store" enctype="multipart/form-data" id="employeeForm">
                <div class="profile-container">   
                    <div class="inner-sidebar">
                        <button type="button" class="tab-btn active" onclick="openTab(this, 'personal')">
                            <i data-lucide="user"></i> PERSONAL INFORMATION
                        </button>
                        <button type="button" class="tab-btn" onclick="openTab(this, 'contact')">
                            <i data-lucide="phone"></i> CONTACT DETAILS
                        </button>
                        <button type="button" class="tab-btn" onclick="openTab(this, 'government')">
                            <i data-lucide="file-text"></i> GOVERNMENT IDS
                        </button>
                        <button type="button" class="tab-btn" onclick="openTab(this, 'emergency')">
                            <i data-lucide="shield-alert"></i> EMERGENCY CONTACT
                        </button>
                        <button type="button" class="tab-btn" onclick="openTab(this, 'employment')">
                            <i data-lucide="briefcase"></i> EMPLOYMENT INFORMATION
                        </button>
                        
                        <?php if ($isAdmin): ?>
                        <button type="button" class="tab-btn" onclick="openTab(this, 'salary')">
                            <i data-lucide="banknote"></i> SALARY & DEDUCTIONS
                        </button>
                        <?php endif; ?>

                        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                            <button type="submit" class="side-action-btn btn-save-main" id="submitBtn">
                                <i data-lucide="save" class="w-4 h-4"></i> SAVE RECORD
                            </button>
                            <a href="employees.php" class="side-action-btn btn-cancel-secondary">
                                <i data-lucide="x" class="w-4 h-4"></i> CANCEL
                            </a>
                        </div>
                    </div>

                    <div class="profile-card">
                        <div id="personal" class="tab-content active">
                            <h5 class="tab-title">Personal Information</h5>

                            <div class="text-center mb-8">
                                <div class="avatar-wrapper mx-auto" onclick="document.getElementById('ID_filename').click();">
                                    <div id="initialsPreview" class="avatar-initials">??</div>
                                    <img id="imgPreview" src="" alt="Profile Preview">
                                    <div class="upload-overlay"><i data-lucide="camera" class="inline w-3 h-3 mr-1"></i> UPLOAD</div>
                                </div>
                                <input type="file" name="ID_filename" id="ID_filename" accept="image/*" style="display: none;" onchange="previewImage(this);">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Employee Photo</p>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6">
                                <!-- <div>
                                    <label class="form-label-custom">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom uppercase-input" name="first_name" id="first_name" required onkeyup="updateInitials()">
                                </div>
                                <div>
                                    <label class="form-label-custom">Middle Name</label>
                                    <input type="text" class="form-input-custom uppercase-input" name="middle_name">
                                </div>
                                <div>
                                    <label class="form-label-custom">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom uppercase-input" name="last_name" id="last_name" required onkeyup="updateInitials()">
                                </div> -->
                                <div>
                                    <label class="form-label-custom">First Name <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom uppercase-input" name="first_name" id="first_name" 
                                        value="<?= htmlspecialchars($_SESSION['add_employee_old']['first_name'] ?? '') ?>"required onkeyup="updateInitials()" autofocus>
                                </div>

                                <div>
                                    <label class="form-label-custom">Middle Name</label>
                                    <input type="text" class="form-input-custom uppercase-input" name="middle_name"
                                        value="<?= htmlspecialchars($_SESSION['add_employee_old']['middle_name'] ?? '') ?>">
                                </div>

                                <div>
                                    <label class="form-label-custom">Last Name <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom uppercase-input" name="last_name" id="last_name" 
                                        value="<?= htmlspecialchars($_SESSION['add_employee_old']['last_name'] ?? '') ?>" required onkeyup="updateInitials()">
                                </div>
                                <div>
                                    <label class="form-label-custom">Date of Birth <span class="text-red-500">*</span></label>
                                    <input type="date" class="form-input-custom" name="date_of_birth" required>
                                </div>
                                <div>
                                    <label class="form-label-custom">Gender <span class="text-red-500">*</span></label>
                                    <select name="gender" class="form-input-custom" required>
                                        <option value="" disabled selected hidden>SELECT GENDER</option>
                                        <option value="Male">MALE</option>
                                        <option value="Female">FEMALE</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label-custom">Civil Status <span class="text-red-500">*</span></label>
                                    <select name="civil_status" class="form-input-custom" required>
                                        <option value="" disabled selected hidden>SELECT STATUS</option>
                                        <option value="SINGLE">SINGLE</option>
                                        <option value="MARRIED">MARRIED</option>
                                        <option value="WIDOWED">WIDOWED</option>
                                        <option value="SEPARATED">SEPARATED</option>
                                    </select>
                                </div>
                                <div class="md:col-span-3">
                                    <div class="row" style="display: flex; flex-wrap: wrap; margin: 0 -10px;">
                                        <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px; box-sizing: border-box;">
                                            <label class="form-label-custom">Nationality <span class="text-red-500">*</span></label>
                                            <input type="text" name="nationality" class="form-input-custom uppercase-input" value="FILIPINO" required>
                                        </div>
                                        <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px; box-sizing: border-box;">
                                            <label class="form-label-custom">Religion <span class="text-red-500">*</span></label>
                                            <input type="text" name="religion" class="form-input-custom uppercase-input" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="contact" class="tab-content">
                            <h5 class="tab-title">Contact Details</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                                <!-- <div>
                                    <label class="form-label-custom">Mobile Number <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom" name="phone" id="phone_input" maxlength="11" placeholder="09XXXXXXXXX" required>
                                </div> -->
                                <div>
                                    <label class="form-label-custom">Mobile Number <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom" name="phone" id="phone_input" maxlength="11" placeholder="09XXXXXXXXX" required>
                                    <div id="phone_error" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: none;">
                                        It should be 11 digits and start with 09.
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label-custom">Email Address <span class="text-red-500">*</span></label>
                                    <input type="email" class="form-input-custom" name="email_address" required>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="form-label-custom">Current Address <span class="text-red-500">*</span></label>
                                    <textarea class="form-input-custom uppercase-input" name="current_address" rows="3" required style="height: auto;"></textarea>
                                </div>
                            </div>
                        </div>

                        <div id="government" class="tab-content">
                            <h5 class="tab-title">Government Identifications</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                                <div>
                                    <label class="form-label-custom">SSS Number</label>
                                    <input type="text" class="form-input-custom" name="sss_no" maxlength="15">
                                </div>
                                <div>
                                    <label class="form-label-custom">PhilHealth Number</label>
                                    <input type="text" class="form-input-custom" name="philhealth_no" maxlength="12">
                                </div>
                                <div>
                                    <label class="form-label-custom">Pag-IBIG Number</label>
                                    <input type="text" class="form-input-custom" name="pagibig_no" maxlength="14">
                                </div>
                                <div>
                                    <label class="form-label-custom">TIN Number</label>
                                    <input type="text" class="form-input-custom" name="tin_no" maxlength="12">
                                </div>
                            </div>
                        </div>

                        <div id="emergency" class="tab-content">
                            <h5 class="tab-title">Emergency Contact</h5>
                            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                                <div>
                                    <label class="form-label-custom">Emergency Contact Person <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom uppercase-input" name="emergency_name" required>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                                <!-- <div>
                                    <label class="form-label-custom">Emergency Contact Number <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom" name="emergency_number" id="emergency_input" maxlength="11" placeholder="09XXXXXXXXX" required>
                                </div> -->
                                <div>
                                    <label class="form-label-custom">Emergency Contact Number <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom" name="emergency_number" id="emergency_phone_input"  maxlength="11" placeholder="09XXXXXXXXX" required>
                                    <div id="emergency_phone_error" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: none;">
                                        It should be 11 digits and start with 09.
                                    </div>
                                </div>
                                <div>
                                    <label class="form-label-custom">Emergency Contact Relationship <span class="text-red-500">*</span></label>
                                    <select name="emergency_relationship" class="form-input-custom" required>
                                        <option value="" disabled selected hidden>SELECT RELATIONSHIP</option>
                                        <option value="Mother">MOTHER</option>
                                        <option value="Father">FATHER</option>
                                        <option value="Spouse">SPOUSE</option>
                                        <option value="Sibling">SIBLING</option>
                                        <option value="Others">OTHERS</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="employment" class="tab-content">
                            <h5 class="tab-title">Employment Information</h5>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6">
                                <?php $ym = date('Ym') . '-'; ?>
                                <!-- <div>
                                    <label class="form-label-custom">Tassi ID Number <span class="text-red-500">*</span></label>
                                    <input type="text" id="employee_code_input" class="form-input-custom uppercase-input font-bold" name="employee_code" required 
                                        value="<?= (!empty($employee['employee_code'])) ? $employee['employee_code'] : $ym ?>" oninput="checkPrefix(this, '<?= $ym ?>')">
                                </div> -->
                                <div>
                                    <label class="form-label-custom">Tassi ID Number <span class="text-red-500">*</span></label>
                                    <p id="id-note" style="font-size: 0.75rem; color: #ef4444; margin-top: 4px; font-style: italic;">
                                        * Note: <strong>Date hired</strong> should be selected first to generate the YYYYMM prefix.
                                    </p>
                                <input type="text" id="employee_code_input" name="employee_code" class="form-input-custom" 
                                                style="color: #2e4494; font-weight: 700;" 
                                                value="<?= htmlspecialchars($_SESSION['add_employee_old']['employee_code'] ?? '') ?>" 
                                                placeholder="YYYYMM-XXXXXX">
                                </div>
                                <div>
                                    <label class="form-label-custom">Card ID Number <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom uppercase-input" name="CardID" required>
                                </div>
                                <div>
                                    <label class="form-label-custom">Department <span class="text-red-500">*</span></label>
                                    <select name="department" class="form-input-custom" required>
                                        <option value="" disabled selected hidden>SELECT DEPARTMENT</option>
                                        <?php $depts = ['Admin', 'Finance', 'Sales', 'Technical', 'CIRD', 'IT', 'CMS', 'RDU', 'Shop']; 
                                        foreach($depts as $d): ?>
                                            <option value="<?= strtoupper($d) ?>"><?= strtoupper($d) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label-custom">Designation <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom uppercase-input" name="designation" required>
                                </div>
                                <!-- <div>
                                    <label class="form-label-custom">Date Hired <span class="text-red-500">*</span></label>
                                    <input type="date" class="form-input-custom" name="date_of_joining" required value="<?= date('Y-m-d') ?>">
                                </div> -->
                                <div>
                                    <label class="form-label-custom">Date Hired <span class="text-red-500">*</span></label>
                                <input type="date" id="date_hired" name="date_of_joining" class="form-input-custom" 
                                                value="<?= $_SESSION['add_employee_old']['date_of_joining'] ?? '' ?>" 
                                                onchange="autoGenerateTassiID(this.value)">
                                </div>
                                <div>
                                    <label class="form-label-custom">Probation End Date</label>
                                    <input type="date" class="form-input-custom" name="probation_end_date" value="<?= ['probation_end_date'] ?? '' ?>">
                                </div>
                                <div>
                                    <label class="form-label-custom">Employment Type <span class="text-red-500">*</span></label>
                                    <select name="employment_type" class="form-input-custom" required>
                                        <option value="" disabled selected hidden>SELECT EMPLOYMENT TYPE</option>
                                        <option value="REGULAR">REGULAR</option>
                                        <option value="PROBATIONARY">PROBATIONARY</option>
                                        <option value="CONTRACTUAL">CONTRACTUAL</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label-custom">Reporting Manager <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom uppercase-input" name="reporting_manager" 
                                        value="<?= htmlspecialchars($employee['reporting_manager'] ?? '') ?>" required>
                                </div>
                                <div>
                                    <label class="form-label-custom">Work Location <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom uppercase-input" name="work_location" value="<?= htmlspecialchars($employee['work_location'] ?? '') ?>" required>
                                </div>
                                <div>
                                    <label class="form-label-custom">Status <span class="text-red-500">*</span></label>
                                    <select name="status" class="form-input-custom" required>
                                        <option value="" disabled selected hidden>SELECT STATUS</option>
                                        <option value="1" <?= (['status'] == 1) ? 'selected' : '' ?>>ACTIVE</option>
                                        <option value="0" <?= (['status'] == 0) ? 'selected' : '' ?>>INACTIVE</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="form-label-custom">Time In <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom uppercase-input" name="time_in" placeholder="EX: 7:00" value="<?= htmlspecialchars($employee['time_in'] ?? '') ?>" required>
                                </div>
                                <div>
                                    <label class="form-label-custom">Time Out <span class="text-red-500">*</span></label>
                                    <input type="text" class="form-input-custom uppercase-input" name="time_out" placeholder="EX: 16:00" value="<?= htmlspecialchars($employee['time_out'] ?? '') ?>" required>
                                </div>
                            </div>
                        </div>

                        <?php if ($isAdmin): ?>
                            <div id="salary" class="tab-content">
                                <h5 class="tab-title">Salary & Deductions</h5>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6">
                                    <div>
                                        <label class="form-label-custom">Basic Rate Per Day <span class="text-red-500">*</span></label>
                                        <div style="position: relative;">
                                            <span style="position: absolute; left: 16px; top: 12px; font-weight: 800; color: #64748b;">₱</span>
                                            <input type="number" step="1" class="form-input-custom" name="salary" style="padding-left: 35px !important;" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label-custom">HMO Premium <span class="text-red-500">*</span></label>
                                        <div style="position: relative;">
                                            <span style="position: absolute; left: 16px; top: 12px; font-weight: 800; color: #64748b;">₱</span>
                                            <input type="number" step="1" class="form-input-custom" name="hmo" style="padding-left: 35px !important;" required>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="form-label-custom">Emergency Fund (EF) <span class="text-red-500">*</span></label>
                                        <div style="position: relative;">
                                            <span style="position: absolute; left: 16px; top: 12px; font-weight: 800; color: #64748b;">₱</span>
                                            <input type="number" step="1" class="form-input-custom" name="ef" style="padding-left: 35px !important;" required>
                                        </div>
                                    </div>
                                </div>
                                <div class="p-4 bg-slate-50 border border-dashed border-slate-200 rounded-2xl flex items-center gap-3 mt-6">
                                    <i data-lucide="lock" class="w-4 h-4 text-slate-400"></i>
                                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Confidential Data: Only visible to authorized administrators.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div> 
                </div> 
            </form>
        </main>
    </div>
</div>

<script>
    lucide.createIcons();

    const employeeCodeInput = document.getElementById('employee_code_input');

    /*** Function para makuha ang YYYYMM format*/
    const getPrefixFromDate = (dateValue) => {
        if (!dateValue) return "";
        const d = new Date(dateValue);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        return `${year}${month}`;
    };

    /*** AUTO-GENERATE para sa Create Page (Walang prompt/confirm)*/
    function autoGenerateTassiID(newDate) {
        if (!newDate || !employeeCodeInput) return;

        const newPrefix = getPrefixFromDate(newDate);
        const currentVal = employeeCodeInput.value;
        
        // Kunin ang existing suffix kung meron na (e.g. -0005), 
        // kung wala, hayaang blangko ang suffix para HR ang mag-type
        const suffix = currentVal.includes('-') ? currentVal.split('-')[1] : '';
        
        // I-set ang value: YYYYMM- (kung may suffix, isama)
        employeeCodeInput.value = `${newPrefix}-${suffix}`;
        
        // Visual cue na nag-update ang field
        employeeCodeInput.classList.add('bg-blue-50'); 
        setTimeout(() => employeeCodeInput.classList.remove('bg-blue-50'), 500);
    }

    /*** Protection: Siguraduhin na laging may dash pag nagta-type sila*/
    if (employeeCodeInput) {
        employeeCodeInput.addEventListener('input', function() {
            const dateHired = document.getElementById('date_hired').value;
            const prefix = getPrefixFromDate(dateHired);
            
            if (prefix && !this.value.startsWith(prefix)) {
                // Kung sinubukang burahin ang prefix, ibalik ito
                const currentSuffix = this.value.includes('-') ? this.value.split('-')[1] : this.value.replace(/^\d+/, "");
                this.value = prefix + (currentSuffix.startsWith('-') ? currentSuffix : '-' + currentSuffix);
            }
        });
    }
    
    // --- PREFIX CONTROL FUNCTION --- (For Tassi ID Number will not allow deletion of the prefix)
    function checkPrefix(input, prefix) {
        // Kapag sinubukang burahin ang prefix, ibabalik natin ito agad
        if (!input.value.startsWith(prefix)) {
            input.value = prefix;
        }
    }

    // Dagdag na protection: Pinipigilan ang cursor na pumunta sa unahan ng prefix
    const inputField = document.getElementById('employee_code_input');
    const prefixStr = '<?= $ym ?>';

    inputField.addEventListener('keydown', function(e) {
        // Kung ang cursor ay nasa loob ng prefix area at nag-backspace o delete
        if (this.selectionStart < prefixStr.length && (e.key === 'Backspace' || e.key === 'Delete')) {
            e.preventDefault();
        }
    });

    inputField.addEventListener('click', function() {
        // Kung ic-click ng user ang unahan ng prefix, itatapon ang cursor sa dulo nito
        if (this.selectionStart < prefixStr.length) {
            this.setSelectionRange(prefixStr.length, prefixStr.length);
        }
    });
    
    // --- GLOBAL STATE ---
    let isNameDuplicate = false;

    document.addEventListener('DOMContentLoaded', () => {
        // --- INITIALIZATIONS ---
        const alert = document.getElementById('status-alert');
        if (alert) {
            setTimeout(() => { 
                alert.classList.remove('show'); 
                setTimeout(() => alert.remove(), 500); 
            }, 5000);
        }

        // --- PHONE VALIDATION INITIALIZATION ---
        // Siguraduhin na ang IDs sa HTML mo ay 'phone_input' at 'emergency_input'
        validatePhoneNumber(document.getElementById('phone_input'), document.getElementById('phone_error'));
        validatePhoneNumber(document.getElementById('emergency_input'), document.getElementById('emergency_phone_error'));

        // --- DUPLICATE CHECKER LISTENERS ---
        // Idagdag ang class na 'check-duplicate' sa first_name, last_name, at date_of_birth inputs
        const duplicateFields = document.querySelectorAll('#first_name, #last_name, #date_of_birth');
        duplicateFields.forEach(input => {
            input.addEventListener('change', runDuplicateCheck);
            input.addEventListener('blur', runDuplicateCheck);
        });
        
        // --- AUTO UPPERCASE ---
        document.querySelectorAll('.uppercase-input').forEach(input => {
            input.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
                // I-update rin ang initials habang nagta-type kung name field ito
                if (this.id === 'first_name' || this.id === 'last_name') {
                    updateInitials();
                }
            });
        });

        // --- PREFIX CONTROL ---
        const tassiInput = document.getElementById('employee_code');
        if (tassiInput) {
            const prefix = tassiInput.dataset.prefix || "";
            tassiInput.addEventListener('input', function() {
                if (!this.value.startsWith(prefix)) this.value = prefix;
            });
        }
    });

document.addEventListener('DOMContentLoaded', function() {
    // 1. Success Message Auto-hide
    const successToast = document.getElementById('success-toast');
    if (successToast) {
        setTimeout(() => {
            successToast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            successToast.style.opacity = '0';
            successToast.style.transform = 'translateY(-20px)'; // May slide up effect
            setTimeout(() => successToast.remove(), 500);
        }, 5000); // Mawawala after 5 seconds
    }

    // 2. Error Message Auto-hide (Optional: Mas matagal ng konti para mabasa ang error)
    const errorToast = document.getElementById('error-toast');
    if (errorToast) {
        setTimeout(() => {
            errorToast.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            errorToast.style.opacity = '0';
            errorToast.style.transform = 'translateY(-20px)';
            setTimeout(() => errorToast.remove(), 500);
        }, 8000); // 8 seconds para sa error
    }
});

    // --- LIVE INITIALS UPDATE ---
    function updateInitials() {
        const fname = document.getElementById('first_name').value.trim();
        const lname = document.getElementById('last_name').value.trim();
        const initialBox = document.getElementById('initialsPreview');
        if (!initialBox) return;
        let initialText = "";
        if(fname) initialText += fname.charAt(0);
        if(lname) initialText += lname.charAt(0);
        initialBox.innerText = initialText ? initialText.toUpperCase() : "??";
    }

    // --- PHOTO PREVIEW LOGIC ---
function previewImage(input) {
    const preview = document.getElementById('imgPreview');
    const initials = document.getElementById('initialsPreview');
    // Gumamit tayo ng try-catch o checking para hindi mag-error ang script kung walang photo_error element
    const errorMsg = document.getElementById('photo_error'); 

    if (input.files && input.files[0]) {
        const file = input.files[0];

        // 2MB Validation
        if (file.size > 2 * 1024 * 1024) {
            if (errorMsg) errorMsg.style.display = "block";
            input.value = ""; 
            if(preview) preview.style.display = "none";
            if(initials) initials.style.display = "flex";
            return;
        }

        if (errorMsg) errorMsg.style.display = "none";

        const reader = new FileReader();
        reader.onload = function(e) {
            if(preview) {
                preview.src = e.target.result;
                preview.style.display = "block"; // ETO ANG MAGPAPAKITA NG IMAGE
            }
            if(initials) {
                initials.style.display = "none"; // ETO ANG MAGTATAGO NG INITIALS
            }
        }
        reader.readAsDataURL(file);
    }
}

    // --- TAB SYSTEM ---
    function openTab(btnElement, tabId) {
        document.querySelectorAll(".tab-content").forEach(tab => tab.classList.remove("active"));
        document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("active"));
        const targetTab = document.getElementById(tabId);
        if (targetTab) targetTab.classList.add("active");
        if (btnElement) btnElement.classList.add("active");
    }

    // // --- DEDUCTION ---
    // function addDeductionRow() {
    //     const container = document.getElementById('dynamic-deductions-container');
    //     const noMsg = document.getElementById('no-deduction-msg');
    //     if (noMsg) noMsg.style.display = 'none';
    //     const row = document.createElement('div');
    //     row.className = 'deduction-row';
    //     row.innerHTML = `<div class="row g-2 align-items-end"><div class="col-md-6"><label class="form-label-custom">Type</label><select class="form-input-custom mb-0" name="deduction_type[]"><option value="Paluwagan">Paluwagan</option><option value="Gadgets">Gadgets</option><option value="Appliance">Appliance</option><option value="COOP">COOP</option><option value="Donation">Donation</option><option value="Others">Others</option></select></div><div class="col-md-4"><label class="form-label-custom">Amount</label><input type="number" class="form-input-custom mb-0" name="deduction_amount[]" step="0.01"></div><div class="col-md-2 text-end"><button type="button" class="btn btn-outline-danger border-0" onclick="this.closest('.deduction-row').remove()"><i class="fas fa-trash"></i></button></div></div>`;
    //     container.appendChild(row);
    // }

    // --- DUPLICATE CHECKER ---
    function runDuplicateCheck() {
        const f = document.getElementById('first_name').value.trim();
        const l = document.getElementById('last_name').value.trim();
        const b = document.getElementById('date_of_birth').value;
        const errorText = document.getElementById('duplicate_error');

        // --- STEP 1: EXIT PAG KULANG ANG INPUT ---
        // Kung kahit isa sa mga ito ay empty, itago ang alert at i-enable ang button.
        if (!f || !l || !b) {
            if (errorText) errorText.style.display = 'none';
            isNameDuplicate = false;
            updateSubmitButton();
            return; 
        }

        // --- STEP 2: PROCEED SA AJAX PAG KUMPLETO NA ---
        const formData = new FormData();
        formData.append('first_name', f);
        formData.append('last_name', l);
        formData.append('date_of_birth', b);

        fetch('employees.php?a=check_duplicate', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            isNameDuplicate = data.exists;
            if (isNameDuplicate && errorText) errorText.style.display = 'block';
            else if (errorText) errorText.style.display = 'none';
            updateSubmitButton();
        });
    }

    // // --- PHONE VALIDATION FUNCTION ---
    // function validatePhoneNumber(inputElement, errorElement) {
    //     if(inputElement && errorElement) {
    //         inputElement.addEventListener('input', function() {
    //             const val = this.value;
    //             if (val.length > 0 && (!val.startsWith('09') || val.length !== 11)) {
    //                 errorElement.style.display = "block";
    //                 inputElement.classList.add('input-error');
    //             } else {
    //                 errorElement.style.display = "none";
    //                 inputElement.classList.remove('input-error');
    //             }
    //         });
    //     }
    // }

    // --- PHONE VALIDATION FUNCTION ---
    function validatePhoneNumber(inputElement, errorElement) {
        if(inputElement && errorElement) {
            inputElement.addEventListener('input', function() {
                // 1. BLOCK TEXT: Burahin agad ang hindi numero
                this.value = this.value.replace(/[^0-9]/g, '');

                const val = this.value;

                // 2. CHECK FORMAT: 09 prefix at 11 digits
                if (val.length > 0 && (!val.startsWith('09') || val.length !== 11)) {
                    errorElement.style.display = "block";
                    inputElement.classList.add('input-error');
                } else {
                    errorElement.style.display = "none";
                    inputElement.classList.remove('input-error');
                }
            });
        }
    }

    // 3. TAWAGIN ANG FUNCTION
    // --- EXISTING (Para sa Mobile Number) ---
    const createPhoneInput = document.getElementById('phone_input');
    const createPhoneError = document.getElementById('phone_error');
    validatePhoneNumber(createPhoneInput, createPhoneError);

    // --- IDADAGDAG (Para sa Emergency Number) ---
    const emergencyInput = document.getElementById('emergency_phone_input');
    const emergencyError = document.getElementById('emergency_phone_error');
    validatePhoneNumber(emergencyInput, emergencyError);
    
    // --- SUBMIT BUTTON CONTROL ---
 function updateSubmitButton() {
    const btn = document.getElementById('submitBtn');
    if (btn) btn.disabled = false; // 🔥 FORCE ENABLE
}
    
    // --- VALIDATION TAB SWITCHER (Para sa HTML5 validation) ---
    document.getElementById('employeeForm')?.addEventListener('invalid', (function (e) {
        e.preventDefault();
        const field = e.target;
        const tabPane = field.closest('.tab-content');
        if (tabPane && !tabPane.classList.contains('active')) {
            const tabId = tabPane.id;
            const targetBtn = document.querySelector(`.tab-btn[data-tab="${tabId}"]`);
            if (targetBtn) openTab(targetBtn, tabId);
        }
        field.reportValidity();
    }), true);
</script>