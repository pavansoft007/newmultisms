<section class="panel">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="fas fa-bug"></i> Footer Menu Debug
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <h5>Database Table Structure</h5>
                <pre>
CREATE TABLE `footer_menu_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `menu_item` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
                </pre>
                
                <h5>Current Footer Menu Configuration</h5>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Role ID</th>
                            <th>Role Name</th>
                            <th>Menu Item</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $CI =& get_instance();
                        $CI->db->select('f.*, r.name as role_name');
                        $CI->db->from('footer_menu_config f');
                        $CI->db->join('roles r', 'r.id = f.role_id', 'left');
                        $CI->db->order_by('f.role_id', 'ASC');
                        $CI->db->order_by('f.menu_item', 'ASC');
                        $query = $CI->db->get();
                        
                        if ($query->num_rows() > 0) {
                            foreach ($query->result() as $row) {
                                echo '<tr>';
                                echo '<td>' . $row->id . '</td>';
                                echo '<td>' . $row->role_id . '</td>';
                                echo '<td>' . $row->role_name . '</td>';
                                echo '<td>' . $row->menu_item . '</td>';
                                echo '<td>' . ($row->status ? 'Active' : 'Inactive') . '</td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="5" class="text-center">No records found</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
                
                <h5>Test Footer Menu Helper Function</h5>
                <div class="row">
                    <div class="col-md-6">
                        <select id="test_role_id" class="form-control">
                            <option value="">Select Role</option>
                            <?php
                            $roles = $CI->db->get('roles')->result();
                            foreach ($roles as $role) {
                                echo '<option value="' . $role->id . '">' . $role->name . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <button id="test_button" class="btn btn-primary">Test</button>
                    </div>
                </div>
                
                <div id="test_result" class="mt-md" style="display: none;">
                    <h6>Result:</h6>
                    <pre id="result_content"></pre>
                </div>
                
                <h5 class="mt-lg">Fix Database Issues</h5>
                <div class="row">
                    <div class="col-md-6">
                        <button id="fix_button" class="btn btn-danger">Fix Database Issues</button>
                    </div>
                </div>
                
                <div id="fix_result" class="mt-md" style="display: none;">
                    <h6>Fix Result:</h6>
                    <pre id="fix_content"></pre>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function() {
        $('#test_button').on('click', function() {
            var roleId = $('#test_role_id').val();
            if (!roleId) {
                alert('Please select a role');
                return;
            }
            
            $.ajax({
                url: "<?=base_url('settings_footer/test_helper')?>",
                type: 'POST',
                data: {
                    role_id: roleId
                },
                dataType: 'json',
                success: function(response) {
                    $('#result_content').html(JSON.stringify(response, null, 2));
                    $('#test_result').show();
                },
                error: function(xhr, status, error) {
                    $('#result_content').html('Error: ' + error);
                    $('#test_result').show();
                }
            });
        });
        
        $('#fix_button').on('click', function() {
            if (!confirm('This will attempt to fix database issues. Continue?')) {
                return;
            }
            
            $.ajax({
                url: "<?=base_url('settings_footer/fix_database')?>",
                type: 'POST',
                dataType: 'json',
                success: function(response) {
                    $('#fix_content').html(JSON.stringify(response, null, 2));
                    $('#fix_result').show();
                    
                    if (response.success) {
                        setTimeout(function() {
                            location.reload();
                        }, 3000);
                    }
                },
                error: function(xhr, status, error) {
                    $('#fix_content').html('Error: ' + error);
                    $('#fix_result').show();
                }
            });
        });
    });
</script>