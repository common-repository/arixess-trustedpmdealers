jQuery(document).ready(function($, window) {
    // start main program module
    tpmdOptions.load();
    $('.tmpd-accordion').accordion();
});

// main option module
var tpmdOptions = (function ($) {
    "use strict";

    // init variables option page
    var form, api_key, cur, btnTest, btnSave, bar_success, bar_error, loader;

    var load = function () {
        init_vars();
        set_handlers();
    };

    var set_handlers = function () {


            $('.tooltip').tooltipster({
    trigger: 'custom',
        content: $('#tooltip_content'),
        contentAsHTML: true,
    triggerOpen: {
        mouseenter: true,
    },
    triggerClose: {
        click: true
    }
});

        api_key.on('input', function (e) {
            cur.empty();
            if (!api_key.val()) {
                btnTest.attr('disabled', 'disabled');
                btnSave.attr('disabled', 'disabled');
                return;
            } else {
                btnTest.attr('disabled', false);
                btnSave.attr('disabled', false);
            }
        });

        btnTest.click(function (e) {
            e.preventDefault();
            if (!api_key.val()) return;
            var data = {
                action: 'get_all_attributes_ajax',
                key: api_key.val()
            };
            doAjax(data, testSuccess);
        });

        btnSave.click(function (e) {
            e.preventDefault();
            if (!api_key.val() || !cur.val()) {
                errorMsg(window.tpmd_message.save_before_test);
                return;
            }
            var data = {
                action: 'set_option_data_ajax',
                key: api_key.val(),
                cur: cur.val(),
            };
            doAjax(data, saveSuccess);
        });
    }

    var testSuccess = function (result) {
        var parsed = JSON.parse(result);
        if (parsed.success == 'true'){
            successMsg(parsed.message);
            setValueCur(parsed.data.priceCurrency);
        } else {
            errorMsg(parsed.message);
            cur.empty();
        }
    };

    var saveSuccess = function (result) {
        var parsed = JSON.parse(result);
        if (parsed.success == 'true'){
            successMsg(parsed.message);
        } else {
            errorMsg(parsed.message);
        }
    };

    var setValueCur = function (data) {
        var current_item = cur.val();
        cur.empty();
        for (var i in data) {
            cur.append('<option value=' + data[i] + '>' + data[i] + '</option>');
        }
        if(current_item){
            cur.val(current_item);
        }
    };

    var doAjax = function (data, success) {
        $.ajax({
            type: 'post',
            url: ajaxurl,
            data: data,
            beforeSend: function () {
                loader.show();
                formDisable();
            },
            complete: function () {
                formEnable();
                loader.hide();
            },
            success: success,
            error: function(result) {
                errorMsg(tpmd_message.test_error);
            },
        });
    };

    var init_vars = function () {
        // option page
        form = $('#tpmd-api-form');
        api_key = $('#tpmd-api-key');
        cur = $('#tpmd-currency-select');
        btnTest = $('#tpmd-btn-api-test');
        btnSave = $('#tpmd-btn-api-submit');
        bar_success = $('.tpmd-api-success');
        bar_error = $('.tpmd-api-error');
        loader = $('.tpmd-loader');
        loader.hide();

        if (!api_key.val()) {
            cur.attr('disabled', 'disabled');
            btnTest.attr('disabled', 'disabled');
            btnSave.attr('disabled', 'disabled');
        }
    };

    var formDisable = function () {
        form.find('input, button, select').attr('disabled','disabled');
    };

    var formEnable = function () {
        form.find('input, button, select').attr('disabled', false);
    };

    var successMsg = function (message) {
        bar_success.html(message);
        bar_success.slideDown("slow");
        setTimeout(function(){
            bar_success.slideUp("slow", function() { bar_success.hide(); });
        }, 2000);
    }

    var errorMsg = function (message) {
        bar_error.html(message);
        bar_error.slideDown("slow");
        setTimeout(function(){
            bar_error.slideUp("slow", function() { bar_error.hide(); });
            }, 2000);
    }

    return {
        load : load
    }

})(jQuery, window);