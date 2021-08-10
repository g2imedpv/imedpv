//js codes for showing up used fields
$(document).ready(function() {
    $('#lineListTable').DataTable( {
        dom: 'Blfrtip',
        buttons: [
            'columnsToggle',
            {
                extend: 'excelHtml5',
                text:      '<i class="far fa-file-excel"></i> Export to Excel',
                titleAttr: 'Excel',
                className: 'btn btn-success btn-lg mx-2',
                messageTop: 'The information in this table is copyright to  G2 Biopharma Services Inc.',
                title: 'G2-MDS Line listing data export',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="far fa-file-pdf"></i> Export to PDF',
                titleAttr: 'PDF',
                className: 'btn btn-success btn-lg mx-2',
                messageTop: 'The information in this table is copyright to  G2 Biopharma Services Inc.',
                title: 'G2-MDS Line listing data export',
                orientation: 'landscape',
                download: 'open',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });

    $(".buttons-columnVisibility").addClass("btn btn-outline-primary m-2").attr("data-bs-toggle","button");
    $(".dt-buttons").addClass("mb-2");
    $(".dataTables_info, .dataTables_length").addClass("float-left");

});
