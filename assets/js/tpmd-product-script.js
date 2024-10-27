jQuery(document).ready(function($, window) {

    
    // start main program module
    tpmdProduct.load();
    var i=0;
    $('#tpmd-weightMeasurement option').each(function(){
        if(i==0){
            $(this).remove();
        }
        i++;
    });
    var i=0;
    $('#tpmd-preciousMetalWeightMeasurement option').each(function(){
        if(i==0){
            $(this).remove();
        }
        i++;
    });
    $('.options_group h2').click(function(){
        $(this).parent().find('.toggle-indicator').click();
    });
});

// main option module
var tpmdProduct = (function ($) {
    "use strict";

    var selectMetal, yearType, purity, metal_list, purity_section,
        gradingService, grade, grade_section, mintCountry, mintState;
    var tpmd_price_select = $('#tpmd-priceType'),
        tpmd_specprice_select = $('#tpmd-SpecialpriceType');

    var load = function () {
        init_vars();
        set_state();
        set_handlers();
    };

    var set_handlers = function () {

        $('#tpmd-priceTier a.tpmd-removeTier').click(function (event){
            event.preventDefault();
            $('a#tpmd-addTier').removeAttr("disabled");
            $(this).closest('tr').remove();
            var n = -1;
            $('#tpmd-priceTier table tbody tr').each(function () {
                $(this).find('input.tpmd-priceTiers-qty').attr('name','tpmd-priceTiers['+ n +'][qty]');
                $(this).find('input.tpmd-priceTiers-premium').attr('name','tpmd-priceTiers['+ n +'][premium]');
                n++;
            });

        });

        $('a#tpmd-addTier').click(function (event) {
            var n = $('#tpmd-priceTier table tbody tr').length - 1;
            event.preventDefault();
            if( $(this).attr("disabled")){
                return false;
            }
            if($('#tpmd-priceTier table tbody tr').length >= 4){
                $(this).attr("disabled","disabled");
            }


            $('#tpmd-priceTier table tbody').append('<tr>\n' +
                '<td></td>\n' +
                '<td><input style="width: 40%;" type="number" maxlength="6" class="input decimal-4 tpmd-priceTiers-qty" name="tpmd-priceTiers['+ n +'][qty]">&nbsp;and above</td>\n' +
                '<td><input type="text" maxlength="6" class="input decimal-4 tpmd-priceTiers-premium" name="tpmd-priceTiers['+ n +'][premium]"></td>\n' +
                '<td><a href="#" class="button tpmd-removeTier">X</a></td>'+
                '</tr>');

        });

        $(document).on('click', 'a.search-choice-close', function () {
            var li = $(this).closest('li');
            $('.treeselect input#' + li.attr('id')).prop('checked', false);
            li.remove();
            return false;
        });



         $('#title').bind("change keyup input click", function () {
             $('#id-name-tpmd').val($(this).val());
         });

         $('#_sku').bind("change keyup input click", function () {
             $('#id-productId-tpmd').val($(this).val());
         });

         $('#tpmd-priceType').change(function () {
             if (this.value === '3') {
                 $('.tpmd-premiumValue').hide();
                 //$('#id-premium-tpmd').val('');
             } else {
                 $('.tpmd-premiumValue').show();
             }
         });

         $('.trpmd-tab_options').click(function () {
             if ($('#id-name-tpmd').val() == '') {
                 $('#id-name-tpmd').val($('#title').val());
             }
             if ($('#id-productId-tpmd').val() == '') {
                 $('#id-productId-tpmd').val($('#_sku').val());
             }
         });



         // $('#_stock_status').change( function() {
         //     $('#tpmd-status').val($(this).val());
         // });

         $('#id-productId-tpmd').bind("change keyup input click", function () {
             if (this.value.match(/[^a-zA-Z0-9-]/g)) {
                 this.value = this.value.replace(/[^a-zA-Z0-9-]/g, '');
             }
         });

         $('.decimal-4').bind("change keyup input click", function () {
             var regex = /^\d*((\.){0,1})(\d{1,4})?$/;
             if (!regex.test(this.value)) {
                 this.value = this.value.slice(0, -1);
             }
         });

         $('.decimal-2').bind("change keyup input click", function () {
             var regex = /^\d*((\.){0,1})(\d{1,2})?$/;
             if (!regex.test(this.value)) {
                 this.value = this.value.slice(0, -1);
             }
         });

         $('#tpmd-years-single').bind("change keyup input click", function () {
             var regex = /^\d{0,4}$/;
             if (!regex.test(this.value)) {
                 this.value = this.value.slice(0, -1);
             }

         });
        $('#tpmd-years-from').bind("change keyup input click", function () {
            var regex = /^\d{0,4}$/;
            if (!regex.test(this.value)) {
                this.value = this.value.slice(0, -1);
            }

        });
        $('#tpmd-years-to').bind("change keyup input click", function () {
            var regex = /^\d{0,4}$/;
            if (!regex.test(this.value)) {
                this.value = this.value.slice(0, -1);
            }

        });

         yearType.change(function () {
             if (this.value == 1 ||
                 this.value == 3 ||
                 this.value == 4) {
                 $('#tpmd-years-single').show().attr('disabled', false);
                 $('#tpmd-years-from').hide().attr('disabled', 'disabled');
                 $('#tpmd-years-to').hide().attr('disabled', 'disabled');
             } else {
                 $('#tpmd-years-single').hide().attr('disabled', 'disabled');
                 $('#tpmd-years-from').show().attr('disabled', false);
                 $('#tpmd-years-to').show().attr('disabled', false);

             }
         });

         selectMetal.change(function () {
             purity.empty();
             var volume = $(this).val();

             if (metal_list.indexOf(volume) == -1) {
                 purity_section.hide();
                 $('.tpmd-preciousMetalWeight label').removeClass('required');
                 tpmd_price_select.find('option[value=\'1\']').attr('disabled','disabled');
                 tpmd_price_select.find('option[value=\'2\']').attr('disabled','disabled');
                 tpmd_price_select.val("3");
                 tpmd_specprice_select.find('option[value=\'1\']').attr('disabled','disabled');
                 tpmd_specprice_select.find('option[value=\'2\']').attr('disabled','disabled');
                 tpmd_specprice_select.val("3");
                 return;
             }
             tpmd_price_select.find('option[value=\'1\']').removeAttr('disabled');
             tpmd_price_select.find('option[value=\'2\']').removeAttr('disabled');
             tpmd_specprice_select.find('option[value=\'1\']').removeAttr('disabled');
             tpmd_specprice_select.find('option[value=\'2\']').removeAttr('disabled');
             purity_section.show();
             $('.tpmd-preciousMetalWeight label').addClass('required');

             $.each(window.tpmd_purity_obj[volume], function (key, value) {

                 purity.append($('<option>', {
                     value: value[0],
                     text: value[1]
                 }));
             });
         });

         // coutry section
         mintCountry.change(function () {
             var data = {
                 'mint-state-select': $(this).val(),
                 'action': 'get_mint_state'
             };
             doAjax(data, mintSuccess);
         });

         mintState.click(function () {
             if ($('select#tpmd-mintState option').length > 1) {
                 return;
             }
             var data = {
                 'mint-state-select': mintCountry.val(),
                 'action': 'get_mint_state'
             };
             doAjax(data, mintSuccess);
         });

        gradingService.change(function(){
            var data = {
                'grade-service-select' : $(this).val(),
                'action' : 'get_grade_service'
            };
            doAjax(data, gradeSuccess);
        });

        grade.click(function(){
            if( $('select#tpmd-grade option').length > 1 ) {
                return;
            }
            var data = {
                'grade-service-select' : gradingService.val(),
                'action' : 'get_grade_service'
            };
            doAjax(data, gradeSuccess);
        });
    };

    var gradeSuccess = function(result){
        if (result === 'empty'){
            grade.empty();
            grade.attr('disabled', 'disabled');
            return;
        }
        var parsed = JSON.parse(result);
        setSelectValue(parsed, grade);
        grade.prop( "disabled", false );
    };

    var mintSuccess = function(result){
        if (result === 'empty'){
            mintState.empty();
            mintState.attr('disabled', 'disabled');
            return;
        }
        var parsed = JSON.parse(result);
        setSelectValue(parsed, mintState);
        mintState.prop( "disabled", false );
    };

    var setSelectValue = function(data, item) {
        item.empty();
        for (var i in data) {
            item.append('<option value="' + i + '">' + data[i] + '</option>');
        }
    };

    var treeCategories = function (result) {
        var parsed = JSON.parse(result);
        console.log(parsed);
    };

    var doAjax = function (data, success) {
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: data,
            beforeSend: function () {
                //loader.show();
            },
            complete: function () {
                //loader.hide();
            },
            success: success,
            error: function(result) {
                //errorMsg(tpmd_message.test_error);
            },
        });
    };

    var category_item_selected = function (item) {
        console.log('12' + item);
    };

    var set_state = function () {
        selectMetal.find('option:first').remove();

        if($('#tpmd-priceTier table tbody tr').length > 4){
            $('a#tpmd-addTier').attr("disabled","disabled");
        }

        if ( metal_list.indexOf(selectMetal.val()) == -1 ) {
            purity_section.hide();
            tpmd_price_select.find('option[value=\'1\']').attr('disabled','disabled');
            tpmd_price_select.find('option[value=\'2\']').attr('disabled','disabled');
            tpmd_specprice_select.find('option[value=\'1\']').attr('disabled','disabled');
            tpmd_specprice_select.find('option[value=\'2\']').attr('disabled','disabled');
        }

        if (gradingService.val() === ''){
            grade.attr('disabled', 'disabled');
        }
        $('#tpmd-years-from').hide().attr('disabled', 'disabled');
        $('#tpmd-years-to').hide().attr('disabled', 'disabled');
        $('#tpmd-years-single').hide().attr('disabled', 'disabled');

        if( yearType.val() == 1 || yearType.val() == 3 || yearType.val() == 4 )
        {
            $('#tpmd-years-single').show().attr('disabled', false);
        } else {
            $('#tpmd-years-single').hide().attr('disabled', 'disabled');
            $('#tpmd-years-from').show().attr('disabled', false);
            $('#tpmd-years-to').show().attr('disabled', false);
        }

        if ( $('#tpmd-priceType').val() === '3'){
            $('.tpmd-premiumValue').hide();
            //$('#id-premium-tpmd').val('');
        }


    };

    var init_vars = function () {
        yearType = $('#tpmd-yearType');

        selectMetal = $('#tpmd-metal');
        purity = $('#tpmd-purity');
        purity_section = $('.tpmd-purity');
        metal_list = ["1", "2", "3", "4"];

        gradingService = $('#tpmd-gradingService');
        grade = $('#tpmd-grade');
        grade_section = $('.tpmd-grade');

        mintCountry = $('#tpmd-mintCountry');
        mintState = $('#tpmd-mintState');

//        $('#tpmd-years-from').datepicker({
//            format: "yyyy",
//            autoclose: true,
//            minViewMode: "years"
//        });

//        $('#tpmd-years-to').datepicker({
//            format: "yyyy",
//            autoclose: true,
//            minViewMode: "years"
//        });

        $('div.chosentree').chosentree({
            deepLoad: true,
            input_placeholder: '',
            label:'Category',
            showtree: true,
            collapsed: false,
            inputName: 'tpmd-categories[]',
            default_value: {},
            load: function (node, callback) {
                doAjax({
                    action: 'get_categories_attr',
                    tmpd_post_id: window.tpmd_post_ajax.tpmd_id,
                    test: 'test'
                }, function (response) {
                    response = JSON.parse(response);

                    callback(loadChildren(node, response));
                });
            }
        });

        var loadChildren = function (node, response) {
            node.children = response.nodes;

            if (response.tags.length > 0) {
                var tags = [];

                $.each(response.tags, function(i, obj) {
                    tags.push($('<li>', {
                        class: 'search-choice',
                        id: 'choice_' + obj.id,
                        html: [
                            $('<span>', {
                                text: obj.title
                            }),
                            $('<a>', {
                                class: 'search-choice-close',
                                href: '#'
                            })
                        ]
                    }));
                });

                if (tags.length > 0) {
                    $('.chzntree-choices.chosentree-choices').prepend( tags );
                }
            }

            return node;
        };
    };

    return {
        load : load
    }
})(jQuery, window);