<template>
    <card class="flex flex-col items-center justify-center w-full" style="min-height: 200px">
        
        <CardQueryFilter 
            :columnChecker="columnChecker"
            @reloadData="reloadData"
        /> 
    
        <div class="w-full px-5 py-3 my-2 min-w-full max-w-full ds-section box-border">

            <AllWebsiteBreakDown :typeTag="typeTag" :startDate="startDate" :endDate="endDate"  
                :card="card" :triggerReload="triggerReload"/>
        </div>
       
    </card>
</template>

<script>
import CardQueryFilter from '../../../../../nova/resources/js/components/RevenueDriver/CardQueryFilter'
import AllWebsiteBreakDown from './AllWebsiteBreakDown'
export default {
    data () {
        return {
            loading: false,
            filterOpen: false,
            triggerReload: 0,
            rowsList: {},
            metricWidth: this.card.metricWidth,
            columnData: ['All'],
            typeTag: [],
            startDate: '',
            endDate: '', 
            data: [],
        }
    },
    props: [
        'card', 
    ],
    components: {
        CardQueryFilter,
        AllWebsiteBreakDown
    },

    methods: {
        reloadData(param) {
            this.typeTag = param.columnDataSelected
            this.startDate = param.startDate
            this.endDate = param.endDate
            this.filterOpen = false 
            this.triggerReload++
        }
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
    }
}
</script>