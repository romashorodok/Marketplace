<template>
    <app-header />

    <main class="app app-layout">
        <div class="app-wrapper">
            <router-view />
        </div>
    </main>

</template>

<script>
import header from '../header';

export default {
    components: {
        "app-header": header
    }
}
</script>
