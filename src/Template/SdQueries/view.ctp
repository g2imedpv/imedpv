<title><?php echo __("Query Content")?></title>

<div class="card text-center w-75 my-3 mx-auto">
    <div class="card-header">
        <button type="button" class="btn btn-link float-left" onclick="window.history.back();"><i class="fas fa-arrow-left"></i></button>
        <h3><?php echo __("Query Content")?></h3>
    </div>
    <div class="card-body">
        <h1 class="display-4 text-left"><?= h($sdQuery->title) ?></h1>
        <div class="row">
            <div class="lead col-auto mr-auto text-left"><?php echo __("From")?>: <?php
                if($sdQuery->senderfirstname==null) echo "SystmNotice";
                else echo $sdQuery->senderfirstname." ".$sdQuery->senderlastname."  &lt;".$sdQuery->senderEmail."&gt;";
                ?>
                <br>
                <?php echo __("To")?>: <?php
                if($sdQuery->receiverfirstname==null) echo "SystmNotice";
                else echo $sdQuery->receiverfirstname." ".$sdQuery->receiverlastname."   &lt;".$sdQuery->receiverEmail."&gt;";
                ?>
            </div>
            <div class="col-auto"><?= h($sdQuery->send_date) ?></div>
        </div>
        <hr class="my-4">
        <?php echo ($sdQuery->content) ?>
    </div>
</div>