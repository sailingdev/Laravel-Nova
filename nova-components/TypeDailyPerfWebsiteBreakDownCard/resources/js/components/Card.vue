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
            triggerReload: false,
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
            this.triggerReload = true
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
<style> 
    .ds-section { 
        position: relative;
    }
    .table {
        display: table; 
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
    } 
    .table-striped > tbody > tr:nth-of-type(odd) {
      background-color: #f9f9f9;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #ddd !important;
    } 
.table > thead > tr > th,
.table > tbody > tr > th,
.table > tfoot > tr > th,
.table > thead > tr > td,
.table > tbody > tr > td,
.table > tfoot > tr > td {
  padding: 8px;
  line-height: 1.42857143;
  vertical-align: top;
  border-top: 1px solid #ddd;
}
.table tr td {
    font-size: 12px;
  font-weight: bolder;
  text-align: center;
}
.table > thead > tr > th {
  vertical-align: bottom;
  border-bottom: 2px solid #ddd;
}
    thead tr th {
        background-color: #2d3748 !important; 
        color: #ffffff !important
    }
    .bg-gray-800 { background-color: #2d3748; }
    .bg-gray-200 { background-color: #edf2f7; }
    .bg-white { background-color: #fff; }
    .border-4 { border-width: 4px; }
    .border-gray-200 { border-color: #edf2f7; }
    /* .bg-gray-900 { background-color: #1a202c !important; border: 2px solid yellow; } */
    .table-auto { table-layout: auto; }
    .table-fixed { table-layout: fixed; }
    .min-w-full { min-width: 100%; }
    .max-w-full { max-width: 100%; }
    .w-full { width: 100%; }
    .text-center { text-align: center; }
    .p-4 { padding: 1rem; }
   .form-control {
  display: block;
  width: 100%;
  height: 34px;
  padding: 6px 12px;
  font-size: 14px;
  line-height: 1.42857143;
  color: #555;
  background-color: #fff;
  background-image: none;
  border: 1px solid #ccc;
  border-radius: 4px;
  -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
          box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
  -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
       -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
          transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
}
.form-control:focus {
  border-color: #66afe9;
  outline: 0;
  -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
          box-shadow: inset 0 1px 1px rgba(0,0,0,.075), 0 0 8px rgba(102, 175, 233, .6);
}
.form-control::-moz-placeholder {
  color: #999;
  opacity: 1;
}
.form-control:-ms-input-placeholder {
  color: #999;
}
.form-control::-webkit-input-placeholder {
  color: #999;
}
.form-control::-ms-expand {
  background-color: transparent;
  border: 0;
}
.form-control[disabled],
.form-control[readonly],
fieldset[disabled] .form-control {
  background-color: #eee;
  opacity: 1;
}
.form-control[disabled],
fieldset[disabled] .form-control {
  cursor: not-allowed;
}
textarea.form-control {
  height: auto;
}
input[type="search"] {
  -webkit-appearance: none;
}
</style>