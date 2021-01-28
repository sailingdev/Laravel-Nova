<template>
    <div class="w-6/12 mt-10 box-border float-left p-5">
        <div class="pt-5 pb-1 pl-6 pr-6 rounded-lg shadow-lg">
             <h4 class="text-center mb-2"> {{ websiteData.website }} </h4>
            <div class="">
                <DynamicTable :data="websiteData.totals" :extractDataValues="true" :headerRows="headerRows" 
                :enableSearch="false"></DynamicTable>
            </div>
        </div>  
    </div>
</template>
<script>
import DynamicTable from '../../../../../nova/resources/js/components/RevenueDriver/DynamicTable'
export default {
    name: "WebsiteBreakDown",
    props: { 
        websiteData: {
            type: Object,
            required: true
        },
        extractDataValues: {
            type: Boolean,
            default: true
        },
        headerRows: {
            type: Array,
            required: true
        }
    },
    components: {
        DynamicTable
    }, 
}
</script>