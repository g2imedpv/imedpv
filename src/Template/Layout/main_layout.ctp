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
</head>
<body>

<div class="topNav">
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

      <div class="nav-item dropdown p-2 bd-highlight">
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
</div>

<nav class="mainNav navbar navbar-expand-lg navbar-dark">
  <!-- <a class="navbar-brand" href="#">Navbar</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button> -->

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navMenu navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="/Dashboards/index">Dashboard <span class="sr-only">(current)</span></a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="/sd-products/search" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">Product</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/sd-products/search">Search Product</a>
          <a class="dropdown-item" href="/sd-products/addproduct">Add Product</a>
        </div>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="/sd-cases/caselist" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">ICSR</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/sd-cases/caseregistration">Register SAE/AE</a>
          <a class="dropdown-item" href="/sd-cases/caselist">Case List</a>
          <!-- Use the following if we need diveder in dropdown menu
          <div class="dropdown-divider"></div>
          <a class="dropdown-item" href="#">Something else here</a>
          -->
        </div>
      </li>
      <!-- <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" aria-haspopup="true" aria-expanded="false">Form Builder</a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
          <a class="dropdown-item" href="/">Add Form</a>
          <a class="dropdown-item" href="/">Form List</a>
        </div>
      </li> -->
      <li class="nav-item">
        <a class="nav-link" href="/sd-users/myaccount">My Account</a>
      </li>
      <!-- <li class="nav-item">
        <a class="nav-link disabled" href="#">Disabled</a>
      </li> -->
    </ul>
    <!-- <form class="form-inline my-2 my-lg-0">
      <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
    </form> -->
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