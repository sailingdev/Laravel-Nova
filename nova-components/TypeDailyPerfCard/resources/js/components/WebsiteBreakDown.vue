<template>
    <div class="website-summary-wrapper  pt-5 pb-5">
        <h1 class="text-center p-2">Break Down by Website</h1>

        <div v-if="loading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>

        <div v-else class="box-border clearfix w-full"> 
            <div v-if="websiteBreakDownData.length < 1"> 
                <div class="px-4 py-3 leading-normal text-gray-100 bg-gray-700 rounded-lg text-center" role="alert">
                    <p> No data </p>
                </div> 
            </div> 

              <div v-else class=" mt-10 box-border p-5 rounded-lg shadow-lg"> 
                    <DynamicTable :values="values" :columns="tableHeaders" :showFilter="false"></DynamicTable>
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
            websiteBreakDownData: [],
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
                { title:"Website" },
                { title:"Total Spend($)" },
                { title:"Total Revenue($)" },
                { title:"Total Profit($)" },
                { title:"Total ROI($)" }
            ]
        },
        values() {
            let values = []
            if (this.websiteBreakDownData.length > 0) {
                this.websiteBreakDownData.forEach((record, index) => {
                    const row = {
                        "Website": record.site,
                        "Total Spend($)": record.tot_spend != null ? parseFloat(record.tot_spend) : 0,
                        'Total Revenue($)': record.tot_revenue != null ? parseFloat(record.tot_revenue) : 0,
                        'Total Profit($)': record.tot_revenue != null ? parseFloat(record.tot_revenue) : 0,
                        'Total ROI($)': record.tot_roi != null ? parseFloat(record.tot_roi) : 0
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
            axios.post('/nova-vendor/'+this.card.component + '/daily-summary-by-type-tags/website-break-down', {
                type_tag: this.typeTag,
                start_date: this.startDate,
                end_date: this.endDate
            }) 
            .then(response => {  
                this.websiteBreakDownData = response.data.data.daily_summary.website_break_down         
                this.loading = false
            }).catch(error => {   
                this.loading = false
                this.errorResponse = error.response.data
            }) 
        },

    }
}
</script>