(self.webpackChunk=self.webpackChunk||[]).push([[519],{7763:(t,r,e)=>{"use strict";function o(t){return o="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},o(t)}function n(t,r){var e=Object.keys(t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(t);r&&(o=o.filter((function(r){return Object.getOwnPropertyDescriptor(t,r).enumerable}))),e.push.apply(e,o)}return e}function i(t){for(var r=1;r<arguments.length;r++){var e=null!=arguments[r]?arguments[r]:{};r%2?n(Object(e),!0).forEach((function(r){s(t,r,e[r])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(e)):n(Object(e)).forEach((function(r){Object.defineProperty(t,r,Object.getOwnPropertyDescriptor(e,r))}))}return t}function s(t,r,e){return(r=function(t){var r=function(t,r){if("object"!==o(t)||null===t)return t;var e=t[Symbol.toPrimitive];if(void 0!==e){var n=e.call(t,r||"default");if("object"!==o(n))return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===r?String:Number)(t)}(t,"string");return"symbol"===o(r)?r:String(r)}(r))in t?Object.defineProperty(t,r,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[r]=e,t}e.d(r,{Z:()=>a});const a={computed:i(i({},(0,e(629).Se)(["isInLoading"])),{},{isLoading:function(){return this.isInLoading(this.waitFor)}}),methods:{startLoading:function(){this.$store.dispatch("startLoadingBy",this.waitFor)},stopLoading:function(){this.$store.dispatch("stopLoadingBy",this.waitFor)}}}},2303:(t,r,e)=>{"use strict";e.d(r,{Z:()=>p});var o=e(629),n=e(1666),i=e(1233);function s(t){return s="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},s(t)}function a(t,r){return function(t){if(Array.isArray(t))return t}(t)||function(t,r){var e=null==t?null:"undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(null!=e){var o,n,i,s,a=[],c=!0,u=!1;try{if(i=(e=e.call(t)).next,0===r){if(Object(e)!==e)return;c=!1}else for(;!(c=(o=i.call(e)).done)&&(a.push(o.value),a.length!==r);c=!0);}catch(t){u=!0,n=t}finally{try{if(!c&&null!=e.return&&(s=e.return(),Object(s)!==s))return}finally{if(u)throw n}}return a}}(t,r)||function(t,r){if(!t)return;if("string"==typeof t)return c(t,r);var e=Object.prototype.toString.call(t).slice(8,-1);"Object"===e&&t.constructor&&(e=t.constructor.name);if("Map"===e||"Set"===e)return Array.from(t);if("Arguments"===e||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(e))return c(t,r)}(t,r)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function c(t,r){(null==r||r>t.length)&&(r=t.length);for(var e=0,o=new Array(r);e<r;e++)o[e]=t[e];return o}function u(t,r){var e=Object.keys(t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(t);r&&(o=o.filter((function(r){return Object.getOwnPropertyDescriptor(t,r).enumerable}))),e.push.apply(e,o)}return e}function l(t){for(var r=1;r<arguments.length;r++){var e=null!=arguments[r]?arguments[r]:{};r%2?u(Object(e),!0).forEach((function(r){f(t,r,e[r])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(e)):u(Object(e)).forEach((function(r){Object.defineProperty(t,r,Object.getOwnPropertyDescriptor(e,r))}))}return t}function f(t,r,e){return(r=function(t){var r=function(t,r){if("object"!==s(t)||null===t)return t;var e=t[Symbol.toPrimitive];if(void 0!==e){var o=e.call(t,r||"default");if("object"!==s(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===r?String:Number)(t)}(t,"string");return"symbol"===s(r)?r:String(r)}(r))in t?Object.defineProperty(t,r,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[r]=e,t}const p={data:function(){var t=this;return{rules:{required:function(r){return!!r||0===r||t.$t("rules.required")},email:function(r){return/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(r)||t.$t("rules.email")},confirmed:function(r){return function(e){return r==e||t.$t("rules.confirmed")}},gte:function(r){return function(e){return e&&e.length>=r||t.$t("rules.gte",{length:r})}}}}},computed:l({},(0,o.rn)(["errors"])),methods:l(l({},(0,o.nv)(["setErrors","unsetError"])),{},{syncErrors:function(t){var r=this;if(void 0!==t)if(void 0!==t.response&&t.hasOwnProperty("response"))if(400===t.response.status||401===t.response.status)i.Z.commit("LOGGED_OUT"),n.Z.push({name:"Login"});else if(403===t.response.status)n.Z.push({name:"Forbidden"});else if(404===t.response.status)n.Z.push({name:"NotFound"});else if(419===t.response.status)n.Z.push({name:"ExpiredSession"});else if(t.response.hasOwnProperty("data")&&t.response.data.hasOwnProperty("errors")){this.setErrors(t.response.data.errors);for(var e=0,o=Object.entries(this.errors);e<o.length;e++){var s=o[e];void 0!==this.$refs[s]&&(this.$refs[s].valid=!1)}setTimeout((function(){r.focusFirstErrorInput()}),500)}else n.Z.push({name:"NotFound"});else console.log(t)},clearErrors:function(){this.setErrors({})},clearError:function(t){this.hasErrors(t)&&(this.unsetError(t),this.$refs[t].valid=!0)},focusFirstErrorInput:function(){for(var t=0,r=Object.entries(this.form);t<r.length;t++){var e=a(r[t],2),o=e[0];e[1];if(void 0!==this.$refs[o]&&!this.$refs[o].valid){this.$refs[o].focus();break}}},hasErrors:function(t){return this.errors.hasOwnProperty(t)},getError:function(t){return this.hasErrors(t)?this.errors[t][0]:null},getErrors:function(t){return this.hasErrors(t)?this.errors[t]:null}})}},8552:(t,r,e)=>{var o=e(852)(e(5639),"DataView");t.exports=o},7071:(t,r,e)=>{var o=e(852)(e(5639),"Map");t.exports=o},3818:(t,r,e)=>{var o=e(852)(e(5639),"Promise");t.exports=o},8525:(t,r,e)=>{var o=e(852)(e(5639),"Set");t.exports=o},2705:(t,r,e)=>{var o=e(5639).Symbol;t.exports=o},577:(t,r,e)=>{var o=e(852)(e(5639),"WeakMap");t.exports=o},4239:(t,r,e)=>{var o=e(2705),n=e(9607),i=e(2333),s=o?o.toStringTag:void 0;t.exports=function(t){return null==t?void 0===t?"[object Undefined]":"[object Null]":s&&s in Object(t)?n(t):i(t)}},9454:(t,r,e)=>{var o=e(4239),n=e(7005);t.exports=function(t){return n(t)&&"[object Arguments]"==o(t)}},8458:(t,r,e)=>{var o=e(3560),n=e(5346),i=e(3218),s=e(346),a=/^\[object .+?Constructor\]$/,c=Function.prototype,u=Object.prototype,l=c.toString,f=u.hasOwnProperty,p=RegExp("^"+l.call(f).replace(/[\\^$.*+?()[\]{}|]/g,"\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g,"$1.*?")+"$");t.exports=function(t){return!(!i(t)||n(t))&&(o(t)?p:a).test(s(t))}},8749:(t,r,e)=>{var o=e(4239),n=e(1780),i=e(7005),s={};s["[object Float32Array]"]=s["[object Float64Array]"]=s["[object Int8Array]"]=s["[object Int16Array]"]=s["[object Int32Array]"]=s["[object Uint8Array]"]=s["[object Uint8ClampedArray]"]=s["[object Uint16Array]"]=s["[object Uint32Array]"]=!0,s["[object Arguments]"]=s["[object Array]"]=s["[object ArrayBuffer]"]=s["[object Boolean]"]=s["[object DataView]"]=s["[object Date]"]=s["[object Error]"]=s["[object Function]"]=s["[object Map]"]=s["[object Number]"]=s["[object Object]"]=s["[object RegExp]"]=s["[object Set]"]=s["[object String]"]=s["[object WeakMap]"]=!1,t.exports=function(t){return i(t)&&n(t.length)&&!!s[o(t)]}},280:(t,r,e)=>{var o=e(5726),n=e(6916),i=Object.prototype.hasOwnProperty;t.exports=function(t){if(!o(t))return n(t);var r=[];for(var e in Object(t))i.call(t,e)&&"constructor"!=e&&r.push(e);return r}},7518:t=>{t.exports=function(t){return function(r){return t(r)}}},4429:(t,r,e)=>{var o=e(5639)["__core-js_shared__"];t.exports=o},1957:(t,r,e)=>{var o="object"==typeof e.g&&e.g&&e.g.Object===Object&&e.g;t.exports=o},852:(t,r,e)=>{var o=e(8458),n=e(7801);t.exports=function(t,r){var e=n(t,r);return o(e)?e:void 0}},9607:(t,r,e)=>{var o=e(2705),n=Object.prototype,i=n.hasOwnProperty,s=n.toString,a=o?o.toStringTag:void 0;t.exports=function(t){var r=i.call(t,a),e=t[a];try{t[a]=void 0;var o=!0}catch(t){}var n=s.call(t);return o&&(r?t[a]=e:delete t[a]),n}},4160:(t,r,e)=>{var o=e(8552),n=e(7071),i=e(3818),s=e(8525),a=e(577),c=e(4239),u=e(346),l="[object Map]",f="[object Promise]",p="[object Set]",d="[object WeakMap]",b="[object DataView]",y=u(o),m=u(n),v=u(i),h=u(s),g=u(a),j=c;(o&&j(new o(new ArrayBuffer(1)))!=b||n&&j(new n)!=l||i&&j(i.resolve())!=f||s&&j(new s)!=p||a&&j(new a)!=d)&&(j=function(t){var r=c(t),e="[object Object]"==r?t.constructor:void 0,o=e?u(e):"";if(o)switch(o){case y:return b;case m:return l;case v:return f;case h:return p;case g:return d}return r}),t.exports=j},7801:t=>{t.exports=function(t,r){return null==t?void 0:t[r]}},5346:(t,r,e)=>{var o,n=e(4429),i=(o=/[^.]+$/.exec(n&&n.keys&&n.keys.IE_PROTO||""))?"Symbol(src)_1."+o:"";t.exports=function(t){return!!i&&i in t}},5726:t=>{var r=Object.prototype;t.exports=function(t){var e=t&&t.constructor;return t===("function"==typeof e&&e.prototype||r)}},6916:(t,r,e)=>{var o=e(5569)(Object.keys,Object);t.exports=o},1167:(t,r,e)=>{t=e.nmd(t);var o=e(1957),n=r&&!r.nodeType&&r,i=n&&t&&!t.nodeType&&t,s=i&&i.exports===n&&o.process,a=function(){try{var t=i&&i.require&&i.require("util").types;return t||s&&s.binding&&s.binding("util")}catch(t){}}();t.exports=a},2333:t=>{var r=Object.prototype.toString;t.exports=function(t){return r.call(t)}},5569:t=>{t.exports=function(t,r){return function(e){return t(r(e))}}},5639:(t,r,e)=>{var o=e(1957),n="object"==typeof self&&self&&self.Object===Object&&self,i=o||n||Function("return this")();t.exports=i},346:t=>{var r=Function.prototype.toString;t.exports=function(t){if(null!=t){try{return r.call(t)}catch(t){}try{return t+""}catch(t){}}return""}},5694:(t,r,e)=>{var o=e(9454),n=e(7005),i=Object.prototype,s=i.hasOwnProperty,a=i.propertyIsEnumerable,c=o(function(){return arguments}())?o:function(t){return n(t)&&s.call(t,"callee")&&!a.call(t,"callee")};t.exports=c},1469:t=>{var r=Array.isArray;t.exports=r},8612:(t,r,e)=>{var o=e(3560),n=e(1780);t.exports=function(t){return null!=t&&n(t.length)&&!o(t)}},4144:(t,r,e)=>{t=e.nmd(t);var o=e(5639),n=e(5062),i=r&&!r.nodeType&&r,s=i&&t&&!t.nodeType&&t,a=s&&s.exports===i?o.Buffer:void 0,c=(a?a.isBuffer:void 0)||n;t.exports=c},1609:(t,r,e)=>{var o=e(280),n=e(4160),i=e(5694),s=e(1469),a=e(8612),c=e(4144),u=e(5726),l=e(6719),f=Object.prototype.hasOwnProperty;t.exports=function(t){if(null==t)return!0;if(a(t)&&(s(t)||"string"==typeof t||"function"==typeof t.splice||c(t)||l(t)||i(t)))return!t.length;var r=n(t);if("[object Map]"==r||"[object Set]"==r)return!t.size;if(u(t))return!o(t).length;for(var e in t)if(f.call(t,e))return!1;return!0}},3560:(t,r,e)=>{var o=e(4239),n=e(3218);t.exports=function(t){if(!n(t))return!1;var r=o(t);return"[object Function]"==r||"[object GeneratorFunction]"==r||"[object AsyncFunction]"==r||"[object Proxy]"==r}},1780:t=>{t.exports=function(t){return"number"==typeof t&&t>-1&&t%1==0&&t<=9007199254740991}},3218:t=>{t.exports=function(t){var r=typeof t;return null!=t&&("object"==r||"function"==r)}},7005:t=>{t.exports=function(t){return null!=t&&"object"==typeof t}},6719:(t,r,e)=>{var o=e(8749),n=e(7518),i=e(1167),s=i&&i.isTypedArray,a=s?n(s):o;t.exports=a},5062:t=>{t.exports=function(){return!1}},4122:(t,r,e)=>{"use strict";e.d(r,{Z:()=>n});const o={name:"Modal",props:{dialog:{type:Boolean,default:!1,required:!0},width:{type:[String,Number],required:!0}},data:function(){return{dialogWidth:"".concat(this.width,"px")}},computed:{dialogState:function(){return this.dialog}}};const n=(0,e(1900).Z)(o,(function(){var t=this,r=t._self._c;return r("v-dialog",{attrs:{persistent:"","max-width":t.dialogWidth},model:{value:t.dialogState,callback:function(r){t.dialogState=r},expression:"dialogState"}},[r("v-card",[t._t("default")],2)],1)}),[],!1,null,null,null).exports},1633:(t,r,e)=>{"use strict";e.d(r,{Z:()=>o});const o=(0,e(1900).Z)({},(function(){return(0,this._self._c)("v-card-text",[this._t("default")],2)}),[],!1,null,null,null).exports},6669:(t,r,e)=>{"use strict";e.d(r,{Z:()=>n});const o={name:"ModalFooter",props:{isLoading:{type:Boolean,required:!0}}};const n=(0,e(1900).Z)(o,(function(){var t=this,r=t._self._c;return r("v-card-actions",{staticClass:"pb-6 pr-6"},[r("v-spacer"),t._v(" "),r("v-progress-circular",{directives:[{name:"show",rawName:"v-show",value:t.isLoading,expression:"isLoading"}],attrs:{indeterminate:""}}),t._v(" "),t._t("default")],2)}),[],!1,null,null,null).exports},9034:(t,r,e)=>{"use strict";e.d(r,{Z:()=>o});const o=(0,e(1900).Z)({},(function(){var t=this._self._c;return t("v-card-title",[t("span",{staticClass:"title font-weight-light"},[this._t("default")],2)])}),[],!1,null,null,null).exports},6519:(t,r,e)=>{"use strict";e.r(r),e.d(r,{default:()=>x});var o=e(629),n=e(249),i=e(2303),s=e(7763),a=e(4122),c=e(9034),u=e(1633),l=e(6669);function f(t){return f="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},f(t)}function p(t,r){var e=Object.keys(t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(t);r&&(o=o.filter((function(r){return Object.getOwnPropertyDescriptor(t,r).enumerable}))),e.push.apply(e,o)}return e}function d(t){for(var r=1;r<arguments.length;r++){var e=null!=arguments[r]?arguments[r]:{};r%2?p(Object(e),!0).forEach((function(r){b(t,r,e[r])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(e)):p(Object(e)).forEach((function(r){Object.defineProperty(t,r,Object.getOwnPropertyDescriptor(e,r))}))}return t}function b(t,r,e){return(r=function(t){var r=function(t,r){if("object"!==f(t)||null===t)return t;var e=t[Symbol.toPrimitive];if(void 0!==e){var o=e.call(t,r||"default");if("object"!==f(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===r?String:Number)(t)}(t,"string");return"symbol"===f(r)?r:String(r)}(r))in t?Object.defineProperty(t,r,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[r]=e,t}const y={name:"DeletePortfolioModal",components:{Modal:a.Z,ModalHeading:c.Z,ModalBody:u.Z,ModalFooter:l.Z},mixins:[s.Z],data:function(){return{waitFor:"destroy_portfolio",id:null}},computed:d({},(0,o.rn)(["showModal"])),mounted:function(){var t=this;document.addEventListener("keydown",(function(r){27==r.keyCode&&t.close()}))},methods:d(d({},(0,o.nv)(["destroyPortfolio","setShowModal"])),{},{open:function(t){this.id=t,this.setShowModal(!0)},close:function(){this.id=null,this.setShowModal(!1)},submit:function(){var t=this;this.startLoading(),this.destroyPortfolio(this.id).then((function(r){(0,n.IE)(t.$t("Portfolio has been successfully deleted.")),t.$router.push({name:"Home"})})).catch((function(t){(0,n.O1)(t)})).finally((function(){t.stopLoading(),t.close()}))}})};var m=e(1900);const v=(0,m.Z)(y,(function(){var t=this,r=t._self._c;return r("modal",{attrs:{width:460,dialog:t.showModal}},[r("modal-heading",[t._v("\n    "+t._s(t.$t("Delete Portfolio"))+"\n  ")]),t._v(" "),r("modal-body",[r("div",{staticClass:"text-xs-center"},[t._v("\n      "+t._s(t.$t("Are you sure you want to delete this portfolio?"))+"\n    ")])]),t._v(" "),r("modal-footer",{attrs:{"is-loading":t.isLoading}},[r("v-btn",{staticClass:"btn-close",attrs:{disabled:t.isLoading},on:{click:t.close}},[t._v("\n      "+t._s(t.$t("Close"))+"\n    ")]),t._v(" "),r("v-btn",{staticClass:"btn-warning",attrs:{disabled:t.isLoading},on:{click:t.submit}},[t._v("\n      "+t._s(t.$t("Delete"))+"\n    ")])],1)],1)}),[],!1,null,null,null).exports;function h(t){return h="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},h(t)}function g(t,r){var e=Object.keys(t);if(Object.getOwnPropertySymbols){var o=Object.getOwnPropertySymbols(t);r&&(o=o.filter((function(r){return Object.getOwnPropertyDescriptor(t,r).enumerable}))),e.push.apply(e,o)}return e}function j(t){for(var r=1;r<arguments.length;r++){var e=null!=arguments[r]?arguments[r]:{};r%2?g(Object(e),!0).forEach((function(r){O(t,r,e[r])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(e)):g(Object(e)).forEach((function(r){Object.defineProperty(t,r,Object.getOwnPropertyDescriptor(e,r))}))}return t}function O(t,r,e){return(r=function(t){var r=function(t,r){if("object"!==h(t)||null===t)return t;var e=t[Symbol.toPrimitive];if(void 0!==e){var o=e.call(t,r||"default");if("object"!==h(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return("string"===r?String:Number)(t)}(t,"string");return"symbol"===h(r)?r:String(r)}(r))in t?Object.defineProperty(t,r,{value:e,enumerable:!0,configurable:!0,writable:!0}):t[r]=e,t}const w={name:"EditPortfolio",components:{FormErrors:e(7460).Z,DeletePortfolioModal:v},mixins:[i.Z,s.Z],props:{portfolio:{type:Object,required:!0}},data:function(){return{waitFor:"update_portfolio",form:{name:this.portfolio.name,currency:this.portfolio.currency,commission:this.portfolio.commission,filtered:this.portfolio.filtered},valid:!0}},computed:j({},(0,o.Se)(["portfoliosCount"])),methods:j(j({},(0,o.nv)(["updatePortfolio"])),{},{submit:function(){var t=this;this.$refs.form.validate()?(this.startLoading(),this.updatePortfolio({id:this.$route.params.id,form:this.form}).then((function(r){t.clearErrors(),(0,n.IE)(t.$t("Your portfolio is successfully updated.")),t.$router.push({name:"Home"})})).catch((function(r){t.syncErrors(r)})).finally((function(){t.stopLoading()}))):this.focusFirstErrorInput()}})};const x=(0,m.Z)(w,(function(){var t=this,r=t._self._c;return r("v-row",{attrs:{align:"center",justify:"center"}},[r("delete-portfolio-modal",{ref:"deletePortfolioModal"}),t._v(" "),r("v-col",{attrs:{cols:"12",sm:"8",md:"4"}},[r("v-card",[r("v-card-title",[r("span",{staticClass:"title font-weight-light"},[t._v(t._s(t.$t("Update Portfolio")))])]),t._v(" "),r("v-card-text",[r("v-form",{ref:"form",attrs:{"lazy-validation":""},nativeOn:{keyup:function(r){return!r.type.indexOf("key")&&t._k(r.keyCode,"enter",13,r.key,"Enter")?null:t.submit.apply(null,arguments)},keydown:function(r){return t.clearError(r.target.name)}},model:{value:t.valid,callback:function(r){t.valid=r},expression:"valid"}},[r("form-errors",{attrs:{errors:t.errors}}),t._v(" "),r("v-text-field",{ref:"name",attrs:{id:"name",type:"text",name:"name",filled:"",autofocus:"","prepend-inner-icon":"text_fields",disabled:t.isLoading,label:t.$t("Portfolio Name"),rules:[t.rules.required],"error-messages":t.getError("name")},model:{value:t.form.name,callback:function(r){t.$set(t.form,"name",r)},expression:"form.name"}}),t._v(" "),r("v-select",{ref:"currency",attrs:{id:"currency",type:"select",name:"currency",filled:"","prepend-inner-icon":"money",disabled:t.isLoading,items:["TRY"],label:t.$t("Currency"),rules:[t.rules.required],"error-messages":t.getError("currency")},model:{value:t.form.currency,callback:function(r){t.$set(t.form,"currency",r)},expression:"form.currency"}}),t._v(" "),r("v-text-field",{ref:"commission",attrs:{id:"commission",type:"number",name:"commission",filled:"","prepend-inner-icon":"donut_large",step:"0.0001",disabled:t.isLoading,label:t.$t("Enter Commission Rate"),rules:[t.rules.required],"error-messages":t.getError("commission"),hint:t.$t("for_example",{example:t.$t("Garanti Bank: 0.188")})},model:{value:t.form.commission,callback:function(r){t.$set(t.form,"commission",r)},expression:"form.commission"}}),t._v(" "),r("v-checkbox",{attrs:{label:t.$t("Filter stocks that are no lot")},model:{value:t.form.filtered,callback:function(r){t.$set(t.form,"filtered",r)},expression:"form.filtered"}})],1)],1),t._v(" "),r("v-card-actions",{staticClass:"pb-4 px-4"},[t.portfoliosCount>1?r("v-btn",{staticClass:"btn-warning",on:{click:function(r){return t.$refs.deletePortfolioModal.open(t.$route.params.id)}}},[t._v("\n          "+t._s(t.$t("Delete"))+"\n        ")]):t._e(),t._v(" "),r("v-spacer"),t._v(" "),r("v-progress-circular",{directives:[{name:"show",rawName:"v-show",value:t.isLoading,expression:"isLoading"}],attrs:{indeterminate:""}}),t._v(" "),r("v-btn",{staticClass:"btn-close",attrs:{to:"/",disabled:t.isLoading}},[t._v("\n          "+t._s(t.$t("Close"))+"\n        ")]),t._v(" "),r("v-btn",{staticClass:"btn-action",attrs:{disabled:t.isLoading},on:{click:t.submit}},[t._v("\n          "+t._s(t.$t("Update"))+"\n        ")])],1)],1)],1)],1)}),[],!1,null,null,null).exports},7460:(t,r,e)=>{"use strict";e.d(r,{Z:()=>s});var o=e(1609),n=e.n(o);const i={name:"FormErrors",props:{errors:{type:Object,default:function(){return{}},required:!1}},data:function(){return{any:null}},watch:{errors:function(t,r){this.any=!n()(t)}}};const s=(0,e(1900).Z)(i,(function(){var t=this,r=t._self._c;return r("v-alert",{attrs:{text:"",dismissible:"",type:"error",border:"left",value:t.any}},t._l(t.errors,(function(e){return r("dl",{key:e.id},t._l(e,(function(e){return r("dd",{key:e.id},[t._v("\n      "+t._s(e)+"\n    ")])})),0)})),0)}),[],!1,null,null,null).exports}}]);