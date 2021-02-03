<template>
    <card class="flex flex-col items-center justify-center w-full" style="min-height: 200px">
        
        <div class="relative h-16 w-full mt-2 mb-2 text-right pt-4 pr-5">         
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
           
            cardDataEndpoint: this.card.component + '/daily-summary-by-type-tags',
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

    mounted() {   
        this.loadTypeTags()
    },

    methods: {
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
            this.triggerReload++
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
    }
}
</script>