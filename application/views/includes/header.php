<!DOCTYPE html>
<html>
<head>
  <style type="text/css">

    .option-input {
      -webkit-appearance: none;
      -moz-appearance: none;
      -ms-appearance: none;
      -o-appearance: none;
      appearance: none;
      position: relative;
      top: 0;
      right: 0;
      bottom: 0;
      left: 10px;
      height: 20px;
      width: 20px;
      transition: all 0.15s ease-out 0s;
      background: #cbd1d8;
      border: none;
      color: #fff;
      cursor: pointer;
      display: inline-block;
      margin-right: 0.5rem;
      outline: none;
      position: relative;
      z-index: 1000;
    }
    .option-input:hover {
      background: #9faab7;
    }
    .option-input:checked {
      background: #40e0d0;
    }
    .option-input:checked::before {
      height: 20px;
      width: 20px;
      position: absolute;
      content: '✔';
      display: inline-block;
      font-size: 20.66667px;
      text-align: center;
      line-height: 20px;
    }

    .overfulow{
      overflow-x: inherit;
    }
    
    .dataTables_filter{
      float: right;
    }

    .dataTables_empty{
      text-align: center;
    }

    h1{
      margin-top: 0px !important;
    }

    .pagination{
      margin-top: 0 !important;
      float: right;
    }

    .sidebar{
      background-color: #222122;
      position: fixed;
      left: 0;
      min-width: 15%;
      height: 100%;
      color: white;
      font-family: 'Source Sans Pro','Helvetica Neue',Helvetica,Arial,sans-serif;
    }

    .panel-body{
      /*margin-left: 50px;*/
    }

    .col-md-10{
      padding-left: 0px !important;
    }

    .col-md-offset-2{
    }

    .jasrich{
      margin-top: 5px;
      margin-left: 5px;
      text-align: center;
      color: orange;
    }

    .bars{
      margin-top: 10%;
      font-size: 15px;
    }
  
    .bar-item{
      /*height: 32px;*/
    }

    .bar-item:hover{
      background-color: #302d30;
    }

    .bar-item a{
      color: white;
    }

    .bar-item a:hover{
      color: white;
      text-decoration: none;
    }

    .bar-item a div{
      margin-left: 10px;
      padding-top: 5px;
      padding-bottom: 6px;
      color: #e4dfdf;
    }

    .bar-item a div i{
      float: right;
      margin-right: 10px;
    }
  </style>

  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/style.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/bootstrap.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/select2.min.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/flatpickr.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/bootstrap-datepicker.min.css">
  <link rel="stylesheet" type="text/css" href="<?= base_url() ?>css/font-awesome.min.css">
<!--   <link rel="stylesheet" type="text/css" href="../font-awesome/css/font-awesome.css">
  <link rel="stylesheet" type="text/css" href="../css/datables.css"> -->
  <title>Loginpage</title>
</head>
<body>
<!-- <li data-toggle="tooltip" title="TRANSACTION"><a href="<?= base_url() ?>Admin/transaction"><i class="glyphicon glyphicon-usd"></i></a></li> -->

<div class="sidebar">
  <div class="jasrich">
    <h4>JASRICH  MEDICAL</h4>
  </div>
  <div class="bars">
    <?php if($this->session->role == 'admin' || $this->session->role == 'staff'): ?>
    <div class="bar-item">
      <a href="<?= base_url() . ucfirst($this->session->role)?>/home"><div>HOME<i class="glyphicon glyphicon-home"></i></div></a>
    </div>
    <?php endif; ?>
    <div class="bar-item">
      <a href="<?= base_url() . ucfirst($this->session->role)?>/orders"><div>ORDERS<i class="glyphicon glyphicon-shopping-cart"></i></div></a>
    </div>
    <?php if($this->session->role == 'admin' || $this->session->role == 'staff'): ?>
    <div class="bar-item">
        <a href="<?= base_url() . ucfirst($this->session->role)?>/inventory"><div>INVENTORY<i class="glyphicon glyphicon-tasks"></i></div></a>
    </div>
    <div class="bar-item">
        <a href="<?= base_url() ?>Products"><div>STOCK LOGS<i class="glyphicon glyphicon-list-alt"></i></div></a>
    </div>
      <?php if($this->session->role == 'admin'): ?>
    <?php endif; ?>
      <div class="bar-item">
          <a href="<?= base_url() . ucfirst($this->session->role)?>/suppliers"><div>SUPPLIERS<i class="fa fa-users"></i></div></a>
      </div>
      <div class="bar-item">
          <a href="<?= base_url() . ucfirst($this->session->role)?>/users"><div>USERS<i class="glyphicon glyphicon-user"></i></div></a>
      </div>
      <div class="bar-item">
          <a href="<?= base_url() ?>Reports"><div>REPORTS<i class="glyphicon glyphicon-stats"></i></div></a>
      </div>
      <div class="bar-item">
          <a href="<?= base_url() . ucfirst($this->session->role)?>/auditTrail"><div>AUDIT TRAIL<i class="glyphicon glyphicon-duplicate"></i></div></a>
      </div>
    <?php endif; ?>
    <div class="bar-item">
        <a href="<?= base_url() . ucfirst($this->session->role)?>/logout"><div>LOGOUT<i class="glyphicon glyphicon-remove-sign"></i></div></a>
    </div>
  </div>
</div>
<!-- <div id="bars" onclick="toggleSidebar(this);">
  <span></span>
  <span></span>
  <span></span>
</div>
<div id="sidebar" style="z-index: 1;">
  <ul>
    <?php if($this->session->role == 'admin' || $this->session->role == 'staff'): ?>
    <li data-toggle="tooltip" title="HOME"><a href="<?= base_url() . ucfirst($this->session->role)?>/home"><i class="glyphicon glyphicon-home"></i></a></li>
  <?php endif; ?>
    <li data-toggle="tooltip" title="ORDERS"><a href="<?= base_url() . ucfirst($this->session->role) ?>/orders"><i class="glyphicon glyphicon-shopping-cart"></i></a></li>
    <?php if($this->session->role == 'admin' || $this->session->role == 'staff'): ?>
      <li data-toggle="tooltip" title="INVENTORY"><a href="<?= base_url() . ucfirst($this->session->role) ?>/inventory"><i class="glyphicon glyphicon-tasks"></i></a></li>
      <li data-toggle="tooltip" title="PRODUCTS"><a href="<?= base_url() ?>Products"><i class="glyphicon glyphicon-list-alt"></i></a></li>
      <?php if($this->session->role == 'admin'): ?>
        <li data-toggle="tooltip" title="REPORTS"><a href="<?= base_url() ?>Reports"><i class="glyphicon glyphicon-stats"></i></a></li>
        <li data-toggle="tooltip" title="USERS"><a href="<?= base_url() . 'Admin/users' ?>"><i class="glyphicon glyphicon-user"></i></a></li>
        <li data-toggle="tooltip" title="AUDIT TRAIL"><a href="<?= base_url() ?>Admin/auditTrail"><i class="glyphicon glyphicon-duplicate"></i></a></li>
      <?php endif; ?>
    <?php endif; ?>
    <li data-toggle="tooltip" title="LOGOUT"><a href="<?= base_url() . ucfirst($this->session->role) ?>/logout"><i class="glyphicon glyphicon-remove-sign"></i></a></li>
  </ul>
</div> -->

<?php 
  if($this->session->loginStatus)
  {
    $text = $this->session->loginStatus;
    echo "<script type='text/javascript'>alert('$text');</script>";
  }
?>