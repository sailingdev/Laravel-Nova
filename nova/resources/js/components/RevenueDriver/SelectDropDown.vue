<template>
    <v-select  class="rd__column-selector"  :class="extraClasses" v-model="selectedOption" :multiple="selectMultiple" 
        :options="options" :filterable="allowFilter">
    </v-select>
</template>
<script>
export default {
    name: 'SelectDropDown',
    props: {
        extraClasses: {
            type: String,
            default: ''
        },
        selectedOption: {
            type: String,
            default: ''
        },
        selectMultiple: {
            type: Boolean,
            default: false
        },
        options: {
            type: Array,
            default: []
        },
        allowFilter: {
            type: Boolean,
            default: false
        }
    },
    watchers: {
        selectedOption (newVal, oldVal) {
            if (newVal != oldVal) {
                this.$emit('newOption', newVal)
            }
        }
    },
    mounted () {
        console.log('her')
    }
}
</script>