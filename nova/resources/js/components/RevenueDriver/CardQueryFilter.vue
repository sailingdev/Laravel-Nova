<template>
    <div v-if="filterOpen" class="rd__card-query-filter filter-wrapper p-4 w-60  text-left shadow-lg rounded-lg">
        
        <div v-if="columnChecker.length > 0">
            <h4 class="p-2"> {{ columnChecker[0].title }} </h4>
            <v-select class="rd__column-selector" v-model="columnDataSelected" multiple 
                :options="paginated" @search="query => search = query" :filterable="true">
                    <li slot="list-footer" class="pagination text-grey-600">
                        <button @click="offset -= 10" :disabled="!hasPrevPage">Prev</button>
                        <button @click="offset += 10" :disabled="!hasNextPage">Next</button>
                    </li>
                </v-select>
          
        </div>
       
        <div class="flex mt-4">
            <div class="mb-5 flex-grow mr-2">
                 <h4 class="p-2 text-base text-80 font-bold">Start Date</h4>
                <date-time-picker :placeholder="new Date().toDateString()" @change="startDateChanged"
                    :value="startDate" :dateFormat="'Y-m-d'" :enableTime="false" :altFormat="'Y-m-d'"></date-time-picker>
            </div>

            <div class="mb-5 flex-grow ml-2">
                <h4 class="p-2 text-base text-80 font-bold">End Date</h4>
                <date-time-picker :placeholder="new Date().toDateString()" @change="endDateChanged"
                    :value="endDate" :dateFormat="'Y-m-d'" :enableTime="false" :altFormat="'Y-m-d'"></date-time-picker>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <button @click="reloadData" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded shadow-sm" type="button">
                Load
            </button>
            <p class="inline-block align-baseline font-bold text-sm text-blue hover:text-blue-darker" href="#"></p>
        </div>
       
    </div> 
</template>
<script>
export default {
    name: 'CardQueryFilter',

    data() {
        return { 
            columnDataSelected: [],
            startDate: '',
            endDate: '',
            search: '',
            offset: 0,
            limit: 10,
        }
    },
    props: {
        columnChecker: {
            type: Array
        },
        filterOpen: {
            type: Boolean,
            default: false
        },
        columnData: {
            type: Array,
            required: true
        }
    }, 
    methods: {
        reloadData() {
            this.$emit("reloadData", {
                columnDataSelected: this.columnDataSelected,
                startDate: this.startDate,
                endDate: this.endDate
            })
        },
        startDateChanged(data) {
           this.startDate = data
        },
        endDateChanged(data) {
            this.endDate = data
        }
    },
    computed: {
        filtered () {
            return this.columnData.filter(country => country.includes(this.search));
        },
        paginated () {
            return this.filtered.slice(this.offset, this.limit + this.offset);
        },
        hasNextPage () {
            const nextOffset = this.offset + 10;
            return Boolean(this.filtered.slice(nextOffset, this.limit + nextOffset).length);
        },
        hasPrevPage () {
            const prevOffset = this.offset - 10;
            return Boolean(this.filtered.slice(prevOffset, this.limit + prevOffset).length);
        }
    },
}
</script>
<style scoped> 
    .rd__card-query-filter {
       position: absolute;
       z-index: 9900;
       min-width: 500px;
       max-width: 100%; 
       right: 10%;
       background: #fff;
    }  
    .rd__column-selector > input  {
        padding: 6px 10px !important; 
    }
    .pagination {
        display: flex;
        margin: 1.25rem 1.25rem 0; 
        border-top: 1px solid #cccccc;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
       -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        font-size: 16px; 
    }
    .pagination button {
        flex-grow: 1;  
        padding: 12px 14px;
    }
     .pagination button:first-child {
        border-right: 1px solid #ccc;
    }
  .pagination button:hover {
    cursor: pointer; 
  }
</style>
