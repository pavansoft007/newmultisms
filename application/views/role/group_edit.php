<?php /* Edit Role Group View */ ?>
<div class="panel">
    <div class="panel-heading"><h4 class="panel-title">Edit Role Group</h4></div>
    <div class="panel-body">
        <?php echo form_open('', array('class' => 'form-horizontal')); ?>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="name" class="form-control" value="<?=html_escape($group['name'])?>" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control"><?=html_escape($group['description'])?></textarea>
            </div>
            <div class="form-group">
                <label>Assign Roles</label>
                <select name="role_ids[]" class="form-control" multiple>
                    <?php foreach($roles as $role): ?>
                        <option value="<?=$role['id']?>" <?=in_array($role['id'],$assigned_roles)?'selected':''?>><?=html_escape($role['name'])?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Update</button>
            <a href="<?=base_url('role/groups')?>" class="btn btn-default">Cancel</a>
        <?php echo form_close(); ?>
    </div>
</div>
