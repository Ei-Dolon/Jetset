import{u as v,t as h}from"./links.4c32e7b9.js";import{B as y}from"./Img.722f356c.js";import{C as S}from"./Caret.da0d1a59.js";import{S as b}from"./Profile.dc3ffcdc.js";import{r as n,o as c,c as l,a as e,d as r,t as s,J as k,f as I,e as x,D as C,N,O as B}from"./vue.runtime.esm-bundler.ba5c08e0.js";import{_ as V}from"./_plugin-vue_export-helper.80405f63.js";const w={setup(){return{rootStore:v()}},components:{BaseImg:y,CoreLoader:S,SvgDannieProfile:b},props:{description:{type:String,required:!0},image:String,loading:{type:Boolean,default:!1},title:{type:String,required:!0}},data(){return{images:{}}},computed:{appName(){return"All in One SEO"},date(){const o=new Date;return o.toLocaleString("default",{month:"long"})+" "+o.getDate()},isVerticalImage(){const o=this.images[this.image];return o?o.vertical:!1}},methods:{truncate:h}},d=o=>(N("data-v-55e2045a"),o=o(),B(),o),D={class:"aioseo-facebook-preview"},A={class:"facebook-post"},L={class:"facebook-header"},P={class:"profile-photo"},O={class:"poster"},q={class:"poster-name"},E={class:"poster-date"},F=d(()=>e("div",{class:"ellipsis"},[e("div"),e("div"),e("div")],-1)),R={key:0,class:"loader"},z={class:"facebook-site-description"},J={class:"site-domain"},T={class:"site-title"},U={class:"site-description"},j=d(()=>e("div",{class:"facebook-footer"},null,-1));function G(o,i,a,_,u,t){const p=n("svg-dannie-profile"),m=n("base-img"),f=n("core-loader");return c(),l("div",D,[e("div",A,[e("div",L,[e("div",P,[r(p)]),e("div",O,[e("div",q,s(t.appName),1),e("div",E,s(t.date),1)]),F]),e("div",{class:k(["facebook-content",{vertical:t.isVerticalImage}])},[r(m,{debounce:!1,src:a.image,onImages:i[0]||(i[0]=g=>u.images=g),class:"facebook-content__image"},null,8,["src"]),a.loading?(c(),l("div",R,[r(f)])):I("",!0),e("div",z,[e("div",J,[x(o.$slots,"site-url",{},()=>[C(s(_.rootStore.aioseo.urls.domain),1)],!0)]),e("div",T,s(t.truncate(a.title,60)),1),e("div",U,s(t.truncate(a.description,110)),1)])],2),j])])}const Y=V(w,[["render",G],["__scopeId","data-v-55e2045a"]]);export{Y as C};