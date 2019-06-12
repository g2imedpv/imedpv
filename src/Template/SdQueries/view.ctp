<title><?php echo __("Query Content")?></title>

<div class="container-fluid w-75 my-3">
    <div class="jumbotron">
    <button type="button" class="btn btn-link queryBackBtn" onclick="window.history.back();"><i class="fas fa-arrow-left"></i></button>
    <h1 class="display-4"><?= h($sdQuery->title) ?></h1>
    <div class="d-flex">
        <div class="lead w-75"><?php echo __("From")?>: <?php
                if($sdQuery->senderfirstname==null) echo "SystmNotice";
                else echo $sdQuery->senderfirstname." ".$sdQuery->senderlastname."  &lt;".$sdQuery->senderEmail."&gt;";
                ?>
        </div>
        <div class="lead w-75"><?php echo __("To")?>: <?php
                if($sdQuery->receiverfirstname==null) echo "SystmNotice";
                else echo $sdQuery->receiverfirstname." ".$sdQuery->receiverlastname."   &lt;".$sdQuery->receiverEmail."&gt;";
                ?>
        </div>
        <div class="float-right text-right w-25"><?= h($sdQuery->send_date) ?></div>
    </div>
    <hr class="my-4">
    <?php echo ($sdQuery->content) ?>
    </div>
</div>