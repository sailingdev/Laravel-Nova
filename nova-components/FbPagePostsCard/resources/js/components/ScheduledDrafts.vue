<template>
    <button @click="loadScheduledDrafts">Reload</button>
</template>
<script>
export default {
    name: 'ScheduledDrafts',
    data () {
        return {
            scheduledDrafts: []
        }
    },
    props: {
        card: {
            required: true
        }
    },
    mounted () {
        this.loadScheduledDrafts()
    },
    methods: {
        loadScheduledDrafts () {
            axios.get('/nova-vendor/' + this.card.component + '/load-scheduled-drafts')  
            .then(response => {  
                this.scheduledDrafts = response.data.data
            }).catch(error => {   
                this.errorResponse = error.response.data
            }).finally(() => {
                this.loading = false
            })
        }
    }
}
</script>