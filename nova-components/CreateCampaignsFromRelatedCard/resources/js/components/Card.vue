<template>
    <card class="flex flex-col items-center justify-center create-campaigns-from-related-card">
        <div class="px-3 py-5 w-full">
            <h3 class="text-center text-2xl text-80 font-dark mt-3 mb-3">Create Campaigns From Related Card</h3>
            <CreateFromRelated :card="card" @formSubmitted="formSubmitted" />
            <ProcessedHistory :card="card" ref="history"/>
            <CreateFromTemplate :card="card"/>
            <vue-confirm-dialog></vue-confirm-dialog>
        </div>
    </card>
</template>
<script>
import CreateFromRelated from './CreateFromRelated'
import ProcessedHistory from './ProcessedHistory'
import CreateFromTemplate from './CreateFromTemplate'
export default {
    props: [
        'card',
    ],
    components: {
        CreateFromRelated,
        ProcessedHistory,
        CreateFromTemplate
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
