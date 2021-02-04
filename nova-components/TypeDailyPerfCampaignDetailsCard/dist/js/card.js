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
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ([
/* 0 */
/***/ (function(module, exports, __webpack_require__) {

__webpack_require__(1);
module.exports = __webpack_require__(6);


/***/ }),
/* 1 */
/***/ (function(module, exports, __webpack_require__) {

Nova.booting(function (Vue, router, store) {
  Vue.component('type-daily-perf-campaign-details-card', __webpack_require__(2));
});

/***/ }),
/* 2 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
var normalizeComponent = __webpack_require__(3)
/* script */
var __vue_script__ = __webpack_require__(4)
/* template */
var __vue_template__ = __webpack_require__(5)
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
/* 3 */
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
/* 4 */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
Object.defineProperty(__webpack_exports__, "__esModule", { value: true });
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__nova_resources_js_components_RevenueDriver_CardQueryFilter__ = __webpack_require__(11);
/* harmony import */ var __WEBPACK_IMPORTED_MODULE_0__nova_resources_js_components_RevenueDriver_CardQueryFilter___default = __webpack_require__.n(__WEBPACK_IMPORTED_MODULE_0__nova_resources_js_components_RevenueDriver_CardQueryFilter__);
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


// import FeedTotals from '/FeedTotals'
/* harmony default export */ __webpack_exports__["default"] = ({
    props: ['card'],
    components: {
        CardQueryFilter: __WEBPACK_IMPORTED_MODULE_0__nova_resources_js_components_RevenueDriver_CardQueryFilter___default.a
        // FeedTotals
    },
    mounted: function mounted() {
        //
    },

    computed: {
        columnChecker: function columnChecker() {
            return [{
                title: 'Type Tags',
                load_from: '/api/v1/type-tags'
            }];
        }
    },
    methods: {
        reloadData: function reloadData(param) {
            this.typeTag = param.columnDataSelected;
            this.startDate = param.startDate;
            this.endDate = param.endDate;
            this.filterOpen = false;
            this.triggerReload++;
        }
    }
});

/***/ }),
/* 5 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "card",
    { staticClass: "flex flex-col items-center justify-center" },
    [
      _c("CardQueryFilter", {
        attrs: { columnChecker: _vm.columnChecker, selectMultiple: false },
        on: { reloadData: _vm.reloadData }
      }),
      _vm._v(" "),
      _c("h1", { staticClass: "textcenter" }, [
        _vm._v(" WORK IN PROGRESS (WIP)  ")
      ]),
      _vm._v(" "),
      _c("div", {
        staticClass:
          "w-full px-5 py-3 my-2 min-w-full max-w-full ds-section box-border"
      })
    ],
    1
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
/* 6 */
/***/ (function(module, exports) {

// removed by extract-text-webpack-plugin

/***/ }),
/* 7 */,
/* 8 */,
/* 9 */,
/* 10 */,
/* 11 */
/***/ (function(module, exports, __webpack_require__) {

var disposed = false
function injectStyle (ssrContext) {
  if (disposed) return
  __webpack_require__(12)
}
var normalizeComponent = __webpack_require__(3)
/* script */
var __vue_script__ = __webpack_require__(17)
/* template */
var __vue_template__ = __webpack_require__(18)
/* template functional */
var __vue_template_functional__ = false
/* styles */
var __vue_styles__ = injectStyle
/* scopeId */
var __vue_scopeId__ = "data-v-0bd84813"
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
Component.options.__file = "nova/resources/js/components/RevenueDriver/CardQueryFilter.vue"

/* hot reload */
if (false) {(function () {
  var hotAPI = require("vue-hot-reload-api")
  hotAPI.install(require("vue"), false)
  if (!hotAPI.compatible) return
  module.hot.accept()
  if (!module.hot.data) {
    hotAPI.createRecord("data-v-0bd84813", Component.options)
  } else {
    hotAPI.reload("data-v-0bd84813", Component.options)
  }
  module.hot.dispose(function (data) {
    disposed = true
  })
})()}

module.exports = Component.exports


/***/ }),
/* 12 */
/***/ (function(module, exports, __webpack_require__) {

// style-loader: Adds some css to the DOM by adding a <style> tag

// load the styles
var content = __webpack_require__(13);
if(typeof content === 'string') content = [[module.i, content, '']];
if(content.locals) module.exports = content.locals;
// add the styles to the DOM
var update = __webpack_require__(15)("fcbbd2a2", content, false, {});
// Hot Module Replacement
if(false) {
 // When the styles change, update the <style> tags
 if(!content.locals) {
   module.hot.accept("!!../../../../node_modules/css-loader/index.js!../../../../../nova-components/TypeDailyPerfCampaignDetailsCard/node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-0bd84813\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../nova-components/TypeDailyPerfCampaignDetailsCard/node_modules/vue-loader/lib/selector.js?type=styles&index=0!./CardQueryFilter.vue", function() {
     var newContent = require("!!../../../../node_modules/css-loader/index.js!../../../../../nova-components/TypeDailyPerfCampaignDetailsCard/node_modules/vue-loader/lib/style-compiler/index.js?{\"vue\":true,\"id\":\"data-v-0bd84813\",\"scoped\":true,\"hasInlineConfig\":true}!../../../../../nova-components/TypeDailyPerfCampaignDetailsCard/node_modules/vue-loader/lib/selector.js?type=styles&index=0!./CardQueryFilter.vue");
     if(typeof newContent === 'string') newContent = [[module.id, newContent, '']];
     update(newContent);
   });
 }
 // When the module is disposed, remove the <style> tags
 module.hot.dispose(function() { update(); });
}

/***/ }),
/* 13 */
/***/ (function(module, exports, __webpack_require__) {

exports = module.exports = __webpack_require__(14)(false);
// imports


// module
exports.push([module.i, "\n.rd__card-query-filter[data-v-0bd84813] {\n     position: absolute;\n     z-index: 9900;\n     min-width: 500px;\n     max-width: 100%; \n     right: 10%;\n     background: #fff;\n}\n.rd__column-selector > input[data-v-0bd84813]  {\n      padding: 6px 10px !important;\n}\n.pagination[data-v-0bd84813] {\n      display: -webkit-box;\n      display: -ms-flexbox;\n      display: flex;\n      margin: 1.25rem 1.25rem 0; \n      border-top: 1px solid #cccccc;\n      -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);\n      box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);\n      -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;\n      transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;\n      transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;\n      transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;\n      font-size: 16px;\n}\n.pagination button[data-v-0bd84813] {\n      -webkit-box-flex: 1;\n          -ms-flex-positive: 1;\n              flex-grow: 1;  \n      padding: 12px 14px;\n}\n.pagination button[data-v-0bd84813]:first-child {\n      border-right: 1px solid #ccc;\n}\n.pagination button[data-v-0bd84813]:hover {\n  cursor: pointer;\n}\n.v-select-container[data-v-0bd84813] {\n  position: relative;\n}\n.selector-btn-holder[data-v-0bd84813] {\n  margin-bottom: 12px;\n}\n.unselector-btn-holder[data-v-0bd84813] {\n     margin-bottom: 12px;\n     margin-left: 10px;\n}\n", ""]);

// exports


/***/ }),
/* 14 */
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
/* 15 */
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

var listToStyles = __webpack_require__(16)

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
/* 16 */
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
/* 17 */
/***/ (function(module, exports, __webpack_require__) {

"use strict";


Object.defineProperty(exports, "__esModule", {
    value: true
});
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

exports.default = {
    name: 'CardQueryFilter',

    data: function data() {
        return {
            columnDataSelected: [],
            startDate: '',
            endDate: '',
            search: '',
            offset: 0,
            limit: 10,
            showSelectAllButton: false,
            filterOpen: false,
            columnData: [],
            queryError: {}
        };
    },

    props: {
        columnChecker: {
            type: Array
        },
        selectMultiple: {
            type: Boolean,
            default: true
            // columnData: {
            //     type: Array,
            //     required: true
            // }
        } },
    mounted: function mounted() {
        this.loadData();
    },

    methods: {
        loadData: function loadData() {
            var _this = this;

            axios.get(this.columnChecker[0].load_from).then(function (response) {
                _this.columnData = response.data.data.type_tags;
            }).catch(function (error) {
                _this.queryError = error.response.data.message;
            });
        },
        toggleFilter: function toggleFilter() {
            this.filterOpen = this.filterOpen == true ? false : true;
        },
        reloadData: function reloadData() {
            this.filterOpen = false;
            this.$emit("reloadData", {
                columnDataSelected: this.columnDataSelected,
                startDate: this.startDate,
                endDate: this.endDate
            });
        },
        startDateChanged: function startDateChanged(data) {
            this.startDate = data;
        },
        endDateChanged: function endDateChanged(data) {
            this.endDate = data;
        },
        selectAll: function selectAll() {
            var _this2 = this;

            var h = this.columnData.filter(function (country) {
                return country.includes(_this2.search);
            });
            if (h.length > 0) {
                this.columnDataSelected = h;
            }
            this.showSelectAllButton = false;
        },
        unselectAll: function unselectAll() {
            this.columnDataSelected = [];
            this.showSelectAllButton = false;
            this.search = 'ol.';
        },
        searching: function searching(query) {
            var _this3 = this;

            this.search = query;
            var f = this.columnData.filter(function (country) {
                return country.includes(_this3.search);
            });
            if (f.length > 0) {
                this.showSelectAllButton = true;
            }
            if (query.length < 1 || f.length < 1) {
                this.showSelectAllButton = false;
            }
        }
    },
    computed: {
        filtered: function filtered() {
            var _this4 = this;

            return this.columnData.filter(function (country) {
                return country.includes(_this4.search);
            });
        },
        paginated: function paginated() {
            return this.filtered.slice(this.offset, this.limit + this.offset);
        },
        hasNextPage: function hasNextPage() {
            var nextOffset = this.offset + 10;
            return Boolean(this.filtered.slice(nextOffset, this.limit + nextOffset).length);
        },
        hasPrevPage: function hasPrevPage() {
            var prevOffset = this.offset - 10;
            return Boolean(this.filtered.slice(prevOffset, this.limit + prevOffset).length);
        }
    }
};

/***/ }),
/* 18 */
/***/ (function(module, exports, __webpack_require__) {

var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c(
    "div",
    { staticClass: "relative h-16 w-full mt-2 mb-2 text-right pt-4 pr-5" },
    [
      _c(
        "button",
        {
          staticClass:
            "rounded active:outline-none active:shadow-outline focus:outline-none focus:shadow-outline",
          attrs: { type: "button" },
          on: { click: _vm.toggleFilter }
        },
        [
          _c(
            "div",
            {
              staticClass:
                "dropdown-trigger h-dropdown-trigger flex items-center cursor-pointer select-none bg-30 px-3 border-2 border-30 rounded"
            },
            [
              _c("icon", {
                staticClass: "cursor-pointer text-60 -mb-1",
                attrs: {
                  type: "filter",
                  viewBox: "0 0 17 17",
                  height: "25",
                  width: "25"
                }
              })
            ],
            1
          )
        ]
      ),
      _vm._v(" "),
      _vm.filterOpen
        ? _c(
            "div",
            {
              staticClass:
                "rd__card-query-filter filter-wrapper p-4 w-60  text-left shadow-lg rounded-lg"
            },
            [
              _vm.columnChecker.length > 0
                ? _c(
                    "div",
                    { staticClass: "v-select-container" },
                    _vm._l(_vm.columnChecker, function(column, index) {
                      return _c(
                        "div",
                        { key: index },
                        [
                          _c("div", { staticClass: "flex" }, [
                            _c("h4", { staticClass: "p-2" }, [
                              _vm._v(
                                " " +
                                  _vm._s(_vm.columnChecker[index].title) +
                                  " "
                              )
                            ]),
                            _vm._v(" "),
                            _vm.showSelectAllButton
                              ? _c(
                                  "div",
                                  { staticClass: "selector-btn-holder" },
                                  [
                                    _c(
                                      "button",
                                      {
                                        staticClass:
                                          "bg-blue-500 hover:bg-blue-300 text-white font-bold py-2 px-4 rounded-lg shadow-sm",
                                        on: { click: _vm.selectAll }
                                      },
                                      [_vm._v("Select all")]
                                    )
                                  ]
                                )
                              : _vm._e(),
                            _vm._v(" "),
                            _vm.columnDataSelected.length > 0
                              ? _c(
                                  "div",
                                  { staticClass: "unselector-btn-holder" },
                                  [
                                    _c(
                                      "button",
                                      {
                                        staticClass:
                                          "bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 rounded-lg shadow-sm",
                                        on: { click: _vm.unselectAll }
                                      },
                                      [_vm._v(" Unselect all")]
                                    )
                                  ]
                                )
                              : _vm._e()
                          ]),
                          _vm._v(" "),
                          _c(
                            "v-select",
                            {
                              staticClass: "rd__column-selector",
                              attrs: {
                                multiple: _vm.selectMultiple ? true : false,
                                options: _vm.paginated,
                                filterable: true
                              },
                              on: { search: _vm.searching },
                              model: {
                                value: _vm.columnDataSelected,
                                callback: function($$v) {
                                  _vm.columnDataSelected = $$v
                                },
                                expression: "columnDataSelected"
                              }
                            },
                            [
                              _c(
                                "li",
                                {
                                  staticClass: "pagination text-grey-600",
                                  attrs: { slot: "list-footer" },
                                  slot: "list-footer"
                                },
                                [
                                  _c(
                                    "button",
                                    {
                                      attrs: { disabled: !_vm.hasPrevPage },
                                      on: {
                                        click: function($event) {
                                          _vm.offset -= 10
                                        }
                                      }
                                    },
                                    [_vm._v("Prev")]
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "button",
                                    {
                                      attrs: { disabled: !_vm.hasNextPage },
                                      on: {
                                        click: function($event) {
                                          _vm.offset += 10
                                        }
                                      }
                                    },
                                    [_vm._v("Next")]
                                  )
                                ]
                              )
                            ]
                          )
                        ],
                        1
                      )
                    }),
                    0
                  )
                : _vm._e(),
              _vm._v(" "),
              _c("div", { staticClass: "flex mt-4" }, [
                _c(
                  "div",
                  { staticClass: "mb-5 flex-grow mr-2" },
                  [
                    _c(
                      "h4",
                      { staticClass: "p-2 text-base text-80 font-bold" },
                      [_vm._v("Start Date")]
                    ),
                    _vm._v(" "),
                    _c("date-time-picker", {
                      attrs: {
                        placeholder: new Date().toDateString(),
                        value: _vm.startDate,
                        dateFormat: "Y-m-d",
                        enableTime: false,
                        altFormat: "Y-m-d"
                      },
                      on: { change: _vm.startDateChanged }
                    })
                  ],
                  1
                ),
                _vm._v(" "),
                _c(
                  "div",
                  { staticClass: "mb-5 flex-grow ml-2" },
                  [
                    _c(
                      "h4",
                      { staticClass: "p-2 text-base text-80 font-bold" },
                      [_vm._v("End Date")]
                    ),
                    _vm._v(" "),
                    _c("date-time-picker", {
                      attrs: {
                        placeholder: new Date().toDateString(),
                        value: _vm.endDate,
                        dateFormat: "Y-m-d",
                        enableTime: false,
                        altFormat: "Y-m-d"
                      },
                      on: { change: _vm.endDateChanged }
                    })
                  ],
                  1
                )
              ]),
              _vm._v(" "),
              _c("div", { staticClass: "flex items-center justify-between" }, [
                _c(
                  "button",
                  {
                    staticClass:
                      "bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow-sm",
                    attrs: { type: "button" },
                    on: { click: _vm.reloadData }
                  },
                  [_vm._v("\n                Load\n            ")]
                ),
                _vm._v(" "),
                _c("p", {
                  staticClass:
                    "inline-block align-baseline font-bold text-sm text-blue hover:text-blue-darker",
                  attrs: { href: "#" }
                })
              ])
            ]
          )
        : _vm._e()
    ]
  )
}
var staticRenderFns = []
render._withStripped = true
module.exports = { render: render, staticRenderFns: staticRenderFns }
if (false) {
  module.hot.accept()
  if (module.hot.data) {
    require("vue-hot-reload-api")      .rerender("data-v-0bd84813", module.exports)
  }
}

/***/ })
/******/ ]);