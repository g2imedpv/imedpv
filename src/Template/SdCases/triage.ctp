<title><?php echo __("Triage")?></title>
<head>
    <?= $this->Html->script('meddra.js') ?>
    <?= $this->Html->script('cases/triage.js') ?>

<head>
<script>
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
    var caseNo = "<?= $caseNo ?>";
    var versionNo = <?= $versionNo?>;
    var dayZero = "<?= $field_value_set['12']['field_value']?>";
    var field_value_set = <?= json_encode($field_value_set)?>;
    var event_set = <?= json_encode($event_set)?>;
</script>
<div class="mx-auto my-3 formContainer text-center">
    <p style="font-size: 2rem;">
        <?php echo __("Create New Case")?>
    </p>
  <div class="">
    <div class="alert alert-primary w-50 mx-auto" role="alert"><h4><?php echo __("New Case Number")?>: <?= $caseNo ?></h4></div>
    <hr class="my-3">
    <?= $this->Form->create($caseNo,['id'=>"triageForm", 'enctype'=>"multipart/form-data"]) ?>
    <!-- Basic Info Fields Set -->
    <?php if($versionNo>1){
        echo "<div id=\"versionInfo\" class=\"form-group\"><h4 class=\"text-left\">".__("Version Info")."</h4>";
        echo "<div class=\"form-row\">";
        echo "<div class=\"form-group col-md-3\">";
        echo "<label>".__("Reason for Version Up")."</label>";
        // if($field_value_set['79']['id']!=null)
        //     echo "<input type=\"hidden\" id=\"id_patientField_id_id\" name=\"field_value[79][id]\" value=\"".$field_value_set['79']['id']."\">";
        // echo "<input type=\"text\" class=\"form-control\" id=\"patientField_id\" name=\"field_value[79][value]\" value=\"".$field_value_set['79']['field_value']."\"></div>";
        // echo "</div>";
        echo "<select class=\"custom-select\">";
        echo "<option>".__("Follow Up")."</option>";
        echo "<option>".__("Data Correction")."</option>";
        echo "</select></div>";
        echo "<div class=\"form-group col-md-3\">";
        echo "<label>".__("Versioning Comment")."</label>";
        echo "<textarea class=\"form-control\"></textarea>";
        echo "</div>";
        echo "</div></div>";
    }
    ?>
    <div id="basicInfo" class="form-group mx-3">
        <div class="form-row text-left">
            <h4><?php echo __("Product")?></h4>
            <div class="form-group col-md-12">
                <label><?php echo __("Product Name")?> (B.4.k.2.1)<i class="fas fa-asterisk reqField"></i></label>
                <p><b><?= $field_value_set['176']['field_value'] ?></b><p>
            </div>
        </div>

        <!-- Patient Info -->
        <h4 class="text-left"><?php echo __("Patient")?></h4>
        <div id="patientInfo" class="form-row bg-light">
            <div class="form-group col-md-3">
                <label><?php echo __("Patient ID")?> (B.1.1)</label>
                <?php echo "<input type=\"hidden\" id=\"id_patientField_id_id\" name=\"field_value[79][id]\" value=\"".$field_value_set['79']['id']."\">";?>
                <input type="text" class="form-control" id="patientField_id" name="field_value[79][value]" value="<?= $field_value_set['79']['field_value']?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Sex")?> (B.1.5)</label>
                <?php echo "<input type=\"hidden\" id=\"id_patientField_sex_id\" name=\"field_value[93][id]\" value=\"".$field_value_set['93']['id']."\">";?>
                <select class="custom-select" id="patientField_sex" name="field_value[93][value]">
                    <option value=""><?php echo __("Select Sex")?></option>
                    <option value="1" <?php echo ($field_value_set['93']['field_value']=='1')?"selected":null?>><?php echo __("Male")?></option>
                    <option value="2" <?php echo ($field_value_set['93']['field_value']=='2')?"selected":null?>><?php echo __("Female")?></option>
                    <option value="3" <?php echo ($field_value_set['93']['field_value']=='3')?"selected":null?>><?php echo __("Unknown")?></option>
                    <option value="4" <?php echo ($field_value_set['93']['field_value']=='4')?"selected":null?>><?php echo __("Not Specified")?></option>
                </select>
            </div>
        </div>

        <div id="patientInfo" class="form-row bg-light justify-content-between">
            <div class="form-group col-md-4">
                <label><?php echo __("Date of Birth")?> (B.1.2.1b)</label>
                <?php echo "<input type=\"hidden\" id=\"id_patientField_dob_id\" name=\"field_value[85][id]\" value=\"".$field_value_set['85']['id']."\">";?>
                <div class="d-flex">
                    <div class="col-md-4">
                        <select class="custom-select js-example-basic-single" placeholder="Day" id="patientField_dob_day" name="field_value[85][value]">
                        <?php
                        echo "<option value=\"00\">".__("Day")."</option>";
                        for($i=1;$i<32;$i++){
                            echo "<option value=\"".sprintf("%02d",$i)."\"";
                            if (array_key_exists('85',$field_value_set)&&(substr($field_value_set['85']['field_value'],0,2)==sprintf("%02d",$i))) echo "selected";
                            echo ">".$i."</option>";
                        }
                        ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="custom-select js-example-basic-single"  name="field_value[85][value]" placeholder="Month" id="patientField_dob_month">
                        <?php
                        $month_str = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
                        echo "<option value=\"00\">".__("Month")."</option>";
                        foreach($month_str as $i => $month){
                            echo "<option value=\"".sprintf("%02d",$i+1)."\"";
                            if (array_key_exists('85',$field_value_set)&&(substr($field_value_set['85']['field_value'],2,2)==sprintf("%02d",$i+1))) echo "selected";
                            $j=$i+1;
                            echo ">".__($month.-$j)."</option>";
                        }
                        ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select class="custom-select js-example-basic-single yearSelect" placeholder="Year" id="patientField_dob_year" name="field_value[85][value]">
                        <option value="0000"><?php echo __("Year")?></option>
                        <?php
                        for($i=1900;$i<=2050;$i++){
                            echo "<option value=\"".sprintf("%04d",$i)."\"";
                            if (array_key_exists('85',$field_value_set)&&(substr($field_value_set['85']['field_value'],4,4)==sprintf("%02d",$i))) echo "selected";
                            echo ">".$i."</option>";
                        }
                        ?>
                        </select>
                    </div>
                    <?php
                    echo "<input id=\"patientField_dob\" name=\"field_value[85][value]\"";
                    if($field_value_set['85']['field_value']!=null) echo "value=\"".$field_value_set['85']['field_value']."\"";
                    echo " type=\"hidden\">";
                    ?>
                </div>
                <!-- <div class="form-row">
                </div> -->
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Age")?> (B.1.2.2a)</label>
                <?php echo "<input type=\"hidden\" id=\"id_patientField_age_id\" name=\"field_value[86][id]\" value=\"".$field_value_set['86']['id']."\">";?>
                <a tabindex="0" role="button" data-toggle="popover" data-trigger="focus" title="<?php echo __("Field Helper")?>" data-content="<?php echo __("Age at Time of Onset of Reaction/event")?>"><i class="qco fas fa-info-circle"></i></a>
                <input type="text" class="form-control" id="patientField_age" name="field_value[86][value]" value="<?= $field_value_set['86']['field_value']?>">
            </div>
            <!-- <div class="form-group col-md-3">
                <label>Age at the time of event (B.1.2.2a)</label>
                <input type="text" class="form-control" id="">
            </div> -->
            <div class="form-group col-md-3">
                <label><?php echo __("Age Unit")?> (B.1.2.2b)</label>
                <?php echo "<input type=\"hidden\" id=\"id_patientField_sex_id\" name=\"field_value[87][id]\" value=\"".$field_value_set['87']['id']."\">";?>
                <select class="custom-select" name="field_value[87][value]" id="patientField_ageunit">
                    <option value=""><?php echo __("Select Age Unit")?></option>
                    <option value="800" <?php echo ($field_value_set['87']['field_value']=='800')?"selected":null?>><?php echo __("Decade")?></option>
                    <option value="801" <?php echo ($field_value_set['87']['field_value']=='801')?"selected":null?>><?php echo __("Year")?></option>
                    <option value="802" <?php echo ($field_value_set['87']['field_value']=='802')?"selected":null?>><?php echo __("Month")?></option>
                    <option value="803" <?php echo ($field_value_set['87']['field_value']=='803')?"selected":null?>><?php echo __("Week")?></option>
                    <option value="804" <?php echo ($field_value_set['87']['field_value']=='804')?"selected":null?>><?php echo __("Day")?></option>
                    <option value="805" <?php echo ($field_value_set['87']['field_value']=='805')?"selected":null?>><?php echo __("Hour")?></option>
                </select>
            </div>
        </div>
        <h4 class="text-left mt-3"><?php echo __("Reporter")?></h4>
        <div id="reporterInfo" class="form-row">
            <div class="form-group col-md-3">
                <label><?php echo __("First Name")?> (A.2.1.1b)</label>
                <?php echo "<input type=\"hidden\" id=\"id_reporterField_firstname_id\" name=\"field_value[26][id]\" value=\"".$field_value_set['26']['id']."\">";?>
                <input type="text" class="form-control" name="field_value[26][value]" id="reporterField_firstname" value="<?= $field_value_set['26']['field_value']?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Last Name")?> (A.2.1.1d)</label>
                <?php echo "<input type=\"hidden\" id=\"id_reporterField_lastname_id\" name=\"field_value[28][id]\" value=\"".$field_value_set['28']['id']."\">";?>
                <input type="text" class="form-control" name="field_value[28][value]" id="reporterField_lastname" value="<?= $field_value_set['28']['field_value']?>">
            </div>
            <div class="form-group col-md-3">
                <label><?php echo __("Latest Received Date")?> (A.1.7.b)</label>
                <?php echo "<input type=\"hidden\" id=\"id_reporterField_firstname_id\" name=\"field_value[12][id]\" value=\"".$field_value_set['12']['id']."\">";?>
                <input type="hidden" class="form-control" name="field_value[12][value]" id="reporterField_latestreceiveddate" value="<?= $field_value_set['12']['field_value']?>">
                <input type="date" class="form-control"  id="reporterField_latestreceiveddate_plugin">
            </div>
            <div class="form-group col-md-3" >
                <label><?php echo __("Initial Received Date")?> (A.1.7.b)</label>
                <?php echo "<input type=\"hidden\" id=\"id_reporterField_firstname_id\" name=\"field_value[10][id]\" value=\"".$field_value_set['10']['id']."\">";?>
                <input type="hidden" class="form-control" name="field_value[10][value]" id="reporterField_initialreceiveddate" value="<?= $field_value_set['10']['field_value']?>">
                <input type="date" class="form-control"  id="reporterField_initialreceiveddate_plugin" >
            </div>
            <?php echo "<input type=\"hidden\" id=\"id_reporterField_firstname_id\" name=\"field_value[225][id]\" value=\"".$field_value_set['225']['id']."\">";?>
            <input type="hidden" class="form-control" name="field_value[225][value]" id="reporterField_regulatoryclockstartddate" value="<?= $field_value_set['225']['field_value']?>">

        </div>

        <h4 class="text-left mt-3"><?php echo __("Event")?></h4>
        <div id="eventBlock">
            <div class="text-right mb-2">
                <button type="button" class="btn btn-info" id="addBtn" onclick="addEvent()"><i class="fas fa-plus-square"></i> <?php echo __("Add Event")?></button>
                <button type="button" class="btn btn-success" id="saveBtn" onclick="saveEvent()"><i class="fas fa-save"></i> <?php echo __("Save Event")?></button>
                <button type="button" class="btn btn-danger" id="deleteBtn" onclick="deleteEvent()"><i class="fas fa-trash-alt"></i> <?php echo __("Delete Event")?></button>
            </div>
            <table id="eventSummary" class="table table-striped table-bordered table-hover mb-3">
                <thead>
                    <tr>
                        <th><?php echo __("Event No")?></th>
                        <th><?php echo __("Reported Term")?></th>
                        <th><?php echo __("LLT Code")?></th>
                        <th><?php echo __("LLT Name")?></th>
                        <th><?php echo __("PT Code")?></th>
                        <th><?php echo __("PT Name")?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach($event_set as $setNo => $event_detail){
                        echo "<tr id=\"eventSet-".$setNo."\" onclick=\"mapfieldId(".$setNo.")\">";
                        echo "<td>";
                        echo $setNo;
                        echo "</td>";
                        echo "<td>";
                        echo array_key_exists('149',$event_set[$setNo])?$event_set[$setNo]['149']['field_value']:"";
                        echo "</td>";
                        echo "<td>";
                        echo array_key_exists('392',$event_set[$setNo])?$event_set[$setNo]['392']['field_value']:"";
                        echo "</td>";
                        echo "<td>";
                        echo array_key_exists('457',$event_set[$setNo])?$event_set[$setNo]['457']['field_value']:"";
                        echo "</td>";
                        echo "<td>";
                        echo array_key_exists('394',$event_set[$setNo])?$event_set[$setNo]['394']['field_value']:"";
                        echo "</td>";
                        echo "<td>";
                        echo array_key_exists('458',$event_set[$setNo])?$event_set[$setNo]['458']['field_value']:"";
                        echo "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <div id="eventDiv-1">
                <div class="form-row bg-light">
                    <div class="form-group col-md-4">
                        <?php echo "<input type=\"hidden\" id=\"id_eventField_term_id\" name=\"event[1][149][id]\" value=\"";
                        if($event_set!=null)
                            echo array_key_exists('149',$event_set[1])?$event_set[1]['149']['id']:"";
                        echo "\">";?>
                        <label><?php echo __("Reported Term")?> (B.2.i.0) <i class="fas fa-asterisk reqField"></i></label>
                        <input type="text" class="form-control" name="event[1][149][value]" id="eventField_term" value="<?php
                        if($event_set!=null)
                        echo array_key_exists('149',$event_set[1])?$event_set[1]['149']['field_value']:"";
                        ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label> &nbsp; </label>
                        <div>
                            <?php
                            // $meddraCell = $html->cell('Meddra',[$sd_section_structure_detail->sd_field->descriptor, $sd_section_structure_detail->sd_field->id]);
                            $meddraCell = $this->cell('Meddra',['llt-c:392,llt-n:457,pt-c:394,pt-n:458,ver:150,ver:443','496']);
                            echo $meddraCell;
                            echo "<input type=\"hidden\" id=\"id_eventField_meddraresult_id\" name=\"event[1][496][id]\" value=\"";
                            if($event_set!=null)
                            echo array_key_exists('496',$event_set[1])?$event_set[1]['496']['id']:"";
                            echo "\">";
                            echo "<input type=\"hidden\" id=\"eventField_meddraResult-496\" name=\"event[1][496][field_value]\" value=\"";
                            if($event_set!=null)
                                echo array_key_exists('496',$event_set[1])?$event_set[1]['496']['field_value']:"";
                            echo "\">";
                            ?>
                            <input id="eventField-meddraResult-496" class="form-control" name="event[1][496][value]" type="hidden">
                        </div>
                    </div>
                </div>
                <div class="form-row bg-light">
                    <div class="form-group col-md-3">
                        <label><?php echo __("LLT Code")?></label>
                        <?php echo "<input type=\"hidden\" id=\"id_eventField_meddralltname_id\" name=\"event[1][392][id]\" value=\"";
                        if($event_set!=null)
                            echo array_key_exists('392',$event_set[1])?$event_set[1]['392']['id']:"";
                            echo "\">";?>
                        <input type="text" class="form-control" name="event[1][392][value]" id="eventField_meddrashow-392" value="<?php
                        if($event_set!=null)
                            echo array_key_exists('392',$event_set[1])?$event_set[1]['392']['field_value']:"";
                        ?>">

                    </div>
                    <div class="form-group col-md-3">
                        <label><?php echo __("LLT Name")?></label>
                        <?php echo "<input type=\"hidden\" id=\"id_eventField_meddraptname_id\" name=\"event[1][457][id]\" value=\"";
                        if($event_set!=null)
                        echo array_key_exists('457',$event_set[1])?$event_set[1]['457']['id']:"";
                        echo "\">";?>
                        <input type="text" class="form-control" name="event[1][457][value]" id="eventField_meddrashow-457" value="<?php
                        if($event_set!=null)echo array_key_exists('457',$event_set[1])?$event_set[1]['457']['field_value']:"";?>
                        ">

                    </div>
                    <div class="form-group col-md-3">
                        <label><?php echo __("PT Code")?></label>
                        <?php echo "<input type=\"hidden\" id=\"id_eventField_meddrahltname_id\" name=\"event[1][394][id]\" value=\"";
                        if($event_set!=null)
                        echo array_key_exists('394',$event_set[1])?$event_set[1]['394']['id']:"";
                        echo "\">";?>
                        <input type="text" class="form-control" name="event[1][394][value]" id="eventField_meddrashow-394" value="<?php
                        if($event_set!=null)echo array_key_exists('394',$event_set[1])?$event_set[1]['394']['field_value']:"";?>
                        ">

                    </div>
                    <div class="form-group col-md-3">
                        <label><?php echo __("PT Name")?></label>
                        <?php echo "<input type=\"hidden\" id=\"id_eventField_meddrahltname_id\" name=\"event[1][458][id]\" value=\"";
                        if($event_set!=null)
                            echo array_key_exists('458',$event_set[1])?$event_set[1]['458']['id']:"";
                        echo "\">";?>
                        <input type="text" class="form-control" name="event[1][458][value]" id="eventField_meddrashow-458" value="<?php
                        if($event_set!=null) echo array_key_exists('458',$event_set[1])?$event_set[1]['458']['field_value']:"";?>
                        ">
                    </div>
                </div>
            </div>
        </div>

        <!-- Attachment -->
        <h4 class="text-left mt-3"><?php echo __("Attachments and References")?>
            <button id="addNewAttach-1" type="button" class="btn btn-outline-primary mx-1"><i class="fas fa-plus-square"></i> <?php echo __("Add New")?></button>
        </h4>
        <div class="form-row mb-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col"><?php echo __("Classification")?></th>
                        <th scope="col"><?php echo __("Description")?></th>
                        <th scope="col"><?php echo __("Type")?></th>
                        <th scope="col"><?php echo __("File/Reference")?></th>
                        <th scope="col"><?php echo __("Action")?></th>
                    </tr>
                </thead>
                <tbody id="newAttachArea">
                <tr>
                    <td><input type="text" class="form-control" name="document[0][doc_classification]" id="doc_classification_0"></td>
                    <td><input type="text" class="form-control" name="document[0][doc_description]" id="doc_description_0"></td>
                    <td><select class="custom-select" onchange="fileUrlSwitcher(0)" name="document[0][doc_source]" id="doc_source_0">
                            <option value="File Attachment"><?php echo __("File Attachment")?></option>
                            <option value="URL Reference"><?php echo __("URL Reference")?></option>
                        </select></td>
                    <td><input type="text" class="form-control" style="display:none;" name="document[0][doc_path]" id="doc_path_0">
                        <input type="file" name="document[0][doc_attachment]" id="doc_attachment_0"></td>
                        <td><button type="button" class="btn btn-outline-danger btn-sm my-1 w-100 attachDel"><i class="fas fa-trash-alt"></i> <?php echo __("Delete")?></button></td>
                </tr>
                </tbody>
            </table>
        </div>
        <?php
            if (count($sdDocList) > 0)
            {
        ?>
        <div id="showDocList">
                <!-- <h5>Document List</h5> -->
                <table id="docTable" class="table table-striped table-bordered table-hover dataTable w-100" role="grid">
                <thead>
                <tr style="cursor: pointer;" role="row">
                <th class="align-middle sorting_asc" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: activate to sort column descending"><?php echo __("ID")?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Classification: activate to sort column ascending"><?php echo __("Classification")?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Description: activate to sort column ascending"><?php echo __("Description")?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Source: activate to sort column ascending"><?php echo __("Source")?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Doc Name: activate to sort column ascending"><?php echo __("Doc Name")?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Doc Size: activate to sort column ascending"><?php echo __("Doc Size")?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Created User: activate to sort column ascending"><?php echo __("Uploaded By")?></th></tr>
                </thead>
                <tbody>
                <?php
                    foreach ($sdDocList as $key=>$sdDoc)
                    {
                        if ($key/2 == 0)
                            $odd_even = "even";
                        else
                            $odd_even = "odd";
                        print '<tr class='.'"'.$odd_even.'" '. 'role="row">';
                        print '<td class="align-middle">'.$sdDoc['id'].'</td>';
                        print '<td class="align-middle">'.__($sdDoc['doc_classification']).'</td>';
                        print '<td class="align-middle">'.$sdDoc['doc_description'].'</td>';
                        print '<td class="align-middle">'.$sdDoc['doc_source'].'</td>';
                        if ($sdDoc['doc_source'] == "File Attachment"){
                            print '<td class="align-middle"><a href="'.$sdDoc['doc_path'].'">'.$sdDoc['doc_name'].'</a></td>';
                        }
                        else{
                            print '<td class="align-middle"><a href="'.$sdDoc['doc_path'].'">'.$sdDoc['doc_path'].'</a></td>';
                        }

                        print '<td class="align-middle">'.$sdDoc['doc_size'].'</td>';
                        print '<td class="align-middle">'.$sdDoc['created_by'].'</td>';
                        print "</tr>";

                    }
                ?>
                </tbody>
                </table>
            </div>
        <?php
            }
        ?>
        <?php echo "<input type=\"hidden\" id=\"id_validcase\" name=\"field_value[223][id]\" value=\"".$field_value_set['223']['id']."\">";?>
        <?php echo "<input type=\"hidden\" id=\"validcase\" name=\"field_value[223][value]\" value=\"".$field_value_set['223']['field_value']."\">";?>
        <button type="button" id="confirmElements" class="btn btn-primary m-auto w-25"><?php echo __("Continue")?></button>
        <button type="button" onclick="savenexit()" id="savenexitbtn" class="btn btn-outline-info m-auto w-25"><?php echo __("Save and Exit")?></button>
    </div>

    <hr class="my-2">

    <!-- If invalid then choose YES, Select Reasons -->
    <div id="selRea" class="card w-50 mx-auto my-3" style="display:none;">
        <div class="card-header text-center"><h5><?php echo __("Please Select Reasons For Continuing")?></h5></div>
        <div class="card-body" id="selectReasonContent">
        <?php echo "<input type=\"hidden\" id=\"reason_id\" name=\"field_value[417][id]\" disabled value=\"".$field_value_set['417']['id']."\">";?>
        <?php echo "<input type=\"hidden\" id=\"reason_value\" name=\"field_value[417][value]\" disabled value=\"".$field_value_set['417']['field_value']."\">";?>
            <div class="mx-auto w-50">
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="1" id="reason-1" disabled="true" <?php if(substr($field_value_set['417']['field_value'],0,1)==1) echo "checked"; ?>>
                    <label class="form-check-label" for="reason-1"><?php echo __("Reporter is Reliable")?> </label>
                </div>
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="1" id="reason-2" disabled="true"<?php if(substr($field_value_set['417']['field_value'],1,1)==1) echo "checked"; ?> >
                    <label class="form-check-label" for="reason-2"><?php echo __("Important Event")?> </label>
                </div>
                <div class="form-check my-2 text-left">
                    <input class="form-check-input" type="checkbox" value="1" id="reason-3" disabled="true" <?php if(substr($field_value_set['417']['field_value'],2,1)==1)  echo "checked"; ?> >
                    <label class="form-check-label" for="reason-3"><?php echo __("Others")?> </label>
                    <?php echo "<input type=\"hidden\" id=\"id_otherReason_id\" disabled name=\"field_value[420][id]\" value=\"".$field_value_set['420']['id']."\">";?>
                    <textarea class="form-control" id="otherReason" rows="3" <?php if(substr($field_value_set['417']['field_value'],-1)!=1) echo "style=\"display:none;\" disabled"; ?> name="field_value[420][value]">
                    <?= $field_value_set['420']['field_value'] ?></textarea>
                </div>
            </div>
            <button type="button" id="selReaBack" class="btn btn-outline-warning my-2 mx-2 w-25"><?php echo __("Back")?></button>
            <button type="button" id="confirmRea" class="btn btn-primary my-2 mx-2 w-25"><?php echo __("Confirm")?></button>
        </div>
    </div>

    <!-- If Valid then choose NO, Prioritize -->
    <div id="prioritize" class="card mx-auto my-3 w-50" style="display:none;">
        <div class="card-header text-center"><h5><?php echo __("Prioritize")?></h5></div>
        <div class="card-body" id="prioritizeContent">
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right"><?php echo __("Seriousness")?></legend>
                <?php echo "<input type=\"hidden\" id=\"id_seriousness_id\" disabled name=\"field_value[421][id]\" value=\"".$field_value_set['421']['id']."\">";?>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-seriousness-1" value="1" name="field_value[421][value]" <?php if($field_value_set['421']['field_value']==1) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-seriousness-1"><?php echo __("Fatal / Life Threatening")?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-seriousness-2" value="2" name="field_value[421][value]" <?php if($field_value_set['421']['field_value']==2) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-seriousness-2"><?php echo __("Other Serious")?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-seriousness-3" value="3" name="field_value[421][value]" <?php if($field_value_set['421']['field_value']==3) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-seriousness-3"><?php echo __("Serious / Spontaneous")?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-seriousness-4" value="4" name="field_value[421][value]" <?php if($field_value_set['421']['field_value']==4) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-seriousness-4"><?php echo __("Non Serious")?></label>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right"><?php echo __("Related")?></legend>
                <?php echo "<input type=\"hidden\" id=\"id_related_id\" disabled name=\"field_value[422][id]\" value=\"".$field_value_set['422']['id']."\">";?>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-related-1" value="1" name="field_value[422][value]" <?php if($field_value_set['422']['field_value']==1) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-related-1"><?php echo __("Yes")?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-related-2" value="2" name="field_value[422][value]" <?php if($field_value_set['422']['field_value']==2) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-related-2"><?php echo __("No")?></label>
                    </div>
                </div>
            </div>
            <div class="row my-2">
                <legend class="col-form-label col-sm-4 pt-0 text-right"><?php echo __("Unlabelled")?></legend>
                <?php echo "<input type=\"hidden\" disabled id=\"id_unlabelled_id\" name=\"field_value[423][id]\" value=\"".$field_value_set['423']['id']."\">";?>
                <div class="col-sm-8 text-left">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-unlabelled-1" value="1" name="field_value[423][value]" <?php if($field_value_set['423']['field_value']==1) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-unlabelled-1"><?php echo __("Yes")?></label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="prioritize-unlabelled-2" value="2" name="field_value[423][value]" <?php if($field_value_set['423']['field_value']==2) echo "checked"; ?> disabled>
                        <label class="form-check-label" for="prioritize-unlabelled-2"><?php echo __("No")?></label>
                    </div>
                </div>
            </div>
            <input type="hidden" disabled id="id_case_type_id" name="field_value[500][id]" <?php echo "value=\"".$field_value_set['500']['id']."\""?>>
            <input type="hidden" disabled id="id_case_type_value" name="field_value[500][value]" <?php echo "value=\"".$field_value_set['500']['field_value']."\""?>>
            <?php echo "<input type=\"hidden\" id=\"submissionDate_id\" name=\"field_value[415][id]\" disabled value=\"".$field_value_set['415']['id']."\">";?>
            <?php echo "<input type=\"hidden\" id=\"submissionDate_value\" name=\"field_value[415][value]\" disabled value=\"".$field_value_set['415']['field_value']."\">";?>
            <div id="prioritizeType"></div>
            <button type="button" id="prioritizeBack" class="btn btn-outline-warning my-2 mx-2 w-25"><?php echo __("Back")?></button>
            <div class="btn btn-outline-danger mx-1" title="Sign Off"data-toggle="modal" data-target=".signOff" onclick="endTriage()"><?php echo __("End Triage")?></div>
        </div>
    </div>
    <div class="modal fade signOff" tabindex="-1" role="dialog" aria-labelledby="signOff" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div id="action-text-hint"></div>
            </div>
        </div>
    </div>
    <input name="endTriage" type="hidden" value="1" disabled>
            <?= $this->Form->end();?>
  </div>
</div>
<div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document" style="max-width: 1175px !important;">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id=""><?php echo __("MedDRA Browser")?> (<?php echo __("Version")?>: MedDRA 18.1)</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
            <!-- Search field -->
            <div class="container">
                <div class="form-row mx-2">
                    <!-- <div class="form-group col-md-4">
                        <label>Search LLT Term</label>
                        <input type="text" class="form-control" id="llt_term"  placeholder="Search LLT Term">
                    </div>
                    <div class="form-group col-md-4">
                        <label>Search PT Term</label>
                        <input type="text" class="form-control" id="pt_term"  placeholder="Search PT Term">
                    </div> -->
                    <div class="form-group col-md-4">
                        <label><?php echo __("SMQ")?>:</label>
                        <input type="text" class="form-control" id="wildcard_search"  placeholder="Search by SMQ">
                    </div>
                    <div class="form-group">
                        <label for="meddra-full_text"><?php echo __("Full Text Search")?></label>
                        <input type="checkbox" class="form-control" id="meddra-full_text">
                    </div>
                </div>
                <!-- <div class="form-row justify-content-center">
                    <div class="form-group col-sm-3">
                        <div id="meddrasea" onclick="searchMedDra()" class="form-control btn btn-primary w-100"><i class="fas fa-search"></i> Search</div>
                    </div>
                    <div class="form-group col-sm-1">
                        <div class="clearsearch form-control btn btn-outline-danger w-100"><i class="fas fa-eraser"></i> Clear</div>
                    </div>
                </div> -->
            </div>
            <div class="container mb-5">
                    <div class="form-row justify-content-around">
                        <div class="form-group col-md-2">
                            <label for=""><?php echo __("SOC Name")?></label>
                            <input type="text" class="form-control" id="typed-soc-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for=""><?php echo __("HLGT Name")?></label>
                            <input type="text" class="form-control" id="typed-hlgt-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for=""><?php echo __("HLT Name")?></label>
                            <input type="text" class="form-control" id="typed-hlt-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for=""><?php echo __("PT Name")?></label>
                            <input type="text" class="form-control" id="typed-pt-name">
                        </div>
                        <div class="form-group col-md-2">
                            <label for=""><?php echo __("LLT Name")?></label>
                            <input type="text" class="form-control" id="typed-llt-name">
                        </div>
                    </div>
                    <div style="height: 300px;" class="form-row justify-content-around">
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-soc_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-hlgt_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-hlt_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-pt_name"></div>
                        <div class="form-group border h-100 col-md-2 overflow-auto" id="field-llt_name"></div>
                    </div>
            </div>
            <!-- Table field (Should be hidden before search) -->
            <h4 class="text-center"><?php echo __("MedDra Details")?></h4> <hr>
            <div class="container">
                <fieldset disabled>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for=""><?php echo __("LLT Name")?></label>
                            <input type="text" class="form-control" id="select-llt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for=""><?php echo __("LLT Code")?></label>
                            <input type="text" class="form-control" id="select-llt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for=""><?php echo __("PT Name")?></label>
                            <input type="text" class="form-control" id="select-pt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for=""><?php echo __("PT Code")?></label>
                            <input type="text" class="form-control" id="select-pt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for=""><?php echo __("HLT Name")?></label>
                            <input type="text" class="form-control" id="select-hlt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for=""><?php echo __("HLT Code")?></label>
                            <input type="text" class="form-control" id="select-hlt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for=""><?php echo __("HLGT Name")?></label>
                            <input type="text" class="form-control" id="select-hlgt-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for=""><?php echo __("HLGT Code")?></label>
                            <input type="text" class="form-control" id="select-hlgt-c">
                        </div>
                    </div>
                    <div class="form-row justify-content-center">
                        <div class="form-group col-md-4">
                            <label for=""><?php echo __("SOC Name")?></label>
                            <input type="text" class="form-control" id="select-soc-n">
                        </div>
                        <div class="form-group col-md-3 offset-md-1">
                            <label for=""><?php echo __("SOC Code")?></label>
                            <input type="text" class="form-control" id="select-soc-c">
                        </div>
                    </div>
                </fieldset>
            </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="meddraSelectBtn" class="btn btn-success w-25 mx-auto"  onclick="selectMeddraButton()" data-dismiss="modal"><?php echo __("Select")?></button>
      </div>
    </div>
  </div>
</div>
