<div class='reveal-modal blocking-modal api-key-modal' id='failed_to_create_api_key'>
  <h4><?php echo sprintf(__('Setup Shareaholic', 'shareaholic')); ?></h4>
  <div class="content pal" style="padding:10px;">
    <p>
        <?php _e('It appears that we are having some trouble setting up Shareaholic for WordPress right now. This is usually temporary. Please revisit this section after a few minutes or click "retry" now.', 'shareaholic'); ?>
    </p>
    <a id='get_started' class="btn_main" style="margin-top: 15px;" href=''><?php echo _e('Retry', 'shareaholic'); ?></a>
    <br />
    <span style="font-size:12px; font-weight:normal;">
      <a href='<?php echo admin_url() ?>'><?php _e('or, try again later.', 'shareaholic'); ?></a>
    </span>
    <br /><br />
    <span style="font-size:11px; font-weight:normal;">
      <?php echo sprintf(__('If you continue to get this prompt for more than a few minutes, try to check Shareaholic server connectivity or reset the plugin. Both are available under %sadvanced settings%s.', 'shareaholic'), '<a href="admin.php?page=shareaholic-advanced">', '</a>'); ?> <?php echo sprintf(__('Also, if you have a question or have a bug to report, please %slet us know%s.', 'shareaholic'), '<a href="#" onclick="SnapEngage.startLink();">','</a>'); ?>
    </span>
  </div>
</div>

