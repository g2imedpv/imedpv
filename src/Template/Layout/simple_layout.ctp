<!DOCTYPE html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- <title>Dashboard</title> -->

    <!-- For local jQuery link, Bootstrap required -->
    <?= $this->Html->script('bootstrap/jquery-3.3.1.min.js') ?>
    <?= $this->Html->script('bootstrap/popper.min.js') ?>

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
    <?= $this->Html->script('bootstrap/bootstrap.min.js') ?>
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
</head>
<body>

<!-- <div class="topNav">
    <div class="navInner mx-auto d-flex bd-highlight">
      <a class="navLogo navbar-brand mr-auto my-auto bd-highlight" href="/Dashboards/index">
        <img src="/img/logo-mds.png" title="MDS" alt="logo" style="width:200px;">
      </a>
      <div class="d-flex p-2 bd-highlight">
        <?php
        $mailNotice = $this->cell('QueryNotice',[$this->request->getSession()->read('Auth.User.id')]);
        echo $mailNotice;
        ?>
      </div>

      <div class="nav-item dropdown p-2 bd-highlight myaccount">
        <a class="nav-link text-dark bg-light" href="/sd-users/myaccount" id="accountInfo" role="button" aria-haspopup="true" aria-expanded="false">
          <h5>Hi, <span id="roleName"> <?php print $this->request->getSession()->read('Auth.User.firstname'); ?>&nbsp;<?php print $this->request->getSession()->read('Auth.User.lastname'); ?> </span> </h5>
        </a>
        <div class="dropdown-menu login" aria-labelledby="accountInfo">
          <h5 class="dropdown-header"><?php echo $this->request->getSession()->read('Auth.User.role_name'); ?></h5>
          <a class="dropdown-item my-1" href="/sd-users/myaccount"><i class="fas fa-user-cog"></i> My Account</a>
          <a class="dropdown-item my-1" href="/sd-users/logout"><i class="fas fa-sign-out-alt"></i> Log Out</a>
        </div>
      </div>

    </div>
</div> -->

<nav class="navbar navbar-expand-lg mainNav navbar-dark bg-primary">
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="nav navbar-nav mr-auto">
      <li class="nav-item"><a class="nav-link" href="/Dashboards/index">Dashboard <span class="sr-only">(current)</span></a></li>
      <!-- <li class="nav-item"><a class="nav-link" href="#">Link</a></li> -->
      <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#">Product</a>
        <ul class="dropdown-menu">
          <a class="dropdown-item" href="/sd-products/search">Search Product</a>
          <a class="dropdown-item" href="/sd-products/addproduct">Add Product</a>
        </ul>
      </li>
      <li class="nav-item dropdown"><a class="nav-link dropdown-toggle" href="#">ICSR</a>
        <ul class="dropdown-menu">
          <a class="dropdown-item" href="/sd-cases/caseregistration">Register SAE/AE</a>
          <a class="dropdown-item" href="/sd-cases/caselist">Case List</a>
        </ul>
      </li>
      <li class="nav-item"><a class="nav-link" href="/sd-users/myaccount">My Account</a></li>
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