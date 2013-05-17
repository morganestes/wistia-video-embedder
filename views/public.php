<?php
/**
 * Represents the view for the public-facing component of the plugin.
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package    WistiaVideoEmbedder
 * @subpackage views
 * @author     Morgan Estes <morgan.estes@gmail.com>
 * @license    GPL-2.0+
 * @link       TODO
 * @version    1.0.0
 * @since      1.0.0
 */

/**
 * Embed videos from <a href="http://golftailor.wistia.com">Wistia</a> using a shortcode.
 *
 * @version 1.0
 * <code>
 * [wistia id="vmHxehT"]
 * [wistia id="vheol93" autoplay="true"]
 * </code>
 */
function gt_wistia_video( $atts ) {
	global $is_IE;
	global $is_chrome;

	/**
	 * @var string      $version
	 * @var string|int  $id
	 * @var string|int  $width
	 * @var string|int  $height
	 * @var string|bool $controls
	 * @var string|bool $autoplay
	 * @var string|bool $playbutton
	 * @var string|bool $responsive
	 */
	extract( shortcode_atts( array(
		'version'    => 'v1',
		'id'         => '',
		'width'      => isset( $content_width ) ? $content_width : '640',
		'height'     => '360',
		'controls'   => 'false',
		'autoplay'   => 'false',
		'playbutton' => 'true',
		'responsive' => 'false',
	), $atts ) );

	$api = <<<HTML
	<div class="video video-wistia">
<div id="wistia_{$id}" class="wistia_embed" style="width: {$width}px; height: {$height}px;" data-video-width="$width" data-video-height="$height">&nbsp;</div>
</div>
<script>
wistiaEmbed = Wistia.embed("$id", {
  version: "$version",
  videoWidth: $width,
  videoHeight: $height,
  playButton: $playbutton,
  smallPlayButton: $controls,
  playbar: $controls,
  fullscreenButton: $controls,
  autoPlay: $autoplay,
  videoFoam: $responsive
});
</script>
HTML;

	/**
	 * Uses an iframe to render the video.
	 * morganestes changed this to add the HTML5 "seamless" attribute.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/HTML/Element/iframe
	 */
	$html5 = $is_chrome ? '&platformPreference=html5' : '';

	$iframe = <<<HTML
	<div class="media video video-wistia">
<iframe src="//fast.wistia.net/embed/iframe/$id?autoPlay=$autoplay&fullscreenButton=$controls&playButton=$playbutton&playbar=$controls&smallPlayButton=$controls&version=$version&videoHeight=$height&videoWidth=$width&videoFoam=$responsive{$html5}" allowtransparency="true" frameborder="0" scrolling="no" class="wistia_embed media-object aligncenter" name="wistia_embed" id="wistia-$id" width="$width" height="$height" seamless="true"></iframe>
</div>
<div class="clearfix"></div>
HTML;

	// IE7 can't handle the iframe, so we use the API. Otherwise, frame it up!
	if ( $is_IE )
		$video = $api;
	else {
		$video = $iframe;
	}

	return $video;
}

add_shortcode( 'wistia', 'gt_wistia_video' );
//add_action( 'wp_print_footer_scripts', 'gt_wistia_tracker' );


/**
 * Adds tracking JavaScript code to the page.
 */
function gt_wistia_tracker() {
	// Load the iframe helper
	//wp_enqueue_script( 'wistia-iframe', '//fast.wistia.com/static/iframe-api-v1.js' );

	$script = <<<HTML
<script>
	/* Track individual videos */
	$('.wistia_embed').each(function () {
		var wis = $(this),
				id = wis.attr('id'),
				hash = id.split('-')[1],
				wistiaEmbed = wis[0].wistiaApi;

		wistiaEmbed.bind('play', function () {
			console.log('Wistia video ' + hash + ' started.');
		});
	});

	wistiaEmbeds.onFind(function (video) {
		video.addPlugin("googleAnalytics", {
			src: "//fast.wistia.com/labs/google-analytics/plugin.js",
			outsideIframe: true
		});
	});
</script>
HTML;

	print $script;
}


/**
 * Only load scripts and styles if the [wistia] shortcode is used.
 */
function gt_wistia_enqueue() {
	if ( has_shortcode( 'wistia' ) ) {
		wp_enqueue_script( 'wistia-embed-shepherd', '//fast.wistia.com/static/embed_shepherd-v1.js', array(), 'v1', false );
	}
}

add_filter( 'the_post', 'gt_wistia_enqueue' ); // the_post gets triggered before wp_head

