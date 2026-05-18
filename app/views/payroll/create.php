<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/left.php'; ?>

<?php if ($_SESSION['role'] === 'admin'): ?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>

<style>
    body { background-color: #f8f9fa; margin: 0; padding: 0; }
    
    /* Ito ang nagpapadikit sa Sidebar at Content */
    .page-layout { display: flex; align-items: flex-start; padding: 20px; gap: 20px; width: 100%; }

    .main-wrapper { flex-grow: 1; min-width: 0; }

    .content-area { background: white; border-radius: 25px; padding: 30px;  min-height: 90vh; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }

    .card-ui { background: white; border-radius: 8px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    
    .breadcrumb-ui { font-size: 0.90rem; font-weight: 500; color: #6c757d; }
    .breadcrumb-ui a { text-decoration: none; color: #6c757d; transition: 0.2s; }    
    .breadcrumb-ui a:hover { color: #e32133; } /* 2e4494 */
    .breadcrumb-ui span { font-weight: 600; }

    .payroll-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; table-layout: fixed; }
    .payroll-table td { border: 1px solid #dee2e6; padding: 5px 10px; height: 38px; vertical-align: middle; }
    
    .label-blue { background-color: #2c5499; color: white; font-weight: 700; font-size: 10px; text-transform: uppercase; width: 40%; }
    .value-cell { background-color: white; width: 60%; font-size: 12px; }
    .value-cell input, .value-cell select { border: none !important; width: 100%; outline: none; font-weight: 600; background: transparent; }

    /* PAYSLIP STYLING */
    .payslip-col { border: 1px solid #2c5499; min-height: 400px; display: flex; flex-direction: column; background: #fff; }
    .ps-header { background: #2c5499; color: white; padding: 5px 10px; font-weight: bold; font-size: 13px; text-transform: uppercase; }
    .ps-table { width: 100%; border-collapse: collapse; }
    .ps-table td { padding: 2px 8px; font-size: 11px; border-bottom: 1px solid #eee; vertical-align: middle; }
    
    .bullet-item { padding-left: 20px !important; position: relative; color: #555; }
    .bullet-item::before { content: "•"; position: absolute; left: 8px; color: #333; font-weight: bold; }
    .sub-bullet { padding-left: 35px !important; color: #666; font-style: italic; }
    
    .ps-input { width: 100%; border: none; border-bottom: 1px solid #ccc; text-align: right; outline: none; font-weight: bold; background: transparent; }
    .center-input { text-align: center !important; }
    
    /* Totals and Lines */
    .total-row td { border-bottom: none !important; padding-top: 10px; }
    /* .double-line { border-bottom: 3px double #333 !important; } */

    .ps-table td.double-line { border-bottom: 3px double #000 !important; padding-bottom: 5px !important; }

    /* Siguraduhin din na ang input sa loob ay walang sariling border na nakaharang */
    .double-line .ps-input { border-bottom: none !important; font-weight: 800 !important; }
    .underlined-label { text-decoration: underline; font-weight: bold; text-transform: uppercase; }
    .label { font-weight: bold; text-transform: uppercase; }
    
    .ps-footer { background: #2c5499; color: white; padding: 10px; font-weight: bold; display: flex; justify-content: space-between; margin-top: auto; font-size: 14px; }
    .section-header { color: #2c5499; font-weight: 800; font-size: 1.2rem; border-bottom: 2px solid #2c5499; margin-bottom: 20px; padding-bottom: 5px; }

    /* Mag-highlight ang row kapag active ang input sa loob nito */
    .ps-table tr:focus-within { background-color: #f0f7ff !important; outline: 1px solid #2c5499; }
    /* Para magmukhang clickable ang mga inputs */
    .ps-input:hover {  background-color: #fffdec; border-bottom: 1px solid #2c5499 !important; }

    .bg-primary { background-color: #2c5499 !important; } /* Dark blue shade from image */
    .table-bordered td { vertical-align: middle; }
    .modal-xl { max-width: 95% !important; } /* Make it almost full screen */
    #pv_earnings_table, #pv_deductions_table, #pv_personal_table {  background-color: #fff; }
    .border-end { border-right: 1px solid #dee2e6 !important; }
</style>

<!-- <div class="page-layout">

    <div class="main-wrapper">
      <main class="content-area">
        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm no-print" style="border: 1px solid #f1f1f1;">
          <nav class="breadcrumb-ui">
            <a href="dashboard.php">Dashboard</a> / 
            <a href="payroll.php?a=index">Payroll</a> / 
            <span style="color: #1c1e1b;">Generate Payroll</span>
          </nav>
          <a href="payroll.php?a=index" class="btn btn-outline-secondary btn-sm"> <i class="fas fa-arrow-left"></i> Back </a>
        </div> -->

<main id="main-content" class="flex-1 overflow-y-auto p-6 lg:p-10 sidebar-transition">
    <!-- BREADCRUMBS -->
    <div class="flex items-center gap-2 mb-6 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
        <a href="dashboard.php" class="hover:text-red-600 transition flex items-center gap-1.5">
            <i data-lucide="layout-dashboard" class="w-3 h-3"></i> Dashboard
        </a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <a href="payroll.php?a=index" class="hover:text-red-600 transition flex items-center gap-1.5">
            <i data-lucide="wallet" class="w-3 h-3"></i> Payroll List
            </a>
        <i data-lucide="chevron-right" class="w-3 h-3"></i>
        <span class="text-slate-900 flex items-center gap-1.5">
            <i data-lucide="philippine-peso" class="w-3 h-3 text-red-600"></i> Generate Payroll
        </span>
    </div>

    <!-- SECTION HEADER -->
    <div class="section-header">GENERATE PAYROLL</div>

    <!-- <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/60 border border-slate-200 mb-10 overflow-hidden"> -->
    <!-- MAIN FORM AREA -->
    <div id="payroll-capture-area" style="background: #ffffff; padding: 15px; border-radius: 8px;">
        <form id="payrollForm" method="POST" action="payroll.php?a=store">
        
            <!-- TOP SECTION: Ito ang pinalitan natin para maging swak (Tailwind Grid) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                <!-- Column 1: Employee Info -->
                <div class="overflow-hidden">
                    <table class="payroll-table w-full">
                        <tr>
                            <td class="label-blue whitespace-nowrap p-2 text-[11px] font-bold bg-slate-50 border border-slate-200">Employee Name</td>
                            <td class="value-cell border border-slate-200">
                                <select name="employee_id" id="empSearch" value="<?php echo $emp['employee_id'] ?? ''; ?> required>
                                    <option value="<?php echo $emp['employee_id'] ?? ''; ?>">Select Employee</option>
                                    <?php foreach ($employees as $emp): 
                                        $mi = !empty($emp['middle_name']) ? substr($emp['middle_name'], 0, 1) . '.' : '';
                                        $fullName = trim($emp['last_name'] . ', ' . $emp['first_name'] . ' ' . $mi);
                                        $statusText = ($emp['status'] == 1) ? 'ACTIVE' : 'INACTIVE';
                                    ?>
                                    <option value="<?= $emp['id'] ?>" 
                                            data-name="<?= htmlspecialchars($fullName) ?>"
                                            data-code="<?= htmlspecialchars($emp['employee_code'] ?? '') ?>"
                                            data-dept="<?= htmlspecialchars($emp['department'] ?? 'N/A') ?>"
                                            data-desig="<?= htmlspecialchars($emp['designation'] ?? 'N/A') ?>"
                                            data-status="<?= $statusText ?>"
                                            data-hired="<?= htmlspecialchars($emp['date_of_joining'] ?? 'N/A') ?>"
                                            data-salary="<?= htmlspecialchars($emp['salary'] ?? '0') ?>"
                                            data-hmo="<?= htmlspecialchars($emp['hmo'] ?? '0') ?>" 
                                            data-ef="<?= htmlspecialchars($emp['ef'] ?? '0') ?>">
                                            <?= htmlspecialchars($fullName) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                        </tr>
                        
                    <tr><td class="label-blue">Employee ID</td><td class="value-cell"><input type="text" id="disp_id"  readonly placeholder="AUTO-FILL" ></td></tr>
                    <tr><td class="label-blue">Basic Rate Per Day</td><td class="value-cell"><input type="number" name="basic_rate" id="rate" readonly placeholder="AUTO-FILL"></td></tr>
                    <tr><td class="label-blue">Number of Regular Worked Days</td><td class="value-cell"><input type="number" step="1" name="regular_days" id="days" oninput="compute()"></td></tr>
                    </table>
                </div>

                <!-- Column 2: Department/Status -->
                <div class="col-md-4">
                    <table class="payroll-table">
                        <tr><td class="label-blue">Department</td><td class="value-cell"><input type="text" id="department" readonly placeholder="AUTO-FILL"></td></tr>
                        <tr><td class="label-blue">Designation</td><td class="value-cell"><input type="text" id="designation" readonly placeholder="AUTO-FILL"></td></tr>
                        <tr><td class="label-blue">Employee Status</td><td class="value-cell"><input type="text" id="status" readonly placeholder="AUTO-FILL"></td></tr>
                        <tr><td class="label-blue">Date Hired</td><td class="value-cell"><input type="text" id="date_of_joining" readonly placeholder="AUTO-FILL"></td></tr>
                    </table>
                </div>

                <!-- Column 3: Period Info -->
                <div class="col-md-4">
                    <table class="payroll-table">
                        <tr><td class="label-blue">Period Starting</td><td class="value-cell"><input type="date" name="period_start" required></td></tr>
                        <tr><td class="label-blue">Period Ending</td><td class="value-cell"><input type="date" name="period_end" required></td></tr>
                        <tr><td class="label-blue">Payment Date</td><td class="value-cell"><input type="date" name="payment_date" required></td></tr>
                        <tr><td class="label-blue">Payroll Frequency</td><td class="value-cell"><select name="frequency"><option value="SEMI-MONTHLY">SEMI-MONTHLY</option><option value="MONTHLY">MONTHLY</option></select></td></tr>
                    </table>
                </div>
            </div>

            <!-- BOTTOM SECTION: Earnings and Deductions (Responsive Grid) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-0 border border-slate-200 rounded-xl overflow-hidden">
                <div class="col-md-4 d-flex">
                    <div class="payslip-col w-100"><div class="ps-header">EARNINGS / INCOME</div>
                        <table class="ps-table">
                            <tr><td class="underlined-label">BASIC</td><td></td><td colspan="2"><input type="number" id="basic_disp" class="ps-input" readonly></td></tr>
                            <tr><td class="fw-bold" style="padding-top:5px;">OT/HOLIDAY PAY:</td><td class="text-center fw-bold small">No. of Hr.</td><td></td></tr>
                            <tr><td class="bullet-item">Regular OT (25%)</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input earn-calc ot-input" placeholder="-" name="reg_ot" oninput="compute()"></td></tr>
                            <tr><td class="sub-bullet">Reg. OT ND</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input earn-calc ot-input" placeholder="-" name="reg_ot_nd" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">SNWH/Sun/RD (130%)</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input earn-calc ot-input" placeholder="-" name="sun_ot" oninput="compute()"></td></tr>
                            <tr><td class="sub-bullet">SH/RD/Sun ND:</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input earn-calc ot-input" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">RH (100%)</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input earn-calc ot-input" placeholder="-" name="rh_pay" oninput="compute()"></td></tr>
                            <tr><td class="sub-bullet">RH-NDIFF:</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input earn-calc ot-input" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">CMS OT - 30% only</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input earn-calc ot-input" placeholder="-" name="cms_ot" oninput="compute()"></td></tr>
                            <tr class="total-row"><td colspan="2" class="text-end fw-bold">TOTAL OT PAY:</td><td class="double-line"><input type="number" id="total_ot" class="ps-input" readonly value="0.00"></td></tr>

                            <input type="hidden" name="allowances" id="hidden_allowances" value="0.00">
                            <input type="hidden" name="deductions" id="hidden_deductions" value="0.00">
                            <input type="hidden" name="net_salary" id="hidden_net_salary" value="0.00">
                            <input type="hidden" name="payslip_image" id="payslip_image">
                            <input type="hidden" name="payslip_path" id="payslip_path_input">

                            <tr><td class="underlined-label" colspan="3" style="padding-top:10px;">ALLOWANCES</td></tr>
                            <tr><td class="bullet-item">Daily (/13 Days)</td><td><input type="number" class="ps-input center-input" placeholder="Days"></td><td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Manager/Head/Supervisor</td><td></td><td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Special Task</td><td></td><td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Special Allowance</td><td></td><td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">BCP (200/day)</td><td><input type="number" id="bcpDays" class="ps-input center-input" placeholder="Days" min="0" max="31"></td><td><input type="number" id="bcpResult" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">CMS HO (150/NS)</td><td><input type="number" class="ps-input center-input" placeholder="NS"></td><td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Others:</td><td></td><td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="underlined-label">ADJ:</td><td></td><td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="underlined-label">OTHERS:</td><td></td><td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr class="total-row"><td colspan="2" class="text-end fw-bold">TOTAL ALLOWANCE:</td><td class="double-line"><input type="number" id="total_allow" class="ps-input" readonly value="0.00"></td></tr>

                            <tr><td class="underlined-label" colspan="3" style="padding-top:10px;">INCENTIVES</td></tr>
                            <tr>
                                <td class="bullet-item">Attendance & Punctuality</td>
                                <td>
                                    <select id="monthSelect" class="ps-input center-input" style="font-size: 10px; border:none; border-bottom: 1px solid #ccc;">
                                        <option value="" disabled selected hidden>Month</option>
                                        <option value="JAN">JANUARY</option><option value="FEB">FEBRUARY</option><option value="MAR">MARCH</option><option value="APR">APRIL</option><option value="MAY">MAY</option><option value="JUN">JUNE</option>
                                        <option value="JUL">JULY</option><option value="AUG">AUGUST</option><option value="SEP">SEPTEMBER</option><option value="OCT">OCTOBER</option><option value="NOV">NOVEMBER</option><option value="DEC">DECEMBER</option>
                                    </select>
                                </td>
                                <td><input type="number" id="amountInput" class="ps-input earn-calc inc-calc" placeholder="-" oninput="compute()"></td>
                            </tr>
                            <tr><td class="bullet-item">Others</td><td><input type="text" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input earn-calc inc-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Others</td><td><input type="text" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input earn-calc inc-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Others</td><td><input type="text" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input earn-calc inc-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr class="total-row"><td colspan="2" class="text-end fw-bold">TOTAL INCENTIVES:</td><td class="double-line"><input type="number" id="total_inc" class="ps-input" readonly value="0.00"></td></tr>
                        <td></td>
                        </table>
                        <div class="ps-footer">
                            <span>GROSS SUB-TOTAL PAY:</span>
                            <span id="gross_val">₱ 0.00</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 d-flex">
                    <div class="payslip-col w-100 h-full" style="border-left:none; border-right:none;"><div class="ps-header">DEDUCTIONS</div>
                        <table class="ps-table">
                            <tr><td class="underlined-label">TIME & ATTENDANCE</td></tr>
                            <tr><td class="bullet-item">Tardiness</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input deduct-calc dtr-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Undertime</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input deduct-calc dtr-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Adjustments</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input deduct-calc dtr-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr class="total-row"><td colspan="2" class="text-end fw-bold">DTR TOTAL DEDUCTION:</td><td class="double-line"><input type="number" id="total_dtr" class="ps-input" readonly value="0.00"></td></tr>
                            <td></td>
                            <tr style="background:#2c5499; color:white;"><td colspan="2" class="fw-bold" style="padding: 8px;">GROSS PAY:</td><td class="text-end fw-bold" id="gross_pay_val" style="padding: 5px 10px; font-weight: bold; font-size: 13px; text-transform: uppercase;">₱ 0.00</td></tr>
                            <tr><td class="underlined-label" colspan="3" style="padding-top:10px;">MANDATORY CONTRIBUTIONS</td></tr>                 
                            <tr><td class="bullet-item">SSS EE</td><td></td><td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">SSS WISP</td><td></td><td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">PHILHEALTH EE</td><td></td><td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">PAG-IBIG EE</td><td></td><td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="underlined-label" colspan="3" style="padding-top:10px;">LOANS</td></tr>
                            <tr><td class="fw-bold">• SSS</td><td></td></tr>
                            <tr><td class="sub-bullet">Salary Loan</td><td></td><td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="sub-bullet">Calamity Loan</td><td></td><td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="fw-bold">• PAG-IBIG</td><td></td></tr>
                            <tr><td class="sub-bullet">Multi-Purpose Loan</td><td></td><td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="sub-bullet">Calamity Loan</td><td></td><td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td></tr>
                            <!-- <tr><td class="underlined-label">WITHHOLDING TAX</td><td></td><td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td></tr> -->
                            <tr class="total-row"><td class="underlined-label" style="padding-top:10px;">WITHHOLDING TAX</td><td></td><td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="underlined-label">HMO: </td><td><input type="text" class="ps-input center-input" value="Aug 2025-July 2026"></td><td><input type="number" id="hmo" name="hmo" class="ps-input deduct-calc" readonly placeholder="AUTO-FILL" oninput="compute()"></td></tr>
                            <tr><td class="underlined-label">OTHERS:</td><td></td><td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr class="total-row"><td colspan="2" class="text-end fw-bold">TOTAL DEDUCTION:</td><td class="double-line"><input type="number" id="total_deduct_val" class="ps-input" readonly value="0.00"></td></tr>
                        </table>
                        <div class="ps-footer" style="background:#2c5499">
                            <span>NET SUB-TOTAL:</span>
                            <span id="net_sub_val">₱ 0.00</span>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 d-flex">
                    <div class="payslip-col w-100 h-full"><div class="ps-header">PERSONAL DEDUCTIONS</div>
                        <table class="ps-table">
                            <tr><td class="bullet-item">EF</td><td></td><td><input type="number" id="ef" name="ef" class="ps-input pers-calc" readonly placeholder="AUTO-FILL"></td></tr>
                            <tr><td class="bullet-item">Paluwagan - A</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Paluwagan - B</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Paluwagan - C</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Paluwagan - D</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Paluwagan - E</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Paluwagan - F (JAN-JUN)</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Paluwagan - G (JAN-JUN)</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">GADGETS</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">GADGETS</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">APLLIANCE</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">APLLIANCE</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">COOP</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">COOP</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">COOP</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">COOP</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Donation</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Contribution</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Others</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Others</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Others</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Others</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Others</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Others</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr><td class="bullet-item">Others</td><td><input type="number" class="ps-input center-input" placeholder="-"></td><td><input type="number" class="ps-input pers-calc" placeholder="-" oninput="compute()"></td></tr>
                            <tr class="total-row"><td colspan="2" class="text-end fw-bold">TOTAL PERSONAL DEDUCTION:</td><td class="double-line"><input type="number" id="total_pers" class="ps-input" readonly value="0.00"></td></tr>
                        </table>
                        <div class="ps-footer" style="background:#2c5499"><span>FINAL NET PAY:</span><span id="final_val">₱ 0.00</span></div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div class="mt-6 flex justify-end items-center gap-3 w-full no-print">
        <!-- Preview Button -->
        <button type="button" id="btnPreview" disabled
                class="flex items-center px-4 py-2 text-sm font-medium text-slate-600 bg-slate-50 border border-slate-200 rounded-lg shadow-sm hover:bg-slate-100 hover:text-blue-600 disabled:bg-slate-300 disabled:cursor-not-allowed disabled:shadow-none transition-all duration-200">
                <!-- class="flex items-center px-4 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg shadow-lg hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed disabled:shadow-none transition-all duration-200 ml-2"> -->
            <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
            Preview
        </button>

        <!-- Excel Export Button -->
        <button type="button" id="btnExport" 
                class="flex items-center px-4 py-2 text-sm font-medium text-emerald-700 bg-emerald-50 border border-emerald-100 rounded-lg shadow-sm hover:bg-emerald-100 hover:shadow-md transition-all duration-200">
            <i data-lucide="file-spreadsheet" class="w-4 h-4 mr-2"></i>
            Excel Export
        </button>

        <!-- Clear Button -->
<button type="button" 
        onclick="openClearModal()"
        class="flex items-center px-4 py-2 text-sm font-medium text-red-700 bg-red-50 border border-red-100 rounded-lg hover:bg-red-100 transition-all">
    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i> Clear
</button>

        <!-- Generate Button (Main Action) -->
        <button type="button" id="btnGenerate" disabled
                class="flex items-center px-6 py-2 text-sm font-bold text-white bg-blue-600 rounded-lg shadow-lg hover:bg-blue-700 disabled:bg-slate-300 disabled:cursor-not-allowed disabled:shadow-none transition-all duration-200 ml-2">
            <i data-lucide="cpu" class="w-5 h-5 mr-2"></i>
            Generate Payroll
        </button>
    </div>
</main>

<!-- PREVIEW MODAL -->
<div id="previewPayrollModal" class="fixed inset-0 z-[150] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closePreviewModal()"></div>
    <div class="flex items-center justify-center min-h-screen p-4 w-full">
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-[1150px] flex flex-col overflow-hidden max-h-[95vh] border border-slate-200">
            <div class="px-6 py-4 bg-primary text-white flex justify-between items-center shrink-0">
                <div class="flex items-center gap-3">
                    <i data-lucide="eye" class="w-5 h-5"></i>
                    <h5 class="font-black uppercase tracking-widest text-sm m-0">Payroll Slip Preview</h5>
                </div>
                <button onclick="closePreviewModal()" class="text-white/50 hover:text-white transition-all transform hover:rotate-90">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>

            <div class="p-6 overflow-y-auto bg-slate-50 custom-scrollbar flex-1">
                <div id="payroll-capture-area" class="bg-white shadow-sm rounded-xl border border-slate-300 overflow-hidden mx-auto w-full" style="font-size: 11px;">
                    <div class="flex border-b border-slate-300 bg-slate-50">
                        <div class="flex-1 border-r border-slate-300 p-2">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><td class="bg-primary text-white fw-bold px-2" width="45%">EMPLOYEE NAME</td><td id="pv_name" class="ps-2 fw-bold text-uppercase border-b border-slate-100"></td></tr>
                                <tr><td class="bg-primary text-white fw-bold px-2">EMPLOYEE ID</td><td id="pv_id" class="ps-2 border-b border-slate-100"></td></tr>
                                <tr><td class="bg-primary text-white fw-bold px-2">BASIC RATE</td><td id="pv_rate" class="ps-2 border-b border-slate-100">0.00</td></tr>
                                <tr><td class="bg-primary text-white fw-bold px-2">WORKED DAYS</td><td id="pv_days" class="ps-2">0</td></tr>
                            </table>
                        </div>
                        <div class="flex-1 border-r border-slate-300 p-2">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><td class="bg-primary text-white fw-bold px-2" width="45%">DEPARTMENT</td><td id="pv_dept" class="ps-2 border-b border-slate-100"></td></tr>
                                <tr><td class="bg-primary text-white fw-bold px-2">DESIGNATION</td><td id="pv_desig" class="ps-2 border-b border-slate-100"></td></tr>
                                <tr><td class="bg-primary text-white fw-bold px-2">STATUS</td><td id="pv_status" class="ps-2 text-uppercase border-b border-slate-100"></td></tr>
                                <tr><td class="bg-primary text-white fw-bold px-2">DATE HIRED</td><td id="pv_hired" class="ps-2"></td></tr>
                            </table>
                        </div>
                        <div class="flex-1 p-2">
                            <table class="table table-sm table-borderless mb-0">
                                <tr><td class="bg-primary text-white fw-bold px-2" width="45%">PERIOD START</td><td id="pv_start" class="ps-2 border-b border-slate-100"></td></tr>
                                <tr><td class="bg-primary text-white fw-bold px-2">PERIOD END</td><td id="pv_end" class="ps-2 border-b border-slate-100"></td></tr>
                                <tr><td class="bg-primary text-white fw-bold px-2">PAY DATE</td><td id="pv_paydate" class="ps-2 border-b border-slate-100"></td></tr>
                                <tr><td class="bg-primary text-white fw-bold px-2">FREQUENCY</td><td id="pv_freq" class="ps-2 text-uppercase">Monthly</td></tr>
                            </table>
                        </div>
                    </div>

                    <div class="flex bg-white min-h-[400px]">
                        <div class="flex-1 border-r border-slate-300 flex flex-col">
                            <div class="bg-primary text-white text-center py-1 fw-bold border-b border-slate-300 uppercase">Earnings / Income</div>
                            <div class="p-2 flex-grow">
                                <table class="table table-sm table-borderless mb-0" style="table-layout: fixed; width: 100%;">
                                    <colgroup> <col style="width: 45%;"><col style="width: 30%;"><col style="width: 25%;"> </colgroup>
                                    
                                    <tr class="fw-bold border-b border-slate-200">
                                        <td colspan="2" class="py-1 text-[10px]">BASIC SALARY</td>
                                        <td class="text-end" id="pv_basic_val">0.00</td>
                                    </tr>

                                    <tbody id="pv_ot_body"></tbody>
                                    <tr class="fw-bold border-t border-dark">
                                        <td></td> <td class="text-end text-[10px] whitespace-nowrap pr-1">TOTAL OT PAY:</td><td class="text-end" id="pv_ot_total">0.00</td>
                                    </tr>

                                    <tbody id="pv_allow_body"></tbody>
                                    <tr class="fw-bold border-t border-dark">
                                        <td></td> <td class="text-end text-[10px] whitespace-nowrap pr-1">TOTAL ALLOWANCE:</td><td class="text-end" id="pv_allow_total">0.00</td>
                                    </tr>

                                    <tbody id="pv_incentive_body"></tbody>
                                    <tr class="fw-bold border-t border-dark">
                                        <td></td> <td class="text-end text-[10px] whitespace-nowrap pr-1">TOTAL INCENTIVES:</td><td class="text-end" id="pv_incent_total">0.00</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="bg-primary text-white flex justify-between p-2 fw-bold">
                                <span>GROSS SUB-TOTAL:</span><span id="pv_gross_sub">0.00</span>
                            </div>
                        </div>

                        <div class="flex-1 border-r border-slate-300 flex flex-col bg-slate-50/30">
                            <div class="bg-primary text-white text-center py-1 fw-bold border-b border-slate-300 uppercase">Deductions</div>
                            <div class="p-2 flex-grow">
                                <table class="table table-sm table-borderless mb-0" style="table-layout: fixed; width: 100%;">
                                    <colgroup> <col style="width: 45%;"><col style="width: 30%;"><col style="width: 25%;"> </colgroup>
                                    
                                    <tbody id="pv_dtr_body"></tbody>
                                    <tr class="fw-bold border-t border-dark">
                                        <td></td> <td class="text-end text-[10px] whitespace-nowrap pr-1">DTR TOTAL DEDUCTION:</td><td class="text-end" id="pv_dtr_total">0.00</td>
                                    </tr>

                                    <tbody id="pv_deduct_body"></tbody>
                                    <tr class="fw-bold border-t border-dark">
                                        <td></td> <td class="text-end text-[10px] whitespace-nowrap pr-1">TOTAL DEDUCTION:</td><td class="text-end" id="pv_total_deduct">0.00</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="bg-primary text-white flex justify-between p-2 fw-bold">
                                <span>NET SUB-TOTAL:</span><span id="pv_net_sub">0.00</span>
                            </div>
                        </div>

                        <div class="flex-1 flex flex-col">
                            <div class="bg-primary text-white text-center py-1 fw-bold border-b border-slate-300 uppercase">Personal Deductions</div>
                            <div class="p-2 flex-grow">
                                <table class="table table-sm table-borderless mb-0" style="table-layout: fixed; width: 100%;">
                                    <colgroup> <col style="width: 20%;"><col style="width: 55%;"><col style="width: 25%;"> </colgroup>
                                    
                                    <tbody id="pv_pers_body"></tbody>
                                    <tr class="fw-bold border-t border-dark">
                                        <td></td><td class="text-end text-[10px] whitespace-nowrap pr-1">TOTAL PERSONAL DEDUCTION:</td><td class="text-end" id="pv_pers_total">0.00</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="bg-primary text-white flex justify-between p-2 fw-bold">
                                <span>FINAL NET PAY:</span><span id="pv_final_net">0.00</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 border-t border-slate-100 flex justify-end items-center gap-3 bg-white shrink-0">
                <button onclick="closePreviewModal()" class="px-6 py-2 text-slate-400 text-[10px] font-black uppercase tracking-widest hover:text-slate-600 transition-colors">Cancel</button>
                <button id="confirmPreviewBtn" class="flex items-center gap-2 px-8 py-2 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-100 hover:scale-105 transition-all">
                    <i data-lucide="check" class="w-4 h-4"></i> Confirm & Generate
                </button>
            </div>
        </div>
    </div>
</div>

<div id="clearPayrollModal" class="fixed inset-0 z-[200] hidden overflow-y-auto">
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeClearModal()"></div>
    <div class="flex items-center justify-center min-h-screen p-4 w-full">
        <div class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-8 text-center border border-slate-100">
            <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="refresh-cw" class="w-10 h-10 text-primary"></i>
            </div>
            <h3 class="text-xl font-black text-slate-900 uppercase tracking-tight mb-2">Clear All Entries?</h3>
            <p class="text-slate-500 text-sm mb-8 px-4">Sigurado ka ba? Marereset lahat ng input fields at mawawala ang mga na-compute na data.</p>
            <div class="flex gap-3">
                <button onclick="closeClearModal()" class="flex-1 px-6 py-3 bg-slate-100 text-slate-600 rounded-xl text-[10px] font-black uppercase tracking-widest hover:bg-slate-200 transition">Keep Working</button>
                <button id="confirmClearBtn" class="flex-1 px-6 py-3 bg-primary text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-100 hover:bg-blue-700 transition">Yes, Clear All</button>
            </div>
        </div>
    </div>
</div>

<script>
lucide.createIcons();

// --- 1. MODAL CONTROLS ---
function openPreviewModal() { 
    $('#previewPayrollModal').removeClass('hidden').addClass('flex'); 
    $('body').css('overflow', 'hidden'); 
}
function closePreviewModal() { 
    $('#previewPayrollModal').addClass('hidden').removeClass('flex'); 
    $('body').css('overflow', 'auto'); 
}
function openClearModal() { 
    $('#clearPayrollModal').removeClass('hidden').addClass('flex'); 
    $('body').css('overflow', 'hidden'); 
}
function closeClearModal() { 
    $('#clearPayrollModal').addClass('hidden').removeClass('flex'); 
    $('body').css('overflow', 'auto'); 
}

// --- 2. CLEAR FORM LOGIC (FIXED) ---
function processClearForm() {
    // I-reset ang main form elements
    const form = document.getElementById('payrollForm');
    if(form) form.reset();

    // I-reset ang Select2 (Employee Search)
    $('#empSearch').val(null).trigger('change');

    // I-zero lahat ng input fields na ginagamit sa calculation
    $('.earn-calc, .inc-calc, .deduct-calc, .dtr-calc, .pers-calc, .ot-input, .ps-input').val('');
    
    // Siguraduhin ang mga specific summary fields ay zero
    $('#basic_disp, #total_ot, #total_allow, #total_inc, #total_dtr, #total_deduct_val, #total_pers, #rate, #days').val('');
    
    // I-reset ang mga text labels sa dashboard
    $('#gross_val, #gross_pay_val, #net_sub_val, #final_val').text('₱ 0.00');
    
    // Linisin ang mga read-only info fields
    $('#disp_id, #department, #designation, #status, #date_of_joining').val('');

    // Re-run computation para mag-sync ang hidden fields
    compute(); 
    // I-disable ulit ang mga buttons
    validateGenerateButton();
    closeClearModal();
}

$(document).ready(function() {
    // --- 3. INITIALIZATION & EMPLOYEE SEARCH ---
    $('#empSearch').select2({ width: '100%' });
///BAGONG CODE


    // --- DITO MO ISISINGIT (START) ---
    const urlParams = new URLSearchParams(window.location.search);
    const getCardNo = urlParams.get('card_no');
    const getStart  = urlParams.get('start');
    const getEnd    = urlParams.get('end');

    // Hanapin ang employee base sa card_no mula sa URL
    if (getCardNo) {
        $('#empSearch option').each(function() {
            if ($(this).data('code') == getCardNo) {
                $('#empSearch').val($(this).val()).trigger('change');
                return false; 
            }
        });
    }

    // Ilagay ang dates mula sa URL
    if (getStart) $('input[name="period_start"]').val(getStart);
    if (getEnd)   $('input[name="period_end"]').val(getEnd);
    
    // Auto-compute at check button status
    if (getCardNo || getStart) {
        setTimeout(() => {
            compute();
            validateGenerateButton();
        }, 500);
    }

//END BAGONG CODE
    $('#empSearch').on('change', function() {
        const sel = $(this).find(':selected');
        $('#disp_id').val(sel.data('code'));
        $('#department').val(sel.data('dept'));
        $('#designation').val(sel.data('desig'));
        $('#status').val(sel.data('status'));
        $('#date_of_joining').val(sel.data('hired'));
        $('#rate').val(sel.data('salary'));
        $('#hmo').val(sel.data('hmo'));
        $('#ef').val(sel.data('ef'));
        
        compute();
        validateGenerateButton();
    });

    // --- 4. VALIDATION LISTENERS (Kailangan para sa Preview/Generate lock) ---
    // Binabantayan nito ang bawat galaw sa mga date at days fields
    $('#days, input[name="period_start"], input[name="period_end"], input[name="payment_date"]').on('input change', function() {
        validateGenerateButton();
    });

    // Enter key navigation
    $('input').on('keydown', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            var inputs = $(this).closest('form').find(':input:visible');
            inputs.eq(inputs.index(this) + 1).focus();
        }
    });

    // Auto-format decimals on blur
    $(document).on('blur', '.earn-calc, .inc-calc, .deduct-calc, .dtr-calc, .pers-calc', function() {
        let value = parseFloat($(this).val());
        if (!isNaN(value)) { $(this).val(value.toFixed(2)); }
    });

    $(document).on('focus', 'input', function() { $(this).select(); });

// --- 5. PREVIEW PAYSLIP LOGIC (UPDATED) ---
$('#btnPreview').on('click', function() {
    // Double check validation bago mag-open
    if($(this).prop('disabled')) return;

    // Employee Info Mapping
    $('#pv_name').text($('#empSearch option:selected').text());
    $('#pv_id').text($('#disp_id').val());
    $('#pv_rate').text($('#rate').val());
    $('#pv_days').text($('#days').val());
    $('#pv_dept').text($('#department').val());
    $('#pv_desig').text($('#designation').val());
    $('#pv_status').text($('#status').val());
    $('#pv_hired').text($('#date_of_joining').val());
    $('#pv_start').text($('input[name="period_start"]').val());
    $('#pv_end').text($('input[name="period_end"]').val());
    $('#pv_paydate').text($('input[name="payment_date"]').val());
    $('#pv_freq').text($('select[name="frequency"]').val());

    // Updated Table Row Generator: Inayos ang HMO alignment at spacing
    function getTableRows(selector) {
        let html = '';
        $(selector).each(function() {
            let amount = parseFloat($(this).val()) || 0;
            if (amount > 0) {
                let tr = $(this).closest('tr');
                let label = tr.find('td:first').text().trim().replace('•', '');
                let qty = tr.find('.center-input').val() || '';
                
                // Special handling para sa Attendance/Incentive months
                if (label.toLowerCase().includes("attendance") || label.toLowerCase().includes("punctuality")) {
                    let month = tr.find('select').val() || '';
                    label = label + " (" + month + ")";
                    qty = ""; // Iwas double display kung nakuha na sa label
                }

                // Layout fix para sa HMO (Aug 2025-July 2026) para hindi mag-split sa dalawang linya
                html += `
                    <tr>
                        <td width="55%" class="py-0 text-[10px] whitespace-nowrap">${label}</td>
                        <td width="20%" class="py-0 text-center fw-bold">${qty}</td>
                        <td width="25%" class="py-0 text-end fw-bold">${amount.toLocaleString(undefined,{minimumFractionDigits:2})}</td>
                    </tr>`;
            }
        });
        return html;
    }

    // Populate Modal Tables
    $('#pv_ot_body, #pv_allow_body, #pv_incentive_body, #pv_dtr_body, #pv_deduct_body, #pv_pers_body').html('');
    
    // Basic Salary
    $('#pv_basic_val').text($('#basic_disp').val());

    // Dynamic Bodies
    $('#pv_ot_body').html(getTableRows('.ot-input'));
    $('#pv_allow_body').html(getTableRows('.earn-calc:not(.ot-input, .inc-calc)'));
    $('#pv_incentive_body').html(getTableRows('.inc-calc, .incent-calc'));
    $('#pv_dtr_body').html(getTableRows('.dtr-calc'));
    $('#pv_deduct_body').html(getTableRows('.deduct-calc:not(.dtr-calc)'));
    $('#pv_pers_body').html(getTableRows('.pers-calc'));

    // --- [FIX] MAPPING TOTAL VALUES TO MODAL ---
    // Kinukuha nito ang mga kinalkula sa main form (compute function) at ipinapasa sa modal
    
    // Earnings Totals
    $('#pv_ot_total').text($('#total_ot').val() || '0.00');
    $('#pv_allow_total').text($('#total_allow').val() || '0.00');
    $('#pv_incent_total').text($('#total_inc').val() || '0.00');

    // Deductions Totals
    $('#pv_dtr_total').text($('#total_dtr').val() || '0.00');
    
    // Check kung ang total deduction sa main form ay input field o plain text
    let mainTotalDeduct = $('#total_deduct_val').is('input') ? $('#total_deduct_val').val() : $('#total_deduct_val').text();
    $('#pv_total_deduct').text(mainTotalDeduct || '0.00');

    // Personal Deduction Total
    $('#pv_pers_total').text($('#total_pers').val() || '0.00');

    // Bottom Summary Values (Pinapaganda ang formatting)
    $('#pv_gross_sub').text($('#gross_val').text().replace('₱ ', ''));
    $('#pv_net_sub').text($('#net_sub_val').text().replace('₱ ', ''));
    $('#pv_final_net').text($('#final_val').text().replace('₱ ', ''));

    openPreviewModal();
});

    // --- 6. ACTION BUTTONS ---
    $('#confirmPreviewBtn').on('click', function() {
        closePreviewModal();
        $('#btnGenerate').click(); 
    });

    $('#confirmClearBtn').on('click', function() {
        processClearForm();
    });

    $('#btnGenerate').on('click', function() {
        const btn = $(this);
        if(btn.prop('disabled')) return;

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Saving...');
        
        // Image Capture Logic
        const empName = $('#empSearch option:selected').text().trim();
        const filename = empName.replace(/[\\/:*?"<>|]/g, '') + "_" + Date.now() + ".png";

        html2canvas(document.querySelector("#payroll-capture-area"), {
            scale: 2, useCORS: true, backgroundColor: "#ffffff"
        }).then(canvas => {
            const imageData = canvas.toDataURL("image/png");
            $('#payslip_image').val(imageData);
            
            $.ajax({
                type: "POST",
                url: "payroll.php?a=save_image", 
                data: { image: imageData, filename: filename },
                success: function() {
                    $('#payslip_path_input').val("uploads/payslips/" + filename); 
                    document.getElementById('payrollForm').submit();
                }
            });
        });
    });

    // BCP/Attendance Logic
    $('#bcpDays').on('input', function() {
        let days = parseFloat($(this).val()) || 0;
        if (days > 31) { $(this).val(31); days = 31; }
        $('#bcpResult').val((days * 200).toFixed(2));
        compute();
    });

    $('#monthSelect').on('change', function() {
        $('#amountInput').val("150.00");
        compute();
    });
});

// --- 7. CALCULATION ENGINE ---
function compute() {
    const r = parseFloat($('#rate').val()) || 0;
    const d = parseFloat($('#days').val()) || 0;
    const basic = r * d;
    $('#basic_disp').val(basic.toFixed(2));

    let otTotal = 0; $('.ot-input').each(function() { otTotal += parseFloat($(this).val()) || 0; });
    $('#total_ot').val(otTotal.toFixed(2));

    let incTotal = 0; $('.inc-calc').each(function() { incTotal += parseFloat($(this).val()) || 0; });
    $('#total_inc').val(incTotal.toFixed(2));

    let allowTotal = 0;
    $('.earn-calc').not('.ot-input, .inc-calc').each(function() { allowTotal += parseFloat($(this).val()) || 0; });
    $('#total_allow').val(allowTotal.toFixed(2));

    const grossSub = basic + otTotal + allowTotal + incTotal;
    $('#gross_val').text('₱ ' + grossSub.toLocaleString(undefined, {minimumFractionDigits: 2}));

    let dtrDed = 0; $('.dtr-calc').each(function() { dtrDed += parseFloat($(this).val()) || 0; });
    $('#total_dtr').val(dtrDed.toFixed(2));

    const grossPay = grossSub - dtrDed;
    $('#gross_pay_val').text('₱ ' + grossPay.toLocaleString(undefined, {minimumFractionDigits: 2}));

    let mandatoryLoansTotal = 0;
    $('.deduct-calc').not('.dtr-calc').each(function() { mandatoryLoansTotal += parseFloat($(this).val()) || 0; });

    if ($('#total_deduct_val').is('input')) {
        $('#total_deduct_val').val(mandatoryLoansTotal.toFixed(2));
    } else {
        $('#total_deduct_val').text(mandatoryLoansTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
    }

    const netSubTotal = grossPay - mandatoryLoansTotal;
    $('#net_sub_val').text('₱ ' + netSubTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));

    let persTotal = 0; $('.pers-calc').each(function() { persTotal += parseFloat($(this).val()) || 0; });
    $('#total_pers').val(persTotal.toFixed(2));

    const finalNet = netSubTotal - persTotal;
    $('#final_val').text('₱ ' + finalNet.toLocaleString(undefined, {minimumFractionDigits: 2}));

    // Hidden Fields
    $('#hidden_allowances').val((otTotal + allowTotal + incTotal).toFixed(2));
    $('#hidden_deductions').val((dtrDed + mandatoryLoansTotal + persTotal).toFixed(2));
    $('#hidden_net_salary').val(finalNet.toFixed(2));
}

// --- 8. VALIDATION FUNCTION (PREVIEW & GENERATE LOCK) ---
function validateGenerateButton() {
    const workedDays = parseFloat($('#days').val()) || 0;
    const periodStart = $('input[name="period_start"]').val();
    const periodEnd = $('input[name="period_end"]').val();
    const paymentDate = $('input[name="payment_date"]').val();
    const empSelected = $('#empSearch').val();
    
    // Kinakailangan na lahat ito ay may laman bago maging clickable ang buttons
    const isValid = (workedDays > 0 && periodStart !== "" && periodEnd !== "" && paymentDate !== "" && empSelected !== null && empSelected !== "");
    
    // I-update ang status ng buttons
    $('#btnGenerate').prop('disabled', !isValid);
    $('#btnPreview').prop('disabled', !isValid);
    
    // Visual feedback para sa user (Optional: Opacity adjustment)
    if(isValid) {
        $('#btnPreview').removeClass('opacity-50 cursor-not-allowed').addClass('opacity-100 cursor-pointer');
        $('#btnGenerate').removeClass('bg-slate-300').addClass('bg-blue-600');
    } else {
        $('#btnPreview').addClass('opacity-50 cursor-not-allowed').removeClass('opacity-100 cursor-pointer');
        $('#btnGenerate').addClass('bg-slate-300').removeClass('bg-blue-600');
    }
}
</script>
<?php endif; ?>