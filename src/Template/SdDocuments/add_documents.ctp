<head>
<?= $this->Html->script('cases/triage.js') ?>
<head>
<script>
    var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
    var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
</script>
<form method="post" action="/sd-documents/save/<?= $case_id ?>" enctype="multipart/form-data">

<div style="display:none;">
<input type="hidden" name="_method" value="POST"/>
<input type="hidden" name="_csrfToken" autocomplete="off" value=<?= json_encode($this->request->getParam('_csrfToken')) ?>/>
</div>
<!-- Attachment -->
<h4 class="text-left mt-3">Attachments and References</h4>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Classification<i class="fas fa-asterisk reqField"></i></label>
                <input type="text" class="form-control" name="doc_classification_0" id="doc_classification_0" value="">
            </div>
            <div class="form-group col-md-3">
                <label>Description<i class="fas fa-asterisk reqField"></i></label>
                <input type="text" class="form-control" name="doc_description_0" id="doc_description_0" value="">
            </div>
            <div class="form-group col-md-3">
                <label>File/Reference</label>
                <input type="text" class="form-control" name="doc_path_0" id="doc_path_0" value="" style="display:none">
                <input name="doc_attachment_0" id="doc_attachment_0" type="file"/>
            </div>
            <div class="form-group col-md-3">
            <label>&nbsp;</label>
            <select name="doc_source_0" id="doc_source_0">
            <!--<option value="">Choose the source</option>-->
            <option value="File Attachment">File Attachment</option>
            <option value="URL Reference">URL Reference</option>
            </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-3">
                <label>Classification<i class="fas fa-asterisk reqField"></i></label>
                <input type="text" class="form-control" name="doc_classification_1" id="doc_classification_1" value="">
            </div>
            <div class="form-group col-md-3">
                <label>Description<i class="fas fa-asterisk reqField"></i></label>
                <input type="text" class="form-control" name="doc_description_1" id="doc_description_1" value="">
            </div>
            <div class="form-group col-md-3">
                <label>File/Reference</label>
                <input type="text" class="form-control" name="doc_path_1" id="doc_path_1" value="" style="display:none">
                <input name="doc_attachment_1" id="doc_attachment_1" type="file"/>
            </div>
            <div class="form-group col-md-3">
            <label>&nbsp;</label>
            <select name="doc_source_1" id="doc_source_1">
            <!--<option value="">Choose the source</option>-->
            <option value="File Attachment">File Attachment</option>
            <option value="URL Reference">URL Reference</option>
            </select>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mx-2" onclick="">Upload Files</button>
</form>
<hr>
<div id="showDocList" class="col-sm-10">
<label>Document List</label>
<table id="docTable" class="table table-striped table-bordered table-hover dataTable no-footer" role="grid">
<thead>
<tr style="cursor: pointer;" role="row">
<th class="align-middle sorting_asc" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" style="width: 90px;" aria-sort="ascending" aria-label="ID: activate to sort column descending">ID</th>
<th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" style="width: 58px;" aria-label="Classification: activate to sort column ascending">Classification</th>
<th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" style="width: 76px;" aria-label="Description: activate to sort column ascending">Description</th>
<th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" style="width: 62px;" aria-label="Source: activate to sort column ascending">Source</th>
<th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" style="width: 84px;" aria-label="Doc Name: activate to sort column ascending">Doc Name</th>
<th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" style="width: 84px;" aria-label="Doc Size: activate to sort column ascending">Doc Size</th>
<th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" style="width: 105px;" aria-label="Created User: activate to sort column ascending">Created User</th></tr>
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
        print '<td class="align-middle">'.$sdDoc['doc_classification'].'</td>';
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
<!--.page-content-->