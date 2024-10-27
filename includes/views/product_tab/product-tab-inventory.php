<div class="tpmd">

    <div class="inside">
    <p class="form-field tpmd-status">
        <?php $this->create_select( 'TrustedPMDealers status', 'status', $product, $attr, true ); ?>
        <?php
        if ( isset( $this->err_fields['status'] ) ) :
            echo '<span class="tpmd-error-message">' . $this->err_fields['status'][0] . '</span>';
        endif;
        ?>
     </p>
  </div>

</div>
