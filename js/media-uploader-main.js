(function($) {
	$(function() {
		var
			custom_uploader = wp.media({
				title: "Choose Image",
				library: {
					type: "image"
				},
				button: {
					text: "Choose Image"
				},
				multiple: false
			}),
			media_id = "";

		custom_uploader.on("select", function () {
			var images = custom_uploader.state().get("selection");

			images.each(function(file) {
				switch (media_id) {
					case "media-upload":
						$("#logo").val(file.toJSON().url);
						$("#logo-width").val(file.toJSON().width);
						$("#logo-height").val(file.toJSON().height);
						break;
					case "media-upload-default":
						$("#default_image").val(file.toJSON().url);
						break;
				}
			});
		});

		$("#media-upload, #media-upload-default").on("click", function(e) {
			media_id = e.target.id;
			e.preventDefault();
			custom_uploader.open();
		});
	});
})(jQuery);