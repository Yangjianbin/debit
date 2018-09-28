define("publicHeaderNav", ["jquery"], function (a) {
    var b = {
        itHover: function () {
            var b = a(".public_headernav_module .nav li");
            b.bind("mouseenter", function () {
                clearTimeout(c), a(this).hasClass("chubannav") || a(this).hasClass("yuanchuangnav") ? (a(".chuban").css({display: "none"}), a(".yuanchuang").css({display: "none"}), a(this).hasClass("chubannav") ? (a(".chuban").css({display: "block"}), a(".public_child_nav").css({display: "block"})) : a(this).hasClass("yuanchuangnav") && (a(".yuanchuang").css({display: "block"}), a(".public_child_nav").css({display: "block"}))) : (a(".chuban").css({display: "none"}), a(".yuanchuang").css({display: "none"}), a(".public_child_nav").css({display: "none"}))
            });
            var c = null;
            b.bind("mouseleave", function () {
                c = setTimeout(function () {
                    a(".chuban").css({display: "none"}), a(".yuanchuang").css({display: "none"}), a(".public_child_nav").css({display: "none"})
                }, 1e3)
            });
            var d = a(".public_headerchildnav_module");
            d.bind("mouseenter", function () {
                clearTimeout(c)
            }), d.bind("mouseleave", function () {
                a(".chuban").css({display: "none"}), a(".yuanchuang").css({display: "none"}), a(".public_child_nav").css({display: "none"})
            })
        }
    };
    return b
}), define("publicHeaderChildnavModule", ["jquery"], function (a) {
    var b = {
        itHover: function () {
            a(".yuanchuang .inner a").bind("mouseenter", function () {
                a(".yuanchuang .inner a i").removeClass("on"), a(this).find("i").addClass("on")
            })
        }
    };
    return b
}), define("publicSideCodeModule", ["jquery"], function (a) {
    var b = {
        toTop: function () {
            a(".public_totop_module").on("click", function () {
                a(window).scrollTop(0)
            }), a(window).bind("scroll", function () {
                0 === a(window).scrollTop() ? a(".public_totop_module").hide() : a(".public_totop_module").show()
            })
        }, closeCode: function () {
            a(".public_sideecode_module .close").on("click", function () {
                a(".public_sideecode_module").css({display: "none"})
            })
        }
    };
    return b
}), define("publicHeadersearch", ["jquery"], function (a) {
    var b = {
        getCursor: function () {
            a(".searchtext").on("focus", function () {
                "作品、作者、出版社" === a(this).val() && a(this).val("")
            }), a(".searchtext").on("blur", function () {
                "" === a(this).val() && a(this).val("作品、作者、出版社")
            })
        }, searchBtn: function () {
            a(".searchbtn").on("click", function () {
                "" === a(".searchtext").val() || "作品、作者、出版社" === a(".searchtext").val() || (window.location = "http://e.dangdang.com/searchresult_page.html?keyword=" + a(".searchtext").val())
            }), document.onkeydown = function (b) {
                var c = b || window.event || arguments.callee.caller.arguments[0];
                c && 13 == c.keyCode && ("" === a(".searchtext").val() || "作品、作者、出版社" === a(".searchtext").val() || (window.location = "http://e.dangdang.com/searchresult_page.html?keyword=" + a(".searchtext").val()))
            }
        }
    };
    return b
}), define("ddbase", ["jquery", "underscore"], function (a, b) {
    var c = c || {};
    return c.header_trim = function (a) {
        return a.replace(/(\s*$)|(^\s*)/g, "")
    }, c.getCookie = function (a) {
        var b, c = new RegExp("(^| )" + a + "=([^;]*)(;|$)");
        return (b = document.cookie.match(c)) ? decodeURIComponent(escape(b[2])) : null
    }, c.token = function () {
        var a = c.getCookie("sessionID");
        return null === a && (a = ""), a
    }, c.setCookie = function (a, b, c, d) {
        var e = new Date;
        e.setTime(e.getTime() + 60 * c * 60 * 1e3), d ? document.cookie = a + "=" + escape(b) + ";Domain=" + d + ";path=/;expires=" + e.toGMTString() : document.cookie = a + "=" + escape(b) + ";Domain=dangdang.com;path=/;expires=" + e.toGMTString()
    }, c.delCookie = function (a) {
        var b = new Date;
        b.setTime(b.getTime() - 1);
        var d = c.getCookie(a);
        null != d && (document.cookie = a + "=" + d + ";expires=" + b.toGMTString() + "; path=/;Domain=dangdang.com")
    }, c.setData = function (a, b, d) {
        var b = encodeURIComponent(b);
        if (window.localStorage) {
            var e = window.localStorage;
            e.setItem(a, b)
        } else c.setCookie(a, b, d)
    }, c.getData = function (a) {
        if (window.localStorage) {
            var b = window.localStorage;
            return b.getItem(a) ? decodeURIComponent(b.getItem(a)) : null
        }
        c.getCookie(a)
    }, c.delData = function (a) {
        if (window.localStorage) {
            var b = window.localStorage;
            b.removeItem(a)
        } else c.delCookie(a)
    }, c.dateFormat = function (a, b) {
        var c = b, d = new Date(a), e = {
            "M+": d.getMonth() + 1,
            "d+": d.getDate(),
            "h+": d.getHours(),
            "m+": d.getMinutes(),
            "s+": d.getSeconds(),
            "q+": Math.floor((d.getMonth() + 3) / 3),
            S: d.getMilliseconds()
        };
        /(y+)/.test(c) && (c = c.replace(RegExp.$1, (d.getFullYear() + "").substr(4 - RegExp.$1.length)));
        for (var f in e)new RegExp("(" + f + ")").test(c) && (c = c.replace(RegExp.$1, 1 == RegExp.$1.length ? e[f] : ("00" + e[f]).substr(("" + e[f]).length)));
        return c
    }, c.randomNumeric = function (a) {
        for (var b = "", c = a, d = 0; c > d; d++)b += Math.floor(10 * Math.random());
        return b
    }, c.generateGuid = function () {
        var a = "", b = (new Date).getTime();
        b = b.substring(b.length() - 3, b.length());
        var d = c.dateFormat(new Date, "yyyyMMddHHmmss");
        return a = d + b + c.randomNumeric(6) + c.randomNumeric(6) + c.randomNumeric(6), a.toString()
    }, c.getQueryString = function (a) {
        var b = new RegExp("(^|&)" + a + "=([^&]*)(&|$)", "i"), c = window.location.search.substr(1).match(b);
        return null != c ? decodeURIComponent(c[2]) : null
    }, c.loadJsFiles = function (a, b) {
        var c = document.createElement("script");
        c.setAttribute("type", "text/javascript"), c.setAttribute("src", b), a.append(c)
    }, c.UUID = function () {
        this.id = this.createUUID()
    }, c.UUID.prototype = {
        valueOf: function () {
            return this.id
        }, toString: function () {
            return this.id
        }, createUUID: function () {
            var a = this, b = new Date(1582, 10, 15, 0, 0, 0, 0), c = new Date, d = c.getTime() - b.getTime(),
                e = a.getIntegerBits(d, 0, 31), f = a.getIntegerBits(d, 32, 47), g = a.getIntegerBits(d, 48, 59) + "1",
                h = a.getIntegerBits(a.rand(4095), 0, 7), i = a.getIntegerBits(a.rand(4095), 0, 7),
                j = a.getIntegerBits(a.rand(8191), 0, 7) + a.getIntegerBits(a.rand(8191), 8, 15) + a.getIntegerBits(a.rand(8191), 0, 7) + a.getIntegerBits(a.rand(8191), 8, 15) + a.getIntegerBits(a.rand(8191), 0, 15);
            return e + f + g + h + i + j
        }, getIntegerBits: function (a, b, c) {
            var d = this.returnBase(a, 16), e = new Array, f = "", g = 0;
            for (g = 0; g < d.length; g++)e.push(d.substring(g, g + 1));
            for (g = Math.floor(b / 4); g <= Math.floor(c / 4); g++)f += e[g] && "" != e[g] ? e[g] : "0";
            return f
        }, returnBase: function (a, b) {
            return a.toString(b).toUpperCase()
        }, rand: function (a) {
            return Math.floor(Math.random() * (a + 1))
        }
    }, c.getUserName = function () {
        var b = c.getCookie("MDD_username");
        return null == b && a.ajax({
            method: "GET",
            //url: "/media/api2.go?action=getUser&selfType=0&pubId=5&rewardIcon=" + c.setBaseApiParams(),
            url:"welcome/api1",
            async: !1,
            success: function (a) {
                if (0 == parseInt(a.status.code)) {
                    var d = a.data.userInfo;
                    c.setCookie("MDD_username", d.nickName, 8760), c.setCookie("MDD_custId", d.pubCustId, 8760), b = d.nickName
                }
            }
        }), b
    }, c.getCustId = function () {
        return c.token() && null != c.token() ? (a.ajax({
            method: "GET",
            url: "/media/api2.go?action=getUser&selfType=0&pubId=5&rewardIcon=" + c.setBaseApiParams(),
            async: !1,
            success: function (a) {
                if (0 == parseInt(a.status.code)) {
                    var b = a.data.userInfo;
                    custId = decodeURIComponent(b.pubCustId)
                }
            }
        }), custId) : !1
    }, c.setBaseApiParams = function () {
        var a = c.getQueryString("channelId");
        null == a && (a = 7e4);
        var b = c.getCookie("__permanent_id");
        // return null == b && (b = ""), "&deviceSerialNo=html5&macAddr=html5&channelType=html5&permanentId=" + b + "&returnType=json&channelId=" + a + "&clientVersionNo=5.8.4&platformSource=DDDS-P&fromPlatform=106&deviceType=pconline&token=" + c.token()
        return '';
    }, c.baseApiParams = c.setBaseApiParams(), c.template = function (a, c) {
        var d = b.template(a);
        return d(c)
    }, c.dealTime = function (a) {
        if (void 0 == a)return "";
        var a = parseInt(a), b = (new Date).getTime() - a, c = 864e5;
        if (b > 0) {
            if (b - c > 0) {
                var d = new Date(a);
                return d.getFullYear() + "-" + (d.getMonth() + 1) + "-" + d.getDate()
            }
            var e = b / 1e3;
            if (parseInt(e / 60) < 60)return parseInt(e / 60) + "分钟前";
            var f = Math.floor(e / 3600);
            return f + "小时" + parseInt((e - 3600 * f) / 60) + "分钟前"
        }
        return "0分钟前"
    }, c.newDealTime = function (a) {
        function b(a) {
            return 1 === (a + "").length ? "0" + a : a
        }

        function c() {
            var a = new Date, b = a.getHours(), c = a.getMinutes(), d = a.getSeconds();
            return 1e3 * (3600 * b + 60 * c + d)
        }

        if (!a)return "";
        var d = parseInt(a), a = new Date(d), e = new Date, f = e.getTime(), g = a.getFullYear(), h = e.getFullYear(),
            i = a.getMonth() + 1, j = a.getDate(), k = a.getHours(), l = a.getMinutes();
        if (h > g)return b(g) + "-" + b(i) + "-" + b(j);
        var m = c();
        return m > f - d ? "今天 " + b(k) + ":" + b(l) : f - d > m && m + 864e5 > f - d ? "昨天" + b(k) + ":" + b(l) : b(i) + "-" + b(j)
    }, c.leaveTime = function (a) {
        var a = parseInt(a);
        if (isNaN(a) || !a)return 0;
        var b = a - (new Date).getTime();
        if (0 >= b)return 0;
        var c = 864e5, d = 36e5, e = 6e4, f = 1e3;
        return b >= c ? parseInt(b / c) + "天" : b >= d ? parseInt(b / d) + "小时" : b >= e ? parseInt(b / e) + "分钟" : b >= f ? parseInt(b / f) + "秒" : void 0
    }, c.getBookUrl = function (a) {
        var b = a.mediaType || a.bookType;
        if (!b)return null;
        var c = "";
        switch (b) {
            case 1:
            case 2:
            case 4:
                a.saleId && a.mediaId && (c = "./product/" + a.saleId + ".html");
                break;
            case 3:
                a.productId && (c = "http://product.dangdang.com/" + a.productId + ".html")
        }
        return c
    }, c.joinBar = function (b) {
        a.get("/media/api.go?action=barMember" + c.baseApiParams, {
            actionType: b.actionType || 1,
            barId: b.barId,
            custId: b.custId || ""
        }, function (a) {
            return 0 != a.status.code && 10003 != a.status.code && 25015 != a.status.code ? void c.setMessageTip("好像发生了什么事情，稍后再试试吧~") : void b.callback(a)
        })
    }, c.onTime = function (b) {
        var c = a(window).scrollTop(), d = a(document).height(), e = a(window).height();
        return c + e + b >= d ? !0 : !1
    }, c.dealStar = function (a) {
        return Math.round(a)
    }, c.setMessageTip = function (b) {
        var c = this;
        c.msgTip = a(".message-tip"), 0 == c.msgTip.length && (c.msgTip = a('<div class="message-tip"></div>'), a("body").append(c.msgTip)), clearTimeout(c.msgTipTime), c.msgTip.html(b).fadeIn(1e3), c.msgTipTime = setTimeout(function () {
            c.msgTip.fadeOut(1e3)
        }, 2500)
    }, c.hideMessageTip = function () {
        var b = this;
        b.msgTip = a(".message-tip"), clearTimeout(b.msgTipTime), b.msgTip.hide()
    }, c.getHost = function () {
        return "http://e.dangdang.com/"
    }, window.productPostsNum = function () {
        window.productDetailReturn && window.productBarReturn && a("#postNum").length > 0 && "" == a("#postNum").html() && a("#postNum").html("(" + window.productComNum + "人评论)")
    }, window.productComNum = 0, window.productDetailReturn = !1, window.productBarReturn = !1, window.nofind = function (a, b) {
        a.src = b || "img/book_def_180_260.png", a.onerror = null
    }, document.ondragstart = function () {
        return !1
    }, c
}), define("loginModule", ["jquery", "ddbase"], function (a, b) {
    var c = function (a) {
        var c = b.token();
        if ("" == c) {
            var d, e = "https://login.dangdang.com/signin.aspx";
            d = void 0 != a && "" != a ? a : window.location.href.toString(), window.location.href = e + "?returnurl=" + encodeURIComponent(d)
        }
    };
    return c
}), define("signOutModule", ["jquery", "ddbase"], function (a, b) {
    var c = function (c) {
        var d = b.token();
        if ("" != d) {
            var e = "/media/api2.go?action=loginOutV2" + b.setBaseApiParams();
            a.ajax({
                method: "POST", url: e, success: function (a) {
                    0 == parseInt(a.status.code) && (b.delCookie("sessionID"), b.delCookie("MDD_username"), b.delCookie("MDD_custId"), b.delCookie("login.dangdang.com"), void 0 != c && c())
                }
            })
        }
    };
    return c
}), define("headerChildNav", ["jquery", "ddbase"], function (a, b) {
    var c = {
        init: function (a) {
            var c = this;
            "original_index" == c.judgePage() && ("man" == b.getQueryString("originalSex") || null == b.getQueryString("originalSex") ? c.initPage(a, 0) : "woman" == b.getQueryString("originalSex") && c.initPage(a, 1)), c.change(a)
        }, change: function (b) {
            a("#originLeftMenuMan").click(function () {
                window.location.href = "original_index_page.html?originalSex=man"
            }), a("#originLeftMenuWoman").click(function () {
                window.location.href = "original_index_page.html?originalSex=woman"
            })
        }, clickfn: function (a, b) {
            var c = this;
            c.initPage(a, b)
        }, judgePage: function () {
            var a = window.location.pathname, b = a.lastIndexOf("/");
            return a = a.substr(b + 1, a.length - b), a = a.split("_page")[0]
        }, initPage: function (b, c) {
            for (key in b)"#focusPic" != b[key] ? (a(b[key]).find(".sexContent").hide().eq(c).show(), a(b[key]).find(".more li").hide().eq(c).show(), a(b[key]).find(".nav li").hide().eq(c).show(), a(window).scroll()) : (a(b[key]).find(".sexContent").css({
                opacity: 0,
                "z-index": 1
            }).eq(c).css({
                opacity: 1,
                "z-index": 10
            }), 0 == c && window.originWomanPic && window.originManPic ? (originWomanPic.timer = setTimeout(function () {
                window.originWomanPic.stop()
            }, 0), window.originManPic.stop(), window.originManPic.play()) : 1 == c && window.originWomanPic && window.originManPic && (originManPic.timer = setTimeout(function () {
                    window.originManPic.stop()
                }, 0), window.originWomanPic.stop(), window.originWomanPic.play()))
        }
    };
    return c
}), define("publicMethod", ["jquery", "publicHeaderNav", "publicHeaderChildnavModule", "publicSideCodeModule", "publicHeadersearch", "loginModule", "signOutModule", "ddbase", "headerChildNav"], function (a, b, c, d, e, f, g, h, i) {
    var j = {
        init: function () {
            b.itHover(), d.toTop(), d.closeCode(), c.itHover(), e.getCursor(), e.searchBtn(), this.checkReadingCenterLogin(), this.jumpToRecharge(), i.init({
                el1: "#originHotArticle",
                el2: "#originRecommend",
                el3: "#originLimitedFree",
                el4: "#originRightRecomend",
                el5: "#originBigAds",
                el6: "#hotWholeBook",
                el7: "#originalHotTopic",
                el8: "#focusPic"
            }), j.setHeaderImg(), a("#loginBtn").click(function () {
                f()
            }), a("#signOutBtn").on("click", function () {
                g(j.setLoginHtml())
            }), a("#headerMyOrder").on("click", function () {
                if ("" == h.token()) {
                    var a = window.location.href;
                    a = a.split("/"), a[a.length - 1] = "http://orderb.dangdang.com/myallorders.aspx", a = a.join("/"), f(a)
                } else window.location = "http://orderb.dangdang.com/myallorders.aspx"
            });
            var k = h.getQueryString("channelId");
            null == k && (k = 7e4), h.setCookie("MDD_channelId", k, 8760), h.setCookie("MDD_fromPlatform", "307", 8760), this.readcenterRedpoint()
        }, setHeaderImg: function () {
            if ("" != h.token()) {
                var b = h.getUserName(), c = '<img class="header_img" src="img/payment/head_img.png">' + b;
                a("#loginBtn").html(c), a("#loginBtn").after('<span id="signOutBtn">[退出]</span>'), a("#registerBtn").html("")
            }
        }, setLoginHtml: function () {
            a("#signOutBtn").remove(), a("#loginBtn").html("你好，请登录"), a("#registerBtn").html('<a target="_blank" href="https://login.dangdang.com/Register.aspx?returnurl=http://e.dangdang.com/">免费注册</a>|')
        }, setProductTotalCount: function (b) {
            parseInt(b) > 0 && a(".public_headersearch_module .shopcar_num").html("(" + b + ")"), 0 == parseInt(b) && a(".public_headersearch_module .shopcar_num").html("")
        }, checkReadingCenterLogin: function () {
            a("#readingCenterBtn").on("click", function (a) {
                if (a.preventDefault(), h.token() && "" != h.token()) window.location.href = "http://e.dangdang.com/reading_center_page.html"; else {
                    var b = window.location.href;
                    b = b.split("/"), b[b.length - 1] = "http://e.dangdang.com/reading_center_page.html", b = b.join("/"), f(b)
                }
            })
        }, jumpToRecharge: function () {
            a(".public_headernav_module .want_to_recharge").on("click", function (a) {
                a.preventDefault(), h.token() && "" != h.token() ? window.location.href = "http://e.dangdang.com/recharge_methord_page.html?fromPlatform=309" : f()
            })
        }, readcenterRedpoint: function () {
            var b = h.token(), c = this;
            b && a.get("/media/api.go?action=getCustomerSubscription" + h.baseApiParams, {
                start: 0,
                end: 10
            }, function (b) {
                0 == b.status.code && 0 != b.data.allUpdateChapter && 0 != b.data.allUpdateMedia && (h.getCookie("showzhuigengredpointer") || (a("#readingCenterBtn").html('<i class="icon"></i><span class="zhuigengredcontainer">阅读中心<span class="zhuigengredpoint"></span></span>'), c.zhuigengNotice(b.data), a(".zhuigengredcontainer .zhuigengredpoint").show()))
            })
        }, getMedia: function (a) {
            for (var b = {}, c = "", d = 0; d < a.mediaList.length; d++)if (a.mediaList[d].subscriptionCount > 0) {
                b = a.mediaList[d];
                break
            }
            return c = 1 == a.allUpdateMedia ? '<span class="title">' + b.title + '</span>已经更新至<span class="lastChapt">' + b.lastUpdateChapter + "</span>" : '<span class="title">' + b.title + "</span>等已经有了更新"
        }, zhuigengNotice: function (b) {
            var c = window.location.href;
            if (-1 != c.indexOf("index_page") || -1 != c.indexOf("publish_index_page") || -1 != c.indexOf("original_index_page.html")) {
                var d = '<div class="zhuigengNotice"><div class="title">更新通知<div class="closeBtn"></div></div><div class="content"><div>' + this.getMedia(b) + '</div><a class="gotoRead" href="booksshelf_page.html?tab=read">去书架</a></div></div>';
                a("body").append(d), a("body").delegate(".zhuigengNotice .closeBtn", "click", function () {
                    a(".zhuigengNotice").hide()
                })
            }
        }
    };
    return j
}), define("classification_left_nav", ["jquery", "underscore", "ddbase"], function (a, b, c) {
    var d = {};
    return d.init = function (d) {
        var e = b.template(a(d.template).html());
        a.get(d.url + c.setBaseApiParams(), function (b) {
            a(d.container).html(e(b.data)), a(".classification_left_nav .publisher h3").on("click", function () {
                var b = a(this).parent();
                b.addClass("selected").siblings().removeClass("selected"), b.children("ul").slideToggle().end().siblings().children("ul").slideUp()
            }), a(".classification_left_nav h4").on("click", function () {
                a(this).addClass("current").parent().siblings().find("h4").removeClass("current"), a(this).siblings("ul").slideToggle().end().parent().siblings().find("ul").slideUp(), a(this).parents(".publisher").siblings().find("h4").removeClass("current").siblings("ul").slideUp(), a(this).parents(".publisher").siblings(".original_blank").find(".current").removeClass("current")
            }), a(".third_level li").on("click", function () {
                a(this).addClass("current").siblings().removeClass("current")
            }), a(".original_blank .second_level li").on("click", function () {
                a(".classification_left_nav li").removeClass("current"), a(this).addClass("current")
            });
            var c = a("[data-type=" + d.queryString + "]");
            c.parents(".publication").length ? c.parents(".third_level").length ? (c.addClass("current"), c.parents(".third_level").slideDown().siblings().addClass("current").parents(".second_level").slideDown().parents(".first_level").addClass("selected")) : c.parents(".second_level").length ? c.addClass("current").siblings(".third_level").slideDown().parents(".second_level").slideDown().parents(".first_level").addClass("selected") : c.siblings(".second_level").slideDown().parents(".first_level").addClass("selected") : c.parents(".original_blank").length && (c.parent(".second_level").slideDown(), c.addClass("current").siblings().removeClass("current"), a(".for_original").show(), a(".for_publish").hide())
        })
    }, d
}), define("dealPrice", [], function () {
    var a = {
        init: function (a) {
            var b = "";
            if (null != a && a.mediaList && a.mediaList[0]) {
                var c = a.mediaList[0];
                if (3 == c.promotionId || 1 == c.freeBook) b = "免费"; else if (2 == c.mediaType) {
                    void 0 == c.price ? b = "" : 0 == c.price ? b = "免费" : c.promotionPrice && void 0 != c.promotionPrice ? (b = Math.min((parseInt(c.price) / 100).toFixed(2), c.promotionPrice), b = "￥" + b.toFixed(2)) : b = "￥" + (parseInt(c.price) / 100).toFixed(2)
                } else 1 == c.mediaType ? b = 1 == a.isSupportFullBuy ? void 0 == a.price ? "" : 0 == a.price ? "免费" : parseInt(a.price) + "铃铛/本" : void 0 == c.priceUnit ? "" : 0 == c.priceUnit ? "免费" : parseInt(c.priceUnit) + "铃铛/千字" : 4 == c.mediaType ? b = 1 == a.isSupportFullBuy ? void 0 == a.price ? "" : 0 == a.price ? "免费" : parseInt(a.price) + "铃铛/本" : void 0 == c.priceUnit ? "" : 0 == c.priceUnit ? "免费" : parseInt(c.priceUnit) + "铃铛/话" : 3 == c.mediaType && (b = 0 == c.lowestPrice ? "免费" : "￥" + (parseInt(100 * c.lowestPrice) / 100).toFixed(2));
                return b
            }
        }, dealPrice2: function (a) {
            var b = "";
            if (null != a)if (3 == a.promotionId) b = "免费"; else if (2 == a.mediaType) {
                b = void 0 == a.price ? "" : 0 == a.price ? "免费" : "￥" + (parseInt(a.price) / 100).toFixed(2)
            } else 1 == a.mediaType ? b = 1 == a.isSupportFullBuy ? void 0 == a.price ? "" : 0 == a.price ? "免费" : parseInt(a.price) + "铃铛/本" : void 0 == a.priceUnit ? "" : 0 == a.priceUnit ? "免费" : parseInt(a.priceUnit) + "铃铛/千字" : 4 == a.mediaType ? b = 1 == a.isSupportFullBuy ? void 0 == a.price ? "" : 0 == a.price ? "免费" : parseInt(a.price) + "铃铛/本" : void 0 == a.priceUnit ? "" : 0 == a.priceUnit ? "免费" : parseInt(a.priceUnit) + "铃铛/话" : 3 == a.mediaType && (b = 0 == a.lowestPrice ? "免费" : "￥" + (parseInt(100 * a.lowestPrice) / 100).toFixed(2));
            return b
        }, originPrice: function (a) {
            var b = "";
            return void 0 != a.paperBookId && "" != a.paperBookId && (b = "￥" + (parseInt(a.paperBookPrice) / 100).toFixed(2)), b
        }
    };
    return a
}), define("add_more", ["jquery", "underscore", "dealPrice", "ddbase"], function (a, b, c, d) {
    var e = {};
    e.dealParam = function (c) {
        this.totalNum = 0, this.templContent = a(c.template).html(), this.templ = b.template(this.templContent), this.startNumber = c.startNumber || 0, this.endNumber = c.endNumber || 3, this.gapNumber = c.gapNumber || 0, this.distance = c.distance || 100, this.eventName = c.eventName || "scroll", this.flag = c.flag || !1, this.url = c.url, this.first = !0, this.midCallback = c.midCallback || function () {
            }, this.firstCallback = c.firstCallback || function () {
            }, this.container = c.container, this.gapNumber = c.gapNumber || "", this.otherParam = c.otherParam || " ", this.endDeal = function () {
            a(this.container).append("<div class='go_for_more' style='clear:both'><a href='#'>去看更多帖子(共" + this.totalNum + "条)</a></div>")
        }, this.endCallback = c.endCallback || this.endDeal, this.dealMethod = c.dealMethod || {}
    }, e.init = function (a) {
        this.dealParam(a), this.first = !0, "scroll" == this.eventName ? this.getData(this.otherParam) : (this.getData(this.otherParam), this.first = !1)
    }, e.render = function (b) {
        if ("scroll" == this.eventName) {
            var c = a("body").outerHeight(), d = a(window).scrollTop(), e = a(window).height();
            d + e > c - this.distance && !this.flag && (this.flag = !0, this.first = !1, this.getData(this.otherParam))
        } else b && b.otherParam && (this.otherParam = b.otherParam || this.otherParam), this.first = !1, this.getData(this.otherParam)
    }, e.getData = function (b) {
        if (!this.first) {
            if (this.startNumber = this.endNumber + 1, this.endNumber = this.startNumber + this.gapNumber - 1, this.startNumber > this.totalNum)return !1;
            this.startNumber <= this.totalNum && this.endNumber >= this.totalNum && (this.endNumber = this.totalNum)
        }
        var c = this.url + "&start=" + this.startNumber + "&end=" + this.endNumber;
        this.first && "click" == this.eventName && a(this.container).html('<div class="loading"></div>'), a(".clickMore").hide();
        var d = this;
        a.get(c, b, function (b) {
            if ((0 == d.startNumber || d.startNumber == d.gapNumber) && d.firstCallback(b), a(".clickMore").show(), a(d.container).find(".loading").remove(), b.data.total && b.data.total > 0) d.totalNum = b.data.total; else {
                if (!(b.data.totalCount && b.data.totalCount > 0))return a(d.container).append('<div class="add_more_end">亲，没有更多内容了</div>'), a(d.container).find(".loading").remove(), a(".clickMore").hide(), !1;
                d.totalNum = b.data.totalCount
            }
            var b = b.data, c = a.extend({}, {data: b}, d.dealMethod);
            d.first ? (a(d.container).find(".loading").remove(), a(d.container).html(d.templ(c)), a(".clickMore_wrap").show()) : (a(".clickMore_wrap").show(), a(d.container).append(d.templ(c)), d.endNumber >= d.totalNum && a(d.container).find(".loading").remove(), d.first = !1), a(d.container).append('<div class="loading" style="clear:both;"></div>'), d.midCallback(), d.endNumber >= d.totalNum && (a(".clickMore_wrap").hide(), "scroll" == d.eventName && (a(d.container).find(".loading").remove(), d.endCallback())), d.flag = !1
        })
    };
    var f = function () {
    };
    return f.prototype = e, f
}), require(["jquery", "underscore", "publicMethod", "classification_left_nav", "dealPrice", "publicHeaderNav", "publicHeaderChildnavModule", "publicHeadersearch", "add_more", "ddbase", "publicSideCodeModule"], function (a, b, c, d, e, f, g, h, i, j, k) {
    c.init(), f.itHover(), g.itHover(), h.getCursor(), h.searchBtn(), a(".public_totop_module").click(function () {
        k.toTop()
    }), a(".public_sideecode_module .close").click(function () {
        k.closeCode()
    });
    var l = "WY1", m = "dd_sale", n = 0, o = j.getQueryString("category"), p = j.getQueryString("dimension");
    o && p && (l = o, m = p), d.init({
        //url: "/media/api2.go?action=mediaCategory&channelType=ddds&start=0&end=5&level=6&channelId=10020",
          url:"./index.php/welcome/api1",
        template: "#classification_nav",
        container: "#nav_left",
        queryString: o
    });
    var q = function (b) {
        var c = a("li[data-type=" + b + "]");
        c.addClass("on").siblings("li").removeClass("on");
        var d = c.parent().find("li").eq(0).outerWidth(),
            e = c.offset().left - c.parent().offset().left + (c.outerWidth() - d) / 2;
        a(".index_subnav_module .bar").width(d).animate({left: e + "px"}, 100), m = b
    }, r = new i;
    r.init({
        template: "#classification_book_list",
        container: "#book_list",
        startNumber: 0,
        endNumber: 20,
        gapNumber: 21,
        eventName: "scroll",
        distance: 100,
        //url: "/media/api.go?action=mediaCategoryLeaf" + j.setBaseApiParams(),
          url:"./index.php/welcome/api2?1=1",
        dealMethod: {dealPrice: e},
        // otherParam: {category: l, dimension: m},
        otherParam: {},
        endCallback: function () {
            a(".loading").remove(), a("#book_list").append("<div class='go_for_more' style='clear:both'><a href='javascript:void(0)'>亲，没有更多内容了</a></div>")
        }
    }), a("body").on("click", ".classification_left_nav", function (b) {
        return a(b.target).hasClass("whole") ? !1 : void(l != a(b.target).data("type") && null != l && (a("#book_list").html('<div class="loading"></div>'), l = a(b.target).data("type"), r.init({
            template: "#classification_book_list",
            container: "#book_list",
            startNumber: 0,
            endNumber: 20,
            gapNumber: 21,
            distance: 100,
            eventName: "scroll",
            dealMethod: {dealPrice: e},
            //url: "/media/api.go?action=mediaCategoryLeaf" + j.setBaseApiParams(),
              url:"./index.php/welcome/api2?1=1",
            otherParam: {category: l, dimension: m, order: n},
            endCallback: function () {
                a(".loading").remove(), a("#book_list").append("<div class='go_for_more' style='clear:both'><a href='javascript:void(0)'>亲，没有更多内容了</a></div>")
            }
        })))
    });
    var s = function (b) {
        l != a(b.target).data("type") && null != l && (a("#book_list").html('<div class="loading"></div>'), l = a(b.target).data("type"), r.init({
            template: "#classification_book_list",
            container: "#book_list",
            startNumber: 0,
            endNumber: 20,
            gapNumber: 21,
            eventName: "scroll",
            distance: 100,
            dealMethod: {dealPrice: e},
            //url: "/media/api.go?action=mediaCategoryLeaf" + j.setBaseApiParams(),
              url:"./index.php/welcome/api2?1=1",
            otherParam: {category: l, dimension: m, order: n},
            endCallback: function () {
                a(".loading").remove(), a("#book_list").append("<div class='go_for_more' style='clear:both'><a href='javascript:void(0)'>亲，没有更多内容了</a></div>")
            }
        }))
    };
    a("body").on("click", ".publication", function (b) {
        a(".index_subnav_module .for_publish").show(), a(".index_subnav_module .for_original").hide(), "dd_sale" == m || "comment" == m || "newest" == m || "price" == m ? s(b) : ("sale" == m || "comment_star" == m || "update" == m || "rewards" == m) && (q("dd_sale"), s(b))
    }), a("body").on("click", ".original_blank", function (b) {
        a(".index_subnav_module .for_publish").hide(), a(".index_subnav_module .for_original").show(), "dd_sale" == m || "comment" == m || "newest" == m || "price" == m ? (q("sale"), s(b)) : ("sale" == m || "comment_star" == m || "update" == m || "rewards" == m) && s(b)
    }), a(".index_subnav_module li").click(function (b) {
        n = 0;
        var c = !0;
        a(this).hasClass("price_holder") ? a(this).hasClass("down") ? (a(this).removeClass("down").addClass("up"), n = 2, c = !1) : a(this).hasClass("up") ? (a(this).removeClass("up").addClass("down"), n = 0, c = !1) : (a(this).addClass("down"), n = 0, c = !0) : (a(".price_holder").removeClass("up").removeClass("down"), n = 0, c = !0), (m != a(b.target).data("type") && null != m || 0 == c) && (a("#book_list").html('<div class="loading"></div>'), q(a(b.target).data("type")), r.init({
            template: "#classification_book_list",
            container: "#book_list",
            startNumber: 0,
            endNumber: 20,
            gapNumber: 21,
            distance: 100,
            eventName: "scroll",
            dealMethod: {dealPrice: e},
            url: "/media/api.go?action=mediaCategoryLeaf" + j.setBaseApiParams(),
            otherParam: {category: l, dimension: m, order: n},
            endCallback: function () {
                a(".loading").remove(), a("#book_list").append("<div class='go_for_more' style='clear:both'><a href='javascript:void(0)'>亲，没有更多内容了</a></div>")
            }
        }))
    }), a(window).scroll(function () {
        r.render()
    })
}), define("classification_list", function () {
});