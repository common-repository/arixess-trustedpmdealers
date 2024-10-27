<p class="form-field tpmd-<?php echo $filed_name ?>">
    <label class="<?php if ( $required ) : ?>required<?php endif; ?>"><?php _e( $label, 'tr_pmd_locale' ) ?></label>
    <input class="input <?php echo $input_class; ?><?php if ( isset( $this->err_fields[ $filed_name ] ) ) { echo ' tpmd-error-field'; } ?>"
           type="text" name="tpmd-<?php echo $filed_name; ?>" id="id-<?php echo $filed_name; ?>-tpmd"
    <?php
    $val = ( $product  && $product->get_field( $filed_name ) != 0  ) ? $product->get_field( $filed_name ) : '';
    echo 'value ="' . $val . '"';
    echo ' maxlength="' . $maxlenght . '"/>';
    if ( isset( $this->err_fields[ $filed_name ] ) ) :
        echo '<span class="tpmd-error-message">' . $this->err_fields[$filed_name][0] . '</span>';
    endif;
    ?>
</p>