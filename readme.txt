=== Markup (JSON-LD) structured in schema.org ===
Contributors: miiitaka
Tags: schema, schema.org, json, json-ld, seo, post, posts, google, shortcode, breadcrumb
Requires at least: 4.3.1
Tested up to: 5.6.1
Stable tag: 4.8.1

Allows you to include schema.org JSON-LD syntax markup on your website

== Description ==

Allows you to include schema.org JSON-LD syntax markup on your website
Base knowledge is "https://schema.org/" and "https://developers.google.com/structured-data/"

= Schema.org Type =

* Article: https://schema.org/Article
* BlogPosting: https://schema.org/BlogPosting
* BreadcrumbList: https://schema.org/BreadcrumbList
* Event: https://schema.org/Event
* LocalBusiness : https://schema.org/LocalBusiness
* NewsArticle: https://schema.org/NewsArticle
* Organization: https://schema.org/Organization
* Person: https://schema.org/Person
* SiteNavigation: https://schema.org/SiteNavigationElement
* Speakable: https://pending.schema.org/speakable
* Video: https://schema.org/Video
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

[ Schema Filters ]

* Filter Name: structuring_markup_meta_article ( Article )
* Filter Name: structuring_markup_meta_blog_posting ( Blog Posting )
* Filter Name: structuring_markup_meta_breadcrumb ( Breadcrumb List )
* Filter Name: structuring_markup_meta_event ( Event )
* Filter Name: structuring_markup_meta_local_business ( Local Business )
* Filter Name: structuring_markup_meta_news_article ( News Article )
* Filter Name: structuring_markup_meta_organization ( Organization )
* Filter Name: structuring_markup_meta_person ( Person )
* Filter Name: structuring_markup_meta_site_navigation ( Site Navigation )
* Filter Name: structuring_markup_meta_video ( Video )
* Filter Name: structuring_markup_meta_website ( WebSite )

== Installation ==

* A plug-in installation screen is displayed in the WordPress admin panel.
* It installs in `wp-content/plugins`.
* The plug-in is activated.
* Open 'Schema.org Setting' menu.

== Changelog ==

= 4.8.1 (2020-04-26)
* Fixed : http://schema.org/ -> https://schema.org/.
* Checked : WordPress version 5.7.1 operation check.
* Checked : WordPress version 5.7.0 operation check.

= 4.8.0 (2020-02-14) =
* Fixed : Schema Type Local Business: Nesting of ternary operators.
* Checked : WordPress version 5.6.1 operation check.

= 4.7.0 (2020-01-13) =
* Updated : Added categories to videos and event types.
* Fixed : HTML tags are removed when outputting breadcrumbs.
* Fixed : Schema type Article: bug: 1st image in content is always used if check is on, even if no image present.
* Checked : WordPress version 5.3.2 operation check.
* Checked : WordPress version 5.3.1 operation check.
* Checked : WordPress version 5.3.0 operation check.
* Checked : WordPress version 5.2.5 operation check.
* Checked : WordPress version 5.2.4 operation check.
* Checked : WordPress version 5.2.3 operation check.
* Checked : WordPress version 5.2.2 operation check.
* Checked : WordPress version 5.2.1 operation check.
* Checked : WordPress version 5.2.0 operation check.
* Checked : WordPress version 5.1.3 operation check.
* Checked : WordPress version 5.1.2 operation check.
* Checked : WordPress version 5.1.1 operation check.

= 4.6.5 (2019-03-11) =
* Fixed : A bug where the modification date is fixed to GMT time.

= 4.6.4 (2019-02-26) =
* Checked : WordPress version 5.1.0 operation check.
* Fixed : Notice Error.

= 4.6.3 (2019-02-02) =
* Checked : WordPress version 5.0.3 operation check.
* Updated : Filters for each schema.

= 4.6.2 (2019-01-06) =
* Checked : WordPress version 5.0.2 operation check.
* Checked : WordPress version 5.0.1 operation check.
* Checked : WordPress version 5.0.0 operation check.
* Fixed : Version information missing at new registration.

= 4.6.1 (2018-09-19) =
* Fixed : Typo Error.

= 4.6.0 (2018-09-19) =
* Updated : Schema type Breadcrumbs: Added switching between home_url () and site_url ().
* Updated : Organization schema.org type subdivision.

= 4.5.3 (2018-09-07) =
* Fixed : Fixed broken links.
* Fixed : Fixed bug when displaying Config menu.

= 4.5.2 (2018-09-03) =
* Fixed : Changed title fixing of breadcrumbs on page 404.

= 4.5.0 (2018-08-17) =
* Checked : WordPress version 4.9.8 operation check.
* Added : Speakable structured markup is implemented in "Article", "BlogPosting", "NewsArticle".
* Added : Added function to compress output data.

= 4.4.0 (2018-07-10) =
* Checked : WordPress version 4.9.7 operation check.
* Updated : Schema.ory Type "Image", "BlogPosting", "NewsArticle" image property added so that default image URL can be set.

= 4.3.0 (2018-06-16) =
* Checked : WordPress version 4.9.6 operation check.
* Updated : Enable / disable function of link setting of current page of breadcrumbs.

= 4.2.2 (2018-05-15) =
* Checked : WordPress version 4.9.5 operation check.
* Fixed : If there is a child element on that page in the top fixed page, the parent element duplicates.
* Fixed : Taxonomy name of custom posting is not displayed in a custom taxonomy archive page.

= 4.2.1 (2018-03-23) =
* Fixed : Taxonomy name of custom posting is not displayed.

= 4.1.8 (2018-02-16) =
* Checked : WordPress version 4.9.4 operation check.
* Checked : WordPress version 4.9.3 operation check.

= 4.1.7 (2018-01-22) =
* Checked : WordPress version 4.9.2 operation check.

= 4.1.6 (2017-12-12) =
* Checked : WordPress version 4.9.1 operation check.
* Fixed : Hidden if addressRegion and telephone is empty.

= 4.1.5 (2017-11-22) =
* Checked : WordPress version 4.9.0 operation check.

= 4.1.4 (2017-11-01) =
* Checked : WordPress version 4.8.3 operation check.
* Fixed : Error setting aria-label.

= 4.1.3 (2017-09-27) =
* Checked : WordPress version 4.8.2 operation check.
* Updated : Stop setting the default value.
* Fixed : availableLanguage and area_served array setting ( Organization )

= 4.1.2 (2017-08-23) =
* Checked : WordPress version 4.8.1 operation check.

= 4.1.1 (2017-07-26) =
* Added : Correct to display / hide the current page.(BreadcrumbList schema.org)
* Updated : Event and Video type css update.
* Fixed : Do not display when the search query is empty.

= 4.1.0 (2017-07-03) =
* Added : If there is no feature image setting, set the first image of the post.
* Updated : Organization Image recommended.(Article / NewsArticle / BlogPosting)
* Fixed : Events and videos of custom posts should not appear in choices.
* Fixed : Organization type Notice Error.

= 4.0.2 (2017-06-14) =
* Checked : WordPress version 4.8.0 operation check.
* Updated : Warning: Event type schema.org ( availability, validFrom and performer. )
* Fixed : Events and videos of custom posts should not appear in choices.

= 4.0.1 (2017-05-25) =
* Added : Hook point of AMP plug-ins (Automattic, Inc. release). https://wordpress.org/plugins/amp/
* Added : Site Navigation on only Home Page.
* Fixed : Problem with custom taxonomies.(BreadcrumbList)

= 3.2.6 (2017-05-20) =
* Checked : WordPress version 4.7.5 operation check.

= 3.2.5 (2017-04-24) =
* Checked : WordPress version 4.7.4 operation check.

= 3.2.4 (2017-03-30) =
* Fixed : BreadcrumbList - Ignore the Home setting when setting the head fixed page.
* Fixed : BreadcrumbList - Categories of two or more levels are not displayed.
* Fixed : Minor bug fixed.
* Updated : Article,BlogPosting and NewsArticle - Limit headline to 110 chars.

= 3.2.3 (2017-03-21) =
* Fixed : "Warning: Illegal string offset" error occurred on Video and Event Schema.org.
* Updated : Event Types add fields.
* Updated : Video Types add fields.
* Updated : Change selection method of SiteNavigation type.

= 3.2.2 (2017-03-09) =
* Fixed : Article, BlogPosting, and NewsArticle can not display the Publisher attribute.
* Added : Add media selection function to the field for inputting image path.
* Checked : WordPress version 4.7.3 operation check.

= 3.2.1 (2017-02-21) =
* Fixed : Call to undefined function imagecreatefromstring().

= 3.2.0 (2017-01-30) =
* Fixed : Invalid breadcrumb markup.
* Fixed : Some items are not displayed on "Organization schema.org".
* Added : Add items to e-mail to "Organization schema.org".
* Checked : WordPress version 4.7.2 operation check.

= 3.1.7 (2017-01-12) =
* Checked : WordPress version 4.7.1 operation check.

= 3.1.6 (2016-12-16) =
* Updated : Change ImageObject attribute of Schema.org type "Article", "BlogPosting", "NewsArticle" from "required" to "recommended".
* Fixed : Custom post menu control.

= 3.1.5 (2016-12-08) =
* Checked : WordPress version 4.7.0 operation check.

= 3.1.4 (2016-11-25) =
* Added : Schema.org type "LocalBusiness" item added. "Image", "priceRange" and "servesCuisine".
* Added : Print plugin version in comments.

= 3.1.3 (2016-11-22) =
* Updated : Event Type select item of Schema.org type "Event".
* Updated : Short Code display changed of Schema.org type "BreadcrumbList".

= 3.1.2 (2016-09-27) =
* Updated : homeLocation input item of Schema.org type "Person".

= 3.1.1 (2016-09-20) =
* Added : Schema.org type "SiteNavigation".
* Checked : WordPress version 4.6.1 operation check.
* Updated : Application URL input item of Schema.org type "WebSite".
* Fixed : LocalBusiness Convert data(In the case of version 2.3.x) Logic remove.

= 3.0.5 (2016-09-06) =
* Fixed : CSS & JavaScript version control.
* Fixed : Registration screen display adjustment.

= 3.0.4 (2016-08-17) =
* Fixed : Type Person and Organization Non-display case "sameAs" is empty.
* Checked : WordPress version 4.6.0 operation check.

= 3.0.3 (2016-07-27) =
* Fixed : If you select a static page in a display of the front page, the home page is not output at the output page. (Added is_front_page function)

= 3.0.2 (2016-07-15) =
* Updated : Security measures of the update process.

= 3.0.1 (2016-06-25) =

* Checked : WordPress version 4.5.3 operation check.

= 3.0.0 (2016-06-20) =

* Added : Schema.org type "Video".
* Added : Display the cause of the JSON-LD is not output in HTML comments.
* Fixed : "Schema.org Event" solve the output of JSON-LD is a problem with the double in the custom posts.
* Fixed : Changes to the search of the array to remove the SQL statement to get the output page.
* Fixed : "Schema.org Organization" If you have not set up a Social Profiles, it does not show an empty array.
* Updated : Japanese translation.

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
