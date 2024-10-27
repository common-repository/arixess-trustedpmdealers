<p class="form-field tpmd-<?php echo $filed_name ?>">
    <label for="tpmd-<?php echo $filed_name ?>"
           class="<?php if ( $required ) echo 'required'; ?>"><?php _e( $label, 'tr_pmd_locale' ); ?></label>
    <select id="tpmd-<?php echo $filed_name ?>" name="tpmd-<?php echo $filed_name ?>"
            class="select short <?php if ( isset( $this->err_fields[ $filed_name ] ) ) : echo 'tpmd-error-field'; endif; ?>">
        <option></option>
    >
     <?php foreach ( $attr[ $filed_name ] as $id=>$item ) : ?>
        <option value="<?php echo $id ?>"
            <?php if ( $product && $product->get_field( $filed_name ) == $id ) : ?>
                selected="selected"
            <?php endif; ?>
            >
            <?php echo $item ?></option>
        <?php endforeach; ?>
    </select>
    <?php
    if ( isset( $this->err_fields[ $filed_name ] ) ) :
        echo '<span class="tpmd-error-message">' . $this->err_fields[ $filed_name ][0] . '</span>';
    endif;
    ?>
</p>