<?= $this->Html->script('addDocument/addDocument.js') ?>
<script>
        var userId = <?= $this->request->getSession()->read('Auth.User.id')?>;
        var csrfToken = <?= json_encode($this->request->getParam('_csrfToken')) ?>;
        var caseId = <?= $caseId ?>;
        var sdDocLists = "<?= $sdDocLists ?>";
</script>
<form method="post" action="/sd-documents/save/<?= $caseId ?>" enctype="multipart/form-data">

    <div style="display:none;">
        <input type="hidden" name="_method" value="POST"/>
        <input type="hidden" name="_csrfToken" autocomplete="off" value=<?= json_encode($this->request->getParam('_csrfToken')) ?>/>
    </div>

    
    <!-- Add Document Modal -->
    <div class="modal fade uploadDoc" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <input type="hidden"  id="hidden_docLists" value="<?= $sdDocLists?>"/>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle"><?php echo __("Upload Documents")?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div><button id="addNewAttach" type="button" class="btn btn-outline-primary mb-3 float-left"><i class="fas fa-folder-plus"></i><?php echo __("Add New")?></button></div>
                    <div class="form-row mb-3 d-block">
                        <table class="doctable table-hover" style="width:100%;">
                            <thead style="line-height:30px;">
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
                            <th scope="row"><input class="form-control" name="doc_classification_0" id="doc_classification_0" type="text"></th>
                            <td><input class="form-control" name="doc_description_0" id="doc_description_0" type="text"></td>
                            <td><select class="custom-select" name="doc_source_0" id="doc_source_0">
                                <option value="File Attachment"><?php echo __("File Attachment")?></option>
                                <option value="URL Reference"><?php echo __("URL Reference")?></option>
                                </select>
                                </td>
                            <td><input class="form-control" style="display:none;" name="doc_path_0" id="doc_path_0" type="text">
                            <input name="doc_attachment_0" id="doc_attachment_0" type="file"></td>                                    
                            <td><button type="button" class="btn btn-outline-danger btn-sm my-1 w-100 attachDel"><?php echo __("Delete")?></button></td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary mx-2" onclick=""><?php echo __("Upload Files")?></button>
                </div>
            </div>
        </div>
    </div>
</form>
