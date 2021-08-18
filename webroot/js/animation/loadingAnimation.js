jQuery(function($) {
    $(function(){
        // Login and select company screen
        $(':submit, a.flag').click(function (){
            $(this).append(` <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>`);
        });

        // Global loading animation
        $('.loadingAni').click(function (){
            $('body').html(
                `<div style="display: flex;  height: 770px; flex-direction: column; justify-content: center; align-items: center;">
                    <img style="width: 12rem; display: block; margin: 5rem auto;" src="/img/logo-mds.png" alt="G2-MDS loading request" />
                    <div class="spinner-border text-primary mainLoadingSpinner" role="status">
                        <span class="visually-hidden"></span>
                    </div>
                    <h3 class="mainLoadingTxt">G2-MDS is loading your request ...</h3>
                </div>`);
        });
    });
});
