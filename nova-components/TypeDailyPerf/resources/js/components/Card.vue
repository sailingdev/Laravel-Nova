<template>
    <card class="flex flex-col items-center justify-center" style="min-height: 200px">
        <div class="px-5 py-5 my-2">
            <div
                v-if="loading"
                class="rounded-lg flex items-center justify-center relative"
                :class="modeClass"
            >
                <loader class="text-60" />
            </div> 
            <div v-else class="ds-section min-w-full max-w-full w-full">
                <h1 class="text-center p-4">Daily Totals</h1>

                <div v-if="Object.entries(errorResponse).length > 0">
                    <div class="px-4 py-3 leading-normal text-red-100 bg-red-700 rounded-lg" role="alert">
                        <p> {{ errorResponse.message }} </p>
                    </div>
                </div>
              
                <div v-else class="table-card"> 
                    <table class="table table-fixed table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total Spend</th>
                                <th>Total Revenue</th>
                                <th>Total Profit</th>
                                <th>Total ROI</th>
                                <th>Yahoo Spend</th>
                                <th>Yahoo Revenue</th>
                                <th>Yahoo Profit</th>
                                <th>Yahoo ROI</th>
                                <th>Media Spend</th>
                                <th>Media Revenue</th>
                                <th>Media Profit</th>
                                <th>Media ROI</th>
                                </tr>
                        </thead>
                        <tbody class="bg-gray-200">
                            <tr v-for="(row, key) in rows" :key="key" class="bg-white border-4 border-gray-200">
                                <td>{{ row.date }}</td>
                                <td>${{ row.tot_spend }}</td>
                                <td>${{ row.tot_revenue }}</td>
                                <td>${{ row.tot_profit }}</td>
                                <td>{{ row.tot_roi }}%</td>
                                <td>${{ row.yahoo_spend }}</td>
                                <td>${{ row.yahoo_revenue }}</td>
                                <td>${{ row.yahoo_profit }}</td>
                                <td>{{ row.yahoo_roi }}%</td>
                                <td>${{ row.media_spend }}</td>
                                <td>${{ row.media_revenue }}</td>
                                <td>${{ row.media_profit }}</td>
                                <td>{{ row.media_roi }}%</td>
                            </tr>
                        </tbody>
                    </table>  

                </div>
                
            </div>
        </div>
      
 
    </card>
</template>

<script>
// import './index.css'
export default {
    data () {
        return {
            loading: false,
            errorResponse: {},
            rows: {}
        }
    },
    props: [
        'card',

        // The following props are only available on resource detail cards...
        // 'resource',
        // 'resourceId',
        // 'resourceName',
    ],

    mounted() { 
        this.loadDailySummary() 
    },

    methods: {
        loadDailySummary() {
            this.loading = true; 
            axios.get('/nova-vendor/'+this.card.component+'/daily-summary', [], {
                headers: {
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                // console.log(response)
                // this.errorResponse = error.response.data 
                this.rows = response.data.data.daily_summary 
            }).catch(error => { 
                this.errorResponse = error.response.data 
            })
            .finally(() => {
                this.loading = false
            });
        }
    }
}
</script>
<style>
.dashboard-main .card-panel {
    border: 2px solid red;
}
    .ds-section { 
        position: relative;
        width: 100% !important;
    }
    .table {
        display: table; 
    } 
    .table-striped > tbody > tr:nth-of-type(odd) {
      background-color: #f9f9f9;
    }
    .table-bordered th,
    .table-bordered td {
        border: 1px solid #ddd !important;
    }
    .table {
  width: 100%;
  max-width: 100%;
  margin-bottom: 20px;
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
</style>