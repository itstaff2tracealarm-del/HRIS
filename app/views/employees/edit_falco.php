<?php include __DIR__ . '/../../includes/header.php'; 

$isAdmin = (isset($_SESSION['role']) && strtolower($_SESSION['role']) === 'admin');
?>
<style>
    body { background-color: #f8f9fa; margin: 0; padding: 0; }
    
    /* Global Layout Consistency */
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
        background: #ffffff;
        border-radius: 30px; 
        padding: 40px; 
        min-height: 90vh; 
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border: 1px solid rgba(0,0,0,0.03);
    }

    .breadcrumb-ui { font-size: 0.90rem; font-weight: 500; color: #6c757d; }
    .breadcrumb-ui a { text-decoration: none; color: #6c757d; transition: 0.2s; }
    .breadcrumb-ui a:hover { color: #e32133; }
    .breadcrumb-ui span { font-weight: 600; }

    .profile-container { display: flex; gap: 30px; align-items: flex-start; }

    /* Avatar Styling */
    .avatar-wrapper { 
        width: 140px; height: 140px; background: #f1f4f9; border-radius: 50%; 
        margin: 0 auto 20px; overflow: hidden; position: relative;
        cursor: pointer; border: 4px solid #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.08);
    }
    .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .upload-overlay {
        position: absolute; bottom: 0; width: 100%; background: rgba(0,0,0,0.5);
        color: #fff; font-size: 0.65rem; padding: 5px 0; text-align: center;
        opacity: 0.9; transition: 0.3s;
    }

    .inner-sidebar { 
        flex: 0 0 300px; background: #ffffff; padding: 25px; 
        border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.02); 
    }

    .tab-btn {
        width: 100%; text-align: center; border: none; padding: 15px; border-radius: 12px; 
        margin-bottom: 15px; background: #f8f9fa; font-weight: 600; color: #444; cursor: pointer; transition: 0.3s;
    }
    .tab-btn.active { background: #e32133; color: #ffffff; }

    .profile-card { 
        flex-grow: 1; background: #ffffff; border-radius: 15px; 
        padding: 40px; box-shadow: 0 4px 20px rgba(0,0,0,0.03); 
        position: relative; min-height: 600px; border: 1px solid #f1f1f1;
    }
    
    .form-label-custom { color: #111111; font-size: 0.8rem; font-weight: 700; margin-bottom: 5px; text-transform: uppercase; }
    .form-control-custom { 
        width: 100%; border: none; background: #f1f4f9; padding: 12px 18px; 
        border-radius: 10px; margin-bottom: 20px; font-weight: 500; color: #333;
    }
    .form-control-custom:focus { outline: 2px solid #2e4494; background: #fff; }

    .uppercase-input { text-transform: uppercase; }

    .tab-content { display: none; }
    .tab-content.active { display: block; animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }

    .save-btn { background: #2e4494; color: #fff; border: none; padding: 15px; border-radius: 12px; font-weight: 600; width: 100%; transition: 0.3s; cursor: pointer; }
    .save-btn:hover { background: #1e2d63; }
</style>

<!-- <div class="page-layout">
    
    <?php include __DIR__ . '/../../includes/left.php'; ?>

    <div class="main-wrapper">
        <main class="content-area shadow-sm">

            <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm" style="border: 1px solid #f1f1f1;">
                <nav class="breadcrumb-ui">
                    <a href="dashboard.php">Dashboard</a> / 
                    <a href="employees.php?a=index">Employees</a> / 
                    <span style="color: #1c1e1b;">Edit Information</span>
                </nav>
                <a href="employees.php?a=index" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>

            <?php if (!empty($successMsg)): ?>
                <div id="success-alert" class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= htmlspecialchars($successMsg) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="employees.php?a=update" enctype="multipart/form-data" id="editEmployeeForm">
                <input type="hidden" name="id" value="<?= $employee['id'] ?>">

                <div class="profile-container">
                    <div class="inner-sidebar">
                        <button type="button" class="tab-btn active" onclick="openTab(event, 'personal')">PERSONAL INFORMATION</button>
                        <button type="button" class="tab-btn" onclick="openTab(event, 'contact')">CONTACT INFORMATION</button>
                        <button type="button" class="tab-btn" onclick="openTab(event, 'emergency')">EMERGENCY CONTACT</button>
                        <button type="button" class="tab-btn" onclick="openTab(event, 'employment')">EMPLOYMENT INFORMATION</button>
                        
                        <?php if ($isAdmin): ?>
                        <button type="button" class="tab-btn" onclick="openTab(event, 'information')">SALARY DEDUCTION</button>
                        <?php endif; ?>
                        <hr>
                        <button type="submit" class="save-btn">
                            <i class="fas fa-save me-2"></i> Save All Changes
                        </button>
                    </div>

                    <div class="profile-card">
                        <div id="personal" class="tab-content active">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Personal Information</h5>
                            
                            <div class="avatar-wrapper mx-auto" onclick="document.getElementById('ID_filename').click();">
                                <?php 
                                    $imgName = $employee['ID_filename'] ?? '';
                                    if (!empty($imgName)) {
                                        $displayPath = "uploads/profile/" . $imgName;
                                    } else {
                                        $displayPath = "../assets/img/default-avatar.png"; 
                                    }
                                ?>
                                <img id="imgPreview" src="<?= $displayPath ?>?v=<?= time() ?>" alt="Profile">
                                <input type="file" name="ID_filename" id="ID_filename" accept="image/*" style="display: none;" onchange="previewImage(this);">
                                <div class="upload-overlay"><i class="fas fa-camera"></i> CHANGE PHOTO</div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <label class="form-label-custom">FIRST NAME</label>
                                    <input type="text" class="form-control-custom uppercase-input" name="first_name" value="<?= htmlspecialchars($employee['first_name']) ?>" oninput="this.value = this.value.toUpperCase()" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">MIDDLE NAME</label>
                                    <input type="text" class="form-control-custom uppercase-input" name="middle_name" value="<?= htmlspecialchars($employee['middle_name'] ?? '') ?>" oninput="this.value = this.value.toUpperCase()" placeholder="N/A">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">LAST NAME</label>
                                    <input type="text" class="form-control-custom uppercase-input" name="last_name" value="<?= htmlspecialchars($employee['last_name']) ?>" oninput="this.value = this.value.toUpperCase()" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">DATE OF BIRTH</label>
                                    <input type="date" class="form-control-custom" name="date_of_birth" value="<?= htmlspecialchars($employee['date_of_birth'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">GENDER</label>
                                    <select name="gender" class="form-control-custom" required>
                                        <option value="Male" <?= ($employee['gender'] === 'Male') ? 'selected' : '' ?>>MALE</option>
                                        <option value="Female" <?= ($employee['gender'] === 'Female') ? 'selected' : '' ?>>FEMALE</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">NATIONALITY</label>
                                    <select name="nationality" class="form-control-custom" required>
                                        <option value="Filipino" <?= (($employee['nationality'] ?? '') === 'Filipino') ? 'selected' : '' ?>>FILIPINO</option>
                                        <option value="American" <?= (($employee['nationality'] ?? '') === 'American') ? 'selected' : '' ?>>AMERICAN</option>
                                        <option value="Others" <?= (($employee['nationality'] ?? '') === 'Others') ? 'selected' : '' ?>>OTHERS</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="contact" class="tab-content">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Contact Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label-custom">CONTACT NUMBER</label>
                                    <input type="text" class="form-control-custom" name="phone" id="phone_input" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?= htmlspecialchars($employee['phone']) ?>" required>
                                    <p id="phone_error" class="text-danger small mt-1" style="display:none; font-weight:600;">Must be 11 digits starting with 09</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">EMAIL ADDRESS</label>
                                    <input type="email" class="form-control-custom" name="email_address" value="<?= htmlspecialchars($employee['email_address'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-12">
                                    <label class="form-label-custom">CURRENT ADDRESS</label>
                                    <textarea class="form-control-custom uppercase-input" name="current_address" rows="3" oninput="this.value = this.value.toUpperCase()" required><?= htmlspecialchars($employee['current_address'] ?? '') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div id="emergency" class="tab-content">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Emergency Contact Information</h5>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label-custom">EMERGENCY CONTACT PERSON NAME</label>
                                    <input type="text" class="form-control-custom uppercase-input" name="emergency_name" oninput="this.value = this.value.toUpperCase()" value="<?= htmlspecialchars($employee['emergency_name'] ?? '') ?>" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">EMERGENCY CONTACT NUMBER</label>
                                    <input type="text" class="form-control-custom" name="emergency_number" id="emergency_input" maxlength="11" oninput="this.value = this.value.replace(/[^0-9]/g, '');" value="<?= htmlspecialchars($employee['emergency_number'] ?? '') ?>" required>
                                    <p id="emergency_phone_error" class="text-danger small mt-1" style="display:none; font-weight:600;">Must be 11 digits starting with 09</p>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label-custom">EMERGENCY CONTACT RELATIONSHIP</label>
                                    <select name="emergency_relationship" class="form-control-custom" required>
                                        <option value="Mother" <?= ($employee['emergency_relationship'] === 'Mother') ? 'selected' : '' ?>>MOTHER</option>
                                        <option value="Father" <?= ($employee['emergency_relationship'] === 'Father') ? 'selected' : '' ?>>FATHER</option>
                                        <option value="Spouse" <?= ($employee['emergency_relationship'] === 'Spouse') ? 'selected' : '' ?>>SPOUSE</option>
                                        <option value="Others" <?= ($employee['emergency_relationship'] === 'Others') ? 'selected' : '' ?>>OTHERS</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div id="employment" class="tab-content">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Employment Information</h5>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label-custom">TASSI ID Number</label>
                                    <input type="text" class="form-control-custom" name="employee_code" 
                                        value="<?= htmlspecialchars($employee['employee_code']) ?>" 
                                        style="background: #eef2f7; border: 1px solid #d1d9e6; color: #2e4494; font-weight: 700; cursor: not-allowed;" 
                                        readonly>
                                    <div style="margin-top: -15px; margin-bottom: 20px;">
                                        <small class="text-muted"><i class="fas fa-info-circle"></i> Official System ID (Permanent)</small>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">DEPARTMENT <span class="text-danger">*</span></label>
                                    <select name="department" class="form-control-custom" style="text-transform: uppercase;" required>
                                        <?php 
                                        $depts = ['Admin', 'Finance', 'Sales', 'Technical', 'CIRD', 'IT', 'CMS', 'RDU', 'Shop']; 
                                        foreach($depts as $d): 
                                            $upperDept = strtoupper($d); 
                                            $selected = (strtoupper($employee['department']) === $upperDept) ? 'selected' : '';
                                        ?>
                                            <option value="<?= $upperDept ?>" <?= $selected ?>><?= $upperDept ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">DESIGNATION</label>
                                    <input type="text" class="form-control-custom uppercase-input" name="designation" value="<?= htmlspecialchars($employee['designation']) ?>" oninput="this.value = this.value.toUpperCase()" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">DATE HIRED</label>
                                    <input type="date" class="form-control-custom" name="date_of_joining" value="<?= htmlspecialchars($employee['date_of_joining']) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">PROBATION END DATE</label>
                                    <input type="date" class="form-control-custom" name="probation_end_date" value="<?= htmlspecialchars($employee['probation_end_date'] ?? '') ?>">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">EMPLOYMENT TYPE</label>
                                    <select name="employment_type" class="form-control-custom" required>
                                        <option value="Full-time" <?= ($employee['employment_type'] === 'Full-time') ? 'selected' : '' ?>>FULL-TIME</option>
                                        <option value="Part-time" <?= ($employee['employment_type'] === 'Part-time') ? 'selected' : '' ?>>PART-TIME</option>
                                        <option value="Probationary" <?= ($employee['employment_type'] === 'Probationary') ? 'selected' : '' ?>>PROBATIONARY</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">REPORTING MANAGER</label>
                                    <input type="text" class="form-control-custom uppercase-input" name="reporting_manager" value="<?= htmlspecialchars($employee['reporting_manager'] ?? '') ?>" oninput="this.value = this.value.toUpperCase()"> 
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">WORK LOCATION</label>
                                    <select name="work_location" class="form-control-custom" required>
                                        <option value="Office" <?= (($employee['work_location'] ?? '') === 'Office') ? 'selected' : '' ?>>OFFICE</option>
                                        <option value="Remote" <?= (($employee['work_location'] ?? '') === 'Remote') ? 'selected' : '' ?>>REMOTE</option>
                                        <option value="Hybrid" <?= (($employee['work_location'] ?? '') === 'Hybrid') ? 'selected' : '' ?>>HYBRID</option>
                                        <option value="Field"  <?= (($employee['work_location'] ?? '') === 'Field') ? 'selected' : '' ?>>FIELD</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label-custom">STATUS</label>
                                    <select name="status" class="form-control-custom" required>
                                        <option value="1" <?= $employee['status'] == 1 ? 'selected' : '' ?>>ACTIVE</option>
                                        <option value="0" <?= $employee['status'] == 0 ? 'selected' : '' ?>>INACTIVE</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <?php if (!$isAdmin): ?>
                            <input type="hidden" name="salary" value="<?= $employee['salary'] ?>">
                            <input type="hidden" name="hmo" value="<?= $employee['hmo'] ?? '0' ?>">
                            <input type="hidden" name="ef" value="<?= $employee['ef'] ?? '0' ?>">
                        <?php endif; ?>

                        <?php if ($isAdmin): ?>
                        <div id="information" class="tab-content">
                            <h5 class="fw-bold mb-4 border-bottom pb-2">Salary Deduction</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label-custom">Basic Rate Per Day</label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 18px; top: 12px; font-weight: 700; color: #333; pointer-events: none;">₱</span>
                                        <input type="number" step="0.01" class="form-control-custom" name="salary" style="padding-left: 35px !important;" value="<?= htmlspecialchars($employee['salary']) ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">HMO</label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 18px; top: 12px; font-weight: 700; color: #333; pointer-events: none;">₱</span>
                                        <input type="number" step="0.01" class="form-control-custom" name="hmo" style="padding-left: 35px !important;" value="<?= htmlspecialchars($employee['hmo'] ?? '0.00') ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">EF (Emergency Fund)</label>
                                    <div style="position: relative;">
                                        <span style="position: absolute; left: 18px; top: 12px; font-weight: 700; color: #333; pointer-events: none;">₱</span>
                                        <input type="number" step="0.01" class="form-control-custom" name="ef" style="padding-left: 35px !important;" value="<?= htmlspecialchars($employee['ef'] ?? '0.00') ?>" required>
                                    </div> 
                                </div>
                                <div class="alert alert-light mt-3 no-print" style="border: 1px dashed #ddd; font-size: 0.8rem;">
                                    <i class="fas fa-lock me-1"></i> Admin-restricted access. This section is hidden from non-admin roles.
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </main>
    </div>
</div> -->

<div class="page-layout">

<?php include __DIR__ . '/../../includes/left.php'; ?>

<div class="main-wrapper">
<main class="content-area">

<h4 class="fw-bold mb-4">Edit Employee</h4>

<?php if (!empty($successMsg)): ?>
<div class="alert alert-success"><?= $successMsg ?></div>
<?php endif; ?>

<form method="POST" action="employees.php?a=update" enctype="multipart/form-data">

<input type="hidden" name="id" value="<?= $employee['id'] ?>">

<!-- 🔥 FALCO CARD -->
<input type="hidden" name="card_no" value="<?= $employee['employee_code'] ?>">

<div class="row">

<div class="col-md-4">
<label>First Name</label>
<input type="text" name="first_name" class="form-control" value="<?= $employee['first_name'] ?>" required>
</div>

<div class="col-md-4">
<label>Middle Name</label>
<input type="text" name="middle_name" class="form-control" value="<?= $employee['middle_name'] ?>">
</div>

<div class="col-md-4">
<label>Last Name</label>
<input type="text" name="last_name" class="form-control" value="<?= $employee['last_name'] ?>" required>
</div>

<div class="col-md-6 mt-3">
<label>Department</label>
<select name="department" class="form-control">
<option>ADMIN</option>
<option>IT</option>
<option>SALES</option>
<option>TECHNICAL</option>
</select>
</div>

<div class="col-md-6 mt-3">
<label>Designation</label>
<input type="text" name="designation" class="form-control" value="<?= $employee['designation'] ?>">
</div>

<div class="col-md-6 mt-3">
<label>Status</label>
<select name="status" class="form-control">
<option value="1" <?= $employee['status']==1?'selected':'' ?>>Active</option>
<option value="0" <?= $employee['status']==0?'selected':'' ?>>Inactive</option>
</select>
</div>

</div>

<hr>

<!-- 🔥 SYNC OPTION -->
<div class="form-check mt-3">
<input class="form-check-input" type="checkbox" name="sync_falco" checked>
<label class="form-check-label">
Sync changes to Falco System
</label>
</div>

<button class="btn btn-primary mt-3">Save Changes</button>

</form>

</main>
</div>

</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
<script>
    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) { tabcontent[i].classList.remove("active"); }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) { tablinks[i].classList.remove("active"); }
        document.getElementById(tabName).classList.add("active");
        
        if (evt && evt.currentTarget) {
            evt.currentTarget.classList.add("active");
        } else if (evt && evt.classList) {
            evt.classList.add("active");
        }
    }

    // Auto-switch tab if validation fails
    document.getElementById('editEmployeeForm').addEventListener('invalid', (function (e) {
        e.preventDefault();
        const closestTab = e.target.closest('.tab-content');
        if (closestTab && !closestTab.classList.contains('active')) {
            const tabId = closestTab.id;
            const tabBtn = document.querySelector(`button[onclick*="'${tabId}'"]`);
            if (tabBtn) {
                openTab(tabBtn, tabId);
            }
        }
        e.target.reportValidity();
    }), true);

    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imgPreview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    // Reusable Phone Validation
    function validatePhoneNumber(inputElement, errorElement) {
        if(inputElement) {
            inputElement.addEventListener('input', function() {
                const val = this.value;
                // Tanggalin ang non-numeric agad
                this.value = val.replace(/[^0-9]/g, '');
                
                if (this.value.length > 0 && (!this.value.startsWith('09') || this.value.length !== 11)) {
                    errorElement.style.display = "block";
                    this.setCustomValidity("Invalid");
                } else {
                    errorElement.style.display = "none";
                    this.setCustomValidity("");
                }
            });
        }
    }
    
    validatePhoneNumber(document.getElementById('phone_input'), document.getElementById('phone_error'));
    validatePhoneNumber(document.getElementById('emergency_input'), document.getElementById('emergency_phone_error'));

    // Awtomatikong pag-fade ng alert pagkatapos ng 5 segundo
    setTimeout(function() {
        let successAlert = document.getElementById('success-alert');
        if (successAlert) {
            // Gagamit tayo ng Bootstrap class para sa smooth fade out
            successAlert.classList.remove('show');
            
            // Optional: Tuluyan itong tanggalin sa HTML para hindi kumain ng space
            setTimeout(function() {
                successAlert.remove();
            }, 500); // 0.5 seconds transition time
        }
    }, 5000); // 5000ms = 5 seconds
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>