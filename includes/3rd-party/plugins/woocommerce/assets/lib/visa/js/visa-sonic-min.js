/* eslint-disable */
!(function (e) {
        function r(a) {
          if (t[a]) return t[a].exports
          var n = (t[a] = { i: a, l: !1, exports: {} })
          return e[a].call(n.exports, n, n.exports, r), (n.l = !0), n.exports
        }
        var t = {}
        ;(r.m = e),
          (r.c = t),
          (r.d = function (e, t, a) {
            r.o(e, t) ||
              Object.defineProperty(e, t, {
                configurable: !1,
                enumerable: !0,
                get: a,
              })
          }),
          (r.n = function (e) {
            var t =
              e && e.__esModule
                ? function () {
                    return e.default
                  }
                : function () {
                    return e
                  }
            return r.d(t, 'a', t), t
          }),
          (r.o = function (e, r) {
            return Object.prototype.hasOwnProperty.call(e, r)
          }),
          (r.p = ''),
          r((r.s = 1))
      })([
        ,
        function (e, r, t) {
          'use strict'
          t(2),
            (function () {
              function e(e, r) {
                r || (r = window.location.href),
                  (e = e.replace(/[\[\]]/g, '\\$&'))
                var t = new RegExp('[?&]' + e + '(=([^&#]*)|&|#|$)').exec(r)
                return t
                  ? t[2]
                    ? decodeURIComponent(t[2].replace(/\+/g, ' '))
                    : ''
                  : null
              }
              function r(e) {
                return document.getElementsByClassName(e)
              }
              function t(e) {
                var r = e
                4 === r.length &&
                  (r = '#' + r[1] + r[1] + r[2] + r[2] + r[3] + r[3])
                var t = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(r)
                return t
                  ? {
                      r: parseInt(t[1], 16),
                      g: parseInt(t[2], 16),
                      b: parseInt(t[3], 16),
                    }
                  : null
              }
              function a(e, r, t) {
                var a = [e, r, t].map(function (e) {
                  return (e /= 255) <= 0.03928
                    ? e / 12.92
                    : Math.pow((e + 0.055) / 1.055, 2.4)
                })
                return 0.2126 * a[0] + 0.7152 * a[1] + 0.0722 * a[2]
              }
              function n(e, r) {
                var t = (a(e.r, e.g, e.b) + 0.05) / (a(r.r, r.g, r.b) + 0.05)
                return t < 1 && (t = 1 / t), t
              }
              function c() {
                var e = d.play()
                void 0 !== e && e.then(function () {}).catch(function (e) {})
              }
              var o = /^((?!chrome|android).)*safari/i.test(
                  navigator.userAgent,
                ),
                s = document.getElementById('visa-branding'),
                i = e('constrained'),
                l = !!i && 'true' === i,
                u =
                  Math.max(
                    document.documentElement.clientWidth,
                    window.innerWidth || 0,
                  ) /
                  Math.max(
                    document.documentElement.clientHeight,
                    window.innerHeight || 0,
                  )
              l
                ? (s.classList.add('constrained'),
                  u > 1.5 &&
                    document
                      .getElementById('visa-branding')
                      .classList.add('greater-than-ar'))
                : u > 4.6 &&
                  document
                    .getElementById('visa-branding')
                    .classList.add('greater-than-ar')
              var g = e('sound')
              if (!g || 'true' === g) {
                var d = new Audio(visa_lib.plugin_url + 'theme/visa_branding_sound.mp3')
                o
                  ? c()
                  : setTimeout(function () {
                      c()
                    }, 220)
              }
              var m = e('checkmark')
              !m || 'true' === m || s.classList.add('no-checkmark')
              var f = e('color') || 'blue',
                h = f.toLowerCase()
              if ('blue' !== h.toLowerCase() && 'white' !== h.toLowerCase())
                if (/(^[0-9A-F]{6}$)|(^[0-9A-F]{3}$)/i.test(f)) {
                  var v = '#' + f,
                    k = (function (e) {
                      var r = t(v),
                        a = n(t('#ffffff'), r),
                        c = n(t('#1a1f71'), r),
                        o = { theme: '', error: '' }
                      return a < 3 && c < 3
                        ? ((o.error =
                            "Your custom color doesn't provide enough contrast. Please enter another color."),
                          o)
                        : a === c
                        ? ((o.theme = 'custom_dark'), o)
                        : ((o.theme = a > c ? 'custom_dark' : 'custom_light'),
                          o)
                    })()
                  k.error
                    ? (console.error(k.error), (f = 'blue'))
                    : (f = k.theme),
                    (function (e) {
                      var a = [
                        'flag-container',
                        'visa-container',
                        'wiper-left',
                        'wiper-right',
                        'wiper-middle',
                        'flag-mask-top',
                        'flag-mask-bottom',
                        'checkmark-mask',
                        'constrained-bottom-flag-mask',
                        'constrained-top-flag-mask',
                      ]
                      s.style.backgroundColor = e
                      for (var n = 0; n < a.length; n++)
                        r(a[n])[0].style.backgroundColor = e
                      var c = t(e),
                        o = 'linear-gradient(to $DIRECTION, $TRANSPARENT_COLOR 0%, $COLOR 95%)'.replace(
                          '$COLOR',
                          e,
                        )
                      ;(o = o.replace(
                        '$TRANSPARENT_COLOR',
                        'rgba(' + c.r + ',' + c.g + ',' + c.b + ',0)',
                      )),
                        (r(
                          'top-flag-fade-mask',
                        )[0].style.background = o.replace(
                          '$DIRECTION',
                          'left',
                        )),
                        (r(
                          'bottom-flag-fade-mask',
                        )[0].style.background = o.replace(
                          '$DIRECTION',
                          'right',
                        ))
                    })(v)
                } else
                  console.error('An invalid hex color was passed:', f),
                    (f = 'blue')
              var b = visa_lib.plugin_url + 'theme',
                _ = r('top-flag')[0],
                p = r('checkmark-circle')[0],
                w = r('checkmark')[0],
                C = r('visa-logo')[0],
                L = r('bottom-flag')[0]
              switch (f.toLowerCase()) {
                case 'white':
                  ;(b += '/white_theme'), 
                    (_.src = b + '/flag_blue.svg'),
                    (p.src = b + '/checkmark_circle_blue.svg'),
                    (w.src = b + '/checkmark_check_blue.svg'),
                    (C.src = b + '/logo_blue.svg'),
                    (L.src = b + '/flag_gold.svg'),
                    s.classList.add('background-light')
                  break
                case 'custom_light':
                  ;(b += '/custom_light'),
                    (_.src = b + '/flag_blue.svg'),
                    (p.src = b + '/checkmark_circle_blue.svg'),
                    (w.src = b + '/checkmark_check_blue.svg'),
                    (C.src = b + '/logo_blue.svg'),
                    (L.src = b + '/flag_blue.svg')
                  break
                case 'custom_dark':
                  ;(b += '/custom_dark'),
                    (_.src = b + '/flag_white.svg'),
                    (p.src = b + '/checkmark_circle_white.svg'),
                    (w.src = b + '/checkmark_check_white.svg'),
                    (C.src = b + '/logo_white.svg'),
                    (L.src = b + '/flag_white.svg')
                  break
                case 'blue':
                default:
                  ;(b += '/blue_theme'),
                    (_.src = b + '/flag_bluegradient.svg'),
                    (p.src = b + '/checkmark_circle_white.svg'),
                    (w.src = b + '/checkmark_check_white.svg'),
                    (C.src = b + '/logo_white.svg'),
                    (L.src = b + '/flag_goldgradient.svg'),
                    s.classList.add('background-dark')
              }
              s.addEventListener(
                'animationend',
                function (e) {
                  'scaleXY' === e.animationName &&
                    window.parent.postMessage('visa-sensory-branding-end', '*')
                },
                !1,
              )
            })()
        },
        function (e, r) {},
      ])
