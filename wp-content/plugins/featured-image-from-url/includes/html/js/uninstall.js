jQuery(document).ready(function ($) {
    jQuery('a#deactivate-featured-image-from-url').click(function (e) {
        e.preventDefault();
        jQuery.fancybox.open(`
            <table>`
                +
                `             
                <tr>
                    <td><button class="uninstall" style="background-color:#f44336" id="pre-deactivate">${fifuUninstallVars.buttonTextClean}</button></td>
                    <td><button class="uninstall" style="width:100%;background-color:#008CBA" id="deactivate">${fifuUninstallVars.buttonTextDeactivate}</button></td>
                </tr>
                <tr>
                    <td style="color:black;text-align:center">${fifuUninstallVars.buttonDescriptionClean}</td>
                    <td style="color:black;text-align:center">${fifuUninstallVars.buttonDescriptionDeactivate}</td>
                </tr>
            </table>
            <br>
            <hr>
            <h4>${fifuUninstallVars.textWhy} <span style="color:grey">${fifuUninstallVars.textEmail}</span></h4>
            <input id="fifu-email" style="width:100%;padding:10px;font-size:13px" placeholder="example@mail.com"></input>
            <br><br>
            <textarea id="fifu-description" style="width:100%;height:135px;padding:10px;font-size:13px" placeholder="${fifuUninstallVars.textReasonConflict}&#013;${fifuUninstallVars.textReasonPro}&#013;${fifuUninstallVars.textReasonSeo}...&#013;${fifuUninstallVars.textReasonLocal}&#013;${fifuUninstallVars.textReasonUndestand}&#013;${fifuUninstallVars.textReasonOthers}"></textarea>
        `);
    });

    jQuery(document).on("click", "button#deactivate", function () {
        let description = jQuery('textarea#fifu-description').val();
        let email = jQuery('input#fifu-email').val();
        let temporary = true;

        if (description || email) {
            jQuery('.fancybox-slide').block({message: '', css: {backgroundColor: 'none', border: 'none', color: 'white'}});
            setTimeout(function () {
                send_feedback(description, email, temporary);
            }, 250);
        }

        href = jQuery('a#deactivate-featured-image-from-url').attr('href');
        window.location.href = href;
    });

    jQuery(document).on("click", "button#pre-deactivate", function () {
        let description = jQuery('textarea#fifu-description').val();
        let email = jQuery('input#fifu-email').val();
        let temporary = false;

        jQuery('.fancybox-slide').block({message: '', css: {backgroundColor: 'none', border: 'none', color: 'white'}});
        setTimeout(function () {
            jQuery.ajax({
                method: "POST",
                url: fifuUninstallVars.restUrl + 'featured-image-from-url/v2/pre_deactivate/',
                data: {
                    "description": description,
                    "email": email,
                    "temporary": temporary,
                },
                async: false,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', fifuUninstallVars.nonce);
                },
                success: function (data) {
                    href = jQuery('a#deactivate-featured-image-from-url').attr('href');
                    window.location.href = href;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function () {
                    // jQuery('.fancybox-slide').unblock();
                }
            });
        }, 250);
    });

    // activating fifu pro
    jQuery('a#activate-fifu-premium, a#activate-featured-image-from-url-fifu-premium').click(function (e) {
        e.preventDefault();

        jQuery('div#wpwrap').block({message: '', css: {backgroundColor: 'none', border: 'none', color: 'white'}});
        setTimeout(function () {
            jQuery.ajax({
                method: "POST",
                url: fifuUninstallVars.restUrl + 'featured-image-from-url/v2/deactivate_itself/',
                data: {},
                async: false,
                beforeSend: function (xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', fifuUninstallVars.nonce);
                },
                success: function (data) {
                    href = jQuery('a#activate-fifu-premium').attr('href');
                    if (!href)
                        href = jQuery('a#activate-featured-image-from-url-fifu-premium').attr('href');
                    window.location.href = href;
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR);
                    console.log(textStatus);
                    console.log(errorThrown);
                },
                complete: function () {
                }
            });
        }, 250);
    });
});

function send_feedback(description, email, temporary) {
    jQuery.ajax({
        method: "POST",
        url: fifuUninstallVars.restUrl + 'featured-image-from-url/v2/feedback/',
        data: {
            "description": description,
            "email": email,
            "temporary": temporary,
        },
        async: false,
        beforeSend: function (xhr) {
            xhr.setRequestHeader('X-WP-Nonce', fifuUninstallVars.nonce);
        },
        success: function (data) {
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorThrown);
        },
        complete: function () {
        }
    });
}
