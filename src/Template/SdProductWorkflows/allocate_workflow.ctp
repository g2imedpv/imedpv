<title>Allocate Team Resources</title>

<head>
<?= $this->Html->css('mainlayout.css') ?>
<?= $this->Html->script('product/workflowmanager.js') ?>
<head>
<script type="text/javascript">
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
    var workflowId = <?= $id?>;
</script>
<div class="container my-3">
    <div class="card text-center">
        <div class="card-header">
            <h3>Allocate Team</h3>
        </div>
        <div class="card-body">
            <h4>Workflow List Details</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="row" class="w-25">Workflow Name</th>
                        <td id=""><?= $sdProductWorkflow['sd_workflow']['name']?></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row" class="w-25">Call Center</th>
                        <td><?= $sdProductWorkflow['sd_company']['company_name']?></td>
                    </tr>
                    <tr>
                        <th scope="row" class="w-25">Country</th>
                        <td><?= $sdProductWorkflow['sd_workflow']['country']?></td>
                    </tr>
                    <tr>
                        <th scope="row" class="w-25">Description</th>
                        <td id=""><?= $sdProductWorkflow['sd_workflow']['description']?></td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <div>
                <h4 class="d-inline-block">Workflow Resources</h4>
                <button type="button" class="btn btn-warning ml-1 mb-1" data-container="body" data-toggle="popover" data-placement="bottom" data-content="<div>Drag the resources below to 'Workflow Activities' for assignments<img src='/img/workflowassignment.gif' style='width:500px;'></div>"><i class="fas fa-question"></i></button>
            </div>
            <div id="workflowResources" class="border border-info p-2 mb-3" style="font-size: 20px;">
            <?php
            foreach($sdUserDistinctAssignments as $userDetail){
                echo "<div class=\"alert alert-info d-inline-block m-2 wfres\" id=\"personal-".$userDetail['sd_user']['id']."\" role=\"alert\">".$userDetail['sd_user']['firstname']." ".$userDetail['sd_user']['lastname']."</div>";
            }
            ?>
            </div>

            <h4>Workflow Activities</h4>
            <div id="workflowActivityDropzone">
            <?php
            foreach($sdProductWorkflow['sd_workflow']['sd_workflow_activities'] as $activityDetail){
                echo "<div id=\"activity_card-".$activityDetail['id']."\" class=\"badge badge-info p-3 resourceDropzone m-3 mw-100\">";
                echo "<div class=\"card-header\"><h5>".$activityDetail['activity_name']."</h5><p>".$activityDetail['description']."</p></div>";

                foreach($sdUserAssignments as $userDetail){
                    if($userDetail['sd_workflow_activity_id']==$activityDetail['id'])
                        echo "<div class=\"alert alert-info d-inline-block m-2 wfres\" id=\"personal-".$userDetail['sd_user']['id']."\" role=\"alert\">".$userDetail['sd_user']['firstname']." ".$userDetail['sd_user']['lastname']."</div>";
                }
                echo "</div>";
                echo "<i class=\"fas fa-long-arrow-alt-right\"></i>";
            }
            ?>
                <div class="badge badge-info p-3 resourceDropzone">
                    <div class="card-header"><h5>Complete</h5><p>End of the case</p></div>
                </div>
            </div>
            <?php if($sdProductWorkflow['status']==1): ?>
            <div class="text-center">
                <button type="submit" onclick="confirmAllocation()" class="btn btn-success mt-3 w-25">Confirm</button>
            </div>
        <?php endif; ?>
        </div>
    </div>
</div>