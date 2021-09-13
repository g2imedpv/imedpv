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
    <?= $this->Html->css('mainlayout.css',['type' => 'text/css']) ?>

    <!-- For local JS link -->
    <?= $this->Html->script('mainlayout.js') ?>
    <?= $this->Html->script('sweetalert.js') ?>
    <?= $this->Html->script('animation/loadingAnimation.js') ?>

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
      <a class="navLogo navbar-brand mr-auto my-auto bd-highlight loadingAni" href="/Dashboards/index">
        <img src="/img/logo-mds.png" title="MDS" alt="logo" style="width:160px;">
      </a>

    <!-- Language Switcher -->
    <div class="btn-group mx-3 my-auto dropdown">
    <?php
      $language = $this->request->getSession()->read('Language');
      if ($language === 'en_US') {
        echo '<button type="button" class="btn dropdown-toggle" title="Language Switcher" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <img src="/img/flags/4x3/us.svg" alt="us_flag" class="flag" >
              </button>';
      } else {
        echo '<button type="button" class="btn dropdown-toggle" title="Language Switcher" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="/img/flags/4x3/cn.svg" alt="cn_flag" class="flag" >
        </button>' ;
      }
    ?>
      <div class="dropdown-menu">
        <a class="dropdown-item loadingAni" class='flag cn_flag' href="/sd-users/setLanguage/en_US">
          <img src="/img/flags/4x3/us.svg" alt="us_flag" class='flag' > <span>English</span>
        </a>
        <a class="dropdown-item loadingAni" href="/sd-users/setLanguage/zh_CN">
          <img src="/img/flags/4x3/cn.svg" alt="cn_flag" class='flag' > <span>Chinese</span>
        </a>
      </div>
    </div>

    <!-- E2B Version Switcher -->
    <!-- <div class="btn-group my-auto">
      <button type="button" class="btn dropdown-toggle" title="E2B Version Switcher" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <? __("E2B Version")?> E2B
      </button>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="/sd-users/setVersion/2"><? __("version")?> E2B R2</a>
        <a class="dropdown-item" href="/sd-users/setVersion/3"><? __("version")?> E2B R3</a>
      </div>
    </div> -->

    <!-- Mail Notice -->
    <div class="btn-group mx-3 my-auto">
      <?php
      $mailNotice = $this->cell('QueryNotice',[$this->request->getSession()->read('Auth.User.id')]);
      echo $mailNotice;
      ?>
    </div>

    <!-- Account Info -->
    <div class="btn-group mx-3 my-auto dropdown">
      <button type="button" class="btn" title="Account Info" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <h5>
          <?php echo   __("Hi!") ?>&nbsp;&nbsp;
          <span id="roleName"><?php echo $this->request->getSession()->read('Auth.User.firstname'); ?>&nbsp;<?php print $this->request->getSession()->read('Auth.User.lastname'); ?> </span>
        </h5>
      </button>
      <div class="dropdown-menu">
        <h6 class="dropdown-header"><?php echo   __("Role: ") ?><b><?php echo $this->request->getSession()->read('Auth.User.role_name'); ?></b></h6>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item loadingAni" href="/sd-users/myaccount"><i class="far fa-user-circle accountIcon"></i><?php echo __(" My Account");?></a>
        <a class="dropdown-item loadingAni" href="/sd-users/logout"><i class="fas fa-sign-out-alt"></i><?php echo __(" Log Out");?></a>
      </div>
    </div>
    </div>
</div>

<nav class="navbar navbar-expand-lg mainNav">
  <div class="container-fluid">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="nav navbar-nav container d-flex justify-content-between">
        <li class="nav-item">
          <a class="nav-link loadingAni" href="/Dashboards/index">
            <?php echo __("Dashboard");?>
            <span class="sr-only">(current)</span>
          </a>
        </li>
        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"><?php echo __("Product");?> </a>
          <ul class="dropdown-menu">
            <a class="dropdown-item loadingAni" href="/sd-products/search"><?php echo __("Search Product");?></a>
            <!-- Admin Permission ONLY -->
            <?php if($this->request->getSession()->read('Auth.User.sd_role_id') == 2) {
              echo "<a class=\"dropdown-item loadingAni\" href=\"/sd-products/addproduct\">". __("Add Product")."</a>";
            }?>
          </ul>
        </li>
        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"><?php echo __("Contact");?></a>
          <ul class="dropdown-menu">
            <a class="dropdown-item loadingAni" href="/sd-contacts/search"><?php echo __("Search Contact");?></a>
            <a class="dropdown-item loadingAni" href="/sd-contacts/addcontact"><?php echo __("Add Contact");?></a>
          </ul>
        </li>
        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"><?php echo __("ICSR");?></a>
          <ul class="dropdown-menu">
            <a class="dropdown-item loadingAni" href="/sd-cases/caseregistration"><?php echo __("Register SAE/AE");?></a>
            <a class="dropdown-item loadingAni" href="/sd-cases/caselist"><?php echo __("Case List");?></a>
          </ul>
        </li>
        <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#"><?php echo __("My Account");?></a>
          <ul class="dropdown-menu">
            <a class="dropdown-item loadingAni" href="/sd-users/myaccount"><?php echo __("My Account");?></a>
            <!-- Admin Permission ONLY -->
            <?php if($this->request->getSession()->read('Auth.User.sd_role_id') == 2) {
              echo "<a class=\"dropdown-item loadingAni\" href=\"/sd-senders/addsender\">". __("Sender Info")."</a>";
            }?>
          </ul>
        </li>
      </ul>
    </div>
  </div>
</nav>


<!-- The following codes are required when this layout applied to any other  -->
<?= $this->Flash->render() ?>
<!-- Disable this when applied Bootstrap Framework    <div class="container clearfix"></div> -->
<?= $this->fetch('content') ?>

<!-- Footer -->
<hr>
<div class="text-center text-muted">
  Copyright &copy; <?php echo date("Y");?> G2-MDS. All rights reserved <br> Designed, Developed and Maintained by G2 Biopharma Services Inc.
</div>
<!-- jQuery required these for loading datepicker -->
<!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
</body>
</html>