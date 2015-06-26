/* Galleria Folio Theme 2013-01-08 | http://galleria.io/license/ | (c) Aino */
(function (a) {
    Galleria.addTheme({
        name: "folio",
        author: "Galleria",
        css: "galleria.folio.css",
        defaults: {
            transition: "pulse",
            thumbCrop: "width",
            imageCrop: !1,
            carousel: !1,
            show: !1,
            easing: "galleriaOut",
            fullscreenDoubleTap: !1,
            trueFullscreen: !1,
            _webkitCursor: !1,
            _animate: !0
        },
        init: function (options) {
            Galleria.requires(1.28, "This version of Folio theme requires Galleria version 1.2.8 or later"), this.addElement("preloader", "loaded", "close").append({
                container: "preloader",
                preloader: "loaded",
                stage: "close"
            });
            var c = this,
                stage = this.$("stage"),
                thumbnails = this.$("thumbnails"),
                images = this.$("images"),
                info = this.$("info"),
                loader = this.$("loader"),
                el = this.$("target"),
                j = 0,
                k = el.width(),
                l = 0,
                show = options.show,
                hash = window.location.hash.substr(2),
                o = function (options) {
                    c.$("info").css({
                        left: Math.max(20, a(window).width() / 2 - options / 2 + 10),
                        marginBottom: c.getData().video ? 40 : 0
                    })
                }, p = function (a) {
                    return Math.min.apply(window, a)
                }, q = function (a) {
                    return Math.max.apply(window, a)
                }, r = function (options, c) {
                    c = a.extend({
                        speed: 400,
                        width: 190,
                        onbrick: function () {},
                        onheight: function () {},
                        delay: 0,
                        debug: !1
                    }, c), options = a(options);
                    var stage = options.children(),
                        thumbnails = options.width(),
                        images = Math.floor(thumbnails / c.width),
                        info = [],
                        loader, el, j, k, l = {
                            "float": "none",
                            position: "absolute",
                            display: Galleria.SAFARI ? "inline-block" : "block"
                        };
                    if (options.data("colCount") === images) return;
                    options.data("colCount", images);
                    if (!stage.length) return;
                    for (loader = 0; loader < images; loader++) info[loader] = 0;
                    options.css("position", "relative"), stage.css(l).each(function (options, stage) {
                        stage = a(stage);
                        for (loader = images - 1; loader > -1; loader--) info[loader] === p(info) && (el = loader);
                        j = {
                            top: info[el],
                            left: c.width * el
                        };
                        if (typeof j.top != "number" || typeof j.left != "number") return;
                        c.speed ? window.setTimeout(function (a, options, c) {
                            return function (stage) {
                                Galleria.utils.animate(a, c, {
                                    easing: "galleriaOut",
                                    duration: options.speed,
                                    complete: options.onbrick
                                })
                            }
                        }(stage, c, j), options * c.delay) : (stage.css(j), c.onbrick.call(stage)), stage.data("height") || stage.data("height", stage.outerHeight(!0)), info[el] += stage.data("height")
                    }), k = q(info);
                    if (k < 0) return;
                    if (typeof k != "number") return;
                    c.speed ? options.animate({
                        height: q(info)
                    }, c.speed, c.onheight) : (options.height(q(info)), c.onheight.call(options))
                };
            this.bind("fullscreen_enter", function (a) {
                this.$("container").css("height", "100%")
            }), this.bind("fullscreen_exit", function (options) {
                this.getData().iframe && (a(this._controls.getActive().container).find("iframe").remove(), this.$("container").removeClass("iframe")), info.hide(), a(c._controls.getActive().container).hide(), thumbnails.css("left", 0), stage.css("left", -1e4)
            }), this.bind("loadstart", function (a) {
                Galleria.TOUCH && this.$("image-nav").toggle( !! a.galleriaData.iframe)
            }), this.bind("thumbnail", function (images) {
                this.addElement("plus");
                var info = images.thumbTarget,
                    loader = this.$("plus").css({
                        display: "block"
                    }).insertAfter(info),
                    k = a(info).parent().data("index", images.index);
                options.showInfo && this.hasInfo(images.index) && loader.append("<span>" + this.getData(images.index).title + "</span>"), l = l || a(info).parent().outerWidth(!0), a(info).css("opacity", 0), k.unbind(options.thumbEventType), Galleria.IE ? loader.hide() : loader.css("opacity", 0), Galleria.TOUCH ? k.bind("touchstart", function () {
                    loader.css("opacity", 1)
                }).bind("touchend", function () {
                    loader.hide()
                }) : k.hover(function () {
                    Galleria.IE ? loader.show() : loader.stop().css("opacity", 1)
                }, function () {
                    Galleria.IE ? loader.hide() : loader.stop().animate({
                        opacity: 0
                    }, 300)
                }), j++, this.$("loaded").css("width", j / this.getDataLength() * 100 + "%"), j === this.getDataLength() && (this.$("preloader").fadeOut(100), thumbnails.data("colCount", null), r(thumbnails, {
                    width: l,
                    speed: options._animate ? 400 : 0,
                    onbrick: function () {
                        var images = this,
                            info = a(images).find("img");
                        window.setTimeout(function (images) {
                            return function () {
                                Galleria.utils.animate(images, {
                                    opacity: 1
                                }, {
                                    duration: options.transition_speed
                                }), images.parent().unbind("mouseup click").bind(Galleria.TOUCH ? "mouseup" : "click", function () {
                                    thumbnails.css("left", -1e4), stage.css("left", 0);
                                    var options = a(this).data("index");
                                    c.enterFullscreen(function () {
                                        c.show(options)
                                    })
                                })
                            }
                        }(info), options._animate ? info.parent().data("index") * 100 : 0)
                    },
                    onheight: function () {
                        el.height(thumbnails.height())
                    }
                }))
            }), this.bind("loadstart", function (a) {
                a.cached || loader.show()
            }), this.bind("data", function () {
                j = 0
            }), this.bind("loadfinish", function (c) {
                loader.hide(), info.hide(), this.hasInfo() && options.showInfo && this.isFullscreen() && info.fadeIn(options.transition ? options.transitionSpeed : 0), o(a(c.imageTarget).width())
            }), !Galleria.TOUCH && !options._webkitCursor && (this.addIdleState(this.get("image-nav-left"), {
                left: -100
            }), this.addIdleState(this.get("image-nav-right"), {
                right: -100
            }), this.addIdleState(this.get("info"), {
                opacity: 0
            })), this.$("container").css({
                width: options.width,
                height: "auto"
            }), options._webkitCursor && Galleria.WEBKIT && !Galleria.TOUCH && this.$("image-nav-right,image-nav-left").addClass("cur"), Galleria.TOUCH && this.setOptions({
                transition: "fadeslide",
                initialTransition: !1
            }), this.$("close").click(function () {
                c.exitFullscreen()
            }), Galleria.History && hash && (stage.css("left", 0), thumbnails.css("left", -1e4), this.$("preloader").hide(), this.enterFullscreen(function () {
                this.show(parseInt(hash, 10))
            })), a(window).resize(function () {
                if (c.isFullscreen()) {
                    c.getActiveImage() && o(c.getActiveImage().width);
                    return
                }
                var a = el.width();
                a !== k && (k = a, r(thumbnails, {
                    width: l,
                    delay: 50,
                    debug: !0,
                    onheight: function () {
                        el.height(thumbnails.height())
                    }
                }))
            })
        }
    })
})(jQuery);