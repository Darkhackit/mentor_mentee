<template>
    <div class="p-3">
    <div class="flex justify-end ">
        <label for="my-modal-3" class="btn bg-red-500 focus:bg-red-500">Add Mentee</label>

        <!-- Put this part before </body> tag -->
        <input type="checkbox" id="my-modal-3" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box relative">
                <label for="my-modal-3" id="close1" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <h3 class="text-lg font-bold pb-3">Add Mentee</h3>
                 <form @submit.prevent="addMentee">
                    <div>
                        <input type="text" v-model="form.name" placeholder="Enter Name" class="input w-full" />
                    </div>
                     <div class="pt-3">
                         <input v-model="form.email" type="text" placeholder="Email" class="file-input w-full" />
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
        :is-loading="loading"
        :rows="rows"
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

                <span @click.prevent="showEdit(props.row.id)" class="text-nowrap cursor-pointer">edit</span>|
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

        <input type="checkbox" id="my-modal-4" class="modal-toggle" />
        <div class="modal">
            <div class="modal-box relative">
                <label for="my-modal-4" id="close2" class="btn btn-sm btn-circle absolute right-2 top-2">✕</label>
                <h3 class="text-lg font-bold pb-3">Edit Mentee</h3>
                <form @submit.prevent="updateMentee">
                    <div>
                        <input type="text" v-model="editForm.name" placeholder="Enter Name" class="input w-full" />
                    </div>
                    <div>
                        <input type="text" v-model="editForm.email" placeholder="Email" class="input w-full" />
                    </div>
                    <div class="pt-3">
                        <input @change="getInputed" type="file" class="file-input w-full" />
                    </div>
                    <div class="pt-5 float-right">
                        <button type="submit" class="btn bg-red-500">Add Mentee</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import {useToast} from 'vue-toastification'
export default {
    name:"Mentee",
    data() {
        return {
            form: {
                name:'',
                image:'',
                email:''
            },
            editForm: {
                name:'',
                id:'',
                image:'',
                email:''
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
      async  addMentee() {
          try {
              let form = new FormData()
              form.append('name',this.form.name)
              form.append('email',this.form.email)
              form.append('image',this.form.image)
             await axios.post('/api/add-mentee',form)
              await this.getMentee()
              this.form.name = ""
              this.form.image = ""
              this.toast.success("Mentee Add Successfully");
              document.getElementById('close1').click()
          }catch (e) {
              console.log(e.response)
              this.toast.error("An error occurred");
          }
      },
        async updateMentee() {
            try {
                let form = new FormData()
                form.append('name',this.editForm.name)
                form.append('image',this.editForm.image)
                form.append('email',this.editForm.email)
                form.append('id',this.editForm.id)
                await axios.post('/api/update-mentee',form)
                await this.getMentee()
                this.toast.success("Mentee Updated Successfully");
                document.getElementById('close2').click()
                this.editForm.name = ""
                this.editForm.image = ""
                this.editForm.id = ""

            }catch (e) {
                console.log(e.response)
                this.toast.error("An error occurred");
            }
        },
        async showEdit(id) {
          try {
              let response = await axios.get(`/api/edit/${id}`)
              this.editForm.name = response.data.name
              this.editForm.email = response.data.email
              this.editForm.id = response.data.id
          }catch (e) {
              console.log(e.response)
              this.toast.error("An error occurred");
          }
          document.getElementById('my-modal-4').checked = true
        },
       async deleteUser(id) {
          if(confirm("Are you sure")){
               try {
                   await axios.get(`/api/delete/${id}`)
                   await this.getMentee()
                   this.toast.success("Mentee Deleted Successfully");
               }catch (e) {
                   console.log(e.response)
                   this.toast.error("An error occurred");
               }
          }
        },
        getInput(e) {
          this.form.image= e.target.files[0]
        },
        getInputed(e) {
          this.editForm.image= e.target.files[0]
        },
       async getMentee() {
          try {
              this.loading = true
              let response = await axios.get('/api/mentee')
              this.rows = response.data
              this.loading = false

          }catch (e) {
              console.log(e.response)
              this.toast.error("An error occurred");
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
