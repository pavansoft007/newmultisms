<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>School Management App UI</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Roboto', sans-serif;
      background-color: #f5f7fa;
    }
    .container {
      padding: 16px;
    }
    .grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 16px;
      margin-top: 20px;
    }
    .card {
      background: linear-gradient(135deg, #ffffff, #f0f0f0);
      border-radius: 16px;
      padding: 24px;
      text-align: center;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
      transition: all 0.3s ease;
      color: #333;
      text-decoration: none;
      display: block;
    }
    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .card-icon {
      font-size: 36px;
      margin-bottom: 10px;
    }
    .students { color: #00796b; }
    .attendance { color: #0288d1; }
    .fees { color: #f57c00; }
    .classes { color: #7b1fa2; }
    .homeworks { color: #c2185b; }
    .marks { color: #512da8; }
    .events { color: #388e3c; }
    .reports { color: #455a64; }

    .bottom-nav {
      position: fixed;
      bottom: 0;
      left: 0;
      width: 100%;
      display: flex;
      background: #ffffff;
      box-shadow: 0 -2px 6px rgba(0, 0, 0, 0.1);
      border-top-left-radius: 12px;
      border-top-right-radius: 12px;
    }
    .bottom-nav a {
      flex: 1;
      padding: 12px 0;
      background: none;
      border: none;
      font-size: 14px;
      color: #757575;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      transition: color 0.3s;
      text-decoration: none;
    }
    .bottom-nav a.active {
      color: #1976d2;
    }
    .bottom-nav a:hover {
      color: #1976d2;
    }
    .material-icons {
      font-size: 24px;
      margin-bottom: 4px;
    }
    
    .header {
      background: #1976d2;
      color: white;
      padding: 16px;
      text-align: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .header h1 {
      margin: 0;
      font-size: 20px;
    }
  </style>
</head>
<body>

<div class="header">
  <h1><?php echo $this->session->userdata('name'); ?></h1>
</div>

<div class="container">
  <div class="grid">
    <a href="<?php echo base_url('student'); ?>" class="card students">
      <span class="material-icons card-icon">school</span>
      <div>Students</div>
    </a>
    <a href="<?php echo base_url('attendance'); ?>" class="card attendance">
      <span class="material-icons card-icon">how_to_reg</span>
      <div>Attendance</div>
    </a>
    <a href="<?php echo base_url('fees'); ?>" class="card fees">
      <span class="material-icons card-icon">attach_money</span>
      <div>Fees</div>
    </a>
    <a href="<?php echo base_url('classes'); ?>" class="card classes">
      <span class="material-icons card-icon">class</span>
      <div>Classes</div>
    </a>
    <a href="<?php echo base_url('homework'); ?>" class="card homeworks">
      <span class="material-icons card-icon">assignment</span>
      <div>HomeWorks</div>
    </a>
    <a href="<?php echo base_url('exam'); ?>" class="card marks">
      <span class="material-icons card-icon">grading</span>
      <div>Marks</div>
    </a>
    <a href="<?php echo base_url('event'); ?>" class="card events">
      <span class="material-icons card-icon">event</span>
      <div>Events</div>
    </a>
    <a href="<?php echo base_url('dashboard/reports'); ?>" class="card reports">
      <span class="material-icons card-icon">analytics</span>
      <div>Reports</div>
    </a>
  </div>
</div>

<div class="bottom-nav">
  <a href="<?php echo base_url('mobilemain'); ?>" class="active">
    <span class="material-icons">dashboard</span>
    <div>Dashboard</div>
  </a>
  <a href="<?php echo base_url('attendance'); ?>">
    <span class="material-icons">how_to_reg</span>
    <div>Attendance</div>
  </a>
  <a href="<?php echo base_url('fees'); ?>">
    <span class="material-icons">attach_money</span>
    <div>Fees</div>
  </a>
  <a href="<?php echo base_url('profile'); ?>">
    <span class="material-icons">menu</span>
    <div>More</div>
  </a>
</div>

<script src="<?php echo base_url('assets/js/mobile-detector.js'); ?>"></script>
</body>
</html>