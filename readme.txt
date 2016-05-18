=== Markup (JSON-LD) structured in schema.org ===
Contributors: miiitaka
Tags: schema, schema.org, json, json-ld, seo, post, posts, google, shortcode, breadcrumb
Requires at least: 4.3.1
Tested up to: 4.5.2
Stable tag: 2.5.1

Allows you to include schema.org JSON-LD syntax markup on your website

== Description ==

Allows you to include schema.org JSON-LD syntax markup on your website
Base knowledge is "https://schema.org/" and "https://developers.google.com/structured-data/"

= Schema.org Type =

* Article: http://schema.org/Article
* BlogPosting: http://schema.org/BlogPosting
* BreadcrumbList: https://schema.org/BreadcrumbList
* Event: https://schema.org/Event
* LocalBusiness : http://schema.org/LocalBusiness
* NewsArticle: http://schema.org/NewsArticle
* Organization: https://schema.org/Organization
* Person: https://schema.org/Person
* Website: https://schema.org/WebSite

= Breadcrumb =

You can display the breadcrumbs in the short code. Breadcrumb definition is available even if not active.

[ Example ]
`
<?php
if ( shortcode_exists( 'wp-structuring-markup-breadcrumb' ) ) {
	echo do_shortcode( '[wp-structuring-markup-breadcrumb]' );
}
?>
`

[ ShortCode Options ]

* Option : id="id_name" attribute additional ol element.
* Option : class="class_name" attribute additional ol element.


== Installation ==

* A plug-in installation screen is displayed in the WordPress admin panel.
* It installs in `wp-content/plugins`.
* The plug-in is activated.
* Open 'Schema.org Setting' menu.

== Changelog ==

= 2.5.1 (2016-05-09) =

* Checked : WordPress version 4.5.2 operation check.
* Checked : WordPress version 4.5.1 operation check.

= 2.5.0 (2016-04-19) =

* Updated : You can select a custom posts at the output page.
* Updated : Add the output on "Pages" in the "Article","BlogPosting" and "NewsArticle" Schema.org type.
* Updated : It added the Holiday Opening Hour of items to Schema.org Type "LocalBusiness".
* Updated : It added the GeoCircle of items to Schema.org Type "LocalBusiness".
* Updated : Japanese translation.
* Checked : WordPress version 4.5.0 operation check.

= 2.4.2 (2016-03-09) =

* Fixed : Updated image size detection to use curl first, as attachment_url_to_postid() hits the database
* Updated : Added a transient cache class to cache taxing operations
* Updated : Turkish translation.

= 2.4.1 (2016-03-01) =

* Updated : Japanese translation.

= 2.4.0 (2016-02-06) =

* Added : Schema.org type "Person".
* Updated : Schema.org type "LocalBusiness" OpenHours : shift time setting.
* Checked : WordPress version 4.4.2 operation check.

= 2.3.3 (2016-01-19) =

* Fixed : Improved wording on admin pages.
* Fixed : Added alternate methods to get image dimensions for systems running legacy SSL.

= 2.3.2 (2016-01-10) =

* Fixed : Fixed a bug that Organization type of display error of contactType comes out.
* Fixed : Fixed a bug that can not save LocalBusiness type of latitude and longitude.

= 2.3.1 (2016-01-07) =

* Checked : WordPress version 4.4.1 operation check.
* Added : Breadcrumb ShortCode option add.

= 2.3.0 (2016-01-03) =

* Added : Schema.org type "LocalBusiness".
* Fixed : Organization Definition minor bug fixed.

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
