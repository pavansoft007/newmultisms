<section class="panel">
	<div class="tabs-custom">
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#template" data-toggle="tab"><i class="fas fa-list-ul"></i> <?php echo translate('whatsapp') . ' ' . translate('template') . ' ' . translate('list'); ?></a>
			</li>
<?php if (get_permission('sendsmsmail_template', 'is_add')){ ?>
			<li>
				<a href="#create" data-toggle="tab"><i class="far fa-edit"></i> <?php echo translate('create') . ' ' . translate('whatsapp') . ' ' . translate('template'); ?></a>
			</li>
<?php } ?>
		</ul>
		<div class="tab-content">
			<div id="template" class="tab-pane active mb-md">
				<table class="table table-bordered table-hover table-condensed mb-none table_default">
					<thead>
						<tr>
							<th><?=translate('sl')?></th>
						<?php if (is_superadmin_loggedin()): ?>
							<th><?=translate('branch')?></th>
						<?php endif; ?>
							<th><?=translate('name')?></th>
							<th><?=translate('body')?></th>
							<th><?=translate('action')?></th>
						</tr>
					</thead>
					<tbody>
						<?php $count = 1; foreach ($templetelist as $row): ?>	
						<tr>
							<td><?php echo $count++; ?></td>
						<?php if (is_superadmin_loggedin()): ?>
							<td><?php echo $row['branch_name'];?></td>
						<?php endif; ?>
							<td><?php echo $row['name']; ?></td>
							<td><?php echo mb_strimwidth(strip_tags($row['body']), 0, 70, "...."); // This might need adjustment for WhatsApp template structures ?></td>
							<td>
								<?php if (get_permission('sendsmsmail_template', 'is_edit')){ ?>
									<a href="<?php echo base_url('sendsmsmail/template_edit/whatsapp/' . $row['id']); ?>" class="btn btn-circle icon btn-default" data-toggle="tooltip"
									data-original-title="<?php echo translate('edit'); ?>">
										<i class="fas fa-pen-nib"></i>
									</a>
								<?php } ?>
								<?php if (get_permission('sendsmsmail_template', 'is_delete')){ ?>
									<?php echo btn_delete('sendsmsmail/template_delete/' . $row['id']); ?>
								<?php } ?>
							</td>
						</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
			</div>
		<?php if (get_permission('sendsmsmail_template', 'is_add')){ ?>
			<div id="create" class="tab-pane">
				<?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal form-bordered frm-submit')); ?>
				<?php if (is_superadmin_loggedin()): ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('branch');?> <span class="required">*</span></label>
						<div class="col-md-6">
							<?php
								$arrayBranch = $this->app_lib->getSelectList('branch');
								echo form_dropdown("branch_id", $arrayBranch, set_value('branch_id'), "class='form-control'
								data-plugin-selectTwo data-width='100%' data-minimum-results-for-search='Infinity'");
							?>
							<span class="error"></span>
						</div>
					</div>
				<?php endif; ?>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('template') . ' ' . translate('name'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<input type="text" class="form-control" name="template_name" value="" />
							<span class="error"></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-md-3 control-label"><?php echo translate('message') . '/' . translate('template_body'); ?> <span class="required">*</span></label>
						<div class="col-md-6">
							<textarea class="form-control" name="message" id="message" rows="5" ></textarea>
							<span class="error"></span>
							<!-- SMS character counter removed -->
                            <!-- If using Aisensy, this might be a template ID or a more structured input -->
                            <small><em>For Aisensy, ensure this matches an approved template structure if not sending plain text. Variables like {name}, {email}, {mobile_no} can be used.</em></small>
						</div>
					</div>
					<p class="col-md-offset-3 mt-md">
						<strong>Dynamic Tag : </strong>
						<a data-value=" {{1}} " class="btn btn-default btn-xs btn_tag ">{name} / {{1}}</a>
						<a data-value=" {{2}} " class="btn btn-default btn-xs btn_tag ">{email} / {{2}}</a>
						<a data-value=" {{3}} " class="btn btn-default btn-xs btn_tag ">{mobile_no} / {{3}}</a>
                        <br><small><em>Use {{number}} for Aisensy template variables. {name} etc. are for general replacement.</em></small>
					</p>
					<footer class="panel-footer">
						<div class="row">
							<div class="col-md-offset-3 col-md-2">
								<button type="submit" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing" class="btn btn-default btn-block">
									<i class="fas fa-plus-circle"></i> <?=translate('save')?>
								</button>
							</div>
						</div>
					</footer>
				<?php echo form_close(); ?>
			</div>
			<?php } ?>
		</div>
	</div>
</section>

<script type="text/javascript">
	$(document).ready(function () {
		// SMS characters counter removed

		$('.btn_tag').on('click', function() {
			var $txt = $("#message");
	     	var caretPos = $txt[0].selectionStart;
	        var textAreaTxt = $txt.val();
	        var txtToAdd = $(this).data("value");
	        $txt.val(textAreaTxt.substring(0, caretPos) + txtToAdd + textAreaTxt.substring(caretPos) );
		});
	});
</script>