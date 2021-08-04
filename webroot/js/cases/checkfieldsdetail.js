//js codes for showing up used fields
$(document).ready(function() {
    $('#lineListTable').DataTable( {
        dom: 'Bfrtip',
        buttons: [
                    {
                        collectionTitle: 'Visibility control',
                        extend: 'colvis',
                        collectionLayout: 'two-column'
                    }
                ]
            }
    );
});