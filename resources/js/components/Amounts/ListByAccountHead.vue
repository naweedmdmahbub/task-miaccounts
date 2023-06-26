
<template>
    <div style="padding: 10px;">

        <h1>
            Account Head Amount List
        </h1>

        <!-- <div class="filter-container">
            <el-input
                v-model="query.keyword"
                placeholder="Keyword"
                style="width: 200px;"
            />
            <el-button type="primary" @click="handleFilter">
                <el-icon style="vertical-align: middle">
                    <Search />
                </el-icon>
                <span style="vertical-align: middle"> Search </span>
            </el-button>
        </div> -->


        <el-table :data="amounts">
            <el-table-column prop="id" label="Acc Head id" />
            <el-table-column prop="group_level_1_name" label="Group Level 1" />
            <el-table-column prop="group_level_2_name" label="Group Level 2" />
            <el-table-column prop="group_level_3_name" label="Group Level 3" />
            <el-table-column prop="name" label="Acc Head" />  
            <el-table-column prop="amount" label="Amount" />
        </el-table>


        <div class="demo-pagination-block">
            <el-pagination
                v-show="total>0"
                :page-size="query.limit"
                layout="total, sizes, prev, pager, next"
                :total="total"
                :page-count="totalPages"
                :page-sizes="[5, 10, 20, 50, 100, 500]"
                @size-change="handleSizeChange"
                @current-change="handlePageChange"
            />
        </div>

    </div>
</template>



<script >
import { ElMessage, ElMessageBox } from 'element-plus'

export default {
    name: 'TransactionList',
    data() {
        return {
            amounts: [],
            logged_in_user: null,
            query: {
                page: 1,
                limit: 10,
                keyword: '',
            },
            total: 10,
            totalPages: null,
            pageSize: 10,
        };
    },
    async created() {
        try {
            await this.getList();
            await axios.get(`/logged_in_user`).
                    then((res) => {
                        this.logged_in_user = res.data;
                    });
        } catch (error) {
            console.error(error);
        }
    },
    methods: {
        async getList() {
            let params = {
                limit: this.pageSize,
                keyword: this.query.keyword,
                page: this.query.page,
            }
            await axios.get(`/api/amount-by-account-head`, {params}).
                    then((res) => {
                        console.log('res:', res);
                        this.amounts = res.data;
                        // this.query.page = res.data.current_page;
                        // this.total = res.data.total;
                        // this.totalPages = Math.ceil(res.data.total / this.pageSize); // Calculate the total number of pages
                    });
        },
        handleSizeChange(val) {
            this.pageSize = val;
            this.getList();
        },
        handlePageChange(currentPage) {
            this.query.page = currentPage;
            this.getList();
        },
        handleFilter() {
            this.query.page = 1;
            this.getList();
        },
    },
    computed: {
        formattedValue() {
            return (val) => {
                if(val != 'undefined' || val != null){
                    return val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });                
                }
            };
        },
    }
};
</script>

<style scoped>
.filter-container {
  padding-bottom: 10px;
}
.demo-pagination-block  {
  margin-top: 10px;
}
</style>
