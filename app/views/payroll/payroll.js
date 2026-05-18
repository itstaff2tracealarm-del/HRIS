
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
