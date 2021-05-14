<template>
    <card class="flex flex-col items-center justify-center create-campaigns-from-related-card">
        <div class="px-3 py-5 w-full">
            <h3 class="text-center text-2xl text-80 font-dark mt-3 mb-3">Create Campaigns From Related Card</h3>
            <BatchesToProcess :card="card" @formSubmitted="formSubmitted" />
            <ProcessedHistory :card="card" ref="history"/>
        </div>
    </card>
</template>
<script>
import BatchesToProcess from './BatchesToProcess'
import ProcessedHistory from './ProcessedHistory'
export default {
    props: [
        'card',
    ],
    components: {
        BatchesToProcess,
        ProcessedHistory
    },
    methods: {
        formSubmitted () {
            this.$refs.history.processHistory()
        }
    },
}
</script>
<style>
    .create-campaigns-from-related-card {
        font-family: DM Sans;
    }
</style>
