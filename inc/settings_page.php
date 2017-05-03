<div class="wrap">
    <h1>Categories Video and Image</h1>

    <form method="post" action="options.php">
        <?php settings_fields( 'cvi-group' ); ?>
        <?php do_settings_sections( 'cvi-group' ); ?>
        <table class="form-table">
            <tr valign="top">
                <th scope="row">Youtube API Key</th>
                <td><input type="text" name="youtube_api_key" style="width: 100%" value="<?php echo esc_attr( get_option('youtube_api_key') ); ?>" /></td>
            </tr>

            <tr valign="top">
                <th scope="row">Vimeo Access Tokken</th>
                <td><input type="text" name="vimeo_api_key" style="width: 100%" value="<?php echo esc_attr( get_option('vimeo_api_key') ); ?>" /></td>
            </tr>

        </table>
        <?php submit_button(); ?>

    </form>
</div>