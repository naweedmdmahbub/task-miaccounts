<template>
    <el-form ref="ruleFormRef" :model="account_head" class="demo-account_head"
        label-position="top"
        status-icon
    >
        <el-text tag="b" v-if="operation === 'view'" type="primary" size="large">View Account Head</el-text>
        <el-text tag="b" v-if="operation === 'edit'" type="primary" size="large">Edit Account Head</el-text>
        <el-text tag="b" v-if="operation === 'create'" type="primary" size="large">Create Account Head</el-text>


        <el-card class="box-card">            
            <el-form-item label="Name" prop="name" required>
                <el-input v-model="account_head.name" type="text" :disabled="operation === 'view'" />
            </el-form-item>
            
            <el-form-item label="Group" prop="group">
              <el-select
                v-model="selectedGroup"
                placeholder="Select Group"
                :disabled="operation === 'view' ? true: null"
                style="width:100%"
              >
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
                    <el-button v-if="operation === 'create'" type="primary" @click="createAccountHead" class="me-2">Create</el-button>
                    <el-button v-if="operation === 'edit'" type="primary" @click="updateAccountHead" class="me-2">Update</el-button>
                    <router-link :to="'/account-heads'">
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
    name: 'AccountHeadCreate',
    data() {
        return {
            routeName: '',
            operation: 'create',
            account_head : {
                id: null,
                group_id: null,
                name: '',
            },
            groups: [],
            selectedGroup: null,
        };
    },
    async created() {
        if(this.$route.name == 'AccountHeadCreate'){
            this.operation = 'create';
        } else if(this.$route.name == 'AccountHeadEdit'){
            this.operation = 'edit';
            let paths = this.$route.path.split("/");
            this.account_head.id = paths[3];
        } else {
            this.operation = 'view';
            let paths = this.$route.path.split("/");
            this.account_head.id = paths[3];
        }
        if(this.account_head.id){
            axios.get(`/api/account-heads/edit/`+this.account_head.id).
                    then((res) => {
                        console.log('res:', res);
                        this.account_head.id = res.data.id;
                        this.account_head.name = res.data.name;
                        this.selectedGroup = res.data.group_id;
                    });
            console.log('AccountHead edit', this.account_head)
        }

        await axios.get(`/api/get-all-groups`).
                then((res) => {
                    this.groups = res.data;
                    // console.log('get-all-groups:', this.groups);
                });
    },
    methods: {
        async createAccountHead() {
            this.account_head.group_id = this.selectedGroup;
            console.log('createAccountHead', this.account_head);
            try {
                await axios.post(`/api/account-heads`, this.account_head).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/account-heads');
                        });
            } catch (error) {
                showErrors(error);
                console.error('error in response:', error.response.data);
            }
        },
        async updateAccountHead() {
            this.account_head.group_id = this.selectedGroup;
            try {
                await axios.post(`/api/account-heads/`+this.account_head.id, this.account_head).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/account-heads');
                        });
            } catch (error) {
                showErrors(error);
            }
        },
    },
};
</script>


<style scoped>
    .demo-account_head {
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
