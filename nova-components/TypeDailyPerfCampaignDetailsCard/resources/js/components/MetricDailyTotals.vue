<template>
    <div class="w-full">
         <h1 class="text-left p-4">Campaign Metrics</h1>
        <div v-if="dailyTotalsIsLoading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>
        <div v-else>
            <div v-if="typeTag != ''" class="flex flex-wrap">
                 <!-- :format="'0,0.00'"  -->
                <CardTrendMetric :card="dailyTotalSpendTrendMetric" :size="metricWidth[0]" :title="'Total Spend'"/>
                <CardTrendMetric :card="dailyTotalRevenueTrendMetric" :loading="dailyTotalsIsLoading" :size="metricWidth[0]" :title="'Total Revenue'"/>
                <CardTrendMetric :card="dailyTotalProfitTrendMetric" :loading="dailyTotalsIsLoading" :size="metricWidth[0]" :title="'Total Profit'"/>
                <CardTrendMetric :card="dailyTotalRoiTrendMetric" :loading="dailyTotalsIsLoading" :size="metricWidth[0]" :title="'Total ROI'"/>

                <CardTrendMetric :card="dailyTotalRpcTrendMetric" :loading="dailyTotalsIsLoading" :size="metricWidth[0]" :title="'Total RPC'"/>
                <CardTrendMetric :card="dailyTotalCpaTrendMetric" :loading="dailyTotalsIsLoading" :size="metricWidth[0]" :title="'Total CPA'"/>
                <CardTrendMetric :card="dailyTotalClickTrendMetric" :loading="dailyTotalsIsLoading" :size="metricWidth[0]" :title="'Total Clicks'"/>
                <div class="px-3 mb-6 w-1/4">
                    <div class="card relative px-6 py-4 shadow-lg card-panel pt-10 target-cpa-card">

                        <div class="flex flex-col items-center mb-4 mt-2">
                            <p> Days Available: 
                                <span class="ml-2 text-sm font-bold text-80 text-2xl">
                                    {{ targetCpa.days_available }}
                                </span> 
                            </p>
                         
                             <p> Target CPA: 
                                <span class="ml-2 text-sm font-bold text-80 text-2xl">
                                    ${{ targetCpa.target_cpa == null ? 0 : targetCpa.target_cpa }}
                                </span> 
                            </p>
                        </div>
                    
                    </div>
               
                </div> 
            </div>
        </div>
    </div>
</template>
<script>
import CardTrendMetric from '../../../../../nova/resources/js/components/RevenueDriver/CardTrendMetric'
export default {
    name: 'MetricDailyTotals',
    data () {
        return { 
            dailyTotalSpendTrendMetric: {},
            dailyTotalRevenueTrendMetric: {},
            dailyTotalProfitTrendMetric: {},
            dailyTotalRoiTrendMetric: {},
            
            dailyTotalRpcTrendMetric: {},
            dailyTotalCpaTrendMetric: {},
            dailyTotalClickTrendMetric: {},
            targetCpa: []
        }
    },
    props: {
        metricWidth: {
            type: Array,
            required: true
        },
         typeTag: {
            type: String,
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
        },
        dailyTotalsIsLoading: {
            type: Boolean,
            default: false
        },
        metrics: {
            type: Object,
            default: (() => {})
        }
    },
    watch: {
        triggerReload(newVal, oldVal) {
            if (newVal > oldVal && this.typeTag != '') { 
                this.renderMetrics()
            }
        }
    },
    components: {
        CardTrendMetric
    },
    methods: {

        renderMetrics() {
            this.dailyTotalSpendTrendMetric = 
                this.prepareMetricTrendChartData('tot_spend', this.metrics.tot_spend)
            
            this.dailyTotalRevenueTrendMetric = 
                this.prepareMetricTrendChartData('tot_revenue', this.metrics.tot_revenue)

            this.dailyTotalProfitTrendMetric = 
                this.prepareMetricTrendChartData('tot_profit', this.metrics.tot_profit)

            this.dailyTotalRoiTrendMetric = 
                this.prepareMetricTrendChartData('tot_roi', this.metrics.tot_roi)

            this.dailyTotalRpcTrendMetric = 
                this.prepareMetricTrendChartData('tot_rpc', this.metrics.tot_rpc)

            this.dailyTotalCpaTrendMetric = 
                this.prepareMetricTrendChartData('tot_cpa', this.metrics.tot_cpa)

            this.dailyTotalClickTrendMetric = 
                this.prepareMetricTrendChartData('tot_clicks', this.metrics.tot_clicks)
            
            const targetCpa = this.metrics.target_cpa

            this.targetCpa = targetCpa.length > 0 ? targetCpa[0] : []
        },
        
        // loadMetricDailyTotals() {   
        //     this.loading = true
        //     axios.get('/nova-vendor/' + this.card.component + '/campaign-details-by-type-tags/daily-totals?' + 
        //         'type_tag=' +  this.typeTag + '&' + 
        //         'start_date=' + this.startDate + '&' + 
        //         'end_date=' + this.endDate
        //     )
        //     .then(response => {  
        //         this.dailyTotalSpendTrendMetric = 
        //             this.prepareMetricTrendChartData('tot_spend', response.data.data.metrics.tot_spend)
                
        //         this.dailyTotalRevenueTrendMetric = 
        //             this.prepareMetricTrendChartData('tot_revenue', response.data.data.metrics.tot_revenue)

        //         this.dailyTotalProfitTrendMetric = 
        //             this.prepareMetricTrendChartData('tot_profit', response.data.data.metrics.tot_profit)

        //         this.dailyTotalRoiTrendMetric = 
        //             this.prepareMetricTrendChartData('tot_roi', response.data.data.metrics.tot_roi)

        //         this.dailyTotalRpcTrendMetric = 
        //             this.prepareMetricTrendChartData('tot_rpc', response.data.data.metrics.tot_roi)

        //         this.dailyTotalCpaTrendMetric = 
        //             this.prepareMetricTrendChartData('tot_cpa', response.data.data.metrics.tot_cpa)

        //         this.dailyTotalClickTrendMetric = 
        //             this.prepareMetricTrendChartData('tot_clicks', response.data.data.metrics.tot_clicks)
                
        //         const targetCpa = response.data.data.metrics.target_cpa

        //         this.targetCpa = targetCpa.length > 0 ? targetCpa[0] : []
        //     }).catch(error => {   
        //         this.errorResponse = error.response.data
        //     }).finally(() => {
        //         this.loading = false
        //     })
        // },

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
<style>
    .target-cpa-card {
        border-bottom: 3px solid orange;
    }
</style>