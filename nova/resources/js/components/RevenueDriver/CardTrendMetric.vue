<template> 
  <BaseCardTrendMetric v-if="renderCard"
    :title="title"

    :help-text="card.helpText"
    :help-width="card.helpWidth"
    :format="format"
    
    :value="card.value"
    :chart-data="card.chartData" 
    
    :prefix="card.prefix"
    :suffix="card.suffix"
    :suffix-inflection="card.suffixInflection" 
    :size="size"
  /> 
  
</template>

<script> 
import BaseCardTrendMetric from './Base/BaseCardTrendMetric' 

export default {
  name: 'CardTrendMetric',
  
  components: {
    BaseCardTrendMetric,
  },

  props: {

    card: {
      type: Object,
      required: true,
    },

    size: {
      type: String,
      default: '1/4'
    },

    title: {
      type: String,
      required: true
    },
     
    format: {
      type: String,
      default: '0[.]00a' 
    }
  },

  data: () => ({ 
    renderCard: false
  }),
  
  mounted() {
    this.renderCard = true
  }
   
}
</script>
