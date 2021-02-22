<template>
    <div class="keyword-batches-wrapper my-5">
        <h1 class="text-center text-3xl text-80 font-dark pt-4">Batch Status</h1>
        <p class="mt-0 text-sm text-center text-gray-500">Within the last 48 hours</p>

        <div v-if="loading" class="rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>

        <div v-else class="w-full mx-auto p-8">   
            <div class="shadow-md">
                
                <div v-if="Object.entries(errorResponse).length > 0">
                    <div class="mt-4 mb-4 px-4 py-3 leading-normal text-red-100 bg-red-700 rounded-lg" role="alert">
                        <h4 class="mt-2 mb-2"> {{ errorResponse.message }} </h4>
                        <p v-for="(error, index) in errorResponse.errors" :key="index" class="text-sm"> 
                            => {{ error[0] }} 
                        </p>
                    </div>
                </div>

                <div v-if="batches.length < 1"> 
                    <div class="px-4 py-3 leading-normal text-gray-100 bg-gray-700 rounded-lg text-center" role="alert">
                        <p @click="loadKeywordBatches"> No record </p>
                    </div> 
                </div>
                
                <div v-else> 
                    <div v-for="(batch, index) in batches" :key="index" class="tab w-full overflow-hidden border-t">
                        <input class="absolute opacity-0 " :id="`tab-${index}`" type="checkbox" name="tabs">
                        <label class="block p-5 leading-normal cursor-pointer batch-label" :for="`tab-${index}`">
                            <p>Batch ID: <span class="font">{{ batch.batch_id }}</span></p>
                            <p>Batch status: 
                                <button type="button" class="mr-2 text-white p-1 rounded leading-none" 
                                    :class="batch.batch_status == 'processing' ? 'bg-orange-600' : 'bg-green-600'">
                                    {{ batch.batch_status }}
                                </button>
                            </p>
                        </label>
                        <div class="tab-content w-full overflow-hidden border-l-2 bg-gray-100 border-indigo-500 leading-normal">
                          
                            <table class="text-left m-4" style="border-collapse:collapse">
                                <thead>
                                    <tr>
                                        <th class="py-4 px-6 bg-grey-lighter font-sans font-medium uppercase text-sm text-grey border-b border-grey-light">Keyword</th>
                                        <th class="py-4 px-6 bg-grey-lighter font-sans font-medium uppercase text-sm text-grey border-b border-grey-light">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(keyword, index2) in batch.keywords" :key="index2" class="hover:bg-blue-lightest">
                                        <td class="py-4 px-6 border-b border-grey-light">{{ keyword.keyword }}</td>
                                        <td class="py-4 px-6 border-b border-grey-light text-center">{{ keyword.status  }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div> 
                </div>

            </div>
        </div>
    </div>
</template>
<script>
export default {
    name: "KeywordBatches",
    data () {
        return {
            loading: false,
            batches: [],
            errorResponse: {}
        }
    },
    props: {
        card: {
            required: true
        }
    },
    mounted () {
        this.loadKeywordBatches()
    },
    methods: {
        loadKeywordBatches () {
            this.loading = true
            axios.get('/nova-vendor/' + this.card.component + '/load-keyword-batches')
            .then(response => {
                this.batches = response.data.data
                console.log(response.data.data)
            }).catch(error => {
                this.errorResponse = error.response.data
            })
            .finally(() => {
                this.loading = false
            })
        }
    }
}
</script>
<style scoped>
    .keyword-batches-wrapper {
        min-width: 650px;
        max-width: 100%;
    }
    .keyword-batches-wrapper .batch-label p {
        font-size: 16px;
    }
    .keyword-batches-wrapper .batch-label span {
        font-weight: bolder;
    }
    .keyword-batches-wrapper .border-t {
        border-top: 1px solid blue
    }
    .tab-content {
        max-height: 0;
        -webkit-transition: max-height .35s;
        -o-transition: max-height .35s;
        transition: max-height .35s; 
        width: 100% !important;
    }
    /* :checked - resize to full height */
    .tab input:checked ~ .tab-content {
        max-height: 100vh;
    }
    /* Label formatting when open */
    .tab input:checked + label{
        font-size: 1.25rem; /*.text-xl*/
        padding: 1.25rem; /*.p-5*/
        border-left-width: 2px; /*.border-l-2*/
        border-color: #6574cd; /*.border-indigo*/
        background-color: #f8fafc; /*.bg-gray-100 */
        color: #6574cd; /*.text-indigo*/
    }
    /* Icon */
    .tab label::after {
    float:right;
    right: 0;
    top: 0;
    display: block;
    width: 1.5em;
    height: 1.5em;
    line-height: 1.5;
    font-size: 1.25rem;
    text-align: center;
    -webkit-transition: all .35s;
    -o-transition: all .35s;
    transition: all .35s;
    }
    /* Icon formatting - closed */
    .tab input[type=checkbox] + label::after {
        content: "+";
        font-weight:bold; /*.font-bold*/
        border-width: 1px; /*.border*/
        border-radius: 9999px; /*.rounded-full */
        border-color: #b8c2cc; /*.border-grey*/
        top: 40px;
    }
    .tab input[type=radio] + label::after {
    content: "\25BE";
    font-weight:bold; /*.font-bold*/
    border-width: 1px; /*.border*/
    border-radius: 9999px; /*.rounded-full */
    border-color: #b8c2cc; /*.border-grey*/
    }
    /* Icon formatting - open */
    .tab input[type=checkbox]:checked + label::after {
    transform: rotate(315deg);
    background-color: #6574cd; /*.bg-indigo*/
    color: #f8fafc; /*.text-grey-lightest*/
    }
    .tab input[type=radio]:checked + label::after {
    transform: rotateX(180deg);
    background-color: #6574cd; /*.bg-indigo*/
    color: #f8fafc; /*.text-grey-lightest*/
    }
    .bg-orange-600 { background-color: #dd6b20; }
    .bg-green-600 { background-color: #38a169; }
</style>