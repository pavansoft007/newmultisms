<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h4 class="panel-title">
                    <i class="fas fa-plus-circle"></i> <?=translate('add_service_provider')?>
                </h4>
            </header>
            <?php echo form_open('api_settings/create', array('class' => 'form-horizontal form-bordered validate', 'id' => 'addProviderForm')); ?>
                <div class="panel-body">
                    <!-- Service Type -->
                    <div class="form-group mt-md">
                        <label class="col-md-3 control-label" for="service_type"><?=translate('service_type')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="service_type" id="service_type" value="<?=set_value('service_type')?>" required placeholder="e.g., whatsapp, sms, email"/>
                            <span class="error"><?php echo form_error('service_type'); ?></span>
                        </div>
                    </div>

                    <!-- Provider Name -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="provider_name"><?=translate('provider_name')?> <span class="required">*</span></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="provider_name" id="provider_name" value="<?=set_value('provider_name')?>" required placeholder="e.g., Aisensy, Twilio, SendGrid"/>
                            <span class="error"><?php echo form_error('provider_name'); ?></span>
                        </div>
                    </div>

                    <!-- API Key -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="api_key"><?=translate('api_key')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="api_key" id="api_key" value="<?=set_value('api_key')?>"/>
                            <span class="error"><?php echo form_error('api_key'); ?></span>
                        </div>
                    </div>

                    <!-- API Secret -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="api_secret"><?=translate('api_secret')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="api_secret" id="api_secret" value="<?=set_value('api_secret')?>"/>
                            <span class="error"><?php echo form_error('api_secret'); ?></span>
                        </div>
                    </div>

                    <!-- API URL -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="api_url"><?=translate('api_url')?></label>
                        <div class="col-md-6">
                            <input type="url" class="form-control" name="api_url" id="api_url" value="<?=set_value('api_url')?>" placeholder="https://api.example.com/"/>
                            <span class="error"><?php echo form_error('api_url'); ?></span>
                        </div>
                    </div>

                    <!-- Username -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="username"><?=translate('username')?></label>
                        <div class="col-md-6">
                            <input type="text" class="form-control" name="username" id="username" value="<?=set_value('username')?>"/>
                            <span class="error"><?php echo form_error('username'); ?></span>
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="password"><?=translate('password')?></label>
                        <div class="col-md-6">
                            <input type="password" class="form-control" name="password" id="password" value=""/>
                            <span class="error"><?php echo form_error('password'); ?></span>
                        </div>
                    </div>

                    <!-- Other Config JSON -->
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="other_config_json"><?=translate('other_config_json')?></label>
                        <div class="col-md-6">
                            <textarea class="form-control" name="other_config_json" id="other_config_json" rows="3" placeholder='<?=translate('enter_valid_json_or_leave_empty')?>'><?=set_value('other_config_json')?></textarea>
                            <span class="error"><?php echo form_error('other_config_json'); ?></span>
                        </div>
                    </div>

                    <!-- Is Active -->
                    <div class="form-group">
                        <label class="col-md-3 control-label"><?=translate('status')?></label>
                        <div class="col-md-6">
                            <div class="checkbox-custom checkbox-primary">
                                <input type="checkbox" name="is_active" id="is_active" value="1" <?=set_checkbox('is_active', '1', TRUE)?>>
                                <label for="is_active"><?=translate('active')?></label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="panel-footer">
                    <div class="row">
                        <div class="col-md-offset-3 col-md-3">
                            <button type="submit" class="btn btn-default btn-block" data-loading-text="<i class='fas fa-spinner fa-spin'></i> Processing">
                                <i class="fas fa-plus-circle"></i> <?=translate('save_provider')?>
                            </button>
                        </div>
                         <div class="col-md-3">
                            <a href="<?=base_url('api_settings/index')?>" class="btn btn-default btn-block">
                                <i class="fas fa-arrow-left"></i> <?=translate('cancel')?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php echo form_close(); ?>
        </section>
    </div>
</div>