import{A as P,f as L}from"./links.4c32e7b9.js";import{C as B}from"./Blur.36d19f95.js";import{C as U}from"./Card.95fd844a.js";import{G,S as R}from"./SeoStatisticsOverview.a7bd9cd8.js";import{G as q,a as N}from"./Row.76881ed1.js";import{P as O}from"./PostsTable.82129f87.js";import{r as s,o as p,b as h,w as o,a as y,d as i,D as _,t as m,c as v,f as b}from"./vue.runtime.esm-bundler.ba5c08e0.js";import{_ as f}from"./_plugin-vue_export-helper.80405f63.js";import{C as A}from"./Index.f352280d.js";import{R as D}from"./RequiredPlans.cbedd1ac.js";import{L as E}from"./LicenseConditions.20cf1cb4.js";import"./default-i18n.3881921e.js";import"./isArrayLikeObject.ab8f4241.js";import"./Tooltip.d28f6bf4.js";import"./Caret.da0d1a59.js";import"./index.df267eaa.js";import"./Slide.3af65e43.js";import"./numbers.c7cb4085.js";import"./license.afc1306d.js";import"./upperFirst.7faab9f8.js";import"./_stringToArray.4de3b1f3.js";import"./toString.7b877a36.js";import"./WpTable.4548d9cd.js";import"./IndexStatus.73e5bc27.js";import"./Table.9ae5bcf6.js";import"./PostTypes.797a4244.js";import"./constants.238e5b7b.js";import"./addons.2b4a9919.js";import"./_arrayEach.56a9f647.js";import"./_getAllKeys.6b3e1125.js";import"./_getTag.e7f511fc.js";import"./vue.runtime.esm-bundler.8db3b8fc.js";const H={setup(){return{searchStatisticsStore:P()}},components:{CoreBlur:B,CoreCard:U,Graph:G,GridColumn:q,GridRow:N,PostsTable:O,SeoStatisticsOverview:R},data(){return{strings:{seoStatisticsCard:this.$t.__("SEO Statistics",this.$td),seoStatisticsTooltip:this.$t.__("The following SEO Statistics graphs are useful metrics for understanding the visibility of your website or pages in search results and can help you identify trends or changes over time.<br /><br />Note: This data is capped at the top 100 keywords per day to speed up processing and to help you prioritize your SEO efforts, so while the data may seem inconsistent with Google Search Console, this is intentional.",this.$td),contentCard:this.$t.__("Content",this.$td),postsTooltip:this.$t.__("These lists can be useful for understanding the performance of specific pages or posts and identifying opportunities for improvement. For example, the top winning content may be good candidates for further optimization or promotion, while the top losing may need to be reevaluated and potentially updated.",this.$td)},defaultPages:{rows:[],totals:{page:0,pages:0,total:0}}}},computed:{series(){var e,a,n,r;return!((a=(e=this.searchStatisticsStore.data)==null?void 0:e.seoStatistics)!=null&&a.statistics)||!((r=(n=this.searchStatisticsStore.data)==null?void 0:n.seoStatistics)!=null&&r.intervals)?[]:[{name:this.$t.__("Search Impressions",this.$td),data:this.searchStatisticsStore.data.seoStatistics.intervals.map(t=>({x:t.date,y:t.impressions})),legend:{total:this.searchStatisticsStore.data.seoStatistics.statistics.impressions}},{name:this.$t.__("Search Clicks",this.$td),data:this.searchStatisticsStore.data.seoStatistics.intervals.map(t=>({x:t.date,y:t.clicks})),legend:{total:this.searchStatisticsStore.data.seoStatistics.statistics.clicks}}]}}},V={class:"aioseo-search-statistics-dashboard"},F=["innerHTML"];function I(e,a,n,r,t,u){const c=s("seo-statistics-overview"),l=s("graph"),d=s("core-card"),k=s("posts-table"),x=s("grid-column"),C=s("grid-row"),T=s("core-blur");return p(),h(T,null,{default:o(()=>[y("div",V,[i(C,null,{default:o(()=>[i(x,null,{default:o(()=>[i(d,{class:"aioseo-seo-statistics-card",slug:"seoPerformance","header-text":t.strings.seoStatisticsCard,toggles:!1,"no-slide":""},{tooltip:o(()=>[y("span",{innerHTML:t.strings.seoStatisticsTooltip},null,8,F)]),default:o(()=>[i(c,{statistics:["impressions","clicks","ctr","position"],"show-graph":!1,view:"side-by-side"}),i(l,{"multi-axis":"",series:u.series,"legend-style":"simple"},null,8,["series"])]),_:1},8,["header-text"]),i(d,{slug:"posts","header-text":t.strings.contentCard,toggles:!1,"no-slide":""},{tooltip:o(()=>[_(m(t.strings.postsTooltip),1)]),default:o(()=>{var g,S,$;return[i(k,{posts:(($=(S=(g=r.searchStatisticsStore.data)==null?void 0:g.seoStatistics)==null?void 0:S.pages)==null?void 0:$.paginated)||t.defaultPages,columns:["postTitle","indexStatus","seoScore","clicksSortable","impressionsSortable","positionSortable","diffPositionSortable"],"show-items-per-page":"","show-table-footer":""},null,8,["posts"])]}),_:1},8,["header-text"])]),_:1})]),_:1})])]),_:1})}const M=f(H,[["render",I]]);const z={setup(){return{licenseStore:L()}},components:{Blur:M,Cta:A,RequiredPlans:D},data(){return{strings:{ctaButtonText:this.$t.__("Unlock Search Statistics",this.$td),ctaHeader:this.$t.sprintf(this.$t.__("Search Statistics is a %1$s Feature",this.$td),"PRO"),ctaDescription:this.$t.__("Connect your site to Google Search Console to receive insights on how content is being discovered. Identify areas for improvement and drive traffic to your website.",this.$td),thisFeatureRequires:this.$t.__("This feature requires one of the following plans:",this.$td),feature1:this.$t.__("Search traffic insights",this.$td),feature2:this.$t.__("Track page rankings",this.$td),feature3:this.$t.__("Track keyword rankings",this.$td),feature4:this.$t.__("Speed tests for individual pages/posts",this.$td)}}}},j={class:"aioseo-search-statistics-seo-statistics"};function J(e,a,n,r,t,u){const c=s("blur"),l=s("required-plans"),d=s("cta");return p(),v("div",j,[i(c),i(d,{"cta-link":e.$links.getPricingUrl("search-statistics","search-statistics-upsell","seo-statistics"),"button-text":t.strings.ctaButtonText,"learn-more-link":e.$links.getUpsellUrl("search-statistics","seo-statistics",e.$isPro?"pricing":"liteUpgrade"),"feature-list":[t.strings.feature1,t.strings.feature2,t.strings.feature3,t.strings.feature4],"align-top":"","hide-bonus":!r.licenseStore.isUnlicensed},{"header-text":o(()=>[_(m(t.strings.ctaHeader),1)]),description:o(()=>[i(l,{"core-feature":["search-statistics","seo-statistics"]}),_(" "+m(t.strings.ctaDescription),1)]),_:1},8,["cta-link","button-text","learn-more-link","feature-list","hide-bonus"])])}const w=f(z,[["render",J]]),K={mixins:[E],components:{SeoStatistics:w,Lite:w}},Q={class:"aioseo-search-statistics-seo-statistics"};function W(e,a,n,r,t,u){const c=s("seo-statistics",!0),l=s("lite");return p(),v("div",Q,[e.shouldShowMain("search-statistics","seo-statistics")?(p(),h(c,{key:0})):b("",!0),e.shouldShowUpgrade("search-statistics","seo-statistics")||e.shouldShowLite?(p(),h(l,{key:1})):b("",!0)])}const Lt=f(K,[["render",W]]);export{Lt as default};