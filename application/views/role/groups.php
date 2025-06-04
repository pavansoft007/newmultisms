<?php /* Role Groups List View */ ?>
<div class="panel">
    <div class="panel-heading">
        <h4 class="panel-title">Role Groups <a href="<?=base_url('role/group_add')?>" class="btn btn-primary btn-xs pull-right">Add Group</a></h4>
    </div>
    <div class="panel-body">
        <table class="table table-bordered">
            <thead>
                <tr><th>Name</th><th>Description</th><th>Roles</th><th>Actions</th></tr>
            </thead>
            <tbody>
            <?php foreach($role_groups as $group): ?>
                <tr>
                    <td><?=html_escape($group['name'])?></td>
                    <td><?=html_escape($group['description'])?></td>
                    <td>
                        <?php $roles = $this->role_model->getRolesByGroup($group['id']);
                        foreach($roles as $role) echo '<span class="label label-info">'.html_escape($role['name']).'</span> '; ?>
                    </td>
                    <td>
                        <a href="<?=base_url('role/group_edit/'.$group['id'])?>" class="btn btn-xs btn-warning">Edit</a>
                        <a href="<?=base_url('role/group_delete/'.$group['id'])?>" class="btn btn-xs btn-danger" onclick="return confirm('Delete this group?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
