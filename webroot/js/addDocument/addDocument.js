$(document).ready(function(){

    $('#docTable').DataTable();

    $(function(){
        function fileUrlSwitcher () {
            $('[id^=doc_source]').each(function(s,v){
                //console.log($(this));
                $(this)
                .change(function () {
                    if ($( "#doc_source_"+s+" option:selected" ).val() == 'File Attachment')
                    {
                        $('#doc_attachment_'+s).show();
                        $('#doc_path_'+s).hide();
                    }
                    else if ($( "#doc_source_"+s+" option:selected" ).val() == 'URL Reference')
                    {
                        $('#doc_attachment_'+s).hide();
                        $('#doc_path_'+s).show();
                    }

                })
            });
        };

        var attachCount = 0;
        $('#addNewAttach').click(function(){
            var newattach = "";

            newattach += "<tr>";
                newattach += "<th scope=\"row\">";
                    newattach += "<input type=\"text\" class=\"form-control\" name=\"doc_classification_" + attachCount + "\" id=\"doc_classification_" + attachCount + "\">";
                newattach += "</th>";
                newattach += "<td>";
                    newattach += "<input type=\"text\" class=\"form-control\" name=\"doc_description_" + attachCount + "\" id=\"doc_description_" + attachCount + "\">";
                newattach += "</td>";
                newattach += "<td>";
                    newattach += "<select class=\"custom-select\" name=\"doc_source_" + attachCount + "\" id=\"doc_source_" + attachCount + "\">";
                        newattach += "<option value=\"File Attachment\">File Attachment</option>";
                        newattach += "<option value=\"URL Reference\">URL Reference</option>";
                    newattach += "</select>";
                newattach += "</td>";
                newattach += "<td>";
                    newattach += "<input type=\"text\" class=\"form-control\" style=\"display:none;\" name=\"doc_path_" + attachCount + "\" id=\"doc_path_" + attachCount + "\">";
                    newattach += "<input type=\"file\" name=\"doc_attachment_" + attachCount + "\" id=\"doc_attachment_" + attachCount + "\">";
                newattach += "</td>";
                newattach += "<td>";
                    newattach += "<button type=\"button\" class=\"btn btn-outline-danger btn-sm my-1 w-100 attachDel\">Delete</button>";
                newattach += "</td>";
            newattach += "</tr>";

            $('#newAttachArea').append(newattach);
            attachCount++;

            fileUrlSwitcher();

            // Delete row button
            $('.attachDel').click(function(){
                $(this).parent().parent().remove();
            });

        });
    });
});