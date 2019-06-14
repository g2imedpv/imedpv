<title><?php echo __("Query Box")?></title>

<div class="container-fluid w-75 my-3">
    <div class="row">
        <div class="col-sm-9 col-md-10">
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-sm-3 col-md-2">
            <!--
            <a href="#" class="btn btn-danger btn-lg btn-block" role="button"><i class="far fa-edit"></i> Compose</a>
            <hr>
            -->
            <ul class="nav nav-pills nav-stacked queryBoxLeft">
                <a href="/sd-queries/queries" class=""><li class="active"><i class="fas fa-inbox mr-5"></i> <?php echo __("Inbox")?> <span class="badge pull-right"><?php $newQueryCount ?></span></li></a>
                <a href="/sd-queries/queries/unread" class=""><li><i class="fas fa-envelope mr-5"></i> <?php echo __("Unread")?></li></a>
                <a href="/sd-queries/queries/system"><li><i class="fas fa-cogs mr-5"></i> <?php echo __("System")?></li></a>
                <a href="/sd-queries/queries/flaged"><li><i class="fas fa-flag mr-5"></i> <?php echo __("Flagged")?></li></a>
                <a href="/sd-queries/queries/sent"><li><i class="fas fa-paper-plane mr-5"></i> <?php echo __("Sent Mail")?></li></a>
                <a href="/sd-queries/queries/deleted"><li><i class="fas fa-trash mr-5"></i> <?php echo __("Trash")?> <span class="badge pull-right"></span></li></a>
            </ul>
        </div>
        <div class="col-sm-9 col-md-10">
            <!-- Split button
            <div class="btn-group">
                <button type="button" class="btn btn-default">
                    <div class="checkbox" style="margin: 0;">
                        <label>
                            <input type="checkbox">
                        </label>
                    </div>
                </button>
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span><span class="sr-only">Toggle Dropdown</span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">All</a></li>
                    <li><a href="#">None</a></li>
                    <li><a href="#">Read</a></li>
                    <li><a href="#">Unread</a></li>
                    <li><a href="#">Starred</a></li>
                    <li><a href="#">Unstarred</a></li>
                </ul>
            </div>
            <button type="button" class="btn btn-default" data-toggle="tooltip" title="Refresh">
                &nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-refresh"></span>&nbsp;&nbsp;&nbsp;</button>
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    More <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" role="menu">
                    <li><a href="#">Mark all as read</a></li>
                    <li class="divider"></li>
                    <li class="text-center"><small class="text-muted">Select messages to see more actions</small></li>
                </ul>
            </div>
            -->
            <!-- Tab panes -->
            <div class="tab-content" style="background-color: aliceblue;">
                <table class="table table-hover table-borderless table-striped">
                    <thead>
                        <tr>
                            <th scope="col" style="width:15%;"><?php echo __("Sender")?></th>
                            <th scope="col" style="width:65%"><?php echo __("Title")?></th>
                            <th scope="col" style="width:15%"><?php echo __("Date")?></th>
                            <!-- <th scope="col" style="width:5%">Action</th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach($sdQueries as $sdQuery){
                            echo "<tr class=\"queryBoxTable\" data-href=\"/sd-queries/view/".$sdQuery['id']."\">";
                            echo "<td>";
                            if(empty($sdQuery['senderfirstname']))
                            echo "System";
                            else echo $sdQuery['senderfirstname']." ".$sdQuery['senderlastname'];
                            echo "</td>";
                            echo "<td>";
                            if(empty($sdQuery['read_date']))
                            echo "<b>";
                            echo __($sdQuery['title']+"");
                            if(empty($sdQuery['read_date']))
                            echo "</b>";
                            echo "</td>";
                            echo "<td>".$sdQuery['send_date']."</td>";
                            //echo "<td><button type=\"button\" class=\"btn btn-outline-danger btn-sm\"><i class=\"fas fa-trash-alt\"></i></button></td>";
                            if(empty($sdQuery['read_date']))
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!--
            <div class="pull-right float-right">
                <span class="text-muted"><b>1</b>–<b>50</b> of <b>160</b></span>
            </div>
            -->
        </div>
    </div>
    <hr>
</div>

<?php
// foreach($sdQueries as $sdQuery){
//     echo "<li><a href=\"/sd-queries/view/".$sdQuery['id']."\">";
//     if(empty($sdQuery['read_date']))
//     echo "<b>";
//     echo "Title:".$sdQuery['title'];
//     echo "Send Date:".$sdQuery['send_date'];
//     if(empty($sdQuery['read_date']))
//     echo "</b>";
//     echo "</a></li>";
// }
?>