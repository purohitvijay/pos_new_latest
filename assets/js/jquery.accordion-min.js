(function(a) {
    var b = function(b, c) {
        var d = {
                type: "vertical",
                cssAttrsVer: {
                    ulWidth: 700,
                    liHeight: 40
                },
                cssAttrsHor: {
                    ulWidth: 700,
                    liWidth: 40,
                    liHeight: 300,
                    responsiveMedia: !1
                },
                startSlide: 1,
                openCloseHelper: {
                    numeric: !1,
                    openIcon: !1,
                    closeIcon: !1
                },
                openOnebyOne: !0,
                classTab: "active",
                slideOn: "click",
                autoPlay: !1,
                autoPlaySpeed: 2e3
            },
            e = a.extend(!0, {}, d, c),
            f = parseInt(a(b.parent()).css("width"), 10),
            g = a(b).html(),
            h = b.children().children(),
            i = h.length,
            j = "",
            k = parseFloat(navigator.appVersion.split("MSIE")[1]),
            l = {
                play: function() {
                    var b;
                    b = e.startSlide && e.startSlide !== i ? e.startSlide : 0, j = setInterval(function() {
                        if (i > b) {
                            var c = b++;
                            a(h[c]).trigger("click"), b === i && (c = 0, b = 0)
                        }
                    }, e.autoPlaySpeed)
                },
                stop: function() {
                    window.clearInterval(j)
                },
                calcBordWidthHor: function() {
                    var b, c;
                    return b = isNaN(parseInt(a(h).parent().find("li").css("border-right-width"), 10)) ? 0 : parseInt(a(h).parent().find("li").css("border-right-width"), 10), c = isNaN(parseInt(a(h).parent().find("li").css("border-left-width"), 10)) ? 0 : parseInt(a(h).parent().find("li").css("border-left-width"), 10), b + c
                },
                calcDivWidthHor: function() {
                    return "number" === a.type(e.cssAttrsHor.ulWidth) ? e.cssAttrsHor.ulWidth - a(h).parent().find("li").length * (e.cssAttrsHor.liWidth + l.calcBordWidthHor()) : f - a(h).parent().find("li").length * (e.cssAttrsHor.liWidth + l.calcBordWidthHor())
                },
                matchQueryMedia: function() {
                    window.matchMedia = window.matchMedia || function(a) {
                        var g, h, i, j, c = a.documentElement,
                            d = c.firstElementChild || c.firstChild,
                            e = a.createElement("body"),
                            f = a.createElement("div");
                        return f.id = "mq-test-1", f.style.cssText = "position:absolute;top:-100em", e.style.background = "none", e.appendChild(f), g = function(a) {
                                var b;
                                return f.innerHTML = '&shy;<style media="' + a + '"> #mq-test-1 { width: 42px; }</style>', c.insertBefore(e, d), b = 42 === f.offsetWidth, c.removeChild(e), {
                                    matches: b,
                                    media: a
                                }
                            }, h = function() {
                                var b, d = c.body,
                                    e = !1;
                                return f.style.cssText = "position:absolute;font-size:1em;width:1em", d || (d = e = a.createElement("body"), d.style.background = "none"), d.appendChild(f), c.insertBefore(d, c.firstChild), e ? c.removeChild(d) : d.removeChild(f), b = j = parseFloat(f.offsetWidth)
                            }, i = g("(min-width: 0px)").matches,
                            function(b) {
                                if (i) return g(b);
                                var m, c = b.match(/\(min\-width:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/) && parseFloat(RegExp.$1) + (RegExp.$2 || ""),
                                    d = b.match(/\(max\-width:[\s]*([\s]*[0-9\.]+)(px|em)[\s]*\)/) && parseFloat(RegExp.$1) + (RegExp.$2 || ""),
                                    e = null === c,
                                    f = null === d,
                                    k = a.body.offsetWidth,
                                    l = "em";
                                return c && (c = parseFloat(c) * (c.indexOf(l) > -1 ? j || h() : 1)), d && (d = parseFloat(d) * (d.indexOf(l) > -1 ? j || h() : 1)), m = (!e || !f) && (e || k >= c) && (f || d >= k), {
                                    matches: m,
                                    media: b
                                }
                            }
                    }(document)
                },
                responsiveAction: function(c) {
                    c.matches ? (a(b).empty().removeAttr("class").addClass("accordion-ver").html(g), a(b).awsAccordion({
                        type: "vertical",
                        cssAttrsVer: {
                            ulWidth: "responsive",
                            liHeight: e.cssAttrsVer.liHeight
                        },
                        startSlide: e.startSlide,
                        openCloseHelper: {
                            numeric: e.openCloseHelper.numeric,
                            openIcon: e.openCloseHelper.openIcon,
                            closeIcon: e.openCloseHelper.closeIcon
                        },
                        openOnebyOne: !0,
                        classTab: e.classTab,
                        slideOn: e.slideOn,
                        autoPlay: e.autoPlay,
                        autoPlaySpeed: e.autoPlaySpeed
                    })) : (a(b).empty().removeAttr("class").addClass("accordion-hor").html(g), a(b).awsAccordion({
                        type: "horizontal",
                        cssAttrsVer: {
                            ulWidth: "responsive",
                            liHeight: e.cssAttrsVer.liHeight
                        },
                        cssAttrsHor: {
                            ulWidth: "responsive",
                            liWidth: e.cssAttrsHor.liWidth,
                            liHeight: e.cssAttrsHor.liHeight,
                            responsiveMedia: e.cssAttrsHor.responsiveMedia
                        },
                        startSlide: e.startSlide,
                        openCloseHelper: {
                            numeric: e.openCloseHelper.numeric,
                            openIcon: e.openCloseHelper.openIcon,
                            closeIcon: e.openCloseHelper.closeIcon
                        },
                        classTab: e.classTab,
                        slideOn: e.slideOn,
                        autoPlay: e.autoPlay,
                        autoPlaySpeed: e.autoPlaySpeed
                    }))
                }
            },
            m = {
                bindEvents: function() {
                    h.on(e.slideOn, m.slideContent), h.each(m.resetSlideContent), m.setAttrs(), e.autoPlay && l.play()
                },
                setAttrs: function() {
                    var b, c, d, g, j, n, o, p, q, r;
                    if ("vertical" === e.type && ("number" === a.type(e.cssAttrsVer.ulWidth) ? a(h).parent().css({
                            width: e.cssAttrsVer.ulWidth + "px"
                        }) : a(h).parent().css({
                            width: "100%"
                        }), e.cssAttrsVer.liHeight && a(h).css({
                            "padding-top": e.cssAttrsVer.liHeight + "px"
                        }), (7 === k || 8 === k) && a("body").css({
                            height: "auto",
                            "overflow-y": "scroll",
                            "overflow-x": "hidden"
                        })), "horizontal" === e.type)
                        for ((7 === k || 8 === k) && a("body").removeAttr("style"), "number" === a.type(e.cssAttrsHor.ulWidth) ? a(h).parent().css({
                                width: e.cssAttrsHor.ulWidth + "px"
                            }) : (l.matchQueryMedia(), c = window.matchMedia(e.cssAttrsHor.responsiveMedia), c.matches ? l.responsiveAction(c) : a(h).parent().css({
                                width: f + "px"
                            }), 7 === k || 8 === k ? a(window).resize(function() {
                                q = a(window).height(), r = a(window).width(), (void 0 === o || o !== q || void 0 === p || p !== r) && (o = q, p = r, l.matchQueryMedia(), c = window.matchMedia(e.cssAttrsHor.responsiveMedia), l.responsiveAction(c))
                            }) : window.onresize = function() {
                                l.matchQueryMedia(), c = window.matchMedia(e.cssAttrsHor.responsiveMedia), l.responsiveAction(c)
                            }), b = 0; a(h).parent().find("li").length > b; b++) a(h).parent().find("li").eq(b).css({
                            width: e.cssAttrsHor.liWidth + "px",
                            height: e.cssAttrsHor.liHeight + "px"
                        }).find("div").css({
                            left: e.cssAttrsHor.liWidth + "px",
                            width: l.calcDivWidthHor() + "px",
                            height: e.cssAttrsHor.liHeight + "px"
                        });
                    if (e.openCloseHelper) {
                        if (e.openCloseHelper.numeric)
                            for (b = 0; i >= b; b++) a(h[b]).append('<p class="numericTab tab' + [b + 1] + '">' + [b + 1] + "</p>");
                        else if (e.openCloseHelper.openIcon)
                            for (b = 0; i >= b; b++) a(h[b]).append('<i class="icon-' + e.openCloseHelper.openIcon + '"></i>');
                        d = parseInt(a(h).find("p.numericTab").css("height"), 10) / 2, g = parseInt(a(h).find("i").css("height"), 10) / 2, j = parseInt(a(h).find("h3").css("height"), 10) / 2, n = parseInt(a(h).find("h3").css("width"), 10) / 2, "horizontal" === e.type ? (h.find("h3").length > 0 && (7 === k || 8 === k || 9 === k ? a(h).find("h3").css({
                            top: "0px",
                            width: "auto",
                            left: e.cssAttrsHor.liWidth / 2 - n + "px"
                        }) : a(h).find("h3").css("left", e.cssAttrsHor.liWidth / 2 - j + "px")), e.openCloseHelper && (e.openCloseHelper.numeric ? a(h).find("p.numericTab").css("width", e.cssAttrsHor.liWidth + "px") : e.openCloseHelper.openIcon && a(h).find("i").css("width", e.cssAttrsHor.liWidth + "px"))) : "vertical" === e.type && (h.find("h3").length > 0 && a(h).find("h3").css("padding-top", e.cssAttrsVer.liHeight / 2 - j + "px"), e.openCloseHelper && (e.openCloseHelper.numeric ? a(h).find("p.numericTab").css("padding-top", e.cssAttrsVer.liHeight / 2 - d + "px") : e.openCloseHelper.openIcon && a(h).find("i").css("padding-top", e.cssAttrsVer.liHeight / 2 - g + "px")))
                    }
                    e.startSlide && ("vertical" === e.type ? m.animateSlideDown(h[e.startSlide - 1]) : m.animateSlideRight(h[e.startSlide - 1]), e.openCloseHelper.closeIcon && a(h[e.startSlide - 1]).find("i").attr("class", "icon-" + e.openCloseHelper.closeIcon), e.classTab && a(h[e.startSlide - 1]).addClass(e.classTab)), a(h).last().addClass("last")
                },
                slideContent: function(b) {
                    if (a(b.target).is("div") || a(b.target).parent().is("div")) return !1;
                    var c = a(b.currentTarget),
                        d = a(c).parent().find("div:visible");
                    e.autoPlay && !b.isTrigger && l.stop(), e.openOnebyOne && d.length > 0 && "vertical" === e.type && m.animateSlideUp(d), "vertical" === e.type ? a(c).find("div").is(":visible") ? m.animateSlideUp(c) : m.animateSlideDown(c) : a(c).find("div").is(":hidden") && (m.animateSlideRight(c), m.animateSlideLeft(d))
                },
                animateSlideUp: function(b) {
                    e.openOnebyOne ? a(b).parent().hasClass("last") ? a(b).parent().removeClass(e.classTab).find("div").slideUp("fast") : a(b).parent().removeAttr("class").find("div").slideUp("fast") : a(b).hasClass("last") ? a(b).removeClass(e.classTab).find("div").slideUp("fast") : a(b).removeAttr("class").find("div").slideUp("fast"), e.openCloseHelper.openIcon && a(b).parent().find("i").attr("class", "icon-" + e.openCloseHelper.openIcon)
                },
                animateSlideDown: function(b) {
                    a(b).parent().find("." + e.classTab).removeAttr("class"), e.openCloseHelper.closeIcon && (a(b).find("i").length > 0 ? a(b).find("i").attr("class", "icon-" + e.openCloseHelper.closeIcon) : a(b).parent().find("i").attr("class", "icon-" + e.openCloseHelper.closeIcon)), a(b).addClass(e.classTab).find("div").slideDown("fast")
                },
                animateSlideRight: function(b) {
                    a(b).parent().find("." + e.classTab).removeClass(e.classTab), e.openCloseHelper.closeIcon && (a(b).find("i").length > 0 ? a(b).find("i").attr("class", "icon-" + e.openCloseHelper.closeIcon) : a(b).parent().find("i").attr("class", "icon-" + e.openCloseHelper.closeIcon)), a(b).addClass(e.classTab).animate({
                        width: l.calcDivWidthHor() + e.cssAttrsHor.liWidth + "px"
                    }, "fast"), a(b).find("div").css("display", "block")
                },
                animateSlideLeft: function(b) {
                    a(b).parent().find("div").animate({
                        width: "0px"
                    }, "fast", function() {
                        a(this).css({
                            display: "none",
                            width: l.calcDivWidthHor() + "px"
                        })
                    }), a(b).parent().hasClass("last") ? a(b).parent().removeClass(e.classTab).animate({
                        width: e.cssAttrsHor.liWidth + "px"
                    }, "fast") : a(b).parent().removeAttr("class").animate({
                        width: e.cssAttrsHor.liWidth + "px"
                    }, "fast"), e.openCloseHelper.openIcon && a(b).parent().find("i").attr("class", "icon-" + e.openCloseHelper.openIcon)
                },
                resetSlideContent: function() {
                    h.find("div").hide()
                },
                init: function() {
                    "vertical" === e.type ? a(b).addClass("accordion-ver") : a(b).addClass("accordion-hor"), m.bindEvents()
                }
            };
        return m.init(), l
    };
    a.fn.awsAccordion = function(a) {
        var c = this;
        return c.each(function() {
            var d;
            d = new b(c, a), c.data("awsAccordion", d)
        })
    }
})(jQuery);