<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Dashboard</title> -->

    <!-- For local jQuery link, Bootstrap required -->
    <?= $this->Html->script('bootstrap/jquery-3.3.1.min.js') ?>
    <?= $this->Html->script('bootstrap/popper.min.js') ?>

    <!-- For Language Switch -->
    <?= $this->Html->script('jed/jed.js') ?>
    <?php $language = $this->request->getSession()->read('Language');
    if($language == "en_US")
      echo $this->Html->script('language/en_US.js');
    else if($language == "zh_CN")
      echo $this->Html->script('language/zh_CN.js') ?>

    <!-- For local Font Awesome icon link -->
    <?= $this->Html->css('fontAwesome/all.min.css') ?>

    <!-- For local CSS link -->
    <?= $this->Html->css('mainlayout.css') ?>

    <!-- For local JS link -->
    <?= $this->Html->script('mainlayout.js') ?>
    <?= $this->Html->script('sweetalert.js') ?>

    <!-- For local Bootstrap/CSS link -->
    <?= $this->Html->css('bootstrap/bootstrap-grid.min.css') ?>
    <?= $this->Html->css('bootstrap/bootstrap-reboot.min.css') ?>
    <?= $this->Html->css('bootstrap/bootstrap.min.css') ?>

    <!-- For local Bootstrap/JS link -->
    <?= $this->Html->script('bootstrap/bootstrap.bundle.min.js') ?>
    <?= $this->Html->script('bootstrap/jquery-1.12.4.js') ?>
    <?= $this->Html->script('bootstrap/jquery-ui.js') ?>

    <!-- For local DataTable CSS/JS link -->
    <?= $this->Html->css('datatable/dataTables.bootstrap4.min.css') ?>
    <?= $this->Html->script('datatable/DataTables/js/jquery.dataTables.min.js') ?>
    <?= $this->Html->script('datatable/DataTables/js/dataTables.bootstrap4.min.js') ?>

    <!-- For local Select2 (External library for quick selecting) CSS/JS link -->
    <?= $this->Html->css('select2/select2.min.css') ?>
    <?= $this->Html->script('select2/select2.min.js') ?>

    <!-- For local NavBar CSS/JS link -->
    <?= $this->Html->css('navbar.css') ?>
    <?= $this->Html->script('navbar.js') ?>

    <?php // echo $this->element('layout/footer'); ?>
</head>
<body>

<div class="topNav">
    <div class="navInner mx-auto d-flex bd-highlight">
      <a class="navLogo navbar-brand mr-auto my-auto bd-highlight" href="/Dashboards/index">
        <img src="/img/logo-mds.png" title="MDS" alt="logo" style="width:200px;">
      </a>

    <!-- Language Switcher -->
    <div class="btn-group my-auto">
      <button type="button" class="btn dropdown-toggle" title="Language Switcher" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-globe-americas"></i>
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="/sd-users/setLanguage/en_US">English
          <!-- <img class="flag" src="/img/flags/4x3/us.svg" href="/sd-users/setLanguage/en_US" alt="English Version" title="English Version"> -->
        </a>
        <a class="dropdown-item" href="/sd-users/setLanguage/zh_CN">Chinese
          <!-- <img class="flag" src="/img/flags/4x3/cn.svg" href="/sd-users/setLanguage/zh_CN" alt="Chinese Version" title="Chinese Version"> -->
        </a>
      </div>
    </div>

    <!-- E2B Version Switcher -->
    <div class="btn-group my-auto">
      <button type="button" class="btn dropdown-toggle" title="E2B Version Switcher" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <? __("E2B Version")?> E2B
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="/sd-users/setVersion/2"><? __("version")?> E2B R2</a>
        <a class="dropdown-item" href="/sd-users/setVersion/3"><? __("version")?> E2B R3</a>
      </div>
    </div>

    <!-- Mail Notice -->
    <div class="d-flex p-2 mx-2">
      <?php
      $mailNotice = $this->cell('QueryNotice',[$this->request->getSession()->read('Auth.User.id')]);
      echo $mailNotice;
      ?>
    </div>

    <!-- Account Info -->
    <div class="nav-item dropdown p-2 myaccount bg-transparent">
      <a class="nav-link text-dark bg-light" href="/sd-users/myaccount" id="accountInfo" role="button" aria-haspopup="true" aria-expanded="false">
        <h5><?php echo   __("Hi!") ?>&nbsp;&nbsp;<span id="roleName"><?php echo $this->request->getSession()->read('Auth.User.firstname'); ?>&nbsp;<?php print $this->request->getSession()->read('Auth.User.lastname'); ?> </span></h5>
      </a>
      <div class="dropdown-menu login" aria-labelledby="accountInfo">
        <h5 class="dropdown-header"><?php echo $this->request->getSession()->read('Auth.User.role_name'); ?></h5>
      </div>
    </div>

    <!-- Log Out -->
    <div class="d-flex p-2 mx-2">
      <a class="dropdown-item my-1" href="/sd-users/logout"><i class="fas fa-sign-out-alt"></i> <?php echo __("Log Out");?></a>
    </div>

    </div>
</div>

<nav class="navbar navbar-expand-lg mainNav navbar-dark bg-primary">
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="nav navbar-nav mr-auto">
      <li class="nav-item"><a class="nav-link" href="/Dashboards/index"><?php echo __("Dashboard");?><span class="sr-only">(current)</span></a></li>
      <!-- <li class="nav-item"><a class="nav-link" href="#">Link</a></li> -->
      <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"><?php echo __("Product");?> </a>
        <ul class="dropdown-menu">
          <a class="dropdown-item" href="/sd-products/search"><?php echo __("Search Product");?></a>
          <a class="dropdown-item" href="/sd-products/addproduct"><?php echo __("Add Product");?></a>
        </ul>
      </li>
      <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"><?php echo __("Contact");?></a>
        <ul class="dropdown-menu">
          <a class="dropdown-item" href="/sd-contacts/search"><?php echo __("Search Contact");?></a>
          <a class="dropdown-item" href="/sd-contacts/addcontact"><?php echo __("Add Contact");?></a>
        </ul>
      </li>
      <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"><?php echo __("ICSR");?></a>
        <ul class="dropdown-menu">
          <a class="dropdown-item" href="/sd-cases/caseregistration"><?php echo __("Register SAE/AE");?></a>
          <a class="dropdown-item" href="/sd-cases/caselist"><?php echo __("Case List");?></a>
        </ul>
      </li>
      <li class="nav-item"><a class="nav-link" href="/sd-users/myaccount"><?php echo __("My Account");?></a></li>
    </ul>
  </div>
</nav>


<!-- The following codes are required when this layout applied to any other  -->
<?= $this->Flash->render() ?>
<!-- Disable this when applied Bootstrap Framework    <div class="container clearfix"></div> -->
<?= $this->fetch('content') ?>


<!-- jQuery required these for loading datepicker -->
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
</body>
</html>