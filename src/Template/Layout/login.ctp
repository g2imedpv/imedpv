<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>Welcome To G2-MDS</title>

		<!-- For local jQuery link, Bootstrap required -->
		<?= $this->Html->script('bootstrap/jquery-3.2.1.slim.min.js') ?>

		<!-- For local Bootstrap/CSS link -->
		<?= $this->Html->css('bootstrap/bootstrap-grid.min.css') ?>
		<?= $this->Html->css('bootstrap/bootstrap-reboot.min.css') ?>
		<?= $this->Html->css('bootstrap/bootstrap.min.css') ?>

		<!-- For local Bootstrap/JS link -->
		<?= $this->Html->script('bootstrap/bootstrap.bundle.min.js') ?>
		<?= $this->Html->script('bootstrap/bootstrap.min.js') ?>

		<!-- For local JS link -->
		<?= $this->Html->script('element.js') ?>

		<!-- For local CSS link -->
        <?= $this->Html->css('login.css') ?>
	</head>
	<body>

        <?= $this->Flash->render() ?>
        <div class="container clearfix m-auto">
            <?= $this->fetch('content') ?>
        </div>

        <div class="fixed-bottom text-center text-muted">
            Copyright &copy; 2018 G2-MDS. All rights reserved
			<br> Designed, Developed and Maintained by G2 Biopharma Services
		</div>

	</body>
</html>
