<!-- Material Design 3 Bottom Navigation -->
<div class="md3-bottom-nav">
    <a href="<?php echo base_url('dashboard'); ?>" class="md3-bottom-nav-item<?php echo $this->uri->segment(1) == 'dashboard' ? ' active' : ''; ?>">
        <i class="fas fa-tachometer-alt md3-bottom-nav-icon"></i>
        <span class="md3-bottom-nav-label"><?php echo translate('dashboard'); ?></span>
    </a>
    <a href="<?php echo base_url('mainmenu'); ?>" class="md3-bottom-nav-item<?php echo $this->uri->segment(1) == 'mainmenu' ? ' active' : ''; ?>">
        <i class="fas fa-th-large md3-bottom-nav-icon"></i>
        <span class="md3-bottom-nav-label"><?php echo translate('menu'); ?></span>
    </a>
    <?php if (is_student_loggedin() || (is_parent_loggedin() && !empty(get_activeChildren_id()))): ?>
    <a href="<?php echo base_url('userrole/attendance'); ?>" class="md3-bottom-nav-item<?php echo $this->uri->segment(1) == 'attendance' ? ' active' : ''; ?>">
        <i class="fas fa-check-double md3-bottom-nav-icon"></i>
        <span class="md3-bottom-nav-label"><?php echo translate('attendance'); ?></span>
    </a>
    <a href="<?php echo base_url('userrole/invoice'); ?>" class="md3-bottom-nav-item<?php echo $this->uri->segment(1) == 'invoice' ? ' active' : ''; ?>">
        <i class="fas fa-money-bill-wave md3-bottom-nav-icon"></i>
        <span class="md3-bottom-nav-label"><?php echo translate('fees'); ?></span>
    </a>
    <?php else: ?>
    <a href="<?php echo base_url('student'); ?>" class="md3-bottom-nav-item<?php echo $this->uri->segment(1) == 'student' ? ' active' : ''; ?>">
        <i class="fas fa-user-graduate md3-bottom-nav-icon"></i>
        <span class="md3-bottom-nav-label"><?php echo translate('student'); ?></span>
    </a>
    <a href="<?php echo base_url('profile'); ?>" class="md3-bottom-nav-item<?php echo $this->uri->segment(2) == 'profile' ? ' active' : ''; ?>">
        <i class="fas fa-user-cog md3-bottom-nav-icon"></i>
        <span class="md3-bottom-nav-label"><?php echo translate('profile'); ?></span>
    </a>
    <?php endif; ?>
</div> 