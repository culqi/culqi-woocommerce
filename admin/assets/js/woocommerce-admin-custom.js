jQuery(document).ready(function () {
    let payment_title = jQuery(".wc-order-totals").find(".description").text();
    jQuery(".wc-order-totals").find(".description").html(payment_title);
});