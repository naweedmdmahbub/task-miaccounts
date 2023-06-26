<template>
    <el-form ref="ruleFormRef" :model="group" class="demo-group"
        label-position="top"
        status-icon
    >
        <el-text tag="b" v-if="operation === 'view'" type="primary" size="large">View Group</el-text>
        <el-text tag="b" v-if="operation === 'edit'" type="primary" size="large">Edit Group</el-text>
        <el-text tag="b" v-if="operation === 'create'" type="primary" size="large">Create Group</el-text>


        <el-card class="box-card">            
            <el-form-item label="Name" prop="name" required>
                <el-input v-model="group.name" type="text" :disabled="operation === 'view'" />
            </el-form-item>
            
            <el-form-item label="Parent Group" prop="parent_group">
              <el-select
                v-model="selectedParentGroup"
                placeholder="Select Parent Group"
                :disabled="operation === 'view' ? true: null"
                style="width:100%"
              >
                <el-option
                    :label="'None'"
                    :value="null"
                />
                <el-option
                  v-for="(val, index) in groups"
                  :key="index"
                  :label="val.name"
                  :value="val.id"
                />
              </el-select>
            </el-form-item>


            <el-row>
                <el-col>
                    <el-button v-if="operation === 'create'" type="primary" @click="createGroup" class="me-2">Create</el-button>
                    <el-button v-if="operation === 'edit'" type="primary" @click="updateGroup" class="me-2">Update</el-button>
                    <router-link :to="'/groups'">
                        <el-button type="info" class="me-2">Back</el-button>
                    </router-link>

                </el-col>
            </el-row>
        </el-card>
    </el-form>
</template>

<script >
import { showErrors } from '@/utils/helper.js'

export default {
    name: 'GroupCreate',
    data() {
        return {
            routeName: '',
            operation: 'create',
            group : {
                id: null,
                name: '',
                parent_id: null,
            },
            groups: [],
            selectedParentGroup: null,
        };
    },
    async created() {
        if(this.$route.name == 'GroupCreate'){
            this.operation = 'create';
        } else if(this.$route.name == 'GroupEdit'){
            this.operation = 'edit';
            let paths = this.$route.path.split("/");
            this.group.id = paths[3];
        } else {
            this.operation = 'view';
            let paths = this.$route.path.split("/");
            this.group.id = paths[3];
        }
        // console.log('Route Name: ', this.$route.name);
        if(this.group.id){
            axios.get(`/api/groups/edit/`+this.group.id).
                    then((res) => {
                        console.log('res:', res);
                        this.group.id = res.data.id;
                        this.group.name = res.data.name;
                        this.selectedParentGroup = res.data.parent_id;
                    });
            console.log('Group edit', this.group)
        }


        await axios.get(`/api/get-all-groups`).
                then((res) => {
                    this.groups = res.data;
                    // console.log('get-all-groups:', this.groups);
                });
    },
    methods: {
        async createGroup() {
            this.group.parent_id = this.selectedParentGroup;
            try {
                await axios.post(`/api/groups`, this.group).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/groups');
                        });
            } catch (error) {
                showErrors(error);
                console.error('error in response:', error.response.data);
            }
        },
        async updateGroup() {
            this.group.parent_id = this.selectedParentGroup;
            try {
                await axios.post(`/api/groups/`+this.group.id, this.group).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/groups');
                        });
            } catch (error) {
                showErrors(error);
            }
        },
    },
};
</script>


<style scoped>
    .demo-group {
        padding-left: 10px;
    }
    .el-form-item__label {
        font-weight:bold !important;
        color: #212529;
    }
    th {
        padding-left: 0 !important;
        padding-top: 0 !important;
    }
</style>>
