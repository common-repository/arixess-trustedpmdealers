<?php
$tpmd_msg = array(
    'save_before_test'  => __('Please, test API key before save', 'tr_pmd_locale'),
    );
echo '<script>';
echo 'var tpmd_message = ' . json_encode( $tpmd_msg ) .';';
echo '</script>';
?>
<div class="wrap tpmd">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <div class="api-key-block">
        <div class="tpmd-loader">
            <div class="tpmd-bar"></div>
        </div>
        <div class="tpmd-notification-bar tpmd-api-success"></div>
        <div class="tpmd-notification-bar tpmd-api-error"></div>
        <div class="api-form-block">
            <form id="tpmd-api-form" method="POST" action="javascript:tpmd_api_save()">
                <label for="tpmd-api-key"><?php echo __('Enter API key here','tr_pmd_locale'); ?></label>
                <input type="text" class="tpmd-input" name="tpmd-api-key" id="tpmd-api-key" value="<?php echo $data->getApiKey(); ?>">
                <input type="button" id="tpmd-btn-api-test" value="<?php echo esc_attr__('Test API Key','tr_pmd_locale'); ?>" class="button button-large">

                <p class="tpmd-tooltip"><span class="tooltip tolltip_url" data-tooltip-content="#tooltip_content">Where to get API
                Key</span></p>
                <div class="tooltip_templates">
                    <span id="tooltip_content">
                        To get your API Key make few steps:<br>
                        1. <a href="https://business.trustedpmdealers.com/sign-in" target="_blank">Login to your TrustedPMDealers account</a><br>
                        2. Go to "Settings" section, tab "General";<br>
                        3. Follow to the API Token field and press "Copy to clipboard" icon to copy your API Key.<br>
                        4. Paste the Key into the field of your Magento plugin here.
                    </span>
                </div>
                <label><?php echo __('Set currency','tr_pmd_locale'); ?></label>
                <select id="tpmd-currency-select" class="tpmd-select">
                <?php if ( $data->getApiCur() ): ?>
                    <?php if ( $attr = $data->getApiAttr() ): ?>
                        <?php foreach ($attr['priceCurrency'] as $cur_item) : ?>
                            <option value="<?php echo $cur_item; ?>" <?php if ($data->getApiCur() == $cur_item) echo 'selected=selected'; ?> >
                                <?php echo $cur_item; ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
                </select>
                <input type="submit" id="tpmd-btn-api-submit" value="<?php echo esc_attr__('Save Settings','tr_pmd_locale'); ?>" class="button button-primary button-large">
                <span class="tpmd_loader"></span>
            </form>
        </div>
    </div>
</div> <!-- //wrap-->
