<template>
    <div class="common-layout">
        <el-container class="full-width-container">
            <el-aside width="200px">
                <el-scrollbar>
                    <el-menu>
                        <router-link to="/users" style="text-decoration: none;"
                                     :style="[$route.path === '/users' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="1">
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <User />
                                </el-icon>
                                Users
                            </el-menu-item>
                        </router-link>
                        <router-link to="/groups" style="text-decoration: none;"
                                     :style="[$route.path === '/groups' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="2">
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <User />
                                </el-icon>
                                Groups
                            </el-menu-item>
                        </router-link>

                        <router-link to="/account-heads" style="text-decoration: none;"
                                     :style="[$route.path === '/account-heads' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="3">
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <User />
                                </el-icon>
                                Account Heads
                            </el-menu-item>
                        </router-link>

                        <router-link to="/transactions" style="text-decoration: none;"
                                     :style="[$route.path === '/transactions' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="4">
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <User />
                                </el-icon>
                                Transactions
                            </el-menu-item>
                        </router-link>

                        
                        <router-link to="/amount-by-group" style="text-decoration: none;"
                                     :style="[$route.path === '/amount-by-group' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="5">
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <User />
                                </el-icon>
                                Group Amount
                            </el-menu-item>
                        </router-link>

                        <router-link to="/amount-by-account-head" style="text-decoration: none;"
                                     :style="[$route.path === '/amount-by-account-head' ? {color: 'royalblue'} : {color: 'black'}]">
                            <el-menu-item index="6">
                                <el-icon :size="20" style="width: 1em; height: 1em; margin-right: 8px">
                                    <User />
                                </el-icon>
                                Account Head Amount
                            </el-menu-item>
                        </router-link>

                    </el-menu>


                </el-scrollbar>
            </el-aside>

            <el-main>
                <router-view></router-view>
            </el-main>

        </el-container>
    </div>
</template>

<script lang="ts" setup>
import { ref } from 'vue'
import { Menu as IconMenu, Message, Setting } from '@element-plus/icons-vue'

</script>

<style >
.layout-container-demo .el-header {
    position: relative;
    background-color: var(--el-color-primary-light-7);
    color: var(--el-text-color-primary);
}

.layout-container-demo .el-aside {
    color: var(--el-text-color-primary);
    /* background: var(--el-color-primary-light-8); */
}

.layout-container-demo .el-menu {
    border-right: none;
}

.layout-container-demo .el-main {
    padding: 0;
}

.layout-container-demo .toolbar {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    right: 20px;
}

.el-form-item__label {
    font-weight:bold !important;
    color: #212529;
}
.el-form-item__label {
    font-weight:bold !important;
    color: #212529;
}
.el-input__wrapper {
    width: 100%;
}
</style>
