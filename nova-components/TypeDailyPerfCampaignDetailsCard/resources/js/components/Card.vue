<template>
    <card class="flex flex-col items-center justify-center">
        <div class="flex w-full" style="border: 2px solid red">
            <nav class="bg-grey-light p-3 rounded font-sans w-full m-4 flex-grow"  style="border: 2px solid blue">
                <!-- <ul class="breadcrumb">
                    <li><a href="#">Tag: {{ typeTag }} </a></li>
                    <li><a href="#">start: {{ startDate }} </a></li>
                    <li><a href="#">End</a> {{ endDate }} </li>
                </ul> -->
            </nav>
            <div class="flex-grow-0">
                <CardQueryFilter
                    :columnChecker="columnChecker"
                    :selectMultiple="false"
                    @reloadData="reloadData"
                />
            </div>  
        </div>
       
        <!-- <p v-if="typeTag != ''"> 
            Type Tag: {{ typeTag }}
        </p> -->
        <h1 class="textcenter"> WORK IN PROGRESS (WIP)  </h1>
        <div class="w-full px-5 py-3 my-2 min-w-full max-w-full ds-section box-border"> 
            <FeedTotals :metricWidth="metricWidth" :typeTag="typeTag" :startDate="startDate" :endDate="endDate"
                :card="card" :triggerReload="triggerReload"/>
        </div>
    </card>
</template>

<script>
import CardQueryFilter from '../../../../../nova/resources/js/components/RevenueDriver/CardQueryFilter'
import FeedTotals from './FeedTotals'
export default {
    data() {
        return {
            typeTag: '',
            startDate: '',
            endDate: '',
            triggerReload: 0,
            metricWidth: this.card.metricWidth,
        }
    },
    props: [
        'card',
    ],
    components: {
        CardQueryFilter,
        FeedTotals
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
<style scoped>
 .breadcrumb {
    padding: .75rem 1rem;
    margin-bottom: 1rem;
    list-style: none;
    background-color: #eceeef;
    border-radius: .25rem;
}
.breadcrumb li {
    display: list-item;
    text-align: -webkit-match-parent;
    float: left;
}
</style>
