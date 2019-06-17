<title>Workflow Manager</title>

<head>
<?= $this->Html->css('mainlayout.css') ?>
<?= $this->Html->script('product/workflowmanager.js') ?>
<head>

<div class="container my-3">
    <div class="card text-center">
        <div class="card-header">
            <h3>Workflow Manager</h3>
        </div>
        <div class="card-body">
            <h4>Workflow List Details</h4>
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="row" class="w-25">Workflow Name</th>
                        <td id="">WWW1</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row" class="w-25">Call Center</th>
                        <td id="">China</td>
                    </tr>
                    <tr>
                        <th scope="row" class="w-25">Country</th>
                        <td id="">China</td>
                    </tr>
                    <tr>
                        <th scope="row" class="w-25">Description</th>
                        <td id="">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum unde assumenda quo consequatur, alias soluta eum placeat eius maxime odit, odio sint, iste veniam omnis!</td>
                    </tr>
                </tbody>
            </table>

            <hr>

            <div>
                <h4 class="d-inline-block"><?php echo __("Workflow Resources");?></h4>
                <button type="button" class="btn btn-warning ml-1 mb-1" data-container="body" data-toggle="popover" data-placement="bottom" data-content="<div>Drag the resources below to 'Workflow Activities' for assignments<img src='/img/workflowassignment.gif' style='width:500px;'></div>"><i class="fas fa-question"></i></button>
            </div>
            <div id="workflowResources" class="border border-info p-2 mb-3" style="font-size: 20px;">
                <div class="alert alert-info d-inline-block m-2 wfres" role="alert">Tom</div>
                <div class="alert alert-info d-inline-block m-2 wfres" role="alert">Jim</div>
                <div class="alert alert-info d-inline-block m-2 wfres" role="alert">Alice</div>
                <div class="alert alert-info d-inline-block m-2 wfres" role="alert">Bob</div>
                <div class="alert alert-info d-inline-block m-2 wfres" role="alert">Jackson</div>
            </div>

            <h4><?php echo __("Workflow Activities");?></h4>
            <div id="workflowActivityDropzone">
                <div class="badge badge-info p-3 resourceDropzone">
                    <div class="card-header"><h5><?php echo __("Triage");?></h5></div>
                </div>
                <i class="fas fa-long-arrow-alt-right"></i>

                <div class="badge badge-info p-3 resourceDropzone">
                    <div class="card-header"><h5><?php echo __("Data Entry");?></h5></div>
                </div>
                <i class="fas fa-long-arrow-alt-right"></i>

                <div class="badge badge-info p-3 resourceDropzone">
                    <div class="card-header"><h5><?php echo __("Quality Check");?></h5></div>
                </div>
                <i class="fas fa-long-arrow-alt-right"></i>

                <div class="badge badge-info p-3 resourceDropzone">
                    <div class="card-header"><h5><?php echo __("Medical Review");?></h5></div>
                </div>
                <i class="fas fa-long-arrow-alt-right"></i>

                <div class="badge badge-info p-3 resourceDropzone">
                    <div class="card-header"><h5><?php echo __("Generate Report");?></h5></div>
                </div>
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-success mt-3 w-25"><?php echo __("Manual");?>Complete</button>
            </div>
        </div>
    </div>
</div>