<template>
    <card class="flex flex-col items-center justify-center" style="min-height: 200px">
        
        <div class="w-full flex flex-wrap px-5 py-5">
        
            <div class="w-3/4">
                <h1 class="text-left p-4">
                    <span v-if="loading" class="">Daily Totals</span>
                    <span v-else>Dashboard Metrics</span>
                </h1>
            </div>
             
            <div class="relative h-16 w-1/4 mt-2 mb-2 text-right"> 
                    
                <button 
                    @click="toggleFilter"
                    type="button" class="rounded active:outline-none active:shadow-outline focus:outline-none focus:shadow-outline">
                    <div class="dropdown-trigger h-dropdown-trigger flex items-center cursor-pointer select-none bg-30 px-3 border-2 border-30 rounded">
                        <icon
                            type="filter"
                            viewBox="0 0 17 17"
                            height="25"
                            width="25"
                            class="cursor-pointer text-60 -mb-1"
                        /> 
                    </div>
                </button>  
                <CardQueryFilter 
                    :filterOpen="filterOpen"
                    :columnChecker="columnChecker" :columnData="columnData" 
                    :filterEndpoint="cardDataEndpoint" 
                    @reloadData="reloadData"></CardQueryFilter> 
            </div>
             
        </div>
        
        <div class="px-5 py-5 my-2">
            <div
                v-if="loading"
                class="rounded-lg flex items-center justify-center relative"
            >
            
                <loader class="text-60" />
            </div> 
            <div v-else class="ds-section min-w-full max-w-full w-full" >
                
                <div class="flex flex-wrap -mx-3 mb-5">
                    <CardTrendMetric :card="dailyTotalSpendTrendMetric" :size="metricWidth[0]" :title="'Total Spend'"/>
                    <CardTrendMetric :card="dailyTotalRevenueTrendMetric" :loading="loading" :size="metricWidth[0]" :title="'Total Revenue'"/>
                    <CardTrendMetric :card="dailyTotalProfitTrendMetric" :loading="loading" :size="metricWidth[0]" :title="'Total Profit'"/>
                    <CardTrendMetric :card="dailyTotalRoiTrendMetric" :loading="loading" :size="metricWidth[0]" :title="'Total ROI'"/>
                </div>
               
              
                <h1 class="text-center p-4">Daily Totals</h1>

                <div v-if="Object.entries(errorResponse).length > 0">
                    <div class="px-4 py-3 leading-normal text-red-100 bg-red-700 rounded-lg" role="alert">
                        <p> {{ errorResponse.message }} </p>
                    </div>
                </div>
              
                <div v-else class="max-w-full w-full box-border"> 

                    <!-- Summary by totals and feed -->
                    <div class="table-card shadow-lg">
                        <div v-if="data.length < 1">
                            <div class="px-4 py-3 text-center leading-normal text-red-100 bg-red-700 rounded-lg" role="alert">
                                <p> No data available for the selected range  </p>
                            </div>
                        </div>
                        <DynamicTable v-else :data="data" :extractDataValues="true" :headerRows="dailyTotalsTableHeader" 
                            :enableSearch="false"></DynamicTable>
                    </div> 


                    <!-- Summary by website -->
                    <div class="website-summary-wrapper">
                        <h1 class="text-center p-4">Break Down by Website</h1>
                        <div class="box-border clearfix w-full"> 
                            <WebsiteBreakDown v-for="(websiteData, index) in websiteBreakDown" :key="index" 
                                :websiteData="websiteData" :headerRows="websiteBreakDownTableHeader"/>
                        </div>
                    </div>

                    <!-- Summary by campaign -->
                    <h1 class="text-center p-4">Break Down by Campaign</h1>
                    <div class="box-border clearfix w-full"> 
                        <CampaignBreakDown :campaignData="campaignBreakDown" :headerRows="campaignBreakDownTableHeader"/>
                    </div>

                </div>

                <div> 
                    <table class="table table-fixed table-striped table-bordered" style="display:none">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total Spend</th>
                                <th>Total Revenue</th>
                                <th>Total Profit</th>
                                <th>Total ROI</th>
                                <th>Yahoo Spend</th>
                                <th>Yahoo Revenue</th>
                                <th>Yahoo Profit</th>
                                <th>Yahoo ROI</th>
                                <th>Media Spend</th>
                                <th>Media Revenue</th>
                                <th>Media Profit</th>
                                <th>Media ROI</th>
                                </tr>
                        </thead>
                        <tbody class="bg-gray-200">
                            <tr v-for="(row, key) in rowsList" :key="key" class="bg-white border-4 border-gray-200">
                                <td>{{ row.date }}</td>
                                <td>${{ row.tot_spend }}</td>
                                <td>${{ row.tot_revenue }}</td>
                                <td>${{ row.tot_profit }}</td>
                                <td>{{ row.tot_roi }}%</td>
                                <td>${{ row.yahoo_spend }}</td>
                                <td>${{ row.yahoo_revenue }}</td>
                                <td>${{ row.yahoo_profit }}</td>
                                <td>{{ row.yahoo_roi }}%</td>
                                <td>${{ row.media_spend }}</td>
                                <td>${{ row.media_revenue }}</td>
                                <td>${{ row.media_profit }}</td>
                                <td>{{ row.media_roi }}%</td>
                            </tr>
                        </tbody>
                    </table>  
                </div>
                
            </div>
        </div>
       
    </card>
</template>

<script>
import CardTrendMetric from '../../../../../nova/resources/js/components/RevenueDriver/CardTrendMetric'
import CardQueryFilter from '../../../../../nova/resources/js/components/RevenueDriver/CardQueryFilter'
import DynamicTable from '../../../../../nova/resources/js/components/RevenueDriver/DynamicTable'
import WebsiteBreakDown from './WebsiteBreakDown'
import CampaignBreakDown from './CampaignBreakDown'  
export default {
    data () {
        return {
            loading: false,
            filterOpen: false,
            errorResponse: {},
            rowsList: {},
            metricWidth: this.card.metricWidth,
            dailyTotalSpendTrendMetric: {},
            dailyTotalRevenueTrendMetric: {},
            dailyTotalProfitTrendMetric: {},
            dailyTotalRoiTrendMetric: {},
            cardDataEndpoint: this.card.component + '/daily-summary-by-type-tags',
            columnData: ['All'],
            typeTag: [],
            startDate: '',
            endDate: '', 
            data: [],
            websiteBreakDown: [],
            campaignBreakDown: []
        }
    },
    props: [
        'card', 
    ],
    components: {
        CardTrendMetric,
        CardQueryFilter,
        DynamicTable,
        WebsiteBreakDown,
        CampaignBreakDown
    },

    mounted() {  
        this.loadDailySummary()
        this.loadTypeTags() 
        
    },

    methods: {
        loadDailySummary() {
            this.loading = true
            axios.get('/nova-vendor/'+this.cardDataEndpoint + 
                '?type_tag=' + this.typeTag + 
                '&start_date=' + this.startDate + 
                '&end_date=' + this.endDate)
            .then(response => { 
                this.rowsList = response.data.data.daily_summary.list
                this.data=this.rowsList
                this.dailyTotalSpendTrendMetric = 
                    this.prepareMetricTrendChartData('tot_spend', response.data.data.daily_summary.metrics.tot_spend)
                
                this.dailyTotalRevenueTrendMetric = 
                    this.prepareMetricTrendChartData('tot_revenue', response.data.data.daily_summary.metrics.tot_revenue)

                this.dailyTotalProfitTrendMetric = 
                    this.prepareMetricTrendChartData('tot_profit', response.data.data.daily_summary.metrics.tot_profit)

                this.dailyTotalRoiTrendMetric = 
                    this.prepareMetricTrendChartData('tot_roi', response.data.data.daily_summary.metrics.tot_roi)
                this.websiteBreakDown = response.data.data.daily_summary.website_break_down
 
                
                this.campaignBreakDown = response.data.data.daily_summary.campaign_break_down
                console.timeEnd('starting')
                this.loading = false
            }).catch(error => {   
                this.loading = false
                this.errorResponse = error.response.data
            }) 
        },

        prepareMetricTrendChartData(type, responseData) {
            
            if (typeof responseData === 'object' && responseData !== null) { 
                responseData['chartData'] = {
                    labels: Object.keys(responseData.trend),
                    series: [
                        _.map(responseData.trend, (value, label) => { 
                            return {
                                meta: label,
                                value: value,
                            }

                        }),
                    ]
                } 
                // responseData['helpText'] = 'In case we wish to display help texts for the metrics in the future'
                // responseData['helpWidth'] = '200'
                return responseData
            }
            return {}
        },
        toggleFilter() {
            this.filterOpen = this.filterOpen == true ? false :  true 
        },
        loadTypeTags() { 
            axios.get('/nova-vendor/' + this.card.component + '/type-tags')
            .then(response => { 
                this.columnData = response.data.data.type_tags
            }).catch(error => {    
                this.queryError = error.response.data.message
            }) 
        },
        reloadData(param) {
            this.typeTag = param.columnDataSelected
            this.startDate = param.startDate
            this.endDate = param.endDate
            this.filterOpen = false 
            this.loadDailySummary()
        }
    },
    computed: {
        columnChecker() {
            return [
                {
                    title: 'Type Tags',
                }
            ]
        },
        dailyTotalsTableHeader() {
            return [
                'DATE', 'TOTAL SPEND', 'TOTAL REVENUE', 'TOTAL PROFIT', 'TOTAL ROI',
                'YAHOO SPEND', 'YAHOO REVENUE',	'YAHOO PROFIT',	'YAHOO ROI', 
                'MEDIA SPEND',	'MEDIA REVENUE', 'MEDIA PROFIT', 'MEDIA ROI'
            ]
        },
        websiteBreakDownTableHeader() {
            return [
                'DATE', 'TOTAL SPEND', 'TOTAL REVENUE', 'TOTAL PROFIT', 'TOTAL ROI',
            ]
        },
        campaignBreakDownTableHeader() {
            return [
                'TYPE TAG', 'TOTAL SPEND', 'TOTAL REVENUE', 'TOTAL PROFIT', 'TOTAL ROI',
                'TOTAL CLICKS', 'TOTAL RPC', 'TOTAL CPA'
            ]
        }
    }
}
</script>
<style> 
    .ds-section { 
        position: relative;
        width: 100% !important;
    }
    .table {
        display: table; 
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
    } 
    .table-striped > tbody > tr:nth-of-type(odd) {
      background-color: #f9f9f9;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #ddd !important;
    } 
.table > thead > tr > th,
.table > tbody > tr > th,
.table > tfoot > tr > th,
.table > thead > tr > td,
.table > tbody > tr > td,
.table > tfoot > tr > td {
  padding: 8px;
  line-height: 1.42857143;
  vertical-align: top;
  border-top: 1px solid #ddd;
}
.table tr td {
    font-size: 12px;
  font-weight: bolder;
  text-align: center;
}
.table > thead > tr > th {
  vertical-align: bottom;
  border-bottom: 2px solid #ddd;
}
    thead tr th {
        background-color: #2d3748 !important; 
        color: #ffffff !important
    }
    .bg-gray-800 { background-color: #2d3748; }
    .bg-gray-200 { background-color: #edf2f7; }
    .bg-white { background-color: #fff; }
    .border-4 { border-width: 4px; }
    .border-gray-200 { border-color: #edf2f7; }
    /* .bg-gray-900 { background-color: #1a202c !important; border: 2px solid yellow; } */
    .table-auto { table-layout: auto; }
    .table-fixed { table-layout: fixed; }
    .min-w-full { min-width: 100%; }
    .max-w-full { max-width: 100%; }
    .w-full { width: 100%; }
    .text-center { text-align: center; }
    .p-4 { padding: 1rem; }
   .form-control {
  display: block;
  width: 100%;
  height: 34px;
  padding: 6px 12px;
  font-size: 14px;
  line-height: 1.42857143;
  color: #555;
  background-color: #fff;
  background-image: none;
  border: 1px solid #ccc;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
  -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
       -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
          transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}
.form-control:focus {
  border-color: #66afe9;
  outline: 0;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
          box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
}
.form-control::-moz-placeholder {
  color: #999;
  opacity: 1;
}
.form-control:-ms-input-placeholder {
  color: #999;
}
.form-control::-webkit-input-placeholder {
  color: #999;
}
.form-control::-ms-expand {
  background-color: transparent;
  border: 0;
}
.form-control[disabled],
.form-control[readonly],
fieldset[disabled] .form-control {
  background-color: #eee;
  opacity: 1;
}
.form-control[disabled],
fieldset[disabled] .form-control {
  cursor: not-allowed;
}
textarea.form-control {
  height: auto;
}
input[type="search"] {
  -webkit-appearance: none;
}
</style>