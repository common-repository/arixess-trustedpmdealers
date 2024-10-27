<div class="tpmd">
    <h2 style="background: #eee;">TrustedPMDealers Price</h2>
    <div class="inside">
    <p class="form-field tpmd-priceValue">
        <label for="tpmd-priceType>" class="required" style="width: 160px;"><?php _e( 'Regular Price', 'tr_pmd_locale' ); ?></label>
	    <?php echo wc_help_tip( '<div>Values "Over Spot" and "Over Melt" are available only for Gold, Silver, Platinum and Palladium metals. Follow to "TrustedPMDealers" tab to set the Metal Value. <br>
Specify how the price for your products will be calculated based on current spot price:
<b>Price Over Spot</b> = (Spot Price + Premium) x Weight <br>
<b>Price Over Melt</b> = (Spot Price x Weight) + Premium <br>
<b>Fixed Price</b> = will be displayed as you specify it and won\'t be affected by any spot price market fluctuations.</div>'); ?>
        <select id="tpmd-priceType" name="tpmd-priceType"
                class="select short <?php if ( isset( $this->err_fields['priceType'] ) ) : echo 'tpmd-error-field'; endif; ?>">
            <option></option>
            <?php foreach ( $attr[ 'priceType' ] as $key=>$item ) : ?>
                <option value="<?php echo $key ?>"
                    <?php if ( $product && $product->get_field( 'priceType' ) == $key ) : ?>
                        selected="selected"
                    <?php endif; ?>
                ><?php echo $item ?></option>
            <?php endforeach; ?>
        </select>



        <select id="tpmd-premiumType" name="tpmd-premiumType"
                class="select short <?php if ( isset( $this->err_fields[ 'premiumType' ] ) ) : echo 'tpmd-error-field'; endif; ?>">
            <?php foreach ( $attr[ 'premiumType' ] as $key=>$item ) : ?>
                <?php  $item_symbol = ( $item == "Amount" ) ? "$" : "%" ; ?>
                <option value="<?php echo $key ?>"
                    <?php if ( $product && $product->get_field( 'premiumType' ) == $key ) : ?>
                        selected="selected"
                    <?php endif; ?>
                ><?php echo $item_symbol; ?></option>
            <?php endforeach; ?>
        </select>

        <input class="input decimal-4 <?php if ( isset( $this->err_fields['premium'] ) ) { echo ' tpmd-error-field'; } ?>"
               type="text" name="tpmd-premium" id="id-premium-tpmd"
        <?php
        $val = ( $product && $product->get_field( 'premium' ) != 0 ) ? $product->get_field( 'premium' ) : '';
         echo 'value ="' .  $val . '" ';
         echo 'maxlength="6" />'; ?>

        <select id="tpmd-priceCurrency" name="tpmd-priceCurrency" class="select short
        <?php if ( isset( $this->err_fields[ 'priceCurrency' ] ) ) : echo 'tpmd-error-field'; endif; ?>" >
            <option></option>
            <?php foreach ( $attr[ 'priceCurrency' ] as $key=>$item ) : ?>
                <option value="<?php echo $key ?>"
                    <?php if ( $product && $product->get_field( 'priceCurrency' ) == $key ) : ?>
                        selected="selected"
                    <?php endif; ?>
                >
                    <?php echo $item ?></option>
            <?php endforeach; ?>
        </select>
    </p>
        <?php

        if ( isset( $this->err_fields['premiumType'] ) ) :
            echo '<span class="tpmd-error-message">' . $this->err_fields['premiumType'][0] . '</span>';
        endif;
        if ( isset( $this->err_fields['premium'] ) ) :
            echo '<span class="tpmd-error-message">' . $this->err_fields['premium'][0] . '</span>';
        endif;
        if ( isset( $this->err_fields['priceType'] ) ) :
            echo '<span class="tpmd-error-message">' . $this->err_fields['priceType'][0] . '</span>';
        endif;
        if ( isset( $this->err_fields['priceCurrency'] ) ) :
            echo '<span class="tpmd-error-message">' . $this->err_fields['priceCurrency'][0] . '</span>';
        endif;
        ?>

        <p class="form-field tpmd-priceValue" >

            <label for="tpmd-SpecialpriceType"  style="width: 160px;"><?php _e( 'Special Price', 'tr_pmd_locale' ); ?></label>
	        <?php echo wc_help_tip( 'Dates for Special Price will be specified as they are set for the Sale price.'); ?>
            <select id="tpmd-SpecialpriceType" name="tpmd-specialPrice[priceType]"
                    class="select short <?php if ( isset( $this->err_fields['SpecialpriceType'] ) ) : echo 'tpmd-error-field'; endif; ?>">
                <option></option>

			    <?php foreach ( $attr[ 'priceType' ] as $key=>$item ) : ?>
                    <option value="<?php echo $key ?>"
					    <?php if ( $product && ($product->get_field( 'specialPrice' ) != '') && $product->get_field( 'specialPrice' )['priceType'] == $key ) : ?>
                            selected="selected"
					    <?php endif; ?>
                    ><?php echo $item ?></option>
			    <?php endforeach; ?>
            </select>

            <select id="tpmd-SpecialpremiumType" name="tpmd-specialPrice[premiumType]"
                    class="select short <?php if ( isset( $this->err_fields[ 'SpecialpremiumType' ] ) ) : echo 'tpmd-error-field'; endif; ?>">
			    <?php foreach ( $attr[ 'premiumType' ] as $key=>$item ) : ?>
				    <?php  $item_symbol = ( $item == "Amount" ) ? "$" : "%" ; ?>
                    <option value="<?php echo $key ?>"
					    <?php if ( $product && ($product->get_field( 'specialPrice' ) != '') && $product->get_field( 'specialPrice' )['premiumType'] == $key ) : ?>
                            selected="selected"
					    <?php endif; ?>
                    ><?php echo $item_symbol; ?></option>
			    <?php endforeach; ?>
            </select>

            <input class="input decimal-4<?php if ( isset( $this->err_fields['Specialpremium'] ) ) { echo ' tpmd-error-field'; } ?>"
                   type="text" name="tpmd-specialPrice[premium]" id="id-special-tpmd"
		    <?php
		    $val = ( $product && ($product->get_field( 'specialPrice' ) != '') && $product->get_field( 'specialPrice' )['premium'] != 0 ) ? $product->get_field( 'specialPrice' )['premium'] : '';
		    echo 'value ="' .  $val . '" ';
		    echo 'maxlength="6" />'; ?>

            <select id="tpmd-SpecialpriceCurrency" name="tpmd-specialPrice[priceCurrency]" class="select short
        <?php if ( isset( $this->err_fields[ 'SpecialpriceCurrency' ] ) ) : echo 'tpmd-error-field'; endif; ?>" >
                <option></option>
			    <?php foreach ( $attr[ 'priceCurrency' ] as $key=>$item ) : ?>
                    <option value="<?php echo $key ?>"
					    <?php

                        if($product && ($product->get_field( 'specialPrice' ) != '')){
	                        $specPriceCurr = $product->get_field( 'specialPrice' )['priceCurrency'];
                        }

                        if ($specPriceCurr == $key ) : ?>
                            selected="selected"
					    <?php endif; ?>
                    >
					    <?php echo $item ?></option>
			    <?php endforeach; ?>
            </select>

        </p>

        <div id="tpmd-priceTier">
            <table cellpadding="0" cellspacing="0">
                <input type="hidden" name="tpmd-priceTiers[10][qty]">&nbsp;
                <input type="hidden" name="tpmd-priceTiers[10][premium]">
                <tbody>
                <tr>
                    <th style="width: 185px;">Price Tier</th>
                    <th style="width: 15%;">Qty</th>
                    <th style="width: 10%;">Premium</th>
                </tr>


                <?php if ( $product && ($product->get_field( 'priceTiers' ) != '')): ?>
                    <input type="hidden" name="old-priceTier" value="1">
	                <?php foreach ( $product->get_field( 'priceTiers' ) as $k=>$v) : ?>
                        <tr>
                            <td></td>
                            <td><input style="width: 40%;" type="number" maxlength="6" class="input decimal-4 tpmd-priceTiers-qty" name="tpmd-priceTiers[<?php echo $k; ?>][qty]" value="<?php echo $v['qty']; ?>">&nbsp;and above</td>
                            <td><input type="text" maxlength="6" class="input decimal-4 tpmd-priceTiers-premium" name="tpmd-priceTiers[<?php echo $k; ?>][premium]" value="<?php echo $v['premium']; ?>" ></td>
                            <td><a href="#" class="button tpmd-removeTier">X</a></td>
                        </tr>
	                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td></td>
                    <td><input style="width: 40%;" type="number" maxlength="6" class="input decimal-4 tpmd-priceTiers-qty" name="tpmd-priceTiers[0][qty]">&nbsp;and above</td>
                    <td><input type="text" maxlength="6" class="input decimal-4 tpmd-priceTiers-premium" name="tpmd-priceTiers[0][premium]"></td>
                    <td><a href="#" class="button tpmd-removeTier">X</a></td>
                </tr>
                <?php endif; ?>

                </tbody>

            </table>
            <a href="#" class="button" style="margin-left: 13px;" id="tpmd-addTier">Add Tier</a>
            <p class="form-field">
                <?php if(isset($this->err_fields['*'])):?>
	                <?php foreach ( $this->err_fields['*'] as $error) : ?>
                        <span class="tpmd-error-message"><?php echo $error; ?></span>
	                <?php endforeach; ?>
                <?php endif; ?>
            </p>
        </div>




  </div>
</div>
