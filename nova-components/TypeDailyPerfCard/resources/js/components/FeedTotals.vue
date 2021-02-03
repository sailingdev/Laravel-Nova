<template>
    <div class="w-full pt-5 pb-5">
        <div class="w-full px-5 py-0">
            <h1 class="p-4" :class="loading ? 'text-center' : 'text-left'">
                <span v-if="loading" class="">Daily Totals</span>
                <span v-else>Dashboard Metrics</span>
            </h1> 
        </div>

        <div v-if="loading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>

        <div v-else>
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

            </div>

        </div>
    </div>
</template>
<script>
import DynamicTable from '../../../../../nova/resources/js/components/RevenueDriver/DynamicTable'
import CardTrendMetric from '../../../../../nova/resources/js/components/RevenueDriver/CardTrendMetric'
export default {
    name: "FeedTotals",
    data() {
        return {
            loading: false,
            data: [],
            dailyTotalSpendTrendMetric: {},
            dailyTotalRevenueTrendMetric: {},
            dailyTotalProfitTrendMetric: {},
            dailyTotalRoiTrendMetric: {},
            errorResponse: {},
        }
    },
    components: {
        CardTrendMetric,
        DynamicTable
    },
    props: {
        metricWidth: {
            type: Array,
            required: true
        },
        typeTag: {
            type: Array,
            required: true
        },
        startDate: {
            type: String,
            required: true
        },
        endDate: {
            type: String,
            required: true
        },
        card: { 
            required: true
        },
        triggerReload: {
            type: Number,
            default: false,
        }
    },
    watch: {
        triggerReload(newVal, oldVal) {
            if (newVal > oldVal) {
                this.loadFeedTotals()
            }
        }
    },
    computed: {
        dailyTotalsTableHeader() {
            return [
                'DATE', 'TOTAL SPEND', 'TOTAL REVENUE', 'TOTAL PROFIT', 'TOTAL ROI',
                'YAHOO SPEND', 'YAHOO REVENUE',	'YAHOO PROFIT',	'YAHOO ROI', 
                'MEDIA SPEND',	'MEDIA REVENUE', 'MEDIA PROFIT', 'MEDIA ROI'
            ]
        },
    },
    mounted() {  
        this.loadFeedTotals()
    },
    methods: {

        loadFeedTotals() {
            this.loading = true
            axios.post('/nova-vendor/'+this.card.component + '/daily-summary-by-type-tags/feed-totals', {
                type_tag: this.typeTag,
                start_date: this.startDate,
                end_date: this.endDate
            })  
            .then(response => {  
                this.data=response.data.data.daily_summary.list

                this.dailyTotalSpendTrendMetric = 
                    this.prepareMetricTrendChartData('tot_spend', response.data.data.daily_summary.metrics.tot_spend)
                
                this.dailyTotalRevenueTrendMetric = 
                    this.prepareMetricTrendChartData('tot_revenue', response.data.data.daily_summary.metrics.tot_revenue)

                this.dailyTotalProfitTrendMetric = 
                    this.prepareMetricTrendChartData('tot_profit', response.data.data.daily_summary.metrics.tot_profit)

                this.dailyTotalRoiTrendMetric = 
                    this.prepareMetricTrendChartData('tot_roi', response.data.data.daily_summary.metrics.tot_roi)
            }).catch(error => {   
                this.errorResponse = error.response.data
            }).finally(() => {
                this.loading = false
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
    }

}
</script>