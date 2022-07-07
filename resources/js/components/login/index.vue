<template>
    <section class="d-flex flex-column">
        <form>
            <div class="wrapper-field">
                <label>Email</label>
                <input v-model="credentials.email" type="email">
            </div>
            <div class="wrapper-field">
                <label>Password</label>
                <input v-model="credentials.password" type="password">
            </div>
        </form>

        <button @click="submit()">Submit</button>
    </section>
</template>

<script>
    export default {
        data: () => {
            return {
                credentials: {
                    email: '',
                    password: ''
                }
            }
        },
        methods: {
            submit() {
                axios({
                    method: "post",
                    url: "/api/login",
                    data: {...this.credentials}
                })
                    .then(result => console.log(result));
            }
        }
    }
</script>

<style scoped>
    input { margin: 5px; }

    .wrapper-field {
        display: flex;
        flex-direction: column;
    }
</style>
