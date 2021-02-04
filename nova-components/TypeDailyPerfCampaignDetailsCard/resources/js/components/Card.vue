<template>
    <card class="flex flex-col items-center justify-center">
        <CardQueryFilter
            :columnChecker="columnChecker"
            :selectMultiple="false"
            @reloadData="reloadData"
        />  
        <h1 class="textcenter"> WORK IN PROGRESS (WIP)  </h1>
        <div class="w-full px-5 py-3 my-2 min-w-full max-w-full ds-section box-border"> 
            <!-- <FeedTotals :metricWidth="metricWidth" :typeTag="typeTag" :startDate="startDate" :endDate="endDate"
                :card="card" :triggerReload="triggerReload"/> -->
        </div>
    </card>
</template>

<script>
import CardQueryFilter from '../../../../../nova/resources/js/components/RevenueDriver/CardQueryFilter'
// import FeedTotals from '/FeedTotals'
export default {
    props: [
        'card',
    ],
    components: {
        CardQueryFilter,
        // FeedTotals
    },
    mounted() {
        //
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
        }
    }
}
</script>
