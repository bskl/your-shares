(self.webpackChunk=self.webpackChunk||[]).push([[904],{4101:(e,t,r)=>{"use strict";function n(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function o(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?n(Object(r),!0).forEach((function(t){i(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):n(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function i(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}r.d(t,{Z:()=>a});const a={data:function(){return{waitFor:""}},computed:o(o({},(0,r(629).Se)(["isInLoading"])),{},{isLoading:function(){return this.isInLoading(this.waitFor)}}),methods:{startLoading:function(){this.$store.dispatch("startLoadingBy",this.waitFor)},stopLoading:function(){this.$store.dispatch("stopLoadingBy",this.waitFor)}}}},4935:(e,t,r)=>{"use strict";r.d(t,{Z:()=>f});var n=r(629),o=r(8593),i=r(9780);function a(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var r=[],n=!0,o=!1,i=void 0;try{for(var a,s=e[Symbol.iterator]();!(n=(a=s.next()).done)&&(r.push(a.value),!t||r.length!==t);n=!0);}catch(e){o=!0,i=e}finally{try{n||null==s.return||s.return()}finally{if(o)throw i}}return r}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return s(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);"Object"===r&&e.constructor&&(r=e.constructor.name);if("Map"===r||"Set"===r)return Array.from(e);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return s(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function s(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}function c(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function u(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?c(Object(r),!0).forEach((function(t){l(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):c(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function l(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}const f={data:function(){var e=this;return{rules:{required:function(t){return!!t||0===t||e.$t("rules.required")},email:function(t){return/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(t)||e.$t("rules.email")},confirmed:function(t){return function(r){return t==r||e.$t("rules.confirmed")}},gte:function(t){return function(r){return r&&r.length>=t||e.$t("rules.gte",{length:t})}}}}},computed:u({},(0,n.rn)(["errors"])),methods:u(u({},(0,n.nv)(["setErrors","unsetError"])),{},{syncErrors:function(e){var t=this;if(void 0!==e)if(void 0!==e.response&&e.hasOwnProperty("response"))if(400===e.response.status||401===e.response.status)i.Z.commit("LOGGED_OUT"),o.Z.push({name:"Login"});else if(403===e.response.status)o.Z.push({name:"Forbidden"});else if(404===e.response.status)o.Z.push({name:"NotFound"});else if(419===e.response.status)o.Z.push({name:"ExpiredSession"});else if(e.response.hasOwnProperty("data")&&e.response.data.hasOwnProperty("errors")){this.setErrors(e.response.data.errors);for(var r=0,n=Object.entries(this.errors);r<n.length;r++){var a=n[r];void 0!==this.$refs[a]&&(this.$refs[a].valid=!1)}setTimeout((function(){t.focusFirstErrorInput()}),500)}else o.Z.push({name:"NotFound"});else console.log(e)},clearErrors:function(){this.setErrors({})},clearError:function(e){this.hasErrors(e)&&(this.unsetError(e),this.$refs[e].valid=!0)},focusFirstErrorInput:function(){for(var e=0,t=Object.entries(this.form);e<t.length;e++){var r=a(t[e],2),n=r[0];r[1];if(void 0!==this.$refs[n]&&!this.$refs[n].valid){this.$refs[n].focus();break}}},hasErrors:function(e){return this.errors.hasOwnProperty(e)},getError:function(e){return this.hasErrors(e)?this.errors[e][0]:null},getErrors:function(e){return this.hasErrors(e)?this.errors[e]:null}})}},8552:(e,t,r)=>{var n=r(852)(r(5639),"DataView");e.exports=n},7071:(e,t,r)=>{var n=r(852)(r(5639),"Map");e.exports=n},3818:(e,t,r)=>{var n=r(852)(r(5639),"Promise");e.exports=n},8525:(e,t,r)=>{var n=r(852)(r(5639),"Set");e.exports=n},2705:(e,t,r)=>{var n=r(5639).Symbol;e.exports=n},577:(e,t,r)=>{var n=r(852)(r(5639),"WeakMap");e.exports=n},4239:(e,t,r)=>{var n=r(2705),o=r(9607),i=r(2333),a=n?n.toStringTag:void 0;e.exports=function(e){return null==e?void 0===e?"[object Undefined]":"[object Null]":a&&a in Object(e)?o(e):i(e)}},9454:(e,t,r)=>{var n=r(4239),o=r(7005);e.exports=function(e){return o(e)&&"[object Arguments]"==n(e)}},8458:(e,t,r)=>{var n=r(3560),o=r(5346),i=r(3218),a=r(346),s=/^\[object .+?Constructor\]$/,c=Function.prototype,u=Object.prototype,l=c.toString,f=u.hasOwnProperty,d=RegExp("^"+l.call(f).replace(/[\\^$.*+?()[\]{}|]/g,"\\$&").replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g,"$1.*?")+"$");e.exports=function(e){return!(!i(e)||o(e))&&(n(e)?d:s).test(a(e))}},8749:(e,t,r)=>{var n=r(4239),o=r(1780),i=r(7005),a={};a["[object Float32Array]"]=a["[object Float64Array]"]=a["[object Int8Array]"]=a["[object Int16Array]"]=a["[object Int32Array]"]=a["[object Uint8Array]"]=a["[object Uint8ClampedArray]"]=a["[object Uint16Array]"]=a["[object Uint32Array]"]=!0,a["[object Arguments]"]=a["[object Array]"]=a["[object ArrayBuffer]"]=a["[object Boolean]"]=a["[object DataView]"]=a["[object Date]"]=a["[object Error]"]=a["[object Function]"]=a["[object Map]"]=a["[object Number]"]=a["[object Object]"]=a["[object RegExp]"]=a["[object Set]"]=a["[object String]"]=a["[object WeakMap]"]=!1,e.exports=function(e){return i(e)&&o(e.length)&&!!a[n(e)]}},280:(e,t,r)=>{var n=r(5726),o=r(6916),i=Object.prototype.hasOwnProperty;e.exports=function(e){if(!n(e))return o(e);var t=[];for(var r in Object(e))i.call(e,r)&&"constructor"!=r&&t.push(r);return t}},7518:e=>{e.exports=function(e){return function(t){return e(t)}}},4429:(e,t,r)=>{var n=r(5639)["__core-js_shared__"];e.exports=n},1957:(e,t,r)=>{var n="object"==typeof r.g&&r.g&&r.g.Object===Object&&r.g;e.exports=n},852:(e,t,r)=>{var n=r(8458),o=r(7801);e.exports=function(e,t){var r=o(e,t);return n(r)?r:void 0}},9607:(e,t,r)=>{var n=r(2705),o=Object.prototype,i=o.hasOwnProperty,a=o.toString,s=n?n.toStringTag:void 0;e.exports=function(e){var t=i.call(e,s),r=e[s];try{e[s]=void 0;var n=!0}catch(e){}var o=a.call(e);return n&&(t?e[s]=r:delete e[s]),o}},4160:(e,t,r)=>{var n=r(8552),o=r(7071),i=r(3818),a=r(8525),s=r(577),c=r(4239),u=r(346),l="[object Map]",f="[object Promise]",d="[object Set]",p="[object WeakMap]",m="[object DataView]",b=u(n),y=u(o),h=u(i),v=u(a),g=u(s),j=c;(n&&j(new n(new ArrayBuffer(1)))!=m||o&&j(new o)!=l||i&&j(i.resolve())!=f||a&&j(new a)!=d||s&&j(new s)!=p)&&(j=function(e){var t=c(e),r="[object Object]"==t?e.constructor:void 0,n=r?u(r):"";if(n)switch(n){case b:return m;case y:return l;case h:return f;case v:return d;case g:return p}return t}),e.exports=j},7801:e=>{e.exports=function(e,t){return null==e?void 0:e[t]}},5346:(e,t,r)=>{var n,o=r(4429),i=(n=/[^.]+$/.exec(o&&o.keys&&o.keys.IE_PROTO||""))?"Symbol(src)_1."+n:"";e.exports=function(e){return!!i&&i in e}},5726:e=>{var t=Object.prototype;e.exports=function(e){var r=e&&e.constructor;return e===("function"==typeof r&&r.prototype||t)}},6916:(e,t,r)=>{var n=r(5569)(Object.keys,Object);e.exports=n},1167:(e,t,r)=>{e=r.nmd(e);var n=r(1957),o=t&&!t.nodeType&&t,i=o&&e&&!e.nodeType&&e,a=i&&i.exports===o&&n.process,s=function(){try{var e=i&&i.require&&i.require("util").types;return e||a&&a.binding&&a.binding("util")}catch(e){}}();e.exports=s},2333:e=>{var t=Object.prototype.toString;e.exports=function(e){return t.call(e)}},5569:e=>{e.exports=function(e,t){return function(r){return e(t(r))}}},5639:(e,t,r)=>{var n=r(1957),o="object"==typeof self&&self&&self.Object===Object&&self,i=n||o||Function("return this")();e.exports=i},346:e=>{var t=Function.prototype.toString;e.exports=function(e){if(null!=e){try{return t.call(e)}catch(e){}try{return e+""}catch(e){}}return""}},5694:(e,t,r)=>{var n=r(9454),o=r(7005),i=Object.prototype,a=i.hasOwnProperty,s=i.propertyIsEnumerable,c=n(function(){return arguments}())?n:function(e){return o(e)&&a.call(e,"callee")&&!s.call(e,"callee")};e.exports=c},1469:e=>{var t=Array.isArray;e.exports=t},8612:(e,t,r)=>{var n=r(3560),o=r(1780);e.exports=function(e){return null!=e&&o(e.length)&&!n(e)}},4144:(e,t,r)=>{e=r.nmd(e);var n=r(5639),o=r(5062),i=t&&!t.nodeType&&t,a=i&&e&&!e.nodeType&&e,s=a&&a.exports===i?n.Buffer:void 0,c=(s?s.isBuffer:void 0)||o;e.exports=c},8367:(e,t,r)=>{var n=r(280),o=r(4160),i=r(5694),a=r(1469),s=r(8612),c=r(4144),u=r(5726),l=r(6719),f=Object.prototype.hasOwnProperty;e.exports=function(e){if(null==e)return!0;if(s(e)&&(a(e)||"string"==typeof e||"function"==typeof e.splice||c(e)||l(e)||i(e)))return!e.length;var t=o(e);if("[object Map]"==t||"[object Set]"==t)return!e.size;if(u(e))return!n(e).length;for(var r in e)if(f.call(e,r))return!1;return!0}},3560:(e,t,r)=>{var n=r(4239),o=r(3218);e.exports=function(e){if(!o(e))return!1;var t=n(e);return"[object Function]"==t||"[object GeneratorFunction]"==t||"[object AsyncFunction]"==t||"[object Proxy]"==t}},1780:e=>{e.exports=function(e){return"number"==typeof e&&e>-1&&e%1==0&&e<=9007199254740991}},3218:e=>{e.exports=function(e){var t=typeof e;return null!=e&&("object"==t||"function"==t)}},7005:e=>{e.exports=function(e){return null!=e&&"object"==typeof e}},6719:(e,t,r)=>{var n=r(8749),o=r(7518),i=r(1167),a=i&&i.isTypedArray,s=a?o(a):n;e.exports=s},5062:e=>{e.exports=function(){return!1}},3904:(e,t,r)=>{"use strict";r.r(t),r.d(t,{default:()=>g});var n=r(629),o=r(8400),i=r(7525),a=r(4935),s=r(4101),c=r(4676),u=r(9062);const l={name:"VCurrencyField",props:{name:{type:String,required:!0},value:{type:Number,default:null},options:{type:Object,default:function(){}}},mixins:[a.Z,s.Z],data:function(){return{formattedValue:null}},watch:{value:function(e){this.setValue(e)}},mounted:function(){this.setValue(this.value)},methods:{setValue:function(e){this.$ci.setValue(this.$refs[this.name],e)},onInput:function(e){this.$emit("input",this.$ci.getValue(this.$refs[this.name])),this.formattedValue=e},onChange:function(e){this.$emit("change",this.$ci.getValue(this.$refs[this.name])),this.formattedValue=e}}};var f=r(1900);const d=(0,f.Z)(l,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("v-text-field",{directives:[{name:"currency",rawName:"v-currency",value:e.options,expression:"options"}],ref:e.name,attrs:{type:"text",name:e.name,id:e.name,filled:"",clearable:"","prepend-inner-icon":"money",value:e.formattedValue,disabled:e.isLoading,label:e.$t("Enter Transaction Price"),rules:[e.rules.required],"error-messages":e.getError(e.name)},on:{change:e.onChange,input:e.onInput}})}),[],!1,null,null,null).exports;function p(e,t){return function(e){if(Array.isArray(e))return e}(e)||function(e,t){if("undefined"==typeof Symbol||!(Symbol.iterator in Object(e)))return;var r=[],n=!0,o=!1,i=void 0;try{for(var a,s=e[Symbol.iterator]();!(n=(a=s.next()).done)&&(r.push(a.value),!t||r.length!==t);n=!0);}catch(e){o=!0,i=e}finally{try{n||null==s.return||s.return()}finally{if(o)throw i}}return r}(e,t)||function(e,t){if(!e)return;if("string"==typeof e)return m(e,t);var r=Object.prototype.toString.call(e).slice(8,-1);"Object"===r&&e.constructor&&(r=e.constructor.name);if("Map"===r||"Set"===r)return Array.from(e);if("Arguments"===r||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(r))return m(e,t)}(e,t)||function(){throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}()}function m(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}function b(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function y(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?b(Object(r),!0).forEach((function(t){h(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):b(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}function h(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}const v={props:{shareId:{type:Number,required:!1},code:{type:String,required:!1},commission:{type:Number,required:!1}},name:"CreateTransaction",mixins:[a.Z,s.Z],components:{FormErrors:c.Z,SearchSymbolField:u.Z,VCurrencyField:d},data:function(){return{waitFor:"store_transaction",form:{share_id:this.shareId,type:o.L0.Buying,date_at:null,symbol_id:0,lot:null,price:null,commission:this.commission,dividend_gain:null},date:null,valid:!0,menu:!1,symbolCode:this.code}},computed:{transactionTypes:function(){var e=this;return o.C_.map((function(t,r){return{id:r,label:e.$t(t)}}))}},watch:{date:function(e){this.form.date_at=this.formatDate(e)}},methods:y(y({},(0,n.nv)(["storeTransaction"])),{},{formatDate:function(e){if(!e)return null;var t=p(e.split("-"),3),r=t[0],n=t[1],o=t[2];return"".concat(o,".").concat(n,".").concat(r)},goBack:function(){window.history.length>1?this.$router.go(-1):this.$router.push({name:"Home"})},submit:function(){var e=this;this.$refs.form.validate()?(this.startLoading(),this.storeTransaction(this.form).then((function(t){(0,i.IE)(t),e.clearErrors(),e.goBack()})).catch((function(t){e.syncErrors(t)})).finally((function(){e.stopLoading()}))):this.focusFirstErrorInput()},allowedDates:function(e){return 0!==new Date(e).getDay()&&6!==new Date(e).getDay()&&new Date(e)<=new Date},saveToLocalStorage:function(){var e={share_id:this.shareId,code:this.code,commission:this.commission};localStorage.setItem("transactionData",JSON.stringify(e))},readFromLocalStorage:function(){var e=JSON.parse(localStorage.getItem("transactionData"));this.form.share_id=e.share_id,this.form.commission=e.commission,this.symbolCode=e.code}}),created:function(){void 0===this.shareId?this.readFromLocalStorage():this.saveToLocalStorage()}};const g=(0,f.Z)(v,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("v-row",{attrs:{align:"center",justify:"center"}},[r("v-col",{attrs:{cols:"12",sm:"8",md:"4"}},[r("v-card",[r("v-card-title",[r("v-toolbar-title",[r("span",{staticClass:"title font-weight-light"},[e._v("\n            "+e._s(e.symbolCode)+"\n            "),r("v-icon",{attrs:{small:""}},[e._v("keyboard_arrow_right")]),e._v("\n            "+e._s(e.$t("Add Transaction"))+"\n          ")],1)])],1),e._v(" "),r("v-card-text",[r("v-form",{ref:"form",attrs:{"lazy-validation":""},nativeOn:{keyup:function(t){return!t.type.indexOf("key")&&e._k(t.keyCode,"enter",13,t.key,"Enter")?null:e.submit(t)},keydown:function(t){return e.clearError(t.target.name)}},model:{value:e.valid,callback:function(t){e.valid=t},expression:"valid"}},[r("form-errors",{attrs:{errors:e.errors}}),e._v(" "),r("v-input",{ref:"share_id",attrs:{type:"hidden",name:"share_id",id:"share_id",readonly:"","hide-details":"",dense:""},model:{value:e.form.share_id,callback:function(t){e.$set(e.form,"share_id",t)},expression:"form.share_id"}}),e._v(" "),r("v-select",{ref:"type",attrs:{name:"type",id:"type",autofocus:"","single-line":"",filled:"",clearable:"","prepend-inner-icon":"format_list_bulleted",disabled:e.isLoading,items:e.transactionTypes,label:e.$t("Select Transaction"),rules:[e.rules.required],"error-messages":e.getError("type"),"item-text":"label","item-value":"id","menu-props":"bottom"},model:{value:e.form.type,callback:function(t){e.$set(e.form,"type",t)},expression:"form.type"}}),e._v(" "),r("v-menu",{ref:"menu",attrs:{"offset-y":"","min-width":"290px",transition:"scale-transition","close-on-content-click":!1,"nudge-right":40,"return-value":e.form.date_at},on:{"update:returnValue":function(t){return e.$set(e.form,"date_at",t)},"update:return-value":function(t){return e.$set(e.form,"date_at",t)}},scopedSlots:e._u([{key:"activator",fn:function(t){var n=t.on;return[r("v-text-field",e._g({ref:"date_at",attrs:{name:"date_at",id:"date_at",readonly:"",filled:"",clearable:"","prepend-inner-icon":"calendar_today",disabled:e.isLoading,label:e.$t("Select Date"),rules:[e.rules.required],"error-messages":e.getError("date_at")},model:{value:e.form.date_at,callback:function(t){e.$set(e.form,"date_at",t)},expression:"form.date_at"}},n))]}}]),model:{value:e.menu,callback:function(t){e.menu=t},expression:"menu"}},[e._v(" "),r("v-date-picker",{attrs:{"no-title":"",scrollable:"","allowed-dates":e.allowedDates,"first-day-of-week":1},model:{value:e.date,callback:function(t){e.date=t},expression:"date"}},[r("v-spacer"),e._v(" "),r("v-btn",{staticClass:"btn-close",on:{click:function(t){e.menu=!1}}},[e._v(e._s(e.$t("Close")))]),e._v(" "),r("v-btn",{staticClass:"btn-action",on:{click:function(t){return e.$refs.menu.save(e.form.date_at)}}},[e._v(e._s(e.$t("Ok")))])],1)],1),e._v(" "),5==e.form.type?r("search-symbol-field",{attrs:{symbolId:e.form.symbol_id},on:{"update:symbolId":function(t){return e.$set(e.form,"symbol_id",t)},"update:symbol-id":function(t){return e.$set(e.form,"symbol_id",t)}}}):e._e(),e._v(" "),r("v-text-field",{ref:"lot",attrs:{type:"number",name:"lot",id:"lot",filled:"",clearable:"","prepend-inner-icon":"format_list_numbered",disabled:e.isLoading,label:e.$t("Enter Share Amount"),rules:[e.rules.required],"error-messages":e.getError("lot"),hint:3==e.form.type?e.$t("You must write your bonus shares."):4==e.form.type?e.$t("You must write your rights shares."):""},model:{value:e.form.lot,callback:function(t){e.$set(e.form,"lot",t)},expression:"form.lot"}}),e._v(" "),5==e.form.type?r("v-text-field",{ref:"exchange_ratio",attrs:{type:"number",name:"exchange_ratio",id:"exchange_ratio",filled:"",clearable:"","prepend-inner-icon":"donut_large",disabled:e.isLoading,label:e.$t("Enter Exchange Ratio"),rules:[e.rules.required],"error-messages":e.getError("exchange_ratio"),hint:e.$t("for_example",{example:"1,15997"})},model:{value:e.form.exchange_ratio,callback:function(t){e.$set(e.form,"exchange_ratio",t)},expression:"form.exchange_ratio"}}):e._e(),e._v(" "),0==e.form.type||1==e.form.type||2==e.form.type?r("v-currency-field",{attrs:{name:"price"},model:{value:e.form.price,callback:function(t){e.$set(e.form,"price",t)},expression:"form.price"}}):e._e(),e._v(" "),0==e.form.type||1==e.form.type?r("v-text-field",{ref:"commission",attrs:{type:"number",name:"commission",id:"commission",filled:"",clearable:"",step:"0.0001","prepend-inner-icon":"donut_large",disabled:e.isLoading,label:e.$t("Enter Commission Rate"),rules:[e.rules.required],"error-messages":e.getError("commission"),hint:e.$t("for_example",{example:"Garanti Bank: 0,188"})},model:{value:e.form.commission,callback:function(t){e.$set(e.form,"commission",t)},expression:"form.commission"}}):e._e(),e._v(" "),2==e.form.type?r("v-currency-field",{attrs:{name:"dividend_gain"},model:{value:e.form.dividend_gain,callback:function(t){e.$set(e.form,"dividend_gain",t)},expression:"form.dividend_gain"}}):e._e()],1)],1),e._v(" "),r("v-card-actions",{staticClass:"pb-4 pr-4"},[r("v-spacer"),e._v(" "),r("v-progress-circular",{directives:[{name:"show",rawName:"v-show",value:e.isLoading,expression:"isLoading"}],attrs:{indeterminate:""}}),e._v(" "),r("v-btn",{staticClass:"btn-close",attrs:{disabled:e.isLoading},on:{click:function(t){return e.$router.go(-1)}}},[e._v("\n          "+e._s(e.$t("Close"))+"\n        ")]),e._v(" "),r("v-btn",{staticClass:"btn-action",attrs:{disabled:e.isLoading},on:{click:e.submit}},[e._v("\n          "+e._s(e.$t("Create"))+"\n        ")])],1)],1)],1)],1)}),[],!1,null,null,null).exports},4676:(e,t,r)=>{"use strict";r.d(t,{Z:()=>a});var n=r(8367),o=r.n(n);const i={props:{errors:{type:Object,required:!1}},name:"FormErrors",data:function(){return{any:null}},watch:{errors:function(e,t){this.any=!o()(e)}}};const a=(0,r(1900).Z)(i,(function(){var e=this,t=e.$createElement,r=e._self._c||t;return r("v-alert",{attrs:{text:"",dismissible:"",type:"error",border:"left",value:e.any}},e._l(e.errors,(function(t){return r("dl",{key:t.id},e._l(t,(function(t){return r("dd",{key:t.id},[e._v(e._s(t))])})),0)})),0)}),[],!1,null,null,null).exports},9062:(e,t,r)=>{"use strict";r.d(t,{Z:()=>u});var n=r(629),o=r(4935),i=r(4101);function a(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function s(e,t,r){return t in e?Object.defineProperty(e,t,{value:r,enumerable:!0,configurable:!0,writable:!0}):e[t]=r,e}const c={name:"SearchSymbolField",mixins:[o.Z,i.Z],data:function(){return{symbols:[],searching:!1,search:null,symbolId:0}},watch:{search:function(e){var t=this;null==e||this.symbols.length>0||this.isLoading||(this.searching=!0,this.fetchSymbols().then((function(e){t.symbols=e.data,t.searching=!1})).catch((function(e){t.syncErrors(e)})))}},methods:function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?a(Object(r),!0).forEach((function(t){s(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):a(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},(0,n.nv)(["fetchSymbols"]))};const u=(0,r(1900).Z)(c,(function(){var e=this,t=e.$createElement;return(e._self._c||t)("v-autocomplete",{ref:"symbol_id",attrs:{name:"symbol_id",id:"symbol_id",filled:"",clearable:"",items:e.symbols,loading:e.searching,"search-input":e.search,rules:[e.rules.required],label:e.$t("Search Symbol"),"no-data-text":e.$t("No data available"),"error-messages":e.getError("symbol_id"),disabled:e.isLoading,"item-text":"code","item-value":"id"},on:{input:function(t){return e.$emit("update:symbolId",e.symbolId)},"update:searchInput":function(t){e.search=t},"update:search-input":function(t){e.search=t}},model:{value:e.symbolId,callback:function(t){e.symbolId=t},expression:"symbolId"}})}),[],!1,null,null,null).exports}}]);