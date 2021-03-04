/******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, {
/******/ 				configurable: false,
/******/ 				enumerable: true,
/******/ 				get: getter
/******/ 			});
/******/ 		}
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "";
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 3);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports) {

/* globals __VUE_SSR_CONTEXT__ */

// IMPORTANT: Do NOT use ES2015 features in this file.
// This module is a runtime utility for cleaner component module output and will
// be included in the final webpack user bundle.

module.exports = function normalizeComponent (
  rawScriptExports,
  compiledTemplate,
  functionalTemplate,
  injectStyles,
  scopeId,
  moduleIdentifier /* server only */
) {
  var esModule
  var scriptExports = rawScriptExports = rawScriptExports || {}

  // ES6 modules interop
  var type = typeof rawScriptExports.default
  if (type === 'object' || type === 'function') {
    esModule = rawScriptExports
    scriptExports = rawScriptExports.default
  }

  // Vue.extend constructor export interop
  var options = typeof scriptExports === 'function'
    ? scriptExports.options
    : scriptExports

  // render functions
  if (compiledTemplate) {
    options.render = compiledTemplate.render
    options.staticRenderFns = compiledTemplate.staticRenderFns
    options._compiled = true
  }

  // functional template
  if (functionalTemplate) {
    options.functional = true
  }

  // scopedId
  if (scopeId) {
    options._scopeId = scopeId
  }

  var hook
  if (moduleIdentifier) { // server build
    hook = function (context) {
      // 2.3 injection
      context =
        context || // cached call
        (this.$vnode && this.$vnode.ssrContext) || // stateful
        (this.parent && this.parent.$vnode && this.parent.$vnode.ssrContext) // functional
      // 2.2 with runInNewContext: true
      if (!context && typeof __VUE_SSR_CONTEXT__ !== 'undefined') {
        context = __VUE_SSR_CONTEXT__
      }
      // inject component styles
      if (injectStyles) {
        injectStyles.call(this, context)
      }
      // register component module identifier for async chunk inferrence
      if (context && context._registeredComponents) {
        context._registeredComponents.add(moduleIdentifier)
      }
    }
    // used by ssr in case component is cached and beforeCreate
    // never gets called
    options._ssrRegister = hook
  } else if (injectStyles) {
    hook = injectStyles
  }

  if (hook) {
    var functional = options.functional
    var existing = functional
      ? options.render
      : options.beforeCreate

    if (!functional) {
      // inject component registration as beforeCreate hook
      options.beforeCreate = existing
        ? [].concat(existing, hook)
        : [hook]
    } else {
      // for template-only hot-reload because in that case the render fn doesn't
      // go through the normalizer
      options._injectStyles = hook
      // register for functioal component in vue file
      options.render = function renderWithStyleInjection (h, context) {
        hook.call(context)
        return existing(h, context)
      }
    }
  }

  return {
    esModule: esModule,
    exports: scriptExports,
    options: options
  }
}


/***/ }),
/* 1 */
/***/ (function(module, exports) {

/*
	MIT License http://www.opensource.org/licenses/mit-license.php
	Author Tobias Koppers @sokra
*/
// css base code, injected by the css-loader
module.exports = function(useSourceMap) {
	var list = [];

	// return the list of modules as css string
	list.toString = function toString() {
		return this.map(function (item) {
			var content = cssWithMappingToString(item, useSourceMap);
			if(item[2]) {
				return "@media " + item[2] + "{" + content + "}";
			} else {
				return content;
			}
		}).join("");
	};

	// import a list of modules into the list
	list.i = function(modules, mediaQuery) {
		if(typeof modules === "string")
			modules = [[null, modules, ""]];
		var alreadyImportedModules = {};
		for(var i = 0; i < this.length; i++) {
			var id = this[i][0];
			if(typeof id === "number")
				alreadyImportedModules[id] = true;
		}
		for(i = 0; i < modules.length; i++) {
			var item = modules[i];
			// skip already imported module
			// this implementation is not 100% perfect for weird media query combinations
			//  when a module is imported multiple times with different media queries.
			//  I hope this will never occur (Hey this way we have smaller bundles)
			if(typeof item[0] !== "number" || !alreadyImportedModules[item[0]]) {
				if(mediaQuery && !item[2]) {
					item[2] = mediaQuery;
				} else if(mediaQuery) {
					item[2] = "(" + item[2] + ") and (" + mediaQuery + ")";
				}
				list.push(item);
			}
		}
	};
	return list;
};

function cssWithMappingToString(item, useSourceMap) {
	var content = item[1] || '';
	var cssMapping = item[3];
	if (!cssMapping) {
		return content;
	}

	if (useSourceMap && typeof btoa === 'function') {
		var sourceMapping = toComment(cssMapping);
		var sourceURLs = cssMapping.sources.map(function (source) {
			return '/*# sourceURL=' + cssMapping.sourceRoot + source + ' */'
		});

		return [content].concat(sourceURLs).concat([sourceMapping]).join('\n');
	}

	return [content].join('\n');
}

// Adapted from convert-source-map (MIT)
function toComment(sourceMap) {
	// eslint-disable-next-line no-undef
	var base64 = btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap))));
	var data = 'sourceMappingURL=data:application/json;charset=utf-8;base64,' + base64;

	return '/*# ' + data + ' */';
}


/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

/*
  MIT License http://www.opensource.org/licenses/mit-license.php
  Author Tobias Koppers @sokra
  Modified by Evan You @yyx990803
*/

var hasDocument = typeof document !== 'undefined'

if (typeof DEBUG !== 'undefined' && DEBUG) {
  if (!hasDocument) {
    throw new Error(
    'vue-style-loader cannot be used in a non-browser environment. ' +
    "Use { target: 'node' } in your Webpack config to indicate a server-rendering environment."
  ) }
}

var listToStyles = __webpack_require__(10)

/*
type StyleObject = {
  id: number;
  parts: Array<StyleObjectPart>
}

type StyleObjectPart = {
  css: string;
  media: string;
  sourceMap: ?string
}
*/

var stylesInDom = {/*
  [id: number]: {
    id: number,
    refs: number,
    parts: Array<(obj?: StyleObjectPart) => void>
  }
*/}

var head = hasDocument && (document.head || document.getElementsByTagName('head')[0])
var singletonElement = null
var singletonCounter = 0
var isProduction = false
var noop = function () {}
var options = null
var ssrIdKey = 'data-vue-ssr-id'

// Force single-tag solution on IE6-9, which has a hard limit on the # of <style>
// tags it will allow on a page
var isOldIE = typeof navigator !== 'undefined' && /msie [6-9]\b/.test(navigator.userAgent.toLowerCase())

module.exports = function (parentId, list, _isProduction, _options) {
  isProduction = _isProduction

  options = _options || {}

  var styles = listToStyles(parentId, list)
  addStylesToDom(styles)

  return function update (newList) {
    var mayRemove = []
    for (var i = 0; i < styles.length; i++) {
      var item = styles[i]
      var domStyle = stylesInDom[item.id]
      domStyle.refs--
      mayRemove.push(domStyle)
    }
    if (newList) {
      styles = listToStyles(parentId, newList)
      addStylesToDom(styles)
    } else {
      styles = []
    }
    for (var i = 0; i < mayRemove.length; i++) {
      var domStyle = mayRemove[i]
      if (domStyle.refs === 0) {
        for (var j = 0; j < domStyle.parts.length; j++) {
          domStyle.parts[j]()
        }
        delete stylesInDom[domStyle.id]
      }
    }
  }
}

function addStylesToDom (styles /* Array<StyleObject> */) {
  for (var i = 0; i < styles.length; i++) {
    var item = styles[i]
    var domStyle = stylesInDom[item.id]
    if (domStyle) {
      domStyle.refs++
      for (var j = 0; j < domStyle.parts.length; j++) {
        domStyle.parts[j](item.parts[j])
      }
      for (; j < item.parts.length; j++) {
        domStyle.parts.push(addStyle(item.parts[j]))
      }
      if (domStyle.parts.length > item.parts.length) {
        domStyle.parts.length = item.parts.length
      }
    } else {
      var parts = []
      for (var j = 0; j < item.parts.length; j++) {
        parts.push(addStyle(item.parts[j]))
      }
      stylesInDom[item.id] = { id: item.id, refs: 1, parts: parts }
    }
  }
}

function createStyleElement () {
  var styleElement = document.createElement('style')
  styleElement.type = 'text/css'
  head.appendChild(styleElement)
  return styleElement
}

function addStyle (obj /* StyleObjectPart */) {
  var update, remove
  var styleElement = document.querySelector('style[' + ssrIdKey + '~="' + obj.id + '"]')

  if (styleElement) {
    if (isProduction) {
      // has SSR styles and in production mode.
      // simply do nothing.
      return noop
    } else {
      // has SSR styles but in dev mode.
      // for some reason Chrome can't handle source map in server-rendered
      // style tags - source maps in <style> only works if the style tag is
      // created and inserted dynamically. So we remove the server rendered
      // styles and inject new ones.
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  if (isOldIE) {
    // use singleton mode for IE9.
    var styleIndex = singletonCounter++
    styleElement = singletonElement || (singletonElement = createStyleElement())
    update = applyToSingletonTag.bind(null, styleElement, styleIndex, false)
    remove = applyToSingletonTag.bind(null, styleElement, styleIndex, true)
  } else {
    // use multi-style-tag mode in all other cases
    styleElement = createStyleElement()
    update = applyToTag.bind(null, styleElement)
    remove = function () {
      styleElement.parentNode.removeChild(styleElement)
    }
  }

  update(obj)

  return function updateStyle (newObj /* StyleObjectPart */) {
    if (newObj) {
      if (newObj.css === obj.css &&
          newObj.media === obj.media &&
          newObj.sourceMap === obj.sourceMap) {
        return
      }
      update(obj = newObj)
    } else {
      remove()
    }
  }
}

var replaceText = (function () {
  var textStore = []

  return function (index, replacement) {
    textStore[index] = replacement
    return textStore.filter(Boolean).join('\n')
  }
})()

function applyToSingletonTag (styleElement, index, remove, obj) {
  var css = remove ? '' : obj.css

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = replaceText(index, css)
  } else {
    var cssNode = document.createTextNode(css)
    var childNodes = styleElement.childNodes
    if (childNodes[index]) styleElement.removeChild(childNodes[index])
    if (childNodes.length) {
      styleElement.insertBefore(cssNode, childNodes[index])
    } else {
      styleElement.appendChild(cssNode)
    }
  }
}

function applyToTag (styleElement, obj) {
  var css = obj.css
  var media = obj.media
  var sourceMap = obj.sourceMap

  if (media) {
    styleElement.setAttribute('media', media)
  }
  if (options.ssrId) {
    styleElement.setAttribute(ssrIdKey, obj.id)
  }

  if (sourceMap) {
    // https://developer.chrome.com/devtools/docs/javascript-debugging
    // this makes source maps inside style tags work properly in Chrome
    css += '\n/*# sourceURL=' + sourceMap.sources[0] + ' */'
    // http://stackoverflow.com/a/26603875
    css += '\n/*# sourceMappingURL=data:application/json;base64,' + btoa(unescape(encodeURIComponent(JSON.stringify(sourceMap)))) + ' */'
  }

  if (styleElement.styleSheet) {
    styleElement.styleSheet.cssText = css
  } else {
    while (styleElement.firstChild) {
      styleElement.removeChild(styleElement.firstChild)
    }
    styleElement.appendChild(document.createTextNode(css))
  }
}


/***/ }),
/* 3 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(4);
module.exports = __webpack_require__(19);


/***/ }),
/* 4 */
/***/ (function(module, exports, __webpack_require__) {

Nova.booting(function (Vue, router, store) {
  Vue.component('submit-keywords-card', __webpack_require__(5));
});

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(6)
/* template */
var __vue_template__ = __webpack_require__(18)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = null
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/Card.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-b9bc2c0a", Component.options)
  } else {
    hotAPI.reload("data-v-b9bc2c0a", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 6 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__SubmitForm__ = __webpack_require__(7);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__SubmitForm___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__SubmitForm__);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__KeywordBatches__ = __webpack_require__(13);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_1__KeywordBatches___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_1__KeywordBatches__);
//
//
//
//
//
//
//
//
//



/* harmony default export */ __webpack_exports__["default"] = ({
    data: function data() {
        return {
            reloadBatch: false
        };
    },

    props: ['card'],
    components: {
        SubmitForm: __WEBPACK_IMPORTED_MODULE_0__SubmitForm___default.a,
        KeywordBatches: __WEBPACK_IMPORTED_MODULE_1__KeywordBatches___default.a
    },
    methods: {
        formSubmitted: function formSubmitted() {
            this.$refs.keywordBatch.loadKeywordBatches();
        }
    }
});

/***/ }),
/* 7 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(8)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(11)
/* template */
var __vue_template__ = __webpack_require__(12)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = null
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/SubmitForm.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-468278b2", Component.options)
  } else {
    hotAPI.reload("data-v-468278b2", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 8 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(9);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(2)("182dc216", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-468278b2\",\"scoped\":false,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./SubmitForm.vue", function() {
     var newContent = require("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-468278b2\",\"scoped\":false,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./SubmitForm.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 9 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(1)(false);
// imports


// module
exports.push([module.i, "\n.submit-keywords-form-wrapper textarea {\n    min-height: 150px;\n    border: 1px solid #ccc;\n    padding: 10px 15px;\n    white-space: pre-line;\n}\n.submit-keywords-form-wrapper label {\n    font-size: 22px;\n    margin-bottom: 5px;\n}\n.submit-keywords-form-wrapper .container-box {\n    margin: 20px auto;\n    min-width: 650px;\n    max-width: 100%;\n}\n.notify-submit-success svg {\n    width: 150px;\n    height: 150px;\n    display: block;\n    margin: auto;\n}\n", ""]);

// exports


/***/ }),
/* 10 */
/***/ (function(module, exports) {

/**
 * Translates the list format produced by css-loader into something
 * easier to manipulate.
 */
module.exports = function listToStyles (parentId, list) {
  var styles = []
  var newStyles = {}
  for (var i = 0; i < list.length; i++) {
    var item = list[i]
    var id = item[0]
    var css = item[1]
    var media = item[2]
    var sourceMap = item[3]
    var part = {
      id: parentId + ':' + i,
      css: css,
      media: media,
      sourceMap: sourceMap
    }
    if (!newStyles[id]) {
      styles.push(newStyles[id] = { id: id, parts: [part] })
    } else {
      newStyles[id].parts.push(part)
    }
  }
  return styles
}


/***/ }),
/* 11 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    name: "SubmitForm",
    data: function data() {
        return {
            processing: false,
            keywords: '',
            market: 'US',
            errorResponse: {},
            displayForm: true,
            displaySubmitSuccess: false,
            batchId: ''
        };
    },

    props: {
        card: {
            required: true
        }
    },
    methods: {
        submit: function submit() {
            var _this = this;

            if (this.keywords != '' && this.market != '') {
                this.processing = true;
                axios.post('/nova-vendor/' + this.card.component + '/submit-keywords', {
                    keywords: this.keywords,
                    market: this.market
                }).then(function (response) {
                    _this.displayForm = false;
                    _this.displaySubmitSuccess = true;
                    _this.batchId = response.data.data;
                    _this.keywords = '';
                    _this.market = 'US';
                    _this.$emit('formSubmitted');
                }).catch(function (error) {
                    _this.errorResponse = error.response.data;
                }).finally(function () {
                    _this.processing = false;
                });
            }
        },
        processAnother: function processAnother() {
            this.displaySubmitSuccess = false;
            this.batchId = '';
            this.displayForm = true;
        }
    }
});

/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "submit-keywords-form-wrapper" }, [
    _c(
      "h1",
      { staticClass: "text-center text-3xl text-80 font-dark px-4 py-4" },
      [_vm._v("Submit Keywords")]
    ),
    _vm._v(" "),
    _vm.displayForm
      ? _c("div", { staticClass: "container-box shadow-sm rounded" }, [
          _c(
            "div",
            { staticClass: "shadow sm:rounded-md sm:overflow-hidden" },
            [
              Object.entries(_vm.errorResponse).length > 0
                ? _c("div", [
                    _c(
                      "div",
                      {
                        staticClass:
                          "px-4 py-3 leading-normal text-red-100 bg-red-700 rounded-lg",
                        attrs: { role: "alert" }
                      },
                      [
                        _c("h4", { staticClass: "mt-2 mb-2" }, [
                          _vm._v(" " + _vm._s(_vm.errorResponse.message) + " ")
                        ]),
                        _vm._v(" "),
                        _vm._l(_vm.errorResponse.errors, function(
                          error,
                          index
                        ) {
                          return _c(
                            "p",
                            { key: index, staticClass: "text-sm" },
                            [
                              _vm._v(
                                " \n                        => " +
                                  _vm._s(error[0]) +
                                  " \n                    "
                              )
                            ]
                          )
                        })
                      ],
                      2
                    )
                  ])
                : _vm._e(),
              _vm._v(" "),
              _c("div", [
                _c(
                  "div",
                  { staticClass: "px-4 py-5 bg-white space-y-6 sm:p-6" },
                  [
                    _c("div", [
                      _c(
                        "label",
                        {
                          staticClass:
                            "block text-sm font-medium text-gray-700",
                          attrs: { for: "about" }
                        },
                        [
                          _vm._v(
                            "\n                            Keywords\n                        "
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        {
                          staticClass:
                            "mt-1 px-10 py-5 pb-6 border-2 border-gray-300  border-dashed rounded-md"
                        },
                        [
                          _c("textarea", {
                            directives: [
                              {
                                name: "model",
                                rawName: "v-model",
                                value: _vm.keywords,
                                expression: "keywords"
                              }
                            ],
                            staticClass:
                              "shadow-lg mt-1 block w-full sm:text-sm border-gray-300 rounded-md\n                            focus:outline-none focus:ring-blue-500 focus:border-blue-500",
                            attrs: {
                              rows: "13",
                              placeholder:
                                "hp latop\n                            best notebooks\n                            mattress"
                            },
                            domProps: { value: _vm.keywords },
                            on: {
                              input: function($event) {
                                if ($event.target.composing) {
                                  return
                                }
                                _vm.keywords = $event.target.value
                              }
                            }
                          }),
                          _vm._v(" "),
                          _c(
                            "p",
                            { staticClass: "mt-4 text-sm text-gray-500" },
                            [
                              _vm._v(
                                "\n                                Enter keywords. One per line.\n                            "
                              )
                            ]
                          )
                        ]
                      )
                    ]),
                    _vm._v(" "),
                    _c("div", [
                      _c(
                        "label",
                        {
                          staticClass: "block text-sm font-medium text-gray-700"
                        },
                        [
                          _vm._v(
                            "\n                            Market\n                        "
                          )
                        ]
                      ),
                      _vm._v(" "),
                      _c(
                        "div",
                        {
                          staticClass:
                            "w-full block mt-1 justify-center p-6 border-2 border-gray-300 border-dashed rounded-md"
                        },
                        [
                          _c(
                            "div",
                            { staticClass: "w-full select-market-wrapper" },
                            [
                              _c(
                                "select",
                                {
                                  directives: [
                                    {
                                      name: "model",
                                      rawName: "v-model",
                                      value: _vm.market,
                                      expression: "market"
                                    }
                                  ],
                                  staticClass:
                                    "mt-1 block w-full p-3 pr-4 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-lg",
                                  on: {
                                    change: function($event) {
                                      var $$selectedVal = Array.prototype.filter
                                        .call($event.target.options, function(
                                          o
                                        ) {
                                          return o.selected
                                        })
                                        .map(function(o) {
                                          var val =
                                            "_value" in o ? o._value : o.value
                                          return val
                                        })
                                      _vm.market = $event.target.multiple
                                        ? $$selectedVal
                                        : $$selectedVal[0]
                                    }
                                  }
                                },
                                [
                                  _c("option", { attrs: { value: "DE" } }, [
                                    _vm._v("Germany")
                                  ]),
                                  _vm._v(" "),
                                  _c("option", { attrs: { value: "UK" } }, [
                                    _vm._v("United Kingdom")
                                  ]),
                                  _vm._v(" "),
                                  _c("option", { attrs: { value: "US" } }, [
                                    _vm._v("United States")
                                  ])
                                ]
                              )
                            ]
                          ),
                          _vm._v(" "),
                          _c(
                            "p",
                            { staticClass: "mt-4 text-sm text-gray-500" },
                            [
                              _vm._v(
                                "\n                                Select target market\n                            "
                              )
                            ]
                          )
                        ]
                      )
                    ])
                  ]
                ),
                _vm._v(" "),
                _c(
                  "div",
                  {
                    staticClass: "px-4 py-3 bg-gray-20 text-right sm:px-6 mt-2"
                  },
                  [
                    _c(
                      "button",
                      {
                        staticClass:
                          "inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500",
                        attrs: { disabled: _vm.processing, type: "submit" },
                        on: { click: _vm.submit }
                      },
                      [
                        _vm.processing
                          ? _c(
                              "span",
                              [
                                _c("loader", {
                                  staticClass: "text-60",
                                  attrs: { fillColor: "#ffffff" }
                                })
                              ],
                              1
                            )
                          : _c("span", [_vm._v("Submit")])
                      ]
                    )
                  ]
                )
              ])
            ]
          )
        ])
      : _vm._e(),
    _vm._v(" "),
    _vm.displaySubmitSuccess
      ? _c("div", [
          _c(
            "div",
            {
              staticClass:
                "mt-1 px-10 py-5 pb-6 border-2 border-gray-300  border-dashed rounded-md notify-submit-success"
            },
            [
              _c(
                "svg",
                {
                  staticStyle: {
                    "-ms-transform": "rotate(360deg)",
                    "-webkit-transform": "rotate(360deg)",
                    transform: "rotate(360deg)"
                  },
                  attrs: {
                    xmlns: "http://www.w3.org/2000/svg",
                    "xmlns:xlink": "http://www.w3.org/1999/xlink",
                    "aria-hidden": "true",
                    focusable: "false",
                    width: "1em",
                    height: "1em",
                    preserveAspectRatio: "xMidYMid meet",
                    viewBox: "0 0 24 24"
                  }
                },
                [
                  _c("path", {
                    attrs: {
                      d:
                        "M9 19.414l-6.707-6.707l1.414-1.414L9 16.586L20.293 5.293l1.414 1.414",
                      fill: "#3da35a"
                    }
                  })
                ]
              ),
              _vm._v(" "),
              _c(
                "h4",
                {
                  staticClass:
                    "text-2xl text-center text-3xl text-80 font-dark px-4 py-4"
                },
                [_vm._v(" Thank you! ")]
              ),
              _vm._v(" "),
              _c("p", { staticClass: "mt-2 mb-2" }, [
                _vm._v(
                  " Keywords have been successfully submitted. Batch processing in progress"
                )
              ]),
              _vm._v(" "),
              _c("p", { staticClass: "mt-3 mb-4" }, [
                _vm._v(" Batch ID: "),
                _c("span", { staticClass: "font-dark text-xl font-semibold" }, [
                  _vm._v(_vm._s(_vm.batchId))
                ])
              ]),
              _vm._v(" "),
              _c("div", [
                _c("p", [
                  _c(
                    "button",
                    {
                      staticClass:
                        "justify-center py-3 px-5 border border-transparent shadow-sm text-lg font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500",
                      on: { click: _vm.processAnother }
                    },
                    [
                      _vm._v(
                        " \n                        Process another \n                    "
                      )
                    ]
                  )
                ])
              ])
            ]
          )
        ])
      : _vm._e()
  ])
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-468278b2", module.exports)
  }
}

/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(14)
}
var normalizeComponent = __webpack_require__(0)
/* script */
var __vue_script__ = __webpack_require__(16)
/* template */
var __vue_template__ = __webpack_require__(17)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-2ab562ea"
/* moduleIdentifier (server only) */
var __vue_module_identifier__ = null
var Component = normalizeComponent(
  __vue_script__,
  __vue_template__,
  __vue_template_functional__,
  __vue_styles__,
  __vue_scopeId__,
  __vue_module_identifier__
)
Component.options.__file = "resources/js/components/KeywordBatches.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-2ab562ea", Component.options)
  } else {
    hotAPI.reload("data-v-2ab562ea", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 14 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(15);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(2)("79a4482e", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-2ab562ea\",\"scoped\":true,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./KeywordBatches.vue", function() {
     var newContent = require("!!../../../node_modules/css-loader/index.js!../../../node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-2ab562ea\",\"scoped\":true,\"hasInlineConfig\":true}!../../../node_modules/vue-loader/lib/selector.js?type=styles&index=0!./KeywordBatches.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 15 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(1)(false);
// imports


// module
exports.push([module.i, "\n.keyword-batches-wrapper[data-v-2ab562ea] {\n    min-width: 650px;\n    max-width: 100%;\n}\n.keyword-batches-wrapper .batch-label p[data-v-2ab562ea] {\n    font-size: 16px;\n}\n.keyword-batches-wrapper .batch-label span[data-v-2ab562ea] {\n    font-weight: bolder;\n}\n.keyword-batches-wrapper .border-t[data-v-2ab562ea] {\n    border-top: 1px solid blue\n}\n.tab-content[data-v-2ab562ea] {\n    max-height: 0;\n    -webkit-transition: max-height .35s;\n    transition: max-height .35s; \n    width: 100% !important;\n}\n/* :checked - resize to full height */\n.tab input:checked ~ .tab-content[data-v-2ab562ea] {\n    max-height: 100vh;\n}\n/* Label formatting when open */\n.tab input:checked + label[data-v-2ab562ea]{\n    font-size: 1.25rem; /*.text-xl*/\n    padding: 1.25rem; /*.p-5*/\n    border-left-width: 2px; /*.border-l-2*/\n    border-color: #6574cd; /*.border-indigo*/\n    background-color: #f8fafc; /*.bg-gray-100 */\n    color: #6574cd; /*.text-indigo*/\n}\n/* Icon */\n.tab label[data-v-2ab562ea]::after {\nfloat:right;\nright: 0;\ntop: 0;\ndisplay: block;\nwidth: 1.5em;\nheight: 1.5em;\nline-height: 1.5;\nfont-size: 1.25rem;\ntext-align: center;\n-webkit-transition: all .35s;\ntransition: all .35s;\n}\n/* Icon formatting - closed */\n.tab input[type=checkbox] + label[data-v-2ab562ea]::after {\n    content: \"+\";\n    font-weight:bold; /*.font-bold*/\n    border-width: 1px; /*.border*/\n    border-radius: 9999px; /*.rounded-full */\n    border-color: #b8c2cc; /*.border-grey*/\n    top: 40px;\n}\n.tab input[type=radio] + label[data-v-2ab562ea]::after {\ncontent: \"\\25BE\";\nfont-weight:bold; /*.font-bold*/\nborder-width: 1px; /*.border*/\nborder-radius: 9999px; /*.rounded-full */\nborder-color: #b8c2cc; /*.border-grey*/\n}\n/* Icon formatting - open */\n.tab input[type=checkbox]:checked + label[data-v-2ab562ea]::after {\n-webkit-transform: rotate(315deg);\n        transform: rotate(315deg);\nbackground-color: #6574cd; /*.bg-indigo*/\ncolor: #f8fafc; /*.text-grey-lightest*/\n}\n.tab input[type=radio]:checked + label[data-v-2ab562ea]::after {\n-webkit-transform: rotateX(180deg);\n        transform: rotateX(180deg);\nbackground-color: #6574cd; /*.bg-indigo*/\ncolor: #f8fafc; /*.text-grey-lightest*/\n}\n.bg-orange-600[data-v-2ab562ea] { background-color: #dd6b20;\n}\n.bg-green-600[data-v-2ab562ea] { background-color: #38a169;\n}\n", ""]);

// exports


/***/ }),
/* 16 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//

/* harmony default export */ __webpack_exports__["default"] = ({
    name: "KeywordBatches",
    data: function data() {
        return {
            loading: false,
            batches: [],
            errorResponse: {}
        };
    },

    props: {
        card: {
            required: true
        }
    },
    mounted: function mounted() {
        this.loadKeywordBatches();
    },

    methods: {
        loadKeywordBatches: function loadKeywordBatches() {
            var _this = this;

            this.loading = true;
            axios.get('/nova-vendor/' + this.card.component + '/load-keyword-batches').then(function (response) {
                _this.batches = response.data.data;
                console.log(response.data.data);
            }).catch(function (error) {
                _this.errorResponse = error.response.data;
            }).finally(function () {
                _this.loading = false;
            });
        }
    }
});

/***/ }),
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("div", { staticClass: "keyword-batches-wrapper my-5" }, [
    _c("h1", { staticClass: "text-center text-3xl text-80 font-dark pt-4" }, [
      _vm._v("Batch Status")
    ]),
    _vm._v(" "),
    _c("p", { staticClass: "mt-0 text-sm text-center text-gray-500" }, [
      _vm._v("Within the last 48 hours")
    ]),
    _vm._v(" "),
    _vm.loading
      ? _c(
          "div",
          {
            staticClass: "rounded-lg flex items-center justify-center relative"
          },
          [_c("loader", { staticClass: "text-60" })],
          1
        )
      : _c("div", { staticClass: "w-full mx-auto p-8" }, [
          _c("div", { staticClass: "shadow-md" }, [
            Object.entries(_vm.errorResponse).length > 0
              ? _c("div", [
                  _c(
                    "div",
                    {
                      staticClass:
                        "mt-4 mb-4 px-4 py-3 leading-normal text-red-100 bg-red-700 rounded-lg",
                      attrs: { role: "alert" }
                    },
                    [
                      _c("h4", { staticClass: "mt-2 mb-2" }, [
                        _vm._v(" " + _vm._s(_vm.errorResponse.message) + " ")
                      ]),
                      _vm._v(" "),
                      _vm._l(_vm.errorResponse.errors, function(error, index) {
                        return _c("p", { key: index, staticClass: "text-sm" }, [
                          _vm._v(
                            " \n                        => " +
                              _vm._s(error[0]) +
                              " \n                    "
                          )
                        ])
                      })
                    ],
                    2
                  )
                ])
              : _vm._e(),
            _vm._v(" "),
            _vm.batches.length < 1
              ? _c("div", [
                  _c(
                    "div",
                    {
                      staticClass:
                        "px-4 py-3 leading-normal text-gray-100 bg-gray-700 rounded-lg text-center",
                      attrs: { role: "alert" }
                    },
                    [
                      _c("p", { on: { click: _vm.loadKeywordBatches } }, [
                        _vm._v(" No record ")
                      ])
                    ]
                  )
                ])
              : _c(
                  "div",
                  _vm._l(_vm.batches, function(batch, index) {
                    return _c(
                      "div",
                      {
                        key: index,
                        staticClass: "tab w-full overflow-hidden border-t"
                      },
                      [
                        _c("input", {
                          staticClass: "absolute opacity-0 ",
                          attrs: {
                            id: "tab-" + index,
                            type: "checkbox",
                            name: "tabs"
                          }
                        }),
                        _vm._v(" "),
                        _c(
                          "label",
                          {
                            staticClass:
                              "block p-5 leading-normal cursor-pointer batch-label",
                            attrs: { for: "tab-" + index }
                          },
                          [
                            _c("p", [
                              _vm._v("Batch ID: "),
                              _c("span", { staticClass: "font" }, [
                                _vm._v(_vm._s(batch.batch_id))
                              ])
                            ]),
                            _vm._v(" "),
                            _c("p", [
                              _vm._v(
                                "Batch status: \n                            "
                              ),
                              _c(
                                "button",
                                {
                                  staticClass:
                                    "mr-2 text-white p-1 rounded leading-none",
                                  class:
                                    batch.batch_status == "processing"
                                      ? "bg-orange-600"
                                      : "bg-green-600",
                                  attrs: { type: "button" }
                                },
                                [
                                  _vm._v(
                                    "\n                                " +
                                      _vm._s(batch.batch_status) +
                                      "\n                            "
                                  )
                                ]
                              )
                            ])
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "div",
                          {
                            staticClass:
                              "tab-content w-full overflow-hidden border-l-2 bg-gray-100 border-indigo-500 leading-normal"
                          },
                          [
                            _c(
                              "table",
                              {
                                staticClass: "text-left m-4",
                                staticStyle: { "border-collapse": "collapse" }
                              },
                              [
                                _vm._m(0, true),
                                _vm._v(" "),
                                _c(
                                  "tbody",
                                  _vm._l(batch.keywords, function(
                                    keyword,
                                    index2
                                  ) {
                                    return _c(
                                      "tr",
                                      {
                                        key: index2,
                                        staticClass: "hover:bg-blue-lightest"
                                      },
                                      [
                                        _c(
                                          "td",
                                          {
                                            staticClass:
                                              "py-4 px-6 border-b border-grey-light"
                                          },
                                          [_vm._v(_vm._s(keyword.keyword))]
                                        ),
                                        _vm._v(" "),
                                        _c(
                                          "td",
                                          {
                                            staticClass:
                                              "py-4 px-6 border-b border-grey-light text-center"
                                          },
                                          [_vm._v(_vm._s(keyword.status))]
                                        )
                                      ]
                                    )
                                  }),
                                  0
                                )
                              ]
                            )
                          ]
                        )
                      ]
                    )
                  }),
                  0
                )
          ])
        ])
  ])
}
var staticRenderFns = [
  function() {
    var _vm = this
    var _h = _vm.$createElement
    var _c = _vm._self._c || _h
    return _c("thead", [
      _c("tr", [
        _c(
          "th",
          {
            staticClass:
              "py-4 px-6 bg-grey-lighter font-sans font-medium uppercase text-sm text-grey border-b border-grey-light"
          },
          [_vm._v("Keyword")]
        ),
        _vm._v(" "),
        _c(
          "th",
          {
            staticClass:
              "py-4 px-6 bg-grey-lighter font-sans font-medium uppercase text-sm text-grey border-b border-grey-light"
          },
          [_vm._v("Status")]
        )
      ])
    ])
  }
]
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-2ab562ea", module.exports)
  }
}

/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "card",
    { staticClass: "flex flex-col items-center justify-center" },
    [
      _c(
        "div",
        { staticClass: "px-3 py-3" },
        [
          _c("SubmitForm", {
            attrs: { card: _vm.card },
            on: { formSubmitted: _vm.formSubmitted }
          }),
          _vm._v(" "),
          _c("KeywordBatches", {
            ref: "keywordBatch",
            attrs: { card: _vm.card }
          })
        ],
        1
      )
    ]
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-b9bc2c0a", module.exports)
  }
}

/***/ }),
/* 19 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ })
/******/ ]);