"use strict";(self["webpackChunkoptinmonster_wordpress_plugin_vue_app"]=self["webpackChunkoptinmonster_wordpress_plugin_vue_app"]||[]).push([[938],{81036:function(t,e,s){s.r(e),s.d(e,{default:function(){return y}});var a=function(){var t=this,e=t._self._c;return t.reachedCampaignLimit?e("templates-limit-exceeded",{attrs:{"is-playbooks":!0}}):e("core-page",[e("div",{staticClass:"playbooks-page-wrapper dashboard-wrapper",class:{previewing:!1}},[e("common-page-tabnav",{attrs:{current:"playbooks",tabs:t.tabs}}),t.alerts.length?e("div",{staticClass:"container",staticStyle:{margin:"46px auto 40px"}},[e("core-alerts",{attrs:{alerts:t.alerts}})],1):t._e(),e("div",{staticClass:"playbooks-content"},[e("playbooks-intro"),t.isLoading?e("core-loading",{staticClass:"playbooks-loading"}):e("div",[t.hasRecommendations?e("playbooks-recommendations"):t._e(),e("playbooks-table")],1)],1),e("playbooks-upgrade-modal",[e("core-upgrade-button",{staticClass:"button primary",staticStyle:{margin:"0 0 15px"},attrs:{slot:"button","utm-medium":"Playbooks",target:"_blank"},slot:"button"},[t._v(" Unlock Access Now ")])],1),e("campaigns-modal-create-campaign",{attrs:{"from-playbook":!0}}),e("templates-modal-not-connected")],1)])},o=[],i=s(55916),n=s(86080),l=(s(57658),s(20629)),r=s(86430),p=s(591),c=s(30727),d=s(7268),m={mixins:[r.G,d.s,c.v,p.j],computed:(0,n.Z)((0,n.Z)((0,n.Z)((0,n.Z)((0,n.Z)({},(0,l.rn)(["alerts"])),(0,l.rn)("playbooks",["playbooks","previewing","filterOptions","selectedPlaybook"])),(0,l.Se)(["connected","reachedCampaignLimit"])),(0,l.Se)("playbooks",["hasRecommendations"])),{},{isLoading:function(){return this.$store.getters.isLoading(["playbooks","rules","filterOptions"])},unavailableRules:function(){var t=this;return this.selectedPlaybook.rules.filter((function(e){return!t.ruleAvailable(e)}))||[]}}),watch:{selectedPlaybook:function(t){t&&this.handleUsePlaybook()}},created:function(){var t=this;this.$store.dispatch("campaigns/fetchRulesetData").then((function(e){var s=e.rules;s.push.apply(s,(0,i.Z)(e.bigCommerceRules)),s.push.apply(s,(0,i.Z)(e.eddRules)),s.push.apply(s,(0,i.Z)(e.shopifyRules)),s.push.apply(s,(0,i.Z)(e.wooCommerceRules)),t.setRules(s)}))["catch"]((function(){})),this.filterOptions.length||this.$store.dispatch("templates/fetchFilterOptions").then((function(e){var s=e.body;t.setFilterOptions(s)}))["catch"]((function(){})),this.playbooks.length||this.$store.dispatch("playbooks/fetchPlaybooksData").then((function(){t.$store.getters.connected&&t.$store.dispatch("playbooks/fetchRecommendationsData")["catch"]((function(){}))}))["catch"]((function(){})),this.setDismissedRecommendations(),this.loadApiScript("omwpapi-playbooks-apijs",this.$constants.PLAYBOOKS_PREVIEW_ACCOUNT,this.$constants.PLAYBOOKS_PREVIEW_USER)},methods:(0,n.Z)((0,n.Z)({},(0,l.OI)("playbooks",["setDismissedRecommendations","setRules","setFilterOptions","setUpgradeRule"])),{},{handleUsePlaybook:function(){var t=this;if(this.$store.commit("templates/setActiveTemplate",this.selectedPlaybook),!this.$store.getters.connected)return this.$modal.show("not-connected");if(this.unavailableRules.length){var e=["exit-intent","inactivity-time"].find((function(e){return t.unavailableRules.includes(e)}));return e&&this.setUpgradeRule(e),this.$modal.show("upgrade-modal")}this.$modal.show("create-campaign-modal")}})},u=m,h=s(1001),b=(0,h.Z)(u,a,o,!1,null,null,null),y=b.exports},30727:function(t,e,s){s.d(e,{v:function(){return a}});var a={data:function(){return{tabs:{templates:{name:"Templates",route:{path:"templates",params:{tab:"popup"}}},playbooks:{name:"Playbooks",route:{path:"playbooks",params:{}}}}}}}},7268:function(t,e,s){s.d(e,{s:function(){return l}});var a=s(73421),o=s(64777),i=s(7977);const n=(t,e,s,a)=>{let o=document.getElementById(e);if(o)return o;if(!t)return void console.error("apiJsUrl not provided to loadApiScript");const n=document.getElementsByTagName("head")[0]||document.documentElement;return o=document.createElement("script"),o.type="text/javascript",o.id=e,o.src=t,o.async=!0,o.dataset.account=s,o.dataset.user=a,(0,i.isProduction)()||(o.dataset.env=(0,i.isDevelopment)()?"dev":i.currentEnv),n.appendChild(o),o},l={created(){this.listenApiLoaded(),(0,a.of)(),(0,a.ge)(),(0,a.O0)(),(0,a.vY)(),(0,a.Kp)(),this.$store.subscribe((t=>{const e=["templates/setLoadingPreview","templates/setPreviewing","templates/filterOptions","templates/templates","templates/permittedTypes","templates/recentTemplates","templates/popular","templates/setApiLoaded"],s=["route/ROUTE_CHANGED"];let a=t.type.startsWith("templates/")&&!e.includes(t.type);a||(a=s.includes(t.type)),a&&this.closeAllPreviews()}))},beforeDestroy(){(0,a.of)(!1),(0,a.ge)(!1),(0,a.vY)(!1),(0,a.O0)(!1),(0,a.Kp)(!1)},methods:{listenApiLoaded(t="addEventListener"){["om.Api.init","om.Main.init","om.Campaigns.init","om.Campaign.init"].forEach((e=>document[t](e,this.setApiLoaded)))},setApiLoaded(){this.listenApiLoaded("removeEventListener"),this.$store.commit("templates/setApiLoaded")},closeAllPreviews(){(0,a.IC)(),this.$store.commit("templates/setLoadingPreview",""),this.$store.commit("templates/setPreviewing","")},loadApiScript(t,e,s){return n(o.jk.apiJs(),t,e,s)}}}}}]);
//# sourceMappingURL=playbooks.8290d850.js.map