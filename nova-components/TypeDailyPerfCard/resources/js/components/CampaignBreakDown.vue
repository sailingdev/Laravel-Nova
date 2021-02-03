<template>
    <div class="w-full box-border pt-5 pb-5">
        <h1 class="text-center p-4">Break Down by Campaign</h1>

        <div v-if="loading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>

        <div v-else class="pb-1 pl-6 pr-6 rounded-lg shadow-lg"> 

            <div v-if="campaignBreakDown.length < 1"> 
                <div class="px-4 py-3 leading-normal text-gray-100 bg-gray-500 rounded-lg text-center" role="alert">
                    <p> No data </p>
                </div> 
            </div>

          <DynamicTable v-else :data="campaignBreakDown" :extractDataValues="true" :headerRows="campaignBreakDownTableHeader" 
                :enableSearch="true"></DynamicTable> 
        </div>  
    </div>
</template>
<script>
import DynamicTable from '../../../../../nova/resources/js/components/RevenueDriver/DynamicTable'
export default {
    name: "CampaignBreakDown",
    data() {
        return {
            campaignBreakDown: [],
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
            type: Number,
            default: false,
        }
    },
    components: {
        DynamicTable
    }, 
    computed: {
        campaignBreakDownTableHeader() {
            return [
                'TYPE TAG', 'TOTAL SPEND', 'TOTAL REVENUE', 'TOTAL PROFIT', 'TOTAL ROI',
                'TOTAL CLICKS', 'TOTAL RPC', 'TOTAL CPA'
            ]
        }
    },
    watch: {
        triggerReload(newVal, oldVal) {
            if (newVal > oldVal) {
                this.loadCampaignBreakDown()
            }
        }
    },
    mounted() {
        this.loadCampaignBreakDown()
    }, 
    methods: {
        loadCampaignBreakDown() {
            this.loading = true 
            axios.post('/nova-vendor/'+this.card.component + '/daily-summary-by-type-tags/campaign-break-down', {
                type_tag: this.typeTag,
                start_date: this.startDate,
                end_date: this.endDate
            }) 
            .then(response => {
                this.campaignBreakDown = response.data.data.daily_summary.campaign_break_down                
                this.loading = false
            }).catch(error => {   
                this.loading = false
                this.errorResponse = error.response.data
            }) 
        },

    }
}
</script>