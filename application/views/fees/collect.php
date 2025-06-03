<?php
// Assuming $global_config, $basic, $invoice, $this->fees_model, $this->application_model, $this->db, $this->app_lib, etc. are available
// and populated from your CodeIgniter framework.

$currency_symbol = isset($global_config['currency_symbol']) ? htmlspecialchars($global_config['currency_symbol']) : '$';
$extINTL = extension_loaded('intl');
if ($extINTL == true) {
    $spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
}

// --- Pre-calculate total discount for the Invoice Summary table ---
$_overall_invoice_summary_discount = 0;
if (isset($basic['id'])) {
    $allocations_for_check = $this->fees_model->getInvoiceDetails($basic['id']);
    if (!empty($allocations_for_check)) {
        foreach ($allocations_for_check as $row_check) {
            $deposit_check = $this->fees_model->getStudentFeeDeposit($row_check['allocation_id'], $row_check['fee_type_id']);
            $_overall_invoice_summary_discount += isset($deposit_check['total_discount']) ? (float)$deposit_check['total_discount'] : 0;
        }
    }
}

// --- Pre-calculate total discount for the Payment History table ---
$_overall_payment_history_discount = 0;
if (isset($basic['id']) && $invoice['status'] != 'unpaid') {
    $allocations_for_history_check = $this->db->where(array('student_id' => $basic['id'], 'session_id' => get_session_id()))->get('fee_allocation')->result_array();
    if(!empty($allocations_for_history_check)) {
        foreach ($allocations_for_history_check as $allRow_check) {
            $historys_for_check = $this->fees_model->getPaymentHistory($allRow_check['id'], $allRow_check['group_id']);
            if(!empty($historys_for_check)){
                foreach ($historys_for_check as $row_hist_check) {
                    $_overall_payment_history_discount += isset($row_hist_check['discount']) ? (float)$row_hist_check['discount'] : 0;
                }
            }
        }
    }
}

// Calculate colspan for invoice summary table (9 base columns: checkbox, #, type, due, status, amount, fine, paid, balance. +1 if discount shown)
$invoice_table_base_colspan = 8; // #, type, due, status, amount, fine, paid, balance (visible on print)
$invoice_table_full_colspan = 10; // checkbox, #, type, due, status, amount, discount (cond), fine, paid, balance
$invoice_table_dynamic_colspan_header = $invoice_table_full_colspan - ($_overall_invoice_summary_discount > 0 ? 0 : 1);
$invoice_table_dynamic_colspan_group = $invoice_table_full_colspan - 2 - ($_overall_invoice_summary_discount > 0 ? 0 : 1); // Checkbox and # are not part of group colspan

// Calculate colspan for payment history table (10 base columns. +1 if discount shown)
$history_table_base_colspan = 8; // type, code, date, remarks, method, amount, fine, paid (visible on print)
$history_table_full_colspan = 11; // checkbox, type, code, date, collect_by, remarks, method, amount, discount (cond), fine, paid
$history_table_dynamic_colspan = $history_table_full_colspan - ($_overall_payment_history_discount > 0 ? 0 : 1);

?>
<style>
    /* General Invoice Styling */
    #invoice_print .invoice,
    #payment_print .invoice.payment {
        font-family: Arial, sans-serif; /* Or a font of your choice */
    }

    /* Invoice Header Styling */
    #invoice_print .invoice header.clearfix,
    #payment_print .invoice.payment header.clearfix {
        border: 1px solid #000; /* Solid border for the header */
        padding: 20px;
        margin-bottom: 25px;
        background-color: #f9f9f9; /* Light background for header */
    }

    #invoice_print .invoice header .ib img,
    #payment_print .invoice.payment header .ib img {
        max-height: 70px; /* Consistent logo height */
        width: auto;
        vertical-align: middle;
    }

    /* School Details Section in Header */
    #invoice_print .invoice header .school-details-column,
    #payment_print .invoice.payment header .school-details-column {
        display: flex;
        flex-direction: column;
        align-items: center; /* Center content horizontally */
        justify-content: center; /* Center content vertically */
        text-align: center; /* Ensure text is centered */
        min-height: 70px; /* From original style */
        padding: 0 10px; /* Add some padding */
    }

    #invoice_print .invoice header .school-details-column h3,
    #payment_print .invoice.payment header .school-details-column h3 {
        color: #D90429; /* Strong, thick red */
        font-weight: bold; /* Bold */
        font-size: 1.6em; /* Slightly larger */
        margin-top: 0;
        margin-bottom: 8px; /* Space below name */
        border-bottom: 2px solid #D90429; /* Red underline for "thick" feel */
        padding-bottom: 5px;
        display: inline-block; /* To make border-bottom only span the text width */
    }

    #invoice_print .invoice header .school-details-column p,
    #payment_print .invoice.payment header .school-details-column p {
        margin-bottom: 4px; /* Tighter spacing for address lines */
        line-height: 1.4;
        font-size: 0.9em;
        color: #333;
    }

    /* Invoice Info Section in Header (Invoice No, Date, Status) */
    #invoice_print .invoice header .col-xs-4.text-right,
    #payment_print .invoice.payment header .col-xs-4.text-right {
        display: flex;
        flex-direction: column;
        align-items: flex-end; /* Align text to the right */
        justify-content: center;
        min-height: 70px;
    }
     #invoice_print .invoice header .col-xs-4.text-right h4,
     #payment_print .invoice.payment header .col-xs-4.text-right h4 {
        margin-top:0;
        margin-bottom: 5px;
     }
     #invoice_print .invoice header .col-xs-4.text-right p,
     #payment_print .invoice.payment header .col-xs-4.text-right p {
        margin-bottom: 3px;
     }


    /* Table Styling */
    .table-responsive > .table.invoice-items,
    .table-responsive > .table#paymentHistory {
        border: 1px solid #333; /* Darker border for tables */
        width: 100%;
        margin-top: 20px; /* Space above table */
        margin-bottom: 1rem;
        color: #212529;
        border-collapse: collapse; /* Key for neat borders */
    }

    .table-responsive > .table.invoice-items th,
    .table-responsive > .table.invoice-items td,
    .table-responsive > .table#paymentHistory th,
    .table-responsive > .table#paymentHistory td {
        border: 1px solid #666; /* Medium gray border for cells */
        padding: 0.65rem 0.75rem; /* Adjust padding */
        vertical-align: middle; /* Better vertical alignment */
        font-size: 0.9em;
    }

    .table-responsive > .table.invoice-items thead th,
    .table-responsive > .table#paymentHistory thead th {
        vertical-align: middle;
        border-bottom: 2px solid #333; /* Thicker border for header bottom */
        background-color: #e9ecef; /* Light gray background for table headers */
        font-weight: bold; /* Make header text bold */
        color: #000;
    }

    /* Style for group rows in Invoice Summary */
    .table.invoice-items td.group {
        background-color: #f2f2f2; /* Lighter gray for group */
        font-weight: bold;
        border-top: 1px solid #666;
        border-bottom: 1px solid #666;
        padding: 0.75rem;
    }
    .table.invoice-items td.group img.group { /* Arrow image in group row */
        margin-left: 8px;
        height: 12px; /* Adjust size as needed */
        vertical-align: middle;
    }

    /* Status Labels Styling */
    .label.label-danger-custom,
    .label.label-info-custom,
    .label.label-success-custom {
        padding: .3em .6em .3em;
        font-size: 75%;
        font-weight: 700;
        line-height: 1;
        color: #fff;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
    }
    .label-danger-custom { background-color: #d9534f; }
    .label-info-custom { background-color: #5bc0de; }
    .label-success-custom { background-color: #5cb85c; }


    /* Print Specific Styles */
    @media print {
        body {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-size: 10pt !important; /* Adjust base font size for print */
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
            border: 1px solid #000 !important;
            background-color: #f9f9f9 !important; /* Keep light bg for header in print */
            padding: 15px !important; /* Adjust padding for print if needed */
        }

        #invoice_print .invoice header .school-details-column h3,
        #payment_print .invoice.payment header .school-details-column h3 {
            color: #D90429 !important;
            border-bottom-color: #D90429 !important;
        }
         #invoice_print .invoice header .school-details-column p,
         #payment_print .invoice.payment header .school-details-column p {
            color: #000 !important;
         }


        .table-responsive {
            overflow-x: visible !important; /* Ensure table doesn't get cut off */
        }
        .table-responsive > .table.invoice-items,
        .table-responsive > .table#paymentHistory {
            border: 1px solid #000 !important;
            font-size: 9pt !important; /* Slightly smaller font in tables for print */
        }
        .table-responsive > .table.invoice-items th,
        .table-responsive > .table.invoice-items td,
        .table-responsive > .table#paymentHistory th,
        .table-responsive > .table#paymentHistory td {
            border: 1px solid #000 !important;
            padding: 0.4rem 0.5rem !important; /* Tighter padding for print */
            color: #000 !important;
        }
        .table-responsive > .table.invoice-items thead th,
        .table-responsive > .table#paymentHistory thead th {
            background-color: #e9ecef !important;
            border-bottom: 2px solid #000 !important;
            color: #000 !important;
        }
        .table.invoice-items td.group {
            background-color: #f2f2f2 !important;
            color: #000 !important;
        }

        .label.label-danger-custom,
        .label.label-info-custom,
        .label.label-success-custom {
            /* Ensure labels print with background colors */
            border: 1px solid #000; /* Add border to make them stand out more if color doesn't print well */
        }
        .label-danger-custom { background-color: #d9534f !important; color: #fff !important; }
        .label-info-custom { background-color: #5bc0de !important; color: #fff !important; }
        .label-success-custom { background-color: #5cb85c !important; color: #fff !important; }


        /* Hide unnecessary elements during print */
        .tabs-custom .nav-tabs, .panel > .tabs-custom > .nav-tabs,
        .text-right.mr-lg.hidden-print,
        button, .btn, /* Hides all buttons */
        .mfp-hide,
        .panel-footer, /* Hides form footers */
        .invoice-summary.text-right.mt-lg.hidden-print, /* Hide screen summary */
        .checkbox-replace, /* Hide checkboxes */
        .hidden-print /* General catch-all */
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
        }
        /* Ensure the print-specific summary sections are visible */
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
                <a href="#fully_paid" data-toggle="tab"><i class="far fa-credit-card"></i> <?=translate('fully_paid') // Changed from "Fully Paid" to use translate for consistency ?></a>
            </li>
<?php endif; ?>
        </ul>
        <div class="tab-content">
            <div id="invoice" class="tab-pane <?=empty($this->session->flashdata('pay_tab')) ? 'active' : ''; ?>">
                <div id="invoice_print">
                    <div class="invoice">
                        <header class="clearfix">
                            <div class="row">
                                <div class="col-xs-4">
                                    <div class="ib">
                                        <img src="<?=$this->application_model->getBranchImage($basic['branch_id'], 'printing-logo')?>" alt="School Logo" onerror="this.onerror=null;this.src='https://placehold.co/150x70/cccccc/000000?text=Logo';" />
                                    </div>
                                </div>
                                <div class="col-xs-4 school-details-column"> <h3><?php echo htmlspecialchars($basic['school_name']); ?></h3>
                                    <p><?php echo htmlspecialchars($basic['school_address']); ?></p>
                                    <p><?php echo htmlspecialchars($basic['school_mobileno']); ?></p>
                                    <p><?php echo htmlspecialchars($basic['school_email']); ?></p>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <h4 class="mt-none mb-none text-dark">Invoice No #<?=htmlspecialchars($invoice['invoice_no'])?></h4>
                                    <p class="mb-none">
                                        <span class="text-dark"><?=translate('date')?> : </span>
                                        <span class="value"><?=_d(date('Y-m-d'))?></span>
                                    </p>
                                    <p class="mb-none">
                                        <span class="text-dark"><?=translate('status')?> : </span><?php
                                            $status_text = ''; // Renamed from $status to avoid conflict
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
                                <div class="col-xs-4 text-right"> </div>
                            </div>
                        </div>
                    <?php if (get_permission('collect_fees', 'is_add')) { ?>
                        <button type="button" class="btn btn-default btn-sm mb-sm hidden-print" id="collectFees" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                            <i class="fas fa-coins fa-fw"></i> <?=translate('selected_fees_collect') // Changed from "Selected Fees Collect" ?>
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
                                        <?php if ($_overall_invoice_summary_discount > 0): ?>
                                        <th class="text-weight-semibold"><?=translate("discount")?></th>
                                        <?php endif; ?>
                                        <th class="text-weight-semibold"><?=translate("fine")?></th>
                                        <th class="text-weight-semibold"><?=translate("paid")?></th>
                                        <th class="text-center text-weight-semibold"><?=translate("balance")?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $group = array();
                                        $count = 1;
                                        $total_fine = 0;
                                        $fully_total_fine = 0; // Used for "fully_paid" tab, ensure calculation is correct
                                        $total_discount = 0; // For summary section
                                        $total_paid = 0;     // For summary section
                                        $total_balance = 0;  // For summary section
                                        $total_amount = 0;   // For summary section (gross amount)
                                        $typeData = array('' => translate('select'));
                                        $allocations = $this->fees_model->getInvoiceDetails($basic['id']);
                                        
                                        if (!empty($allocations)) {
                                            foreach ($allocations as $row) {
                                                $deposit = $this->fees_model->getStudentFeeDeposit($row['allocation_id'], $row['fee_type_id']);
                                                $item_base_amount = isset($row['amount']) ? (float)$row['amount'] : 0;
                                                $type_discount = isset($deposit['total_discount']) ? (float)$deposit['total_discount'] : 0;
                                                $type_fine = isset($deposit['total_fine']) ? (float)$deposit['total_fine'] : 0;
                                                $type_amount = isset($deposit['total_amount']) ? (float)$deposit['total_amount'] : 0; // Amount paid for this item
                                                
                                                $balance = $item_base_amount - ($type_amount + $type_discount);

                                                $total_discount += $type_discount;
                                                $total_fine += $type_fine;
                                                $total_paid += $type_amount;
                                                $total_balance += $balance;
                                                $total_amount += $item_base_amount;

                                                if ($balance != 0) { // Or a more robust float comparison
                                                    $typeData[$row['allocation_id'] . "|" . $row['fee_type_id']] = $row['name'];
                                                    // This fine calculation seems specific for the "fully_paid" tab logic
                                                    $fine_calc = $this->fees_model->feeFineCalculation($row['allocation_id'], $row['fee_type_id']);
                                                    $b_fine_info = $this->fees_model->getBalance($row['allocation_id'], $row['fee_type_id']); // Renamed $b to avoid conflict
                                                    $fine_for_fully_paid = abs($fine_calc - (isset($b_fine_info['fine']) ? (float)$b_fine_info['fine'] : 0));
                                                    $fully_total_fine += $fine_for_fully_paid;
                                                }
                                        ?>
                                    <?php if(!in_array($row['group_id'], $group)) { 
                                        $group[] = $row['group_id'];
                                        // Dynamic colspan for group header row
                                        $group_header_colspan = 8 + ($_overall_invoice_summary_discount > 0 ? 1 : 0); // type, due, status, amount, [discount], fine, paid, balance
                                        ?>
                                    <tr>
                                        <td class="group hidden-print" colspan="2">&nbsp;</td> <td class="group" colspan="<?=$group_header_colspan?>"><strong><?php echo htmlspecialchars(get_type_name_by_id('fee_groups', $row['group_id'])); ?></strong><img class="group" src="<?php echo base_url('assets/images/arrow.png'); ?>" alt="arrow"></td>
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

                                            if($type_amount <= 0 && $effective_due > 0) {
                                                $item_status_text = translate('unpaid');
                                                $item_labelmode = 'label-danger-custom';
                                            } elseif($type_amount >= $effective_due && $effective_due > 0) {
                                                $item_status_text = translate('total_paid');
                                                $item_labelmode = 'label-success-custom';
                                            } elseif ($type_amount > 0 && $type_amount < $effective_due) {
                                                $item_status_text = translate('partly_paid');
                                                $item_labelmode = 'label-info-custom';
                                            } elseif ($effective_due <= 0) { // If amount after discount is zero or less, consider paid
                                                 $item_status_text = translate('total_paid');
                                                 $item_labelmode = 'label-success-custom';
                                            } else { // Default fallback, should ideally not be reached with above logic
                                                 $item_status_text = translate('unpaid');
                                                 $item_labelmode = 'label-danger-custom';
                                            }
                                            echo "<span class='label ".htmlspecialchars($item_labelmode)." '>".htmlspecialchars($item_status_text)."</span>";
                                        ?></td>
                                        <td><?php echo $currency_symbol . number_format($item_base_amount, 2, '.', '');?></td>
                                        <?php if ($_overall_invoice_summary_discount > 0): ?>
                                        <td><?php echo $currency_symbol . number_format($type_discount, 2, '.', '');?></td>
                                        <?php endif; ?>
                                        <td><?php echo $currency_symbol . number_format($type_fine, 2, '.', '');?></td>
                                        <td><?php echo $currency_symbol . number_format($type_amount, 2, '.', '');?></td>
                                        <td class="text-center"><?php echo $currency_symbol . number_format($balance + $type_fine, 2, '.', '');?></td>
                                    </tr>
                                    <?php 
                                        } // end foreach $allocations
                                    } else {
                                        $no_fees_colspan = 9 + ($_overall_invoice_summary_discount > 0 ? 1 : 0); // Checkbox, #, type, due, status, amount, [discount], fine, paid, balance
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
                                        <?php if ($total_discount > 0): // Show discount in summary if it exists ?>
                                        <li><strong><?=translate('discount')?> :</strong> <?=$currency_symbol . number_format($total_discount, 2, '.', ''); ?></li>
                                        <?php endif; ?>
                                        <li><strong><?=translate('paid')?> :</strong> <?=$currency_symbol . number_format($total_paid, 2, '.', ''); ?></li>
                                        <?php if ($total_fine > 0): // Show fine in summary if it exists ?>
                                        <li><strong><?=translate('fine')?> :</strong> <?=$currency_symbol . number_format($total_fine, 2, '.', ''); ?></li>
                                        <?php endif; ?>
                                        
                                        <?php
                                            $summary_total_paid_with_fine = $total_paid + $total_fine;
                                            $summary_final_balance = ($total_amount - $total_discount) + $total_fine - $total_paid;
                                        ?>
                                        <li><strong><?=translate('total_paid')?> (<?=translate('with_fine')?>) :</strong> <?=$currency_symbol . number_format($summary_total_paid_with_fine, 2, '.', ''); ?></li>

                                        <li>
                                            <strong><?=translate('balance')?> : </strong> 
                                            <?php
                                            $summary_final_balance_formatted = number_format($summary_final_balance, 2, '.', '');
                                            $numberSPELL = "";
                                            if ($extINTL == true && $summary_final_balance != 0) { // Check if $spellout is initialized
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
                                <div class="col-xs-4">
                                    <div class="ib">
                                        <img src="<?=$this->application_model->getBranchImage($basic['branch_id'], 'printing-logo')?>" alt="School Logo" onerror="this.onerror=null;this.src='https://placehold.co/150x70/cccccc/000000?text=Logo';" />
                                    </div>
                                </div>
                                <div class="col-xs-4 school-details-column"> <h3><?php echo htmlspecialchars($basic['school_name']); ?></h3>
                                     <p><?php echo htmlspecialchars($basic['school_address']); ?></p>
                                     <p><?php echo htmlspecialchars($basic['school_mobileno']); ?></p>
                                     <p><?php echo htmlspecialchars($basic['school_email']); ?></p>
                                </div>
                                <div class="col-xs-4 text-right">
                                    <h4 class="mt-none mb-none text-dark">Invoice No #<?php echo htmlspecialchars($invoice['invoice_no']); ?></h4>
                                    <p class="mb-none">
                                        <span class="text-dark"><?=translate('date')?> : </span>
                                        <span class="value"><?php echo _d(date('Y-m-d'));?></span>
                                    </p>
                                    <p class="mb-none">
                                        <span class="text-dark"><?=translate('status')?> : </span>
                                        <?php
                                            $history_status_text = ''; // Renamed from $status
                                            $history_labelmode = '';   // Renamed from $labelmode
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
                                <div class="col-xs-6"> <div class="bill-data text-right">
                                        <p class="h5 mb-xs text-dark text-weight-semibold">Academic :</p>
                                        <address style="font-style: normal;">
                                            <?php 
                                            // This seems to repeat school info, which is already in the header.
                                            // If this is intended for a different set of details, adjust accordingly.
                                            // For now, assuming it's a repetition and might be simplified or removed if redundant.
                                            echo htmlspecialchars($basic['school_name']) . "<br/>";
                                            echo htmlspecialchars($basic['school_address']) . "<br/>";
                                            echo htmlspecialchars($basic['school_mobileno']) . "<br/>";
                                            echo htmlspecialchars($basic['school_email']) . "<br/>";
                                            ?>
                                        </address>
                                    </div>
                                </div>
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
                                        <?php if ($_overall_payment_history_discount > 0): ?>
                                        <th class="text-weight-semibold"><?=translate('discount')?></th>
                                        <?php endif; ?>
                                        <th class="text-weight-semibold"><?=translate('fine')?></th>
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

                                    $allocations_hist = $this->db->where(array('student_id' => $basic['id'], 'session_id' => get_session_id()))->get('fee_allocation')->result_array();
                                    if(!empty($allocations_hist)) {
                                        foreach ($allocations_hist as $allRow) {
                                            $historys = $this->fees_model->getPaymentHistory($allRow['id'], $allRow['group_id']);
                                            if(!empty($historys)){
                                                $found_history = true;
                                                foreach ($historys as $row) {
                                                    $history_payment_amount = isset($row['amount']) ? (float)$row['amount'] : 0;
                                                    $history_payment_discount = isset($row['discount']) ? (float)$row['discount'] : 0;
                                                    $history_payment_fine = isset($row['fine']) ? (float)$row['fine'] : 0;
                                                    
                                                    // Accumulate totals for summary
                                                    $history_total_amount += ($history_payment_amount + $history_payment_discount); // Original amount before discount
                                                    $history_total_discount += $history_payment_discount;
                                                    $history_total_fine += $history_payment_fine;
                                                    $history_total_paid += $history_payment_amount; // 'amount' here is the net paid amount for this transaction
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
                                        <td><?php echo $currency_symbol . number_format(($history_payment_amount + $history_payment_discount), 2, '.', ''); // Gross amount for this transaction ?></td>
                                        <?php if ($_overall_payment_history_discount > 0): ?>
                                        <td><?php echo $currency_symbol . number_format($history_payment_discount, 2, '.', ''); ?></td>
                                        <?php endif; ?>
                                        <td><?php echo $currency_symbol . number_format($history_payment_fine, 2, '.', ''); ?></td>
                                        <td><?php echo $currency_symbol . number_format($history_payment_amount, 2, '.', ''); // Net paid amount ?></td>
                                    </tr>
                                    <?php       } // end foreach $historys
                                            } // end if !empty $historys
                                        } // end foreach $allocations_hist
                                    } // end if !empty $allocations_hist
                                    
                                    if (!$found_history) {
                                        $no_history_colspan = 10 + ($_overall_payment_history_discount > 0 ? 1 : 0);
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
                                        <li><strong><?=translate('sub_total')?> :</strong> <?=$currency_symbol . number_format($history_total_amount, 2, '.', ''); // This is sum of (paid + discount) per transaction ?></li>
                                        <?php if ($history_total_discount > 0): ?>
                                        <li><strong><?=translate('discount')?> :</strong> <?=$currency_symbol . number_format($history_total_discount, 2, '.', ''); ?></li>
                                        <?php endif; ?>
                                        <li><strong><?=translate('paid')?> :</strong> <?=$currency_symbol . number_format($history_total_paid, 2, '.', ''); // Sum of net paid amounts ?></li>
                                        <?php if ($history_total_fine > 0): ?>
                                        <li><strong><?=translate('fine')?> :</strong> <?=$currency_symbol . number_format($history_total_fine, 2, '.', ''); ?></li>
                                        <?php endif; ?>
                                        <li>
                                            <strong><?=translate('total_paid')?> (<?=translate('with_fine')?>) : </strong> 
                                            <?php
                                            $numberSPELL = "";
                                            $grand_paid_history = $history_total_paid + $history_total_fine;
                                            $grand_paid_history_formatted = number_format($grand_paid_history, 2, '.', '');
                                            if ($extINTL == true && $grand_paid_history != 0) { // Check if $spellout is initialized
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
            
            <?php if($invoice['status'] != 'total' && get_permission('collect_fees', 'is_add')): // Combined condition ?>
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
                                        <input type="checkbox" name="guardian_sms" checked> <i></i> <?=translate('guardian_confirmation_sms') // Changed for translation consistency ?>
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
            <?php if($invoice['status'] != 'total' && get_permission('collect_fees', 'is_add')): // Combined condition ?>
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
                                    $payvia_list_fully = $this->app_lib->getSelectList('payment_types'); // Use different var name if needed
                                    echo form_dropdown("pay_via", $payvia_list_fully, set_value('pay_via'), "class='form-control' data-plugin-selectTwo data-width='100%'
                                    data-minimum-results-for-search='Infinity' ");
                                ?>
                                <span class="error"></span>
                            </div>
                        </div>
                        <?php
                        $links_fully = $this->fees_model->get('transactions_links', array('branch_id' => $basic['branch_id']), true); // Use different var name
                        if ($links_fully['status'] == 1) {
                        ?>
                            <div class="form-group">
                                <label class="col-md-3 control-label"><?php echo translate('account'); ?> <span class="required">*</span></label>
                                <div class="col-md-6">
                                <?php
                                    $accounts_list_fully = $this->app_lib->getSelectByBranch('accounts', $basic['branch_id']); // Use different var name
                                    echo form_dropdown("account_id", $accounts_list_fully, $links_fully['deposit'], "class='form-control' id='account_id_fully' required data-plugin-selectTwo data-width='100%'"); // Different ID if needed
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
                        <input type="hidden" name="invoice_id" value="<?php echo htmlspecialchars($basic['id']); // This is student_id in other forms, ensure it's correct for fee_fully_paid context ?>">
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
                    <button type="submit" class="btn btn-default" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing"><?=translate('fee_payment') // Changed "Fee Payment" ?></button>
                </div>
            </div>
        </footer>
        <?php echo form_close();?>
    </section>
</div>

<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>"; // Ensure base_url is correctly defined in your JS scope
    var branchID = "<?php echo htmlspecialchars($basic['branch_id']); ?>";
    var studentID = "<?php echo htmlspecialchars($basic['id']); ?>";

    // Ensure jQuery is loaded before this script
    $(document).ready(function() {
        $(".fee-selectAll").on("change", function(ev) {
            var $chcks = $(this).closest("table").find("tbody input[type='checkbox']"); // Changed parent traversal
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
                var feeAmount = $(this).val(); // This is the original amount, not necessarily the balance
                var item = {}; // Use 'item' instead of 'array' to avoid conflict with Array constructor
                item ["feeAmount"] = feeAmount;
                item ["allocationID"] = allocationID;
                item ["feeTypeID"] = feeTypeID;
                arrayData.push(item);
            });

            if (arrayData.length === 0) {
                // Replace alert with a more user-friendly notification if possible (e.g., Bootstrap modal, toastr)
                // For now, using the existing alert as per original code structure, though not ideal.
                alert("<?=translate('no_rows_selected')?>"); 
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
                    // async: false, // Avoid async: false if possible, it locks the browser
                    cache: false,
                    success: function (response) {
                        $("#feeCollect").html(response);
                    },
                    error: function(jqXHR, textStatus, errorThrown) { // Added error handler
                        console.error("AJAX Error for selectedFeesCollect: ", textStatus, errorThrown);
                        alert("<?=translate('ajax_request_failed')?>: " + errorThrown);
                        $btn.button('reset');
                    },
                    complete: function (jqXHR, textStatus) { // Check status in complete
                        if (textStatus !== 'error') { // Only proceed if no error
                            // Reinitialize plugins if they are dynamically loaded into the modal
                            if (typeof $.fn.themePluginSelect2 === 'function') {
                                $("#modal .selectTwo").each(function() { $(this).themePluginSelect2({}); });
                            }
                            if (typeof $.fn.themePluginDatePicker === 'function') {
                                $("#modal .datepicker").each(function() { 
                                    $(this).themePluginDatePicker({ "todayHighlight" : true, "endDate" : "today" }); 
                                });
                            }
                            // Ensure mfp_modal function is available and correctly initializes Magnific Popup
                            if (typeof mfp_modal === 'function') {
                                mfp_modal('#modal');
                            } else if (typeof $.magnificPopup !== 'undefined') {
                                 $.magnificPopup.open({ items: { src: '#modal' }, type: 'inline' });
                            }
                        }
                        // $btn.button('reset'); // Reset is handled in error or after success logic
                    }
                });
            }
        });

        $('#invoicePrint').on('click', function(e) {
            var $btn = $(this);
            $btn.button('loading');
            var arrayData = [];
            var hasCheckedItems = false;
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
                alert("<?=translate('no_rows_selected_to_print')?>");
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
                        // Attempt to print after content is loaded
                        if (typeof fn_printElem === 'function') {
                            fn_printElem('invoice_print'); 
                        } else {
                            window.print(); 
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) { // Added error handler
                        console.error("AJAX Error for printFeesInvoice: ", textStatus, errorThrown);
                        alert("<?=translate('ajax_request_failed_for_print')?>: " + errorThrown);
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
                alert("<?=translate('no_payment_history_selected_to_print')?>");
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
                        // Attempt to print after content is loaded
                        if (typeof fn_printElem === 'function') {
                            fn_printElem('payment_print');
                        } else {
                            window.print();
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) { // Added error handler
                        console.error("AJAX Error for printFeesPaymentHistory: ", textStatus, errorThrown);
                        alert("<?=translate('ajax_request_failed_for_print')?>: " + errorThrown);
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
                alert("<?=translate('no_payment_selected_to_revert')?>");
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
                confirmButtonClass: "btn btn-default swal2-btn-default", 
                cancelButtonClass: "btn btn-default swal2-btn-default",
                confirmButtonText: "<?php echo translate('yes_revert_it')?>",
                cancelButtonText: "<?php echo translate('cancel')?>",
                buttonsStyling: false, 
            }).then((result) => {
                if (result.value || (result.isConfirmed)) { 
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
                                if (result.value || result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        },
                        error: function(xhr, status, error) { 
                            swal("<?php echo translate('error'); ?>", "<?php echo translate('ajax_request_failed'); ?>: " + error, "error");
                            $this.button('reset');
                        },
                        complete: function () {
                            // $this.button('reset'); // Reset is handled by success reload or error
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
                    alert("<?=translate('ajax_request_failed_balance_type')?>");
                }
            });
        });
    });
</script>
