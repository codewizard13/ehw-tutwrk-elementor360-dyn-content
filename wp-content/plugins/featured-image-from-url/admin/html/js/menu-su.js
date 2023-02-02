var restUrl = fifu_get_rest_url();

function signUp() {
    var firstName = jQuery('#su_first_name').val();
    var lastName = jQuery('#su_last_name').val();
    var email = jQuery('#su_email').val();
    var site = jQuery('#su_site').val();

    if (!firstName || !lastName || !email || !site)
        return;

    var code = null;

    fifu_block();

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'featured-image-from-url/v2/sign_up/',
        data: {
            "first_name": firstName,
            "last_name": lastName,
            "email": email,
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            code = data['code'];

            // duplicated
            if (code == -7 || code == -25)
                message(data, 'signup');

            // activation code
            if (code == 3)
                message(data, 'login');

            if (code > 0) {
                remove_sign_up();

                jQuery('#qrcode').children().remove();
                var qrcode = new QRCode(document.getElementById("qrcode"), {width: 150, height: 150});
                qrcode.makeCode('otpauth://totp/FIFU-Cloud:' + email + '?secret=' + data['qrcode'] + '&issuer=FIFU-Cloud');

                jQuery("#su_login_email").val(email);

                jQuery('#qrcode').show();
                jQuery('#qrcode-info-reset').hide();
                jQuery('#qrcode-info-signup').show();

                fifuScriptCloudVars.signUpComplete = true;
            }
            fifu_unblock();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            fifu_unblock();
        }
    });
    return code;
}

function login() {
    var email = jQuery('#su_login_email').val();
    var site = jQuery('#su_login_site').val();
    var tfa = jQuery('#su_login_2fa').val();

    if (!email || !site)
        return;

    var code = null;

    fifu_block();

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'featured-image-from-url/v2/login/',
        data: {
            "email": email,
            "tfa": tfa
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            code = data['code'];

            if (code > 0) {
                Cookies.set('fifu-tfa', data['fifu_tfa_hash']);

                fifu_hide_log_in();

                jQuery('#qrcode').hide();
                jQuery('#qrcode-info-signup').hide();
                jQuery('#qrcode-info-reset').hide();
                jQuery('#login_response_message').hide();
                jQuery('#su_login_reset').removeAttr('disabled');

                setTimeout(function () {
                    jQuery("#tabs-top").tabs("option", "active", 2);
                    listAllFifu(0);
                    fifu_enable_edition_buttons();
                }, 100);
            } else
                fifu_show_login();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function (data) {
            fifu_unblock();
        }
    });
    return code;
}

function logout() {
    fifu_block();
    jQuery.ajax({
        method: "POST",
        url: restUrl + 'featured-image-from-url/v2/logout/',
        data: {
            "tfa": Cookies.get('fifu-tfa'),
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            code = data['code'];

            if (code == 8) {
                jQuery("#su_login_email").val('');
                jQuery('#su_login_reset').removeAttr('disabled');
                fifu_show_login();
                fifu_disable_edition_buttons();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function (data) {
            fifu_unblock();
        }
    });
}

function cancel() {
    jQuery("#su-dialog-cancel").dialog("open");
}

function payment_info() {
    fifu_block();
    jQuery.ajax({
        method: "POST",
        url: restUrl + 'featured-image-from-url/v2/payment_info/',
        data: {
            "tfa": Cookies.get('fifu-tfa'),
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            // not connected
            if (data['code'] == -20) {
                fifu_show_login();
                fifu_disable_edition_buttons();
            } else
                message(data, 'payment_info');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function (data) {
            fifu_unblock();
        }
    });
}

function check_connection() {
    fifu_block();
    jQuery.ajax({
        method: "POST",
        url: restUrl + 'featured-image-from-url/v2/connected/',
        data: {
            "tfa": Cookies.get('fifu-tfa'),
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            if (data == null || data['code'] == 0) {
                data = new Object();
                data['message'] = 'web service is down';
                data['color'] = '#dd4c40';
                message(data, 'login');

                fifu_disable_edition_buttons();
                fifu_show_login();
                jQuery('#su_login_button').prop('disabled', true);
                jQuery('#su_login_reset').prop('disabled', true);

                fifu_unblock();
                return;
            } else {
                fifu_enable_edition_buttons();
                fifu_hide_log_in();
                jQuery('#su_login_button').prop('disabled', false);
                jQuery('#su_login_reset').prop('disabled', false);
            }

            code = data['code'];

            jQuery("#qrcode").hide();
            jQuery("#qrcode-info-reset").hide();
            jQuery("#qrcode-info-signup").hide();

            if (code == 7) {
                fifu_hide_log_in();
                fifu_enable_edition_buttons();
                data['message'] = 'connected';
                message(data, 'logout');
            } else {
                fifu_show_login();
                fifu_disable_edition_buttons();
                data['message'] = 'not connected';
                message(data, 'login');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            fifu_disable_edition_buttons();
            fifu_show_login();
            jQuery('#su_login_button').prop('disabled', true);
            jQuery('#su_login_reset').prop('disabled', true);

            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function (data) {
            fifu_unblock();
        }
    });
}

function resetCredentials() {
    var email = jQuery('#su_login_email').val();
    var site = jQuery('#su_login_site').val();

    if (!email || !site)
        return;

    var code = null;

    fifu_block();

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'featured-image-from-url/v2/reset_credentials/',
        data: {
            "email": email
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            code = data['code'];
            if (code > 0) {
                jQuery('#qrcode').children().remove();
                var qrcode = new QRCode(document.getElementById("qrcode"), {width: 150, height: 150});
                qrcode.makeCode('otpauth://totp/FIFU-Cloud:' + email + '?secret=' + data['qrcode'] + '&issuer=FIFU-Cloud');
                jQuery('#qrcode').show();
                jQuery('#qrcode-info-reset').show();
                jQuery('#qrcode-info-signup').hide();
                jQuery('#su_login_reset').attr('disabled', 'true');

                remove_sign_up();
            }
            message(data, 'login');
            fifu_unblock();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
            fifu_unblock();
        }
    });
    return code;
}

function listAllSu(page) {
    console.log(page);
    update = false;

    var table = jQuery('#removeTable').DataTable({
        "language": {"emptyTable": "No images available"},
        destroy: true,
        "columns": [{"width": "64px"}, {"width": "100%"}, {"width": "64px"}, {"width": "64px"}, {"width": "64px"}],
        "autoWidth": false,
        "order": [[3, 'desc']],
        dom: 'lfrtBip',
        select: true,
        buttons: [
            {
                text: 'select all',
                titleAttr: '1,000 rows limit',
                action: function () {
                    total_rows = table.rows().count();
                    amount = total_rows < MAX_ROWS ? total_rows : MAX_ROWS;
                    table.rows({search: 'applied'}, [...Array(amount).keys()]).select();
                    if (table.rows({selected: true}).count() == 0)
                        table.rows([...Array(amount).keys()]).select();
                }
            },
            {
                text: 'select none',
                action: function () {
                    table.rows().deselect();
                }
            },
            {
                text: '<i class="fas fa-folder-minus"></i> delete',
                attr: {
                    id: 'cloud-del'
                },
                action: function () {
                    jQuery("#su-dialog-remove").dialog("open");
                    update = true;
                }
            },
            {
                text: 'load more',
                action: function () {
                    if (table.rows().count() == MAX_ROWS || update)
                        listAllSu(page + 1);
                }
            },
        ]
    });

    table.clear();

    if (!fifuScriptCloudVars.signUpComplete)
        fifu_disable_edition_buttons();
    else
        check_connection();

    fifu_block();

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'featured-image-from-url/v2/list_all_su/',
        data: {
            "tfa": Cookies.get('fifu-tfa'),
            "page": page,
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            code = data['code'];
            if (code > 0) {
                var bucket = data['bucket'];
                var photo_data = data['photo_data'];
                for (var i = 0; i < photo_data.length; i++) {
                    imgTag = '<img id="' + photo_data[i]['storage_id'] + '" data-src="' + photo_data[i]['proxy_url'] + '" style="border-radius:5%; height:56px; width:56px; object-fit:cover; text-align:center">';

                    if (photo_data[i]['is_category'])
                        local = 'category';
                    else if (photo_data[i]['meta_key'].includes('slider'))
                        local = 'slider';
                    else if (photo_data[i]['meta_key'].includes('url_'))
                        local = 'gallery';
                    else
                        local = 'featured';

                    table.row.add([
                        imgTag,
                        photo_data[i]['title'],
                        photo_data[i]['date'],
                        photo_data[i]['post_id'],
                        local,
                        photo_data[i]['storage_id'],
                        photo_data[i]['meta_id'],
                        photo_data[i]['meta_key'],
                        photo_data[i]['is_category'],
                    ]);
                }
                table.draw(true);
            } else {
                // not connected
                if (data['code'] == -20) {
                    fifu_show_login();
                    fifu_disable_edition_buttons();
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function (data) {
            fifu_unblock();
        }
    });

    jQuery("#su-dialog-remove").dialog({
        autoOpen: false,
        modal: true,
        width: "400px",
        buttons: {
            "Delete": function () {
                selected = table.rows({selected: true});
                count = selected.count();

                if (count == 0)
                    return;

                var arr = [];
                for (var i = 0; i < count; i++) {
                    data = selected.data()[i];
                    arr.push({
                        'storage_id': data[5],
                    });
                }
                fifu_block();
                jQuery(this).dialog("close");
                jQuery.ajax({
                    method: "POST",
                    url: restUrl + 'featured-image-from-url/v2/delete/',
                    data: {
                        "selected": arr,
                        "tfa": Cookies.get('fifu-tfa'),
                    },
                    async: true,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
                    },
                    success: function (data) {
                        table.rows().deselect();

                        // not connected
                        if (data['code'] == -20) {
                            fifu_show_login();
                            fifu_disable_edition_buttons();
                        }
                        // else
                        //     message(data, 'delete');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    },
                    complete: function (data) {
                        selected.remove().draw(false);

                        if (table.rows().count() == 0)
                            listAllSu(0);

                        jQuery(window).lazyLoadXT();
                        fifu_unblock();
                    }
                });
            },
            Cancel: function () {
                jQuery(this).dialog("close");
            }
        }
    });

    // limit number of rows selected
    table.on('select', function (e, dt, type, ix) {
        var selected = dt.rows({selected: true});
        if (selected.count() > MAX_ROWS)
            dt.rows(ix).deselect();
    });

    jQuery('div#removeTable_filter label input').on('keyup', function () {
        jQuery(window).lazyLoadXT();
    });
}

jQuery(document).ready(function ($) {
    jQuery.extend(jQuery.lazyLoadXT, {
        srcAttr: 'data-src',
        visibleOnly: true,
        updateEvent: 'load orientationchange resize scroll touchmove focus hover'
    });
});

jQuery(window).on('ajaxComplete', function () {
    jQuery(window).lazyLoadXT();
});

jQuery(document).on("click", "a.paginate_button, select, th.sorting_asc, th.sorting_desc", function () {
    jQuery(window).lazyLoadXT();
});

jQuery(document).ready(function ($) {
    jQuery('#addTable tbody').on('click', 'tr', function () {
        jQuery(this).toggleClass('selected');
    });
});

const MAX_ROWS = 1000;
const MAX_ROWS_BY_REQUEST = MAX_ROWS / 10;

function listAllFifu(page) {
    console.log(page);
    update = false;

    var table = jQuery('#addTable').DataTable({
        "language": {"emptyTable": "No images available"},
        destroy: true,
        "columns": [{"width": "64px"}, {"width": "100%"}, {"width": "64px"}, {"width": "64px"}, {"width": "64px"}],
        "autoWidth": false,
        "order": [[3, 'desc']],
        dom: 'lfrtBip',
        select: true,
        buttons: [
            {
                text: 'select all',
                titleAttr: '1,000 rows limit',
                action: function () {
                    total_rows = table.rows().count();
                    amount = total_rows < MAX_ROWS ? total_rows : MAX_ROWS;
                    table.rows({search: 'applied'}, [...Array(amount).keys()]).select();
                    if (table.rows({selected: true}).count() == 0)
                        table.rows([...Array(amount).keys()]).select();
                }
            },
            {
                text: 'select none',
                action: function () {
                    table.rows().deselect();
                }
            },
            {
                text: '<i class="fas fa-folder-plus"></i> upload',
                attr: {
                    id: 'cloud-add'
                },
                action: function () {
                    addSu(table);
                    update = true;
                }
            },
            {
                text: 'load more',
                action: function () {
                    if (table.rows().count() == MAX_ROWS || update)
                        listAllFifu(page + 1);
                }
            },
        ]
    });
    table.clear();

    if (!fifuScriptCloudVars.signUpComplete)
        fifu_disable_edition_buttons();
    else
        check_connection();

    fifu_block();
    jQuery.ajax({
        method: "POST",
        url: restUrl + 'featured-image-from-url/v2/list_all_fifu/',
        data: {
            "page": page,
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            for (var i = 0; i < data.length; i++) {
                imgTag = '<img id="' + data[i]['meta_id'] + '" data-src="' + data[i]['url'] + '" style="border-radius:5%; height:56px; width:56px; object-fit:cover; text-align:center">';

                if (data[i]['category'] == 1)
                    local = 'category';
                else if (data[i]['meta_key'].includes('slider'))
                    local = 'slider';
                else if (data[i]['meta_key'].includes('url_'))
                    local = 'gallery';
                else
                    local = 'featured';

                table.row.add([
                    imgTag,
                    data[i]['post_title'],
                    data[i]['post_date'],
                    data[i]['post_id'],
                    local,
                    data[i]['url'],
                    data[i]['meta_key'],
                    data[i]['meta_id'],
                    data[i]['category'],
                    data[i]['video_url']
                ]);
            }
            table.draw(true);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function (data) {
            fifu_unblock();
        }
    });

    // limit number of rows selected
    table.on('select', function (e, dt, type, ix) {
        var selected = dt.rows({selected: true});
        if (selected.count() > MAX_ROWS)
            dt.rows(ix).deselect();
    });

    jQuery('div#addTable_filter label input').on('keyup', function () {
        jQuery(window).lazyLoadXT();
    });
}

async function addSu(table) {
    selected = table.rows({selected: true});
    count = selected.count();

    if (count == 0)
        return;

    fifu_block_progress();

    var arr = [];
    var finished = 0;
    for (var i = 0; i < count; i++) {
        data = selected.data()[i];
        arr.push([
            data[3], // post_id
            data[5], // url
            data[6], // meta_key
            data[7], // meta_id
            data[8], // category
            data[9]  // video_url
        ]);
        if (i + 1 == count || (i > 0 && i % MAX_ROWS_BY_REQUEST == 0)) {
            jQuery.ajax({
                method: "POST",
                url: restUrl + 'featured-image-from-url/v2/create_thumbnails_list/',
                data: {
                    "selected": arr,
                    "tfa": Cookies.get('fifu-tfa'),
                },
                async: true,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
                },
                success: function (data) {
                    // not connected
                    if (data['code'] == -20) {
                        fifu_show_login();
                        fifu_disable_edition_buttons();
                    }
                    // else
                    //     message(data, 'add');
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function (data) {
                    finished++;
                    progress = 100 * finished / (count / MAX_ROWS_BY_REQUEST);
                    jQuery('#progressBar').attr('value', progress);
                    jQuery('#progressBar').attr('text', progress);
                    if (finished >= count / MAX_ROWS_BY_REQUEST) {
                        if (data['responseJSON']['code'] == -24 || data['responseJSON']['code'] == -20) {
                            // none
                        } else {
                            // success
                            selected.remove().draw(false);

                            if (table.rows().count() == 0)
                                listAllFifu(0);
                        }
                        jQuery(window).lazyLoadXT();
                        fifu_unblock();
                    }
                }
            });
            await sleep(2000);
            arr = [];
        }
    }
}

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

function remove_sign_up() {
    jQuery("#sign-up-box").remove();
}

function message(data, box) {
    selector = "#" + box + "_response_message";
    jQuery(selector).css('background-color', data['color']);
    jQuery(selector).css('border-radius', '3px');
    jQuery(selector).css('padding', '6px');
    jQuery(selector).css('color', 'white');
    jQuery(selector).css('font-size', '15px');
    jQuery(selector).val(data['message']);
    jQuery(selector).show();
}

function confirmResetCredentials() {
    if (jQuery("#su_login_email").val())
        jQuery("#su-dialog-reset-credentials").dialog("open");
}

jQuery(function () {
    jQuery("#su-dialog-reset-credentials").dialog({
        autoOpen: false,
        modal: true,
        width: "300px",
        buttons: {
            OK: function () {
                resetCredentials();
                jQuery(this).dialog("close");
            },
            Cancel: function () {
                jQuery(this).dialog("close");
            }
        },
        open: function (event, ui) {
            jQuery(this).parent().find('.ui-dialog-titlebar').empty();
            jQuery(this).parent().find('.ui-dialog-titlebar').append('<i class="fa fa-exclamation-triangle"></i> Are you sure?');
            jQuery(this).parent().children().children('.ui-dialog-titlebar-close').hide();
        },
    });

    jQuery("#su-dialog-cancel").dialog({
        autoOpen: false,
        modal: true,
        width: "400px",
        buttons: {
            "Yes": function () {
                fifu_block();
                jQuery(this).dialog("close");
                jQuery.ajax({
                    method: "POST",
                    url: restUrl + 'featured-image-from-url/v2/cancel/',
                    data: {
                        "tfa": Cookies.get('fifu-tfa'),
                    },
                    async: true,
                    beforeSend: function (xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
                    },
                    success: function (data) {
                        // not connected
                        if (data['code'] == -20) {
                            fifu_show_login();
                            fifu_disable_edition_buttons();
                        } else
                            message(data, 'cancel');
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.log(jqXHR);
                        console.log(textStatus);
                        console.log(errorThrown);
                    },
                    complete: function (data) {
                        fifu_unblock();
                    }
                });
            },
            "No": function () {
                jQuery(this).dialog("close");
            }
        }
    });
});

function fifu_block() {
    jQuery('#tabs-top').block({message: '', css: {backgroundColor: 'none', border: 'none', color: 'white'}});
    jQuery('button').attr('disabled', 'true');
}

function fifu_block_progress() {
    jQuery('#tabs-top').block({message: '<progress id="progressBar" max="100" value="0" style="width:100%;height:32px;background-color:#23282d"></progress>', css: {backgroundColor: 'none', border: 'none', color: 'white'}});
    jQuery('button').attr('disabled', 'true');
}

function fifu_unblock() {
    jQuery('#tabs-top').unblock();
    jQuery('button').removeAttr('disabled');
}

function fifu_show_login() {
    jQuery("#log-in-box").show();
    jQuery("#log-out-box").hide();
    jQuery("#payment-info-box").hide();
    jQuery("#cancel-box").hide();
    jQuery("#su_login_2fa").val('');
    jQuery("#upload-auto-box").hide();
}

function fifu_hide_log_in() {
    jQuery("#log-in-box").hide();
    jQuery("#log-out-box").show();
    jQuery("#payment-info-box").show();
    jQuery("#cancel-box").show();
    jQuery("#upload-auto-box").show();
}

function fifu_disable_edition_buttons() {
    jQuery("button#cloud-add").attr('disabled', 'true');
    jQuery("button#cloud-del").attr('disabled', 'true');
    data = new Array();
    data['message'] = 'not connected';
    data['color'] = '#ea4335';
    message(data, 'add');
    message(data, 'delete');
    message(data, 'billing');
}

function fifu_enable_edition_buttons() {
    jQuery("button#cloud-add").removeAttr('disabled');
    jQuery("button#cloud-del").attr('disabled');
    jQuery('#add_response_message').hide();
    jQuery('#delete_response_message').hide();
    jQuery('#billing_response_message').hide();
}

function listAllMediaLibrary(page) {
    console.log(page);
    update = false;

    var table = jQuery('#mediaTable').DataTable({
        "language": {"emptyTable": "No posts available"},
        destroy: true,
        "columns": [{"width": "64px"}, {"width": "100%"}, {"width": "64px"}, {"width": "64px"}, {"width": "64px"}],
        "autoWidth": false,
        "order": [[3, 'desc']],
        dom: 'lfrtBip',
        select: true,
        buttons: [
            {
                text: 'select all',
                titleAttr: '1,000 rows limit',
                action: function () {
                    total_rows = table.rows().count();
                    amount = total_rows < MAX_ROWS ? total_rows : MAX_ROWS;
                    table.rows({search: 'applied'}, [...Array(amount).keys()]).select();
                    if (table.rows({selected: true}).count() == 0)
                        table.rows([...Array(amount).keys()]).select();
                }
            },
            {
                text: 'select none',
                action: function () {
                    table.rows().deselect();
                }
            },
            {
                text: '<i class="fas fa-link"></i> link',
                attr: {
                    id: 'cloud-link'
                },
                action: function () {
                    update = true;
                }
            },
            {
                text: 'load more',
                action: function () {
                    if (table.rows().count() == MAX_ROWS || update)
                        listAllMediaLibrary(page + 1);
                }
            },
        ]
    });
    table.buttons().disable();
    table.clear();

    fifu_block();
    jQuery.ajax({
        method: "POST",
        url: restUrl + 'featured-image-from-url/v2/list_all_media_library/',
        data: {
            "page": page,
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            for (var i = 0; i < data.length; i++) {
                imgTag = '<img id="' + data[i]['meta_id'] + '" data-src="' + data[i]['url'] + '" style="border-radius:5%; height:56px; width:56px; object-fit:cover; text-align:center">';
                table.row.add([
                    imgTag,
                    data[i]['post_title'],
                    data[i]['post_date'],
                    data[i]['post_id'],
                    data[i]['gallery_ids'] ? data[i]['gallery_ids'].split(',').length : 0,
                    data[i]['url'],
                    data[i]['thumbnail_id'],
                    data[i]['gallery_ids'],
                    data[i]['category'],
                ]);
            }
            table.draw(true);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function (data) {
            fifu_unblock();
        }
    });

    // limit number of rows selected
    table.on('select', function (e, dt, type, ix) {
        var selected = dt.rows({selected: true});
        if (selected.count() > MAX_ROWS)
            dt.rows(ix).deselect();
    });

    jQuery('div#addTable_filter label input').on('keyup', function () {
        jQuery(window).lazyLoadXT();
    });
}

function listDailyCount() {
    var table = jQuery('#billingTable').DataTable({
        "language": {"emptyTable": "No data available"},
        destroy: true,
        "columns": [{"width": "64px"}, {"width": "100%"}],
        "autoWidth": false,
        "order": [[0, 'desc']],
        dom: 'lfrtBip',
        select: false,
        "iDisplayLength": 30,
    });

    table.clear();

    if (!fifuScriptCloudVars.signUpComplete)
        fifu_disable_edition_buttons();
    else
        check_connection();

    fifu_block();

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'featured-image-from-url/v2/list_daily_count/',
        data: {
            "tfa": Cookies.get('fifu-tfa'),
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            code = data['code'];
            if (code > 0) {
                var dc_data = data['dc_data'];
                jQuery('#billing-start').html(data['start_date'].split('+')[0]);
                jQuery('#billing-end').html(data['end_date'].split('+')[0]);
                jQuery('#billing-average').html(data['quantity']);
                jQuery('#billing-cost').html('US$ ' + data['amount_due']);
                for (var i = 0; i < dc_data.length; i++) {
                    table.row.add([
                        dc_data[i]['date'],
                        dc_data[i]['quantity'],
                    ]);
                }

                jQuery(".tier-row").remove();
                tiers = data['tiers'];
                current_tier = data['current_tier'];
                for (let key in tiers) {
                    icon = (parseFloat(key) == current_tier) ? '<i class="fa-solid fa-arrow-left"></i>' : '';
                    jQuery("#tiers-table").append(`<tr class="color tier-row"><td>${tiers[key]}</td><td>$${key}</td><td>${icon}</td></tr>`);
                }

                table.draw(true);
            } else {
                // not connected
                if (data['code'] == -20) {
                    fifu_show_login();
                    fifu_disable_edition_buttons();
                    message(data, 'billing');
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function (data) {
            fifu_unblock();
        }
    });
}

function set_upload_auto() {
    toggle = jQuery("#fifu_toggle_cloud_upload_auto").attr('class');

    var code = null;

    fifu_block();

    jQuery.ajax({
        method: "POST",
        url: restUrl + 'featured-image-from-url/v2/cloud_upload_auto/',
        data: {
            "tfa": Cookies.get('fifu-tfa'),
            "toggle": toggle,
        },
        async: true,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuScriptVars.nonce);
        },
        success: function (data) {
            code = data['code'];
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function (data) {
            fifu_unblock();
        }
    });
    return code;
}
