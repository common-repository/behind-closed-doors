<div class="wrap">
  <form method="post" action="options.php">

    <h2><?php echo WP_BCD_SHORT_TITLE; ?></h2>

    <?php
    
    settings_fields( WP_BCD );
    do_settings_sections( WP_BCD );
    submit_button();
    
    ?>
    
  </form>
</div>