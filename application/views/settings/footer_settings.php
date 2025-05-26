<section class="panel appear-animation" data-appear-animation="<?php echo $global_config['animations']; ?>" data-appear-animation-delay="100">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="fas fa-cogs"></i> <?=translate('footer_settings')?>
        </h4>
    </div>
    <div class="panel-body">
        <?php echo form_open($this->uri->uri_string(), array('class' => 'validate form-horizontal form-bordered')); ?>
        <div class="form-group">
            <label class="col-md-3 control-label"><?=translate('user_role')?></label>
            <div class="col-md-6">
                <select name="role_id" class="form-control" id="role_id" data-plugin-selectTwo data-width="100%" required>
                    <option value=""><?=translate('select')?></option>
                    <?php foreach ($roles as $role): ?>
                    <option value="<?=$role['id']?>"><?=$role['name']?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="form-group" id="menu_items_container" style="display: none;">
            <label class="col-md-3 control-label"><?=translate('menu_items')?></label>
            <div class="col-md-6">
                <div class="checkbox-replace" id="menu_items_list">
                    <!-- Menu items will be loaded here via AJAX -->
                </div>
            </div>
        </div>
        
        <footer class="panel-footer mt-lg">
            <div class="row">
                <div class="col-md-2 col-sm-offset-3">
                    <button type="submit" class="btn btn btn-default btn-block" name="submit" value="save">
                        <i class="fas fa-plus-circle"></i> <?=translate('save');?>
                    </button>
                </div>
            </div>
        </footer>
        <?php echo form_close(); ?>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        $('#role_id').on('change', function() {
            var roleId = $(this).val();
            if (roleId) {
                $.ajax({
                    url: "<?=base_url('settings_footer/get_menu_items')?>",
                    type: 'POST',
                    data: {
                        role_id: roleId
                    },
                    dataType: 'json',
                    success: function(response) {
                        var html = '';
                        $.each(response.all_items, function(key, value) {
                            var checked = '';
                            if ($.inArray(key, response.selected_items) !== -1) {
                                checked = 'checked';
                            }
                            html += '<div class="checkbox-replace mt-md">';
                            html += '<label class="i-checks">';
                            html += '<input type="checkbox" name="menu_items[]" value="' + key + '" ' + checked + '>';
                            html += '<i></i> ' + value;
                            html += '</label>';
                            html += '</div>';
                        });
                        $('#menu_items_list').html(html);
                        $('#menu_items_container').show();
                        
                        // Debug info
                        console.log('Selected items:', response.selected_items);
                    },
                    error: function(xhr, status, error) {
                        console.error('AJAX Error:', error);
                        alert('Error loading menu items. Please try again.');
                    }
                });
            } else {
                $('#menu_items_container').hide();
            }
        });
        
        // Form submission validation
        $('form').on('submit', function(e) {
            var roleId = $('#role_id').val();
            var menuItems = $('input[name="menu_items[]"]:checked').length;
            
            if (!roleId) {
                e.preventDefault();
                alert('Please select a user role');
                return false;
            }
            
            if (menuItems === 0) {
                if (!confirm('No menu items selected. This will hide all footer menu items for this role. Continue?')) {
                    e.preventDefault();
                    return false;
                }
            }
            
            // Debug info
            console.log('Submitting form with role ID:', roleId);
            console.log('Selected menu items:', $('input[name="menu_items[]"]:checked').map(function() {
                return this.value;
            }).get());
            
            return true;
        });
    });
</script>