<template>
    <div class="w-full box-border">
        <div class="pb-1 pl-6 pr-6 rounded-lg shadow-lg"> 
          <DynamicTable :data="campaignData" :extractDataValues="true" :headerRows="headerRows" 
                :enableSearch="true"></DynamicTable> 
        </div>  
    </div>
</template>
<script>
import DynamicTable from '../../../../../nova/resources/js/components/RevenueDriver/DynamicTable'
export default {
    name: "CampaignBreakDown",
    props: { 
        campaignData: {
            type: Array,
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