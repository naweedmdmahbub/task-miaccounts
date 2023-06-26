<template>
    <el-form ref="ruleFormRef" :model="transaction" class="demo-transaction"
        label-position="top"
        status-icon
    >
        <el-text tag="b" v-if="operation === 'view'" type="primary" size="large">View Transaction</el-text>
        <el-text tag="b" v-if="operation === 'edit'" type="primary" size="large">Edit Transaction</el-text>
        <el-text tag="b" v-if="operation === 'create'" type="primary" size="large">Create Transaction</el-text>


        <el-card class="box-card">
            <el-form-item label="Date" required>
                <el-date-picker v-model="transaction.date" type="date" label="Pick a date" placeholder="Pick a date"
                    format="YYYY-MM-DD"
                    value-format="YYYY-MM-DD"
                    style="width: 100%"
                    :disabled="operation === 'view'" />
            </el-form-item>
            
            <el-form-item label="Account Head" prop="account_head">
              <el-select
                v-model="selectedAccountHead"
                placeholder="Select Account Head"
                :disabled="operation === 'view' ? true: null"
                style="width:100%"
              >
                <el-option
                  v-for="(val, index) in account_heads"
                  :key="index"
                  :label="val.name"
                  :value="val.id"
                />
              </el-select>
            </el-form-item>

            
            <el-row :gutter="10">
                <el-col :span="8" class="inline">
                    <el-form-item label="Debit" prop="debit">
                        <el-input v-model="transaction.debit" type="number" 
                                    :disabled="operation === 'view'"
                                    @blur="calculateTotal"
                                    style="width:100%" />
                    </el-form-item>
                </el-col>
                <el-col :span="8" class="inline">
                    <el-form-item label="Credit" prop="credit">
                        <el-input v-model="transaction.credit" type="number" 
                                    :disabled="operation === 'view'"
                                    @blur="calculateTotal"
                                    style="width:100%" />
                    </el-form-item>
                </el-col>
                <el-col :span="8" class="inline">
                    <el-form-item label="Amount" prop="amount">
                        <el-input v-model="transaction.amount" type="number" 
                                placeholder="Total" disabled
                                style="width:100%" />
                    </el-form-item>
                </el-col>
            </el-row>


            <el-row>
                <el-col>
                    <el-button v-if="operation === 'create'" type="primary" @click="createTransaction" class="me-2">Create</el-button>
                    <el-button v-if="operation === 'edit'" type="primary" @click="updateTransaction" class="me-2">Update</el-button>
                    <router-link :to="'/transactions'">
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
    name: 'TransactionCreate',
    data() {
        return {
            routeName: '',
            operation: 'create',
            transaction : {
                id: null,
                date: '',
                debit: 0,
                credit: 0,
                account_head_id: null,
                amount: 0
            },
            account_heads: [],
            selectedAccountHead: null,
        };
    },
    async created() {
        if(this.$route.name == 'TransactionCreate'){
            this.operation = 'create';
        } else if(this.$route.name == 'TransactionEdit'){
            this.operation = 'edit';
            let paths = this.$route.path.split("/");
            this.transaction.id = paths[3];
        } else {
            this.operation = 'view';
            let paths = this.$route.path.split("/");
            this.transaction.id = paths[3];
        }
        // console.log('Route Name: ', this.$route.name);
        if(this.transaction.id){
            await axios.get(`/api/transactions/edit/`+this.transaction.id).
                    then((res) => {
                        // console.log('res:', res);
                        this.transaction = res.data;
                        this.selectedAccountHead = res.data.account_head_id;
                    });
            this.calculateTotal();
            console.log('Transaction edit', this.transaction)
        }


        await axios.get(`/api/get-all-account-heads`).
                then((res) => {
                    this.account_heads = res.data;
                    // console.log('get-all-account_heads:', this.account_heads);
                });
    },
    methods: {
        calculateTotal() {
            this.transaction.amount = (parseFloat(this.transaction.debit) - parseFloat(this.transaction.credit)).toFixed(2);
        },
        async createTransaction() {
            this.transaction.account_head_id = this.selectedAccountHead;
            try {
                await axios.post(`/api/transactions`, this.transaction).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/transactions');
                        });
            } catch (error) {
                showErrors(error);
                console.error('error in response:', error.response.data);
            }
        },
        async updateTransaction() {
            this.transaction.account_head_id = this.selectedAccountHead;
            try {
                await axios.post(`/api/transactions/`+this.transaction.id, this.transaction).
                        then((res) => {
                            console.log('res:', res, this.$router);
                            this.$router.push('/transactions');
                        });
            } catch (error) {
                showErrors(error);
            }
        },
    },
};
</script>


<style scoped>
    .demo-transaction {
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
