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
                            <button @click="viewPost(post)" type="button" class="mr-3 text-sm bg-green-500 hover:bg-green-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">View</button>
                            <button @click="editPost(post, key)" type="button" class="mr-3 text-sm bg-purple-500 hover:bg-purple-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Edit</button>
                            <button @click="deletePost(post, key)" type="button" class="text-sm bg-red-500 hover:bg-red-700 text-white py-1 px-2 rounded focus:outline-none focus:shadow-outline">Delete</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div> 
        <modal-overlay :modalStatus="showModal" @modalClosed="modalClosed">
            <ViewPost v-if="!editMode" :post="post" :setUpdateAlert="setUpdateAlert" :viewType="'post'" @switchToEditMode="switchToEditMode"/>
            <EditPost v-else :post="post" :card="card" @formUpdated="formUpdated"/>
        </modal-overlay>
    </div>
</template>
<script>
import ModalOverlay from '../../../../../nova/resources/js/components/RevenueDriver/ModalOverlay'
import ViewPost from './ViewPost'
import EditPost from './EditPost'
export default {
    name: 'PostLibrary',
    data () {
        return {
            postLibrary: [],
            loading: false,
            showModal: false,
            postReference: '',
            showModal: false,
            post: {},
            keyInView: null,
            editMode: false,
            setUpdateAlert: false,
        }
    },
    props: {
        card: {
            required: true
        }
    },
    components: {
        ModalOverlay,
        ViewPost,
        EditPost
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
        },
        viewPost (post) {
            this.post = post
            this.showModal = true
        },
        editPost (post, key) {
            this.keyInView = key
            this.post = post
            this.showModal = true
            this.editMode = true
        },
        modalClosed () {
            this.showModal = false
            this.editMode = false
        },
        switchToEditMode (post) {
            this.editMode = true
        },
        formUpdated (newUpdate) {
            this.post = newUpdate
            this.postLibrary[this.keyInView] = newUpdate
            this.editMode = false
            this.setUpdateAlert = true
            setTimeout(() => {
                this.setUpdateAlert = false
            }, 4000);
        },
        deletePost (post, key) {
        
            this.$confirm({
                message: `Are you sure you wish to delete this post?`,
                button: {
                    no: 'No',
                    yes: `Yes, I'm sure`
                },
                callback: confirm => {
                    if (confirm) {
                        console.log(confirm)
                       this.postLibrary.splice(key, 1)
                        axios.delete('/nova-vendor/' + this.card.component + '/delete-post', {
                            data: {
                                id: post.id
                            }
                        })
                        return true
                    }
                }
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