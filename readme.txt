=== Markup (JSON-LD) structured in schema.org ===
Contributors: miiitaka
Tags: schema, schema.org, json, json-ld, seo, post, posts, google, shortcode, breadcrumb
Requires at least: 4.3.1
Tested up to: 4.4
Stable tag: 2.2.1

It is plug in to implement structured markup (JSON-LD syntax) by schema.org definition on an post page, fixed page and etc.

== Description ==

It is plug in to implement structured markup (JSON-LD syntax) by schema.org definition on an post page, fixed page and etc.
Base knowledge is "https://schema.org/" and "https://developers.google.com/structured-data/"

= Schema.org Type =

* Article: http://schema.org/Article
* BlogPosting: http://schema.org/BlogPosting
* BreadcrumbList: https://schema.org/BreadcrumbList
* Event: https://schema.org/Event
* NewsArticle: http://schema.org/NewsArticle
* Organization: https://schema.org/Organization
* Website: https://schema.org/WebSite

= Breadcrumb =

You can display the breadcrumbs in the short code. Breadcrumb definition is available even if not active.

[ Example ]
`
<?php
if (shortcode_exists('wp-structuring-markup-breadcrumb')) {
	echo do_shortcode('[wp-structuring-markup-breadcrumb]');
}
?>
`

== Installation ==

* A plug-in installation screen is displayed in the WordPress admin panel.
* It installs in `wp-content/plugins`.
* The plug-in is activated.
* Open 'Schema.org Setting' menu.

== Changelog ==

= 2.2.1 (2015-12-21) =

* Fixed : Breadcrumb ShortCode minor bug fixed.

= 2.2.0 (2015-12-16) =

* Updated : Updated on December 14, 2015 was structured data (Article) AMP correspondence. https://developers.google.com/structured-data/rich-snippets/articles?hl=ja

= 2.1.3 (2015-12-11) =

* Fixed : Minor bug fixed.

= 2.1.2 (2015-12-09) =

* Check : WordPress version 4.4 operation check.

= 2.1.1 (2015-12-04) =

* Added : Add the table update processing at the time of version up.

= 2.1.0 (2015-12-03) =

* Added : Schema.org type "Event" schema.org definition Add "Event" custom post output that works.

= 2.0.2 (2015-11-27) =

* Fixed : Breadcrumb ShortCode minor bug fixed.

= 2.0.1 (2015-11-25) =

* Fixed : Notice error fixed.

= 2.0.0 (2015-11-23) =

* Added : Schema.org type "BreadcrumbList" schema.org definition Add breadcrumbs short code output that works.
* Updated : Schema.org definition immobilization.

= 1.3.2 (2015-11-17) =

* Fixed : Fixed translation.
* Added : Additional output comment.

= 1.3.1 =

* Fixed : none-function fixed.

= 1.3.0 =

* Added : Localizing into Japanese.

= 1.2.1 =

* Updated : Read admin menu css -> wp_register_style setting
* Fixed : Typo Missing.

= 1.2.0 =

* Added : Schema.org type "BlogPosting".

= 1.1.3 =

* Fixed : To escape a newline code and the tag of the body attribute in Type "Article" and "NewsArticle".

= 1.1.2 =

* Updated : Schema.org type "Article" image attribute.

= 1.1.0 =

* Added : Schema.org type "Article".

= 1.0.1 - 1.0.8 =

* Fixed : Missing plugin path setting.

= 1.0.0 =

* First release of this plugin.

== Contact ==

* email to foundationmeister[at]outlook.com
* twitter @miiitaka
