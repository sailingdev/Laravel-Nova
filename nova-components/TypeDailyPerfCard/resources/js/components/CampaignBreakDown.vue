<template>
    <div class="w-full box-border pt-5 pb-5">
        <h1 class="text-center p-4">Break Down by Campaign</h1>

        <div v-if="loading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>

        <div v-else class="pb-1 pl-6 pr-6 rounded-lg shadow-lg"> 

            <div v-if="campaignBreakDownData.length < 1"> 
                <div class="px-4 py-3 leading-normal text-gray-100 bg-gray-500 rounded-lg text-center" role="alert">
                    <p> No data </p>
                </div> 
            </div>

          <DynamicTable v-else :values="values" :columns="tableHeaders" ></DynamicTable> 
        </div>  
    </div>
</template>
<script>
import DynamicTable from '../../../../../nova/resources/js/components/RevenueDriver/DynamicTable'
export default {
    name: "CampaignBreakDown",
    data() {
        return {
            campaignBreakDownData: [],
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
        tableHeaders() { 
            return [
                { title:"Type Tag" },
                { title:"Total Spend($)" },
                { title:"Total Revenue($)" },
                { title:"Total Profit($)" },
                { title:"Total ROI($)" },
                { title:"Total Clicks($)" },
                { title:"Total RPC($)" },
                { title:"Total CPA($)" },
            ]
        },
        values() {
            let values = []
            if (this.campaignBreakDownData.length > 0) {
                this.campaignBreakDownData.forEach((record, index) => {
                    const row = {
                        "Type Tag": record.type_tag,
                        "Total Spend($)": record.tot_spend != null ? parseFloat(record.tot_spend) : 0,
                        'Total Revenue($)': record.tot_revenue != null ? parseFloat(record.tot_revenue) : 0,
                        'Total Profit($)': record.tot_revenue != null ? parseFloat(record.tot_revenue) : 0,
                        'Total ROI($)': record.tot_roi != null ? parseFloat(record.tot_roi) : 0 ,
                        'Total Clicks($)': record.tot_clicks != null ? parseFloat(record.tot_clicks) : 0,
                        'Total RPC($)': record.tot_rpc != null ? parseFloat(record.tot_rpc) : 0,
                        'Total CPA($)': record.tot_cpa != null ? parseFloat(record.tot_cpa) : 0
                    };
                    values.push(row)
                }); 
            }
            return values
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
                this.campaignBreakDownData = response.data.data.daily_summary.campaign_break_down                
                this.loading = false
            }).catch(error => {   
                this.loading = false
                this.errorResponse = error.response.data
            }) 
        },

    }
}
</script>