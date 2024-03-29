//	LiteBox v1.1, Copyright 2014, Joe Mottershaw, https://github.com/joemottershaw/
//	===============================================================================

	;(function($, window, document, undefined) {
		var pluginName = 'liteBox',
			defaults = {
				revealSpeed: 400,
				background: 'rgba(0,0,0,.8)',
				overlayClose: true,
				escKey: true,
				navKey: true,
				callbackInit: function() {},
				callbackBeforeOpen: function() {},
				callbackAfterOpen: function() {},
				callbackBeforeClose: function() {},
				callbackAfterClose: function() {},
				callbackError: function() {},
				callbackPrev: function() {},
				callbackNext: function() {},
				errorMessage: 'Error loading content.'
			};

		function liteBox(element, options) {
			this.element = element;
			this.$element = $(this.element);

			this.options = $.extend({}, defaults, options);

			this._defaults = defaults;
			this._name = pluginName;

			this.init();
		}

		function winHeight() { return window.innerHeight ? window.innerHeight : $(window).height(); }

		function preloadImageArray(images) {
			$(images).each(function () {
				var image = new Image(); 

				image.src = this;

				if (image.width > 0)
					$('<img />').attr('src', this).addClass('litebox-preload').appendTo('body').hide();
			});
		}

		liteBox.prototype = {
			init: function() {
				// Variables
					var	$this = this;

				// Element click
					this.$element.on('click', function(e) {
						e.preventDefault();
						$this.openLitebox();
					});

				// Interaction
					keyEsc = 27,
					keyLeft = 37,
					keyRight = 39;

					$('body').off('keyup').on('keyup', function(e) {
						if ($this.options.escKey && e.keyCode == keyEsc)
							$this.closeLitebox();

						if ($this.options.navKey && e.keyCode == keyLeft)
							$('.litebox-prev').trigger('click');

						if ($this.options.navKey && e.keyCode == keyRight)
							$('.litebox-next').trigger('click');
					});

				// Callback
					this.options.callbackInit.call(this);
			},

			openLitebox: function() {
				// Variables
					var $this = this;

				// Before callback
					this.options.callbackBeforeOpen.call(this);

				// Build
					$this.buildLitebox();

				// Populate
					var	link = this.$element;
					$this.populateLitebox(link);

				// Interactions
					if ($this.options.overlayClose)
						$litebox.on('click', function(e) {
							if (e.target === this || $(e.target).hasClass('litebox-container') || $(e.target).hasClass('litebox-error'))
								$this.closeLitebox();
						});

					$close.on('click', function(){
						$this.closeLitebox();
					});

				// Groups
					if (this.$element.attr('data-litebox-group')) {
						var	$this = this,
							groupName = this.$element.attr('data-litebox-group'),
							group = $('[data-litebox-group="' + this.$element.attr('data-litebox-group') + '"]');

						var imageArray = [];

						$('[data-litebox-group="' + groupName + '"]').each(function() {
							var src = $(this).attr('href');

							imageArray.push(src);
						});

						preloadImageArray(imageArray);

						$('.litebox-nav').show();

						$prevNav.off('click').on('click', function() {
							$this.options.callbackPrev.call(this);

							var index = group.index(link);	

							link = group.eq(index - 1);

							if (!$(link).length)
								link = group.last();

							$this.populateLitebox(link);
						});

						$nextNav.off('click').on('click', function() {
							$this.options.callbackNext.call(this);

							var index = group.index(link);

							link = group.eq(index + 1);

							if (!$(link).length)
								link = group.first();

							$this.populateLitebox(link);
						});
					}

				// After callback
					this.options.callbackAfterOpen.call(this);
			},

			buildLitebox: function() {
				// Variables
					var $this = this;

					$litebox = $('<div>', { 'class': 'litebox-overlay' }),
					$close = $('<div>', { 'class': 'litebox-close' }),
					$delete = $('<div>', { 'class': 'litebox-delete' }),
					$error = $('<div class="litebox-error"><span>' + this.options.errorMessage + '</span></div>'),
					$prevNav = $('<div>', { 'class': 'litebox-nav litebox-prev' }),
					$nextNav = $('<div>', { 'class': 'litebox-nav litebox-next' }),
					$container = $('<div>', { 'class': 'litebox-container' }),
					$loader = $('<div>', { 'class': 'litebox-loader' });

				// Insert into document
					$('body').prepend($litebox.css({ 'background-color': this.options.background }));

					$litebox.append($close, $prevNav, $nextNav, $container,$delete);

					$litebox.fadeIn(this.options.revealSpeed);
			},

			populateLitebox: function(link) {
				// Variables
					var	$this = this,
						href = link.attr('href'),
						$currentContent = $('.litebox-content');

				// Show loader
					$litebox.append($loader);

				// Process
					if (href.match(/\.(jpeg|jpg|gif|png|bmp)/i) !== null) {
						var $img = $('<img>', { 'src': href, 'class': 'litebox-content' });

						$this.transitionContent('image', $currentContent, $img);

						$('img.litebox-content').imagesLoaded(function() {
							$loader.remove();
						});

						$img.error(function() {
							$this.liteboxError();
							$loader.remove();
						});
					} else if (videoURL = href.match(/(youtube|youtu|vimeo|dailymotion|kickstarter)\.(com|be)\/((watch\?v=([-\w]+))|(video\/([-\w]+))|(projects\/([-\w]+)\/([-\w]+))|([-\w]+))/)) {
						var src = '';

						if (videoURL[1] == 'youtube')
							src = 'http://www.youtube.com/v/' + videoURL[5];
						
						if (videoURL[1] == 'youtu')
							src = 'http://www.youtube.com/v/' + videoURL[3];
						
						if (videoURL[1] == 'vimeo')
							src = 'http://player.vimeo.com/video/' + videoURL[3];
						
						if (videoURL[1] == 'dailymotion')
							src = 'https://www.dailymotion.com/embed/video/' + videoURL[7];
						
						if (videoURL[1] == 'kickstarter')
							src = 'https://www.kickstarter.com/projects/' + videoURL[9] + '/' + videoURL[10] + '/widget/video.html';

						if (src) {
							var $iframe = $('<iframe>', {
								'src': src,
								'frameborder': '0',
								'vspace': '0',
								'hspace': '0',
								'scrolling': 'no',
								'allowfullscreen': '',
								'class': 'litebox-content',
								'style': 'background: #000'
							});

							$this.transitionContent('embed', $currentContent, $iframe);

							$iframe.load(function() {
								$loader.remove();
							});
						}
					} else if (href.substring(0, 1) == '#') {
						if ($(href).length) {
							$html = $('<div>', { 'class': 'litebox-content litebox-inline-html' });

							$html.append($(href).clone());

							$this.transitionContent('inline', $currentContent, $html);
						} else {
							$this.liteboxError();
						}

						$loader.remove();
					} else {
						var $iframe = $('<iframe>', {
							'src': href,
							'frameborder': '0',
							'vspace': '0',
							'hspace': '0',
							'scrolling': 'auto',
							'class': 'litebox-content',
							'allowfullscreen': ''
						});

						$this.transitionContent('iframe', $currentContent, $iframe);

						$iframe.load(function() {
							$loader.remove();
						});
					}
			},

			transitionContent: function(type, $currentContent, $newContent) {
				// Variables
					var	$this = this;

					if (type != 'inline')
						$container.removeClass('litebox-scroll');

				// Transition
					$currentContent.remove();
					$container.append($newContent);

					if (type == 'inline')
						$container.addClass('litebox-scroll');

					$this.centerContent();

					$(window).on('resize', function() {
						$this.centerContent();
					});
			},

			centerContent: function() {
				// Overlay to viewport
					$litebox.css({ 'height': winHeight() });

				// Images
					$container.css({ 'line-height': $container.height() + 'px' });

				// Inline
					if (typeof $html != 'undefined' && $('.litebox-inline-html').outerHeight() < $container.height())
						$('.litebox-inline-html').css({ 'margin-top': '-' + ($('.litebox-inline-html').outerHeight()) / 2 + 'px', 'top': '50%' });
			},

			closeLitebox: function() {
				// Before callback
					this.options.callbackBeforeClose.call(this);

				// Remove
					$litebox.fadeOut(this.options.revealSpeed, function() {
						$('.litebox-nav').hide();
						$litebox.empty().remove();
						$('.litebox-preload').remove();
					});

				// Remove click handlers
					$('.litebox-prev').off('click');
					$('.litebox-next').off('click');

				// After callback
					this.options.callbackAfterClose.call(this);
			},

			liteboxError: function() {
				this.options.callbackError.call(this);

				$container.append($error);
			}
		};
		
		$.fn[pluginName] = function(options) {
			return this.each(function() {
				if (!$.data(this, pluginName))
					$.data(this, pluginName, new liteBox(this, options));
			});
		};

	})(jQuery, window, document);