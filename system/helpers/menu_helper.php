<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Get all menu items for the main menu
 * 
 * @param int $role_id User role ID
 * @return array Array of menu items
 */
function get_main_menu_items($role_id = 0) {
    $CI =& get_instance();
    $CI->load->database();
    
    $menu_items = array();
    
    // Basic menu items that everyone can access
    $menu_items[] = array(
        'name' => 'dashboard',
        'url' => 'dashboard',
        'icon' => 'icons icon-grid',
        'desc' => 'View school performance dashboard'
    );
    
    // Check if user is superadmin or master
    $is_superadmin = is_superadmin_loggedin();
    $is_admin = is_admin_loggedin();
    
    // Add school management for superadmin
    if ($is_superadmin) {
        $menu_items[] = array(
            'name' => 'school',
            'url' => 'school',
            'icon' => 'fas fa-school',
            'desc' => 'Manage school settings and configuration'
        );
        
        $menu_items[] = array(
            'name' => 'branch',
            'url' => 'branch',
            'icon' => 'icons icon-directions',
            'desc' => 'Manage school branches and locations'
        );
    }
    
    // Student related menus
    if (get_permission('student', 'is_view')) {
        $menu_items[] = array(
            'name' => 'student',
            'url' => 'student/view',
            'icon' => 'icon-graduation icons',
            'desc' => 'Manage student profiles and records'
        );
    }
    
    // Admission related menus
    if (get_permission('student', 'is_add')) {
        $menu_items[] = array(
            'name' => 'admission',
            'url' => 'student/add',
            'icon' => 'far fa-edit',
            'desc' => 'Create new student admissions'
        );
    }
    
    // Parents related menus
    if (get_permission('parent', 'is_view')) {
        $menu_items[] = array(
            'name' => 'parents',
            'url' => 'parents/view',
            'icon' => 'icons icon-user-follow',
            'desc' => 'Manage parent profiles and accounts'
        );
    }
    
    // Employee related menus
    if (get_permission('employee', 'is_view')) {
        $menu_items[] = array(
            'name' => 'employee',
            'url' => 'employee/view',
            'icon' => 'fas fa-users',
            'desc' => 'Manage staff and employee records'
        );
    }
    
    // Class related menus
    if (get_permission('class', 'is_view')) {
        $menu_items[] = array(
            'name' => 'class',
            'url' => 'classes',
            'icon' => 'fas fa-chalkboard',
            'desc' => 'Manage school classes and sections'
        );
    }
    
    // Subject related menus
    if (get_permission('subject', 'is_view')) {
        $menu_items[] = array(
            'name' => 'subject',
            'url' => 'subject',
            'icon' => 'fas fa-book',
            'desc' => 'Manage subjects and curriculum'
        );
    }
    
    // Class Assignment
    if (get_permission('class_assign', 'is_view')) {
        $menu_items[] = array(
            'name' => 'assign_class_teacher',
            'url' => 'classes/teacher_assign',
            'icon' => 'fas fa-chalkboard-teacher',
            'desc' => 'Assign teachers to classes'
        );
    }
    
    // Subject Assignment
    if (get_permission('subject_assign', 'is_view')) {
        $menu_items[] = array(
            'name' => 'subject_assign',
            'url' => 'subject/assign',
            'icon' => 'fas fa-book-reader',
            'desc' => 'Assign subjects to classes'
        );
    }
    
    // Timetable
    if (get_permission('timetable', 'is_view')) {
        $menu_items[] = array(
            'name' => 'class_schedule',
            'url' => 'timetable',
            'icon' => 'fas fa-clock',
            'desc' => 'Manage class schedules and timetables'
        );
    }
    
    // Attendance
    if (get_permission('student_attendance', 'is_view') || get_permission('employee_attendance', 'is_view')) {
        $menu_items[] = array(
            'name' => 'attendance',
            'url' => 'attendance',
            'icon' => 'fas fa-check-double',
            'desc' => 'Manage student and staff attendance'
        );
    }
    
    // Exam
    if (get_permission('exam', 'is_view')) {
        $menu_items[] = array(
            'name' => 'exam',
            'url' => 'exam',
            'icon' => 'fas fa-diagnoses',
            'desc' => 'Manage exams and assessments'
        );
    }
    
    // Marks
    if (get_permission('marks', 'is_view')) {
        $menu_items[] = array(
            'name' => 'marks',
            'url' => 'exam/marks_register',
            'icon' => 'fas fa-poll',
            'desc' => 'Record and manage student marks'
        );
    }
    
    // Promotion
    if (get_permission('student_promotion', 'is_view')) {
        $menu_items[] = array(
            'name' => 'promotion',
            'url' => 'student/transfer',
            'icon' => 'fas fa-arrow-circle-up',
            'desc' => 'Manage student class promotions'
        );
    }
    
    // Hostel
    if (get_permission('hostel', 'is_view')) {
        $menu_items[] = array(
            'name' => 'hostel',
            'url' => 'hostel',
            'icon' => 'fas fa-hotel',
            'desc' => 'Manage student hostels and rooms'
        );
    }
    
    // Transport
    if (get_permission('transport', 'is_view')) {
        $menu_items[] = array(
            'name' => 'transport',
            'url' => 'transport',
            'icon' => 'fas fa-bus',
            'desc' => 'Manage school transportation'
        );
    }
    
    // Library
    if (get_permission('book', 'is_view')) {
        $menu_items[] = array(
            'name' => 'library',
            'url' => 'library',
            'icon' => 'fas fa-book-reader',
            'desc' => 'Manage library books and resources'
        );
    }
    
    // Fees
    if (get_permission('fees_type', 'is_view') || get_permission('fees_group', 'is_view') || get_permission('fees_invoice', 'is_view')) {
        $menu_items[] = array(
            'name' => 'fees',
            'url' => 'fees/invoice_list',
            'icon' => 'fas fa-money-bill-wave',
            'desc' => 'Manage student fees and payments'
        );
    }
    
    // Accounting
    if (get_permission('account', 'is_view') || get_permission('voucher', 'is_view')) {
        $menu_items[] = array(
            'name' => 'accounting',
            'url' => 'accounting',
            'icon' => 'fas fa-calculator',
            'desc' => 'Financial accounting and reports'
        );
    }
    
    // Office Accounting
    if (get_permission('office_accounting', 'is_view')) {
        $menu_items[] = array(
            'name' => 'office_accounting',
            'url' => 'office_accounting',
            'icon' => 'fas fa-money-check-alt',
            'desc' => 'Manage office accounts and finances'
        );
    }
    
    // Events
    if (get_permission('event', 'is_view')) {
        $menu_items[] = array(
            'name' => 'event',
            'url' => 'event',
            'icon' => 'fas fa-calendar-alt',
            'desc' => 'Manage school events and activities'
        );
    }
    
    // Bulk SMS/Email
    if (get_permission('sendsmsmail', 'is_view')) {
        $menu_items[] = array(
            'name' => 'bulk_sms_and_email',
            'url' => 'sendsmsmail',
            'icon' => 'fas fa-envelope',
            'desc' => 'Send SMS and email notifications'
        );
    }
    
    // Message
    $menu_items[] = array(
        'name' => 'message',
        'url' => 'communication/mailbox/inbox',
        'icon' => 'fas fa-comments',
        'desc' => 'School-wide communication tools'
    );
    
    // Certificate
    if (get_permission('certificate', 'is_view')) {
        $menu_items[] = array(
            'name' => 'certificate',
            'url' => 'certificate',
            'icon' => 'fas fa-certificate',
            'desc' => 'Generate and manage certificates'
        );
    }
    
    // Card Management
    if (get_permission('id_card_templete', 'is_view') || get_permission('admit_card_templete', 'is_view')) {
        $menu_items[] = array(
            'name' => 'card_management',
            'url' => 'card_manage',
            'icon' => 'fas fa-id-card',
            'desc' => 'Manage ID cards and admit cards'
        );
    }
    
    // Human Resource
    if (get_permission('salary_template', 'is_view') || get_permission('salary_payment', 'is_view') || get_permission('salary_assign', 'is_view')) {
        $menu_items[] = array(
            'name' => 'payroll',
            'url' => 'payroll',
            'icon' => 'fas fa-money-check',
            'desc' => 'Manage staff payroll and salaries'
        );
    }
    
    // Leave Management
    if (get_permission('leave_category', 'is_view') || get_permission('leave_manage', 'is_view') || get_permission('leave_request', 'is_view')) {
        $menu_items[] = array(
            'name' => 'leave',
            'url' => 'leave',
            'icon' => 'fas fa-sign-out-alt',
            'desc' => 'Manage staff and student leaves'
        );
    }
    
    // Award
    if (get_permission('award', 'is_view')) {
        $menu_items[] = array(
            'name' => 'award',
            'url' => 'award',
            'icon' => 'fas fa-trophy',
            'desc' => 'Manage awards and recognitions'
        );
    }
    
    // Settings
    if ($is_admin || $is_superadmin) {
        $menu_items[] = array(
            'name' => 'settings',
            'url' => 'settings',
            'icon' => 'fas fa-cogs',
            'desc' => 'System settings and configuration'
        );
    }
    
    // Reports
    if (get_permission('student_attendance_report', 'is_view') || 
        get_permission('employee_attendance_report', 'is_view') || 
        get_permission('exam_attendance_report', 'is_view') || 
        get_permission('accounting_reports', 'is_view') || 
        get_permission('report_card', 'is_view') || 
        get_permission('tabulation_sheet', 'is_view')) {
        $menu_items[] = array(
            'name' => 'reports',
            'url' => 'fees/student_fees_report',
            'icon' => 'fas fa-chart-bar',
            'desc' => 'Generate and view reports'
        );
    }
    
    // Parent role specific menus
    if ($role_id == 6) {
        $menu_items[] = array('name' => 'teachers', 'url' => 'userrole/teachers', 'icon' => 'fas fa-chalkboard-teacher', 'desc' => 'View teacher information');
        $menu_items[] = array('name' => 'subject', 'url' => 'userrole/subject', 'icon' => 'fas fa-school', 'desc' => 'Subjects and classes');
        $menu_items[] = array('name' => 'leave_application', 'url' => 'userrole/leave_request', 'icon' => 'fas fa-file-alt', 'desc' => 'Submit leave applications');
        $menu_items[] = array('name' => 'attachments_book', 'url' => 'userrole/attachments', 'icon' => 'fas fa-book', 'desc' => 'View attachment books');
        $menu_items[] = array('name' => 'homework', 'url' => 'userrole/homework', 'icon' => 'fas fa-tasks', 'desc' => 'View homework assignments');
        $menu_items[] = array('name' => 'exam_master', 'url' => 'userrole/exam_schedule', 'icon' => 'fas fa-clipboard-list', 'desc' => 'View exam details');
        $menu_items[] = array('name' => 'supervision', 'url' => 'supervision', 'icon' => 'fas fa-user-shield', 'desc' => 'View supervision details');
        $menu_items[] = array('name' => 'attendance', 'url' => 'userrole/attendance', 'icon' => 'fas fa-calendar-check', 'desc' => 'View attendance records');
        $menu_items[] = array('name' => 'library', 'url' => 'userrole/book', 'icon' => 'fas fa-book-reader', 'desc' => 'View library resources');
        $menu_items[] = array('name' => 'events', 'url' => 'userrole/event', 'icon' => 'fas fa-calendar-alt', 'desc' => 'View school events');
        $menu_items[] = array('name' => 'fees_history', 'url' => 'userrole/invoice', 'icon' => 'fas fa-money-bill-wave', 'desc' => 'View fees history');
        $menu_items[] = array('name' => 'message', 'url' => 'communication/mailbox/inbox', 'icon' => 'fas fa-envelope', 'desc' => 'View messages');
    }
    
    return $menu_items;
}