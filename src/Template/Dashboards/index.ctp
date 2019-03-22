<title>Dashboard</title>
<head>
<?= $this->Html->script('dashboard/index.js') ?>
<head>
<script>
var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
</script>
<div class="container" >
  <div class="row mt-3"style="display:none;">
    <div class="col">
        <div class="card text-center">
            <h5 class="card-header">Serious AE/EOSI Cases Alert (For Medical Review ONLY)</h5>
            <div class="card-body">
				<!--
                <h5 class="card-title">Special title treatment</h5>
				<p class="card-text">With additional content.</p>
				-->
					<table class="table table-hover">
						<thead>
							<tr class="table-secondary">
							<th scope="col">Death</th>
							<th scope="col">Life Threat</th>
							<th scope="col">Prolong</th>
							<th scope="col">Disability</th>
							<th scope="col">Anomaly</th>
							<th scope="col">Other</th>
							<th scope="col">EOSI</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							<th scope="row">8</th>
							<th scope="col">3</th>
							<th scope="col">5</th>
							<th scope="col">4</th>
							<th scope="col">2</th>
							<th scope="col">3</th>
							<th scope="col">3</th>
							</tr>
						</tbody>
					</table>
            </div>
        </div>
    </div>
  </div>
  <div class="modal fade versionUpFrame" tabindex="-1" role="dialog" aria-labelledby="versionUpFrame" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body m-3">
                            <div class="form-group">
                                <label for="">Reason For Version Up:</label>
                                <select  class="form-control">
                                    <option>Select reason for version up</option>
                                    <option>Data Correction</option>
                                    <option>Follow Up</option>
                                </select>
                            </div>
                            <div class="form-control btn btn-primary w-50 float-right" id="confirmVersionUp" onclick="">Confirm </div>
                        </div>
                    </div>
                </div>
            </div>

  <div class="row mt-3" id="pendcase" style="display:none;">
    <div class="col">
        <div class="card text-center">
            <h5 class="card-header"> <span class="badge badge-pill badge-danger" id="alertNew">3</span> Pending Cases (For Data Entry ONLY)</h5>
            <div class="card-body">
				<!--
                <h5 class="card-title">Special title treatment</h5>
				<p class="card-text">With additional content.</p>
				-->
                <table class="table table-hover" >
                    <thead>
                        <tr class="table-secondary">
                        <th scope="col">Flag</th>
                        <th scope="col">Due Date</th>
                        <th scope="col">Version</th>
                        <th scope="col">Country</th>
                        <th scope="col">Product Type</th>
                        <th scope="col">Activity Due Date</th>
                        <th scope="col">Submission Due Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-href="/sd-tabs/showdetails/1?caseId=1">
                            <th scope="row"><i class="fas fa-flag text-warning"></i></th>
                            <th scope="col">12/30/2018</th>
                            <th scope="col">1</th>
                            <th scope="col">USA</th>
                            <th scope="col">Medicine</th>
                            <th scope="col">03/10/2018</th>
                            <th scope="col">02/10/2019</th>
                        </tr>
                        <tr data-href="#">
                            <th scope="row"><i class="fas fa-flag text-danger"></i></th>
                            <th scope="col">5/30/2018</th>
                            <th scope="col">1</th>
                            <th scope="col">Japan</th>
                            <th scope="col">Device</th>
                            <th scope="col">01/23/2018</th>
                            <th scope="col">02/04/2019</th>
                        </tr>
                        <tr data-href="#">
                            <th scope="row"><i class="fas fa-flag"></i></th>
                            <th scope="col">12/25/2018</th>
                            <th scope="col">1</th>
                            <th scope="col">Canada</th>
                            <th scope="col">Device</th>
                            <th scope="col">05/10/2018</th>
                            <th scope="col">12/12/2019</th>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
  </div>


  <div class="card my-3 searchmodal">
    <div class="card-header text-center">
        <h3>Dashboard</h3>
    </div>
    <div class="card-body">
        <h4 class="display-8 text-center">Quick Look</h4>
        <div class="form-row justify-content-center">
            <?php
            foreach($preferrence_list as $preferrence_detail){
                if ($preferrence_detail['id']==7) {
                    echo "<div class=\"form-group col-lg-4\" onclick=\"onQueryClicked(".$preferrence_detail['id'].")\"><div class=\"form-control btn btn-danger w-100\">";
                    echo $preferrence_detail['preferrence_name']." ";
                    echo "<span class=\"badge badge-light\">".$preferrence_detail['count']."</span>";
                    echo "</div></div>";
                }
                else {
                    echo "<div class=\"form-group col-lg-2\" onclick=\"onQueryClicked(".$preferrence_detail['id'].")\"><div class=\"form-control btn btn-outline-primary w-100\">";
                    echo $preferrence_detail['preferrence_name']." ";
                    echo "<span class=\"badge badge-danger\">".$preferrence_detail['count']."</span>";
                    echo "</div></div>";
                }
            }
            ?>
        </div>
        <hr class="my-4">
        <h4 class="display-8 text-center">Search</h4>
        <div class="form-row  justify-content-center" id="basicSearch">
            <div class="form-group col-lg-3">
                <input type="text" class="form-control" id="searchProductName" name="searchProductName" placeholder="Search by Product Name">
            </div>
            <div class="form-group col-lg-2">
                <div id="searchBtn" onclick="onQueryClicked()" class="form-control btn btn-primary"><i class="fas fa-search"></i> Search</div>
            </div>
            <div class="form-group col-lg-2">
                <div id="fullSearchBtn" class="form-control btn btn-outline-info"><i class="fas fa-keyboard"></i> Advanced Search</div>
            </div>
        </div>
        <div id="fullSearch" style="display:none;">
            <div class="form-row">
                <div class="form-group col-lg-4">
                    <input type="text" class="form-control" id="searchProductName" name="searchProductName" placeholder="Search by Product Name">
                </div>
                <div class="form-group col-lg-4">
                    <input type="text" class="form-control"  id="searchName" name="searchName" placeholder="Select Case No.">
                </div>
                <div class="form-group col-lg-4">
                    <select class="form-control" id="caseStatus" name="caseStatus">
                        <option value="1">activate</option>
                        <option value="2">Inactivate</option>
                        <option value="3">all</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <p class="duedate form-group col-2">Activity Due Date:</p>
                <div class="form-group col-1">
                    <input type="text" class="form-control" id="datepicker1" placeholder="[mm/dd/yyyy]">
                </div>
                <div class="arrow">
                    <i class="far fa-window-minimize"></i>
                </div>
                <div class="form-group col-1">
                    <input type="text" class="form-control" id="datepicker2" placeholder="[mm/dd/yyyy]">
                </div>

                <p class="duedate form-group col-2 float-right">Submission Due Date:</p>
                <div class="form-group col-1">
                    <input type="text" class="form-control" id="datepicker3" placeholder="[mm/dd/yyyy]">
                </div>
                <div class="arrow">
                    <i class="far fa-window-minimize"></i>
                </div>
                <div class="form-group col-1">
                    <input type="text" class="form-control" id="datepicker4" placeholder="[mm/dd/yyyy]">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group col-lg-2">
                    <input type="text" class="form-control" id="patient_id" placeholder="Search by Patient ID">
                </div>
                <div class="form-group col-lg-2">
                    <input type="text" class="form-control" id="datepicker5" placeholder="Choose Date of Birth">
                </div>
                <div class="form-group col-lg-2">
                    <select id="inputState" class="form-control">
                        <option selected>Select Gender</option>
                        <option>Male</option>
                        <option>Female</option>
                        <option>Unknown</option>
                    </select>
                </div>
            </div>
            <div class="form-row justify-content-center">
                <div class="form-group col-lg-3">
                    <div id="searchBtn" onclick="onQueryClicked()" class="form-control btn btn-primary w-100"><i class="fas fa-search"></i> Search</div>
                </div>
                <div class="form-group col-lg-1">
                    <div class="clearsearch form-control btn btn-outline-danger w-100"><i class="fas fa-eraser"></i> Clear</div>
                </div>
            </div>
        </div>
        <hr class="my-3">
        <div id="textHint" class="text-center align-middle"></div>
    </div>
  </div>

</div>
