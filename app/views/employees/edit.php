<?php 
    include __DIR__ . '/../../includes/header.php';
    include __DIR__ . '/../../includes/left.php';

    // Check if admin
    $isAdmin = (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin');
?>

<head>
    <script src="https://unpkg.com/lucide@latest"></script>
    <!-- Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <!-- Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
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
        }
        
        /* .tab-btn {
            width: 100%; text-align: center; border: none; padding: 12px; border-radius: 12px; 
            margin-bottom: 10px; background: #f8f9fa; font-weight: 600; color: #444; cursor: pointer; transition: 0.3s;
            font-size: 0.85rem;
        }
        .tab-btn.active { background: #e32133 !important; color: #ffffff !important; } */
        
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
            border-radius: 15px; 
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
            /* .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; display: none; } */
            .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; display: block; }

            .avatar-initials {
                width: 100%; height: 100%; background: #2e4494; color: #fff;
                display: flex; align-items: center; justify-content: center;
                font-size: 60px; font-weight: 800; text-transform: uppercase;
            }
        
        .edit-overlay {
            position: absolute; bottom: 0; width: 100%; background: rgba(0,0,0,0.6);
            color: #fff; font-size: 9px; text-align: center; padding: 10px 0;
            transition: 0.3s; font-weight: 800; opacity: 0;
        }

            .avatar-wrapper:hover .edit-overlay { opacity: 1; }

        /* FORM STYLE UTILITIES */
        .display-label { color: #64748b; font-size: 0.7rem; font-weight: 800; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; display: block; }
        
            .form-input-custom { 
                width: 100%; border: 2px solid #f1f5f9; background: #f8fafc; padding: 12px 16px; 
                border-radius: 12px; margin-bottom: 20px; font-weight: 600; color: #1e293b;
                font-size: 0.9rem; transition: all 0.3s ease; box-sizing: border-box;
                text-transform: uppercase;
            }
            
            .form-input-custom:focus {
                outline: none; background: #fff; border-color: #2e4494; box-shadow: 0 0 0 4px rgba(46, 68, 148, 0.05);
            }

        .no-caps { text-transform: none !important; }

        /* TAB ANIMATION */
        .tab-content { display: none; }
        .tab-content.active { display: block; animation: fadeIn 0.4s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

        /* .side-action-btn { display: flex; width: 100%; padding: 15px; border-radius: 12px; font-weight: 600; text-align: center; text-decoration: none; transition: 0.3s; margin-top: 10px; border: none; cursor: pointer; } */
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
                    <i data-lucide="user-round-pen" class="w-3 h-3 text-red-600"></i> Edit Profile
                </span>
            </div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Update Employee Record</h1>
                    <p class="text-slate-500 text-sm font-medium italic uppercase">Modifying data for: <?= htmlspecialchars($employee['last_name'] . ', ' . $employee['first_name']) ?></p>
                </div>
            </div>

            <form action="employees.php?a=update" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $employee['id'] ?>">
                    <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                        <div id="success-alert" class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 text-green-700 flex items-center justify-between rounded-r-xl shadow-sm">
                            <div class="flex items-center gap-3">
                                <i data-lucide="check-circle" class="w-5 h-5"></i>
                                <span class="text-xs font-black uppercase tracking-widest">Employee record updated successfully!</span>
                            </div>
                            <button onclick="document.getElementById('success-alert').remove()" class="text-green-500 hover:text-green-700">
                                <i data-lucide="x" class="w-4 h-4"></i>
                            </button>
                        </div>
                    <?php endif; ?>

                    <div class="profile-container">
                        <div class="inner-sidebar">
                            <button type="button" class="tab-btn active" onclick="openTab(event, 'personal')">
                                <i data-lucide="user"></i> PERSONAL INFORMATION
                            </button>
                            <button type="button" class="tab-btn" onclick="openTab(event, 'contact')">
                                <i data-lucide="phone"></i> CONTACT DETAILS
                            </button>
                            <button type="button" class="tab-btn" onclick="openTab(event, 'government')">
                                <i data-lucide="file-text"></i> GOVERNMENT IDS
                            </button>
                            <button type="button" class="tab-btn" onclick="openTab(event, 'emergency')">
                                <i data-lucide="shield-alert"></i> EMERGENCY CONTACT
                            </button>
                            <button type="button" class="tab-btn" onclick="openTab(event, 'employment')">
                                <i data-lucide="briefcase"></i> EMPLOYMENT INFORMATION
                            </button>
                        
                            <?php if ($isAdmin): ?>
                                <button type="button" class="tab-btn" onclick="openTab(event, 'salary')">
                                    <i data-lucide="banknote"></i> SALARY & DEDUCTIONS
                                </button>
                            <?php endif; ?>

                            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #f1f5f9;">
                                <button type="submit" class="side-action-btn btn-save-main">
                                    <i data-lucide="save" class="w-4 h-4"></i> SAVE CHANGES
                                </button>
                                <a href="employees.php?id=<?= $employee['id'] ?>" class="side-action-btn btn-cancel-secondary">
                                    <i data-lucide="x" class="w-4 h-4"></i> CANCEL
                                </a>
                            </div>
                        </div>

                        <div class="profile-card">
                            <?php
                                $fName = $employee['first_name'] ?? '';
                                $lName = $employee['last_name'] ?? '';
                                $imgName = $employee['ID_filename'] ?? '';
                                $displayPath = (!empty($imgName)) ? "uploads/profile/" . $imgName : "../assets/img/default-avatar.png";
                                $initials = strtoupper(substr($fName, 0, 1) . substr($lName, 0, 1)) ?: "??";
                            ?>

                            <div id="personal" class="tab-content active">
                                <h5 class="tab-title">Personal Information</h5>                      
                                <div class="text-center mb-8">
                                    <div class="avatar-wrapper" onclick="document.getElementById('profile_img').click();">
                                        <?php if (!empty($imgName)): ?>
                                            <img id="imgPreview" src="<?= $displayPath ?>?v=<?= time() ?>" alt="Profile">
                                        <?php else: ?>
                                            <div id="initialsContainer" class="avatar-initials"><?= $initials ?></div>
                                            <img id="imgPreview" src="" style="display:none; width:100%; height:100%; object-fit:cover;">
                                        <?php endif; ?>
                                        <div class="edit-overlay"> <i data-lucide="camera" class="w-3 h-3 mr-1"></i> CHANGE </div>
                                        <input type="file" id="profile_img" name="profile_img" hidden accept="image/*" onchange="previewImage(this)">
                                    </div>
                                    <p class="mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest"> Employee Photo </p>
                                </div>

                                <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                                    <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                        <div class="display-label">First Name</div>
                                        <input type="text" name="first_name" class="form-input-custom" value="<?= htmlspecialchars($fName) ?>" required>
                                    </div>
                                    <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                        <div class="display-label">Middle Name</div>
                                        <input type="text" name="middle_name" class="form-input-custom" value="<?= htmlspecialchars($employee['middle_name'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                        <div class="display-label">Last Name</div>
                                        <input type="text" name="last_name" class="form-input-custom" value="<?= htmlspecialchars($lName) ?>" required>
                                    </div>
                                </div>

                                <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                                    <!-- <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                        <div class="display-label">Date of Birth</div>
                                        <input type="date" name="date_of_birth" class="form-input-custom" value="<?= $employee['date_of_birth'] ?? '' ?>">
                                    </div> -->
                                    <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                        <div class="display-label">Date of Birth</div>
                                        <input type="text" id="date_of_birth" name="date_of_birth" class="form-input-custom" placeholder="Select Date.." value="<?= $employee['date_of_birth'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                        <div class="display-label">Gender</div>
                                        <select name="gender" class="form-input-custom">
                                            <option value="MALE" <?= ($employee['gender'] == 'MALE') ? 'selected' : '' ?>>MALE</option>
                                            <option value="FEMALE" <?= ($employee['gender'] == 'FEMALE') ? 'selected' : '' ?>>FEMALE</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                        <div class="display-label">Civil Status</div>
                                        <select name="civil_status" class="form-input-custom">
                                            <option value="SINGLE" <?= (($employee['civil_status'] ?? '') == 'SINGLE') ? 'selected' : '' ?>>SINGLE</option>
                                            <option value="MARRIED" <?= (($employee['civil_status'] ?? '') == 'MARRIED') ? 'selected' : '' ?>>MARRIED</option>
                                            <option value="WIDOWED" <?= (($employee['civil_status'] ?? '') == 'WIDOWED') ? 'selected' : '' ?>>WIDOWED</option>
                                            <option value="SEPARATED" <?= (($employee['civil_status'] ?? '') == 'SEPARATED') ? 'selected' : '' ?>>SEPARATED</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">Nationality</div>
                                        <input type="text" name="nationality" class="form-input-custom" value="<?= htmlspecialchars($employee['nationality'] ?? 'FILIPINO') ?>">
                                    </div>
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">Religion</div>
                                        <input type="text" name="religion" class="form-input-custom" value="<?= htmlspecialchars($employee['religion'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                            <div id="contact" class="tab-content">
                                <h5 class="tab-title">Contact Information</h5>
                                <div class="row" style="display: flex; flex-wrap: wrap;">
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">MOBILE NUMBER</div>
                                        <input type="text" id="phone_input" class="form-input-custom" name="phone" placeholder="09xxxxxxxxx" maxlength="11" value="<?= htmlspecialchars($employee['phone'] ?? '') ?>">
                                        <div id="phone_error" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: none;">
                                            It should be 11 digits and start with 09.
                                        </div>
                                    </div>
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">EMAIL ADDRESS</div>
                                        <input type="email" name="email_address" placeholder="example@gmail.com" class="form-input-custom no-caps" value="<?= htmlspecialchars($employee['email_address'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row" style="display: flex; flex-wrap: wrap;">
                                    <div class="col-12" style="flex: 0 0 100%; padding: 0 10px;">
                                        <div class="display-label">CURRENT ADDRESS</div>
                                        <textarea name="current_address" class="form-input-custom" style="height: auto; min-height: 80px;"><?= htmlspecialchars($employee['current_address'] ?? '') ?></textarea>
                                    </div>
                                </div>
                            </div>

                            <div id="government" class="tab-content">
                                <h5 class="tab-title">Government Identifications</h5>
                                <div class="row" style="display: flex; flex-wrap: wrap;">
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">SSS NUMBER</div>
                                        <input type="text" name="sss_no" class="form-input-custom" value="<?= htmlspecialchars($employee['sss_no'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">PHILHEALTH NUMBER</div>
                                        <input type="text" name="philhealth_no" class="form-input-custom" value="<?= htmlspecialchars($employee['philhealth_no'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row" style="display: flex; flex-wrap: wrap;">
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">PAG-IBIG NUMBER</div>
                                        <input type="text" name="pagibig_no" class="form-input-custom" value="<?= htmlspecialchars($employee['pagibig_no'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">TIN NUMBER</div>
                                        <input type="text" name="tin_no" class="form-input-custom" value="<?= htmlspecialchars($employee['tin_no'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                            <div id="emergency" class="tab-content">
                                <h5 class="tab-title">Emergency Contact</h5>
                                <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                                    <div class="col-12" style="flex: 0 0 100%; padding: 0 10px;">
                                        <div class="display-label">EMERGENCY CONTACT PERSON</div>
                                        <input type="text" name="emergency_name" class="form-input-custom" value="<?= htmlspecialchars($employee['emergency_name'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                                    <!-- <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">EMERGENCY CONTACT NUMBER</div>
                                        <input type="text" name="emergency_number" placeholder="09xxxxxxxxx" maxlength="11" class="form-input-custom" value="<?= htmlspecialchars($employee['emergency_number'] ?? '') ?>">
                                    </div> -->
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">EMERGENCY CONTACT NUMBER</div>
                                        <input type="text" id="emergency_phone_input" name="emergency_number" placeholder="09xxxxxxxxx" maxlength="11" class="form-input-custom" value="<?= htmlspecialchars($employee['emergency_number'] ?? '') ?>">
                                        <div id="emergency_phone_error" style="color: #dc3545; font-size: 11px; margin-top: 5px; display: none;">
                                            Dapat 11 digits at nagsisimula sa 09.
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">EMERGENCY CONTACT RELATIONSHIP</div>
                                        <input type="text" name="emergency_relationship" class="form-input-custom" value="<?= htmlspecialchars($employee['emergency_relationship'] ?? '') ?>">
                                    </div> -->
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">EMERGENCY CONTACT RELATIONSHIP</div>
                                        <select name="emergency_relationship" class="form-input-custom" required>
                                            <option value="" disabled hidden>SELECT RELATIONSHIP</option>
                                            <option value="Mother" <?= ($employee['emergency_relationship'] == 'Mother') ? 'selected' : '' ?>>MOTHER</option>
                                            <option value="Father" <?= ($employee['emergency_relationship'] == 'Father') ? 'selected' : '' ?>>FATHER</option>
                                            <option value="Spouse" <?= ($employee['emergency_relationship'] == 'Spouse') ? 'selected' : '' ?>>SPOUSE</option>
                                            <option value="Sibling" <?= ($employee['emergency_relationship'] == 'Sibling') ? 'selected' : '' ?>>SIBLING</option>
                                            <option value="Others" <?= ($employee['emergency_relationship'] == 'Others') ? 'selected' : '' ?>>OTHERS</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div id="employment" class="tab-content">
                                <h5 class="tab-title">Employment Information</h5>
                                <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                                    <!-- <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">TASSI ID NUMBER</div>
                                        <input type="text" name="employee_code" class="form-input-custom" style="color: #2e4494; font-weight: 700;" value="<?= htmlspecialchars($employee['employee_code'] ?? '') ?>">
                                    </div> -->
                                    <!-- <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">TASSI ID NUMBER</div>
                                        <input type="text" id="employee_code_input" name="employee_code" class="form-input-custom" style="color: #2e4494; font-weight: 700;" 
                                            value="<?= htmlspecialchars($employee['employee_code'] ?? $ym) ?>">
                                    </div> -->
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">TASSI ID NUMBER</div>
                                        <input type="text" id="employee_code_input" name="employee_code" class="form-input-custom" style="color: #2e4494; font-weight: 700;" value="<?= htmlspecialchars($employee['employee_code'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">CARD ID NUMBER</div>
                                        <input type="text" name="CardID" class="form-input-custom" style="color: #2e4494; font-weight: 700;" value="<?= htmlspecialchars($employee['CardID'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <label class="display-label">DEPARTMENT</label>
                                        <select name="department" class="form-input-custom" required>
                                            <option value="" disabled hidden>SELECT DEPARTMENT</option>
                                            <?php 
                                            $depts = ['Admin', 'Finance', 'Sales', 'Technical', 'CIRD', 'IT', 'CMS', 'RDU', 'Shop']; 
                                            foreach($depts as $d): 
                                                $deptUpper = strtoupper($d);
                                                // I-check kung ito ang current department ng employee
                                                $selected = (isset($employee['department']) && strtoupper($employee['department']) == $deptUpper) ? 'selected' : '';
                                            ?>
                                                <option value="<?= $deptUpper ?>" <?= $selected ?>><?= $deptUpper ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">DESIGNATION</div>
                                        <input type="text" name="designation" class="form-input-custom" value="<?= htmlspecialchars($employee['designation'] ?? '') ?>">
                                    </div>
                                </div>

                                <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                                    <!-- <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">DATE HIRED</div>
                                        <input type="date" id="date_hired" name="date_of_joining" class="form-input-custom" value="<?= $employee['date_of_joining'] ?? '' ?>" onchange="askToUpdateID(this.value)">
                                    </div>
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">PROBATION END DATE</div>
                                        <input type="date" name="probation_end_date" class="form-input-custom" value="<?= $employee['probation_end_date'] ?? '' ?>">
                                    </div> -->
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">DATE HIRED</div>
                                        <input type="text" id="date_hired" name="date_of_joining" class="form-input-custom" value="<?= $employee['date_of_joining'] ?? '' ?>">
                                    </div>

                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">PROBATION END DATE</div>
                                        <input type="text" id="probation_end_date" name="probation_end_date" class="form-input-custom" value="<?= $employee['probation_end_date'] ?? '' ?>">
                                    </div>
                                </div>

                                <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">EMPLOYMENT TYPE</div>
                                        <select name="employment_type" class="form-input-custom">
                                            <option value="REGULAR" <?= ($employee['employment_type'] == 'REGULAR') ? 'selected' : '' ?>>REGULAR</option>
                                            <option value="PROBATIONARY" <?= ($employee['employment_type'] == 'PROBATIONARY') ? 'selected' : '' ?>>PROBATIONARY</option>
                                            <option value="CONTRACTUAL" <?= ($employee['employment_type'] == 'CONTRACTUAL') ? 'selected' : '' ?>>CONTRACTUAL</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">REPORTING MANAGER</div>
                                        <!-- <div class="form-input-custom"><?= htmlspecialchars($employee['reporting_manager'] ?? '—') ?></div> -->
                                        <input type="text" name="reporting_manager" class="form-input-custom" value="<?= $employee['reporting_manager'] ?? '' ?>">
                                    </div>
                                </div>

                                <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">WORK LOCATION</div>
                                        <input type="text" name="work_location" class="form-input-custom" value="<?= $employee['work_location'] ?? '' ?>">
                                    </div>
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">STATUS</div>
                                        <select name="status" class="form-input-custom">
                                            <option value="1" <?= ($employee['status'] == 1) ? 'selected' : '' ?>>ACTIVE</option>
                                            <option value="0" <?= ($employee['status'] == 0) ? 'selected' : '' ?>>INACTIVE</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">TIME IN</div>
                                        <input type="text" name="time_in" class="form-input-custom uppercase-input" 
                                                placeholder="e.g. 08:00" value="<?= htmlspecialchars($employee['time_in'] ?? '') ?>">
                                    </div>
                                    <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                        <div class="display-label">TIME OUT</div>
                                        <input type="text" name="time_out" class="form-input-custom uppercase-input" 
                                                placeholder="e.g. 17:00" value="<?= htmlspecialchars($employee['time_out'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                            <?php if ($isAdmin): ?>
                                <div id="salary" class="tab-content">
                                    <h5 class="tab-title">Salary & Deductions</h5>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6">
                                        <div>
                                            <label class="display-label">Basic Rate Per Day</label>
                                            <div style="position: relative;">
                                                <span style="position: absolute; left: 16px; top: 12px; font-weight: 800; color: #64748b;">₱</span>
                                                <input type="number" step="1" name="salary" class="form-input-custom" style="padding-left: 35px !important;" value="<?= (float)($employee['salary'] ?? 0) ?>" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="display-label">HMO Premium</label>
                                            <div style="position: relative;">
                                                <span style="position: absolute; left: 16px; top: 12px; font-weight: 800; color: #64748b;">₱</span>
                                                <input type="number" step="1" name="hmo" class="form-input-custom" style="padding-left: 35px !important;" value="<?= (float)($employee['hmo'] ?? 0) ?>" required>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="display-label">Emergency Fund (EF)</label>
                                            <div style="position: relative;">
                                                <span style="position: absolute; left: 16px; top: 12px; font-weight: 800; color: #64748b;">₱</span>
                                                <input type="number" step="1" name="ef" class="form-input-custom" style="padding-left: 35px !important;" value="<?= (float)($employee['ef'] ?? 0) ?>" required>
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

<!-- <script>
    lucide.createIcons();
    
    // 1. TASSI ID Permanent Prefix Logic
    const employeeCodeInput = document.getElementById('employee_code_input');
    if (employeeCodeInput) {
        const prefix = "<?= $ym ?? date('Ym') ?>-"; 

        employeeCodeInput.addEventListener('input', function() {
            if (!this.value.startsWith(prefix)) {
                this.value = prefix;
            }
        });

        employeeCodeInput.addEventListener('keydown', function(e) {
            if (this.selectionStart <= prefix.length && (e.key === 'Backspace' || e.key === 'Delete')) {
                e.preventDefault();
            }
        });
    }

    // 2. Tab Navigation Logic
    function openTab(evt, tabName) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) { tabcontent[i].classList.remove("active"); }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) { tablinks[i].classList.remove("active"); }
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    // 3. Robust Image Preview Logic
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const img = document.getElementById('imgPreview');
                const initials = document.getElementById('initialsContainer');
                
                if (img) {
                    img.src = e.target.result;
                    img.style.display = 'block'; // Siguraduhing visible
                    img.style.width = '100%';    // Para sakop ang avatar-wrapper
                    img.style.height = '100%';
                    img.style.objectFit = 'cover';
                }
                
                if (initials) {
                    initials.style.display = 'none'; // Itago ang initials pag may napiling file
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script> -->
<script>
    lucide.createIcons();

    const employeeCodeInput = document.getElementById('employee_code_input');
    const joiningInput = document.getElementById('date_of_joining');

    /*** Isang function na lang para sa pag-compute ng prefix*/
    const getPrefixFromDate = (dateValue) => {
        if (!dateValue) return "<?= $ym ?? date('Ym') ?>";
        const d = new Date(dateValue);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        return `${year}${month}`;
    };

    /*** ITO ANG MAIN FUNCTION: Tatawagin ito ng 'onchange' sa HTML*/
    function askToUpdateID(newDate) {
        if (!newDate || !employeeCodeInput) return;

        const newPrefix = getPrefixFromDate(newDate);
        const currentVal = employeeCodeInput.value;
        const suffix = currentVal.includes('-') ? currentVal.split('-')[1] : '0001';
        const suggestedID = `${newPrefix}-${suffix}`;

        // Mag-prompt sa user
        if (confirm(`Binago mo ang Date Hired. Gusto mo bang i-update ang ID sa ${suggestedID}?`)) {
            employeeCodeInput.value = suggestedID;
            // Visual cue na nagbago ang ID
            employeeCodeInput.style.backgroundColor = "#e8f0fe"; 
            setTimeout(() => employeeCodeInput.style.backgroundColor = "", 1000);
        } else {
            // Kung CANCEL, hindi natin gagalawin ang value. 
            // Pwede tayong maglagay ng log sa console para sa debugging.
            console.log("Update cancelled by user. Keeping the old ID.");
        }
    }

    /**
     * Protection Logic: Para hindi mabura ang format habang nag-eedit manual
     */
    if (employeeCodeInput) {
        employeeCodeInput.addEventListener('input', function() {
            if (!this.value.includes('-')) {
                const prefix = getPrefixFromDate(joiningInput.value);
                if (!this.value.startsWith(prefix)) {
                    this.value = prefix + '-' + this.value.replace(/^\d*-?/, "");
                }
            }
        });
    }

    /*** 2. Tab Navigation*/
    function openTab(evt, tabName) {
        const contents = document.querySelectorAll(".tab-content");
        const links = document.querySelectorAll(".tab-btn");
        
        contents.forEach(content => content.classList.remove("active"));
        links.forEach(link => link.classList.remove("active"));
        
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    /*** 3. Image Preview*/
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = (e) => {
                const img = document.getElementById('imgPreview');
                const initials = document.getElementById('initialsContainer');
                if (img) {
                    img.src = e.target.result;
                    img.style.display = 'block';
                }
                if (initials) initials.style.display = 'none';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    // --- PHONE VALIDATION FUNCTION ---
    // Iisang function para sa lahat ng phone fields
    function validatePhoneNumber(inputElement, errorElement) {
        if(inputElement && errorElement) {
            inputElement.addEventListener('input', function() {
                // BLOCK TEXT: Numero lang ang tinatanggap
                this.value = this.value.replace(/[^0-9]/g, '');

                const val = this.value;
                
                // VALIDATION: Check 09 prefix at 11 length
                if (val.length > 0 && (!val.startsWith('09') || val.length !== 11)) {
                    errorElement.style.display = "block";
                    inputElement.style.border = "1px solid #dc3545";
                } else {
                    errorElement.style.display = "none";
                    inputElement.style.border = "";
                }
            });
        }
    }

    // 1. I-activate para sa PERSONAL MOBILE NUMBER
    const phoneInput = document.getElementById('phone_input');
    const phoneError = document.getElementById('phone_error');
    validatePhoneNumber(phoneInput, phoneError);

    // 2. I-activate para sa EMERGENCY CONTACT NUMBER
    const emergencyInput = document.getElementById('emergency_phone_input');
    const emergencyError = document.getElementById('emergency_phone_error');
    validatePhoneNumber(emergencyInput, emergencyError);

    flatpickr("#date_of_birth", {
        altInput: true,          // Gumagawa ng bagong hidden input para sa display
        altFormat: "F j, Y",     // Format na makikita ng tao (e.g. January 15, 1990)
        dateFormat: "Y-m-d",     // Format na ise-save sa database (e.g. 1990-01-15)
        allowInput: true,        // Pwedeng i-type kung gusto
        
        // Dagdag na features para sa HRMS:
        maxDate: "today",        // Hindi pwedeng ipanganak sa future
        changeMonth: true,       // Madaling palitan ang buwan
        changeYear: true         // Madaling palitan ang taon
    });

    // Setup para sa DATE HIRED
    flatpickr("#date_hired", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        onChange: function(selectedDates, dateStr) {
            // Ito ang kapalit ng onchange sa HTML para gumana pa rin ang function mo
            if (typeof askToUpdateID === "function") {
                askToUpdateID(dateStr);
            }
        }
    });

    // Setup para sa PROBATION END DATE
    flatpickr("#probation_end_date", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d"
    });
</script>