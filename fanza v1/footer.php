<?php if (is_active_sidebar('left-footer')) : ?>

    <footer id="footer">
        <div class="inner">

          

            <?php dynamic_sidebar('left-footer'); ?>
        </div>
    </footer>
<?php endif; ?>

</div><!--#wrapper-->
<div id="follow-popup" style="display: none">
    <div class="popup-follow-inner">
        <div class="popup-main">
            <a href="javascript:void(0)" class="follow-close"><i class="fa fa-close"></i></a>
            <div class="popup-desc">
                <p>Please <a href="<?= site_url('/login'); ?>">Login</a> or <a href="<?= site_url('/join-us'); ?>">Sign Up</a></p>
            </div>
        </div>
    </div>
</div>


<!-- comment code for loader -->
<!-- <div class="loader"><span class="inner-loader"></span></div> -->

<script src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js" strict-dynamic></script>
<script>

jQuery('.tab-content').addClass('show');

jQuery(document).ready(function(){
    jQuery(window).load(function() {
        console.log('complete');
        setTimeout(function(){
            //jQuery(".loader").hide();
            jQuery('.tab-content').removeClass('show');
            jQuery(".single-sp_event .tab-all .tabs-menu li.active").trigger('click');
            jQuery('.page-template-page-no-title .tab-all .tabs-menu li.active').trigger('click');
            jQuery('.tax-sp_league .tab-all .tabs-menu li.active').trigger('click');
            jQuery('.single-sp_team .tab-all .tabs-menu li.active').trigger('click');
            jQuery('.single-sp_event .match-report-tabs .tabs-menu li:first').trigger('click');
            jQuery('.single-sp_event #match-report-tab-contents .tab-content:first').addClass('active');
        }, 7000);
    })
});

jQuery(document).ready(function(){
    jQuery('.adsbygoogle').each(function(){
        (adsbygoogle = window.adsbygoogle || []).push({});
    });    
})


jQuery(document).ajaxComplete(function(){
    jQuery('ins').each(function(){
        (adsbygoogle = window.adsbygoogle || []).push({});
    });
})

</script>
<?php wp_footer(); ?>

</body>
</html>



