
function browserWidth() {
	var w = 0;
	if (self.innerWidth) {
		w = self.innerWidth;
	} else if (document.documentElement && document.documentElement.clientWidth) {
		w = document.documentElement.clientWidth;
	} else if (document.body) {
		w = document.body.clientWidth;
	}
	return w;
}

function browserHeight() {
	var h = 0;
	if (self.innerHeight) {
		h = self.innerHeight;
	} else if (document.documentElement && document.documentElement.clientHeight) {
		h = document.documentElement.clientHeight;
	} else if (document.body) {
		h = document.body.clientHeight;
	}
	return h;
}


(function($) {
	$.fn.extend({
	
		imagePreview: function(options) {
					
			var settings = {
				xOffset: 10,
				yOffset: 30,
				defaultText: 'Cargando vista previa...'
			};
					
			if(options) {
				$.extend(settings, options);
			}

			return this.each(function() {
				
				var self = $(this);

				self.hover(function(e){
					this.t = this.title;
					this.title = "";	
					var c = (this.t != "") ? "<div class='imagePreviewBoxData'>" + this.t +"</div>" : "";
					$("body").append("<div class='imagePreviewBox clearfix'><div class='imagePreviewBoxContent'><img src='"+ this.rel +"' alt='"+settings.defaultText+"' /></div>"+ c +"</div>");
					$(".imagePreviewBox")
						.css("top",(e.pageY - settings.xOffset) + "px")
						.css("left",(e.pageX + settings.yOffset) + "px")
						.fadeIn("fast");						
				},function(){
					this.title = this.t;	
					$(".imagePreviewBox").remove();
				});	

				self.mousemove(function(e){
					bW = browserWidth();
					bH = browserHeight();
					mousePosY = (e.pageY - window.pageYOffset);
					mousePosX = (e.pageX - window.pageXOffset);
					boxPosY = mousePosY > 450 ? ((e.pageY-400) - settings.xOffset) : (e.pageY - settings.xOffset);
					boxPosX = mousePosX > (bW - 610) ? ((e.pageX-610) + settings.yOffset) : (e.pageX + settings.yOffset);
					$(".imagePreviewBox").css("top",boxPosY + "px")
					$(".imagePreviewBox").css("left", boxPosX+ "px");
				});			

			});

		}
	
	});
})(jQuery);
