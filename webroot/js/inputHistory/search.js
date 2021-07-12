//For search feature in input histories
jQuery(function($) {
    $(document).ready(searchHistory());

    function searchHistory(){
        const request = {
            'tab_name':$("tab_name").val(),
            'section_name':$("#section_name").val(),
            'user_name':$("#user_name").val(),
            'modified_date_start':$("#modified_date_start").val(),
            'modified_date_end':$("#modified_date_end").val(),
        };
        $.ajax({
            headers: {
                'X-CSRF-Token': csrfToken
            },
            type:'POST',
            url:'/sd-input-histories/search/'+caseId,
            data:request,
            success:function(response){
                const result = JSON.parse(response);
                console.log('result :>> ', result);

                const text = `
                    <table class="table table-hover table-bordered" id="search_result">
                        <thead>
                            <tr>
                                <th scope="col"> ${i18n.gettext("Field Name")} </th>
                                <th scope="col"> ${i18n.gettext("User Name")} </th>
                                <th scope="col"> ${i18n.gettext("User Email")} </th>
                                <th scope="col"> ${i18n.gettext("Activity Name")} </th>
                                <th scope="col"> ${i18n.gettext("Input Data")} </th>
                                <th scope="col"> ${i18n.gettext("Modified Time")} </th>
                            </tr>
                        </thead>
                        <tbody>
                        ${result.map(caseDetail =>
                                `<tr>
                                    <td> ${caseDetail.field.field_label} </td>
                                    <td> ${caseDetail.user.firstname} ${caseDetail.user.lastname} </td>
                                    <td> ${caseDetail.user.email} </td>
                                    <td> ${caseDetail.activity.activity_name} </td>
                                    <td> ${caseDetail.input} </td>
                                    <td> ${caseDetail.time_changed.slice(0,19).replace(/T/, " ")} </td>
                                </tr>`
                            ).join('')
                        }
                        </tbody>
                    </table>`

                $("#searchInputHistoryList").html(text);
                $('#search_result').DataTable();
            },
            error:function(response){
                console.log('error', response.responseText);
                $("#searchInputHistoryList").html(i18n.gettext("Sorry, no case matches"));
            }
        });
    }
});