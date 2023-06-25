
<template>
    <div style="padding: 10px;">

        <h1>
            Account Head List
            <router-link to="/account-heads/create" style="text-decoration: none; color: inherit;">
                <el-button type="primary" v-if="logged_in_user && logged_in_user.role === 'superadmin'" style="float: right;">
                    Create
                </el-button>
            </router-link>
        </h1>

        <div class="filter-container">
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
        </div>


        <el-table :data="account_heads">
            <el-table-column prop="name" label="Name" />
            <el-table-column prop="group.name" label="Group" />
            <el-table-column prop="id" label="Operations" >
                <template  #default="scope">
                    <router-link :to="'/account-heads/edit/'+scope.row.id"  v-if="logged_in_user && logged_in_user.role === 'superadmin'">
                        <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px" >
                            <Edit />
                        </el-icon>
                    </router-link>
                    <router-link :to="'/account-heads/view/'+scope.row.id">
                        <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px" >
                            <View />
                        </el-icon>
                    </router-link>
                    <el-icon :size="20" :color="'red'"
                            style="width: 1em; height: 1em; margin-right: 8px"
                            @click="handleDelete(scope.row.id);"
                             v-if="logged_in_user && logged_in_user.role === 'superadmin'"
                    >
                        <Delete />
                    </el-icon>
                </template>
            </el-table-column>
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
    name: 'AccountHeadList',
    data() {
        return {
            account_heads: [],
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
        handleDelete(id){
            console.log(id);
            ElMessageBox.confirm(
                'Are you sure you want to delete the AccountHead?',
                'Warning',
                {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'Cancel',
                    type: 'warning',
                }
            ).then(() => {
                axios.delete(`/api/account-heads/`+id).
                    then((res) => {
                        console.log('res:', res);
                        this.account_heads = res.data;
                        ElMessage({
                            type: 'success',
                            message: 'Delete completed',
                        })
                    });
            }).catch(() => {
                ElMessage({
                    type: 'info',
                    message: 'Delete canceled',
                })
            })
        },

        handleFilter() {
            this.query.page = 1;
            this.getList();
        },
        async getList() {
            let params = {
                limit: this.pageSize,
                keyword: this.query.keyword,
                page: this.query.page,
            }
            await axios.get(`/api/account-heads`, {params}).
                    then((res) => {
                        console.log('res:', res);
                        this.account_heads = res.data.data;
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
    },
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
