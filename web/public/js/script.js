/**
* Sigunup/Profile edit logic
* */
$(document).ready(function() {
    $('#signupForm').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
        feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
            "user_register[firstName]": {
                validators: {
                    stringLength: {
                        min: 2,
                        message: 'Прекалено късо'
                    },
                    notEmpty: {
                        message: 'Въведете име'
                    }
                }
            },
            "user_register[lastName]": {
                validators: {
                    stringLength: {
                        min: 2,
                        message: 'Прекалено късо'
                    },
                    notEmpty: {
                        message: 'Въведете фамилия'
                    }
                }
            },
            "user_register[userName]": {
                validators: {
                    regexp: {
                        regexp: /\b[A-Za-z\d]+\b$/i,
                        message: 'Позволени са само латински букви и цифри'
                    },
                    stringLength: {
                        min: 4,
                        max: 32,
                        message: 'Дължината трябва да е 4-32 символа'
                    },
                    notEmpty: {
                        message: 'Моля въведене потребителско име'
                    }
                }
            },
            "user_register[rawPassword][first]": {
                validators: {
                    stringLength: {
                        min: 4,
                        max: 24,
                        message: 'Паролата трябва да 4-24 символа'
                    },
                    notEmpty: {
                        message: 'Въведете парола'
                    }
                }
            },
            "user_register[rawPassword][second]": {
                validators: {
                    identical: {
                        field: 'user_register[rawPassword][first]',
                        message: 'Паролите не съвпадат'
                    },
                    notEmpty: {
                        message: 'Потвърдете паролата'
                    }
                }
            },
            "user_register[email]": {
                validators: {
                    notEmpty: {
                        message: 'Въведете мейл'
                    },
                    emailAddress: {
                        message: 'Невалиден мейл адрес'
                    }
                }
            },
            "user_register[shippingAddress]": {
                validators: {
                    stringLength: {
                        min: 12,
                        message: 'Опишете адреса подробно!',
                    },
                    notEmpty: {
                        message: 'въведете адрес за доставка'
                    }
                },
                "user_register[country]": {
                    validators: {
                        notEmpty: {
                            message: 'Please select your Department/Office'
                        }
                    }
                },
            }
        }
    })
        .on('success.form.bv', function(e) {
           // $('#success_message').slideDown({ opacity: "show" }, "slow") // Do something ...
            $('#contact_form').data('bootstrapValidator').resetForm();

            // Prevent form submission
            e.preventDefault();

            // Get the form instance
            var $form = $(e.target);

            // Get the BootstrapValidator instance
            var bv = $form.data('bootstrapValidator');

            // Use Ajax to submit form data
            $.post($form.attr('action'), $form.serialize(), function(result) {
                console.log(result);
            }, 'json');
        });
});

var confirmButtonText = "Да";
var cancelButtonText = "Не";

/**
 * Shopping cart modal logic
 * */
$(function () {

    var goToCartIcon = function($addTocartBtn){
        var $cartIcon = $(".my-cart-icon");
        var $image = $('<img width="30px" height="30px" src="' + $addTocartBtn.data("image") + '"/>').css({"position": "fixed", "z-index": "999"});
        $addTocartBtn.prepend($image);
        var position = $cartIcon.offset();
        $image.animate({
            top: position.top,
            left: position.left
        }, 500 , "linear", function() {
            $image.remove();
        });
    }

    $('.my-cart-btn').myCart({
        currencySymbol: 'лв.',
        classCartIcon: 'my-cart-icon',
        classCartBadge: 'my-cart-badge',
        classProductQuantity: 'my-product-quantity',
        classProductRemove: 'my-product-remove',
        classCheckoutCart: 'my-cart-checkout',
        affixCartIcon: true,
        showCheckoutModal: true,
        numberOfDecimals: 2,
        // cartItems: [
        //     // {id: 1, name: 'product 1', summary: 'summary 1', price: 10, quantity: 1, image: 'images/img_1.png'},
        //     // {id: 2, name: 'product 2', summary: 'summary 2', price: 20, quantity: 2, image: 'images/img_2.png'},
        //     // {id: 3, name: 'product 3', summary: 'summary 3', price: 30, quantity: 1, image: 'images/img_3.png'}
        // ],
        clickOnAddToCart: function($addTocart){
            goToCartIcon($addTocart);
        },
        afterAddOnCart: function(products, totalPrice, totalQuantity) {
            console.log("afterAddOnCart", products, totalPrice, totalQuantity);
        },
        clickOnCartIcon: function($cartIcon, products, totalPrice, totalQuantity) {
            console.log("cart icon clicked", $cartIcon, products, totalPrice, totalQuantity);
        },
        checkoutCart: function(products, totalPrice, totalQuantity) {
            var checkoutString = "Total Price: " + totalPrice + "\nTotal Quantity: " + totalQuantity;
            checkoutString += "\n\n id \t name \t summary \t price \t quantity \t image path";
            $.each(products, function(){
                checkoutString += ("\n " + this.id + " \t " + this.name + " \t " + this.summary + " \t " + this.price + " \t " + this.quantity + " \t " + this.image);
            });
            alert(checkoutString);
            console.log("checking out!", products, totalPrice, totalQuantity);
        },
        getDiscountPrice: function(products, totalPrice, totalQuantity) {
            console.log("calculating discount", products, totalPrice, totalQuantity);
            return totalPrice * 0.5;
        }
    });
});

/**
*  Scroll to top logic
* */
$(document).ready(function(){
    $(window).scroll(function () {
        if ($(this).scrollTop() > 50) {
            $('#back-to-top').fadeIn();
        } else {
            $('#back-to-top').fadeOut();
        }
    });
    // scroll body to 0px on click
    $('#back-to-top').click(function () {
        $('#back-to-top').tooltip('hide');
        $('body,html').animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    $('#back-to-top').tooltip('show');

});

/**
*  Keep navbar fixed
* */
window.onscroll=function () {
    var top = window.pageXOffset ? window.pageXOffset : document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop;

    var navbarTop    = $('header').outerHeight();
    var navbarHeight = $('#navbar-menu').height();

    if (top >= navbarTop) {
        $('#navbar-menu').addClass('fixed');
        $('#navbar-fixed').css({height: navbarHeight+'px'});
    } else {
        $('#navbar-menu').removeClass('fixed');
        $('#navbar-fixed').css({height: '0px'});
    }
};

$(document).ready(function() {
    onlyDecimal();
    onlyNumbers();

    initJS();

    if ($('.deal-name').length > 0) {
        $('.deal-name').matchHeight();
    }
    if ($('.deal-box.grid').length > 0) {
        $('.deal-box.grid').matchHeight();
    }
    if ($('.deal-box.grid h2.title').length > 0) {
        $('.deal-box.grid h2.title').matchHeight();
    }
    if ($('a.merchant-link').length > 0) {
        $('a.merchant-link').matchHeight();
    }
    if ($('a.category-link').length > 0) {
        $('a.category-link').matchHeight();
    }

    $("#addDealForm").on('submit',(function(e) {
        e.preventDefault();

        var form = $(this).attr('id');
        var formId = form;
        var btn = $('#'+formId).find('button.submit');
        btn.button('loading');

        $.ajax({
            type: "POST",
            processData: false,
            contentType: false,
            cache: false,
            url: my_url + "ajax/deals/add/",
            data: new FormData(this),
            success: function(msg) {
                var obj = $.parseJSON(msg);

                removeError(formId);

                if (obj.status == 'success') {
                    $('#add-deal-content').hide();
                    $('#success-deal').show();
                    goTo('#success-deal');
                } else {
                    if (is_array(obj.text)) {
                        showError(obj.text, form);
                    } else {
                        if (obj.login == 1) {
                            openLogin();
                        } else {
                            createMsg('error', obj.text, '');
                        }
                    }
                }

                btn.button('reset');
            }
        });
    }));
});

function initJS()
{
    $('div[data-href]').click(function(){
        var href = $(this).attr('data-href');
        window.location=href;
    });

    $('input[type=text], input[type=password], input[type=email]').bind('keypress', function(e) {
        var code = (e.keyCode ? e.keyCode : e.which);
        if(code == 13) {
            var btn = $(this).closest('form').find('button[type=button].submit');

            if (btn.length > 0) {
                e.preventDefault();

                btn.click();
            }
        }
    });

    $('.label_check, .label_radio').click(function(){
        setupLabel();
    });
    setupLabel();

    $(".tooltip_top").tooltip({ placement: "top", html: true });
    $(".tooltip_bottom").tooltip({ placement: "bottom", html: true });
    $(".tooltip_left").tooltip({ placement: "left", html: true });
    $(".tooltip_right").tooltip({ placement: "right", html: true });

    $(".popover_top").popover({ trigger: 'click', placement: "top", html: true });
    $(".popover_bottom").popover({ trigger: 'click', placement: "bottom", html: true });
    $(".popover_left").popover({ trigger: 'click', placement: "left", html: true });
    $(".popover_right").popover({ trigger: 'click', placement: "right", html: true });
}

function onlyDecimal() {
    if ($('.onlyDecimal').length > 0) {
        $('.onlyDecimal').keypress(function(event) {
            var $this = $(this);
            if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
               ((event.which < 48 || event.which > 57) &&
               (event.which != 0 && event.which != 8))) {
                   event.preventDefault();
            }

            var text = $(this).val();
            if ((event.which == 46) && (text.indexOf('.') == -1)) {
                setTimeout(function() {
                    if ($this.val().substring($this.val().indexOf('.')).length > 3) {
                        $this.val($this.val().substring(0, $this.val().indexOf('.') + 3));
                    }
                }, 1);
            }

            if ((text.indexOf('.') != -1) &&
                (text.substring(text.indexOf('.')).length > 2) &&
                (event.which != 0 && event.which != 8) &&
                ($(this)[0].selectionStart >= text.length - 2)) {
                    event.preventDefault();
            }
        });
    }
}

function onlyNumbers() {
    if ($('.onlyNumbers').length > 0) {
        $(".onlyNumbers").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 ||
                 // Allow: Ctrl+A, Command+A
                (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
                 // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                     // let it happen, don't do anything
                     return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    }
}

function createMsg(msgType, title, text) {
    new PNotify({
        title: title,
        text: text,
        type: msgType,
        styling: 'bootstrap3',
        buttons: {
            sticker: false
        },
        animate: {
            animate: true,
            in_class: 'zoomInLeft',
            out_class: 'zoomOutRight'
        }
    });
}

function setupLabel() {
    if ($('.label_check input').length) {
        $('.label_check').each(function(){
            $(this).removeClass('c_on');
        });
        $('.label_check input:checked').each(function(){
            $(this).parent('label').addClass('c_on');
        });
    };

    if ($('.label_radio input').length) {
        $('.label_radio').each(function(){
            $(this).removeClass('r_on');
        });
        $('.label_radio input:checked').each(function(){
            $(this).parent('label').addClass('r_on');
        });
    };
}

/**
* AJAX
* */

function setNotificationOpen(notificationId, url) {
    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/profile/setNotificationOpen/",
        data: { id: notificationId },
        success: function(msg) {
            var obj = jQuery.parseJSON(msg);

            if (obj.status == 'success') {
                window.location = url;
            } else {
                createMsg('error', obj.text, '');
            }
        }
    });
}

function openAllNotifications() {
    swal({ 
        title: "Сигурни ли сте, че искате да отбележите всички известия като видяни?", type: "warning", showCancelButton: true, confirmButtonText: "Да", cancelButtonText: "Не", closeOnConfirm: true 
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: "POST",
                async: false,
                url: my_url + "ajax/profile/openAllNotifications/",
                data: {  },
                success: function(msg) {
                    var obj = jQuery.parseJSON(msg);

                    if (obj.status == 'success') {
                        window.location = f_url;
                    } else {
                        createMsg('error', obj.text, '');
                    }
                }
            });
        }
    });
}

function removeAllNotifications() {
    swal({ 
        title: "Сигурни ли сте, че искате да изтриете всички известия?", type: "warning", showCancelButton: true, confirmButtonText: "Да", cancelButtonText: "Не", closeOnConfirm: true 
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: "POST",
                async: false,
                url: my_url + "ajax/profile/removeAllNotifications/",
                data: {  },
                success: function(msg) {
                    var obj = jQuery.parseJSON(msg);

                    if (obj.status == 'success') {
                        window.location = f_url;
                    } else {
                        createMsg('error', obj.text, '');
                    }
                }
            });
        }
    });
}

function subscribe(formId) {
    var formData = $(formId).serialize();

    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/subscribe/",
        data: formData,
        success: function(msg) {
            var obj = jQuery.parseJSON(msg);

            if (obj.status == 'success') {
                $(formId)[0].reset();
                createMsg('success', obj.text, '');
            } else {
                createMsg('error', obj.text, '');
            }
        }
    });
}

function login(id) {
    var form = $('#'+id).serialize();

    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/profile/login/",
        data: form,
        success: function(msg) {
            var obj = $.parseJSON(msg);

            if (obj.status == 'success') {
                //window.location = my_url + 'profile';
                window.location = f_url;
            } else {
                createMsg('error', obj.text, '');
            }
        }
    });
}

function signUp(id) {
    var form = $('#'+id).serialize();
    
    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/profile/signup/",
        data: form,
        success: function(msg) {
            var obj = jQuery.parseJSON(msg);

            removeError(id);
            if (obj.status == 'success') {
                $('form#'+ id).trigger("reset");
                $('#signupModal').modal('hide');

                createMsg('success', obj.title, obj.text);
            } else {
                if (is_array(obj.text)) {
                    showError(obj.text, id);
                } else {
                    createMsg('error', obj.text, '');
                }
            }
        }
    });
}

function forgotPassword(id) {
    var form = $('#'+id).serialize();

    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/profile/forgotPassword/",
        data: form,
        success: function(msg) {
            var obj = $.parseJSON(msg);

            removeError(id);
            if (obj.status == 'success') {
                $('form#'+ id).trigger("reset");
                $('#forgotPasswordModal').modal('hide');

                createMsg('success', obj.text, '');
            } else {
                if (is_array(obj.text)) {
                    showError(obj.text, id);
                } else {
                    createMsg('error', obj.text, '');
                }
            }
        }
    });
}

function changePassword(id) {
    var form = $('#'+id).serialize();

    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/profile/changePassword/",
        data: form,
        success: function(msg) {
            var obj = $.parseJSON(msg);

            removeError(id);
            if (obj.status == 'success') {
                createMsg('success', obj.text, '');
            } else {
                if (is_array(obj.text)) {
                    showError(obj.text, id);
                } else {
                    if (obj.login == 1) {
                        openLogin();
                    } else {
                        createMsg('error', obj.text, '');
                    }
                }
            }
        }
    });
}

function changeNotifications(id) {
    var form = $('#'+id).serialize();

    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/profile/changeNotifications/",
        data: form,
        success: function(msg) {
            var obj = $.parseJSON(msg);

            removeError(id);
            if (obj.status == 'success') {
                createMsg('success', obj.text, '');
            } else {
                if (is_array(obj.text)) {
                    showError(obj.text, id);
                } else {
                    if (obj.login == 1) {
                        openLogin();
                    } else {
                        createMsg('error', obj.text, '');
                    }
                }
            }
        }
    });
}

function addPhrase(id, message = 0) {
    var form = $('#'+id).serialize();

    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/profile/addPhrase/",
        data: form,
        success: function(msg) {
            var obj = $.parseJSON(msg);

            removeError(id);
            if (obj.status == 'success') {
                if (message == 1) {
                    createMsg('success', obj.text, '');
                } else {
                    window.location = f_url;
                }
            } else {
                if (is_array(obj.text)) {
                    showError(obj.text, id);
                } else {
                    if (obj.login == 1) {
                        openLogin();
                    } else {
                        createMsg('error', obj.text, '');
                    }
                }
            }
        }
    });
}

function deletePhrase(id)
{
    swal({ 
        title: "Сигурни ли сте, че искате да изтриете фразата?", type: "warning", showCancelButton: true, confirmButtonText: "Да", cancelButtonText: "Не", closeOnConfirm: true 
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: "POST",
                async: false,
                url: my_url + "ajax/profile/deletePhrase/",
                data: { id: id },
                success: function(msg){
                    var obj = $.parseJSON(msg);

                    if (obj.status == 'success') {
                        createMsg('success', obj.text, '');

                        $('#phrase-'+ id).fadeOut();
                    } else {
                        if (obj.login == 1) {
                            openLogin();
                        } else {
                            createMsg('error', obj.text, '');
                        }
                    }
                }
            });
        }
    });
}

function setPhraseNotification(event, val, phraseId)
{
    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/profile/setPhraseNotification/",
        data: { value: val, id: phraseId },
        success: function(msg) {
            var obj = $.parseJSON(msg);

            if (obj.status == 'success') {

            } else {
                event.preventDefault();

                if (obj.login == 1) {
                    openLogin();
                } else {
                    createMsg('error', obj.text, '');
                }
            }
        }
    });
}

function sendMessage(userId)
{
    var formId = 'sendMessageForm';
    var form = $('#'+ formId).serialize();

    var btn = $('#'+ formId).find('button.submit');
    btn.button('loading');

    $.ajax({
        type: "POST",
        url: my_url + "ajax/messages/send/",
        data: form + '&userId='+ userId,
        success: function(msg) {
            var obj = $.parseJSON(msg);

            removeError(formId);
            if (obj.status == 'success') {
                $('.modal').modal('hide');
                $('#'+ formId).trigger("reset");
                
                createMsg('success', obj.text, '');
            } else {
                if (is_array(obj.text)) {
                    showError(obj.text, formId);
                } else {
                    if (obj.login == 1) {
                        openLogin();
                    } else {
                        createMsg('error', obj.text, '');
                    }
                }
            }

            btn.button('reset');
        }
    });
}

function deleteMessage(id)
{
    swal({ 
        title: "Сигурни ли сте, че искате да изтриете кореспонденцията?", type: "warning", showCancelButton: true, confirmButtonText: "Да", cancelButtonText: "Не", closeOnConfirm: true 
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: "POST",
                async: false,
                url: my_url + "ajax/messages/delete/",
                data: { id: id },
                success: function(msg){
                    var obj = $.parseJSON(msg);

                    if (obj.status == 'success') {
                        createMsg('success', obj.text, '');

                        $('#message-'+ id).fadeOut();
                    } else {
                        if (obj.login == 1) {
                            openLogin();
                        } else {
                            createMsg('error', obj.text, '');
                        }
                    }
                }
            });
        }
    });
}

var messagesList = "";
function viewMessages(id, el)
{
    var lastId = $('#messages-list .direct-chat-msg').last().attr('id');

    $.ajax({
        type: "GET",
        async: true,
        url: my_url + "ajax/messages/view/",
        data: { id: id, lastId: lastId },
        success: function(msg) {
            if (base64_encode(msg) != messagesList) {
                messagesList = base64_encode(msg);
            
                $("#messages-list").append(msg);

                $("#messages-list").animate({ scrollTop: $('#messages-list').prop("scrollHeight")}, 100);
            }

            initJS();
        }
    });

    var size_li = $('#messages-list .direct-chat-msg').size();
    var x=21;
    $('#messages-list .direct-chat-msg:gt(-'+x+')').show();
}

function showMoreMessages()
{
    var size_li = $('#messages-list .direct-chat-msg').size();
    var x = $('#messages-list .direct-chat-msg:visible').size();
    x = (x+21 <= size_li) ? x+21 : size_li;
    $('#messages-list .direct-chat-msg:gt(-'+x+')').show();

    if (x == size_li) {
        $('#loadOld').hide();
    }
}

function addReply(id, form)
{
    var message = $('#replyMessage').val();

    var btn = $('#'+ form).find('button.submit');
    btn.button('loading');

    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/messages/reply/",
        data: { message: message, id: id },
        success: function(msg) {
            var obj = $.parseJSON(msg);

            removeError(form);

            if (obj.status == 'success') {
                $('#'+ form).trigger("reset");

                $('#reloadMessages').click();
            } else {
                if (is_array(obj.text)) {
                    showError(obj.text, form);
                } else {
                    if (obj.login == 1) {
                        openLogin();
                    } else {
                        createMsg('error', obj.text, '');
                    }
                }
            }

            btn.button('reset');
        }
    });
}

/**
 * VIEW PROFILE*
 * */
function userAddFav(followId)
{
    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/view_profile/addFav/",
        data: { followId: followId },
        success: function(msg) {
            var obj = $.parseJSON(msg);

            if (obj.status == 'success') {
                createMsg('success', obj.text, '');

                $('.profile-btn-'+ followId +' .fav-add').hide();
                $('.profile-btn-'+ followId +' .fav-remove').show();
            } else {
                if (obj.login == 1) {
                    openLogin();
                } else {
                    createMsg('error', obj.text, '');
                }
            }
        }
    });
}
function userDelFav(followId)
{
    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/view_profile/removeFav/",
        data: { followId: followId },
        success: function(msg) {
            var obj = $.parseJSON(msg);

            if (obj.status == 'success') {
                createMsg('success', obj.text, '');

                $('.profile-btn-'+ followId +' .fav-remove').hide();
                $('.profile-btn-'+ followId +' .fav-add').show();
            } else {
                if (obj.login == 1) {
                    openLogin();
                } else {
                    createMsg('error', obj.text, '');
                }
            }
        }
    });
}


/**
 * DEALS
 * */
function showVoucherCode(dealId) {
    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/deals/getCode/",
        data: { dealId: dealId },
        success: function(msg) {
            var obj = $.parseJSON(msg);

            if (obj.status == 'success') {
                $('#voucherCode').modal('show');
                $('#voucher-code-box').html(obj.code);
            } else {
                if (obj.login == 1) {
                    openLogin();
                } else {
                    createMsg('error', obj.text, '');
                }
            }
        }
    });
}

function changeDealType(type) {
    if (type == 'online') {
        $('#offline-deal').hide();
        //$('#online-deal').show();
    } else {
        //$('#online-deal').hide();
        $('#offline-deal').show();
    }
}

function changeDealAvailability(availability) {
    if (availability == 0) {
        $('#availability-cities').hide();
    } else {
        $('#availability-cities').show();
    }

}
function addDeal(formId, edit) {
    $('#' + formId).submit();
}

function dealRate(type, dealId) {
    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/deals/rate/",
        data: { type: type, dealId: dealId },
        success: function(msg) {
            var obj = $.parseJSON(msg);

            if (obj.status == 'success') {
                $('.deal-degree-'+ dealId).removeClass('hot');
                $('.deal-degree-'+ dealId).removeClass('cold');
                if (obj.degree >= 0) {
                    $('.deal-degree-'+ dealId).addClass('hot');
                } else {
                    $('.deal-degree-'+ dealId).addClass('cold');
                }

                $('.deal-degree-'+ dealId + ' span').html(obj.degree);
                $('#btns-hot-'+ dealId +' a').addClass('disabled');
            } else {
                if (obj.login == 1) {
                    openLogin();
                } else {
                    createMsg('error', obj.text, '');
                }
            }
        }
    });
}

function changeDealGeneralType(type, id)
{
    swal({ 
        title: "Сигурни ли сте, че искате да промените генералния тип на далаверата?", type: "warning", showCancelButton: true, confirmButtonText: "Да", cancelButtonText: "Не", closeOnConfirm: true 
    }, function(isConfirm) {
        if (isConfirm) {
            $.ajax({
                type: "POST",
                async: false,
                url: my_url + "ajax/deals/changeGeneralType/",
                data: { type: type, id: id },
                success: function(msg){
                    var obj = $.parseJSON(msg);

                    if (obj.status == 'success') {
                        createMsg('success', obj.text, '');

                        window.location = f_url;
                    } else {
                        if (obj.login == 1) {
                            openLogin();
                        } else {
                            createMsg('error', obj.text, '');
                        }
                    }
                }
            });
        }
    });
}

function addDealComment(id)
{
    var form = $('#'+ id).serialize();
    
    var btn = $('#'+id).find('button.submit');
    btn.button('loading');

    $.ajax({
        type: "POST",
        url: my_url + "ajax/deals/addComment/",
        data: form,
        success: function(msg) {
            var obj = $.parseJSON(msg);

            removeError(id);
            if (obj.status == 'success') {
                window.location = f_url + "#comments";
                location.reload();
            } else {
                if (obj.login == 1) {
                    openLogin();
                } else {
                    if (is_array(obj.text)) {
                        showError(obj.text, id);
                    } else {
                        createMsg('error', obj.text, '');
                    }
                }
            }

            btn.button('reset');
        }
    });
}

function reportDealComment(id)
{
    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/deals/reportComment/",
        data: { id: id },
        success: function(msg) {
            var obj = $.parseJSON(msg);

            removeError(id);
            if (obj.status == 'success') {
                createMsg('success', obj.text, '');
            } else {
                if (obj.login == 1) {
                    openLogin();
                } else {
                    if (is_array(obj.text)) {
                        showError(obj.text, id);
                    } else {
                        createMsg('error', obj.text, '');
                    }
                }
            }
        }
    });
}

function dealCommentRate(commentId, type)
{
    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/deals/rateComment/",
        data: { commentId: commentId, type: type },
        success: function(msg) {
            var obj = $.parseJSON(msg);

            if (obj.status == 'success') {
                $('#helpful'+'-'+commentId).html(obj.info.helpful);
                $('#unhelpful'+'-'+commentId).html(obj.info.unhelpful);
            } else {
                if (obj.login == 1) {
                    openLogin();
                } else {
                    createMsg('error', obj.text, '');
                }
            }
        }
    });
}

function dealAddFav(dealId)
{
    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/deals/addFav/",
        data: { dealId: dealId },
        success: function(msg) {
            var obj = $.parseJSON(msg);

            if (obj.status == 'success') {
                createMsg('success', obj.text, '');

                $('#fav-deal-'+ dealId +' .fav-add').hide();
                $('#fav-deal-'+ dealId +' .fav-remove').show();
            } else {
                if (obj.login == 1) {
                    openLogin();
                } else {
                    createMsg('error', obj.text, '');
                }
            }
        }
    });
}
function dealDelFav(dealId)
{
    $.ajax({
        type: "POST",
        async: false,
        url: my_url + "ajax/deals/removeFav/",
        data: { dealId: dealId },
        success: function(msg) {
            var obj = $.parseJSON(msg);

            if (obj.status == 'success') {
                createMsg('success', obj.text, '');

                $('#fav-deal-'+ dealId +' .fav-remove').hide();
                $('#fav-deal-'+ dealId +' .fav-add').show();
            } else {
                if (obj.login == 1) {
                    openLogin();
                } else {
                    createMsg('error', obj.text, '');
                }
            }
        }
    });
}

function openLogin() {
    $('.modal').modal('hide');
    $("#loginModal").modal();
}

function goTo(el) {
    var targetOffset = $(el).offset().top;
    $("html, body").animate({ scrollTop: targetOffset-70 }, "slow");
}

function showError (arr, id) {
    $('#'+id+' .has-error').removeClass('has-error');
    $.each(arr, function(k, v) {
        if ($('#'+id+' *[data-error='+ k +']').length > 0) {
            $('#'+id+' *[data-error='+ k +']').html('<div class="has-error-text">'+ v + '</div>');
        } else {
            $('#'+id+' *[name='+ k +']').addClass('has-error');
            $('#'+id+' *[name='+ k +']').after('<div class="has-error-text">'+ v + '</div>');
        }
    });

    if ($('.has-error:visible:first').length > 0) {
        $('html, body').animate({
            scrollTop: $('.has-error:visible:first').offset().top-150
        }, 500);
        //$('.has-error:visible:first').focus();
    }
}

function removeError (id) {
    $('.has-error-text').remove();
    $('#'+id+' .has-error').removeClass('has-error');
}

function blockEnter(e, formId) {
    if (e.keyCode == 13) {
        $("#"+ formId).on('submit',function(event) {
            event.preventDefault();
        });
        return false;
    }
}

function is_array(mixed_var) {
  var ini,
    _getFuncName = function(fn) {
      var name = (/\W*function\s+([\w\$]+)\s*\(/)
        .exec(fn);
      if (!name) {
        return '(Anonymous)';
      }
      return name[1];
    };
  _isArray = function(mixed_var) {
    // return Object.prototype.toString.call(mixed_var) === '[object Array]';
    // The above works, but let's do the even more stringent approach: (since Object.prototype.toString could be overridden)
    // Null, Not an object, no length property so couldn't be an Array (or String)
    if (!mixed_var || typeof mixed_var !== 'object' || typeof mixed_var.length !== 'number') {
      return false;
    }
    var len = mixed_var.length;
    mixed_var[mixed_var.length] = 'bogus';
    // The only way I can think of to get around this (or where there would be trouble) would be to have an object defined
    // with a custom "length" getter which changed behavior on each call (or a setter to mess up the following below) or a custom
    // setter for numeric properties, but even that would need to listen for specific indexes; but there should be no false negatives
    // and such a false positive would need to rely on later JavaScript innovations like __defineSetter__
    if (len !== mixed_var.length) { // We know it's an array since length auto-changed with the addition of a
      // numeric property at its length end, so safely get rid of our bogus element
      mixed_var.length -= 1;
      return true;
    }
    // Get rid of the property we added onto a non-array object; only possible
    // side-effect is if the user adds back the property later, it will iterate
    // this property in the older order placement in IE (an order which should not
    // be depended on anyways)
    delete mixed_var[mixed_var.length];
    return false;
  };

  if (!mixed_var || typeof mixed_var !== 'object') {
    return false;
  }

  // BEGIN REDUNDANT
  this.php_js = this.php_js || {};
  this.php_js.ini = this.php_js.ini || {};
  // END REDUNDANT

  ini = this.php_js.ini['phpjs.objectsAsArrays'];

  return _isArray(mixed_var) ||
  // Allow returning true unless user has called
  // ini_set('phpjs.objectsAsArrays', 0) to disallow objects as arrays
  ((!ini || ( // if it's not set to 0 and it's not 'off', check for objects as arrays
    (parseInt(ini.local_value, 10) !== 0 && (!ini.local_value.toLowerCase || ini.local_value.toLowerCase() !==
      'off')))) && (
    Object.prototype.toString.call(mixed_var) === '[object Object]' && _getFuncName(mixed_var.constructor) ===
    'Object' // Most likely a literal and intended as assoc. array
  ));
}

function base64_encode (data) {
  var b64 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/='
  var o1, o2, o3, h1, h2, h3, h4, bits, i = 0,
    ac = 0,
    enc = '',
    tmp_arr = []

  if (!data) {
    return data
  }

  data = unescape(encodeURIComponent(data))

  do {
    // pack three octets into four hexets
    o1 = data.charCodeAt(i++)
    o2 = data.charCodeAt(i++)
    o3 = data.charCodeAt(i++)

    bits = o1 << 16 | o2 << 8 | o3

    h1 = bits >> 18 & 0x3f
    h2 = bits >> 12 & 0x3f
    h3 = bits >> 6 & 0x3f
    h4 = bits & 0x3f

    // use hexets to index into b64, and append result to encoded string
    tmp_arr[ac++] = b64.charAt(h1) + b64.charAt(h2) + b64.charAt(h3) + b64.charAt(h4)
  } while (i < data.length)

  enc = tmp_arr.join('')

  var r = data.length % 3

  return (r ? enc.slice(0, r - 3) : enc) + '==='.slice(r || 3)
}

function number_format (number, decimals, decPoint, thousandsSep) {
  number = (number + '').replace(/[^0-9+\-Ee.]/g, '')
  var n = !isFinite(+number) ? 0 : +number
  var prec = !isFinite(+decimals) ? 0 : Math.abs(decimals)
  var sep = (typeof thousandsSep === 'undefined') ? ',' : thousandsSep
  var dec = (typeof decPoint === 'undefined') ? '.' : decPoint
  var s = ''

  var toFixedFix = function (n, prec) {
    var k = Math.pow(10, prec)
    return '' + (Math.round(n * k) / k)
      .toFixed(prec)
  }

  // @todo: for IE parseFloat(0.55).toFixed(0) = 0;
  s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.')
  if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep)
  }
  if ((s[1] || '').length < prec) {
    s[1] = s[1] || ''
    s[1] += new Array(prec - s[1].length + 1).join('0')
  }

  return s.join(dec)
}
