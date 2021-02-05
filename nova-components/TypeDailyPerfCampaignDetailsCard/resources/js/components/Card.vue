<template>
    <card class="flex flex-col items-center justify-center">
        <div class="flex w-full">
            <nav class="bg-grey-light p-3 rounded w-full m-4 flex-grow">
                <ul class="breadcrumb" v-if="typeTag != ''">
                    <li><span class="bg-grey-light text-orange-200">Tag: <b>{{ typeTag }}</b> </span></li>
                    <li><span>Start: <b>{{ startDate == '' ? 'default' : startDate }} </b></span></li>
                    <li><span>End: <b>{{ endDate == '' ? 'default' : endDate }}</b> </span></li>
                </ul>
            </nav>
            <div class="flex-grow-0">
                <CardQueryFilter
                    :columnChecker="columnChecker"
                    :selectMultiple="false"
                    @reloadData="reloadData"
                />
            </div>  
        </div> 
       
       <div class="w-full -mx-3 mb-5 p-3">
            <MetricDailyTotals :metricWidth="metricWidth" :typeTag="typeTag" :startDate="startDate" :endDate="endDate"
                :card="card" :triggerReload="triggerMetricsReload" :metrics="metrics" :dailyTotalsIsLoading="dailyTotalsIsLoading"
            />
       </div>

        <div class="w-full px-5 py-3 my-2 min-w-full max-w-full ds-section box-border"> 
            <FeedTotals :typeTag="typeTag" :startDate="startDate" :endDate="endDate"
                :card="card" :triggerReload="triggerReload"/>

            <WebsiteTotals :typeTag="typeTag" :startDate="startDate" :endDate="endDate"
                :card="card" :triggerReload="triggerReload"/>

            <DailyTotals :typeTag="typeTag" :startDate="startDate" :endDate="endDate"
                :card="card" :triggerReload="triggerReload" @renderTrendMetrics="renderTrendMetrics" 
                @dailyTotalsLoadingStatus="dailyTotalsLoadingStatus"/>
        </div>
    </card>
</template>

<script>
import CardQueryFilter from '../../../../../nova/resources/js/components/RevenueDriver/CardQueryFilter'
import FeedTotals from './FeedTotals'
import MetricDailyTotals from './MetricDailyTotals'
import WebsiteTotals from './WebsiteTotals'
import DailyTotals from './DailyTotals'
export default {
    data() {
        return {
            typeTag: '',
            startDate: '',
            endDate: '',
            triggerReload: 0,
            triggerMetricsReload: 0,
            metricWidth: this.card.metricWidth,
            dailyTotalsIsLoading: false,
            metrics: {}
        }
    },
    props: [
        'card',
    ],
    components: {
        CardQueryFilter,
        FeedTotals,
        MetricDailyTotals,
        WebsiteTotals,
        DailyTotals
    },
    computed: {
        columnChecker() {
            return [
                {
                    title: 'Type Tags',
                    load_from: '/api/v1/type-tags'
                }
            ]
        },
    },
    methods: {
        reloadData(param) {
            this.typeTag = param.columnDataSelected
            this.startDate = param.startDate
            this.endDate = param.endDate
            this.filterOpen = false 
            this.triggerReload++
        },
        dailyTotalsLoadingStatus(status) {
            this.dailyTotalsIsLoading = status
            if (!status) {
                this.triggerMetricsReload++
            }
        },
        renderTrendMetrics(metrics) {
            this.metrics = metrics
        }
        // new Date().toISOString().slice(0, 10)
    }
}
</script>
<style scoped>
 .breadcrumb {
    padding: .75rem 1rem;
    margin-bottom: 1rem;
    list-style: none;
    background-color: #eceeef;
    border-radius: .25rem;
    display: inline-block;
    padding: 0;
    margin-bottom: 0;
    background: none;
}
.breadcrumb li {
    display: list-item;
    text-align: -webkit-match-parent;
    float: left;
    float: left;
}
.breadcrumb li span {
    display: inline-block;
    padding: 12px 30px;
    position: relative;
    background: #eef0f4;
    margin: 0 0.57rem 0.42rem;
}
/* .breadcrumb li span:nth-child(1) {
     background: #green;
} */
.breadcrumb li span::before {
    content: '';
    display: block;
    position: absolute;
    top: 0;
    left: -10px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 39px 10px 0 0;
    border-color: transparent orange transparent transparent;
    -webkit-transition: all 0.2s ease-in-out;
    transition: all 0.2s ease-in-out;
}
.breadcrumb li span::after {
    content: '';
    display: block;
    position: absolute;
    top: 0;
    right: -10px;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 0 39px 10px;
    border-color: transparent transparent transparent orange;
    -webkit-transition: all 0.2s ease-in-out;
    transition: all 0.2s ease-in-out;
}
</style>
