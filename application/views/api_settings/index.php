<div class="row">
    <div class="col-md-12">
        <!-- Service Providers List -->
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title">
                    <i class="fas fa-cogs"></i> <?=translate('service_provider_settings')?>
                </h4>
                <div class="panel-actions">
                    <a href="<?=base_url('api_settings/add')?>" class="btn btn-default btn-sm">
                        <i class="fas fa-plus-circle"></i> <?=translate('add_new_provider')?>
                    </a>
                </div>
            </header>
            <div class="panel-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-condensed mb-none">
                        <thead>
                            <tr>
                                <th><?=translate('service_type')?></th>
                                <th><?=translate('provider_name')?></th>
                                <th><?=translate('status')?></th>
                                <th><?=translate('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($providers)): ?>
                                <?php foreach ($providers as $provider): ?>
                                <tr>
                                    <td><?=htmlspecialchars(ucfirst($provider->service_type))?></td>
                                    <td><?=htmlspecialchars($provider->provider_name)?></td>
                                    <td>
                                        <?php if ($provider->is_active): ?>
                                            <span class="label label-success"><?=translate('active')?></span>
                                        <?php else: ?>
                                            <span class="label label-danger"><?=translate('inactive')?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="min-w-c">
                                        <a href="<?=base_url('api_settings/edit/' . $provider->id)?>" class="btn btn-circle btn-default icon" data-toggle="tooltip" data-original-title="<?=translate('edit')?>">
                                            <i class="fas fa-pen-nib"></i>
                                        </a>
                                        <a href="<?=base_url('api_settings/delete/' . $provider->id)?>" onclick="return confirm('<?=translate('are_you_sure_to_delete_this_provider')?>');" class="btn btn-circle btn-danger icon" data-toggle="tooltip" data-original-title="<?=translate('delete')?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center"><?=translate('no_providers_found')?></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Global API Key Settings (Optional - if still needed) -->
        <section class="panel mt-lg">
            <header class="panel-heading">
                <h4 class="panel-title">
                    <i class="fas fa-key"></i> <?=translate('global_api_key_settings')?>
                </h4>
            </header>
            <?php echo form_open('api_settings/save_global_api_key', array('class' => 'form-horizontal form-bordered validate')); ?>
                <div class="panel-body">
                    <div class="form-group mt-md">
                        <label class="col-md-3 control-label" for="global_api_key_field"><?=translate('global_api_key')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="global_api_key_field" id="global_api_key_field" value="<?php echo set_value('global_api_key_field', isset($global_api_key) ? $global_api_key : ''); ?>" required />
                            <span class="error"><?php echo form_error('global_api_key_field'); ?></span>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-2">
                            <button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                                <i class="fas fa-save"></i> <?=translate('save')?>
                            </button>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </section>
    </div>
</div>