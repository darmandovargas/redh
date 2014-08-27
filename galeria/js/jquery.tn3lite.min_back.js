/*!
 * tn3 v1.4.0.106
 * http://tn3gallery.com/
 *
 * License
 * http://tn3gallery.com/license
 *
 * Date: 06 Feb, 2014 12:21:15 +0200
 */

(function(i) {
	function b(g) {
		var l = g.skinDir + "/" + g.skin, k = f[l];
		if (k)
			k.loaded ? a.call(this, g, k.html) : k.queue.push({
				c : this,
				s : g
			});
		else {
			f[l] = {
				loaded : false,
				queue : [{
					c : this,
					s : g
				}]
			};
			i.ajax({
				url : l + ".html",
				dataType : "text",
				success : function(m) {
					var t = f[l];
					t.loaded = true;
					t.html = m;
					for ( m = 0; m < t.queue.length; m++)
						a.call(t.queue[m].c, t.queue[m].s, t.html)
				},
				dataFilter : function(m) {
					return m = m.substring(m.indexOf("<body>") + 6, m.lastIndexOf("</body>"))
				},
				error : function() {
					if (g.error) {
						var m = i.Event("tn3_error");
						m.description = "tn3 skin load error";
						g.error(m)
					}
				}
			})
		}
		return this
	}

	function a(g, l) {
		this.each(function() {
			for (var k = i(this), m, t, u = l.indexOf("<img src="); u != -1; ) {
				u += 10;
				t = l.indexOf('"', u);
				m = g.skinDir + "/" + l.substring(u, t);
				l = l.substr(0, u) + m + l.substr(t);
				u = l.indexOf("<img src=", u)
			}
			k.append(l);
			k.data("tn3").init(k, g.fullOnly)
		})
	}

	function c(g) {
		var l = [], k = g.children(".tn3.album"), m, t;
		if (k.length > 0)
			k.each(function(u) {
				m = i(this);
				l[u] = {
					title : m.find(":header").html()
				};
				i.extend(l[u], d(m));
				if ( t = e(m)) {
					l[u].imgs = t;
					if (!l[u].thumb)
						l[u].thumb = l[u].imgs[0].thumb
				}
			});
		else if ( t = e(g))
			l[0] = {
				imgs : t
			};
		return l
	}

	function e(g) {
		var l = [], k, m;
		k = g.children("ol,ul").children("li");
		if (k.length > 0)
			k.each(function(t) {
				m = i(this);
				$firsta = m.find("a:not(.tn3 > a)").filter(":first");
				l[t] = {
					title : m.find(":header").filter(":first").html()
				};
				if ($firsta.length > 0) {
					l[t].img = $firsta.attr("href");
					l[t].thumb = $firsta.find("img").attr("src")
				} else
					l[t].img = m.find("img").filter(":first").attr("src");
				i.extend(l[t], d(m));
				l[t].alt = m.find("img").filter(":first").attr("alt") || l[t].title
			});
		else {
			k = g.find("img");
			k.each(function(t) {
				m = i(this);
				$at = m.parent("a");
				l[t] = $at.length == 0 ? {
					title : m.attr("title"),
					img : m.attr("src")
				} : {
					title : m.attr("title"),
					img : $at.attr("href"),
					thumb : m.attr("src")
				};
				l[t].alt = m.attr("alt") || l[t].title
			})
		}
		if (l.length == 0)
			return null;
		return l
	}

	function d(g) {
		var l = {};
		g = g.children(".tn3");
		var k;
		i.each(g, function() {
			k = i(this);
			l[k.attr("class").substr(4)] = k.html()
		});
		return l
	}

	function h(g) {
		i('a[href^="#tn3-' + g + '"]').click(function(l) {
			var k = n[g];
			l = i(l.currentTarget).attr("href");
			l = l.substr(l.indexOf("-", 5) + 1);
			l = l.split("-");
			switch(l[0]) {
				case "next":
					k.cAlbum != null && k.show("next", l[1] == "fs");
					break;
				case "prev":
					k.cAlbum != null && k.show("prev", l[1] == "fs");
					break;
				default:
					k.cAlbum != parseInt(l[0]) ? k.showAlbum(parseInt(l[0]), parseInt(l[1]), l[2] == "fs") : k.show(parseInt(l[1]), l[2] == "fs")
			}
		})
	}

	function j() {
		if (n.length == 0) {
			var g = i(".tn3gallery");
			g.length > 0 && g.tn3({})
		}
	}

	if ( function(g) {
		for (var l, k, m = i.fn.jquery, t = m.split("."); g.length < t.length; )
			g.push(0);
		for ( l = 0; l < g.length; l++) {
			k = parseInt(t[l]);
			if (k > g[l])
				return true;
			if (k < g[l])
				return false
		}
		return m === g.join(".")
	}([1, 4, 2])) {
		var f = {}, n = [];
		i.fn.tn3 = function(g) {
			i.each(["skin", "startWithAlbums", "external"], function(l, k) {
				var m = k.split(".");
				if (m.length > 1 && g[m[0]])
					delete g[m[0]][m[1]];
				else
					delete g[k]
			});
			g = i.extend(true, {}, i.fn.tn3.config, g);
			if (g.skin != null)
				if ( typeof g.skin == "object") {
					g.skinDir += "/" + g.skin[0];
					if (g.cssID == null)
						g.cssID = g.skin[0];
					g.skin = g.skin[1]
				} else
					g.skinDir += "/" + g.skin;
			else {
				g.skin = "tn3";
				g.skinDir += "/tn3";
				i.fn.tn3.dontLoad = true
			}
			if (g.cssID == null)
				g.cssID = g.skin == null ? "tn3" : g.skin;
			this.each(function() {
				var l = i(this);
				g.fullOnly ? l.hide() : l.css("visibility", "hidden");
				var k = g.data ? g.data : c(l);
				k = n.push(new i.fn.tn3.Gallery(k, g)) - 1;
				l.data("tn3", n[k]);
				for (var m = 0; m < i.fn.tn3.plugins.length; m++)
					i.fn.tn3.plugins[m].init(l, g, n[k]);
				l.empty();
				h(k)
			});
			i.fn.tn3.dontLoad ? a.call(this, g, g.skinDefault) : b.call(this, g);
			return this
		};
		i.fn.tn3.plugins = [];
		i.fn.tn3.plugIn = function(g, l) {
			i.fn.tn3.plugins.push({
				id : g,
				init : l
			})
		};
		i.fn.tn3.version = "1.4.0.106";
		i.fn.tn3.config = {
			data : null,
			skin : null,
			skinDir : "skins",
			skinDefault : '<div class="tn3-gallery"><div class="tn3-image"><div class="tn3-text-bg"><div class="tn3-image-title"></div><div class="tn3-image-description"></div></div><div class="tn3-next tn3_v tn3_o"></div><div class="tn3-prev tn3_v tn3_o"></div><div class="tn3-preloader tn3_h tn3_v"><img src="preload.gif"/></div><div class="tn3-timer"></div></div><div class="tn3-controls-bg tn3_rh"><div class="tn3-sep1"></div><div class="tn3-sep2"></div><div class="tn3-sep3"></div></div><div class="tn3-thumbs"></div><div class="tn3-fullscreen"></div><div class="tn3-show-albums"></div><div class="tn3-next-page"></div><div class="tn3-prev-page"></div><div class="tn3-play"></div><div class="tn3-count"></div><div class="tn3-albums"><div class="tn3-inalbums"><div class="tn3-album"></div></div><div class="tn3-albums-next"></div><div class="tn3-albums-prev"></div><div class="tn3-albums-close"></div></div></div>',
			cssID : null
		};
		i.fn.tn3.translations = {};
		i.fn.tn3.translate = function(g, l) {
			if (l)
				i.fn.tn3.translations[g] = l;
			else {
				var k = i.fn.tn3.translations[g];
				return k ? k : g
			}
		};
		i(function() {
			setTimeout(j, 1)
		})
	} else
		alert("tn3gallery requires jQuery v1.4.2 or later!  You are using v" + i.fn.jquery)
})(jQuery);
(function(i) {
	i.fn.tn3utils = U = {};
	U.shuffle = function(b) {
		var a, c, e = b.length;
		if (e)
			for (; --e; ) {
				c = Math.floor(Math.random() * (e + 1));
				a = b[c];
				b[c] = b[e];
				b[e] = a
			}
	};
	i.extend(i.easing, {
		def : "easeOutQuad",
		swing : function(b, a, c, e, d) {
			return i.easing[i.easing.def](b, a, c, e, d)
		},
		linear : function(b, a, c, e, d) {
			return e * a / d + c
		},
		easeInQuad : function(b, a, c, e, d) {
			return e * (a /= d) * a + c
		},
		easeOutQuad : function(b, a, c, e, d) {
			return -e * (a /= d) * (a - 2) + c
		},
		easeInOutQuad : function(b, a, c, e, d) {
			if ((a /= d / 2) < 1)
				return e / 2 * a * a + c;
			return -e / 2 * (--a * (a - 2) - 1) + c
		},
		easeInCubic : function(b, a, c, e, d) {
			return e * (a /= d) * a * a + c
		},
		easeOutCubic : function(b, a, c, e, d) {
			return e * (( a = a / d - 1) * a * a + 1) + c
		},
		easeInOutCubic : function(b, a, c, e, d) {
			if ((a /= d / 2) < 1)
				return e / 2 * a * a * a + c;
			return e / 2 * ((a -= 2) * a * a + 2) + c
		},
		easeInQuart : function(b, a, c, e, d) {
			return e * (a /= d) * a * a * a + c
		},
		easeOutQuart : function(b, a, c, e, d) {
			return -e * (( a = a / d - 1) * a * a * a - 1) + c
		},
		easeInOutQuart : function(b, a, c, e, d) {
			if ((a /= d / 2) < 1)
				return e / 2 * a * a * a * a + c;
			return -e / 2 * ((a -= 2) * a * a * a - 2) + c
		},
		easeInQuint : function(b, a, c, e, d) {
			return e * (a /= d) * a * a * a * a + c
		},
		easeOutQuint : function(b, a, c, e, d) {
			return e * (( a = a / d - 1) * a * a * a * a + 1) + c
		},
		easeInOutQuint : function(b, a, c, e, d) {
			if ((a /= d / 2) < 1)
				return e / 2 * a * a * a * a * a + c;
			return e / 2 * ((a -= 2) * a * a * a * a + 2) + c
		},
		easeInSine : function(b, a, c, e, d) {
			return -e * Math.cos(a / d * (Math.PI / 2)) + e + c
		},
		easeOutSine : function(b, a, c, e, d) {
			return e * Math.sin(a / d * (Math.PI / 2)) + c
		},
		easeInOutSine : function(b, a, c, e, d) {
			return -e / 2 * (Math.cos(Math.PI * a / d) - 1) + c
		},
		easeInExpo : function(b, a, c, e, d) {
			return a == 0 ? c : e * Math.pow(2, 10 * (a / d - 1)) + c
		},
		easeOutExpo : function(b, a, c, e, d) {
			return a == d ? c + e : e * (-Math.pow(2, -10 * a / d) + 1) + c
		},
		easeInOutExpo : function(b, a, c, e, d) {
			if (a == 0)
				return c;
			if (a == d)
				return c + e;
			if ((a /= d / 2) < 1)
				return e / 2 * Math.pow(2, 10 * (a - 1)) + c;
			return e / 2 * (-Math.pow(2, -10 * --a) + 2) + c
		},
		easeInCirc : function(b, a, c, e, d) {
			return -e * (Math.sqrt(1 - (a /= d) * a) - 1) + c
		},
		easeOutCirc : function(b, a, c, e, d) {
			return e * Math.sqrt(1 - ( a = a / d - 1) * a) + c
		},
		easeInOutCirc : function(b, a, c, e, d) {
			if ((a /= d / 2) < 1)
				return -e / 2 * (Math.sqrt(1 - a * a) - 1) + c;
			return e / 2 * (Math.sqrt(1 - (a -= 2) * a) + 1) + c
		},
		easeInElastic : function(b, a, c, e, d) {
			b = 1.70158;
			var h = 0, j = e;
			if (a == 0)
				return c;
			if ((a /= d) == 1)
				return c + e;
			h || ( h = d * 0.3);
			if (j < Math.abs(e)) {
				j = e;
				b = h / 4
			} else
				b = h / (2 * Math.PI) * Math.asin(e / j);
			return -(j * Math.pow(2, 10 * (a -= 1)) * Math.sin((a * d - b) * 2 * Math.PI / h)) + c
		},
		easeOutElastic : function(b, a, c, e, d) {
			b = 1.70158;
			var h = 0, j = e;
			if (a == 0)
				return c;
			if ((a /= d) == 1)
				return c + e;
			h || ( h = d * 0.3);
			if (j < Math.abs(e)) {
				j = e;
				b = h / 4
			} else
				b = h / (2 * Math.PI) * Math.asin(e / j);
			return j * Math.pow(2, -10 * a) * Math.sin((a * d - b) * 2 * Math.PI / h) + e + c
		},
		easeInOutElastic : function(b, a, c, e, d) {
			b = 1.70158;
			var h = 0, j = e;
			if (a == 0)
				return c;
			if ((a /= d / 2) == 2)
				return c + e;
			h || ( h = d * 0.3 * 1.5);
			if (j < Math.abs(e)) {
				j = e;
				b = h / 4
			} else
				b = h / (2 * Math.PI) * Math.asin(e / j);
			if (a < 1)
				return -0.5 * j * Math.pow(2, 10 * (a -= 1)) * Math.sin((a * d - b) * 2 * Math.PI / h) + c;
			return j * Math.pow(2, -10 * (a -= 1)) * Math.sin((a * d - b) * 2 * Math.PI / h) * 0.5 + e + c
		},
		easeInBack : function(b, a, c, e, d, h) {
			if (h == undefined)
				h = 1.70158;
			return e * (a /= d) * a * ((h + 1) * a - h) + c
		},
		easeOutBack : function(b, a, c, e, d, h) {
			if (h == undefined)
				h = 1.70158;
			return e * (( a = a / d - 1) * a * ((h + 1) * a + h) + 1) + c
		},
		easeInOutBack : function(b, a, c, e, d, h) {
			if (h == undefined)
				h = 1.70158;
			if ((a /= d / 2) < 1)
				return e / 2 * a * a * (((h *= 1.525) + 1) * a - h) + c;
			return e / 2 * ((a -= 2) * a * (((h *= 1.525) + 1) * a + h) + 2) + c
		},
		easeInBounce : function(b, a, c, e, d) {
			return e - i.easing.easeOutBounce(b, d - a, 0, e, d) + c
		},
		easeOutBounce : function(b, a, c, e, d) {
			return (a /= d) < 1 / 2.75 ? e * 7.5625 * a * a + c : a < 2 / 2.75 ? e * (7.5625 * (a -= 1.5 / 2.75) * a + 0.75) + c : a < 2.5 / 2.75 ? e * (7.5625 * (a -= 2.25 / 2.75) * a + 0.9375) + c : e * (7.5625 * (a -= 2.625 / 2.75) * a + 0.984375) + c
		},
		easeInOutBounce : function(b, a, c, e, d) {
			if (a < d / 2)
				return i.easing.easeInBounce(b, a * 2, 0, e, d) * 0.5 + c;
			return i.easing.easeOutBounce(b, a * 2 - d, 0, e, d) * 0.5 + e * 0.5 + c
		}
	})
})(jQuery); 


(function(i) {
	i.fn.tn3.Gallery = function(d, h) {
		this.data = d;
		this.config = i.extend(true, {}, i.fn.tn3.Gallery.config, h);
		this.initialized = false;
		this.t = i.fn.tn3.translate;
		this.loader = new i.fn.tn3.External(h.external, this)
	};
	i.fn.tn3.Gallery.config = {
		cssID : "tn3",
		active : [],
		inactive : [],
		iniAlbum : 0,
		iniImage : 0,
		imageClick : "next",
		startWithAlbums : false,
		autoplay : false,
		delay : 7E3,
		timerMode : "bar",
		timerSteps : 300,
		timerStepChar : "&#8226;",
		isFullScreen : false,
		fullOnly : false,
		width : null,
		height : null,
		mouseWheel : true,
		keyNavigation : "fullscreen",
		timerOverStop : true,
		responsive : false,
		useNativeFullScreen : true,
		autohideControls : false,
		autohideOver : true,
		overFadeDuration : 300,
		spinjs : null,
		image : {},
		thumbnailer : {},
		touch : {}
	};
	i.fn.tn3.Gallery.prototype = {
		config : null,
		$c : null,
		$tn3 : null,
		data : null,
		thumbnailer : null,
		imager : null,
		cAlbum : null,
		timer : null,
		items : null,
		initialized : null,
		n : null,
		albums : null,
		loader : null,
		fso : null,
		timerSize : null,
		special : null,
		areHidden : false,
		$inImage : null,
		isPlaying : false,
		cparent : null,
		spinner : null,
		showInit : false,
		init : function(d, h) {
			this.$c = d;
			if (!(this.loader.reqs > 0 || this.data.length == 0 || h)) {
				this.trigger("init_start");
				this.config.useNativeFullScreen = this.config.useNativeFullScreen && b.supportsFullScreen;
				this.config.fullOnly && this.$c.show();
				this.$c.css("visibility", "visible");
				this.$tn3 = this.$c.find("." + this.config.cssID + "-gallery");
				var j = this.config.initValues = {
					width : this.$tn3.width(),
					height : this.$tn3.height()
				};
				this.$tn3.css("float", "left");
				j.wDif = this.$tn3.outerWidth(true) - j.width;
				j.hDif = this.$tn3.outerHeight(true) - j.height;
				/*this.replaceMenu("tn3gallery.com",
				 "http://tn3gallery.com")*/;
				var f = this;
				this.timer = new i.fn.tn3.Timer(this.$c, this.config.delay, this.config.timerSteps);
				this.$c.bind("tn3_timer_end", function() {
					f.show("next")
				});
				this.special = {
					rv : [],
					rh : [],
					v : [],
					h : [],
					vi : [],
					hi : [],
					o : [],
					x : {}
				};
				this.parseLayout();
				this.center();
				i.each(this.items, function(n, g) {
					switch(n) {
						case "next":
							g.click(function(k) {
								f.show("next");
								k.stopPropagation()
							});
							g.attr("title", f.t("Next Image"));
							break;
						case "prev":
							g.click(function(k) {
								f.show("prev");
								k.stopPropagation()
							});
							g.attr("title", f.t("Previous Image"));
							break;
						case "next-page":
							g.click(function() {
								f.items.thumbs && f.thumbnailer.next(true)
							});
							g.attr("title", f.t("Next Page"));
							break;
						case "prev-page":
							g.click(function() {
								f.items.thumbs && f.thumbnailer.prev(true)
							});
							g.attr("title", f.t("Previous Page"));
							break;
						case "thumbs":
							f.config.thumbnailer.cssID = f.config.cssID;
							f.config.thumbnailer.initValues = {
								width : g.width(),
								height : g.height()
							};
							f.config.thumbnailer.initValues.vertical = g.width() <= g.height();
							g.bind("tn_click", function(k) {
								f.show(k.n)
							}).bind("tn_over", function() {
								f.timer.pause(true)
							}).bind("tn_out", function() {
								f.timer.pause(false)
							}).bind("tn_error", function(k) {
								f.trigger("error", k)
							});
							break;
						case "image":
							f.config.image.cssID = f.config.cssID;
							f.config.image.initValues = {
								width : g.width(),
								height : g.height()
							};
							g.bind("img_click", function(k) {
								switch(f.config.imageClick) {
									case "next":
										f.show("next");
										break;
									case "fullscreen":
										f.fullscreen(true);
										break;
									case "url":
										if ( k = f.data[f.cAlbum].imgs[k.n].url.replace(/&amp;/g, "&"))
											window.location = k
								}
							}).bind("img_load_start", function() {
								f.displayPreloader(true)
							}).bind("img_load_end", function(k) {
								f.n = k.n;
								f.displayPreloader(false);
								f.items.timer && f.items.timer.hide();
								f.$inImage && f.$inImage.hide()
							}).bind("img_transition", function(k) {
								f.setTextValues(false, "image");
								f.items.thumbs && f.thumbnailer.thumbClick(f.n);
								f.$inImage && f.imager.cached[k.n].isImage && f.$inImage.fadeIn(300);
								f.items.count && f.items.count.text(f.n + 1 + "/" + f.data[f.cAlbum].imgs.length);
								f.isPlaying && f.timer.start();
								f.special.o.length > 0 && f.config.autohideOver && f.hideElements();
								if (f.config.autohideControls) {
									if (f.items.prev)
										f.items.prev[k.n==
										0?"hide":"show"]();
									if (f.items.next)
										f.items.next[k.n==f.imager.data.length-1?"hide":"show"]()
								}
							}).bind("img_enter", function() {
								f.imageEnter()
							}).bind("img_leave", function() {
								f.imageLeave()
							}).bind("img_resize", function(k) {
								if (f.$inImage) {
									f.$inImage.width(k.w).height(k.h).css("left", k.left).css("top", k.top);
									f.center();
									f.imager.bindMouseEvents(f.$inImage)
								}
							}).bind("img_error", function(k) {
								f.trigger("error", k)
							});
							break;
						case "preloader":
							f.config.spinjs && g.find("img").remove();
							f.displayPreloader(false);
							break;
						case "timer":
							var l = g.width() > g.height() ? "width" : "height";
							f.$c.bind("timer_tick", function(k) {
								if (f.config.timerMode == "char") {
									for (var m = f.config.timerStepChar; --k.tick; )
										m += f.config.timerStepChar;
									f.items.timer.html(m)
								} else
									f.items.timer[l](f.timerSize / k.totalTicks * k.tick);
								f.trigger(k.type, k)
							}).bind("timer_start", function(k) {
								f.timerSize = f.$inImage[l]();
								f.items.timer.fadeIn(300);
								f.items.play && f.items.play.addClass(f.config.cssID + "-play-active");
								f.trigger(k.type, k)
							}).bind("timer_end timer_stop", function(k) {
								f.items.timer.hide();
								k.type == "timer_end" && f.n == f.data[f.cAlbum].imgs.length - 1 && f.trigger("autoplay_finish");
								f.trigger(k.type, k)
							});
							g.hide();
							break;
						case "play":
							g.click(function(k) {
								var m = {
									id : "play",
									execute : true
								};
								f.trigger("control", m);
								if (f.isPlaying) {
									if (m.execute) {
										f.timer.stop();
										g.removeClass(f.config.cssID + "-play-active");
										g.attr("title", f.t("Start Slideshow"))
									}
									f.isPlaying = false
								} else {
									if (m.execute) {
										f.timer.start();
										g.addClass(f.config.cssID + "-play-active");
										g.attr("title", f.t("Stop Slideshow"))
									}
									f.isPlaying = true
								}
								k.stopPropagation()
							});
							g.attr("title", f.t("Start Slideshow"));
							break;
						case "count":
							break;
						case "albums":
							f.albums = new i.fn.tn3.Albums(f.data, g, f.config.cssID);
							g.hide();
							g.bind("albums_binit", function(k) {
								f.trigger(k.type, k)
							}).bind("albums_click", function(k) {
								f.showAlbum(k.n);
								f.trigger(k.type, k)
							}).bind("albums_init", function(k) {
								f.timer.pause(true);
								f.trigger(k.type, k)
							}).bind("albums_error", function(k) {
								f.trigger("error", k)
							}).bind("albums_close", function() {
								f.timer.pause(false)
							});
							break;
						case "album":
							break;
						case "albums-next":
							f.albums && f.albums.setControl("next", g);
							g.attr("title", f.t("Next Album Page"));
							break;
						case "albums-prev":
							f.albums && f.albums.setControl("prev", g);
							g.attr("title", f.t("Previous Album Page"));
							break;
						case "albums-close":
							f.albums && f.albums.setControl("close", g);
							g.attr("title", f.t("Close"));
							break;
						case "show-albums":
							g.click(function(k) {
								f.items.albums && f.albums.show(0, f.cAlbum, false, true);
								k.stopPropagation()
							});
							g.attr("title", f.t("Album List"));
							break;
						case "fullscreen":
							g.click(function(k) {
								var m = {
									id : "fullscreen",
									execute : true
								};
								f.trigger("control", m);
								m.execute && f.fullscreen(true);
								k.stopPropagation()
							});
							g.attr("title", f.t("Maximize"));
							break;
						default:
							f.special.x[n] && g.click(function(k) {
								var m = !f.items[f.special.x[n]].is(":visible"), t = {
									id : n,
									execute : true,
									active : m
								};
								f.trigger("control", t);
								if (t.execute) {
									m ? g.addClass(f.config.cssID + "-" + n + "-active") : g.removeClass(f.config.cssID + "-" + n + "-active");
									f.items[f.special.x[n]].toggle()
								}
								k.stopPropagation()
							})
					}
				});
				if (this.config.width !== null || this.config.height !== null) {
					if (this.config.width == null)
						this.config.width = this.config.initValues.width;
					if (this.config.height == null)
						this.config.height = this.config.initValues.height;
					this.resize(this.config.width, this.config.height)
				}
				j = Math.min(this.config.iniAlbum, this.data.length - 1);
				this.config.responsive !== false && this.initResponsive(this.config.responsive);
				this.initialized = true;
				this.config.startWithAlbums && this.data.length > 1 && this.items.albums ? this.albums.show() : this.showAlbum(j, this.config.iniImage);
				this.config.isFullScreen && this.onFullResize();
				if (this.config.autoplay)
					this.items.play ? this.items.play.click() : this.timer.start();
				this.trigger("init")
			}
		},
		parseLayout : function() {
			var d = this.items = {}, h = this.config, j = h.active, f = h.inactive, n = h.cssID.length + 1, g = this, l = /MSIE/.test(navigator.userAgent), k, m;
			this.$c.find("div[class^='" + h.cssID + "-']").each(function() {
				k = i(this);
				m = k.attr("class").split(" ")[0].substr(n);
				if (i.inArray(m, f) != -1)
					k.remove();
				else if (j.length == 0 || i.inArray(m, j) != -1)
					d[m] = k;
				else
					m != "gallery" && k.remove();
				if (k.parent().hasClass(h.cssID + "-image")) {
					if (!g.$inImage) {
						g.$inImage = i('<div class="tn3-in-image"></div>');
						k.parent().append(g.$inImage);
						if (l) {
							var u = i("<div />");
							u.css("background-color", "#fff").css("opacity", 0).css("width", "100%").css("height", "100%");
							u.appendTo(g.$inImage)
						}
						g.$inImage.css("position", "absolute").width(d.image.width()).height(d.image.height())
					}
					k.appendTo(g.$inImage)
				}
				this.className.indexOf("tn3_") != -1 && g.addSpecial(m, this.className)
			});
			$cm = this.$c;
			i.each(["albums", "album", "album-next", "album-prev", "show-albums"], function(u, q) {
				delete d[q];
				$cm.find("." + h.cssID + "-" + q).remove()
			});
			var t = i('<div title="Powered by TN3 Gallery"></div>');
			t.css("position", "absolute").css("background-image", "url('" + this.config.skinDir + "/tn3.png')").css("background-position", "-258px -7px").css("bottom", "14px").css("right", "53px").css("cursor", "pointer").width(40).height(18);
			t.appendTo(this.$c.find("." + h.cssID + "-gallery"));
			t.click(function() {
				window.location = "http://tn3gallery.com"
			}).hover(function() {
				i(this).css("background-position", "-258px -45px")
			}, function() {
				i(this).css("background-position", "-258px -7px")
			})
		},
		addSpecial : function(d, h) {
			for (var j = h.split(" "), f, n = 0; n < j.length; n++) {
				f = j[n].split("_");
				if (f[0] == "tn3")
					if (f[1] == "x")
						this.special.x[d] = f[2];
					else {
						this.special[f[1]].push(d);
						if (f[1] == "rh" || f[1] == "rv")
							this.config.initValues[d] = {
								w : this.items[d].width(),
								h : this.items[d].height()
							}
					}
			}
		},
		initHover : function(d, h) {
			var j = this;
			d.hover(function() {
				d.addClass(j.config.cssID + "-" + h + "-over")
			}, function() {
				d.removeClass(j.config.cssID + "-" + h + "-over")
			})
		},
		setTextValues : function(d, h) {
			var j, f, n, g = h + "-";
			for (n in this.items)
			if (n.indexOf(g) == 0) {
				j = n.substr(g.length);
				if (j != "info" && j != "prev" && j != "next") {
					f = h == "image" ? this.data[this.cAlbum].imgs[this.n] : this.data[this.cAlbum];
					if (!f || f[j] == undefined) {
						f = {};
						f[j] = ""
					} else
						f[j] = i.trim(f[j]);
					j = {
						field : j,
						text : f[j],
						data : f
					};
					this.trigger("set_text", j);
					if (d || j.text == undefined || j.text.length == 0) {
						this.items[n].html("");
						this.items[n].hide()
					} else {
						this.items[n].html(j.text);
						this.items[n].show()
					}
				}
			}
		},
		show : function(d, h) {
			this.timer.stop();
			this.showInit = true;
			this.imager && this.imager.show(d);
			h && this.fullscreen()
		},
		setAlbumData : function(d, h) {
			if (h)
				this.trigger("error", {
					description : h
				});
			else {
				for (var j = 0, f = d.length; j < f; j++)
					this.data.push(d[j]);
				this.$c && this.init(this.$c, this.config.fullOnly)
			}
		},
		setImageData : function(d, h, j) {
			if (j)
				this.trigger("error", {
					description : j
				});
			else {
				this.displayPreloader(false);
				d = {
					data : d
				};
				this.trigger("image_data", d);
				this.data[h].imgs = d.data;
				if (this.cAlbum == h)
					this.rebuild(d.data, this.showInit ? 0 : this.config.iniImage)
			}
		},
		showAlbum : function(d, h, j) {
			if (this.initialized) {
				if (d > this.data.length)
					return;
				this.timer.stop();
				this.cAlbum = d;
				this.albums && this.albums.hide();
				if (this.data[this.cAlbum].imgs === undefined)
					if (this.loader) {
						this.loader.getImages(this.data[this.cAlbum].adata, this.cAlbum);
						this.displayPreloader(true)
					} else
						this.trigger("error", {
							description : "Wrong album id"
						});
				else
					this.rebuild(this.data[this.cAlbum].imgs, h)
			} else {
				this.config.iniAlbum = d;
				this.config.iniImage = h;
				this.init(this.$c, false)
			}
			j && this.fullscreen()
		},
		rebuild : function(d, h) {
			if (this.items.thumbs)
				if (this.thumbnailer)
					this.thumbnailer.rebuild(d);
				else
					this.thumbnailer = new i.fn.tn3.Thumbnailer(this.items.thumbs, d, this.config.thumbnailer);
			if (this.items.image)
				if (this.imager)
					this.imager.rebuild(d);
				else
					this.imager = new i.fn.tn3.Imager(this.items.image, d, this.config.image);
			this.setTextValues(true, "image");
			this.setTextValues(false, "album");
			this.trigger("rebuild", {
				album : this.cAlbum
			});
			this.show(h == null ? 0 : h)
		},
		showElements : function(d) {
			if (this.areHidden) {
				var h = this, j;
				i.each(this.special.o, function(f, n) {
					if (h.config.autohideControls) {
						if (n == "prev" && h.n == 0)
							return true;
						if (n == "next" && h.n == h.imager.data.length - 1)
							return true
					}
					j = h.items[n];
					j.show();
					if (d && i.support.opacity) {
						j.stop(true);
						j.css("opacity", 0);
						j.animate({
							opacity : 1
						}, {
							duration : d,
							queue : false
						})
					}
				});
				this.areHidden = false
			}
		},
		hideElements : function(d) {
			if (!this.areHidden) {
				var h = this, j;
				i.each(this.special.o, function(f, n) {
					j = h.items[n];
					if (d && i.support.opacity) {
						j.stop(true);
						j.animate({
							opacity : 0
						}, {
							duration : d,
							complete : function() {
								j.hide()
							},
							queue : false
						})
					} else
						j.hide()
				});
				this.areHidden = true
			}
		},
		setData : function(d) {
			if (this.items.thumbs)
				this.thumbnailer.data = d;
			if (this.items.imager)
				this.imager.data = d
		},
		exitFullScreen : function() {
			!b.isFullScreen() && this.config.isFullScreen && this.fullscreen()
		},
		nativeFS : false,
		fullscreen : function(d) {
			if (this.config.isFullScreen) {
				if (this.config.useNativeFullScreen && this.nativeFS) {
					b.cancelFullScreen(this.$c);
					document.removeEventListener(b.fullScreenEventName, i.proxy(this.exitFullScreen, this))
				} else {
					i(window).unbind("resize", this.onFullResize);
					i.tn3unblock()
				}
				if (this.config.responsive !== false)
					this.initResponsive(this.config.responsive);
				else
					this.config.width !== null || this.config.height !== null ? this.resize(this.config.width, this.config.height) : this.resize(this.config.initValues.width, this.config.initValues.height);
				if (this.items.fullscreen) {
					this.items.fullscreen.removeClass(this.config.cssID + "-fullscreen-active");
					this.items.fullscreen.attr("title", this.t("Maximize"))
				}
				this.config.fullOnly && this.$c.hide();
				this.config.isFullScreen = false;
				this.trigger("fullscreen", {
					fullscreen : false
				});
				this.config.keyNavigation == "fullscreen" && i(document).unbind("keyup", this.listenKeys)
			} else {
				if (this.config.useNativeFullScreen && d) {
					document.addEventListener(b.fullScreenEventName, i.proxy(this.exitFullScreen, this), true);
					b.requestFullScreen(this.$tn3);
					this.nativeFS = true
				} else {
					i.tn3block({
						message : this.$tn3,
						cssID : this.config.cssID
					});
					i(window).bind("resize", i.proxy(this.onFullResize, this));
					this.nativeFS = false
				}
				this.config.fullOnly && this.$c.show();
				this.config.isFullScreen = true;
				if (this.items.fullscreen) {
					this.items.fullscreen.addClass(this.config.cssID + "-fullscreen-active");
					this.items.fullscreen.attr("title", this.t("Minimize"))
				}
				this.onFullResize();
				this.trigger("fullscreen", {
					fullscreen : true
				})
			}
		},
		listenKeys : function(d) {
			if (d.keyCode == 70)
				this.items.fullscreen.click();
			else if (this.items.albums && this.albums.enabled) {
				var h = 0;
				switch(d.keyCode) {
					case 27:
						this.albums.hide();
						break;
					case 39:
						h = "r";
						break;
					case 37:
						h = "l";
						break;
					case 38:
						h = "u";
						break;
					case 40:
						h = "d";
						break;
					case 32:
						h = "p"
				}
				h && this.albums.select(h)
			} else
				switch(d.keyCode) {
					case 27:
						this.config.isFullScreen && this.fullscreen(true);
						break;
					case 39:
						this.show("next");
						break;
					case 37:
						this.show("prev");
						break;
					case 38:
						this.items.albums && this.albums.show(0, this.cAlbum, false, true);
						break;
					case 32:
						this.items.play.click()
				}
		},
		onFullResize : function() {
			if (this.config.useNativeFullScreen && this.nativeFS)
				this.resize(window.screen.width, window.screen.height);
			else {
				var d = i(window), h = d.width();
				d = d.height();
				h -= this.config.initValues.wDif;
				d -= this.config.initValues.hDif;
				this.resize(h, d)
			}
		},
		resize : function(d, h) {
			this.$tn3.width(d).height(h);
			var j = d - this.config.initValues.width, f = h - this.config.initValues.height, n, g, l = this;
			if (this.items.image) {
				n = this.config.image.initValues.width + j;
				g = this.config.image.initValues.height + f;
				if (this.imager)
					this.imager.setSize(n, g);
				else {
					this.items.image.width(n).height(g);
					this.$inImage.width(n).height(g)
				}
			}
			if (this.items.thumbs) {
				n = this.config.thumbnailer.initValues.width + j;
				g = this.config.thumbnailer.initValues.height + f;
				if (this.thumbnailer)
					this.thumbnailer.setSize(n, g);
				else
					this.config.thumbnailer.initValues.vertical ? this.items.thumbs.height(g) : this.items.thumbs.width(n)
			}
			if (this.items.albums) {
				n = this.albums.initValues.width + j;
				g = this.albums.initValues.height + f;
				this.albums.changeSize(j, f)
			}
			i.each(this.special.rh, function(k, m) {
				l.items[m].width(l.config.initValues[m].w + j)
			});
			i.each(this.special.rv, function(k, m) {
				l.items[m].height(l.config.initValues[m].h + f)
			});
			this.center()
		},
		center : function() {
			var d, h = this, j = h.items.image.position();
			i.each(this.special.v, function(f, n) {
				d = h.items[n];
				d.css("top", (d.parent().height() - d.height()) / 2)
			});
			i.each(this.special.h, function(f, n) {
				d = h.items[n];
				d.css("left", (d.parent().width() - d.width()) / 2)
			});
			i.each(this.special.vi, function(f, n) {
				d = h.items[n];
				d.css("top", j.top + (h.items.image.height() - d.height()) / 2)
			});
			i.each(this.special.hi, function(f, n) {
				d = h.items[n];
				d.css("left", j.left + (h.items.image.width() - d.width()) / 2)
			});
			if (this.spinner) {
				this.displayPreloader(true);
				this.displayPreloader(false)
			}
		},
		trigger : function(d, h) {
			var j = i.Event("tn3_" + d), f;
			for (f in h)
			j[f] = h[f];
			if (h && h.type != undefined)
				j.type = "tn3_" + d;
			j.source = this;
			this.$c.trigger(j);
			this.config[d] && this.config[d].call(this, j);
			for (f in h)
			h[f] = j[f]
		},
		initMouseWheel : function() {
			var d = this, h = function(j) {
				d.show((j.originalEvent.detail ? -j.originalEvent.detail : j.originalEvent.wheelDelta) > 0 ? "prev" : "next");
				j.preventDefault()
			};
			this.$tn3.bind("mousewheel", h);
			this.$tn3.bind("DOMMouseScroll", h)
		},
		initResponsive : function(d) {
			var h;
			if ( typeof d == "number") {
				var j = i(window);
				h = function() {
					var t = j.width(), u = j.height();
					this.resize(t * d / 100, u * d / 100)
				}
			} else if (i.isFunction(d))
				h = d;
			else if (d !== false) {
				var f = this.$c.parent(), n = (this.config.width == null ? this.config.initValues.width : this.config.width) / (this.config.height == null ? this.config.initValues.height : this.config.height), g = f.width(), l = f.height();
				h = d == "enabled" ? function() {
					g = f.width();
					l = f.height();
					this.resize(g, l)
				} : d == "height" ? function() {
					l = f.height();
					g = l * n;
					this.resize(g, l)
				} : function() {
					g = f.width();
					l = g / n;
					this.resize(g, l)
				}
			}
			var k = this, m = function() {
				if (k.config.useNativeFullScreen)
					b.isFullScreen() || i.proxy(h,k)();
				else
					i.proxy(h,k)()
			};
			i(window).bind("resize", m);
			m()
		},
		replaceMenu : function(d, h) {
			var j = '<div style="position:absolute;background-color:#fff;color: #000;padding:0px 4px 0px 4px;z-index:1010;font-family:sans-serif;font-size:12px;">&copy; <a href="' + h + '">' + d + "</a></div>";
			this.$tn3.bind("contextmenu", function(f) {
				f.preventDefault()
			}).bind("mousedown", function(f) {
				if (f.which == 3) {
					var n = i(j);
					i("body").append(n);
					n.css("left", f.pageX).css("top", f.pageY);
					n.find("a").mouseup(function(g) {
						window.location = h;
						n.unbind(g)
					});
					i("body").mouseup(function(g) {
						n.remove();
						i("body").unbind(g)
					})
				}
			})
		},
		imageEnter : function() {
			this.config.timerOverStop && this.timer.pause(true);
			this.special.o.length > 0 && this.showElements(this.config.overFadeDuration)
		},
		imageLeave : function() {
			this.config.timerOverStop && this.timer.pause(false);
			this.special.o.length > 0 && this.hideElements(this.config.overFadeDuration)
		},
		displayPreloader : function(d) {
			if (this.items.preloader)
				if (d) {
					this.items.preloader.show();
					if (this.config.spinjs)
						if (this.spinner)
							this.spinner.spin(this.items.preloader[0]);
						else
							this.spinner = (new Spinner(this.config.spinjs)).spin(this.items.preloader[0])
				} else {
					this.spinner && this.spinner.stop();
					this.items.preloader.hide()
				}
		}
	};
	var b = {
		supportsFullScreen : false,
		isFullScreen : function() {
			return false
		},
		requestFullScreen : function() {
		},
		cancelFullScreen : function() {
		},
		fullScreenEventName : "",
		prefix : ""
	}, a = "webkit moz o ms khtml".split(" ");
	if ( typeof document.cancelFullScreen != "undefined")
		b.supportsFullScreen = true;
	else
		for (var c = 0, e = a.length; c < e; c++) {
			b.prefix = a[c];
			if ( typeof document[b.prefix + "CancelFullScreen"] != "undefined") {
				b.supportsFullScreen = true;
				break
			}
		}
	if (b.supportsFullScreen) {
		b.fullScreenEventName = b.prefix + "fullscreenchange";
		b.isFullScreen = function() {
			switch(this.prefix) {
				case "":
					return document.fullScreen;
				case "webkit":
					return document.webkitIsFullScreen;
				default:
					return document[this.prefix + "FullScreen"]
			}
		};
		b.requestFullScreen = function(d) {
			this.prefix === "webkit" && d.css("float", "none");
			return this.prefix === "" ? d[0].requestFullScreen() : d[0][this.prefix+"RequestFullScreen"]()
		};
		b.cancelFullScreen = function(d) {
			this.prefix === "webkit" && d.css("float", "left");
			return this.prefix === "" ? document.cancelFullScreen() : document[this.prefix+"CancelFullScreen"]()
		}
	}
})(jQuery);
(function(i, b, a) {
	function c(q, p) {
		var r = b.createElement(q || "div"), s;
		for (s in p)
		r[s] = p[s];
		return r
	}

	function e(q) {
		for (var p = 1, r = arguments.length; p < r; p++)
			q.appendChild(arguments[p]);
		return q
	}

	function d(q, p, r, s) {
		var v = ["opacity", p, ~~(q * 100), r, s].join("-");
		r = 0.01 + r / s * 100;
		s = Math.max(1 - (1 - q) / p * (100 - r), q);
		var w = k.substring(0, k.indexOf("Animation")).toLowerCase();
		w = w && "-" + w + "-" || "";
		return l[v] || (m.insertRule("@" + w + "keyframes " + v + "{0%{opacity:" + s + "}" + r + "%{opacity:" + q + "}" + (r + 0.01) + "%{opacity:1}" + (r + p) % 100 + "%{opacity:" + q + "}100%{opacity:" + s + "}}", m.cssRules.length), l[v] = 1), v
	}

	function h(q, p) {
		var r = q.style, s, v;
		if (r[p] !== a)
			return p;
		p = p.charAt(0).toUpperCase() + p.slice(1);
		for ( v = 0; v < g.length; v++) {
			s = g[v] + p;
			if (r[s] !== a)
				return s
		}
	}

	function j(q, p) {
		for (var r in p)
		q.style[h(q, r) || r] = p[r];
		return q
	}

	function f(q) {
		for (var p = 1; p < arguments.length; p++) {
			var r = arguments[p], s;
			for (s in r)q[s] === a && (q[s] = r[s])
		}
		return q
	}

	function n(q) {
		for (var p = {
			x : q.offsetLeft,
			y : q.offsetTop
		}; q = q.offsetParent; ) {
			p.x += q.offsetLeft;
			p.y += q.offsetTop
		}
		return p
	}

	var g = ["webkit", "Moz", "ms", "O"], l = {}, k, m = function() {
		var q = c("style", {
			type : "text/css"
		});
		return e(b.getElementsByTagName("head")[0], q), q.sheet || q.styleSheet
	}(), t = {
		lines : 12,
		length : 7,
		width : 5,
		radius : 10,
		rotate : 0,
		corners : 1,
		color : "#000",
		speed : 1,
		trail : 100,
		opacity : 0.25,
		fps : 20,
		zIndex : 2E9,
		className : "spinner",
		top : "auto",
		left : "auto"
	}, u = function q(p) {
		if (!this.spin)
			return new q(p);
		this.opts = f(p || {}, q.defaults, t)
	};
	u.defaults = {};
	f(u.prototype, {
		spin : function(q) {
			this.stop();
			var p = this, r = p.opts, s = p.el = j(c(0, {
				className : r.className
			}), {
				position : "relative",
				width : 0,
				zIndex : r.zIndex
			}), v = r.radius + r.length + r.width, w, y;
			q && (q.insertBefore(s, q.firstChild || null), y = n(q), w = n(s), j(s, {
				left : (r.left == "auto" ? y.x - w.x + (q.offsetWidth >> 1) : parseInt(r.left, 10) + v) + "px",
				top : (r.top == "auto" ? y.y - w.y + (q.offsetHeight >> 1) : parseInt(r.top, 10) + v) + "px"
			}));
			s.setAttribute("aria-role", "progressbar");
			p.lines(s, p.opts);
			if (!k) {
				var z = 0, x = r.fps, A = x / r.speed, C = (1 - r.opacity) / (A * r.trail / 100), D = A / r.lines;
				(function E() {
					z++;
					for (var B = r.lines; B; B--)
						p.opacity(s, r.lines - B, Math.max(1 - (z + B * D) % A * C, r.opacity), r);
					p.timeout = p.el && setTimeout(E, ~~(1E3 / x))
				})()
			}
			return p
		},
		stop : function() {
			var q = this.el;
			return q && (clearTimeout(this.timeout), q.parentNode && q.parentNode.removeChild(q), this.el = a), this
		},
		lines : function(q, p) {
			function r(w, y) {
				return j(c(), {
					position : "absolute",
					width : p.length + p.width + "px",
					height : p.width + "px",
					background : w,
					boxShadow : y,
					transformOrigin : "left",
					transform : "rotate(" + ~~(360 / p.lines * s + p.rotate) + "deg) translate(" + p.radius + "px,0)",
					borderRadius : (p.corners * p.width >> 1) + "px"
				})
			}

			for (var s = 0, v; s < p.lines; s++) {
				v = j(c(), {
					position : "absolute",
					top : 1 + ~(p.width / 2) + "px",
					transform : p.hwaccel ? "translate3d(0,0,0)" : "",
					opacity : p.opacity,
					animation : k && d(p.opacity, p.trail, s, p.lines) + " " + 1 / p.speed + "s linear infinite"
				});
				p.shadow && e(v, j(r("#000", "0 0 4px #000"), {
					top : "2px"
				}));
				e(q, e(v, r(p.color, "0 0 1px rgba(0,0,0,.1)")))
			}
			return q
		},
		opacity : function(q, p, r) {
			p < q.childNodes.length && (q.childNodes[p].style.opacity = r)
		}
	});
	(function() {
		function q(r, s) {
			return c("<" + r + ' xmlns="urn:schemas-microsoft.com:vml" class="spin-vml">', s)
		}

		var p = j(c("group"), {
			behavior : "url(#default#VML)"
		});
		!h(p, "transform") && p.adj ? (m.addRule(".spin-vml", "behavior:url(#default#VML)"), u.prototype.lines = function(r, s) {
			function v() {
				return j(q("group", {
					coordsize : z + " " + z,
					coordorigin : -y + " " + -y
				}), {
					width : z,
					height : z
				})
			}

			function w(C, D, E) {
				e(A, e(j(v(), {
					rotation : 360 / s.lines * C + "deg",
					left : ~~D
				}), e(j(q("roundrect", {
					arcsize : s.corners
				}), {
					width : y,
					height : s.width,
					left : s.radius,
					top : -s.width >> 1,
					filter : E
				}), q("fill", {
					color : s.color,
					opacity : s.opacity
				}), q("stroke", {
					opacity : 0
				}))))
			}

			var y = s.length + s.width, z = 2 * y, x = -(s.width + s.length) * 2 + "px", A = j(v(), {
				position : "absolute",
				top : x,
				left : x
			});
			if (s.shadow)
				for ( x = 1; x <= s.lines; x++)
					w(x, -2, "progid:DXImageTransform.Microsoft.Blur(pixelradius=2,makeshadow=1,shadowopacity=.3)");
			for ( x = 1; x <= s.lines; x++)
				w(x);
			return e(r, A)
		}, u.prototype.opacity = function(r, s, v, w) {
			r = r.firstChild;
			w = w.shadow && w.lines || 0;
			r && s + w < r.childNodes.length && ( r = r && r.firstChild, r && (r.opacity = v))
		}) : k = h(p, "animation")
	})();
	typeof define == "function" && define.amd ? define(function() {
		return u
	}) : i.Spinner = u
})(window, document); 


(function(i) {
	i.fn.tn3.Imager = function(b, a, c) {
		this.$c = b;
		this.data = a;
		c.crop = false;
		this.config = i.extend(true, {}, i.fn.tn3.Imager.config, c);
		this.init()
	};
	i.fn.tn3.Imager.config = {
		transitions : null,
		defaultTransition : {
			type : "slide"
		},
		random : false,
		cssID : "tn3",
		maxZoom : 1.4,
		crop : false,
		clickEvent : "click",
		idleDelay : 3E3,
		stretch : true,
		dif : 0
	};
	i.fn.tn3.Imager.prototype = {
		config : null,
		$c : false,
		$ins : null,
		data : false,
		cached : null,
		active : -1,
		$active : false,
		$buffer : false,
		isInTransition : false,
		ts : null,
		cDim : null,
		qid : null,
		currentlyLoading : null,
		side : null,
		$ic : null,
		$binder : null,
		infoID : null,
		lastEnter : false,
		mouseCoor : {
			x : 0,
			y : 0
		},
		mouseIsOver : false,
		init : function() {
			this.$c.css("overflow", "hidden");
			this.$c.css("position", "relative");
			this.$ins = i('<div class="' + this.config.cssID + '-image-ins" style="position:absolute;width:100%;height:100%;"></div>');
			this.$c.prepend(this.$ins);
			this.bindMouseEvents(this.$c);
			this.cached = [];
			this.ts = new i.fn.tn3.Transitions(this.config.transitions, this.config.defaultTransition, this.config.random, this, "onTransitionEnd")
		},
		bindMouseEvents : function(b) {
			this.unbindMouseEvents();
			var a = this;
			b.hover(function() {
				a.mouseIsOver = true;
				a.enterLeave("enter");
				a.startIdle();
				i(document).mousemove(i.proxy(a.onMouseMove, a))
			}, function() {
				a.mouseIsOver = false;
				a.enterLeave("leave");
				a.stopIdle();
				i(document).unbind("mousemove", a.onMouseMove)
			});
			b[this.config.clickEvent](function(c) {
				a.active == -1 || a.isInTransition || c.target.tagName.toUpperCase() != "A" && a.trigger("click", {
					n : a.active
				})
			});
			this.$binder = b
		},
		unbindMouseEvents : function() {
			this.$binder && this.$binder.unbind("mouseenter mouseleave " + this.config.clickEvent);
			i(document).unbind("mousemove", this.onMouseMove);
			this.stopIdle()
		},
		startIdle : function() {
			this.stopIdle();
			var b = this;
			if (this.config.idleDelay > 0)
				this.infoID = setTimeout(function() {
					b.enterLeave("leave");
					b.stopIdle()
				}, this.config.idleDelay)
		},
		onMouseMove : function(b) {
			this.mouseCoor = {
				x : b.pageX,
				y : b.pageY
			};
			if (!this.isInTransition) {
				this.infoID || this.enterLeave("enter");
				this.startIdle()
			}
		},
		stopIdle : function() {
			clearTimeout(this.infoID);
			this.infoID = null
		},
		enterLeave : function(b) {
			this.lastEnter != b && this.trigger(b);
			this.lastEnter = b
		},
		show : function(b) {
			if (this.isInTransition)
				this.qid = b;
			else {
				this.qid = null;
				if (b == "next") {
					b = this.active + 1 < this.data.length ? this.active + 1 : 0;
					this.side = "left"
				} else if (b == "prev") {
					b = this.active > 0 ? this.active - 1 : this.data.length - 1;
					this.side = "right"
				} else
					this.side = this.active > b ? "right" : "left";
				if (this.data[b]) {
					this.trigger("load_start", {
						n : b
					});
					this.$buffer = i('<div class="' + this.config.cssID + '-image-in" style="position:absolute;overflow:hidden;"></div>');
					this.$buffer.css("visibility", "hidden");
					this.$ins.prepend(this.$buffer);
					if (this.cached[this.currentlyLoading] != undefined)
						this.cached[this.currentlyLoading].init = false;
					if (this.cached[b] != undefined)
						if (this.cached[b].status == "loaded")
							this.initImage(b);
						else {
							this.cached[b].init = true;
							this.currentlyLoading = b
						}
					else
						this.startLoading(b, true)
				}
			}
		},
		startLoading : function(b, a) {
			var c = typeof b == "number" ? b : b == "next" ? this.active + 1 : this.active - 1;
			if (!(this.cached[c] != undefined && this.cached[c].status == "loaded" || this.data[c] == undefined)) {
				this.cached[c] = {
					isImage : true,
					status : "loading",
					init : a
				};
				this.currentlyLoading = c;
				if (this.data[c].content != undefined) {
					this.cached[c].isImage = false;
					this.onCacheLoad(i(i.trim(this.data[c].content)), c)
				} else
					this.cached[c].loader = new i.fn.tn3.ImageLoader(this.data[c].img, this, this.onCacheLoad, [c])
			}
		},
		onCacheLoad : function(b, a, c) {
			this.cached[a].status = "loaded";
			this.cached[a].isImage && b.attr("alt", this.data[a].alt);
			this.cached[a].$content = b;
			c && this.trigger("error", {
				description : c,
				n : a
			});
			this.cached[a].init && this.initImage(a)
		},
		initImage : function(b) {
			var a = this.cached[b].$content;
			this.currentlyLoading = null;
			this.active = b;
			if (!this.cDim)
				this.cDim = {
					w : this.$c.width(),
					h : this.$c.height()
				};
			this.$buffer.width(this.cDim.w).height(this.cDim.h);
			var c = i('<div class="' + this.config.cssID + '-full-image" style="position:absolute"></div>');
			a.appendTo(c);
			this.$buffer.append(c);
			this.$buffer.data("ic", c);
			this.$buffer.data("img", a);
			this.resize(this.$buffer);
			c = [true];
			this.trigger("load_end", {
				n : b,
				content : a,
				isImage : this.cached[b].isImage,
				doTransition : c
			});
			c[0] && this.initTransition()
		},
		initTransition : function() {
			this.$buffer.css("visibility", "visible");
			if (this.$active != false) {
				this.isInTransition = true;
				this.unbindMouseEvents();
				if (this.mouseIsOver)
					i(document).mousemove(i.proxy(this.onMouseMove, this));
				else
					this.mouseCoor = {
						x : 0,
						y : 0
					};
				this.lastEnter = "leave";
				this.$active.find("video").length > 0 && this.config.transitions && this.config.transitions.length > 0 && this.config.transitions[0].type === "translate" ? this.ts.start(this.$active, this.$buffer, this.side, true) : this.ts.start(this.$active, this.$buffer, this.side)
			} else {
				this.$active = this.$buffer;
				this.trigger("transition", {
					n : this.active
				})
			}
			this.startLoading(this.side == "right" ? "prev" : "next", false)
		},
		setSize : function(b, a) {
			this.isInTransition && this.ts.stop(this.$active, this.$buffer, this.ts.config);
			this.$c.width(b).height(a);
			this.cDim = {
				w : this.$c.width(),
				h : this.$c.height()
			};
			if (this.$active) {
				this.$active.width(b).height(a);
				this.resize(this.$active)
			}
		},
		resize : function(b) {
			if (b.data("img") == undefined)
				this.trigger("resize", {
					w : this.cDim.w,
					h : this.cDim.h,
					left : 0,
					top : 0
				});
			else
				this.cached[this.active].isImage ? this.resizeImage(b) : this.resizeContent(b)
		},
		resizeImage : function(b) {
			$img = b.data("img");
			$ic = b.data("ic");
			$img.width("auto").height("auto");
			b.data("scaled", false);
			var a = $img.width(), c = $img.height(), e = 0, d = 0, h = {
				w : a,
				h : c,
				left : 0,
				top : 0
			};
			$img.attr("width", a).attr("height", c);
			if ($img.get(0).tagName.toUpperCase() == "IMG" && (a != this.cDim.w || c != this.cDim.h)) {
				e = this.cDim.w / a;
				d = this.cDim.h / c;
				e = this.config.crop ? Math.max(e, d) : Math.min(e, d);
				e = Math.min(this.config.maxZoom, e);
				a = h.w = Math.round(a * e) - this.config.dif;
				c = h.h = Math.round(c * e) - this.config.dif;
				if (this.cDim.w >= a)
					e = h.left = (this.cDim.w - a) / 2;
				else {
					e = -(a - this.cDim.w) * 0.5;
					h.w = this.cDim.w
				}
				if (this.cDim.h > c)
					d = h.top = (this.cDim.h - c) / 2;
				else {
					d = -(c - this.cDim.h) * 0.5;
					h.h = this.cDim.h
				}
				$img.width(a).height(c);
				$img.attr("width", a).attr("height", c);
				$ic.width(a).height(c);
				b.data("scaled", true)
			}
			$ic.css("left", e).css("top", d);
			this.bindMouseEvents($ic);
			this.trigger("resize", h)
		},
		resizeContent : function(b) {
			$ic = b.data("ic");
			$img = b.data("img");
			b.data("scaled", false);
			var a = $img.width(), c = $img.height(), e = {
				w : a,
				h : c,
				left : 0,
				top : 0
			};
			if (this.config.stretch) {
				$ic.width(this.cDim.w).height(this.cDim.h);
				$img.width(this.cDim.w).height(this.cDim.h);
				b.data("scaled", true)
			} else {
				e.left = (this.cDim.w - a) * 0.5;
				e.top = (this.cDim.h - c) * 0.5;
				$ic.css("left", e.left).css("top", e.top)
			}
			this.bindMouseEvents($ic);
			this.trigger("resize", e)
		},
		onTransitionEnd : function() {
			this.$active.remove();
			this.$active = this.$buffer;
			this.$active.css("width", "+=1");
			this.isInTransition = false;
			this.trigger("transition", {
				n : this.active
			});
			this.bindMouseEvents(this.$binder);
			var b = this.$binder.offset();
			this.mouseIsOver = false;
			if (this.mouseCoor.x >= b.left && this.mouseCoor.x <= b.left + this.$binder.width())
				if (this.mouseCoor.y >= b.top && this.mouseCoor.y <= b.top + this.$binder.height()) {
					this.lastEnter = "leave";
					this.enterLeave("enter");
					this.startIdle();
					this.mouseIsOver = true;
					i(document).mousemove(i.proxy(this.onMouseMove, this))
				}
			this.qid != null && this.show(this.qid)
		},
		trigger : function(b, a) {
			var c = i.Event("img_" + b), e;
			for (e in a)
			c[e] = a[e];
			c.source = this;
			this.$c.trigger(c);
			this.config[b] && this.config[b].call(this, c)
		},
		destroy : function() {
			this.isInTransition && this.ts.stop(this.$active, this.$buffer);
			this.$active && this.$active.remove();
			this.$buffer.remove()
		},
		rebuild : function(b) {
			this.quid = null;
			this.isInTransition && this.ts.stop(this.$active, this.$buffer);
			this.$buffer && this.$buffer.remove();
			this.cached = [];
			this.data = b;
			this.loader && this.loader.cancel()
		}
	}
})(jQuery);


(function(i) {
	i.fn.tn3.Thumbnailer = function(b, a, c) {
		this.$c = b;
		this.data = a;
		this.config = i.extend({}, i.fn.tn3.Thumbnailer.config, c);
		i(window).resize(i.proxy(this.onWinResize, this));
		this.init()
	};
	i.fn.tn3.Thumbnailer.config = {
		overMove : true,
		buffer : 20,
		speed : 8,
		slowdown : 50,
		shaderColor : "#000000",
		shaderOpacity : 0.5,
		shaderDuration : 300,
		shaderOut : 300,
		useTitle : false,
		seqLoad : true,
		align : 1,
		mode : "thumbs",
		cssID : "tn3"
	};
	i.fn.tn3.Thumbnailer.prototype = {
		config : null,
		$c : null,
		$oc : null,
		$ul : null,
		data : null,
		active : -1,
		listSize : 0,
		containerSize : 0,
		containerPadding : 0,
		noBufSize : 0,
		containerOffset : 0,
		mcoor : "mouseX",
		edge : "left",
		size : "width",
		outerSize : "outerWidth",
		mouseX : 0,
		mouseY : 0,
		intID : false,
		pos : 0,
		difference : 0,
		cnt : 1,
		thumbCount : -1,
		initialized : false,
		clickWhenReady : -1,
		loaders : null,
		lis : null,
		isVertical : null,
		marginDif : 0,
		nloaded : 0,
		firstToLoad : 0,
		isTouch : false,
		init : function() {
			this.$c.css("position", "absolute").css("cursor", "progress");
			this.lis = [];
			this.loaders = [];
			this.initialized = false;
			this.$oc = i("<div />");
			this.$ul = i("<ul />");
			this.$oc.appendTo(this.$c);
			this.$oc.css("position", "absolute").css("overflow", "hidden").width(this.$c.width()).height(this.$c.height());
			this.$ul.appendTo(this.$oc);
			this.$ul.css("position", "relative").css("margin", "0px").css("padding", "0px").css("border-width", "0px").css("width", "12000px").css("list-style", "none");
			if (this.isVertical == null) {
				this.isVertical = this.$c.width() < this.$c.height();
				if (this.isVertical = false) {
					this.mcoor = "mouseY";
					this.edge = "top";
					this.size = "height";
					this.outerSize = "outerHeight"
				} else {
					this.mcoor = "mouseX";
					this.edge = "left";
					this.size = "width";
					this.outerSize = "outerWidth"
				}
				this.containerSize = this.$oc[this.size]();
				this.noBufSize = this.containerSize - 2 * this.config.buffer;
				this.containerOffset = this.$oc.offset()[this.edge];
				this.containerPadding = parseInt(this.$c.css("padding-" + this.edge))
			}
			this.listSize = 0;
			if (navigator.userAgent.indexOf("MSIE") != -1)
				this.config.seqLoad = false;
			this.data.length > 0 && this.loadNextThumb()
		},
		loadNextThumb : function() {
			this.thumbCount++;
			var b = i("<li></li>");
			this.$ul.append(b);
			if (this.config.mode == "thumbs") {
				var a = this.data[this.thumbCount].thumb;
				if (a) {
					this.loaders.push(new i.fn.tn3.ImageLoader(a, this, this.onLoadThumb, [b, this.thumbCount]));
					!this.config.seqLoad && this.thumbCount < this.data.length - 1 && this.loadNextThumb();
					return
				} else
					this.config.mode = "bullets"
			}
			this.config.mode == "numbers" && b.text(this.thumbCount + 1);
			this.onLoadThumb(null, b, this.thumbCount)
		},
		onLoadThumb : function(b, a, c, e) {
			this.lis[c] = {
				li : a
			};
			a.addClass(this.config.cssID + "-thumb");
			a.css("float", this.isVertical ? "none" : "left");
			if (b) {
				var d = this.lis[c].thumb = i(b);
				a.append(d);
				this.lis[c].pos = a.position()[this.edge]
			}
			this.config.useTitle && this.data[c].title && a.attr("title", this.data[c].title.replace(/\&amp;/g, "&"));
			if (this.config.mode == "thumbs" && this.config.shaderOpacity > 0) {
				this.lis[c].shade = i("<div/>");
				a.prepend(this.lis[c].shade);
				this.lis[c].shade.css("background-color", this.config.shaderColor).css("width", d.width()).css("height", d.height()).css("position", "absolute")
			}
			this.initThumb(c);
			a.css("opacity", 0);
			a.animate({
				opacity : 1
			}, 1E3);
			this.listSize += a[this.outerSize](true);
			if (!this.initialized) {
				this.firstToLoad = c;
				this.initialized = true;
				this.initMouse(true)
			}
			e && this.trigger("error", {
				description : e,
				n : c
			});
			this.trigger("thumbLoad", {
				n : c
			});
			this.nloaded++;
			if (this.nloaded < this.data.length) {
				if (this.config.seqLoad || this.config.mode != "thumbs")
					this.loadNextThumb()
			} else {
				if (b)
					this.loaders = null;
				if (!this.config.seqLoad)
					for ( b = 0; b < this.lis.length; b++)
						this.lis[b].pos = this.lis[b].li.position()[this.edge];
				this.thumbsLoaded()
			}
			if (this.clickWhenReady == c) {
				this.clickWhenReady = -1;
				this.thumbClick(c)
			}
		},
		initThumb : function(b) {
			var a = this.lis[b];
			if (a.li) {
				a.li.removeClass().addClass(this.config.cssID + "-thumb");
				if (a.shade) {
					a.shade.stop();
					a.shade.css("opacity", this.config.shaderOpacity)
				}
				var c = this;
				a.li.click(function() {
					c.thumbClick(b);
					c.trigger("click", {
						n : b
					});
					return false
				});
				this.config.mode != "thumbs" && a.li.hover(function() {
					c.mouseOver(b)
				}, function() {
					c.mouseOver(-1)
				})
			}
		},
		lastOver : -1,
		mouseOver : function(b) {
			if (b != this.lastOver) {
				if (this.lastOver != -1 && this.lastOver != this.active) {
					a = this.lis[this.lastOver];
					a.li.removeClass(this.config.cssID + "-thumb-over");
					if (a.shade) {
						a.shade.stop();
						a.shade.animate({
							opacity : this.config.shaderOpacity
						}, {
							duration : this.config.shaderOut,
							easing : "easeOutCubic",
							queue : false
						})
					}
					this.trigger("thumbOut", {
						n : b
					})
				}
				this.lastOver = b;
				if (!(b == -1 || b == this.active)) {
					var a = this.lis[b];
					a.li.addClass(this.config.cssID + "-thumb-over");
					if (a.shade) {
						a.shade.stop();
						a.shade.animate({
							opacity : 0
						}, {
							duration : this.config.shaderDuration,
							easing : "easeOutCubic",
							queue : false
						})
					}
					this.trigger("thumbOver", {
						n : b
					})
				}
			}
		},
		next : function(b) {
			if (b)
				this.listSize > this.containerSize && this.move(this.$ul.position()[this.edge] - this.containerSize);
			else {
				b = this.active + 1;
				if (this.active == -1 || this.active + 1 == this.data.length)
					b = 0;
				this.thumbClick(b)
			}
		},
		prev : function(b) {
			if (b)
				this.listSize > this.containerSize && this.move(this.$ul.position()[this.edge] + this.containerSize);
			else {
				b = this.active - 1;
				if (this.active == -1 || this.active == 0)
					b = this.data.length - 1;
				this.thumbClick(b)
			}
		},
		move : function(b) {
			var a = {};
			a[this.edge] = Math.min(0, Math.max(b, -(this.listSize - this.containerSize)));
			this.$ul.stop();
			this.$ul.animate(a, 300)
		},
		thumbClick : function(b) {
			if (this.active == -1) {
				if (this.thumbCount < b || b > this.lis.length - 1 || this.lis[b] == null) {
					this.clickWhenReady = b;
					return
				}
			} else if (b == this.active)
				return;
			else
				this.initThumb(this.active);
			if (b == "next")
				b = this.active + 1 < this.data.length ? this.active + 1 : 0;
			else if (b == "prev")
				b = this.active > 0 ? this.active - 1 : this.data.length - 1;
			if (this.lis[b]) {
				var a = this.lis[b];
				a.li.addClass(this.config.cssID + "-thumb-selected").unbind("click mouseenter mouseleave");
				a.shade && a.shade.animate({
					opacity : 0
				}, this.config.shaderDuration);
				this.active = b;
				this.centerActive()
			}
		},
		centerActive : function(b) {
			if (this.active != -1) {
				var a = this.lis[this.active].li, c = this.$ul.position()[this.edge] + a.position()[this.edge], e = a[this.outerSize]() / 2;
				if (c + e > this.containerSize || c + e < 0) {
					a = 10 - a.position()[this.edge] + this.containerSize / 2 - e;
					a = Math.min(0, a);
					a = Math.max(a, -this.listSize + this.containerSize);
					c = {};
					c[this.edge] = a;
					if (this.isTouch)
						this.$oc["scroll"+this.edge.substring(0,1).toUpperCase()+this.edge.substring(1)](-a);
					else
						b ? this.$ul.css(c) : this.$ul.animate(c, 200)
				}
			}
		},
		thumbsLoaded : function() {
			this.$c.css("cursor", "auto");
			this.$ul.css("width", this.listSize + "px");
			this.centerList();
			this.trigger("load")
		},
		centerList : function(b) {
			if (this.listSize < this.containerSize) {
				var a = {};
				a[this.edge] = this.config.align ? this.config.align == 1 ? (this.containerSize - this.listSize) / 2 : this.containerSize - this.listSize : 0;
				b || this.config.mode != "thumbs" ? this.$ul.css(a) : this.$ul.animate(a, 300)
			} else {
				this.centerActive(b);
				if (this.$ul.position()[this.edge] > 0)
					this.$ul.css(this.edge, 0);
				else
					this.$ul.position()[this.edge] + this.listSize < this.containerSize && this.$ul.css(this.edge, -(this.listSize - this.containerSize))
			}
		},
		initMouse : function(b) {
			if (this.config.mode == "thumbs") {
				b = b ? "bind" : "unbind";
				this.$oc[b]("mouseenter", i.proxy(this.mouseenter, this));
				this.$oc[b]("mouseleave", i.proxy(this.mouseleave, this))
			}
		},
		mouseenter : function() {
			this.trigger("over");
			clearInterval(this.intID);
			var b = this;
			this.$ul.stop();
			this.$c.mousemove(this.mcoor == "mouseX" ? function(a) {
				b.mouseX = a.pageX - b.containerOffset
			} : function(a) {
				b.mouseY = a.pageY - b.containerOffset
			});
			this.marginDif = parseInt(this.lis[this.firstToLoad].li.css("margin-" + this.edge));
			if (isNaN(this.marginDif))
				this.marginDif = 0;
			b.intID = this.listSize > this.containerSize && this.config.overMove ? setInterval(function() {
				b.slide.call(b)
			}, 10) : setInterval(function() {
				b.mouseTrack.call(b)
			}, 10)
		},
		mouseleave : function() {
			this.trigger("out");
			this.$c.unbind("mousemove");
			clearInterval(this.intID);
			var b = this;
			this.intID = setInterval(function() {
				b.slideOut.call(b)
			}, 10);
			this.mouseOver(-1)
		},
		slide : function() {
			this.cnt = 1;
			var b = this[this.mcoor];
			if (b <= this.config.buffer)
				this.pos = 0;
			else if (b >= this.containerSize - this.config.buffer)
				this.pos = this.containerSize - this.listSize - 1;
			else {
				var a = this.containerSize * (b - this.config.buffer);
				a /= this.noBufSize;
				this.pos = a * (1 - this.listSize / this.containerSize)
			}
			for ( a = this.lis.length - 1; a > -1; a--) {
				var c = b - this.prevdx;
				if (c >= this.lis[a].pos && c < this.lis[a].pos + this.lis[a].li[this.size]()) {
					this.mouseOver(a);
					break
				}
			}
			b = this.prevdx - this.marginDif;
			this.difference = b - this.pos;
			b = Math.round(b - this.difference / this.config.speed);
			if (this.prevdx != b) {
				this.$ul.css(this.edge, b);
				this.prevdx = b
			}
		},
		prevdx : 0,
		mouseTrack : function() {
			for (var b = this[this.mcoor], a = this.lis.length - 1; a > -1; a--) {
				var c = b - this.$ul.position()[this.edge];
				if (c >= this.lis[a].pos && c < this.lis[a].pos + this.lis[a].li[this.size]()) {
					this.mouseOver(a);
					break
				}
			}
		},
		clickOn : function(b) {
			b = b[this.edge] - this.$ul.position()[this.edge] - this.$c.offset()[this.edge];
			for (var a = 0; a < this.lis.length; a++) {
				lpos = this.lis[a].li.position()[this.edge];
				if (b >= lpos && b < lpos + this.lis[a].li[this.size]()) {
					this.lis[a].li.click();
					break
				}
			}
		},
		slideOut : function() {
			if (this.config.slowdown != 0 && this.difference != 0) {
				var b = this.$ul.position()[this.edge];
				this.difference = b - this.pos;
				this.$ul.css(this.edge, b - this.difference / (this.config.speed * this.cnt));
				this.cnt *= 1 + 4 / this.config.slowdown;
				if (this.cnt >= 40) {
					this.difference = 0;
					this.cnt = 1
				}
			} else {
				clearInterval(this.intID);
				this.intID = null
			}
		},
		trigger : function(b, a) {
			var c = i.Event("tn_" + b), e;
			for (e in a)
			c[e] = a[e];
			c.source = this;
			this.$c.trigger(c);
			this.config[b] && this.config[b].call(this, c)
		},
		destroy : function() {
			clearInterval(this.intID);
			this.$c.empty()
		},
		rebuild : function(b) {
			clearInterval(this.intID);
			this.$c.empty();
			this.data = b;
			this.active = this.thumbCount = -1;
			this.nloaded = 0;
			this.initMouse(false);
			this.loaders !== null && i.each(this.loaders, function(a, c) {
				c.cancel()
			});
			this.init()
		},
		setSize : function(b, a) {
			if (this.config.mode == "thumbs") {
				this.isVertical ? this.$c.height(a) : this.$c.width(b);
				this.$oc.width(this.$c.width()).height(this.$c.height());
				this.containerSize = this.$oc[this.size]();
				this.noBufSize = this.containerSize - 2 * this.config.buffer;
				this.containerOffset = this.$oc.offset()[this.edge];
				this.initMouse(true);
				this.loaders === null && this.centerList(true)
			}
		},
		onWinResize : function() {
			this.containerOffset = this.$oc.offset()[this.edge]
		}
	}
})(jQuery);


(function(i) {
	i.fn.tn3.altLink = null;
	i.fn.tn3.ImageLoader = function(b, a, c, e) {
		this.$img = i(new Image);
		e.unshift(this.$img);
		this.altLink = i.fn.tn3.altLink;
		a = {
			url : b,
			context : a,
			callback : c,
			args : e
		};
		this.$img.bind("load", a, this.load);
		this.$img.bind("error", a, i.proxy(this.error, this));
		this.$img.attr("src", b)
	};
	i.fn.tn3.ImageLoader.prototype = {
		$img : null,
		altLink : null,
		load : function(b) {
			b.data.callback.apply(b.data.context, b.data.args);
			b.data.args[0].unbind("load").unbind("error")
		},
		error : function(b) {
			if (this.altLink) {
				this.altLink = null;
				this.$img.attr("src", i.fn.tn3.altLink + b.data.url)
			} else {
				b.data.args.push("image loading error: " + b.data.url);
				b.data.callback.apply(b.data.context, b.data.args);
				this.$img.unbind("load").unbind("error")
			}
		},
		cancel : function() {
			this.$img.unbind("load").unbind("error")
		}
	}
})(jQuery);
(function(i) {
	i.fn.tn3.Timer = function(b, a, c) {
		this.$target = b;
		this.duration = a;
		this.tickint = c
	};
	i.fn.tn3.Timer.prototype = {
		$target : null,
		duration : null,
		id : null,
		runs : false,
		counter : null,
		countDuration : null,
		tickid : null,
		ticks : null,
		tickint : 500,
		start : function() {
			if (!this.runs) {
				this.runs = true;
				this.startCount(this.duration);
				this.trigger("timer_start")
			}
		},
		startCount : function(b) {
			this.clean();
			this.countDuration = b;
			this.counter = +new Date;
			var a = this;
			this.id = setTimeout(function() {
				a.clean.call(a);
				a.runs = false;
				a.trigger.call(a, "timer_end")
			}, b);
			var c = this.duration / this.tickint;
			this.ticks = Math.round(b / c);
			this.tickid = setInterval(function() {
				a.ticks = Math.ceil((b - new Date + a.counter) / c);
				a.ticks > 0 && a.trigger.call(a, "timer_tick", {
					tick : a.ticks,
					totalTicks : a.tickint
				})
			}, c);
			this.trigger("timer_start");
			this.trigger("timer_tick", {
				tick : this.ticks,
				totalTicks : this.tickint
			})
		},
		stop : function() {
			this.clean();
			this.runs = false;
			this.trigger("timer_stop")
		},
		clean : function() {
			clearTimeout(this.id);
			this.id = null;
			clearInterval(this.tickid);
			this.elapsed = this.tickid = null
		},
		elapsed : null,
		pause : function(b) {
			if (this.runs) {
				if (b) {
					this.clean();
					var a = this.duration / this.tickint;
					this.elapsed = Math.floor((+new Date - this.counter) / a) * a
				} else {
					if (this.elapsed == null)
						return;
					this.startCount(this.countDuration - this.elapsed);
					this.elapsed = null
				}
				this.trigger("timer_pause", {
					pause : b
				})
			}
		},
		trigger : function(b, a) {
			var c = i.Event(b), e;
			for (e in a)
			c[e] = a[e];
			this.$target.trigger(c)
		}
	}
})(jQuery);


(function(i) {
	var b = i.fn.tn3.Transitions = function(c, e, d, h, j) {
		this.ts = c;
		this.def = i.extend(true, {}, this[e.type + "Config"], e);
		if (!c)
			this.ts = [this.def];
		for (var f in this.ts)
		this.ts[f] = i.extend(true, {}, this[this.ts[f].type + "Config"], this.ts[f]);
		this.random = d;
		this.end = i.proxy(h, j)
	}, a = b.prototype = {
		ts : null,
		def : {
			type : "slide"
		},
		random : false,
		gs : [],
		end : null,
		ct : null,
		counter : -1,
		setTransition : function() {
			if (this.ts.length == 1)
				this.ct = this.ts[0];
			else {
				this.counter++;
				if (this.counter == this.ts.length)
					this.counter = 0;
				this.random && this.counter == 0 && i.fn.tn3utils.shuffle(this.ts);
				this.ct = this.ts[this.counter]
			}
		},
		start : function(c, e, d, h) {
			if (h)
				this.ct = this.def;
			else
				this.setTransition();
			if (this[this.ct.type + "Condition"] !== undefined && !this[this.ct.type+"Condition"](c, e, this.ct))
				this.ct = this.def;
			this[this.ct.type](c, e, this.ct, d)
		},
		stop : function(c, e) {
			this[this.ct.type+"Stop"](c, e, this.ct)
		},
		makeGrid : function(c, e, d) {
			var h = c.width(), j = Math.round(h / e);
			h = h - j * e;
			var f = c.height(), n = Math.round(f / d);
			f = f - n * d;
			var g, l, k, m, t, u = 0, q = 0, p = "url(" + c.find("img").attr("src") + ") no-repeat scroll -";
			for ( g = 0; g < e; g++) {
				this.gs[g] = [];
				m = h > g ? j + 1 : j;
				for ( l = 0; l < d; l++) {
					k = c.append("<div></div>").find(":last");
					t = f > l ? n + 1 : n;
					k.width(m).height(t).css("background", p + u + "px -" + q + "px").css("left", u).css("top", q).css("position", "absolute");
					this.gs[g].push(k);
					q += t
				}
				u += m;
				q = 0
			}
			c.find("img").remove()
		},
		stopGrid : function() {
			for (var c = 0; c < this.gs.length; c++)
				for (var e = 0; e < this.gs[c].length; e++) {
					this.gs[c][e].clearQueue();
					this.gs[c][e].remove()
				}
			this.gs = []
		},
		flatSort : function(c) {
			for (var e = [], d = 0; d < this.gs.length; d++)
				for (var h = 0; h < this.gs[d].length; h++)
					e.push(this.gs[d][h]);
			c && e.reverse();
			return e
		},
		randomSort : function() {
			var c = this.flatSort();
			i.fn.tn3utils.shuffle(c);
			return c
		},
		diagonalSort : function(c, e) {
			for (var d = [], h = c > 0 ? this.gs.length - 1 : 0, j = e > 0 ? 0 : this.gs[0].length - 1; this.gs[h]; ) {
				d.push(this.addDiagonal([], h, j, c, e));
				h -= c
			}
			h += c;
			for (j += e; this.gs[h][j]; ) {
				d.push(this.addDiagonal([], h, j, c, e));
				j += e
			}
			return d
		},
		addDiagonal : function(c, e, d, h, j) {
			c.push(this.gs[e][d]);
			return this.gs[e + h] && this.gs[e+h][d + j] ? this.addDiagonal(c, e + h, d + j, h, j) : c
		},
		circleSort : function(c) {
			var e = [], d = this.gs.length, h = this.gs[0].length, j = [Math.floor(d / 2), Math.floor(h / 2)];
			d = d * h;
			h = [[1, 0], [0, 1], [-1, 0], [0, -1]];
			var f = 0, n = 0, g;
			for (e.push(this.gs[j[0]][j[1]]); e.length < d; ) {
				for ( g = 0; g <= f; g++)
					this.addGridPiece(e, j, h[n]);
				if (n == h.length - 1)
					n = 0;
				else
					n++;
				f += 0.5
			}
			c && e.reverse();
			return e
		},
		addGridPiece : function(c, e, d) {
			e[0] += d[0];
			e[1] += d[1];
			this.gs[e[0]] && this.gs[e[0]][e[1]] && c.push(this.gs[e[0]][e[1]])
		},
		getSlidePositions : function(c, e) {
			var d = {
				dir : e
			};
			switch(e) {
				case "left":
					d.pos = c.outerWidth(true);
					break;
				case "right":
					d.pos = -c.outerWidth(true);
					d.dir = "left";
					break;
				case "top":
					d.pos = -c.outerHeight(true);
					break;
				case "bottom":
					d.pos = c.outerHeight(true);
					d.dir = "top"
			}
			return d
		},
		animateGrid : function(c, e, d, h, j, f, n) {
			var g = {
				duration : h,
				easing : d,
				complete : function() {
					i(this).remove()
				}
			};
			for ( d = 0; d < c.length; d++) {
				h = i.easing[j](0, d, 0, f, c.length);
				if (d == c.length - 1) {
					var l = this;
					g.complete = function() {
						i(this).remove();
						n.call(l)
					}
				}
				if (i.isArray(c[d]))
					for (var k in c[d])
					c[d][k].delay(h).animate(e[d], g);
				else
					c[d].delay(h).animate(e[d], g)
			}
		},
		getValueArray : function(c, e, d) {
			var h = [], j = i.isArray(e), f = i.isArray(d), n;
			for ( n = 0; n < c; n++) {
				o = {};
				o[ j ? e[n % e.length] : e] = f ? d[n % d.length] : d;
				h.push(o)
			}
			return h
		}
	};
	b.defined = [];
	b.define = function(c) {
		for (var e in c)
		switch(e) {
			case "type":
				b.defined.push(c.type);
				break;
			case "config":
				a[c.type + "Config"] = c.config;
				break;
			case "f":
				a[c.type] = c.f;
				break;
			case "stop":
				a[c.type + "Stop"] = c.stop;
				break;
			case "condition":
				a[c.type + "Condition"] = c.condition;
				break;
			default:
				a[e] = c[e]
		}
	};
	b.define({
		type : "none",
		config : {},
		f : function() {
			this.end()
		},
		stop : function() {
			this.end()
		}
	});
	b.define({
		type : "fade",
		config : {
			duration : 300,
			easing : "easeInQuad"
		},
		f : function(c, e, d) {
			var h = this;
			c.animate({
				opacity : 0
			}, d.duration, d.easing, function() {
				h.end()
			})
		},
		stop : function(c) {
			c.stop();
			this.end()
		}
	});
	b.define({
		type : "slide",
		config : {
			duration : 300,
			direction : "auto",
			easing : "easeInOutCirc"
		},
		f : function(c, e, d, h) {
			h = this.getSlidePositions(e, d.direction == "auto" ? h : d.direction);
			var j = {}, f = {};
			e.css(h.dir, h.pos);
			j[h.dir] = 0;
			e.animate(j, d.duration, d.easing, this.end);
			f[h.dir] = -h.pos;
			c.animate(f, d.duration, d.easing)
		},
		stop : function(c, e) {
			e.stop();
			c.stop();
			c.css("left", 0).css("top", 0);
			e.css("left", 0).css("top", 0);
			this.end()
		}
	});
	b.define({
		type : "blinds",
		config : {
			duration : 240,
			easing : "easeInQuad",
			direction : "vertical",
			parts : 12,
			partDuration : 100,
			partEasing : "easeInQuad",
			method : "fade",
			partDirection : "auto",
			cross : true
		},
		f : function(c, e, d, h) {
			d.direction == "horizontal" ? this.makeGrid(c, 1, d.parts) : this.makeGrid(c, d.parts, 1);
			h = d.partDirection == "auto" ? h : d.partDirection;
			c = this.flatSort(h == "left" || h == "top");
			var j;
			switch(d.method) {
				case "fade":
					j = this.getValueArray(c.length, "opacity", 0);
					break;
				case "scale":
					j = this.getValueArray(c.length, h == "left" ? "width" : "height", "1px");
					break;
				case "slide":
					e = this.getSlidePositions(e, h);
					j = this.getValueArray(c.length, e.dir, d.cross ? [e.pos, -e.pos] : e.pos)
			}
			this.animateGrid(c, j, d.partEasing, d.partDuration, d.easing, d.duration, this.blindsStop)
		},
		stop : function() {
			this.stopGrid();
			this.end()
		},
		condition : function(c, e) {
			return !c.data("scaled") || !e.data("scaled")
		}
	});
	b.define({
		type : "grid",
		config : {
			duration : 260,
			easing : "easeInQuad",
			gridX : 7,
			gridY : 5,
			sort : "diagonal",
			sortReverse : false,
			diagonalStart : "bl",
			method : "fade",
			partDuration : 300,
			partEasing : "easeOutSine",
			partDirection : "left"
		},
		f : function(c, e, d, h) {
			this.makeGrid(c, d.gridX, d.gridY);
			c = d.partDirection == "auto" ? h : d.partDirection;
			var j, f;
			if (d.sort == "diagonal")
				switch(d.diagonalStart) {
					case "tr":
						j = this.diagonalSort(1, 1);
						break;
					case "tl":
						j = this.diagonalSort(-1, 1);
						break;
					case "br":
						j = this.diagonalSort(1, -1);
						break;
					case "bl":
						j = this.diagonalSort(-1, -1)
				}
			else
				j = this[d.sort+"Sort"](d.sortReverse);
			switch(d.method) {
				case "fade":
					f = this.getValueArray(j.length, "opacity", 0);
					break;
				case "scale":
					f = this.getValueArray(j.length, c == "left" ? "width" : "height", "1px")
			}
			this.animateGrid(j, f, d.partEasing, d.partDuration, d.easing, d.duration, this.gridStop)
		},
		stop : function() {
			this.stopGrid();
			this.end()
		},
		condition : function(c, e) {
			return !c.data("scaled") || !e.data("scaled")
		}
	})
})(jQuery);

(function(i){function b(g){var l=g&&g.message!==undefined?g.message:undefined;g=i.extend({},i.tn3block.defaults,g||{});l=l===undefined?g.message:l;n&&a({});var k=g.baseZ,m=h||g.forceIframe?i('<iframe class="blockUI" style="z-index:'+k++ +';display:none;border:none;margin:0;padding:0;position:absolute;width:100%;height:100%;top:0;left:0" src="'+g.iframeSrc+'"></iframe>'):i('<div class="blockUI" style="display:none"></div>'),t=i('<div class="blockUI '+g.cssID+'-overlay" style="z-index:'+k++ +';display:none;border:none;margin:0;padding:0;width:100%;height:100%;top:0;left:0"></div>');
k=i('<div class="blockUI '+g.blockMsgClass+' blockPage" style="z-index:'+k+';display:none;position:fixed"></div>');k.css("left","0px").css("top","0px");t.css(g.overlayCSS);t.css("position","fixed");if(h||g.forceIframe)m.css("opacity",0);var u=[m,t,k],q=i("body");i.each(u,function(){this.appendTo(q)});u=f&&(!i.boxModel||i("object,embed",null).length>0);if(j||u){g.allowBodyStretch&&i.boxModel&&i("html,body").css("height","100%");i.each([m,t,k],function(p,r){var s=r[0].style;s.position="absolute";if(p<
2){s.setExpression("height","Math.max(document.body.scrollHeight, document.body.offsetHeight)- (jQuery.boxModel?0:"+g.quirksmodeOffsetHack+') + "px"');s.setExpression("width",'jQuery.boxModel && document.documentElement.clientWidth || document.body.clientWidth + "px"')}else if(g.centerY){s.setExpression("top",'(document.documentElement.clientHeight || document.body.clientHeight) / 2- (this.offsetHeight / 2)+ (blah = document.documentElement.scrollTop? document.documentElement.scrollTop: document.body.scrollTop)+ "px"');
s.marginTop=0}else g.centerY||s.setExpression("top",'(document.documentElement.scrollTop ? document.documentElement.scrollTop : document.body.scrollTop) + "px"')})}if(l){l.data("blockUI.parent",l.parent());k.append(l);if(l.jquery||l.nodeType)i(l).show()}if((h||g.forceIframe)&&g.showOverlay)m.show();g.showOverlay&&t.show();l&&k.show();g.onBlock&&g.onBlock();e(1,g);n=l}function a(g){g=i.extend({},i.tn3block.defaults,g||{});e(0,g);var l=i("body").children().filter(".blockUI").add("body > .blockUI");
c(l,g)}function c(g,l){g.each(function(){this.parentNode&&this.parentNode.removeChild(this)});n.data("blockUI.parent").append(n);n=null;typeof l.onUnblock=="function"&&l.onUnblock.call(l.con)}function e(g,l){if(g||n)!l.bindEvents||g&&!l.showOverlay||(g?i(document).bind("mousedown mouseup keydown keypress",l,d):i(document).unbind("mousedown mouseup keydown keypress",d))}function d(g){var l=g.data;if(i(g.target).parents("div."+l.blockMsgClass).length>0)return true;return i(g.target).parents().children().filter("div.blockUI").length==
0}var h=/MSIE/.test(navigator.userAgent),j=/MSIE 6.0/.test(navigator.userAgent),f=i.isFunction(document.createElement("div").style.setExpression);i.tn3block=function(g){b(g)};i.tn3unblock=function(g){a(g)};var n=undefined;i.tn3block.defaults={message:"<h1>Please wait...</h1>",overlayCSS:{},iframeSrc:/^https/i.test(window.location.href||"")?"javascript:false":"about:blank",forceIframe:false,baseZ:2147483647,allowBodyStretch:true,bindEvents:true,showOverlay:true,applyPlatformOpacityRules:true,onBlock:null,
onUnblock:null,quirksmodeOffsetHack:4,blockMsgClass:"blockMsg",cssID:"tn3"}})(jQuery);

(function(i) {
	(i.fn.tn3.External = function(b, a) {
		if (b) {
			this.context = a;
			this.reqs = b.length;
			for (var c = 0; c < b.length; c++)
				this.plugs[b[c].origin] = new i.fn.tn3.External[b[c].origin](b[c], this)
		}
	}).prototype = {
		context : null,
		reqs : 0,
		plugs : {},
		reset : function(b, a) {
			var c = this;
			a.callback = function(e) {
				c.context.data = e;
				c.context.showAlbum(0)
			};
			this.plugs[b].config = i.extend(true, {}, this.plugs[b].config, a);
			this.reqs++;
			this.plugs[b].init()
		},
		getImages : function(b, a) {
			this.plugs[b.origin].getImages(b, a)
		},
		setAlbumData : function(b, a) {
			this.reqs--;
			this.context.setAlbumData.call(this.context, b, a)
		},
		setImageData : function(b, a, c) {
			this.context.setImageData.call(this.context, b, a, c)
		},
		getAlbumData : function(b) {
			return this.context.data[b]
		}
	}
})(jQuery);

