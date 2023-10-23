jQuery(document).ready(function () {
    const payment_title = jQuery(".wc-order-totals").find(".description").text();
    jQuery(".wc-order-totals").find(".description").html(payment_title);
    jQuery(".billing_address").find(".description").each(function() {
        let payment_title_on_list = jQuery(this).text();
        jQuery(this).html(payment_title_on_list);
    });
});