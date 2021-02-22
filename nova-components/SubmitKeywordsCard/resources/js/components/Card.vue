<template>
    <card class="flex flex-col items-center justify-center">
        <div class="px-3 py-3">
           <SubmitForm :card="card" />
           <KeywordBatches :card="card" />
        </div>
    </card>
</template>

<script>
import SubmitForm from './SubmitForm'
import KeywordBatches from './KeywordBatches'
export default {
    props: [
        'card',
    ],
    components: {
        SubmitForm,
        KeywordBatches
    },
    mounted() {
        //
    },
}
</script>
