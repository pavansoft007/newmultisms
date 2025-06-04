<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $title; ?></h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <p>WhatsApp Templates Management Interface.</p>
                    <!-- Add your template listing and management tools here -->
                    <!-- Example: -->
                    <!--
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Template Name</th>
                                <th>Category</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($templates) && !empty($templates)): ?>
                                <?php foreach ($templates as $template): ?>
                                    <tr>
                                        <td><?php echo html_escape($template->id); ?></td>
                                        <td><?php echo html_escape($template->name); ?></td>
                                        <td><?php echo html_escape($template->category); ?></td>
                                        <td><?php echo html_escape($template->status); ?></td>
                                        <td>
                                            <!-- Action buttons -->
                                            <a href="<?php echo admin_url('whatsapp_sender/template_edit/' . $template->id); ?>" class="btn btn-xs btn-primary">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                            <!-- Add delete button with confirmation -->
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="text-center">No templates found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    -->
                    <p>Content for WhatsApp templates will go here.</p>
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->