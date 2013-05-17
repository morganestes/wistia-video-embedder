=== Wistia Video Embedder ===
Contributors: morganestes
Donate link: https://github.com/morganestes/wistia-video-embedder
Tags: wistia, video, oembed
Requires at least: 3.5
Tested up to: 3.5.1
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Easily embed your Wistia videos and playlists into your site.

== Description ==

Easily embed Wistia videos and playlists from your account into your website.

Features:

* Add videos through shortcode, either manually or through the editor button.
* Support for project playlists or individual videos.

Requirements:

* An account with [Wistia](http://wistia.com).
* An API key (for getting project and video details).

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Unzip plugin and upload the `wistia-video-embedder` folder to the `/wp-content/plugins/` directory.
1. Activate the plugin through the 'Plugins' menu in WordPress.
1. Add your API key to the plugin settings page.

== Frequently Asked Questions ==

= Why do I have to have an API key? =

Right now, it's used to connect to your Wistia account to get project lists.

= How do I use it? =

* Click the Wistia icon in the editor and enter the ID of the video or playlist.
* You can also manually enter a shortcode: `[wistia id="hashedId"]` or `[wistia playlist="hashedId"]`.

== Screenshots ==

1.
2.


== Changelog ==

= 1.0.0 =
* Initial version. It works for me :)

== Upgrade Notice ==

= 1.0.0 =
You're definitely going to want to upgrade when the next version is released.

== Disclaimer ==

This plugin is not created or (yet) endorsed by Wistia. It was just made by a client for in-house use and thought I'd share.
If you experience any problems, feel free to fork it, fix it, or scrap it. You can also open a GitHub issue and I'll take a look.

== Props ==
* Uses [https://github.com/stephenreid/wistia-api](wistia-api) for API connections.
* See [http://wistia.com/doc/data-api](Wistia Data API Docs) for implementation.
* Built with [https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate](WordPress Plugin Boilerplate).
