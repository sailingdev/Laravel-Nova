<template>
    <div class="rd__dynamic-table">
        <vue-table-dynamic v-if="shouldDisplay" :params="params" ref="table"></vue-table-dynamic>
  </div>
</template>
<script>
import VueTableDynamic from 'vue-table-dynamic'
import VueHtml2pdf from 'vue-html2pdf' 
export default {
    name: 'DynamicTable',
    components: {
        VueTableDynamic,
        VueHtml2pdf
    },
    props: {
        data: {
            required: true, 
            default: []
        },
        enableSearch: {
            default: true
        },
        header: {
            default: 'row'
        },
        border: {
            default: true,
        },
        stripe: {
            default: true,
        },
        showCheck: {
            default: false
        }, 
        pagination: {
            default: true
        },
        pageSize: {
            default: 31,
        },
        pageSizes: {
            default: () => {
                return [31, 10, 20, 30, 40, 50, 100, 150, 200, 500]
            }
        },
        extractDataValues: {
            type: Boolean,
            default: false
        },
        headerRows: {
            type: Array
        } 
        // filter: [
                //     {
                //         column: 0, 
                //         content: [
                //             {
                //                 text: '> 2', 
                //                 value: 2
                //             }, 
                //             {
                //                 text: '> 4', 
                //                 value: 4
                //             }
                //         ], 
                //             method: (value, tableCell) => { return tableCell.data > value }
                //     }, 
                //     {
                //         column: 3, 
                //         content: 
                //         [
                //             {
                //                 text: '2019-01-01', 
                //                 value: '2019-01-01'
                //             }, 
                //             {
                //                 text: '2019-02-02', 
                //                 value: '2019-02-02'
                //             }
                //         ], 
                //         method: (value, tableCell) => { 
                //             return String(tableCell.data).toLocaleLowerCase().includes(String(value).toLocaleLowerCase()) 
                //         }
                //     }
                // ],
    },
    data() {
        return {
            params: {
                // data: [
                 
                // ]
            },
            shouldDisplay: false
        }
    },
    methods: {
        onSelect (isChecked, index, data) { 
        },
        onSelectionChange (checkedDatas, checkedIndexs, checkedNum) { 
        },
        prepForRender() {
            this.params.header = this.header
            this.params.border = this.border
            this.params.stripe = this.stripe
            this.params.showCheck = this.showCheck
            this.params.enableSearch = this.enableSearch
            this.params.pagination = this.pagination
            this.params.pageSize = this.pageSize
            this.params.pageSizes =  this.pageSizes
          
            
            let container = []
            if (this.extractDataValues) {
                this.data.forEach(element => { 
                    container.push(Object.values(element)) 
                });   
            }
            else {
                container = this.data
            }

            let sortColumns = container[0]
            if (this.headerRows.length > 0) {
                container.unshift(this.headerRows)
                sortColumns = this.headerRows
            }
            this.params.data = container

            let sort = []
            sortColumns.forEach((column, index) => {
                sort.push(index)
            })
            this.params.sort = sort

            return true
        },

         /*
            Generate Report using refs and calling the
            refs function generatePdf()
        */
        generateReport () {
            this.$refs.html2Pdf.generatePdf()
        }
    },
    mounted() {   
        return this.shouldDisplay = this.prepForRender() ? true : false
    }
}
</script>
<style>
    .rd__dynamic-table {
        display: block !important; 
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        box-sizing: border-box; 
        overflow-x: hidden; 
    } 

 
    .v-table-dynamic {
        position:relative;
        box-sizing:border-box;
        font-family: inherit !important;
        font-size: 12px !important;
        color:inherit !important;
        
        padding-bottom: 10px;
        overflow: hidden; 
        width: 100%;
        margin-bottom: 20px;  
    }
    .v-table-dynamic > div:first-child {
        min-width: 100% !important; 
        max-width: 100% !important;
    } 
    .v-table-dynamic.is-fit-height{
        height: auto !important;
    }

     
    .v-table {
        position:relative;
        box-sizing: border-box; 
        border: none;
        border-color: #edf2f7
    }
  
    .v-table.v-show-border {
       border: 1px solid #ddd !important;
    }
   
   
  
    .v-table-body  {
        margin:0;padding:0;
        box-sizing:border-box;
        overflow:hidden; 
        background-color: #edf2f7;
    }
     
    
 
    .v-table-row.is-striped{
        background-color: #F9F9F9;
    }
    .v-table-row {
        box-sizing:border-box;
        border:none;
        border-bottom:none !important;
        background-color: #FFFFFF;
        display: flex;
        max-width: 100% !important; 
        width: auto !important; 
        margin-left: 0px; 
        height: auto !important;
        border-color: #edf2f7 !important;
    }
    .v-table-body .v-table-row {
        height: 55px !important; 
        justify-content: center;
        text-align:center;
    } 
    .vue-scrollbar {
        overflow: auto !important;
    }
    
    .table-cell {
        box-sizing: border-box;
        flex: 1 1 100% !important;
        margin: 0 0 0 0 !important;
        padding: 0 !important;
        height: 100% !important;
        position: relative !important;
        white-space:pre-line !important;
        border: 1px solid #ddd !important; 
        padding: 8px !important;
        font-size: 12px !important;
        font-weight: bolder !important;
        text-align: center !important;
        vertical-align: top !important; 
        justify-content: center !important; 
    }
  
    .table-cell-inner { 
        height: 100% !important;
        display: inline-block !important;
        top: 0 !important;
        left:0 !important;
        width: 100% !important;
        bottom: 0 !important;
    }
      .table-cell-content {
        
        box-sizing:border-box;
        font-size: 12px;
        font-weight: bold !important;
        text-align: center !important;
        display: inline-block !important;
        width: 100%;
        height: 100%;
        max-width: 100%; 
        vertical-align: top !important;   
        word-wrap: break-word !important;
        word-break: break-word;
        white-space: pre-line !important; 
        overflow-wrap: break-word !important; 
        text-overflow: clip !important;
        padding: 0px !important;
        margin: 0 !important; 
    }
    .v-table-row.is-header {
        padding: 0 !important;
    }
     .v-table-row.is-header .table-cell { 
        overflow: hidden !important;
        margin: 0 !important;
        /* height: auto !important; */
        /* worn */
        display: flex !important;
        flex-flow: column !important;
        height: 100% !important;
        min-height: 70px !important;
     }
    .v-table-row.is-header .table-cell-content {
        height: 100% !important;
        letter-spacing: .05em !important;
        text-transform: uppercase;
        font-size: .70rem !important;
        text-align: center !important; 
        font-weight: 600;
        line-height: 1.42857143 !important;
        vertical-align: top !important; 
        justify-content: center !important;
        /* padding-top: 5px !important; */
        flex: 1 0 100% !important;
    }
      .table-cell.is-header,
    .v-table-row.is-header {
        background-color: #2d3748 !important; 
        color: #ffffff !important;
    } 
   
   
.table-sort{
  width: 100% !important;
  margin-left: 0px !important;
  left: 0% !important;
  top: 0%;
  bottom: 0%;
  height: 100% !important;
  position: absolute !important;
  vertical-align: middle;
  z-index: 99992 !important;
  display: none;
}
.v-table-row.is-header .table-cell:hover .table-sort {
     display: block;
}
  .sort-btns{
    width: 0 !important;
    height: 0 !important;
    border: 5px solid transparent;
    position: absolute !important;
    left: 40% !important;;
    cursor: pointer;
  } 
  .sort-ascending{
    border-bottom-color: #C0C4CC;
    top: 0px !important;
  }
  .sort-descending{
    border-top-color: #C0C4CC;
      bottom: -4px !important; 
  }
  .sort-ascending.activated{
    border-bottom-color: #ddd;
  }
  .sort-descending.activated{
    border-top-color: #ddd;
  }
    .table-pagination {
        margin: 5px 10px 5px !important;
    }
 

</style>
