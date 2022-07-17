<template>
    <div class="account-wrapper">
        <aside class="sidebar-wrapper">
            <router-link class="sidebar-btn"
                         to="/account/profile">
                profile
            </router-link>
            <router-link class="sidebar-btn"
                         to="/account/products">
                products
            </router-link>
            <router-link class="sidebar-btn"
                         to="/account/orders">
                orders
            </router-link>
        </aside>
        <div class="account-body">
            <Suspense>
                <template #default>
                    <router-view />
                </template>
                <template #fallback>
                    Loading...
                </template>
            </Suspense>
        </div>
    </div>
</template>

<script>

export default {

}
</script>
