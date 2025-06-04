<?php
// Assuming $global_config, $basic, $invoice, $this->fees_model, $this->application_model, $this->db, $this->app_lib, etc. are available
// and populated from your CodeIgniter framework.

$currency_symbol = isset($global_config['currency_symbol']) ? htmlspecialchars($global_config['currency_symbol']) : '$';
$extINTL = extension_loaded('intl');
if ($extINTL == true) {
    $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
}

// --- Pre-calculate total discount and fine for the Invoice Summary table ---
$_overall_invoice_summary_discount = 0;
$_overall_invoice_summary_fine = 0;
if (isset($basic['id'])) {
    $allocations_for_check = $this->fees_model->getInvoiceDetails($basic['id']);
    if (!empty($allocations_for_check)) {
        foreach ($allocations_for_check as $row_check) {
            $deposit_check = $this->fees_model->getStudentFeeDeposit($row_check['allocation_id'], $row_check['fee_type_id']);
            $_overall_invoice_summary_discount += isset($deposit_check['total_discount']) ? (float)$deposit_check['total_discount'] : 0;
            $_overall_invoice_summary_fine += isset($deposit_check['total_fine']) ? (float)$deposit_check['total_fine'] : 0;
        }
    }
}

// --- Pre-calculate total discount and fine for the Payment History table ---
$_overall_payment_history_discount = 0;
$_overall_payment_history_fine = 0;
if (isset($basic['id']) && $invoice['status'] != 'unpaid') {
    $allocations_for_history_check = $this->db->where(array('student_id' => $basic['id'], 'session_id' => get_session_id()))->get('fee_allocation')->result_array();
    if(!empty($allocations_for_history_check)) {
        foreach ($allocations_for_history_check as $allRow_check) {
            $historys_for_check = $this->fees_model->getPaymentHistory($allRow_check['id'], $allRow_check['group_id']);
            if(!empty($historys_for_check)){
                foreach ($historys_for_check as $row_hist_check) {
                    $_overall_payment_history_discount += isset($row_hist_check['discount']) ? (float)$row_hist_check['discount'] : 0;
                    $_overall_payment_history_fine += isset($row_hist_check['fine']) ? (float)$row_hist_check['fine'] : 0;
                }
            }
        }
    }
}

?>
<style>
    /* General Invoice Styling */
    #invoice_print .invoice,
    #payment_print .invoice.payment {
        font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; /* Modern font stack for screen */
        color: #333; 
    }

    /* Invoice Header Styling */
    #invoice_print .invoice header.clearfix,
    #payment_print .invoice.payment header.clearfix {
        border: 1px solid #dee2e6; 
        padding: 20px;
        margin-bottom: 30px; 
        background-color: #f8f9fa; 
        border-radius: 0.25rem; 
    }

    /* Header Row Flex Alignment */
    #invoice_print .invoice header .row,
    #payment_print .invoice.payment header .row {
        display: flex;
        align-items: center; 
        justify-content: space-between; 
    }

    #invoice_print .invoice header .col-xs-4,
    #payment_print .invoice.payment header .col-xs-4 {
        padding-left: 10px; 
        padding-right: 10px; 
    }

    /* Logo column */
    #invoice_print .invoice header .col-xs-4:first-child,
    #payment_print .invoice.payment header .col-xs-4:first-child {
        flex: 0 0 auto; 
        max-width: 25%; 
        display: flex;
        align-items: center;
        justify-content: flex-start;
    }
    #invoice_print .invoice header .ib,
    #payment_print .invoice.payment header .ib {
        display: inline-block; 
    }

    #invoice_print .invoice header .ib img,
    #payment_print .invoice.payment header .ib img {
        max-height: 60px; 
        width: auto;
        display: block; 
    }
    
    /* School Details Section in Header */
    #invoice_print .invoice header .school-details-column,
    #payment_print .invoice.payment header .school-details-column {
        flex: 1 1 auto; 
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 0 10px; 
        overflow: hidden; 
    }

    #invoice_print .invoice header .school-details-column h3,
    #payment_print .invoice.payment header .school-details-column h3 {
        color: #007bff;
        font-weight: 600;
        font-size: 1.6em; 
        margin-top: 0;
        margin-bottom: 8px;
        border-bottom: 2px solid #007bff;
        padding-bottom: 6px;
        display: inline-block; 
        word-break: break-word; 
        hyphens: auto; 
        overflow-wrap: break-word; 
    }

    #invoice_print .invoice header .school-details-column p,
    #payment_print .invoice.payment header .school-details-column p {
        margin-bottom: 5px;
        line-height: 1.5;
        font-size: 0.95em; 
        color: #555; 
    }

    /* Invoice Info Section in Header (Invoice No, Date, Status) */
    #invoice_print .invoice header .col-xs-4.text-right,
    #payment_print .invoice.payment header .col-xs-4.text-right {
        flex: 0 0 auto; 
        max-width: 25%; 
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        justify-content: center;
        text-align: right;
        padding-left: 10px; 
    }
     #invoice_print .invoice header .col-xs-4.text-right h4,
     #payment_print .invoice.payment header .col-xs-4.text-right h4 {
         margin-top:0;
         margin-bottom: 8px; 
         font-size: 1.1em;
         color: #333;
     }
     #invoice_print .invoice header .col-xs-4.text-right p,
     #payment_print .invoice.payment header .col-xs-4.text-right p {
         margin-bottom: 5px; 
         font-size: 0.95em;
         color: #444;
     }

    /* Bill Info Section */
    .bill-info {
        margin-top: 30px; 
        margin-bottom: 30px; 
    }
    .bill-info .bill-data p.h5 {
        font-size: 1.1em;
        font-weight: 600; 
        color: #007bff; 
        margin-bottom: 10px;
    }
    .bill-info address {
        font-size: 0.95em;
        line-height: 1.6;
        color: #555;
    }


    /* Table Styling */
    .table-responsive > .table.invoice-items,
    .table-responsive > .table#paymentHistory {
        border: 1px solid #dee2e6; 
        width: 100%;
        margin-top: 20px;
        margin-bottom: 1.5rem; 
        color: #333;
        border-collapse: collapse;
        font-size: 0.9em; 
    }

    .table-responsive > .table.invoice-items th,
    .table-responsive > .table.invoice-items td,
    .table-responsive > .table#paymentHistory th,
    .table-responsive > .table#paymentHistory td {
        border: 1px solid #e0e0e0; 
        padding: 0.75rem 0.85rem; 
        vertical-align: middle;
        line-height: 1.5;
    }

    .table-responsive > .table.invoice-items thead th,
    .table-responsive > .table#paymentHistory thead th {
        vertical-align: middle;
        border-bottom: 2px solid #007bff; 
        background-color: #f1f3f5; 
        font-weight: 600; 
        color: #212529; 
        font-size: 0.95em;
        text-transform: uppercase; 
        letter-spacing: 0.5px;
    }

    /* Style for group rows in Invoice Summary */
    .table.invoice-items td.group {
        background-color: #e9ecef; 
        font-weight: 600; 
        color: #333;
        border-top: 1px solid #dee2e6;
        border-bottom: 1px solid #dee2e6;
        padding: 0.85rem;
    }
    .table.invoice-items td.group img.group {
        margin-left: 10px;
        height: 14px;
        vertical-align: middle;
    }
    
    /* Status Labels Styling */
    .label.label-danger-custom,
    .label.label-info-custom,
    .label.label-success-custom {
        padding: .4em .7em .4em; 
        font-size: 80%; 
        font-weight: 600; 
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .30em; 
    }
    .label-danger-custom { background-color: #dc3545; } 
    .label-info-custom { background-color: #17a2b8; } 
    .label-success-custom { background-color: #28a745; } 


    /* Print Specific Styles */
    @media print {
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-size: 10pt !important; /* Slightly increased base font size for print */
            font-family: 'Times New Roman', Times, serif !important; /* Changed to serif font */
            color: #000 !important;
        }
        .hidden-print {
            display: none !important;
        }
        .visible-print-block {
            display: block !important;
        }
        .panel, .tabs-custom {
            border: none !important;
            box-shadow: none !important;
        }
        #invoice_print .invoice header.clearfix,
        #payment_print .invoice.payment header.clearfix {
            border: 1px solid #000 !important; /* Darker border for print header */
            background-color: #fff !important; /* White background for print */
            padding: 15px !important;
            border-radius: 0 !important; 
            position: relative !important; 
            padding-bottom: 40px !important; 
        }
        
        #invoice_print .invoice header .row > .col-xs-4:first-child,
        #payment_print .invoice.payment header .row > .col-xs-4:first-child {
            display: none !important; /* Hide screen logo container */
        }
        
        /* Watermark Logo */
        #invoice_print .invoice header .ib, /* This targets the div containing the img */
        #payment_print .invoice.payment header .ib {
            position: absolute !important;
            top: 50% !important; 
            left: 50% !important;
            transform: translate(-50%, -50%) !important;
            z-index: 0 !important; 
            opacity: 0.07 !important; /* Made slightly fainter */
        }
        #invoice_print .invoice header .ib img,
        #payment_print .invoice.payment header .ib img {
            max-height: 70px !important; 
            width: auto !important;
            object-fit: contain;
        }

        /* School Details Column */
        #invoice_print .invoice header .school-details-column,
        #payment_print .invoice.payment header .school-details-column {
            flex: 1 1 100% !important; 
            width: 100% !important;
            text-align: center !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 0 5px !important;
            position: relative !important; 
            z-index: 1 !important;
            min-height: 70px; 
        }

        #invoice_print .invoice header .school-details-column h3,
        #payment_print .invoice.payment header .school-details-column h3 {
            color: #000 !important; 
            font-size: 14pt !important; /* Increased for prominence */
            font-weight: bold !important; 
            word-break: break-word !important;
            overflow-wrap: break-word !important; 
            hyphens: auto !important;
            line-height: 1.3 !important; 
            margin-top: 0 !important;
            margin-bottom: 6px !important; 
            display: inline-block !important; /* Changed to inline-block */
            text-align: center !important; 
            border-bottom: 1px solid #000 !important; 
            padding-bottom: 4px !important; 
            max-width: 95%; /* Allow it to be wide but with slight padding from edges */
        }
         #invoice_print .invoice header .school-details-column p,
        #payment_print .invoice.payment header .school-details-column p {
            color: #000 !important;
            font-size: 9pt !important; 
            line-height: 1.3 !important;
            margin-bottom: 3px !important;
            position: relative !important; 
            z-index: 1 !important;
         }
        
        /* Invoice Info Column */
        #invoice_print .invoice header .col-xs-4.text-right,
        #payment_print .invoice.payment header .col-xs-4.text-right {
            position: absolute !important;
            bottom: 10px !important;
            right: 15px !important;
            width: auto !important; 
            max-width: none !important; 
            padding: 0 !important;
            z-index: 1 !important; 
            flex: none !important; 
        }
         #invoice_print .invoice header .col-xs-4.text-right h4,
         #payment_print .invoice.payment header .col-xs-4.text-right h4 {
             font-size: 10pt !important;
             font-weight: bold;
             color: #000 !important;
             margin-bottom: 4px !important;
         }
         #invoice_print .invoice header .col-xs-4.text-right p,
         #payment_print .invoice.payment header .col-xs-4.text-right p {
             font-size: 9pt !important;
             color: #000 !important;
             margin-bottom: 2px !important;
         }

        .bill-info {
            margin-top: 20px !important;
            margin-bottom: 20px !important;
        }
        .bill-info address {
            font-size: 10pt !important;
            line-height: 1.4 !important;
            color: #000 !important;
        }
         .bill-info .bill-data p.h5 {
            font-size: 11pt !important;
            font-weight: bold !important;
            color: #000 !important;
        }


        .table-responsive {
            overflow-x: visible !important; 
        }
        .table-responsive > .table.invoice-items,
        .table-responsive > .table#paymentHistory {
            border: 1px solid #000 !important; 
            font-size: 9pt !important; 
            margin-top: 15px !important;
            margin-bottom: 15px !important;
        }
        .table-responsive > .table.invoice-items th,
        .table-responsive > .table.invoice-items td,
        .table-responsive > .table#paymentHistory th,
        .table-responsive > .table#paymentHistory td {
            border: 1px solid #333 !important; 
            padding: 5px 7px !important; 
            color: #000 !important;
            vertical-align: middle !important;
        }
        .table-responsive > .table.invoice-items thead th,
        .table-responsive > .table#paymentHistory thead th {
            background-color: #e0e0e0 !important; 
            border-bottom: 2px solid #000 !important; 
            color: #000 !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
        }
        .table.invoice-items td.group {
            background-color: #eaeaea !important; 
            color: #000 !important;
            font-weight: bold !important;
        }

        .label.label-danger-custom,
        .label.label-info-custom,
        .label.label-success-custom {
            border: 1px solid #000;
            color: #fff !important; 
            background-clip: padding-box; /* Helps with rendering background colors in print */
        }
        .label-danger-custom { background-color: #dc3545 !important; }
        .label-info-custom { background-color: #17a2b8 !important; }
        .label-success-custom { background-color: #28a745 !important; }
        
        .invoice-summary.visible-print-block ul.amounts li {
            font-size: 10pt !important;
            margin-bottom: 4px !important;
            color: #000 !important;
        }
        .invoice-summary.visible-print-block ul.amounts li strong {
            font-weight: bold !important;
        }

        .tabs-custom .nav-tabs, .panel > .tabs-custom > .nav-tabs,
        .text-right.mr-lg.hidden-print,
        button, .btn, 
        .mfp-hide,
        .panel-footer, 
        .invoice-summary.text-right.mt-lg.hidden-print, 
        .checkbox-replace, 
        .hidden-print 
         {
            display: none !important;
        }
        #invoice_print, #payment_print {
            display: block !important;
        }
        .invoice {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background-color: #fff !important; /* Ensure invoice background is white for print */
        }
        #invDetailsPrint, #invPaymentHistory {
            display: block !important;
        }
    }
</style>
<section class="panel">
    <div class="tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active">
                <a href="#invoice" data-toggle="tab"><i class="far fa-credit-card"></i> <?=translate('invoice')?></a>
            </li>
<?php if ($invoice['status'] != 'unpaid'): ?>
            <li>
                <a href="#history" data-toggle="tab"><i class="fas fa-dollar-sign"></i> <?=translate('payment_history')?></a>
            </li>
<?php endif; ?>
<?php if (get_permission('collect_fees', 'is_add') && $invoice['status'] != 'total'): ?>
            <li>
                <a href="#collect_fees" data-toggle="tab"><i class="fas fa-hand-holding-usd"></i> <?=translate('collect_fees')?></a>
            </li>
<?php endif; ?>
<?php if (get_permission('collect_fees', 'is_add') && $invoice['status'] != 'total'): ?>
            <li>
                <a href="#fully_paid" data-toggle="tab"><i class="far fa-credit-card"></i> <?=translate('fully_paid') ?></a>
            </li>
<?php endif; ?>
        </ul>
        <div class="tab-content">
            <div id="invoice" class="tab-pane <?=empty($this->session->flashdata('pay_tab')) ? 'active' : ''; ?>">
                <div id="invoice_print">
                    <div class="invoice">
                        <header class="clearfix">
                            <div class="row">
                                <div class="col-xs-3"> <div class="ib">
                                        <img src="<?=$this->application_model->getBranchImage($basic['branch_id'], 'printing-logo')?>" alt="School Logo" onerror="this.onerror=null;this.src='https://placehold.co/150x70/cccccc/000000?text=Logo';" />
                                    </div>
                                </div>
                            <div class="col-xs-6 school-details-column" style="font-size: 17px; line-height: 1; text-align: center; word-wrap: break-word; max-width: 100%; font-weight: 700; color: #FF0000;">
                                    <?php echo htmlspecialchars($basic['school_name']); ?>
                                    <p><?php echo htmlspecialchars($basic['school_address']); ?></p>
                                    <p><?php echo htmlspecialchars($basic['school_mobileno']); ?></p>
                                    <p><?php echo htmlspecialchars($basic['school_email']); ?></p>
                                </div>
                                <div class="col-xs-3 text-right">
                                    <h4 class="mt-none mb-none text-dark">Invoice No #<?=htmlspecialchars($invoice['invoice_no'])?></h4>
                                    <p class="mb-none">
                                        <span class="text-dark"><?=translate('date')?> : </span>
                                        <span class="value"><?=_d(date('Y-m-d'))?></span>
                                    </p>
                                    <p class="mb-none">
                                        <span class="text-dark"><?=translate('status')?> : </span><?php
                                            $status_text = ''; 
                                            $labelmode = '';
                                            if($invoice['status'] == 'unpaid') {
                                                $status_text = translate('unpaid');
                                                $labelmode = 'label-danger-custom';
                                            } elseif($invoice['status'] == 'partly') {
                                                $status_text = translate('partly_paid');
                                                $labelmode = 'label-info-custom';
                                            } elseif($invoice['status'] == 'total') {
                                                $status_text = translate('total_paid');
                                                $labelmode = 'label-success-custom';
                                            }
                                            echo "<span class='value label " . htmlspecialchars($labelmode) . " '>" . htmlspecialchars($status_text) . "</span>";
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </header>
                        <div class="bill-info">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="bill-data">
                                        <p class="h5 mb-xs text-dark text-weight-semibold">Invoice To :</p>
                                        <address style="font-style: normal;">
                                            <?php 
                                            echo htmlspecialchars($basic['first_name'] . ' ' . $basic['last_name']) . '<br>';
                                            echo (empty($basic['student_address']) ? "" : nl2br(htmlspecialchars($basic['student_address'])) . '<br>');
                                            echo translate('class') . ' : ' . htmlspecialchars($basic['class_name']) . '<br>';
                                            echo translate('email') . ' : ' . htmlspecialchars($basic['student_email']); 
                                            ?>
                                        </address>
                                    </div>
                                </div>
                                <div class="col-xs-4 text-right"> </div> </div>
                        </div>
                    <?php if (get_permission('collect_fees', 'is_add')) { ?>
                        <button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="collectFees" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <i class="fas fa-coins fa-fw"></i> <?=translate('selected_fees_collect') ?>
                        </button>
                    <?php } ?>
                        <div class="table-responsive br-none">
                            <table class="table invoice-items table-hover mb-none" id="invoiceSummary">
                                <thead>
                                    <tr class="text-dark">
                                        <th class="text-weight-semibold hidden-print">
                                            <div class="checkbox-replace" >
                                                <label class="i-checks" data-toggle="tooltip" data-original-title="Print Show / Hidden">
                                                    <input type="checkbox" class="fee-selectAll" checked><i></i>
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-weight-semibold hidden-print">#</th>
                                        <th class="text-weight-semibold"><?=translate("fees_type")?></th>
                                        <th class="text-weight-semibold"><?=translate("due_date")?></th>
                                        <th class="text-weight-semibold"><?=translate("status")?></th>
                                        <th class="text-weight-semibold"><?=translate("amount")?></th>
                                        <?php if ($_overall_invoice_summary_discount >= 1): ?>
                                        <th class="text-weight-semibold"><?=translate("discount")?></th>
                                        <?php endif; ?>
                                        <?php if ($_overall_invoice_summary_fine >= 1): ?>
                                        <th class="text-weight-semibold"><?=translate("fine")?></th>
                                        <?php endif; ?>
                                        <th class="text-weight-semibold"><?=translate("paid")?></th>
                                        <th class="text-center text-weight-semibold"><?=translate("balance")?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $group = array();
                                        $count = 1;
                                        $total_fine = 0;
                                        $fully_total_fine = 0; 
                                        $total_discount = 0; 
                                        $total_paid = 0;     
                                        $total_balance = 0;  
                                        $total_amount = 0;   
                                        $typeData = array('' => translate('select'));
                                        
                                        if (!empty($allocations_for_check)) {
                                            foreach ($allocations_for_check as $row) { 
                                                $deposit = $this->fees_model->getStudentFeeDeposit($row['allocation_id'], $row['fee_type_id']);
                                                $item_base_amount = isset($row['amount']) ? (float)$row['amount'] : 0;
                                                $type_discount = isset($deposit['total_discount']) ? (float)$deposit['total_discount'] : 0;
                                                $type_fine = isset($deposit['total_fine']) ? (float)$deposit['total_fine'] : 0;
                                                $type_amount = isset($deposit['total_amount']) ? (float)$deposit['total_amount'] : 0; 
                                                
                                                $balance = $item_base_amount - ($type_amount + $type_discount);

                                                $total_discount += $type_discount;
                                                $total_fine += $type_fine; 
                                                $total_paid += $type_amount;
                                                $total_balance += $balance;
                                                $total_amount += $item_base_amount;

                                                if (abs($balance) > 0.001) { 
                                                    $typeData[$row['allocation_id'] . "|" . $row['fee_type_id']] = $row['name'];
                                                    $fine_calc = $this->fees_model->feeFineCalculation($row['allocation_id'], $row['fee_type_id']);
                                                    $b_fine_info = $this->fees_model->getBalance($row['allocation_id'], $row['fee_type_id']); 
                                                    $fine_for_fully_paid = abs($fine_calc - (isset($b_fine_info['fine']) ? (float)$b_fine_info['fine'] : 0));
                                                    $fully_total_fine += $fine_for_fully_paid;
                                                }
                                        ?>
                                    <?php if(!in_array($row['group_id'], $group)) { 
                                        $group[] = $row['group_id'];
                                        $group_header_colspan = 6 + ($_overall_invoice_summary_discount >= 1 ? 1 : 0) + ($_overall_invoice_summary_fine >= 1 ? 1 : 0);
                                        ?>
                                    <tr>
                                        <td class="group hidden-print" colspan="2">&nbsp;</td>
                                        <td class="group" colspan="<?=$group_header_colspan?>"><strong><?php echo htmlspecialchars(get_type_name_by_id('fee_groups', $row['group_id'])); ?></strong><img class="group" src="<?php echo base_url('assets/images/arrow.png'); ?>" alt="arrow"></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class="hidden-print checked-area">
                                            <div class="checkbox-replace">
                                                <label class="i-checks"><input type="checkbox" name="cb_invoice" value="<?php echo htmlspecialchars($row['amount']); ?>" data-allocation-id="<?php echo htmlspecialchars($row['allocation_id']); ?>" data-fee-type-id="<?php echo htmlspecialchars($row['fee_type_id']); ?>" checked><i></i></label>
                                            </div>
                                        </td>
                                        <td class="hidden-print"><?php echo $count++;?></td>
                                        <td class="text-dark"><?=htmlspecialchars($row['name'])?></td>
                                        <td><?=_d($row['due_date'])?></td>
                                        <td><?php 
                                            $item_status_text = '';
                                            $item_labelmode = '';
                                            $effective_due = $item_base_amount - $type_discount;

                                            if($type_amount <= 0 && $effective_due > 0.001) { 
                                                $item_status_text = translate('unpaid');
                                                $item_labelmode = 'label-danger-custom';
                                            } elseif($type_amount >= $effective_due && $effective_due > 0.001) {
                                                $item_status_text = translate('total_paid');
                                                $item_labelmode = 'label-success-custom';
                                            } elseif ($type_amount > 0 && $type_amount < $effective_due) {
                                                $item_status_text = translate('partly_paid');
                                                $item_labelmode = 'label-info-custom';
                                            } elseif ($effective_due <= 0.001) { 
                                                $item_status_text = translate('total_paid');
                                                $item_labelmode = 'label-success-custom';
                                            } else { 
                                                $item_status_text = translate('unpaid'); 
                                                $item_labelmode = 'label-danger-custom';
                                            }
                                            echo "<span class='label ".htmlspecialchars($item_labelmode)." '>".htmlspecialchars($item_status_text)."</span>";
                                        ?></td>
                                        <td><?php echo $currency_symbol . number_format($item_base_amount, 2, '.', '');?></td>
                                        <?php if ($_overall_invoice_summary_discount >= 1): ?>
                                        <td><?php echo $currency_symbol . number_format($type_discount, 2, '.', '');?></td>
                                        <?php endif; ?>
                                        <?php if ($_overall_invoice_summary_fine >= 1): ?>
                                        <td><?php echo $currency_symbol . number_format($type_fine, 2, '.', '');?></td>
                                        <?php endif; ?>
                                        <td><?php echo $currency_symbol . number_format($type_amount, 2, '.', '');?></td>
                                        <td class="text-center"><?php echo $currency_symbol . number_format($balance + $type_fine, 2, '.', '');?></td>
                                    </tr>
                                    <?php 
                                        } 
                                    } else {
                                        $no_fees_colspan = 8 + ($_overall_invoice_summary_discount >= 1 ? 1 : 0) + ($_overall_invoice_summary_fine >= 1 ? 1 : 0);
                                        echo '<tr><td colspan="' . $no_fees_colspan . '" class="text-center">' . translate('no_fees_found') . '</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="invoice-summary text-right mt-lg hidden-print">
                            <div class="row">
                                <div class="col-md-5 col-xs-12 pull-right">
                                    <ul class="amounts" style="list-style-type: none; padding-left: 0;">
                                        <li><strong><?=translate('grand_total')?> :</strong> <?=$currency_symbol . number_format($total_amount, 2, '.', ''); ?></li>
                                        <?php if ($total_discount >= 1): ?>
                                        <li><strong><?=translate('discount')?> :</strong> <?=$currency_symbol . number_format($total_discount, 2, '.', ''); ?></li>
                                        <?php endif; ?>
                                        <li><strong><?=translate('paid')?> :</strong> <?=$currency_symbol . number_format($total_paid, 2, '.', ''); ?></li>
                                        <?php if ($total_fine >= 1):  ?>
                                        <li><strong><?=translate('fine')?> :</strong> <?=$currency_symbol . number_format($total_fine, 2, '.', ''); ?></li>
                                        <?php endif; ?>
                                        
                                        <?php
                                            $summary_final_balance = ($total_amount - $total_discount) + $total_fine - $total_paid;
                                            $summary_total_paid_with_fine = $total_paid + $total_fine;
                                        ?>
                                        <li><strong><?=translate('total_paid')?> (<?=translate('with_fine')?>) :</strong> <?=$currency_symbol . number_format($summary_total_paid_with_fine, 2, '.', ''); ?></li>

                                        <li>
                                            <strong><?=translate('balance')?> : </strong> 
                                            <?php
                                            $summary_final_balance_formatted = number_format($summary_final_balance, 2, '.', '');
                                            $numberSPELL = "";
                                            if ($extINTL == true && abs($summary_final_balance) >= 0.01) { 
                                                $numberSPELL = ' </br>( ' . ucwords($spellout->format(abs($summary_final_balance))) . ' )';
                                            }
                                            echo $currency_symbol . $summary_final_balance_formatted . $numberSPELL;
                                            ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-summary text-right mt-lg visible-print-block" id="invDetailsPrint"></div> 
                    </div>
                    <div class="text-right mr-lg hidden-print">
                        <button id="invoicePrint" class="btn btn-default ml-sm" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><i class="fas fa-print"></i> <?=translate('print')?></button>
                    </div>
                </div>
            </div>
            
            <?php if ($invoice['status'] != 'unpaid'): ?>
            <div class="tab-pane" id="history">
                <div id="payment_print">
                    <div class="invoice payment">
                        <header class="clearfix">
                             <div class="row">
                                <div class="col-xs-3"> 
                                    <div class="ib">
                                        <img src="<?=$this->application_model->getBranchImage($basic['branch_id'], 'printing-logo')?>" alt="School Logo" onerror="this.onerror=null;this.src='https://placehold.co/150x70/cccccc/000000?text=Logo';" />
                                    </div>
                                </div>
                           
                             <div class="col-xs-6 school-details-column" style="font-size: 17px; line-height: 1; text-align: center; word-wrap: break-word; max-width: 100%; font-weight: 700; color: #FF0000;">
                                    <?php echo htmlspecialchars($basic['school_name']); ?>
                                    <p><?php echo htmlspecialchars($basic['school_address']); ?></p>
                                    <p><?php echo htmlspecialchars($basic['school_mobileno']); ?></p>
                                    <p><?php echo htmlspecialchars($basic['school_email']); ?></p>
                                </div>
                                <div class="col-xs-3 text-right">
                                    <h4 class="mt-none mb-none text-dark">Invoice No #<?php echo htmlspecialchars($invoice['invoice_no']); ?></h4>
                                    <p class="mb-none">
                                        <span class="text-dark"><?=translate('date')?> : </span>
                                        <span class="value"><?php echo _d(date('Y-m-d'));?></span>
                                    </p>
                                    <p class="mb-none">
                                        <span class="text-dark"><?=translate('status')?> : </span>
                                        <?php
                                            $history_status_text = ''; 
                                            $history_labelmode = '';   
                                            if($invoice['status'] == 'unpaid') {
                                                $history_status_text = translate('unpaid');
                                                $history_labelmode = 'label-danger-custom';
                                            } elseif($invoice['status'] == 'partly') {
                                                $history_status_text = translate('partly_paid');
                                                $history_labelmode = 'label-info-custom';
                                            } elseif($invoice['status'] == 'total') {
                                                $history_status_text = translate('total_paid');
                                                $history_labelmode = 'label-success-custom';
                                            }
                                            echo "<span class='value label ".htmlspecialchars($history_labelmode)." '>".htmlspecialchars($history_status_text)."</span>";
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </header>
                        <div class="bill-info">
                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="bill-data">
                                        <p class="h5 mb-xs text-dark text-weight-semibold">Invoice To :</p>
                                        <address style="font-style: normal;">
                                            <?php 
                                            echo htmlspecialchars($basic['first_name'] . ' '. $basic['last_name']) . '<br>';
                                            echo (empty($basic['student_address']) ? "" : nl2br(htmlspecialchars($basic['student_address'])) . '<br>');
                                            echo translate('class').' : '. htmlspecialchars($basic['class_name']) . '<br>';
                                            echo translate('email').' : '. htmlspecialchars($basic['student_email']); 
                                            ?>
                                        </address>
                                    </div>
                                </div>
                                <!-- <div class="col-xs-6"> 
                                    <div class="bill-data text-right"> 
                                        <p class="h5 mb-xs text-dark text-weight-semibold">Academic :</p>
                                        <address style="font-style: normal;">
                                            <?php 
                                            echo htmlspecialchars($basic['school_name']) . "<br/>";
                                            echo htmlspecialchars($basic['school_address']) . "<br/>";
                                            echo htmlspecialchars($basic['school_mobileno']) . "<br/>";
                                            echo htmlspecialchars($basic['school_email']) . "<br/>";
                                            ?>
                                        </address>
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    <?php if (get_permission('fees_revert', 'is_delete')): ?>
                        <button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="selected_revert" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <i class="fas fa-trash-restore-alt"></i> <?php echo translate('selected_revert'); ?>
                        </button>
                    <?php endif; ?>
                        <div class="table-responsive">
                            <table class="table invoice-items" id="paymentHistory"> <thead>
                                <tr class="h5 text-dark">
                                    <th class="text-weight-semibold hidden-print">
                                        <div class="checkbox-replace" >
                                            <label class="i-checks" data-toggle="tooltip" data-original-title="Print Show / Hidden">
                                                <input type="checkbox" class="fee-selectAll" checked> <i></i>
                                            </label>
                                        </div>
                                    </th>
                                    <th class="text-weight-semibold"><?=translate('fees_type')?></th>
                                    <th class="text-weight-semibold"><?=translate('fees_code')?></th>
                                    <th class="text-weight-semibold"><?=translate('date')?></th>
                                    <th class="text-weight-semibold hidden-print"><?=translate('collect_by')?></th>
                                    <th class="text-weight-semibold"><?=translate('remarks')?></th>
                                    <th class="text-weight-semibold"><?=translate('method')?></th>
                                    <th class="text-weight-semibold"><?=translate('amount')?></th>
                                    <?php if ($_overall_payment_history_discount >= 1): ?>
                                    <th class="text-weight-semibold"><?=translate('discount')?></th>
                                    <?php endif; ?>
                                    <?php if ($_overall_payment_history_fine >= 1): ?>
                                    <th class="text-weight-semibold"><?=translate('fine')?></th>
                                    <?php endif; ?>
                                    <th class="text-weight-semibold"><?=translate('paid')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $history_total_amount = 0; 
                                $history_total_discount = 0; 
                                $history_total_fine = 0; 
                                $history_total_paid = 0; 
                                $found_history = false;

                                if(!empty($allocations_for_history_check)) {
                                    foreach ($allocations_for_history_check as $allRow) {
                                        $historys = $this->fees_model->getPaymentHistory($allRow['id'], $allRow['group_id']);
                                        if(!empty($historys)){
                                            $found_history = true;
                                            foreach ($historys as $row) {
                                                $history_payment_amount = isset($row['amount']) ? (float)$row['amount'] : 0; 
                                                $history_payment_discount = isset($row['discount']) ? (float)$row['discount'] : 0;
                                                $history_payment_fine = isset($row['fine']) ? (float)$row['fine'] : 0;
                                                
                                                $history_total_amount += ($history_payment_amount + $history_payment_discount); 
                                                $history_total_discount += $history_payment_discount;
                                                $history_total_fine += $history_payment_fine;
                                                $history_total_paid += $history_payment_amount; 
                                ?>
                                <tr>
                                    <td class="hidden-print checked-area">
                                        <div class="checkbox-replace">
                                            <label class="i-checks"><input type="checkbox" name="cb_feePay" value="<?php echo htmlspecialchars($row['id']); ?>" checked><i></i></label>
                                        </div>
                                    </td>
                                    <td class="text-weight-semibold text-dark"><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['fee_code']); ?></td>
                                    <td><?php echo _d($row['date']); ?></td>
                                    <td class="hidden-print">
                                        <?php
                                            if ($row['collect_by'] == 'online') {
                                                echo translate('online');
                                            } else {
                                                echo htmlspecialchars(get_type_name_by_id('staff', $row['collect_by']));
                                            }
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['remarks']); ?></td>
                                    <td><?php echo htmlspecialchars($row['payvia']); ?></td>
                                    <td><?php echo $currency_symbol . number_format(($history_payment_amount + $history_payment_discount), 2, '.', ''); ?></td>
                                    <?php if ($_overall_payment_history_discount >= 1): ?>
                                    <td><?php echo $currency_symbol . number_format($history_payment_discount, 2, '.', ''); ?></td>
                                    <?php endif; ?>
                                    <?php if ($_overall_payment_history_fine >= 1): ?>
                                    <td><?php echo $currency_symbol . number_format($history_payment_fine, 2, '.', ''); ?></td>
                                    <?php endif; ?>
                                    <td><?php echo $currency_symbol . number_format($history_payment_amount, 2, '.', ''); ?></td>
                                </tr>
                                <?php           } 
                                        } 
                                    } 
                                } 
                                
                                if (!$found_history) {
                                    $no_history_colspan = 9 + ($_overall_payment_history_discount >= 1 ? 1 : 0) + ($_overall_payment_history_fine >= 1 ? 1 : 0);
                                    echo '<tr><td colspan="' . $no_history_colspan . '" class="text-center">' . translate('no_payment_history_found') . '</td></tr>';
                                }
                                ?>
                            </tbody>
                            </table>
                        </div>
                        <?php if ($found_history): ?>
                        <div class="invoice-summary text-right mt-lg hidden-print">
                            <div class="row">
                                <div class="col-md-5 col-xs-12 pull-right">
                                    <ul class="amounts" style="list-style-type: none; padding-left: 0;">
                                        <li><strong><?=translate('sub_total')?> :</strong> <?=$currency_symbol . number_format($history_total_amount, 2, '.', ''); ?></li>
                                        <?php if ($history_total_discount >= 1): ?>
                                        <li><strong><?=translate('discount')?> :</strong> <?=$currency_symbol . number_format($history_total_discount, 2, '.', ''); ?></li>
                                        <?php endif; ?>
                                        <li><strong><?=translate('paid')?> :</strong> <?=$currency_symbol . number_format($history_total_paid, 2, '.', ''); ?></li>
                                        <?php if ($history_total_fine >= 1): ?>
                                        <li><strong><?=translate('fine')?> :</strong> <?=$currency_symbol . number_format($history_total_fine, 2, '.', ''); ?></li>
                                        <?php endif; ?>
                                        <li>
                                            <strong><?=translate('total_paid')?> (<?=translate('with_fine')?>) : </strong> 
                                            <?php
                                            $numberSPELL = "";
                                            $grand_paid_history = $history_total_paid + $history_total_fine;
                                            $grand_paid_history_formatted = number_format($grand_paid_history, 2, '.', '');
                                            if ($extINTL == true && abs($grand_paid_history) >= 0.01) { 
                                                $numberSPELL = ' </br>( ' . ucwords($spellout->format(abs($grand_paid_history))) . ' )';
                                            }
                                            echo $currency_symbol . $grand_paid_history_formatted . $numberSPELL;
                                            ?>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="invoice-summary text-right mt-lg visible-print-block" id="invPaymentHistory"></div> 
                        <?php endif; ?>
                    </div>
                    <div class="text-right mr-lg hidden-print">
                        <button id="paymentPrint" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><i class="fas fa-print"></i> <?=translate('print')?></button>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <?php if($invoice['status'] != 'total' && get_permission('collect_fees', 'is_add')): ?>
                <div id="collect_fees" class="tab-pane">
                    <?php echo form_open('fees/fee_add', array('class' => 'form-horizontal frm-submit' )); ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('fees_type')?> <span class="required">*</span></label>
                            <div class="col-md-6">
                            <?php
                                echo form_dropdown("fees_type", $typeData, set_value('fees_type'), "class='form-control' id='fees_type'
                                data-plugin-selectTwo data-width='100%' ");
                            ?>
                            <span class="error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('date')?> <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" data-plugin-datepicker
                                data-plugin-options='{"todayHighlight" : true, "endDate": "today"}' name="date" value="<?=date('Y-m-d')?>" autocomplete="off" />
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('amount')?> <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="amount" id="feeAmount" value="" autocomplete="off" />
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('discount')?></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="discount_amount" value="0" autocomplete="off" />
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('fine')?></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="fine_amount" id="fineAmount" value="0" autocomplete="off" />
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('payment_method')?> <span class="required">*</span></label>
                            <div class="col-md-6">
                                <?php
                                    $payvia_list = $this->app_lib->getSelectList('payment_types');
                                    echo form_dropdown("pay_via", $payvia_list, set_value('pay_via'), "class='form-control' data-plugin-selectTwo data-width='100%'
                                    data-minimum-results-for-search='Infinity' ");
                                ?>
                                <span class="error"></span>
                            </div>
                        </div>
                        <?php
                        $links = $this->fees_model->get('transactions_links', array('branch_id' => $basic['branch_id']), true);
                        if ($links['status'] == 1) {
                        ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo translate('account'); ?> <span class="required">*</span></label>
                                <div class="col-md-6">
                                <?php
                                    $accounts_list = $this->app_lib->getSelectByBranch('accounts', $basic['branch_id']);
                                    echo form_dropdown("account_id", $accounts_list, $links['deposit'], "class='form-control' id='account_id' required data-plugin-selectTwo data-width='100%'");
                                ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('remarks')?></label>
                            <div class="col-md-6 mb-md">
                                <textarea name="remarks" rows="2" class="form-control" placeholder="<?=translate('write_your_remarks')?>"></textarea>
                                <div class="checkbox-replace mt-lg">
                                    <label class="i-checks">
                                        <input type="checkbox" name="guardian_sms" checked> <i></i> <?=translate('guardian_confirmation_sms') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="branch_id" value="<?=htmlspecialchars($basic['branch_id'])?>">
                        <input type="hidden" name="student_id" value="<?=htmlspecialchars($basic['id'])?>">
                        <footer class="panel-footer">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-3">
                                    <button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                                        <?=translate('fee_payment')?>
                                    </button>
                                </div>
                            </div>
                        </footer>
                    <?php echo form_close();?>
                </div>
            <?php endif; ?>
            <?php if($invoice['status'] != 'total' && get_permission('collect_fees', 'is_add')): ?>
                <div id="fully_paid" class="tab-pane">
                    <?php echo form_open('fees/fee_fully_paid', array('class' => 'form-horizontal frm-submit' )); ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('date')?> <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" data-plugin-datepicker
                                data-plugin-options='{"todayHighlight" : true, "endDate":"today"}' name="date" value="<?=date('Y-m-d')?>" autocomplete="off" />
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('amount')?> <span class="required">*</span></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="amount" value="<?=number_format($total_balance, 2, '.', '')?>" autocomplete="off" readonly />
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('fine')?></label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="fine_amount" value="<?=number_format($fully_total_fine, 2, '.', '')?>" autocomplete="off" readonly />
                                <span class="error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('payment_method')?> <span class="required">*</span></label>
                            <div class="col-md-6">
                                <?php
                                    $payvia_list_fully = $this->app_lib->getSelectList('payment_types'); 
                                    echo form_dropdown("pay_via", $payvia_list_fully, set_value('pay_via'), "class='form-control' data-plugin-selectTwo data-width='100%'
                                    data-minimum-results-for-search='Infinity' ");
                                ?>
                                <span class="error"></span>
                            </div>
                        </div>
                        <?php
                        $links_fully = $this->fees_model->get('transactions_links', array('branch_id' => $basic['branch_id']), true); 
                        if ($links_fully['status'] == 1) {
                        ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo translate('account'); ?> <span class="required">*</span></label>
                                <div class="col-md-6">
                                <?php
                                    $accounts_list_fully = $this->app_lib->getSelectByBranch('accounts', $basic['branch_id']); 
                                    echo form_dropdown("account_id", $accounts_list_fully, $links_fully['deposit'], "class='form-control' id='account_id_fully' required data-plugin-selectTwo data-width='100%'"); 
                                ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label"><?=translate('remarks')?></label>
                            <div class="col-md-6 mb-md">
                                <textarea name="remarks" rows="2" class="form-control" placeholder="<?=translate('write_your_remarks')?>"></textarea>
                                <div class="checkbox-replace mt-lg">
                                    <label class="i-checks">
                                        <input type="checkbox" name="guardian_sms" checked> <i></i> <?=translate('guardian_confirmation_sms')?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="invoice_id" value="<?php echo htmlspecialchars($invoice['id']); ?>">
                        <input type="hidden" name="branch_id" value="<?=htmlspecialchars($basic['branch_id'])?>">
                        <input type="hidden" name="student_id" value="<?=htmlspecialchars($basic['id'])?>">
                        <footer class="panel-footer">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-3">
                                    <button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                                        <?=translate('fee_payment')?>
                                    </button>
                                </div>
                            </div>
                        </footer>
                    <?php echo form_close();?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<div class="zoom-anim-dialog modal-block mfp-hide modal-block-full" id="modal">
    <section class="panel">
        <header class="panel-heading">
            <h4 class="panel-title"><i class="fas fa-coins fa-fw"></i> <?=translate('collect_fees')?>
                <button type="button" class="close modal-dismiss" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </h4>
        </header>
        <?php echo form_open('fees/selectedFeesPay', array('class' => 'frm-submit' )); ?>
        <div class="panel-body">
            <div id="printResult" class="pt-sm pb-sm">
                <div class="table-responsive">                                      
                    <table class="table table-bordered table-condensed text-dark" id="feeCollect">
                        </table>
                </div>
            </div>
        </div>
        <footer class="panel-footer">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><?=translate('fee_payment') ?></button>
                </div>
            </div>
        </footer>
        <?php echo form_close();?>
    </section>
</div>

<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>"; 
    var branchID = "<?php echo htmlspecialchars($basic['branch_id']); ?>";
    var studentID = "<?php echo htmlspecialchars($basic['id']); ?>";

    $(document).ready(function() {
        $(".fee-selectAll").on("change", function(ev) {
            var $chcks = $(this).closest("table").find("tbody input[type='checkbox']");
            if($(this).is(':checked')) {
                $chcks.prop('checked', true).trigger('change');
            } else {
                $chcks.prop('checked', false).trigger('change');
            }
        });

        $('#collectFees').on('click', function(e) {
            var $btn = $(this);
            $btn.button('loading');
            var arrayData = [];
            $("#invoiceSummary tbody input[name='cb_invoice']:checked").each(function() {
                var allocationID = $(this).data("allocation-id");
                var feeTypeID = $(this).data("fee-type-id");
                var item = {}; 
                item ["allocationID"] = allocationID;
                item ["feeTypeID"] = feeTypeID;
                arrayData.push(item);
            });

            if (arrayData.length === 0) {
                if(typeof swal === 'function') {
                    swal({title: "<?=translate('information')?>", text: "<?=translate('no_rows_selected')?>", type: "info", buttonsStyling: false, confirmButtonClass: "btn btn-default swal2-btn-default"});
                } else {
                    alert("<?=translate('no_rows_selected')?>"); 
                }
                $btn.button('reset');
            } else {
                $.ajax({
                    url: base_url + "fees/selectedFeesCollect",
                    type: 'POST',
                    data: {
                        'data': JSON.stringify(arrayData),
                        'branch_id': branchID,
                        'student_id' : studentID,
                    },
                    dataType: "html",
                    cache: false,
                    success: function (response) {
                        $("#feeCollect").html(response);
                        if (typeof $.fn.themePluginSelect2 === 'function') {
                            $("#modal .selectTwo").each(function() { $(this).themePluginSelect2({}); });
                        }
                        if (typeof $.fn.themePluginDatePicker === 'function') {
                            $("#modal .datepicker").each(function() { 
                                $(this).themePluginDatePicker({ "todayHighlight" : true, "endDate" : "today" }); 
                            });
                        }
                        if (typeof mfp_modal === 'function') {
                            mfp_modal('#modal');
                        } else if (typeof $.magnificPopup !== 'undefined') {
                             $.magnificPopup.open({ items: { src: '#modal' }, type: 'inline', callbacks: { close: function() { $btn.button('reset');}} });
                        } else {
                             $btn.button('reset'); 
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) { 
                        console.error("AJAX Error for selectedFeesCollect: ", textStatus, errorThrown);
                        if(typeof swal === 'function') {
                            swal({title: "<?=translate('error')?>", text: "<?=translate('ajax_request_failed')?>: " + errorThrown, type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-default swal2-btn-default"});
                        } else {
                            alert("<?=translate('ajax_request_failed')?>: " + errorThrown);
                        }
                        $btn.button('reset');
                    }
                });
            }
        });

        $('#invoicePrint').on('click', function(e) {
            var $btn = $(this);
            $btn.button('loading');
            var arrayData = [];
            var hasCheckedItems = false;
            $("#invoiceSummary tbody tr").removeClass("hidden-print"); 
            $("#invoiceSummary tbody input[name='cb_invoice']").each(function() {
                if($(this).is(':checked')) {
                    hasCheckedItems = true;
                    var allocationID = $(this).data("allocation-id");
                    var feeTypeID = $(this).data("fee-type-id");
                    var item = {};
                    item ["allocationID"] = allocationID;
                    item ["feeTypeID"] = feeTypeID;
                    arrayData.push(item);
                    $(this).closest('tr').removeClass("hidden-print"); 
                } else {
                    $(this).closest('tr').addClass("hidden-print");
                }
            });

            if (!hasCheckedItems) { 
                if(typeof swal === 'function') {
                    swal({title: "<?=translate('information')?>", text: "<?=translate('no_rows_selected_to_print')?>", type: "info", buttonsStyling: false, confirmButtonClass: "btn btn-default swal2-btn-default"});
                } else {
                    alert("<?=translate('no_rows_selected_to_print')?>");
                }
                $btn.button('reset');
                $("#invoiceSummary tbody tr").removeClass("hidden-print"); 
            } else {
                $("#invDetailsPrint").html(""); 
                $.ajax({
                    url: base_url + "fees/printFeesInvoice",
                    type: 'POST',
                    data: {'data': JSON.stringify(arrayData)}, 
                    dataType: "html",
                    cache: false,
                    success: function (response) {
                        $("#invDetailsPrint").html(response); 
                        if (typeof fn_printElem === 'function') {
                            fn_printElem('invoice_print'); 
                        } else {
                            window.print(); 
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) { 
                        console.error("AJAX Error for printFeesInvoice: ", textStatus, errorThrown);
                        if(typeof swal === 'function') {
                            swal({title: "<?=translate('error')?>", text: "<?=translate('ajax_request_failed_for_print')?>: " + errorThrown, type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-default swal2-btn-default"});
                        } else {
                            alert("<?=translate('ajax_request_failed_for_print')?>: " + errorThrown);
                        }
                    },
                    complete: function () {
                        $btn.button('reset');
                    }
                });
            }
        });

        $('#paymentPrint').on('click', function(e) {
            var $btn = $(this);
            $btn.button('loading');
            var arrayData = [];
            var hasCheckedPaymentItems = false;
            $("#paymentHistory tbody tr").removeClass("hidden-print"); 
            $("#paymentHistory tbody input[name='cb_feePay']").each(function() {
                if($(this).is(':checked')) {
                    hasCheckedPaymentItems = true;
                    var paymentID = $(this).val();
                    var item = {};
                    item ["payment_id"] = paymentID;
                    arrayData.push(item);
                    $(this).closest('tr').removeClass("hidden-print");
                } else {
                    $(this).closest('tr').addClass("hidden-print");
                }
            });

            if (!hasCheckedPaymentItems) {
                if(typeof swal === 'function') {
                    swal({title: "<?=translate('information')?>", text: "<?=translate('no_payment_history_selected_to_print')?>", type: "info", buttonsStyling: false, confirmButtonClass: "btn btn-default swal2-btn-default"});
                } else {
                    alert("<?=translate('no_payment_history_selected_to_print')?>");
                }
                $btn.button('reset');
                $("#paymentHistory tbody tr").removeClass("hidden-print"); 
            } else {
                $("#invPaymentHistory").html("");
                $.ajax({
                    url: base_url + "fees/printFeesPaymentHistory",
                    type: 'POST',
                    data: {'data': JSON.stringify(arrayData)},
                    dataType: "html",
                    cache: false,
                    success: function (response) {
                        $("#invPaymentHistory").html(response);
                        if (typeof fn_printElem === 'function') {
                            fn_printElem('payment_print');
                        } else {
                            window.print();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) { 
                        console.error("AJAX Error for printFeesPaymentHistory: ", textStatus, errorThrown);
                         if(typeof swal === 'function') {
                            swal({title: "<?=translate('error')?>", text: "<?=translate('ajax_request_failed_for_print')?>: " + errorThrown, type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-default swal2-btn-default"});
                        } else {
                            alert("<?=translate('ajax_request_failed_for_print')?>: " + errorThrown);
                        }
                    },
                    complete: function () {
                        $btn.button('reset');
                    }
                });
            }
        });

        $('#selected_revert').on('click', function(e){
            var $this = $(this);
            var paymentID = [];
            $("#paymentHistory tbody input[name='cb_feePay']:checked").each(function() {
                paymentID.push($(this).val());
            });

            if (paymentID.length === 0) {
                if(typeof swal === 'function') {
                    swal({title: "<?=translate('information')?>", text: "<?=translate('no_payment_selected_to_revert')?>", type: "info", buttonsStyling: false, confirmButtonClass: "btn btn-default swal2-btn-default"});
                } else {
                    alert("<?=translate('no_payment_selected_to_revert')?>");
                }
                return;
            }

            if (typeof swal !== 'function') {
                alert('SweetAlert is not loaded.'); return;
            }

            swal({
                title: "<?php echo translate('are_you_sure')?>",
                text: "<?php echo translate('revert_this_payment_information') ?>",
                type: "warning", 
                showCancelButton: true,
                confirmButtonClass: "btn btn-default swal2-btn-confirm", 
                cancelButtonClass: "btn btn-default swal2-btn-cancel",  
                confirmButtonText: "<?php echo translate('yes_revert_it')?>",
                cancelButtonText: "<?php echo translate('cancel')?>",
                buttonsStyling: false, 
            }).then((result) => {
                if (result.value || (typeof result.isConfirmed !== 'undefined' && result.isConfirmed)) { 
                    $.ajax({
                        url: base_url + 'fees/paymentRevert',
                        type: "POST",
                        data: {'id': paymentID}, 
                        dataType: "JSON",
                        beforeSend: function () {
                            $this.button('loading');
                        },
                        success:function(data) {
                            swal({
                                title: data.status == 'success' ? "<?php echo translate('reverted')?>" : "<?php echo translate('error')?>",
                                text: data.message,
                                buttonsStyling: false,
                                showCloseButton: true,
                                focusConfirm: false,
                                confirmButtonClass: "btn btn-default swal2-btn-default",
                                type: data.status 
                            }).then((result) => {
                                if (result.value || (typeof result.isConfirmed !== 'undefined' && result.isConfirmed)) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(xhr, status, error) { 
                            swal("<?php echo translate('error'); ?>", "<?php echo translate('ajax_request_failed'); ?>: " + error, "error");
                            $this.button('reset'); 
                        },
                        complete: function () {
                        }
                    });
                } else {
                     $this.button('reset'); 
                }
            });
        });

        $('#fees_type').on("change", function(){
            var typeID = $(this).val();
            if (!typeID) { 
                 $('#feeAmount').val('0.00');
                 $('#fineAmount').val('0.00');
                 return;
            }
            $.ajax({
                url: base_url + 'fees/getBalanceByType', 
                type: 'POST',
                data: {
                    'typeID': typeID,
                    'student_id': studentID 
                },
                dataType: "json",
                success: function (data) {
                    if(data && typeof data.balance !== 'undefined' && typeof data.fine !== 'undefined'){
                        $('#feeAmount').val(parseFloat(data.balance).toFixed(2));
                        $('#fineAmount').val(parseFloat(data.fine).toFixed(2));
                    } else {
                        $('#feeAmount').val('0.00');
                        $('#fineAmount').val('0.00');
                        console.error("Invalid response from getBalanceByType:", data);
                    }
                },
                error: function(xhr, status, error) { 
                    console.error("AJAX error getBalanceByType:", status, error);
                    $('#feeAmount').val('0.00');
                    $('#fineAmount').val('0.00');
                    if(typeof swal === 'function') {
                        swal({title: "<?=translate('error')?>", text: "<?=translate('ajax_request_failed_balance_type')?>", type: "error", buttonsStyling: false, confirmButtonClass: "btn btn-default swal2-btn-default"});
                    } else {
                        alert("<?=translate('ajax_request_failed_balance_type')?>");
                    }
                }
            });
        });
    });
</script>
