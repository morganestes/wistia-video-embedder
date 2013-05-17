(function () {
	tinymce.create('tinymce.plugins.wistiaVideoEmbedder', {
		init         : function (ed, url) {
			var trimPath = /js$/,
					path = url.replace(trimPath, '');
			ed.addButton('wistiaVideoEmbedder', {
				title  : 'Embed Wistia Video',
				image  : path + 'assets/wistia-ico.png',
				onclick: function () {
					var videoId = prompt("Video ID", "Enter the video ID or type 'playlist'.");

					if (videoId !== null && videoId !== '') {
						if (videoId == 'playlist') {
							var playlistId = prompt("Playlist ID", "Enter the playlist ID");
							ed.execCommand('mceInsertContent', false, '[wistia playlist_id="' + playlistId + '"]');
						} else {
							ed.execCommand('mceInsertContent', false, '[wistia id="' + videoId + '"]');
						}
					} else {
							alert('No ID was entered.');
							return false;
						}
					}
				});
		},
		createControl: function (n, cm) {
			return null;
		},
		getInfo      : function () {
			return {
				longname : "Wistia Video Embedder",
				author   : 'Morgan Estes',
				authorurl: 'http://www.morganestes.me',
				infourl  : 'http://github.com/morganestes',
				version  : "1.0.0"
			};
		}
	});
	tinymce.PluginManager.add('wistiaVideoEmbedder', tinymce.plugins.wistiaVideoEmbedder);
})();
