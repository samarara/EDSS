<?php
/**
*
* admin/partials/wp-cbf-admin-display.php - Don't add this comment
*
**/
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">

    <h2><?php echo esc_html(get_admin_page_title()); ?></h2>
    
    <form method="post" name="cleanup_options" action="options.php">
    <?php
        //Grab all options
        $options = get_option($this->plugin_name);
        // Cleanup
        $api_key = isset($options['api_key']) ? $options['api_key'] : null;
    ?>
    <?php
        settings_fields($this->plugin_name);
        do_settings_sections($this->plugin_name);
    ?>

    
        <!-- remove some meta and generators from the <head> -->
        <fieldset>
            <legend class="screen-reader-text"><span><?php _e('Your Line-Up API Key', $this->plugin_name); ?></span></legend>
            <label for="<?php echo $this->plugin_name; ?>-api_key">API Key</label>
            <input type="text" class="regular-text" id="<?php echo $this->plugin_name; ?>-api_key" name="<?php echo $this->plugin_name; ?>[api_key]" value="<?php if(!empty($api_key)) echo $api_key; ?>"/>
        </fieldset>

        <?php submit_button('Save all changes', 'primary','submit', TRUE); ?>

    </form>

</div>