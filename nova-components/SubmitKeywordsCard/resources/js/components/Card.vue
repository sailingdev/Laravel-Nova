<template>
    <card class="flex flex-col items-center justify-center">
        <div class="px-3 py-3">
           <SubmitForm :card="card" @formSubmitted="formSubmitted"/>
           <KeywordBatches :card="card" ref="keywordBatch"/>
        </div>
    </card>
</template>

<script>
import SubmitForm from './SubmitForm'
import KeywordBatches from './KeywordBatches'
export default {
    data () {
        return {
            reloadBatch: false
        }
    },
    props: [
        'card',
    ],
    components: {
        SubmitForm,
        KeywordBatches
    },
    methods: {
        formSubmitted () {
            this.$refs.keywordBatch.loadKeywordBatches()
        } 
    }
}
</script>
