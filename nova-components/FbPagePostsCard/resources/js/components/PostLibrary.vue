<template>
    <div class="mt-32 w-90p m-auto">
        
        <div class="t-display-header relative">
            <h1 class="text-center text-3xl text-80 font-dark px-4 py-5">Post Library</h1>
            <button @click="loadPostLibrary"  class="absolute right-0 mr-3 text-sm bg-purple-500 hover:bg-purple-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Reload</button>
        </div>
        <div v-if="loading" class="px-3 py-4 rounded-lg flex items-center justify-center relative">
            <loader class="text-60" />
        </div>

        <div class="px-3 py-4 flex justify-center" v-else>
            <table class="w-full text-md shadow-md rounded mb-4 table-striped table-bordered">
                <thead class="bg-black ">
                    <tr class="border-b">
                        <th class="text-left p-3 px-5">POST REFERENCE</th>
                        <th class="text-left p-3 px-5">URL</th>
                        <th class="text-left p-3 px-5">TEXT</th>
                        <th class="text-left p-3 px-5">ACTION</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-b hover:bg-orange-100 bg-white" v-for="(post, key) in postLibrary" :key="key">
                        <td class="p-3 px-5">{{ post.reference }}</td>
                        <td class="p-3 px-5">{{ post.url }}</td>
                        <td class="p-3 px-5">
                            {{ post['text'].length > 60  ? post['text'].substring(0, 60) + '...' : post.text }}
                        </td>
                        <td class="p-3 px-5 flex justify-end">
                            <button type="button" class="mr-3 text-sm bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">View</button>
                            <button type="button" class="mr-3 text-sm bg-purple-500 hover:bg-purple-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Edit</button>
                            <button type="button" class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Delete</button></td>
                    </tr>
                </tbody>
            </table>
        </div> 
        
    </div>
</template>
<script>
export default {
    name: 'PostLibrary',
    data () {
        return {
            postLibrary: [],
            loading: false
        }
    },
    props: {
        card: {
            required: true
        }
    },
    mounted () {
        this.loadPostLibrary()
    },
    methods: {
        loadPostLibrary () {
            this.loading = true
            axios.get('/nova-vendor/' + this.card.component + '/load-post-library')  
            .then(response => {  
                this.postLibrary = response.data.data
            }).catch(error => {   
                this.errorResponse = error.response.data
            }).finally(() => {
                this.loading = false
            })
        }
    }
}
</script>
<style scoped>
    table tr td, table tr th {
        font-size: 14px;
    }
</style>