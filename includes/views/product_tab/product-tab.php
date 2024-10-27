<?php
echo '<script>';
echo 'var tpmd_purity_obj = { "1": [' . $this->create_js_obj('purity', '1', $attr) . '], "2": [' . $this->create_js_obj('purity', '2', $attr) . '], "3":[' . $this->create_js_obj('purity', '3', $attr) . '],"4":[' . $this->create_js_obj('purity', '4', $attr) . '] }; ';
echo '</script>';
?>

<div id="<?php echo $this->target_tab ?>" class="panel woocommerce_options_panel tpmd">
    <div class="options_group postbox">
        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text"></span><span class="toggle-indicator" aria-hidden="true"></span></button>
        <h2 class=" ui-sortable-handle"><span><?php _e( 'Common', 'tr_pmd_locale' ); ?></span></h2>
        <div class="inside">
            <p class="form-field tpmd-productId">
                <label for="tpmd-productId" class="required"><?php _e( 'Product ID (SKU)', 'tr_pmd_locale' ); ?></label>
                <?php
                if($product){
                    $product_value = ($product->get_field( 'newProductId' ) != '')? $product->get_field( 'newProductId' ): $product->get_field( 'productId' );
                }else{
	                $product_value = '';
                }
                         ?>
                <?php $rest_uploaded = ( $product && $product->get_field( 'rest_uploaded' ) ) ? 'readonly' : '' ?>
                <input class="input <?php if ( isset( $this->err_fields['productId'] ) ) { echo ' tpmd-error-field'; } ?>"
                       type="text" name="tpmd-productId" id="id-productId-tpmd" value="<?php echo $product_value ?>" <?php echo $rest_uploaded ?>>
                <?php if ( isset( $this->err_fields['productId'] ) ) :
                    echo '<span class="tpmd-error-message">' . $this->err_fields['productId'][0] . '</span>';
                endif; ?>
            </p>
            <input type="hidden" name="tpmd-oldProductId" value="<?php echo $product_value; ?>">
            <p class="form-field tpmd-name">
                <label class="required">Name</label>

	            <?php
	            if($product){
		            $product_name = $product->get_field( 'name' );
	            }else{
		            $product_name = '';
	            }
	            ?>
                <input class="input " type="text" name="tpmd-name" id="id-name-tpmd" value="<?php echo $product_name;?>" maxlength="255"></p>

            <?php $this->create_select( 'Format', 'format', $product, $attr, true ); ?>
            <?php $this->create_select( 'Volume', 'volume', $product, $attr, true ); ?>
            <p class="form-field tpmd-category">
    <!--            <label for="tpmd-category">--><?php //_e( 'Category', 'tr_pmd_locale' ); ?><!--</label>-->
            </p>
            <div id="tpmd-category" class="chosentree"></div>
            <?php $this->create_select( 'Condition', 'condition', $product, $attr );  ?>
        </div>
    </div>
    <div class="options_group postbox">
        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text"></span><span class="toggle-indicator" aria-hidden="true"></span></button>
        <h2 class=" ui-sortable-handle"><span><?php _e( 'Metal', 'tr_pmd_locale' ); ?></span></h2>
        <div class="inside">

        <?php $this->create_select( 'Metal', 'metal', $product, $attr, true ); ?>

        <p class="form-field tpmd-purity">
            <label for="tpmd-purity" class="required"><?php _e( 'Purity', 'tr_pmd_locale' ); ?></label>
            <select id="tpmd-purity" name="tpmd-purity" class="select short">
                <option></option>
                <?php
                $metal_list = array( 1, 2, 3, 4 );
                if ( $product ) {
                    $metal = $product->get_field( 'metal' );
                } else {
                    $metal = null;
                }
                if ( in_array( $metal, $metal_list ) ) :
                    foreach ( $attr['purity'][ $metal ] as $key => $item ) : ?>
                        <option value="<?php echo $key . '" ';
                        if ( $product->get_field( 'purity' )  == $key ) {
                            echo ' selected="selected"';
                        } ?>
                        >
                        <?php echo $item ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
            <?php if ( isset( $this->err_fields['purity'] ) ) :
	                        echo '<span class="tpmd-error-message">' . $this->err_fields['purity'][0] . '</span>';
                        endif; ?>
        </p>
        <div class='twoBlock'>
            <?php $this->create_input( 'Weight', 'weight', $product, 'decimal-4' ); ?>
            <?php $this->create_select( 'Weight Measurement', 'weightMeasurement', $product, $attr ); ?>
        </div>
        <div class='twoBlock'>
            <?php $this->create_input( 'Precious Metal Weight', 'preciousMetalWeight', $product, 'decimal-4' ); ?>
            <?php $this->create_select( 'Precious Weight Measurement', 'preciousMetalWeightMeasurement', $product, $attr ); ?>
        </div>
      </div>
    </div>
    <div class="options_group postbox">
        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text"></span><span class="toggle-indicator" aria-hidden="true"></span></button>
        <h2 class=" ui-sortable-handle"><span><?php _e( 'Production', 'tr_pmd_locale' ); ?></span></h2>
        <div class="inside">

        <p class="form-field tpmd-yearType">
            <label class="required" for="tpmd-yearType required"><?php _e('Year type', 'tr_pmd_locale'); ?></label>
            <?php
            $year_type_error = isset( $this->err_fields['yearType'] ) ? ' tpmd-error-field' : '';
            $year_type_value = ( $product ) ? $product->get_field( 'yearType') : '';
            ?>
            <select id="tpmd-yearType" name="tpmd-yearType" class="select short<?php echo $year_type_error; ?>">
                <?php foreach ( $attr['yearType'] as $key=>$item ) : ?>
                    <option value="<?php echo $key ?>"
                        <?php if ( $product && $product->get_field( 'yearType' ) == $key ) : ?>
                            selected="selected"
                        <?php endif; ?>
                        >
                        <?php echo $item ?></option>
                <?php endforeach; ?>
            </select>
            <?php
            $year_value_error = isset( $this->err_fields['yearValue'] ) ? ' tpmd-error-field' : '';
            $year_value_from = ( $product && \is_array( $product->get_field( 'yearValue' ) ) ) ? $product->get_field( 'yearValue' )[0] : '';
            $year_value_to = ( $product && \is_array( $product->get_field( 'yearValue' ) ) ) ? $product->get_field( 'yearValue' )[1] : '';
            $year_value = ( $product && ! \is_array( $product->get_field( 'yearValue' ) ) ) ? $product->get_field( 'yearValue' ) : '';
            ?>
            <input class="input<?php echo $year_value_error; ?>" value="<?php echo $year_value_from; ?>" type="number" name="tpmd-yearValue[]" id="tpmd-years-from" placeholder="From" maxlength="4" />
            <input class="input<?php echo $year_value_error; ?>" value="<?php echo $year_value_to; ?>" type="number" name="tpmd-yearValue[]" id="tpmd-years-to" placeholder="To" maxlength="4" />
            <input class="input<?php echo $year_value_error; ?>" value="<?php echo $year_value; ?>" type="number" name="tpmd-yearValue" id="tpmd-years-single" maxlength="4"/>
            <?php
            if ( isset( $this->err_fields['yearType'] ) ) : ?>
                <span class="tpmd-error-message"><?php echo $this->err_fields['yearType'][0] ?></span>
            <?php endif; ?>
        </p>
        <?php $this->create_select('Production Type', 'productionType', $product, $attr); ?>
        <?php $this->create_select('Strike Type', 'strikeType', $product, $attr); ?>
        <?php $this->create_select('Mint', 'mint', $product, $attr); ?>
        <?php $this->create_select('Mint Country', 'mintCountry', $product, $attr); ?>
         <p class="form-field tpmd-mintState">
            <label for="tpmd-mintState"><?php _e('Mint State', 'tr_pmd_locale'); ?></label>
            <?php
            $mint_state_error = isset( $this->err_fields['mintState'] ) ? ' tpmd-error-field' : '';
            if($product->get_field( 'mintCountry' ) != ''){
	            $mint_state_value = $attr['mintState'][$product->get_field( 'mintCountry' )][$product->get_field( 'mintState' )];
            }else{
	            $mint_state_value =  '';
            }
            //$mint_state_value = ( $product ) ? $product->get_field( 'mintState' ) : '';
            ?>
            <select id="tpmd-mintState" name="tpmd-mintState" class="select short<?php echo $mint_state_error; ?>">
                <option value="<?php echo $product->get_field( 'mintState' ); ?>"><?php echo $mint_state_value; ?></option>
            </select>
             <?php
             if ( isset( $this->err_fields['mintState'] ) ) : ?>
                 <span class="tpmd-error-message"><?php echo $this->err_fields['mintState'][0] ?></span>
             <?php endif; ?>
        </p>

	        <?php
	        if($product){
		        $product_mintMark = $product->get_field( 'mintMark' );
	        }else{
		        $product_mintMark = '';
	        }
	        ?>
            <p class="form-field tpmd-mintMark">
                <label class="">Mint Mark</label>
                <input class="input " type="text" name="tpmd-mintMark" id="id-mintMark-tpmd" value="<?php echo $product_mintMark;?>" maxlength="255">
            </p>
        </div>
    </div>
    <div class="options_group postbox">
        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text"></span><span class="toggle-indicator" aria-hidden="true"></span></button>
        <h2 class=" ui-sortable-handle"><span>Certification</span></h2>
        <div class="inside">
        <?php $this->create_select('Grading Service', 'gradingService', $product, $attr); ?>
        <p class="form-field tpmd-grade">
            <label for="tpmd-grade"><?php _e('Grade', 'tr_pmd_locale'); ?></label>
            <?php
            $grade_error = isset( $this->err_fields['grade'] ) ? ' tpmd-error-field' : '';
            if($product->get_field( 'gradingService' ) != ''){
	            $grade_value = $attr['grade'][$product->get_field( 'gradingService' )][$product->get_field( 'grade' )];
            }else{
	            $grade_value =  '';
            }

            //echo json_encode($attr['grade']['2']);
            ?>
            <select id="tpmd-grade" name="tpmd-grade" class="select short<?php echo $grade_error; ?>">
                <option  value="<?php echo $product->get_field( 'grade' ); ?>"><?php echo $grade_value; ?></option>
            </select>
            <?php
            if ( isset( $this->err_fields['grade'] ) ) : ?>
                <span class="tpmd-error-message"><?php echo $this->err_fields['grade'][0] ?></span>
            <?php endif; ?>
        </p>
    </div>
    </div>
    <div class="options_group postbox">
        <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text"></span><span class="toggle-indicator" aria-hidden="true"></span></button>
        <h2 class=" ui-sortable-handle"><span>Appearance</span></h2>
        <div class="inside">
        <?php $this->create_select( 'Special Feature/Attribute', 'specialFeature', $product, $attr ); ?>
        <?php $this->create_select( 'Damage', 'damage', $product, $attr ); ?>
        <?php $this->create_input( 'Thickness', 'thickness', $product, 'decimal-2' ); ?>
        <?php $this->create_input( 'Diameter', 'diameter', $product, 'decimal-2' ); ?>
        <?php $this->create_input( 'Face Value', 'faceValue', $product, 'decimal-2' ); ?>
        <?php $this->create_select( 'Orientation', 'orientation', $product, $attr ); ?>
        <?php $this->create_select( 'Shape', 'shape', $product, $attr ); ?>
        <?php $this->create_select( 'Edge', 'edge', $product, $attr ); ?>
        <?php $this->create_select( 'Rim', 'rim', $product, $attr ); ?>
    </div>
    <div class="options_group">
         <input type="hidden" name="tpmd-productUrl" id="id-productUrl-tpmd"  value="<?php if ( $product ) echo $product->get_field( 'productUrl' ); ?>"/>
         <input type="hidden" name="tpmd-images[]" id="id-image-url-1-tpmd"  value="<?php if ( $product ) echo $product->get_field( 'images' )[0]; ?>"/>
         <input type="hidden" name="tpmd-images[]" id="id-image-url-2-tpmd"  value="<?php if ( $product ) echo $product->get_field( 'images' )[1]; ?>"/>
        <p class="form-field">
            <?php
            if ( isset( $this->err_fields['productUrl'] ) ) : ?>
                <span class="tpmd-error-message"><?php echo $this->err_fields['productUrl'][0] ?></span>
            <?php endif;
            if ( isset( $this->err_fields['images'] ) ) : ?>
                <span class="tpmd-error-message"><?php echo $this->err_fields['images'][0] ?></span>
            <?php endif; ?>
        </p>
    </div>
</div>
</div>