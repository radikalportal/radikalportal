/* Galleria Azur Theme 2013-01-10 | http://galleria.io/license/ | (c) Aino */
(function (a) {
    Galleria.addTheme({
        name: "azur",
        author: "Galleria",
        css: "galleria.azur.css",
        defaults: {
            transition: "fade",
            transitionSpeed: 500,
            imageCrop: !1,
            thumbCrop: "height",
            idleMode: "hover",
            idleSpeed: 500,
            fullscreenTransition: !1,
            _locale: {
                show_captions: "Show captions",
                hide_captions: "Hide captions",
                play: "Play slideshow",
                pause: "Pause slideshow",
                enter_fullscreen: "Enter fullscreen",
                exit_fullscreen: "Exit fullscreen",
                next: "Next image",
                prev: "Previous image",
                showing_image: "Showing image %s of %s"
            },
            _toggleCaption: !0,
            _showCaption: !0,
            _showTooltip: !0
        },
        init: function (b) {
            Galleria.requires(1.28, "This version of Azur theme requires Galleria version 1.2.8 or later"), this.addElement("bar", "fullscreen", "play", "progress").append({
                stage: "progress",
                container: "bar",
                bar: ["fullscreen", "play", "thumbnails-container"]
            }).prependChild("stage", "info").appendChild("container", "tooltip");
            var c = this,
                d = window.document,
                e = b._locale,
                f = "getContext" in d.createElement("canvas");
            (function () {
                if (!f) {
                    c.addElement("progressbar").appendChild("progress", "progressbar"), c.$("progress").addClass("nocanvas");
                    var b = c.$("progress").width();
                    c.bind("progress", function (a) {
                        c.$("progressbar").width(a.percent / 100 * b)
                    });
                    return
                }
                var e = 24,
                    g = d.createElement("canvas"),
                    h = g.getContext("2d"),
                    i = function (a) {
                        return a * (Math.PI / 180)
                    }, j = function (a, b) {
                        h.strokeStyle = b || "#000", h.lineWidth = 3, h.clearRect(0, 0, e, e), h.beginPath(), h.arc(e / 2, e / 2, e / 2 - 2, i(-90), i(a - 90), !1), h.stroke(), h.closePath()
                    };
                g.width = e, g.height = e, a(g).css({
                    zIndex: 1e4,
                    position: "absolute",
                    right: 10,
                    top: 10
                }).appendTo(c.get("container")), c.bind("progress", function (b) {
                    a(g).fadeIn(200), j(b.percent * 3.6, "rgba(255,255,255,.7)")
                }), c.bind("pause", function () {
                    a(g).fadeOut(200, function () {
                        h.clearRect(0, 0, e, e)
                    })
                })
            })(),
            function () {
                if (!f) {
                    c.$("loader").addClass("nocanvas");
                    return
                }
                var b = d.createElement("canvas"),
                    e = b.getContext("2d"),
                    g = Math,
                    h = function (a, b, c) {
                        var d = c ? -2 : 2;
                        a.translate(b / d, b / d)
                    }, i = 28;
                a(b).hide().appendTo(c.get("loader")).fadeIn(500);
                var j = function (a, b) {
                    var c = 48,
                        d = 28,
                        e;
                    a.clearRect(0, 0, c, c), a.lineWidth = 1.5;
                    for (var f = 0; f < d; f++) e = f + b >= d ? f - d + b : f + b, a.strokeStyle = "rgba(255,255,255," + g.max(0, e / d) + ")", a.beginPath(), a.moveTo(c / 2, c / 2 - 16), a.lineTo(c / 2, 0), a.stroke(1), h(a, c, !1), a.rotate(360 / d * g.PI / 180), h(a, c, !0);
                    a.save(), h(a, c, !1), a.rotate(-1 * (360 / d / 8) * g.PI / 180), h(a, c, !0)
                };
                window.setInterval(function () {
                    j(e, i), i = i === 0 ? 28 : i - 1
                }, 20)
            }();
            var g = Galleria.IE < 9 ? {
                bottom: -100
            } : {
                bottom: -50,
                opacity: 0
            }, h = Galleria.IE < 9 ? {
                top: -20
            } : {
                opacity: 0,
                top: -20
            };
            this.bind("play", function () {
                this.$("play").addClass("pause"), f || this.$("progress").show()
            }).bind("pause", function () {
                this.$("play").removeClass("pause"), f || this.$("progress").hide()
            }).bind("loadstart", function (a) {
                a.cached || this.$("loader").show()
            }).bind("loadfinish", function (a) {
                f ? this.$("loader").fadeOut(100) : this.$("loader").hide()
            }), this.addIdleState(this.get("info"), g, Galleria.IE < 9 ? {} : {
                opacity: 1
            }, !0).addIdleState(this.get("image-nav-left"), {
                opacity: 0,
                left: 0
            }, {
                opacity: 1
            }, !0).addIdleState(this.get("image-nav-right"), {
                opacity: 0,
                right: 0
            }, {
                opacity: 1
            }, !0).addIdleState(this.get("counter"), h, Galleria.IE < 9 ? {} : {
                opacity: .9
            }, !0), this.$("fullscreen").click(function (a) {
                a.preventDefault(), c.toggleFullscreen()
            }), this.$("play").click(function (a) {
                a.preventDefault(), c.playToggle()
            }), b._toggleCaption && (this.$("info").addClass("toggler"), this.addElement("captionopen").appendChild("stage", "captionopen"), this.addElement("captionclose").appendChild("info", "captionclose"), this.$("captionopen").click(function () {
                c.$("info").addClass("open"), a(this).hide()
            }).html(e.show_captions), this.bind("loadstart", function () {
                this.$("captionopen").toggle(!c.$("info").hasClass("open") && this.hasInfo())
            }), this.$("captionclose").click(function () {
                c.$("info").removeClass("open"), c.hasInfo() && c.$("captionopen").show()
            }).html("&#215;"), b._showCaption && this.$("captionopen").click()), b._showTooltip && this.bindTooltip({
                fullscreen: function () {
                    return c.isFullscreen() ? e.exit_fullscreen : e.enter_fullscreen
                },
                play: function () {
                    return c.isPlaying() ? e.pause : e.play
                },
                captionclose: e.hide_captions,
                "image-nav-right": e.next,
                "image-nav-left": e.prev,
                counter: function () {
                    return e.showing_image.replace(/\%s/, c.getIndex() + 1).replace(/\%s/, c.getDataLength())
                }
            })
        }
    })
})(jQuery);