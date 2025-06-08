<?php
$currency_symbol = $global_config['currency_symbol'];
$extINTL = extension_loaded('intl');
if ($extINTL == true) {
	$spellout = new NumberFormatter("en", NumberFormatter::SPELLOUT);
}
?>
<div class="row">
	<div class="col-lg-5 pull-right">
		<ul class="amounts">
			<li><strong><?=translate('sub_total')?> :</strong> <?=$currency_symbol . number_format($total_paid + $total_discount, 2, '.', ''); ?></li>
			<?php if ($total_discount >= 1): ?>
			<li><strong><?=translate('discount')?> :</strong> <?=$currency_symbol . number_format($total_discount, 2, '.', ''); ?></li>
			<?php endif; ?>
			<li><strong><?=translate('paid')?> :</strong> <?=$currency_symbol . number_format($total_paid, 2, '.', ''); ?></li>
			<?php if ($total_fine >= 1): ?>
			<li><strong><?=translate('fine')?> :</strong> <?=$currency_symbol . number_format($total_fine, 2, '.', ''); ?></li>
			<?php endif; ?>
			<li>
				<strong><?=translate('total_paid')?><?php if ($total_fine >= 1): ?> (<?=translate('with_fine')?>)<?php endif; ?> : </strong> 
				<?php
				$numberSPELL = "";
				$grand_paid = number_format($total_paid + $total_fine, 2, '.', '');
				if ($extINTL == true) {
					$numberSPELL = ' </br>( ' . ucwords($spellout->format($grand_paid)) . ' )';
				}
				echo $currency_symbol . $grand_paid . $numberSPELL;
				?>
			</li>
		</ul>
	</div>
</div>
