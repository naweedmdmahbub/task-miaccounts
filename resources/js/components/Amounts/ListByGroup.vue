
<template>
    <div style="padding: 10px;">

        <h1>
            Group Amount List
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


        <el-table :data="groups">
            <el-table-column prop="group" label="Group" width="120" /> 
            <el-table-column prop="group_head" label="Group/Heads">               
                <template #default="scope">
                    <el-row >
                        <el-col :offset="(scope.row.level-1)*6" :span="12">
                            {{ scope.row.group_head }}
                        </el-col>
                    </el-row>
                </template>
            </el-table-column>
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
            groups: [],
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
            await axios.get(`/api/amount-by-group`, {params}).
                    then((res) => {
                        console.log('res:', res.data);
                        this.groups = res.data;
                        this.query.page = res.data.current_page;
                        this.total = res.data.total;
                        this.totalPages = Math.ceil(res.data.total / this.pageSize); // Calculate the total number of pages
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
