<template>
 <!-- :key="`${card.component}.${card.uriKey}`" -->
  <div class="px-3 mb-6" :class="[getMetricSizeClass]" >
    <div class="card relative px-6 py-4 shadow-lg" :class="getPanelClass">
     
        <div class="flex mb-4">
          <h3 class="mr-3 text-base text-80 font-bold">{{ title }}</h3>

          <!-- @check -->
          <!-- <div v-if="helpText" class="absolute pin-r pin-b p-2 z-20">
            <tooltip trigger="click">
              <icon
                type="help"
                viewBox="0 0 17 17"
                height="16"
                width="16"
                class="cursor-pointer text-60 -mb-1"
              />

              <tooltip-content
                slot="content"
                v-html="helpText"
                :max-width="helpWidth"
              />
            </tooltip>
          </div> -->

        
        </div>

        <p class="flex items-center text-4xl mb-4">
          {{ formattedValue }}
          <span v-if="suffix" class="ml-2 text-sm font-bold text-80">{{
            formattedSuffix
          }}</span>
        </p>
        
        <div
          ref="chart"
          class="absolute pin rounded-b-lg ct-chart"
          style="top: 60%"
        />
 
    </div>
               
  </div> 
</template>

<script>
import _ from 'lodash'
import Chartist from 'chartist'
import 'chartist-plugin-tooltips'
import 'chartist/dist/chartist.min.css'
import { SingularOrPlural } from 'laravel-nova'
import 'chartist-plugin-tooltips/dist/chartist-plugin-tooltip.css'
import { CardSizes } from 'laravel-nova'

export default {
  name: 'BaseCardTrendMetric',
  data() {
    return {
      metricWidthClass: '',
      panelClass: false
    }
  },
  props: {

    title: {},
    
    size: {
      type: String,
    },
    
    helpText: {},

    helpWidth: {},
    
    value: {},
    
    chartData: {},
    
    maxWidth: {},
    
    prefix: '',

    suffix: '',
    
    suffixInflection: true,
    
    format: {
      type: String,
      required: true
    },

  },
 
  mounted() { 
    this.renderChart() 
    console.log(this.format)
  },

  methods: {

  renderChart() {
    
        //     // this.chartist.update(this.chartData)
      const values = this.getTrendValues(this.chartData.series[0])
      const low = Math.min(...values)
      const high = Math.max(...values)

  
      // Use zero as the graph base if the lowest value is greater than or equal to zero.
      // This avoids the awkward situation where the chart doesn't appear filled in.
      const areaBase = low >= 0 ? 0 : low

      this.chartist = new Chartist.Line(this.$refs.chart, this.chartData, {
        lineSmooth: Chartist.Interpolation.none(),
        fullWidth: true,
        showPoint: true,
        showLine: true,
        showArea: true,
        chartPadding: {
          top: 10,
          right: 0,
          bottom: 0,
          left: 0,
      },
      low,
      high,
      areaBase,
      axisX: {
        showGrid: false,
        showLabel: true,
        offset: 0,
      },
      axisY: {
        showGrid: false,
        showLabel: true,
        offset: 0,
      },
      plugins: [
        Chartist.plugins.tooltip({
          anchorToPoint: true,
          transformTooltipTextFnc: value => {
            let formattedValue = Nova.formatNumber(
              new String(value),
              this.format
            )

            if (this.prefix) {
              return `${this.prefix}${formattedValue}`
            }

            if (this.suffix) {
              const suffix = this.suffixInflection
                ? SingularOrPlural(value, this.suffix)
                : this.suffix

              return `${formattedValue} ${suffix}`
            }

            return `${formattedValue}`
          },
        }),
      ],
     })
  },
 
    calculateCardWidth(size) {
      // If the card's width is found in the accepted sizes return that class,
      // or return the default 1/3 class
      return CardSizes.indexOf(size) !== -1 ? `w-${size}` : 'w-1/3'
    },

    getTrendValues(series) {
        const values = series.map((e, i) => {
          return e.value
        }) 
        return values;
    }
  },

  computed: {
    isNullValue() {
      return this.value == null
    },

    formattedValue() { 
      if (!this.isNullValue) {
        
        const value = Nova.formatNumber(new String(this.value), this.format)

        return `${this.prefix}${value}`
      }

      return ''
    },

    formattedSuffix() {
      if (this.suffixInflection === false) {
        return this.suffix
      }

      return SingularOrPlural(this.value, this.suffix)
    },

     /**
     * The class given to the card wrappers based on its width
     */

    getMetricSizeClass() {
      // If  passing in 'large' as the value we want to force the
      // cards to be given the `w-full` class, otherwise we're letting
      // the card decide for itself based on its configuration
      return this.size == 'large' ? 'w-full' : this.calculateCardWidth(this.size)
    },

    /**
     * The class given to the card based on its size
     */
    getPanelClass() { 
      return this.size !== 'large' && this.size !== 'w-full' ? 'card-panel' : ''
    },
  },
  
}
</script>
<style scoped>
  .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); }
</style>

