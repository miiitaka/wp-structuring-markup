=== Markup (JSON-LD) structured in schema.org ===
Contributors: miiitaka
Tags: schema, schema.org, json, json-ld, seo, post, posts, google
Requires at least: 4.3.1
Tested up to: 4.3.1
Stable tag: 2.0.0

It is plug in to implement structured markup (JSON-LD syntax) by schema.org definition on an post page, fixed page and etc.

== Description ==

It is plug in to implement structured markup (JSON-LD syntax) by schema.org definition on an post page, fixed page and etc.
Base knowledge is "https://schema.org/" and "https://developers.google.com/structured-data/"

このプラグインは、投稿ページ、固定ページなどにschema.org定義の構造化マークアップ（JSON-LDの構文）を実装するプラグインです。
基本知識は、https://schema.org/ や https://developers.google.com/structured-data/ を参考にしてください。

= Schema.org Type =

* Article: http://schema.org/Article
* BlogPosting: http://schema.org/BlogPosting
* BreadcrumbList: https://schema.org/BreadcrumbList
* NewsArticle: http://schema.org/NewsArticle
* Organization: https://schema.org/Organization
* Website: https://schema.org/WebSite

== Installation ==

* A plug-in installation screen is displayed in the WordPress admin panel.
* It installs in `wp-content/plugins`.
* The plug-in is activated.
* Open 'Schema.org Setting' menu.

**Breadcrumb**

You can display the breadcrumbs in the short code. Breadcrumb definition is available even if not active.

ショートコードでパンくずリストを表示することができます。Breadcrumb定義がアクティブでなくても使用可能です。

`[wp-structuring-markup-breadcrumb]`

== Changelog ==

= 2.0.0 (2015/11/) =

* Added : Schema.org type "BreadcrumbList" Breadcrumbs schema.org definition Add breadcrumbs short code output that works
* Updated : Schema.org definition immobilization.

= 1.3.2 (2015/11/17) =

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
