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
        flex-wrap: wrap;
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
        width: 140px; height: 150px; background: #f1f4f9; border-radius: 50%; 
        margin: 0 auto 30px; overflow: hidden; border: 5px solid #fff; 
        box-shadow: 0 8px 20px rgba(0,0,0,0.1); position: relative; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
    }
    .avatar-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    
    .avatar-initials {
        width: 100%; height: 100%; background: #2e4494; color: #fff;
        display: flex; align-items: center; justify-content: center;
        font-size: 60px; font-weight: 800; text-transform: uppercase;
    }

    .view-overlay {
        position: absolute; bottom: 0; width: 100%; background: rgba(0,0,0,0.6);
        color: #fff; font-size: 9px; text-align: center; padding: 10px 0;
        transition: 0.3s; font-weight: 800; opacity: 0;
    }
    .avatar-wrapper:hover .view-overlay { opacity: 1; }

    /* FORM STYLE UTILITIES */
    .display-label { color: #64748b; font-size: 0.7rem; font-weight: 800; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; display: block; }
    .form-input-custom { 
        /* width: 100%; border: none; background: #f1f4f9; padding: 10px 15px; 
        border-radius: 10px; margin-bottom: 15px; font-weight: 500; color: #333;
        display: flex; align-items: center; min-height: 42px;
        text-transform: uppercase;
        box-sizing: border-box;
        font-size: 0.9rem; */
        width: 100%; border: 2px solid #f1f5f9; background: #f8fafc; padding: 12px 16px; 
        border-radius: 12px; margin-bottom: 20px; font-weight: 600; color: #1e293b;
        font-size: 0.9rem; transition: all 0.3s ease; box-sizing: border-box;
        text-transform: uppercase;
    }
    .no-caps { text-transform: none !important; }

    /* TAB ANIMATION */
    .tab-content { display: none; }
    .tab-content.active { display: block; animation: fadeIn 0.4s ease; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .side-action-btn { 
        /* display: block; width: 100%; padding: 15px; border-radius: 12px; font-weight: 600; text-align: center; text-decoration: none; transition: 0.3s; margin-top: 10px; border: none;  */
                display: flex; align-items: center; justify-content: center; gap: 8px;
            width: 100%; padding: 14px; border-radius: 12px; font-weight: 800; 
            text-align: center; text-decoration: none; transition: 0.3s; margin-top: 12px; 
            border: none; cursor: pointer; font-size: 0.8rem; text-transform: uppercase;
    }
    .btn-edit-main { background: #2e4494; color: #fff; }
    .btn-edit-main:hover { background: #1e2d63; color: #fff; }
    .btn-print-secondary { background: #f1f5f9; color: #64748b; }
    .btn-print-secondary:hover { background: #5a6268; color: #fff; }

    .tab-title { border-bottom: 2px solid #e32133; display: inline-block; padding-bottom: 5px; margin-bottom: 25px; color: #2e4494; font-weight: 800; }

/* @media print {
    1. Itago ang mga UI elements na hindi dapat kasama sa print
header, 
    nav, .navbar, .sidebar, .inner-sidebar, .no-print, .side-action-btn, #main-sidebar, .top-nav { display: none !important; }

    2. Siguraduhin na ang lahat ng main containers ay naka-layout nang maayos
    html, body {
        height: auto !important;
        overflow: visible !important;
        background: #fff !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .page-layout, 
    .main-wrapper, 
    #main-content, 
    .content-area, 
    .profile-container {
        display: block !important; Tanggalin ang Flexbox
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
        border: none !important;
        box-shadow: none !important;
    }

    .profile-card {
        display: block !important;
        width: 100% !important;
        padding: 10mm !important;
        border: none !important;
    }

    3. LALABAS NA ANG HEADER AT TITLES
    .print-header-wrapper {
        display: block !important; Siguradong lalabas ang logo at system record title
        margin-bottom: 20px !important;
    }

    .tab-content {
        display: block !important; Ipakita ang lahat ng sections (Personal, Contact, etc.)
        margin-bottom: 30px !important;
        page-break-inside: avoid;
    }

    .tab-title {
        display: block !important; Siguradong lalabas ang "Personal Information", "Contact Info", etc.
        color: #2e4494 !important;
        border-bottom: 2px solid #e32133 !important;
        margin-bottom: 15px !important;
        font-size: 14pt !important;
    }

    4. Ayusin ang Grid/Columns para sa papel
    .row {
        display: flex !important;
        flex-wrap: wrap !important;
        flex-direction: row !important;
    }
    .col-md-4, .col-print-4 { width: 33.33% !important; }
    .col-md-6, .col-print-6 { width: 50% !important; }
    .col-12 { width: 100% !important; }

    5. Labels at Inputs
    .display-label {
        display: block !important;
        font-size: 8pt !important;
        color: #555 !important;
        font-weight: bold !important;
        margin-bottom: 2px !important;
    }

    .form-input-custom {
        display: block !important;
        background: transparent !important;
        border-bottom: 1px solid #333 !important;
        padding: 5px 0 !important;
        margin-bottom: 10px !important;
        font-size: 10pt !important;
        min-height: auto !important;
    } */
        
/* Itago ang watermark sa normal screen view */
.print-watermark {
    display: none;
}        
    @media print {
        header, nav, .navbar, .sidebar, .inner-sidebar, .no-print, .side-action-btn, #main-sidebar, .top-nav { 
            display: none !important; 
        }

        html, body {
            height: auto !important;
            overflow: visible !important;
            background: #fff !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .page-layout, .main-wrapper, #main-content, .content-area, .profile-container {
            display: block !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            box-shadow: none !important;
        }

        .profile-card {
            display: block !important;
            width: 100% !important;
            padding: 0 !important;
            border: none !important;
        }

        .print-header-wrapper {
            display: flex !important;
            flex-direction: column;
            margin-bottom: 20px !important;
            /* display: block !important; */
        }

        .tab-content {
            display: block !important;
            margin-bottom: 25px !important;
            page-break-inside: avoid;
        }

        .tab-title {
            display: block !important;
            font-size: 14pt !important;
            margin-top: 20px !important;
            border-bottom: 1px solid #eee !important;
            border-left: 5px solid #e32133 !important;
        }

        .row {
            display: flex !important;
            flex-wrap: wrap !important;
            margin: 0 -10px !important;
        }
        
        .col-md-4, .col-print-4 { width: 33.33% !important; padding: 0 10px !important; }
        .col-md-6, .col-print-6 { width: 50% !important; padding: 0 10px !important; }
        .col-12 { width: 100% !important; padding: 0 10px !important; }

        .form-input-custom {
            background: transparent !important;
            border: none !important;
            border-bottom: 1px solid #ccc !important;
            border-radius: 0 !important;
            padding: 5px 0 !important;
            margin-bottom: 10px !important;
            font-size: 11pt !important;
            min-height: auto !important;
        }

/* Lilitaw lang ito kapag Ctrl+P */
/* Force-show sa print */
    .print-watermark {
        display: block !important;
        visibility: visible !important;
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) rotate(-45deg) !important;
        
        /* Styling */
        font-size: 80pt !important;
        font-weight: 900 !important;
        color: rgba(0, 0, 0, 0.05) !important;
        -webkit-print-color-adjust: exact !important; /* Importante para sa Chrome/Edge */
        print-color-adjust: exact !important;
        
        z-index: 9999 !important; /* Dalhin sa pinaka-ibabaw muna para makita kung working */
        pointer-events: none;
        white-space: nowrap;
        text-align: center;
    }

    /* Siguraduhin na ang profile-card ay relative para hindi lumampas ang watermark */
    .profile-card {
        position: relative;
        overflow: visible;
    }
    }
</style>
</head>

<div class="page-layout">
    <div class="main-wrapper">
        <main id="main-content" class="flex-1 overflow-y-auto p-6 lg:p-10">
            <div class="no-print flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                <a href="dashboard.php" class="hover:text-red-600 transition flex items-center gap-1.5">
                    <i data-lucide="layout-dashboard" class="w-3 h-3"></i> Dashboard
                </a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <a href="employees.php" class="hover:text-red-600 transition flex items-center gap-1.5">
                    <i data-lucide="users" class="w-3 h-3"></i> Employee List
                </a>
                <i data-lucide="chevron-right" class="w-3 h-3"></i>
                <span class="text-slate-900 flex items-center gap-1.5">
                    <i data-lucide="user-cog" class="w-3 h-3 text-red-600"></i> Employee Profile
                </span>
            </div>

            <div class="no-print flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight uppercase">Employee Master Record</h1>
                    <p class="text-slate-500 text-sm font-medium italic">TASSI Operational Real-time Database</p>
                </div>
            </div>

            <div class="profile-container">
                <div class="inner-sidebar no-print">
                    <!-- <button class="tab-btn active" onclick="openTab(event, 'personal')">PERSONAL INFORMATION</button>
                    <button class="tab-btn" onclick="openTab(event, 'contact')">CONTACT INFORMATION</button>
                    <button class="tab-btn" onclick="openTab(event, 'government')">GOVERNMENT IDS</button>
                    <button class="tab-btn" onclick="openTab(event, 'emergency')">EMERGENCY CONTACT</button>
                    <button class="tab-btn" onclick="openTab(event, 'employment')">EMPLOYMENT INFORMATION</button>
                    <?php if ($isAdmin): ?>
                        <button class="tab-btn" onclick="openTab(event, 'salary')">SALARY & DEDUCTION</button>
                    <?php endif; ?>

                    <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                    <a href="employees.php?a=edit&id=<?= $employee['id'] ?>" class="side-action-btn btn-edit-main">
                        <i></i> EDIT RECORD
                    </a>

                    <button onclick="window.print()" class="side-action-btn btn-print-secondary">
                        <i></i> PRINT RECORD
                    </button> -->
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
                        <a href="employees.php?a=edit&id=<?= $employee['id'] ?>" class="side-action-btn btn-edit-main">
                            <i data-lucide="edit" class="w-4 h-4"></i> EDIT RECORD
                        </a>                            
                        <button onclick="window.print()" class="side-action-btn btn-print-secondary">
                            <i data-lucide="printer" class="w-4 h-4"></i> PRINT RECORD
                        </button>
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

                    <div class="print-watermark">OFFICIAL RECORD</div>
                    <div class="print-header-wrapper hidden d-print-block">
                        <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
                            <div style="display: flex; align-items: center; gap: 20px;">
                                <img src="/phphr-main/phphr-main/public/media/tracelogo.png" style="width: 150px; height: auto;">
                                <div>
                                    <h4 style="margin: 0; font-weight: 800;">TRACE ALARM & SECURITY SYSTEM, INC.</h4>
                                    <p style="margin: 0; font-size: 11pt;">Official Employee Information Sheet - System Record</p>
                                    <!-- <small style="color: #666;">Printed on: <?= date('F d, Y h:i A') ?></small> -->
                                    <small style="color: #666;"> Printed on: <?= (new DateTime('now', new DateTimeZone('Asia/Manila')))->format('F d, Y h:i A') ?> </small>
                                </div>
                            </div>
                            <div style="width: 100px; height: 100px; border: 2px solid #333; display: flex; align-items: center; justify-content: center; border-radius: 5px; overflow: hidden;">
                                <?php if (!empty($imgName)): ?>
                                    <img src="<?= $displayPath ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <span style="font-size: 24pt; font-weight: bold;"><?= $initials ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <hr style="border: none; border-top: 2px solid #000; margin: 20px 0;">
                    </div>

                    <div id="personal" class="tab-content active">
                        <h5 class="tab-title">Personal Information</h5>
                        <!-- <div class="avatar-wrapper no-print" onclick="openImageModal();" style="cursor: pointer;">
                            <?php if (!empty($imgName)): ?>
                                <img id="imgView" src="<?= $displayPath ?>?v=<?= time() ?>" alt="Profile">
                                <div class="view-overlay"><i class="fas fa-search-plus"></i> VIEW FULL IMAGE</div>
                            <?php else: ?>
                                <div class="avatar-initials"><?= $initials ?></div>
                                <div class="view-overlay">NO PHOTO UPLOADED</div>
                            <?php endif; ?>
                        </div>
                        <p class="mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest"> Employee Photo </p> -->
                        <div class="flex flex-col items-center justify-center">
                            <!-- Avatar Wrapper -->
                            <div class="avatar-wrapper no-print group relative overflow-hidden rounded-full border-4 border-slate-100 shadow-sm transition-all hover:border-blue-200" 
                                onclick="openImageModal();" 
                                style="cursor: pointer; width: 120px; height: 120px;">
                                
                                <?php if (!empty($imgName)): ?>
                                    <img id="imgView" 
                                        src="<?= $displayPath ?>?v=<?= time() ?>" 
                                        alt="Profile" 
                                        class="h-full w-full object-cover">
                                    
                                    <!-- Hover Overlay -->
                                    <div class="view-overlay absolute inset-0 flex flex-col items-center justify-center bg-black/50 opacity-0 transition-opacity group-hover:opacity-100 text-white text-[9px] font-bold">
                                        <i class="fas fa-search-plus mb-1 text-base"></i>
                                        <span>VIEW FULL IMAGE</span>
                                    </div>
                                <?php else: ?>
                                    <div class="avatar-initials flex h-full w-full items-center justify-center bg-slate-200 text-2xl font-black text-slate-500">
                                        <?= $initials ?>
                                    </div>
                                    
                                    <div class="view-overlay absolute inset-0 flex items-center justify-center bg-black/40 opacity-0 transition-opacity group-hover:opacity-100 text-white text-[8px] font-bold text-center px-2">
                                        NO PHOTO UPLOADED
                                    </div>
                                <?php endif; ?>
                            </div>

                            <p class="mt-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest no-print"> Employee Photo </p>
                        </div>
                        <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                            <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                <div class="display-label">FIRST NAME</div>
                                <div class="form-input-custom"><?= htmlspecialchars($fName ?: '—') ?></div>
                            </div>
                            <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                <div class="display-label">MIDDLE NAME</div>
                                <div class="form-input-custom"><?= !empty($employee['middle_name']) ? htmlspecialchars($employee['middle_name']) : '—' ?></div>
                            </div>
                            <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                <div class="display-label">LAST NAME</div>
                                <div class="form-input-custom"><?= htmlspecialchars($lName ?: '—') ?></div>
                            </div>
                        </div>

                        <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                            <!-- <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                <div class="display-label">DATE OF BIRTH</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['date_of_birth'] ?? '—') ?></div>
                            </div> -->
                            <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                <div class="display-label">DATE OF BIRTH</div>
                                <div class="form-input-custom">
                                    <?php 
                                        if (!empty($employee['date_of_birth'])) {
                                            // I-convert ang string date papunta sa magandang format
                                            $date = new DateTime($employee['date_of_birth']);
                                            echo $date->format('F j, Y'); // Lalabas ay: January 25, 1995
                                        } else {
                                            echo "—";
                                        }
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                <div class="display-label">GENDER</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['gender'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                <div class="display-label">CIVIL STATUS</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['civil_status'] ?? '—') ?></div>
                            </div>
                        </div>

                        <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                            <div class="col-md-4" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">NATIONALITY</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['nationality'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-4" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">RELIGION</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['religion'] ?? '—') ?></div>
                            </div>
                        </div>
                    </div>

                    <div id="contact" class="tab-content">
                        <h5 class="tab-title">Contact Information</h5>
                        <div class="row" style="display: flex; flex-wrap: wrap;">
                            <div class="col-md-6 col-print-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">MOBILE NUMBER</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['phone'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-6 col-print-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">EMAIL ADDRESS</div>
                                <div class="form-input-custom no-caps"><?= htmlspecialchars($employee['email_address'] ?? '—') ?></div>
                            </div>
                        </div>
                        <div class="row" style="display: flex; flex-wrap: wrap;">
                            <div class="col-12" style="flex: 0 0 100%; padding: 0 10px;">
                                <div class="display-label">CURRENT ADDRESS</div>
                                <div class="form-input-custom" style="height: auto; min-height: 80px;"><?= nl2br(htmlspecialchars($employee['current_address'] ?? '—')) ?></div>
                            </div>
                        </div>
                    </div>

                    <div id="government" class="tab-content">
                        <h5 class="tab-title">Government Identifications</h5>
                        <div class="row" style="display: flex; flex-wrap: wrap;">
                            <div class="col-md-6 col-print-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">SSS NUMBER</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['sss_no'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-6 col-print-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">PHILHEALTH NUMBER</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['philhealth_no'] ?? '—') ?></div>
                            </div>
                        </div>
                        <div class="row" style="display: flex; flex-wrap: wrap;">
                            <div class="col-md-6 col-print-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">PAG-IBIG NUMBER</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['pagibig_no'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-6 col-print-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">TIN NUMBER</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['tin_no'] ?? '—') ?></div>
                            </div>
                        </div>
                    </div>

                    <div id="emergency" class="tab-content">
                        <h5 class="tab-title">Emergency Contact</h5>
                        <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                            <div class="col-12" style="flex: 0 0 100%; padding: 0 10px;">
                                <div class="display-label">EMERGENCY CONTACT PERSON</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['emergency_name'] ?? '—') ?></div>
                            </div>
                        </div>
                        <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                            <div class="col-md-6 col-print-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">EMERGENCY CONTACT NUMBER</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['emergency_number'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-6 col-print-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">EMERGENCY CONTACT RELATIONSHIP</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['emergency_relationship'] ?? '—') ?></div>
                            </div>
                        </div>
                    </div>

                    <div id="employment" class="tab-content">
                        <h5 class="tab-title">Employment Information</h5>
                        <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">TASSI ID NUMBER</div>
                                <div class="form-input-custom" style="color: #2e4494; font-weight: 700;"><?= htmlspecialchars($employee['employee_code'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">CARD ID NUMBER</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['CardID'] ?? '—') ?></div>
                            </div>
                        </div>
                        <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">DEPARTMENT</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['department'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">DESIGNATION</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['designation'] ?? '—') ?></div>
                            </div>
                        </div>
                        <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                            <!-- <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">DATE HIRED</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['date_of_joining'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">PROBATION END DATE</div>
                                <div class="form-input-custom"><?= (!empty($employee['probation_end_date']) && $employee['probation_end_date'] != '0000-00-00') ? htmlspecialchars($employee['probation_end_date']) : '—' ?></div>
                            </div> -->
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">DATE HIRED</div>
                                <div class="form-input-custom">
                                    <?php 
                                        if (!empty($employee['date_of_joining']) && $employee['date_of_joining'] != '0000-00-00') {
                                            echo date('F j, Y', strtotime($employee['date_of_joining']));
                                        } else {
                                            echo "—";
                                        }
                                    ?>
                                </div>
                            </div>

                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">PROBATION END DATE</div>
                                <div class="form-input-custom">
                                    <?php 
                                        if (!empty($employee['probation_end_date']) && $employee['probation_end_date'] != '0000-00-00') {
                                            echo date('F j, Y', strtotime($employee['probation_end_date']));
                                        } else {
                                            echo "—";
                                        }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">EMPLOYMENT TYPE</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['employment_type'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">REPORTING MANAGER</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['reporting_manager'] ?: '—') ?></div>
                            </div>
                        </div>
                        <div class="row" style="display: flex; flex-wrap: wrap; margin-bottom: 0;">
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">WORK LOCATION</div>
                                <div class="form-input-custom"><?= htmlspecialchars($employee['work_location'] ?? '—') ?></div>
                            </div>
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">STATUS</div>
                                <div class="form-input-custom"><?= (isset($employee['status']) && $employee['status'] == 1) ? 'ACTIVE' : 'INACTIVE' ?></div>
                            </div>
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">TIME IN</div>
                                <div class="form-input-custom">
                                    <?= htmlspecialchars($employee['time_in'] ?? '—') ?>
                                </div>
                            </div>
                            <div class="col-md-6" style="flex: 0 0 50%; padding: 0 10px;">
                                <div class="display-label">TIME OUT</div>
                                <div class="form-input-custom">
                                    <?= htmlspecialchars($employee['time_out'] ?? '—') ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if ($isAdmin): ?>
                        <div id="salary" class="tab-content">
                            <h5 class="tab-title">Salary & Deductions</h5>
                            <div class="row" style="display: flex; flex-wrap: wrap;">
                                <div class="col-md-4 col-print-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                    <div class="display-label">BASIC RATE PER DAY</div>
                                    <div class="form-input-custom">P <?= number_format((float)($employee['salary'] ?? 0), 2) ?></div>
                                </div>
                                <div class="col-md-4 col-print-4" style="flex: 0 0 33.33%; padding: 0 10px;" >
                                    <div class="display-label">HMO</div>
                                    <div class="form-input-custom">P <?= number_format((float)($employee['hmo'] ?? 0), 2) ?></div>
                                </div>
                                <div class="col-md-4 col-print-4" style="flex: 0 0 33.33%; padding: 0 10px;">
                                    <div class="display-label">EF (EMERGENCY FUND)</div>
                                    <div class="form-input-custom">P <?= number_format((float)($employee['ef'] ?? 0), 2) ?></div>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50 border border-dashed border-slate-200 rounded-2xl flex items-center gap-3 mt-6 no-print">
                                <i data-lucide="lock" class="w-4 h-4 text-slate-400"></i>
                                <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Confidential Data: Only visible to authorized administrators.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<!-- Image Zoom Modal -->
<div id="imageModal" class="fixed inset-0 z-[100] hidden no-print">
    <div class="fixed inset-0 bg-slate-900/90 backdrop-blur-sm transition-opacity" onclick="closeImageModal()"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4 pointer-events-none">
        <div class="inline-block w-full max-w-2xl overflow-hidden text-left align-middle transition-all transform bg-white rounded-2xl shadow-2xl border border-slate-200 pointer-events-auto">
            <div class="px-6 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50">
                <h3 id="caption" class="text-xl font-black text-slate-900 uppercase tracking-tighter mb-1">
                    <?= htmlspecialchars($fName . ' ' . $lName) ?>
                </h3>
                <button onclick="closeImageModal()" class="text-slate-400 hover:text-red-600 transition">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-4 bg-slate-50 flex justify-center">
                <img id="fullImage" src="" class="rounded-xl shadow-md max-h-[70vh] w-auto border-4 border-white object-contain">
            </div>
            <div class="p-8 text-center">
                <div class="flex flex-col sm:flex-row gap-3">
                    <a id="downloadBtn" href="#" download class="flex-1 px-6 py-3 bg-slate-900 text-white rounded-xl text-xs font-black uppercase tracking-widest hover:bg-red-600 transition shadow-lg shadow-slate-200 flex items-center justify-center gap-2">
                        <i data-lucide="download" class="w-4 h-4"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    function openTab(evt, tabName) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) { tabcontent[i].classList.remove("active"); }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) { tablinks[i].classList.remove("active"); }
        document.getElementById(tabName).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    function openImageModal() {
        const modal = document.getElementById("imageModal");
        const img = document.getElementById("imgView");
        const modalImg = document.getElementById("fullImage");
        const downloadBtn = document.getElementById("downloadBtn");

        if (modal) {
            modal.style.display = "flex"; // Palitan ang 'block' ng 'flex'
            if (img) {
                modalImg.src = img.src;
                downloadBtn.href = img.src;
                modalImg.style.display = "block";
            }
        }
    }

    function closeImageModal() {
        document.getElementById("imageModal").style.display = "none";
    }

    // Shortcut: Isara ang modal kapag clinick ang itim na area (labas ng image)
    window.onclick = function(event) {
        const modal = document.getElementById("imageModal");
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }
</script>