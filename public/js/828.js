(self.webpackChunk=self.webpackChunk||[]).push([[828],{4101:(t,e,r)=>{"use strict";function n(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function o(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?n(Object(r),!0).forEach((function(e){s(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):n(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function s(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}r.d(e,{Z:()=>i});const i={data:function(){return{waitFor:""}},computed:o(o({},(0,r(629).Se)(["isInLoading"])),{},{isLoading:function(){return this.isInLoading(this.waitFor)}}),methods:{startLoading:function(){this.$store.dispatch("startLoadingBy",this.waitFor)},stopLoading:function(){this.$store.dispatch("stopLoadingBy",this.waitFor)}}}},4935:(t,e,r)=>{"use strict";r.d(e,{Z:()=>f});var n=r(629),o=r(8593),s=r(9780);function i(t,e){return function(t){if(Array.isArray(t))return t}(t)||function(t,e){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(t)))return;var r=[],n=!0,o=!1,s=void 0;try{for(var i,a=t[Symbol.iterator]();!(n=(i=a.next()).done)&&(r.push(i.value),!e||r.length!==e);n=!0);}catch(t){o=!0,s=t}finally{try{n||null==a.return||a.return()}finally{if(o)throw s}}return r}(t,e)||function(t,e){if(!t)return;if("string"==typeof t)return a(t,e);var r=Object.prototype.toString.call(t).slice(8,-1);"Object"===r&&t.constructor&&(r=t.constructor.name);if("Map"===r||"Set"===r)return Array.from(t);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return a(t,e)}(t,e)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function a(t,e){(null==e||e>t.length)&&(e=t.length);for(var r=0,n=new Array(e);r<e;r++)n[r]=t[r];return n}function c(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function l(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?c(Object(r),!0).forEach((function(e){u(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):c(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function u(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}const f={data:function(){var t=this;return{rules:{required:function(e){return!!e||0===e||t.$t("rules.required")},email:function(e){return/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(e)||t.$t("rules.email")},confirmed:function(e){return function(r){return e==r||t.$t("rules.confirmed")}},gte:function(e){return function(r){return r&&r.length>=e||t.$t("rules.gte",{length:e})}}}}},computed:l({},(0,n.rn)(["errors"])),methods:l(l({},(0,n.nv)(["setErrors","unsetError"])),{},{syncErrors:function(t){var e=this;if(void 0!==t)if(void 0!==t.response&&t.hasOwnProperty("response"))if(400===t.response.status||401===t.response.status)s.Z.commit("LOGGED_OUT"),o.Z.push({name:"Login"});else if(403===t.response.status)o.Z.push({name:"Forbidden"});else if(404===t.response.status)o.Z.push({name:"NotFound"});else if(419===t.response.status)o.Z.push({name:"ExpiredSession"});else if(t.response.hasOwnProperty("data")&&t.response.data.hasOwnProperty("errors")){this.setErrors(t.response.data.errors);for(var r=0,n=Object.entries(this.errors);r<n.length;r++){var i=n[r];void 0!==this.$refs[i]&&(this.$refs[i].valid=!1)}setTimeout((function(){e.focusFirstErrorInput()}),500)}else o.Z.push({name:"NotFound"});else console.log(t)},clearErrors:function(){this.setErrors({})},clearError:function(t){this.hasErrors(t)&&(this.unsetError(t),this.$refs[t].valid=!0)},focusFirstErrorInput:function(){for(var t=0,e=Object.entries(this.form);t<e.length;t++){var r=i(e[t],2),n=r[0];r[1];if(void 0!==this.$refs[n]&&!this.$refs[n].valid){this.$refs[n].focus();break}}},hasErrors:function(t){return this.errors.hasOwnProperty(t)},getError:function(t){return this.hasErrors(t)?this.errors[t][0]:null},getErrors:function(t){return this.hasErrors(t)?this.errors[t]:null}})}},4276:(t,e,r)=>{"use strict";r.d(e,{Z:()=>s});var n=r(3645),o=r.n(n)()((function(t){return t[1]}));o.push([t.id,"table.v-data-table thead tr:last-child th[data-v-23c564a4]{font-size:.9rem!important}",""]);const s=o},8552:(t,e,r)=>{var n=r(852)(r(5639),"DataView");t.exports=n},7071:(t,e,r)=>{var n=r(852)(r(5639),"Map");t.exports=n},3818:(t,e,r)=>{var n=r(852)(r(5639),"Promise");t.exports=n},8525:(t,e,r)=>{var n=r(852)(r(5639),"Set");t.exports=n},2705:(t,e,r)=>{var n=r(5639).Symbol;t.exports=n},577:(t,e,r)=>{var n=r(852)(r(5639),"WeakMap");t.exports=n},4239:(t,e,r)=>{var n=r(2705),o=r(9607),s=r(2333),i=n?n.toStringTag:void 0;t.exports=function(t){return null==t?void 0===t?"[object Undefined]":"[object Null]":i&&i in Object(t)?o(t):s(t)}},9454:(t,e,r)=>{var n=r(4239),o=r(7005);t.exports=function(t){return o(t)&&"[object Arguments]"==n(t)}},8458:(t,e,r)=>{var n=r(3560),o=r(5346),s=r(3218),i=r(346),a=/^\[object .+?Constructor\]$/,c=Function.prototype,l=Object.prototype,u=c.toString,f=l.hasOwnProperty,d=RegExp("^"+u.call(f).replace(/[\\^$.*+?()[\]{}|]/g,"\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g,"$1.*?")+"$");t.exports=function(t){return!(!s(t)||o(t))&&(n(t)?d:a).test(i(t))}},8749:(t,e,r)=>{var n=r(4239),o=r(1780),s=r(7005),i={};i["[object Float32Array]"]=i["[object Float64Array]"]=i["[object Int8Array]"]=i["[object Int16Array]"]=i["[object Int32Array]"]=i["[object Uint8Array]"]=i["[object Uint8ClampedArray]"]=i["[object Uint16Array]"]=i["[object Uint32Array]"]=!0,i["[object Arguments]"]=i["[object Array]"]=i["[object ArrayBuffer]"]=i["[object Boolean]"]=i["[object DataView]"]=i["[object Date]"]=i["[object Error]"]=i["[object Function]"]=i["[object Map]"]=i["[object Number]"]=i["[object Object]"]=i["[object RegExp]"]=i["[object Set]"]=i["[object String]"]=i["[object WeakMap]"]=!1,t.exports=function(t){return s(t)&&o(t.length)&&!!i[n(t)]}},280:(t,e,r)=>{var n=r(5726),o=r(6916),s=Object.prototype.hasOwnProperty;t.exports=function(t){if(!n(t))return o(t);var e=[];for(var r in Object(t))s.call(t,r)&&"constructor"!=r&&e.push(r);return e}},7518:t=>{t.exports=function(t){return function(e){return t(e)}}},4429:(t,e,r)=>{var n=r(5639)["__core-js_shared__"];t.exports=n},1957:(t,e,r)=>{var n="object"==typeof r.g&&r.g&&r.g.Object===Object&&r.g;t.exports=n},852:(t,e,r)=>{var n=r(8458),o=r(7801);t.exports=function(t,e){var r=o(t,e);return n(r)?r:void 0}},9607:(t,e,r)=>{var n=r(2705),o=Object.prototype,s=o.hasOwnProperty,i=o.toString,a=n?n.toStringTag:void 0;t.exports=function(t){var e=s.call(t,a),r=t[a];try{t[a]=void 0;var n=!0}catch(t){}var o=i.call(t);return n&&(e?t[a]=r:delete t[a]),o}},4160:(t,e,r)=>{var n=r(8552),o=r(7071),s=r(3818),i=r(8525),a=r(577),c=r(4239),l=r(346),u="[object Map]",f="[object Promise]",d="[object Set]",p="[object WeakMap]",b="[object DataView]",v=l(n),y=l(o),m=l(s),h=l(i),g=l(a),_=c;(n&&_(new n(new ArrayBuffer(1)))!=b||o&&_(new o)!=u||s&&_(s.resolve())!=f||i&&_(new i)!=d||a&&_(new a)!=p)&&(_=function(t){var e=c(t),r="[object Object]"==e?t.constructor:void 0,n=r?l(r):"";if(n)switch(n){case v:return b;case y:return u;case m:return f;case h:return d;case g:return p}return e}),t.exports=_},7801:t=>{t.exports=function(t,e){return null==t?void 0:t[e]}},5346:(t,e,r)=>{var n,o=r(4429),s=(n=/[^.]+$/.exec(o&&o.keys&&o.keys.IE_PROTO||""))?"Symbol(src)_1."+n:"";t.exports=function(t){return!!s&&s in t}},5726:t=>{var e=Object.prototype;t.exports=function(t){var r=t&&t.constructor;return t===("function"==typeof r&&r.prototype||e)}},6916:(t,e,r)=>{var n=r(5569)(Object.keys,Object);t.exports=n},1167:(t,e,r)=>{t=r.nmd(t);var n=r(1957),o=e&&!e.nodeType&&e,s=o&&t&&!t.nodeType&&t,i=s&&s.exports===o&&n.process,a=function(){try{var t=s&&s.require&&s.require("util").types;return t||i&&i.binding&&i.binding("util")}catch(t){}}();t.exports=a},2333:t=>{var e=Object.prototype.toString;t.exports=function(t){return e.call(t)}},5569:t=>{t.exports=function(t,e){return function(r){return t(e(r))}}},5639:(t,e,r)=>{var n=r(1957),o="object"==typeof self&&self&&self.Object===Object&&self,s=n||o||Function("return this")();t.exports=s},346:t=>{var e=Function.prototype.toString;t.exports=function(t){if(null!=t){try{return e.call(t)}catch(t){}try{return t+""}catch(t){}}return""}},5694:(t,e,r)=>{var n=r(9454),o=r(7005),s=Object.prototype,i=s.hasOwnProperty,a=s.propertyIsEnumerable,c=n(function(){return arguments}())?n:function(t){return o(t)&&i.call(t,"callee")&&!a.call(t,"callee")};t.exports=c},1469:t=>{var e=Array.isArray;t.exports=e},8612:(t,e,r)=>{var n=r(3560),o=r(1780);t.exports=function(t){return null!=t&&o(t.length)&&!n(t)}},4144:(t,e,r)=>{t=r.nmd(t);var n=r(5639),o=r(5062),s=e&&!e.nodeType&&e,i=s&&t&&!t.nodeType&&t,a=i&&i.exports===s?n.Buffer:void 0,c=(a?a.isBuffer:void 0)||o;t.exports=c},8367:(t,e,r)=>{var n=r(280),o=r(4160),s=r(5694),i=r(1469),a=r(8612),c=r(4144),l=r(5726),u=r(6719),f=Object.prototype.hasOwnProperty;t.exports=function(t){if(null==t)return!0;if(a(t)&&(i(t)||"string"==typeof t||"function"==typeof t.splice||c(t)||u(t)||s(t)))return!t.length;var e=o(t);if("[object Map]"==e||"[object Set]"==e)return!t.size;if(l(t))return!n(t).length;for(var r in t)if(f.call(t,r))return!1;return!0}},3560:(t,e,r)=>{var n=r(4239),o=r(3218);t.exports=function(t){if(!o(t))return!1;var e=n(t);return"[object Function]"==e||"[object GeneratorFunction]"==e||"[object AsyncFunction]"==e||"[object Proxy]"==e}},1780:t=>{t.exports=function(t){return"number"==typeof t&&t>-1&&t%1==0&&t<=9007199254740991}},3218:t=>{t.exports=function(t){var e=typeof t;return null!=t&&("object"==e||"function"==e)}},7005:t=>{t.exports=function(t){return null!=t&&"object"==typeof t}},6719:(t,e,r)=>{var n=r(8749),o=r(7518),s=r(1167),i=s&&s.isTypedArray,a=i?o(i):n;t.exports=a},5062:t=>{t.exports=function(){return!1}},5504:(t,e,r)=>{"use strict";r.d(e,{Z:()=>o});const n={props:{dialog:{type:Boolean,required:!0},width:{type:[String,Number],required:!0}},data:function(){return{dialogWidth:"".concat(this.width,"px")}}};const o=(0,r(1900).Z)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-dialog",{attrs:{persistent:"","max-width":t.dialogWidth},model:{value:t.dialog,callback:function(e){t.dialog=e},expression:"dialog"}},[r("v-card",[t._t("default")],2)],1)}),[],!1,null,null,null).exports},2584:(t,e,r)=>{"use strict";r.d(e,{Z:()=>n});const n=(0,r(1900).Z)({},(function(){var t=this,e=t.$createElement;return(t._self._c||e)("v-card-text",[t._t("default")],2)}),[],!1,null,null,null).exports},4697:(t,e,r)=>{"use strict";r.d(e,{Z:()=>o});const n={props:{isLoading:{type:Boolean,required:!0}}};const o=(0,r(1900).Z)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-card-actions",{staticClass:"pb-6 pr-6"},[r("v-spacer"),t._v(" "),r("v-progress-circular",{directives:[{name:"show",rawName:"v-show",value:t.isLoading,expression:"isLoading"}],attrs:{indeterminate:""}}),t._v(" "),t._t("default")],2)}),[],!1,null,null,null).exports},2003:(t,e,r)=>{"use strict";r.d(e,{Z:()=>n});const n=(0,r(1900).Z)({},(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-card-title",[r("span",{staticClass:"title font-weight-light"},[t._t("default")],2)])}),[],!1,null,null,null).exports},2828:(t,e,r)=>{"use strict";r.r(e),r.d(e,{default:()=>A});var n=r(629),o=r(8400),s=r(7525),i=r(4101),a=r(4935),c=r(5504),l=r(2003),u=r(2584),f=r(4697),d=r(4676),p=r(9192);function b(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function v(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?b(Object(r),!0).forEach((function(e){y(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):b(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function y(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}const m={name:"AddShareModal",mixins:[a.Z,i.Z],components:{Modal:c.Z,ModalHeading:l.Z,ModalBody:u.Z,ModalFooter:f.Z,FormErrors:d.Z,SearchSymbolField:p.Z},data:function(){return{waitFor:"store_share",valid:!0,form:{symbol_id:null,portfolio_id:0}}},computed:v({},(0,n.rn)(["showModal"])),mounted:function(){var t=this;document.addEventListener("keydown",(function(e){27==e.keyCode&&t.close()}))},methods:v(v({},(0,n.nv)(["storeShare","setShowModal"])),{},{open:function(t){this.form.portfolio_id=t,this.setShowModal(!0)},close:function(){this.clearErrors(),this.$refs.form.reset(),this.setShowModal(!1)},submit:function(){var t=this;this.$refs.form.validate()?(this.startLoading(),this.storeShare(this.form).then((function(e){(0,s.IE)(t.$t("The new share has been successfully added to your portfolio.")),t.close()})).catch((function(e){t.syncErrors(e)})).finally((function(){t.stopLoading()}))):this.focusFirstErrorInput()}})};var h=r(1900);const g=(0,h.Z)(m,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("modal",{attrs:{width:460,dialog:t.showModal}},[r("modal-heading",[t._v("\n    "+t._s(t.$t("Add Symbol"))+"\n  ")]),t._v(" "),r("modal-body",[r("v-form",{ref:"form",attrs:{"lazy-validation":""},nativeOn:{keyup:function(e){return!e.type.indexOf("key")&&t._k(e.keyCode,"enter",13,e.key,"Enter")?null:t.submit(e)},keydown:function(e){return t.clearError(e.target.name)}},model:{value:t.valid,callback:function(e){t.valid=e},expression:"valid"}},[r("form-errors",{attrs:{errors:t.errors}}),t._v(" "),r("search-symbol-field",{attrs:{symbolId:t.form.symbol_id},on:{"update:symbolId":function(e){return t.$set(t.form,"symbol_id",e)},"update:symbol-id":function(e){return t.$set(t.form,"symbol_id",e)}}})],1)],1),t._v(" "),r("modal-footer",{attrs:{"is-loading":t.isLoading}},[r("v-btn",{staticClass:"btn-close",attrs:{disabled:t.isLoading},on:{click:t.close}},[t._v("\n      "+t._s(t.$t("Close"))+"\n    ")]),t._v(" "),r("v-btn",{staticClass:"btn-action",attrs:{disabled:t.isLoading},on:{click:t.submit}},[t._v("\n      "+t._s(t.$t("Create"))+"\n    ")])],1)],1)}),[],!1,null,null,null).exports;var _=r(9356);function j(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function O(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?j(Object(r),!0).forEach((function(e){w(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):j(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function w(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}const x={props:{switch:{type:Boolean,required:!0},id:{type:Number,required:!0}},name:"VSwitchField",computed:O(O({},(0,n.Se)(["getPortfolioIndexById"])),{},{switched:{get:function(){return this.switch},set:function(t){var e=this;this.updatePortfolio({id:this.id,form:{filtered:t}}).then((function(){var t=e.getPortfolioIndexById(e.id);e.filterShares(t)}))}}}),methods:O({},(0,n.nv)(["updatePortfolio","filterShares"]))};const P=(0,h.Z)(x,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("v-switch",{staticClass:"mt-5",attrs:{id:t.id.toString()},model:{value:t.switched,callback:function(e){t.switched=e},expression:"switched"}})}),[],!1,null,null,null).exports;function S(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function k(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?S(Object(r),!0).forEach((function(e){$(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):S(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}function $(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}const E={name:"Home",mixins:[i.Z],components:{AddShareModal:g,ItemDetail:_.Z,VSwitchField:P},data:function(){return{waitFor:"fetch_symbols_data",itemDetails:o.iQ}},computed:k(k({},(0,n.rn)(["portfolios"])),(0,n.Se)(["isAdmin"])),methods:k(k({},(0,n.nv)(["fetchSymbolsData"])),{},{getColor:function(t){return-1==t?"red lighten-1":1==t?"green lighten-1":""},getTextColor:function(t){return-1==t?"red--text":1==t?"green--text":"grey--text"},getSymbolsData:function(){var t=this;this.startLoading(),this.fetchSymbolsData().then((function(e){(0,s.IE)(t.$t("Share prices are successfully updated."))})).catch((function(t){(0,s.O1)(t)})).finally((function(){t.stopLoading()}))}})};var C=r(3379),Z=r.n(C),D=r(4276),L={insert:"head",singleton:!1};Z()(D.Z,L);D.Z.locals;const A=(0,h.Z)(E,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return t.isInLoading("fetch_data")?t._e():r("v-row",{attrs:{align:"center",justify:"center"}},[r("add-share-modal",{ref:"addShareModal"}),t._v(" "),t._l(t.portfolios,(function(e){return r("v-col",{key:e.id,attrs:{cols:"12",sm:"8",md:"4",lg:"10"}},[r("v-card",[r("v-toolbar",{staticClass:"pl-2",attrs:{flat:""}},[r("v-btn",{attrs:{icon:"",disabled:""}},[r("v-icon",[t._v("home")])],1),t._v(" "),r("v-toolbar-title",{staticClass:"pl-2"},[t._v(t._s(e.name))]),t._v(" "),r("v-spacer"),t._v(" "),t.isAdmin?r("v-btn",{staticClass:"mx-1",attrs:{icon:"",small:"",disabled:t.isLoading},on:{click:function(e){return t.getSymbolsData()}}},[r("v-icon",[t._v("refresh")])],1):t._e(),t._v(" "),r("v-tooltip",{attrs:{bottom:""},scopedSlots:t._u([{key:"activator",fn:function(n){var o=n.on;return[r("v-btn",t._g({staticClass:"mx-1",attrs:{icon:"",small:"",disabled:t.isLoading,to:"/portfolios/"+e.id+"/edit"}},o),[r("v-icon",{attrs:{color:"green darken-2"}},[t._v("edit")])],1)]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(t.$t("Update Portfolio")))])]),t._v(" "),r("v-tooltip",{attrs:{bottom:""},scopedSlots:t._u([{key:"activator",fn:function(n){var o=n.on;return[r("v-btn",t._g({staticClass:"mx-1",attrs:{icon:"",small:"",disabled:t.isLoading},on:{click:function(r){return t.$refs.addShareModal.open(e.id)}}},o),[r("v-icon",{attrs:{color:"blue darken-2"}},[t._v("add")])],1)]}}],null,!0)},[t._v(" "),r("span",[t._v(t._s(t.$t("Add Symbol")))])])],1),t._v(" "),r("v-divider"),t._v(" "),r("v-card-text",[r("v-data-table",{attrs:{"item-key":"id","mobile-breakpoint":0,items:e.shares,"hide-default-footer":e.shares.length<11,"no-data-text":t.$t("You have not created any symbol."),"items-per-page":15,locale:t.$i18n.locale,headers:[{text:t.$t("Symbol"),sortable:!0,value:"symbol_code",align:"start"},{text:t.$t("Last Price"),sortable:!1,value:"symbol_last_price",align:"center"},{text:t.$t("Change"),sortable:!0,value:"symbol_rate_of_change",align:"center"},{text:t.$t("Lots"),sortable:!0,value:"lot",align:"center"},{text:t.$t("Average Cost"),sortable:!1,value:"average",align:"center"},{text:t.$t("Amount"),sortable:!0,value:"amount",align:"center"},{text:t.$t("Average Amount"),sortable:!1,value:"average_amount",align:"center"},{text:t.$t("Gain/Loss"),sortable:!1,value:"gain",align:"center"},{text:t.$t("Gain/Loss (%)"),sortable:!0,value:"gain_percent",align:"center"}]},scopedSlots:t._u([{key:"symbol_code",fn:function(e){var n=e.item;return[r("div",{staticClass:"d-flex align-center justify-start"},[r("v-col",{staticClass:"px-0 float-left font-weight-bold",attrs:{cols:"auto"}},[t._v("\n                "+t._s(n.symbol.code)+"\n              ")]),t._v(" "),r("v-spacer"),t._v(" "),r("v-col",{attrs:{cols:"auto pr-0"}},[r("v-btn",{attrs:{text:"",block:"",small:"",to:"/shares/"+n.id+"/transactions",disabled:t.isLoading}},[r("v-icon",{attrs:{small:""}},[t._v("horizontal_split")])],1)],1)],1)]}},{key:"symbol_last_price",fn:function(e){var n=e.item;return[r("div",{staticClass:"justify-center",class:t.getTextColor(n.symbol.trend)},[t._v("\n              "+t._s(n.symbol.last_price)+"\n            ")])]}},{key:"symbol_rate_of_change",fn:function(e){var n=e.item;return[r("v-chip",{attrs:{label:"",small:"",color:t.getColor(n.symbol.trend)}},[t._v("\n              "+t._s(n.symbol.rate_of_change)+"\n            ")])]}},{key:"lot",fn:function(e){var n=e.item;return[r("div",{staticClass:"justify-center"},[t._v("\n              "+t._s(t.$n(n.lot,"decimal"))+"\n            ")])]}},{key:"average",fn:function(e){var n=e.item;return[r("div",{staticClass:"d-flex align-center justify-center"},[r("v-col",{staticClass:"pr-0 float-right",attrs:{cols:"auto"}},[t._v("\n                "+t._s(n.average)+"\n              ")]),t._v(" "),r("v-col",{staticClass:"float-right overline font-weight-thin",attrs:{cols:"auto"}},[t._v("\n                ("+t._s(n.average_with_dividend)+")\n              ")])],1)]}},{key:"amount",fn:function(e){var n=e.item;return[r("div",{staticClass:"justify-center"},[t._v("\n              "+t._s(n.amount)+"\n            ")])]}},{key:"average_amount",fn:function(e){var n=e.item;return[r("div",{staticClass:"justify-center"},[t._v("\n              "+t._s(n.average_amount)+"\n            ")])]}},{key:"gain",fn:function(e){var n=e.item;return[r("div",{staticClass:"justify-center",class:t.getTextColor(n.gain_trend)},[t._v("\n              "+t._s(n.gain)+"\n            ")])]}},{key:"gain_percent",fn:function(e){var n=e.item;return[r("div",{staticClass:"justify-center",class:t.getTextColor(n.gain_trend)},[t._v("\n              "+t._s(t.$n(n.gain_percent,"percent"))+"\n            ")])]}}],null,!0)}),t._v(" "),e.shares.length?r("div",{staticClass:"ma-4"},[r("v-icon",{attrs:{"x-small":"",dense:""}},[t._v("access_time")]),t._v(" "),r("span",{staticClass:"mx-1 caption font-weight-thin"},[t._v("SG: "+t._s(e.shares[0].symbol.session_time))])],1):t._e()],1),t._v(" "),r("v-divider"),t._v(" "),r("v-card-actions",{staticStyle:{"background-color":"#323639"}},[r("v-flex",{staticClass:"px-2"},[r("v-list",{attrs:{dense:"",color:"#323639"}},[t._l(t.itemDetails,(function(n,o){return[r("item-detail",{key:n.key,attrs:{item:n,value:e[n.key],baseLink:"portfolios/"+e.id}}),t._v(" "),o+1<t.itemDetails.length?r("v-divider",{key:o}):t._e()]}))],2)],1)],1)],1)],1)}))],2)}),[],!1,null,"23c564a4",null).exports},4676:(t,e,r)=>{"use strict";r.d(e,{Z:()=>i});var n=r(8367),o=r.n(n);const s={props:{errors:{type:Object,required:!1}},name:"FormErrors",data:function(){return{any:null}},watch:{errors:function(t,e){this.any=!o()(t)}}};const i=(0,r(1900).Z)(s,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-alert",{attrs:{text:"",dismissible:"",type:"error",border:"left",value:t.any}},t._l(t.errors,(function(e){return r("dl",{key:e.id},t._l(e,(function(e){return r("dd",{key:e.id},[t._v(t._s(e))])})),0)})),0)}),[],!1,null,null,null).exports},9356:(t,e,r)=>{"use strict";r.d(e,{Z:()=>o});const n={props:{item:{type:Object,required:!0},value:{type:[String,Number],required:!1,default:0},baseLink:{type:String,required:!1,default:null}},name:"ItemDetail",computed:{itemLink:function(){return this.item.link?"/".concat(this.baseLink,"/").concat(this.item.link):void 0},disabled:function(){return void 0===this.itemLink}}};const o=(0,r(1900).Z)(n,(function(){var t=this,e=t.$createElement,r=t._self._c||e;return r("v-list-item",{attrs:{dense:"",disabled:t.disabled,to:t.itemLink}},[r("v-list-item-content",[r("v-list-item-title",{domProps:{textContent:t._s(t.$t(t.item.key+".title"))}}),t._v(" "),r("v-list-item-subtitle",{domProps:{innerHTML:t._s(t.$t(t.item.key+".description"))}})],1),t._v(" "),r("v-list-item-action",{staticClass:"body-2 font-weight-medium",domProps:{textContent:t._s(t.value)}})],1)}),[],!1,null,null,null).exports},9192:(t,e,r)=>{"use strict";r.d(e,{Z:()=>l});var n=r(629),o=r(4935),s=r(4101);function i(t,e){var r=Object.keys(t);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(t);e&&(n=n.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),r.push.apply(r,n)}return r}function a(t,e,r){return e in t?Object.defineProperty(t,e,{value:r,enumerable:!0,configurable:!0,writable:!0}):t[e]=r,t}const c={name:"SearchSymbolField",mixins:[o.Z,s.Z],data:function(){return{symbols:[],searching:!1,search:null,symbolId:0}},watch:{search:function(t){var e=this;null==t||this.symbols.length>0||this.isLoading||(this.searching=!0,this.fetchSymbols().then((function(t){e.symbols=t.data,e.searching=!1})).catch((function(t){e.syncErrors(t)})))}},methods:function(t){for(var e=1;e<arguments.length;e++){var r=null!=arguments[e]?arguments[e]:{};e%2?i(Object(r),!0).forEach((function(e){a(t,e,r[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(r)):i(Object(r)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(r,e))}))}return t}({},(0,n.nv)(["fetchSymbols"]))};const l=(0,r(1900).Z)(c,(function(){var t=this,e=t.$createElement;return(t._self._c||e)("v-autocomplete",{ref:"symbol_id",attrs:{name:"symbol_id",id:"symbol_id",filled:"",clearable:"","prepend-inner-icon":"layers",items:t.symbols,loading:t.searching,"search-input":t.search,rules:[t.rules.required],label:t.$t("Search Symbol"),"no-data-text":t.$t("No data available"),"error-messages":t.getError("symbol_id"),disabled:t.isLoading,"item-text":"code","item-value":"id"},on:{input:function(e){return t.$emit("update:symbolId",t.symbolId)},"update:searchInput":function(e){t.search=e},"update:search-input":function(e){t.search=e}},model:{value:t.symbolId,callback:function(e){t.symbolId=e},expression:"symbolId"}})}),[],!1,null,null,null).exports}}]);