<title><?php echo __("Query Content")?></title>

<div class="container-fluid w-75 my-3">
    <div class="jumbotron">
    <h1 class="display-4"><?= h($sdQuery->title) ?></h1>
    <div class="d-flex">
        <div class="lead w-75"><?php echo __("From")?>: <?= h($sdQuery->sender) ?></div>
        <div class="float-right text-right w-25"><?= h($sdQuery->send_date) ?></div>
    </div>
    <hr class="my-4">
    <p><?= h($sdQuery->content) ?></p>
    <button type="button" class="btn btn-primary float-right" onclick="window.history.back();"><?php echo __("Go Back")?></button>
    </div>
</div>