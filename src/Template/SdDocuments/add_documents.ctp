<head>
<title><?= __('Attachments and References') ?></title>
<?= $this->Html->script('addDocument/addDocument.js') ?>
    <script>
        var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
        var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
    </script>
<head>

<body>
    <form method="post" action="/sd-documents/save/<?= $case_id ?>" enctype="multipart/form-data">

    <div style="display:none;">
        <input type="hidden" name="_method" value="POST"/>
        <input type="hidden" name="_csrfToken" autocomplete="off" value=<?= json_encode($this->request->getParam('_csrfToken')) ?>/>
    </div>

    <div class="card text-center mx-auto my-3" style="width:85%;">
        <div class="card-header">
            <h4><?php echo __("Attachments and References");?></h4>
        </div>
        <div class="card-body">
            <!-- Add Document Modal -->
            <button type="button" class="btn btn-primary d-block" data-toggle="modal" data-target=".uploadDoc"><i class="fas fa-cloud-upload-alt"></i> <?php echo __("Upload Documents");?> </button>
            <div class="modal fade uploadDoc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalCenterTitle"><?php echo __("Upload Documents");?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <button id="addNewAttach" type="button" class="btn btn-outline-primary mb-3 float-left"><i class="fas fa-folder-plus"></i> <?php echo __("Add New");?> </button>
                            <div class="form-row mb-3 d-block">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th scope="col"><?php echo __("Classification");?></th>
                                            <th scope="col"><?php echo __("Description");?></th>
                                            <th scope="col"><?php echo __("Type");?></th>
                                            <th scope="col"><?php echo __("File/Reference");?></th>
                                            <th scope="col"><?php echo __("Action");?></th>
                                        </tr>
                                    </thead>
                                    <tbody id="newAttachArea">
                                    <tr>
                                    <th scope="row"><input class="form-control" name="doc_classification_0" id="doc_classification_0" type="text"></th>
                                    <td><input class="form-control" name="doc_description_0" id="doc_description_0" type="text"></td>
                                    <td><select class="custom-select" name="doc_source_0" id="doc_source_0">
                                        <option value="File Attachment"><?php echo __("File Attachment");?></option>
                                        <option value="URL Reference"></option>
                                        </select>
                                        </td>
                                    <td><input class="form-control" style="display:none;" name="doc_path_0" id="doc_path_0" type="text">
                                    <input name="doc_attachment_0" id="doc_attachment_0" type="file"></td>
                                    <td><button type="button" class="btn btn-outline-danger btn-sm my-1 w-100 attachDel"><?php echo __("Delete");?></button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary mx-2" onclick=""><?php echo __("Upload Files");?></button>
                        </div>
                    </div>
                </div>
            </div>

            <div id="showDocList">
                <!-- <h5>Document List</h5> -->
                <table id="docTable" class="table table-striped table-bordered table-hover dataTable w-100" role="grid">
                <thead>
                <tr style="cursor: pointer;" role="row">
                <th class="align-middle sorting_asc" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-sort="ascending" aria-label="ID: activate to sort column descending"><?php echo __("ID");?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Classification: activate to sort column ascending"><?php echo __("Classification");?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Description: activate to sort column ascending"><?php echo __("Description");?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Source: activate to sort column ascending"><?php echo __("Source");?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Doc Name: activate to sort column ascending"><?php echo __("Doc Name");?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Doc Size: activate to sort column ascending"><?php echo __("Doc Size");?></th>
                <th class="align-middle sorting" scope="col" tabindex="0" aria-controls="docTable" rowspan="1" colspan="1" aria-label="Created User: activate to sort column ascending"><?php echo __("Uploaded By");?></th></tr>
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
        </div>
    </div>

</body>
<!--.page-content-->