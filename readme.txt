=== Culqi ===
Contributors: culqionline
Tags: culqi, checkout, payment method, woocommerce, peru
Donate link: https://culqi.com/
Requires at least: 5.0
Tested up to: 6.9
Stable tag: 4.0.0
Requires PHP: 7.4
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: culqi
Domain Path: /languages

Accept payments with debit and credit cards, Yape, Cuotéalo BCP, and PagoEfectivo on WooCommerce.

== Description ==
Spanish: https://github.com/culqi/culqi-woocommerce

Sell securely and steadily with our CulqiOnline payment gateway from your online store! We accept all cards, PagoEfectivo, major mobile wallets, and Cuotéalo BCP so your clients have all payment methods available in a single checkout.

= Benefits =

* **Integrate in a few steps:** Enter your CulqiPanel credentials and choose the environment where you will activate the checkout. Test as many times as you need in the integration environment with our test cards.
* **Stability and security:** PCI-DSS certification and double security layer with our antifraud engine and 3DS authentication.
* Personalize your checkout with your business logo and colors.
* Control your sales through CulqiPanel.
* Better shopping experience for your customers with our new checkout version on desktop and mobile.

= Features =

Start your Culqi plugin configuration by activating Culqi Checkout. Select the environment and enter your CulqiPanel credentials. Your keys will be loaded automatically.

Available payment methods:

* Credit and debit cards
* Yape payment button
* Mobile wallets (QR code)
* Mobile or internet banking / Agents (CIP code)
* Cuotéalo BCP (installments)

With this plugin you can:

* Manage charges
* Manage payment orders (PagoEfectivo, Mobile Wallets, Cuotéalo)
* Activate Culqi checkout as a payment method
* Process refunds with one click from WooCommerce orders
* Have an activity log for each WooCommerce order
* Customize your checkout with your brand colors and logo

== Installation ==

1. Go to **Plugins → Add New** in your WordPress dashboard
2. Search for **Culqi** and click Install
3. Activate the plugin
4. Go to **WooCommerce → Settings → Culqi**
5. Activate Culqi Checkout, select your environment (test/live), and enter your CulqiPanel credentials
6. Select the payment methods you want to offer
7. Customize the checkout with your brand colors and logo
8. Save your changes

For help, contact: team.integration@culqi.com or WhatsApp: 996373833

== FAQ ==

= How do I get Culqi keys? =

Register at [https://www.culqi.com/](https://www.culqi.com/) to get your public and private keys.

= Is it compatible with WooCommerce? =

Yes, you can process payments, orders, and refunds using WooCommerce.

= Can I use PagoEfectivo, Mobile Wallets, and Cuotéalo? =

Yes. These require purchase orders, which are generated automatically. You must configure a webhook to receive payment notifications.

= How do I configure the webhook? =

1. Go to CulqiPanel → Events → Webhooks
2. Select the event: `order.status.changed`
3. Set the URL to: `https://yourdomain.com/fullculqi-api/webhooks`

== Screenshots ==

1. Plugin configuration
2. Checkout customization
3. Payment charges
4. Order management
5. Webhook configuration

== Changelog ==

= 4.0.0 =
* Updated to WooCommerce latest version
* Improved checkout experience

= 1.0.0 =
* Initial release
* Custom Checkout V4
* 3DS support

== Upgrade Notice ==

This plugin requires WooCommerce 2.6.11 or higher.
