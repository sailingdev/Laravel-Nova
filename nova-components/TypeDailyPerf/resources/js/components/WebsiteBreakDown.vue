<template>
    <div class="website-summary-wrapper  pt-5 pb-5">
        <h1 class="text-center p-2">Break Down by Website</h1>

        <div v-if="loading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>

        <div v-else class="box-border clearfix w-full"> 
            <div v-if="websiteBreakDown.length < 1"> 
                <div class="px-4 py-3 leading-normal text-gray-100 bg-gray-700 rounded-lg text-center" role="alert">
                    <p> No data </p>
                </div> 
            </div>
            <div v-else>
                <div class="w-6/12 mt-10 box-border float-left p-5" v-for="(websiteData, index) in websiteBreakDown" :key="index" >
                    <div class="pt-5 pb-1 pl-6 pr-6 rounded-lg shadow-lg">
                        <h4 class="text-center mb-2"> {{ websiteData.website }} </h4>
                            <DynamicTable :data="websiteData.totals" :extractDataValues="true" :headerRows="websiteBreakDownTableHeader" 
                            :enableSearch="false"></DynamicTable>
                    </div>  
                </div>
            </div>
        </div>
    </div>
    
</template>
<script>
import DynamicTable from '../../../../../nova/resources/js/components/RevenueDriver/DynamicTable'
export default {
    name: "WebsiteBreakDown",
    data() {
        return {
            websiteData: {},
            extractDataValues: true,
            websiteBreakDown: [],
            loading: false
        }
    },
    props: {
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
            type: Boolean,
            default: false,
        }
    },
    components: {
        DynamicTable
    }, 
    computed: {
        websiteBreakDownTableHeader() {
            return [
                'DATE', 'TOTAL SPEND', 'TOTAL REVENUE', 'TOTAL PROFIT', 'TOTAL ROI',
            ]
        },
    },
     watch: {
        triggerReload(newVal, oldVal) {
            if (newVal) {
                this.loadWebsiteBreakDown()
            }
        }
    },
    mounted() {
        this.loadWebsiteBreakDown()
    },
    methods: {
        loadWebsiteBreakDown() {
            this.loading = true
            axios.get('/nova-vendor/'+ this.card.component + '/daily-summary-by-type-tags/website-break-down' +  
                '?type_tag=' + this.typeTag + 
                '&start_date=' + this.startDate + 
                '&end_date=' + this.endDate)
            .then(response => {  
                this.websiteBreakDown = response.data.data.daily_summary.website_break_down                
                this.loading = false
            }).catch(error => {   
                this.loading = false
                this.errorResponse = error.response.data
            }) 
        },

    }
}
</script>