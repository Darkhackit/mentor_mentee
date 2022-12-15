<template>
    <div class="p-3">
        <div class="flex justify-end ">
            <label for="my-modal-5" class="btn bg-red-500 focus:bg-red-500">Add Mentor</label>
            <input type="checkbox" id="my-modal-5" class="modal-toggle" />
            <div class="modal">
                <div class="modal-box relative">
                    <label for="my-modal-5" id="close7" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                    <h3 class="text-lg font-bold pb-3">Add Mentor</h3>
                    <form @submit.prevent="addMentor">
                        <div>
                            <input v-model="form.name" type="text" placeholder="Enter Name" class="input w-full" />
                        </div>
                        <div class="m-2">
                            <input v-model="form.position" type="text" placeholder="Enter Position" class="input w-full" />
                        </div>
                        <div class="pt-3">
                            <input @change="getInput" type="file" class="file-input w-full" />
                        </div>
                        <div class="pt-5 float-right">
                            <button type="submit" class="btn bg-red-500">Add Mentee</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <vue-good-table
            :columns="columns"
            :rows="rows"
            :is-loading="loading"
            :search-options="{
            enabled: true
  }">
            <template
                #table-row="props"
            >
                <div
                    v-if="props.column.label === 'Action'"
                    class="text-nowrap"
                >

                    <span @click.prevent="showEditMentor(props.row.id)" class="text-nowrap cursor-pointer">edit</span>|
                    <span @click.prevent="deleteUser(props.row.id)" class="text-nowrap cursor-pointer">delete</span>
                </div>
                <div
                    v-if="props.column.label === 'Image'"
                    class="text-nowrap"
                >
                    <div class="flex items-center space-x-3">
                        <div class="avatar">
                            <div class="mask mask-squircle w-12 h-12">
                                <img :src="getPics + props.row.image" alt="Avatar Tailwind CSS Component" />
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </vue-good-table>
        <input type="checkbox" id="my-modal-9" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box relative">
                <label for="my-modal-9" id="close5" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <h3 class="text-lg font-bold pb-3">Edit Mentor</h3>
                <form @submit.prevent="updateMentor">
                    <div>
                        <input type="text" v-model="editedForm.name" placeholder="Enter Name" class="input w-full" />
                    </div>
                    <div class="m-2">
                        <input v-model="editedForm.position" type="text" placeholder="Enter Position" class="input w-full" />
                    </div>
                    <div class="pt-3">
                        <input @change="getInputed" type="file" class="file-input w-full" />
                    </div>
                    <div class="pt-5 float-right">
                        <button type="submit" class="btn bg-red-500">Update Mentor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {useToast} from "vue-toastification";

export default {
    name:"Mentor",
    data() {
        return {
            form: {
                name:'',
                image:'',
                position:''
            },
            editedForm: {
                name:'',
                id:'',
                image:'',
                position:''
            },
            loading:true,
            columns:[
                {
                    label: 'Image',
                    field: 'image',
                },
                {
                    label: 'Name',
                    field: 'name',
                },
                {
                    label: 'Action',
                    field: 'action',
                },
            ],
            rows:[]
        }
    },
    setup() {
        const toast = useToast();
        return { toast }
    },
    computed: {
        getPics() {
            return "/profile/"
        }
    },
    methods: {
        async  addMentor() {
            try {
                let form = new FormData()
                form.append('name',this.form.name)
                form.append('image',this.form.image)
                form.append('position',this.form.position)
                await axios.post('/api/add-mentor',form)
                await this.getMentee()
                this.form.name = ""
                this.form.image = ""
                this.toast.success("Mentor Add Successfully");
                document.getElementById('close7').click()
            }catch (e) {
                console.log(e.response)
            }
        },
        async updateMentor() {
            try {
                let form = new FormData()
                form.append('name',this.editedForm.name)
                form.append('image',this.editedForm.image)
                form.append('position',this.editedForm.position)
                form.append('id',this.editedForm.id)
                await axios.post('/api/update-mentor',form)
                await this.getMentee()
                this.toast.success("Mentor Updated Successfully");
                document.getElementById('close5').click()

                this.editedForm.name = ""
                this.editedForm.image = ""
                this.editedForm.id = ""

            }catch (e) {
                console.log(e.response)
            }
        },
        async showEditMentor(id) {
            try {
                let response = await axios.get(`/api/mentor/edit/${id}`)
                this.editedForm.name = response.data.name
                this.editedForm.position = response.data.position
                this.editedForm.id = response.data.id
                console.log(response.data)
            }catch (e) {
                console.log(e.response)
            }
            document.getElementById('my-modal-9').checked = true
        },
        async deleteUser(id) {
            if(confirm("Are you sure")){
                try {
                    await axios.get(`/api/mentor/delete/${id}`)
                    await this.getMentee()
                    this.toast.success("Mentor Deleted Successfully");
                }catch (e) {
                    console.log(e.response)
                }
            }
        },
        getInput(e) {
            this.form.image= e.target.files[0]
        },
        getInputed(e) {
            this.editedForm.image= e.target.files[0]
        },
        async getMentee() {
            try {
                this.loading = true
                let response = await axios.get('/api/mentor')
                this.rows = response.data
                this.loading = false

            }catch (e) {
                console.log(e.response)
            }
        }
    },
    async mounted() {
        await this.getMentee()
    }
}
</script>

<style scoped>

</style>
