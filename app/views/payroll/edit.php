<?php include __DIR__ . '/../../includes/header.php'; ?>
<?php include __DIR__ . '/../../includes/left.php'; ?>

<?php if ($_SESSION['role'] === 'admin'): ?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>

<style>
    body { background-color: #f8f9fa; font-family: sans-serif; }
    .content-area { padding: 20px; margin-left: 280px; }
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
    /* Nilagyan natin ng ".ps-table td" sa unahan para mas maging priority ang utos */
    .ps-table td.double-line { border-bottom: 3px double #000 !important; padding-bottom: 5px !important; }

    /* Siguraduhin din na ang input sa loob ay walang sariling border na nakaharang */
    .double-line .ps-input { border-bottom: none !important; font-weight: 800 !important; }
    .underlined-label { text-decoration: underline; font-weight: bold; text-transform: uppercase; }
    
    .ps-footer { background: #2c5499; color: white; padding: 10px; font-weight: bold; display: flex; justify-content: space-between; margin-top: auto; font-size: 14px; }
    .section-header { color: #2c5499; font-weight: 800; font-size: 1.2rem; border-bottom: 2px solid #2c5499; margin-bottom: 20px; padding-bottom: 5px; }

    /* Mag-highlight ang row kapag active ang input sa loob nito */
    .ps-table tr:focus-within { background-color: #f0f7ff !important; outline: 1px solid #2c5499; }
    /* Para magmukhang clickable ang mga inputs */
    .ps-input:hover {  background-color: #fffdec; border-bottom: 1px solid #2c5499 !important; }

    .bg-primary { background-color: #2c5499 !important; } /* Dark blue shade from image */
    .table-bordered td { vertical-align: middle; }
    #pv_earnings_table, #pv_deductions_table, #pv_personal_table {  background-color: #fff; }
    .border-end { border-right: 1px solid #dee2e6 !important; }
</style>

<div class="content-area">
  <div class="card-ui">

    <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded shadow-sm no-print">
      <nav class="breadcrumb-ui">
        <a href="dashboard.php">Dashboard</a> / 
        <a href="payroll.php?a=index">Payroll</a> / 
        <span style="color: #1c1e1b;">Edit Payroll</span>
      </nav>
      <a href="payroll.php?a=index" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
      </a>
    </div>
            
    <div class="section-header">EDIT PAYROLL</div>
      <!-- <form id="payrollForm" method="POST" action="payroll.php?a=store"> -->
        <form id="payrollForm" method="POST" action="payroll.php?a=update&id=<?= $payroll['id'] ?>">
        <div class="row g-3">
          <div class="col-md-4 d-flex">
            <table class="payroll-table">
              <tr>
                <td class="label-blue">Employee Name</td>
                <td class="value-cell">
                  <select id="empSearch" disabled>
                    <?php foreach ($employees as $emp): 
                      $mi = !empty($emp['middle_name']) ? substr($emp['middle_name'], 0, 1) . '.' : '';
                      $fullName = trim($emp['last_name'] . ', ' . $emp['first_name'] . ' ' . $mi);
                      $statusText = ($emp['status'] == 1) ? 'ACTIVE' : 'INACTIVE';
                      $selected = ($emp['id'] == $payroll['employee_id']) ? 'selected' : '';
                    ?>
                    <option value="<?= $emp['id'] ?>" 
                      <?= $selected ?>
                        data-code="<?= htmlspecialchars($emp['employee_code'] ?? '') ?>"
                        data-dept="<?= htmlspecialchars($emp['department'] ?? 'N/A') ?>"
                        data-desig="<?= htmlspecialchars($emp['designation'] ?? 'N/A') ?>"
                        data-status="<?= $statusText ?>"
                        data-hired="<?= htmlspecialchars($emp['date_of_joining'] ?? 'N/A') ?>"
                        data-salary="<?= htmlspecialchars($emp['salary'] ?? '0') ?>">
                      <?= htmlspecialchars($fullName) ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                  <input type="hidden" name="employee_id" value="<?= $payroll['employee_id'] ?>">
                </td>
              </tr>
      
              <tr><td class="label-blue">Employee ID</td><td class="value-cell"><input type="text" id="disp_id" readonly value="<?= $payroll['employee_code'] ?>"></td></tr>
                <tr><td class="label-blue">Basic Rate Per Day</td><td class="value-cell"><input type="number" name="basic_rate" id="rate" readonly value="<?= $payroll['basic_rate'] ?>"></td></tr>
                <tr><td class="label-blue">Worked Days</td><td class="value-cell"><input type="number" name="regular_days" id="days" value="<?= $payroll['regular_days'] ?>" oninput="compute()"></td></tr>
            </table>
          </div>

          <div class="col-md-4">
            <table class="payroll-table">
              <tr><td class="label-blue">Department</td><td class="value-cell"><input type="text" name="department" value="<?= $payroll['department'] ?>" readonly></td></tr>
              <tr><td class="label-blue">Designation</td><td class="value-cell"><input type="text" name="designation" value="<?= $payroll['designation'] ?>" readonly></td></tr>
              <tr><td class="label-blue">Emp. Status</td><td class="value-cell"><input type="text" name="status" value="<?= $payroll['status'] == 1 ? 'ACTIVE' : 'INACTIVE' ?>" readonly></td></tr>
              <tr><td class="label-blue">Date Hired</td><td class="value-cell"><input type="text" name="date_of_joining" value="<?= $payroll['date_of_joining'] ?>" readonly></td></tr>
            </table>
          </div>

          <div class="col-md-4">
            <table class="payroll-table">
              <tr><td class="label-blue">Period Starting</td><td class="value-cell"><input type="date" name="period_start" value="<?= $payroll['period_start'] ?>" readonly></td></tr>
              <tr><td class="label-blue">Period Ending</td><td class="value-cell"><input type="date" name="period_end" value="<?= $payroll['period_end'] ?>" readonly></td></tr>
              <tr><td class="label-blue">Payment Date</td><td class="value-cell"><input type="date" name="payment_date" value="<?= $payroll['payment_date'] ?>" readonly></td></tr>
              <tr><td class="label-blue">Payroll Frequency</td><td class="value-cell">
                <select disabled>
                  <option value="SEMI-MONTHLY" <?= ($payroll['frequency'] == 'SEMI-MONTHLY') ? 'selected' : '' ?>>SEMI-MONTHLY</option>
                  <option value="MONTHLY" <?= ($payroll['frequency'] == 'MONTHLY') ? 'selected' : '' ?>>MONTHLY</option>
                </select>
                <input type="hidden" name="frequency" value="<?= $payroll['frequency'] ?>">
              </td></tr>
            </table>
          </div>
        </div>

        <div class="row g-0 mt-1 d-flex">
          <div class="col-md-4 d-flex">
            <div class="payslip-col w-100">
              <div class="ps-header">EARNINGS / INCOME</div>
                <table class="ps-table">
                  <tr><td class="underlined-label">BASIC</td><td></td><td colspan="2"><input type="number" id="basic_disp" class="ps-input" readonly></td></tr>
                            
                  <tr><td class="fw-bold" style="padding-top:5px;">OT/HOLIDAY PAY:</td><td class="text-center fw-bold small">No. of Hr.</td><td></td></tr>
                  <tr><td class="bullet-item">Regular OT (25%)</td>
                    <td><input type="number" class="ps-input center-input" placeholder="-"></td>
                    <td><input type="number" class="ps-input earn-calc ot-input" name="reg_ot" value="<?= $payroll['reg_ot'] ?? '' ?>" oninput="compute()"></td>
                  </tr>
                  <tr><td class="sub-bullet">Reg. OT ND</td>
                      <td><input type="number" class="ps-input center-input" placeholder="-"></td>
                      <td><input type="number" class="ps-input earn-calc ot-input" name="reg_ot_nd" value="<?= $payroll['reg_ot_nd'] ?? '' ?>" oninput="compute()"></td>
                  </tr>
                  <tr><td class="bullet-item">SNWH/Sun/RD (130%)</td>
                    <td><input type="number" class="ps-input center-input" placeholder="-"></td>
                    <td><input type="number" class="ps-input earn-calc ot-input" name="sun_ot" value="<?= $payroll['sun_ot'] ?? '' ?>" oninput="compute()"></td>
                  </tr>
                  <tr><td class="sub-bullet">SH/RD/Sun ND:</td>
                    <td><input type="number" class="ps-input center-input" placeholder="-"></td>
                    <td><input type="number" class="ps-input earn-calc ot-input" name="sun_ot_nd" value="<?= $payroll['sun_ot_nd'] ?? '' ?>" oninput="compute()"></td>
                  </tr>
                  <tr><td class="bullet-item">RH (100%)</td>
                    <td><input type="number" class="ps-input center-input" placeholder="-"></td>
                    <td><input type="number" class="ps-input earn-calc ot-input" name="rh_pay" value="<?= $payroll['rh_pay'] ?? '' ?>" oninput="compute()"></td>
                  </tr>
                  <tr><td class="sub-bullet">RH-NDIFF:</td>
                    <td><input type="number" class="ps-input center-input" placeholder="-"></td>
                    <td><input type="number" class="ps-input earn-calc ot-input" name="rh_ndiff" value="<?= $payroll['rh_ndiff'] ?? '' ?>" oninput="compute()"></td>
                  </tr>
                  <tr><td class="bullet-item">CMS OT - 30% only</td>
                    <td><input type="number" class="ps-input center-input" placeholder="-"></td>
                    <td><input type="number" class="ps-input earn-calc ot-input" name="cms_ot" value="<?= $payroll['cms_ot'] ?? '' ?>" oninput="compute()"></td>
                  </tr>
                  <tr class="total-row"><td colspan="2" class="text-end fw-bold">TOTAL OT PAY:</td>
                    <td class="double-line"><input type="number" id="total_ot" class="ps-input" readonly value="0.00"></td>
                  </tr>

                  <tr><td class="underlined-label" colspan="3" style="padding-top:10px;">ALLOWANCES</td></tr>
                  <tr><td class="bullet-item">Daily (/13 Days)</td>
                    <td><input type="number" class="ps-input center-input" placeholder="Days"></td>
                    <td><input type="number" class="ps-input earn-calc" name="allowance_val" value="<?= $payroll['allowance_val'] ?? '' ?>" oninput="compute()"></td>
                  </tr>
                  <tr><td class="bullet-item">Manager/Head/Supervisor</td>
                    <td></td>
                    <td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td>
                  </tr>
                  <tr><td class="bullet-item">Special Task</td>
                    <td></td>
                    <td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td>
                  </tr>
                  <tr><td class="bullet-item">Special Allowance</td>
                    <td></td>
                    <td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td>
                  </tr>
                  <tr><td class="bullet-item">BCP (200/day)</td>
                    <td><input type="number" id="bcpDays" name="bcp_days" class="ps-input center-input" placeholder="Days" value="<?= $payroll['bcp_days'] ?? '' ?>"></td>
                    <td><input type="number" id="bcpResult" name="bcp_total" class="ps-input earn-calc" value="<?= $payroll['bcp_total'] ?? '' ?>" readonly></td>
                  </tr>
                  <tr><td class="bullet-item">CMS HO (150/NS)</td>
                    <td><input type="number" class="ps-input center-input" placeholder="NS"></td>
                    <td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td>
                  </tr>
                  <tr><td class="bullet-item">Others:</td>
                    <td><input type="number" class="ps-input center-input" placeholder="-"></td>
                    <td><input type="number" class="ps-input earn-calc" placeholder="-" oninput="compute()"></td>
                  </tr>
                  <tr><td class="underlined-label">OTHERS: 2025 LEAVECONVERSION</td> <td></td>
                    <td><input type="number" class="ps-input earn-calc" oninput="compute()"></td>
                  </tr>
                  <tr class="total-row"><td colspan="2" class="text-end fw-bold">TOTAL ALLOWANCE:</td>
                    <td class="double-line"><input type="number" id="total_allow" class="ps-input" readonly value="0.00"></td>
                  </tr>

                  <tr><td class="underlined-label" colspan="3" style="padding-top:10px;">INCENTIVES</td></tr>
                  <tr><td class="bullet-item">Attendance & Punctuality</td>
                  <td>
                    <select id="monthSelect" 
                            name="incentive_month" 
                            class="ps-input center-input" 
                            style="font-size: 10px; border:none; border-bottom: 1px solid #ccc;"
                            <?= !empty($payroll['incentive_month']) ? 'disabled' : '' ?>>
                        
                        <option value="" disabled <?= empty($payroll['incentive_month']) ? 'selected' : '' ?> hidden>Month</option>
                        
                        <option value="JAN" <?= ($payroll['incentive_month'] ?? '') == 'JAN' ? 'selected' : '' ?>>JANUARY</option>
                        <option value="FEB" <?= ($payroll['incentive_month'] ?? '') == 'FEB' ? 'selected' : '' ?>>FEBRUARY</option>
                        <option value="MAR" <?= ($payroll['incentive_month'] ?? '') == 'MAR' ? 'selected' : '' ?>>MARCH</option>
                        <option value="APR" <?= ($payroll['incentive_month'] ?? '') == 'APR' ? 'selected' : '' ?>>APRIL</option>
                        <option value="MAY" <?= ($payroll['incentive_month'] ?? '') == 'MAY' ? 'selected' : '' ?>>MAY</option>
                        <option value="JUN" <?= ($payroll['incentive_month'] ?? '') == 'JUN' ? 'selected' : '' ?>>JUNE</option>
                        <option value="JUL" <?= ($payroll['incentive_month'] ?? '') == 'JUL' ? 'selected' : '' ?>>JULY</option>
                        <option value="AUG" <?= ($payroll['incentive_month'] ?? '') == 'AUG' ? 'selected' : '' ?>>AUGUST</option>
                        <option value="SEP" <?= ($payroll['incentive_month'] ?? '') == 'SEP' ? 'selected' : '' ?>>SEPTEMBER</option>
                        <option value="OCT" <?= ($payroll['incentive_month'] ?? '') == 'OCT' ? 'selected' : '' ?>>OCTOBER</option>
                        <option value="NOV" <?= ($payroll['incentive_month'] ?? '') == 'NOV' ? 'selected' : '' ?>>NOVEMBER</option>
                        <option value="DEC" <?= ($payroll['incentive_month'] ?? '') == 'DEC' ? 'selected' : '' ?>>DECEMBER</option>
                    </select>
                    <td><input type="number" id="amountInput" name="attendance_incentive" class="ps-input earn-calc inc-calc" 
                           value="<?= number_format($payroll['attendance_incentive'] ?? 0, 2, '.', '') ?>" oninput="compute()"></td>
                  </td>
                </tr>
                <tr><td class="bullet-item">Others</td> <td></td>
                  <td><input type="number" class="ps-input earn-calc inc-calc" placeholder="-" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Others</td> <td></td>
                  <td><input type="number" class="ps-input earn-calc inc-calc" placeholder="-" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Others</td><td></td>
                  <td><input type="number" class="ps-input earn-calc inc-calc" placeholder="-" oninput="compute()"></td>
                </tr>
                <tr class="total-row"><td colspan="2" class="text-end fw-bold">TOTAL INCENTIVES:</td>
                  <td class="double-line"><input type="number" id="total_inc" class="ps-input" readonly value="0.00"></td>
                </tr>
                <td></td>
              </table>
              <div class="ps-footer"><span>GROSS SUB-TOTAL:</span><span id="gross_val">0.00</span></div>
            </div>
          </div>

          <div class="col-md-4 d-flex">
            <div class="payslip-col w-100" style="border-left:none; border-right:none;">
              <div class="ps-header">DEDUCTIONS</div>
              <table class="ps-table">
                <tr><td class="underlined-label">TIME & ATTENDANCE</td></tr>
                <tr><td class="bullet-item">Tardiness</td>
                  <td><input type="number" class="ps-input center-input" placeholder="Min"></td>
                  <td><input type="number" class="ps-input deduct-calc dtr-calc" name="tardiness" value="<?= $payroll['tardiness'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Undertime</td>
                  <td><input type="number" class="ps-input center-input" placeholder="Min"></td>
                  <td><input type="number" class="ps-input deduct-calc dtr-calc" name="undertime" value="<?= $payroll['undertime'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Adjustments</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" class="ps-input deduct-calc dtr-calc" name="attendance_adj" value="<?= $payroll['attendance_adj'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr class="total-row">
                  <td colspan="2" class="text-end fw-bold">DTR TOTAL DEDUCTION:</td>
                  <td class="double-line"><input type="number" id="total_dtr" class="ps-input" readonly value="0.00"></td>
                </tr>
                <td></td>
                <tr style="background:#2c5499; color:white;">
                  <td colspan="2" class="fw-bold" style="padding: 8px;">GROSS PAY:</td>
                  <td class="text-end fw-bold" id="gross_pay_val" style="padding: 5px 10px; font-size: 13px;">0.00</td>
                </tr>

                <tr><td class="underlined-label" colspan="3" style="padding-top:10px;">MANDATORY CONTRIBUTIONS</td></tr>                
                <tr><td class="bullet-item">SSS EE</td><td></td><td><input type="number" class="ps-input deduct-calc" name="sss_ee" value="<?= $payroll['sss_ee'] ?? '' ?>" oninput="compute()"></td></tr>
                <tr><td class="bullet-item">SSS WISP</td><td></td><td><input type="number" class="ps-input deduct-calc" name="sss_wisp" value="<?= $payroll['sss_wisp'] ?? '' ?>" oninput="compute()"></td></tr>
                <tr><td class="bullet-item">PHILHEALTH EE</td><td></td><td><input type="number" class="ps-input deduct-calc" name="philhealth" value="<?= $payroll['philhealth'] ?? '' ?>" oninput="compute()"></td></tr>
                <tr><td class="bullet-item">PAG-IBIG EE</td><td></td><td><input type="number" class="ps-input deduct-calc" name="pagibig" value="<?= $payroll['pagibig'] ?? '' ?>" oninput="compute()"></td></tr>

                <tr><td class="underlined-label" colspan="3" style="padding-top:10px;">LOANS</td></tr>
                <tr><td class="fw-bold">• SSS</td><td></td></tr>
                <tr><td class="sub-bullet">Salary Loan</td><td></td><td><input type="number" class="ps-input deduct-calc" name="sss_loan" value="<?= $payroll['sss_loan'] ?? '' ?>" oninput="compute()"></td></tr>
                <tr><td class="sub-bullet">Calamity Loan</td><td></td><td><input type="number" class="ps-input deduct-calc" name="sss_calamity" value="<?= $payroll['sss_calamity'] ?? '' ?>" oninput="compute()"></td></tr>
                            
                <tr><td class="fw-bold">• PAG-IBIG</td><td></td></tr>
                <tr><td class="sub-bullet">Multi-Purpose</td><td></td><td><input type="number" class="ps-input deduct-calc" name="pagibig_loan" value="<?= $payroll['pagibig_loan'] ?? '' ?>" oninput="compute()"></td></tr>
                <tr><td class="sub-bullet">Calamity Loan</td><td></td><td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td></tr>
                
                <tr class="total-row"><td class="fw-bold" style="padding-top:10px;">WITHHOLDING TAX</td><td></td><td><input type="number" class="ps-input deduct-calc" name="w_tax" value="<?= $payroll['w_tax'] ?? '' ?>" oninput="compute()"></td></tr>
                <tr><td class="fw-bold">HMO: Aug 2025-July 2026</td><td></td><td><input type="number" class="ps-input deduct-calc" name="hmo" value="<?= $payroll['hmo'] ?? '' ?>" oninput="compute()"></td></tr>
                <tr><td class="fw-bold">OTHERS:</td><td></td>
                  <td><input type="number" class="ps-input deduct-calc" placeholder="-" oninput="compute()"></td>
                </tr>
                
                <tr class="total-row"><td colspan="2" class="text-end fw-bold">TOTAL DEDUCTION:</td>
                  <td class="double-line"><input type="number" id="total_deduct_display" class="ps-input" readonly value="0.00"></td>
                </tr>
              </table>
              <div class="ps-footer" style="background:#2c5499">
                <span>NET SUB-TOTAL:</span>
                <span id="net_sub_val">0.00</span>
              </div>
            </div>
          </div>

          <div class="col-md-4 d-flex">
            <div class="payslip-col w-100">
              <div class="ps-header">PERSONAL DEDUCTIONS</div>
              <table class="ps-table">
                <tr><td class="bullet-item">EF</td><td></td>
                  <td><input type="number" name="ef_deduct" class="ps-input pers-calc" value="<?= $payroll['ef_deduct'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr>
                <td class="bullet-item">Paluwagan - A</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="paluwagan_a" class="ps-input pers-calc" value="<?= $payroll['paluwagan_a'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Paluwagan - B</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="paluwagan_b" class="ps-input pers-calc" value="<?= $payroll['paluwagan_b'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Paluwagan - C</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="paluwagan_c" class="ps-input pers-calc" value="<?= $payroll['paluwagan_c'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Paluwagan - D</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="paluwagan_d" class="ps-input pers-calc" value="<?= $payroll['paluwagan_d'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Paluwagan - E</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="paluwagan_e" class="ps-input pers-calc" value="<?= $payroll['paluwagan_e'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Paluwagan - F (JAN-JUN)</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="paluwagan_f" class="ps-input pers-calc" value="<?= $payroll['paluwagan_f'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Paluwagan - G (JAN-JUN)</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="paluwagan_g" class="ps-input pers-calc" value="<?= $payroll['paluwagan_g'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">GADGETS</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="gadgets_1" class="ps-input pers-calc" value="<?= $payroll['gadgets_1'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">GADGETS</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="gadgets_2" class="ps-input pers-calc" value="<?= $payroll['gadgets_2'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">APPLIANCE</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="appliance_1" class="ps-input pers-calc" value="<?= $payroll['appliance_1'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">APPLIANCE</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="appliance_2" class="ps-input pers-calc" value="<?= $payroll['appliance_2'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">COOP</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="coop_1" class="ps-input pers-calc" value="<?= $payroll['coop_1'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">COOP</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="coop_2" class="ps-input pers-calc" value="<?= $payroll['coop_2'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">COOP</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="coop_1" class="ps-input pers-calc" value="<?= $payroll['coop_1'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">COOP</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="coop_2" class="ps-input pers-calc" value="<?= $payroll['coop_2'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Donation</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="donation" class="ps-input pers-calc" value="<?= $payroll['donation'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Contribution</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="contribution" class="ps-input pers-calc" value="<?= $payroll['contribution'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Others</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="others_1" class="ps-input pers-calc" value="<?= $payroll['others_1'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Others</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="others_2" class="ps-input pers-calc" value="<?= $payroll['others_2'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Others</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="others_1" class="ps-input pers-calc" value="<?= $payroll['others_1'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Others</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="others_2" class="ps-input pers-calc" value="<?= $payroll['others_2'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Others</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="others_1" class="ps-input pers-calc" value="<?= $payroll['others_1'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Others</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="others_2" class="ps-input pers-calc" value="<?= $payroll['others_2'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr><td class="bullet-item">Others</td>
                  <td><input type="number" class="ps-input center-input"></td>
                  <td><input type="number" name="others_2" class="ps-input pers-calc" value="<?= $payroll['others_2'] ?? '' ?>" oninput="compute()"></td>
                </tr>
                <tr class="total-row">
                  <td colspan="2" class="text-end fw-bold">TOTAL PERSONAL DEDUCTION:</td>
                  <td class="double-line"><input type="number" id="total_pers" class="ps-input" readonly value="0.00"></td>
                </tr>
              </table>
              <div class="ps-footer" style="background:#2c5499">
                <span>FINAL NET PAY:</span>
                <span id="final_val">₱ 0.00</span>
              </div>
            </div>
          </div>
        </div>

            <div class="mt-4 d-flex justify-content-end no-print">
            <button type="button" id="btnExport" class="btn btn-outline-success ms-2">
                <i class="bi bi-file-earmark-excel"></i> Excel Export
            </button>
            <a href="payroll.php" class="btn btn-outline-secondary ms-2">Cancel</a>   
            <button type="submit" class="btn btn-primary ms-2">Update Payroll</button>
        </div>

        <input type="hidden" name="allowances" id="hidden_allowances" value="<?= $payroll['allowances'] ?>">
        <input type="hidden" name="deductions" id="hidden_deductions" value="<?= $payroll['deductions'] ?>">
        <input type="hidden" name="net_salary" id="hidden_net_salary" value="<?= $payroll['net_salary'] ?>">
        <input type="hidden" name="id" value="<?= $payroll['id'] ?>">

      </form> </div> </div>

<script>
$(document).ready(function() {
    $('#empSearch').select2({ width: '100%' });

    // Mahalaga ito: I-trigger ang computation sa start
    compute(); 

    // Bantayan ang lahat ng input para sa live changes
    $(document).on('input', '.earn-calc, .deduct-calc, .dtr-calc, .pers-calc, #days, #rate', function() {
        compute();
    });

    // 2. AUTO-FILL ON LOAD
    const selectedOption = $('#empSearch').find(':selected');
    if (selectedOption.val() !== "") {
        $('#disp_id').val(selectedOption.data('code'));
        $('#department').val(selectedOption.data('dept'));
        $('#designation').val(selectedOption.data('desig'));
        $('#status').val(selectedOption.data('status'));
        $('#date_of_joining').val(selectedOption.data('hired'));
        // Ang #rate ay galing sa database record ($payroll['basic_rate'])
    }
    // 3. Initial Calculation
    compute(); 
});

function compute() {
    // 1. Basic Pay Calculation
    const r = parseFloat($('#rate').val()) || 0;
    const d = parseFloat($('#days').val()) || 0;
    const basic = r * d;
    $('#basic_disp').val(basic.toFixed(2));

    // 2. OT Pay Calculation (Bukod na total)
    let otTotal = 0;
    $('.ot-input').each(function() { otTotal += parseFloat($(this).val()) || 0; });
    $('#total_ot').val(otTotal.toFixed(2));

    // 3. Incentives Calculation (Bukod na total)
    let incTotal = 0;
    $('.inc-calc').each(function() { incTotal += parseFloat($(this).val()) || 0; });
    $('#total_inc').val(incTotal.toFixed(2));

    // 4. Allowance/Other Earnings Calculation
    // Kinukuha lahat ng earn-calc PERO hindi kasama ang OT at Incentives para hindi double count
    let allowTotal = 0;
    $('.earn-calc').not('.ot-input').not('.inc-calc').each(function() {
        allowTotal += parseFloat($(this).val()) || 0;
    });
    $('#total_allow').val(allowTotal.toFixed(2));

    // 5. Gross Sub-Total (Lahat ng Kita bago ang DTR Deductions)
    const grossSub = basic + otTotal + allowTotal + incTotal;
    $('#gross_val').text('₱ ' + grossSub.toLocaleString(undefined, {minimumFractionDigits: 2}));

    // 6. DTR Deductions (Late, Undertime, Absent)
    let dtrDed = 0;
    $('.dtr-calc').each(function() { dtrDed += parseFloat($(this).val()) || 0; });
    $('#total_dtr').val(dtrDed.toFixed(2));

    // 7. Gross Pay (Gross Sub - DTR)
    const grossPay = grossSub - dtrDed;
    $('#gross_pay_val').text('₱ ' + grossPay.toLocaleString(undefined, {minimumFractionDigits: 2}));

    // 8. Mandatory & Loans Deductions (SSS, Philhealth, etc.)
    let mandatoryLoansTotal = 0;
    $('.deduct-calc').not('.dtr-calc').each(function() {
        mandatoryLoansTotal += parseFloat($(this).val()) || 0;
    });

    // Siguraduhin na ang ID dito ay tugma sa HTML (total_deduct_display)
    if ($('#total_deduct_display').length) {
        if ($('#total_deduct_display').is('input')) {
            $('#total_deduct_display').val(mandatoryLoansTotal.toFixed(2));
        } else {
            $('#total_deduct_display').text(mandatoryLoansTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));
        }
    }
    // 9. Net Sub-Total (Gross Pay - Mandatory Deductions)
    const netSubTotal = grossPay - mandatoryLoansTotal;
    $('#net_sub_val').text('₱ ' + netSubTotal.toLocaleString(undefined, {minimumFractionDigits: 2}));

    // 10. Personal Deductions (Paluwagan, etc.)
    let persTotal = 0;
    $('.pers-calc').each(function() { persTotal += parseFloat($(this).val()) || 0; });
    $('#total_pers').val(persTotal.toFixed(2));

    // 11. Final Net Pay Calculation
    const finalNet = netSubTotal - persTotal;
    $('#final_val').text('₱ ' + finalNet.toLocaleString(undefined, {minimumFractionDigits: 2}));

    // --- PARA SA DATABASE UPDATE (Hidden Fields) ---
    const grandTotalDeductions = dtrDed + mandatoryLoansTotal + persTotal;
    const totalAllowances = otTotal + allowTotal + incTotal;

    $('#hidden_allowances').val(totalAllowances.toFixed(2));
    $('#hidden_deductions').val(grandTotalDeductions.toFixed(2));
    $('#hidden_net_salary').val(finalNet.toFixed(2));
}

//Attendance Logic (150)
$('#monthSelect').on('change', function() {
    $('#amountInput').val("150.00"); // Auto-fill 150 pag pumili ng buwan
    compute(); // Re-calculate agad ang gross at net
});
// BCP Logic (200 * days)
$('#bcpDays').on('input', function() {
    let d = parseFloat($(this).val()) || 0;
    if (d > 31) {
        alert("Maximum of 31 days only.");
        d = 31;
        $(this).val(31);
    }
    $('#bcpResult').val((d * 200).toFixed(2));
    compute(); 
});
</script>

<?php endif; ?>
<?php include __DIR__ . '/../../includes/footer.php'; ?>