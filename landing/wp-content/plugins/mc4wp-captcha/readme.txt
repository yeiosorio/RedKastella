=== MailChimp for WordPress - Captcha ===
Contributors: Ibericode, DvanKooten, hchouhan, lapzor
Donate link: https://mc4wp.com/#utm_source=wp-plugin-repo&utm_medium=mailchimp-top-bar&utm_campaign=donate-link
Tags: mailchimp, mc4wp, captcha, bws captcha
Requires at least: 3.8
Tested up to: 4.6
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Add a Captcha field to your MailChimp for WordPress sign-up forms.

== Description ==

Add a Captcha field to your MailChimp sign-up forms.

This plugin has the following requirements.

- [MailChimp for WordPress](https://wordpress.org/plugins/mailchimp-for-wp/) (v3.0 or later)
- [Captcha by BestWebSoft](https://wordpress.org/plugins/captcha/)

After installing the plugin, adding the following code to your sign-up forms will render a Captcha field.

`
{captcha}
`


== Installation ==

= MailChimp for WordPress - Captcha =

Since this plugin depends on the [MailChimp for WordPress plugin](https://wordpress.org/plugins/mailchimp-for-wp/), you will need to install that first.

= Installing the plugin =

1. In your WordPress admin panel, go to *Plugins > New Plugin*, search for **MailChimp for WordPress - Captcha** and click "*Install now*"
1. Alternatively, download the plugin and upload the contents of `mailchimp-top-bar.zip` to your plugins directory, which usually is `/wp-content/plugins/`.
1. Activate the plugin
1. Add the following code to your form, where you want the captcha to appear.

`
{captcha}
`

== Frequently Asked Questions ==

= How does this work? =

After activating the plugin, you can render a Captcha field by adding the following code to your form mark-up.

`
{captcha}
`


== Screenshots ==



== Changelog ==


#### 1.0.2 - August 2, 2016

**Improvements**

- Compatibility with [upcoming MailChimp for WordPress 4.0 release](https://mc4wp.com/kb/upgrading-to-4-0/).


#### 1.0.1 - March 1, 2016

**Fixes**

- Captcha validation always passing, regardless of input value.


#### 1.0 - November 23, 2015

Initial release.
== Upgrade Notice ==
