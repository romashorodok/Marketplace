<template>
    <app-header />

    <main class="app app-layout">
        <div class="app-wrapper">
            <router-view />
        </div>
    </main>

</template>

<script>
import AppHeader from '../header';

export default {
    components: {
        AppHeader
    }
}
</script>
