<template>
    <div class="w-full pt-5 pb-5">
        
        <h1 class="text-center p-4">Break Down By Website</h1>
        
        <div v-if="loading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>

        <div v-else >

            <div v-if="typeTag == ''">
                <div class="px-4 py-3 text-center leading-normal text-gray-100 bg-gray-400 rounded-lg" role="alert">
                    Please select a type tag
                </div>
            </div>

            <div v-else>
                <div v-if="Object.entries(errorResponse).length > 0">
                    <div class="px-4 py-3 leading-normal text-red-100 bg-red-700 rounded-lg" role="alert">
                        <p> {{ errorResponse.message }} </p>
                    </div>
                </div>

                <div v-else class="max-w-full w-full box-border"> 

                    <!-- Summary by totals and feed -->
                    <div class="table-card shadow-lg">
                        <div v-if="data.length < 1">
                            <div class="px-4 py-3 text-center leading-normal text-red-100 bg-red-700 rounded-lg" role="alert">
                                <p> No data available for this tag and the selected range  </p>
                            </div>
                        </div>
                        <DynamicTable v-else :data="data" :extractDataValues="true" :headerRows="dailyTotalsTableHeader" 
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
    name: "WebsiteTotals",
    data() {
        return {
            loading: false,
            data: [],
            errorResponse: {},
        }
    },
    components: {
        DynamicTable
    },
    props: {
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
        }
    },
    watch: {
        triggerReload(newVal, oldVal) {
            if (newVal > oldVal && this.typeTag != '') {
                this.loadWebsiteTotals()
            }
        }
    },
    computed: {
        dailyTotalsTableHeader() {
            return [
                'WEBSITE', 'SPEND', 'REVENUE', 'PROFIT', 'ROI', 'CLICKS', 'RPC', 'CPA'
            ]
        },
    },
    methods: {

        loadWebsiteTotals() {
            this.loading = true
            axios.get('/nova-vendor/' + this.card.component + '/campaign-details-by-type-tags/website-totals?' + 
                'type_tag=' +  this.typeTag + '&' + 
                'start_date=' + this.startDate + '&' + 
                'end_date=' + this.endDate
            )
            .then(response => {  
                this.data = response.data.data.website_totals
            }).catch(error => {  
                this.errorResponse = error.response.data
            }).finally(() => {
                this.loading = false
            })
        }
    }

}
</script>