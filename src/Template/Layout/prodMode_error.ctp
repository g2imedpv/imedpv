<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<title>PAGE NOT FOUND</title>

        <!-- For local jQuery link, Bootstrap required -->
        <?= $this->Html->script('bootstrap/jquery-3.2.1.slim.min.js') ?>

        <!-- For local Bootstrap/CSS link -->
        <?= $this->Html->css('bootstrap/bootstrap-grid.min.css') ?>
        <?= $this->Html->css('bootstrap/bootstrap-reboot.min.css') ?>
        <?= $this->Html->css('bootstrap/bootstrap.min.css') ?>

		<!-- For local CSS link -->
        <?= $this->Html->css('element.css') ?>
	</head>
	<body style="background-color: lightgrey;">

    <div class="notFound jumbotron w-50 mx-auto">
        <h1 class="display-4">G2-MDS - Page Not Found</h1>
        <p class="lead">Looks like what you searched is not EXISTED. (404 ERROR) </p>
        <hr class="my-4">
        <p>May be the link is wrong or expired. Please make sure the URL is correct :) </p>
        <button type="button" class="btn btn-outline-info btn-lg mr-2" onclick="window.history.back()">Back to Previous</button>
        <a class="btn btn-primary btn-lg" href="/Dashboards/index" role="button">Back to Dashboard</a>
    </div>

	</body>
</html>
