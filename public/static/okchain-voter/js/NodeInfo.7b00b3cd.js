(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["NodeInfo"],{"48a4":function(t,e,a){},"707e":function(t,e,a){"use strict";var i=a("48a4"),s=a.n(i);s.a},8288:function(t,e,a){"use strict";var i=a("a83e"),s=a.n(i);s.a},8460:function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{class:"paginate-view"+(t.borderMode?" border-mode":"")},[t._t("default"),t.totalCount<=0?a("div",{staticClass:"empty-view"},[t._v("\n    "+t._s(t.loading?t.$t("common.loadingShortText"):t.$t("common.emptyText"))+"\n  ")]):t._e(),t.totalPage>1?a("div",{staticClass:"pagation-view"},[a("vue-paginate-al",{attrs:{prevText:t.$t("common.prevPage"),nextText:t.$t("common.nextPage"),totalPage:t.totalPage,currentPage:t.currentPage},on:{btnClick:t.onPage}})],1):t._e()],2)},s=[],n=(a("c5f6"),{props:{currentPage:{type:Number,default:1},totalCount:{type:Number,default:0},limit:{type:Number,default:50},loading:{type:Boolean,default:!0},borderMode:{type:Boolean,default:!1}},computed:{totalPage:function(){return(this.totalCount/this.limit|0)+(this.totalCount%this.limit>0?1:0)}},methods:{onPage:function(t){this.$emit("onPage",t),document.body.scrollTop=0,document.documentElement.scrollTop=0}}}),o=n,r=(a("c454"),a("2877")),c=Object(r["a"])(o,i,s,!1,null,"96b703b6",null);e["a"]=c.exports},"89f3":function(t,e,a){"use strict";var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"crumb-view"},t._l(t.crumbs,(function(e,i){return a("div",{key:i,staticClass:"crumb-item"},[i!=t.crumbs.length-1?[a("router-link",{attrs:{to:e.url}},[t._v(t._s(e.title))]),a("span",{staticClass:"crumb-space"},[t._v(">")])]:[a("span",[t._v(t._s(e.title))])]],2)})),0)},s=[],n={props:{crumbs:{type:Array,default:[]}}},o=n,r=(a("8288"),a("2877")),c=Object(r["a"])(o,i,s,!1,null,"44248d9c",null);e["a"]=c.exports},a83e:function(t,e,a){},c454:function(t,e,a){"use strict";var i=a("d6d1"),s=a.n(i);s.a},d6d1:function(t,e,a){},fa4c:function(t,e,a){"use strict";a.r(e);var i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"pc-inner-widget node-info-page"},[a("common-crumb-view",{attrs:{crumbs:t.crumbs}}),a("div",{staticClass:"top-info"},[a("div",{staticClass:"logo",style:"background-image:url("+t.dataInfo.description.logo+")"}),a("div",{staticClass:"brief"},[a("h2",{staticClass:"title"},[t._v(t._s(t.dataInfo.description.moniker)),t.dataInfo.description.website?a("a",{attrs:{target:"_blank",href:t.dataInfo.description.website}},[t._v(t._s(t.dataInfo.description.website))]):t._e()]),t.dataInfo.description.details?a("div",{staticClass:"desc"},[t._v(t._s(t.dataInfo.description.details))]):t._e()])]),t.dataInfo.description.details?a("div",{staticClass:"desc-mobile"},[t._v(t._s(t.dataInfo.description.details))]):t._e(),a("div",{staticClass:"node-info"},[a("div",{staticClass:"title"},[t._v(t._s(t.$t("节点信息")))]),a("div",{staticClass:"data-info"},[a("div",{staticClass:"data-item"},[a("div",{staticClass:"item-title"},[t._v(t._s(t.$t("得票数（OKT）")))]),a("div",{staticClass:"item-value"},[t._v(t._s(t.dataInfo.fmt_vote_token))])]),a("div",{staticClass:"data-item"},[a("div",{staticClass:"item-title"},[t._v(t._s(t.$t("得票率")))]),a("div",{staticClass:"item-value value-chg-up"},[t._v(t._s(t.votePercent)+"%")])]),a("div",{staticClass:"data-item"},[a("div",{staticClass:"item-title"},[t._v(t._s(t.$t("投票地址数")))]),a("div",{staticClass:"item-value"},[t._v(t._s(t.dataInfo.vote_num))])]),a("div",{staticClass:"data-item"},[a("div",{staticClass:"item-title"},[t._v(t._s(t.$t("可领取奖励（OKT）")))]),a("div",{staticClass:"item-value"},[t._v(t._s(t.dataInfo.fmt_reward_balance))])])])]),a("div",{staticClass:"container"},[a("common-paginate-view",{attrs:{borderMode:!1,limit:t.limit,loading:t.loading,totalCount:t.totalCount,currentPage:t.currentPage},on:{onPage:t.onPage}},[a("div",{staticClass:"common-table"},[a("div",{staticClass:"table-item table-header"},[a("div",{staticClass:"td-item"},[t._v("#")]),a("div",{staticClass:"td-item"},[t._v("投票地址")]),a("div",{staticClass:"td-item"},[t._v("投票数量（OKT）")]),a("div",{staticClass:"halfline"})]),a("div",{staticClass:"table-container"},t._l(t.dataList,(function(e,i){return a("div",{key:i,staticClass:"table-item"},[a("div",{staticClass:"td-item"},[t._v(t._s((t.currentPage-1)*t.limit+i+1))]),a("div",{staticClass:"td-item"},[t._v(t._s(e.voter_address))]),a("div",{staticClass:"td-item"},[t._v(t._s(e.fmt_tokens))]),a("div",{staticClass:"halfline"})])})),0)])])],1)],1)},s=[],n=(a("ac6a"),a("96cf"),a("3b8d")),o=(a("a481"),a("89f3")),r=a("8460"),c=a("b689"),l=a("408b"),d={data:function(){return{page_code:"node-info-page-1",log_type:"node-info-pv",node_name:this.$route.params.node_name,crumbs:[{title:this.$t("nav.homePage"),url:"/"},{title:""}],dataInfo:{description:{logo:"",website:"",details:"",moniker:"Node Name"},vote_token:"0.0000",vote_num:"0"},menuIndex:0,menuList:[{title:this.$t("投票地址")},{title:this.$t("分红记录")},{title:this.$t("投票记录")},{title:this.$t("撤票记录")}],dataList:[],limit:20,totalCount:0,currentPage:1,loading:!0,votePercent:"0.00"}},created:function(){this.node_name?(this.startLoading(),this.initData(),this.onPage(1)):this.$router.replace("/")},components:{CommonPaginateView:r["a"],CommonCrumbView:o["a"]},methods:{changeMenuIndex:function(t){this.menuIndex=t,this.loading=!0,this.totalCount=0,this.dataList=[],this.onPage(1)},onPage:function(t){this.currentPage=t,this.getRecord()},getRecord:function(){var t=Object(n["a"])(regeneratorRuntime.mark((function t(){var e,a,i,s,n,o=this;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,Object(l["e"])({start:(this.currentPage-1)*this.limit,limit:this.limit,validator_address:this.node_name}).then(this.pluginFilterResponse).catch(this.pluginFilterResponse);case 2:if(e=t.sent,a=e.success,i=e.data,s=e.extra,this.stopLoading(),this.loading=!1,a){t.next=10;break}return t.abrupt("return");case 10:i.forEach((function(t){t.fmt_tokens=o.formatCoinNumber(t.tokens),t.voter_address=o.$root.isMobile?c["a"].formatWalletAddress(t.voter_address):t.voter_address})),n=s.total_count,this.totalCount="undefined"===typeof n?i.length:n,this.dataList=i;case 14:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}(),initData:function(){var t=Object(n["a"])(regeneratorRuntime.mark((function t(){var e,a,i,s,n;return regeneratorRuntime.wrap((function(t){while(1)switch(t.prev=t.next){case 0:return t.next=2,Object(l["d"])({validator_address:this.node_name}).then(this.pluginFilterResponse).catch(this.pluginFilterResponse);case 2:if(e=t.sent,a=e.success,i=e.data,s=e.extra,this.stopLoading(),a){t.next=9;break}return t.abrupt("return");case 9:i.fmt_vote_token=this.formatCoinNumber(i.vote_token),i.fmt_reward_balance=this.formatCoinNumber(i.reward_balance),this.crumbs[1].title=i.description.moniker,this.dataInfo=i,n=parseFloat(s.total_delegator_shares,10)||0,n>0&&(this.votePercent=((parseFloat(i.delegator_shares,10)||0)/n*100).toFixed(2));case 15:case"end":return t.stop()}}),t,this)})));function e(){return t.apply(this,arguments)}return e}()}},u=d,m=(a("707e"),a("2877")),v=Object(m["a"])(u,i,s,!1,null,"2789276b",null);e["default"]=v.exports}}]);