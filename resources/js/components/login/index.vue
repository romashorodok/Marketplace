<template>
    <modal v-show="show" @close="">
        <template v-slot:modal-header>
            <h3>Login</h3>
        </template>
        <template v-slot:modal-body>
            <form>
                <div class="wrapper-field">
                    <label>Email</label>
                    <input v-model="credentials.email" type="email">
                </div>
                <div class="wrapper-field">
                    <label>Password</label>
                    <input v-model="credentials.password" type="password">

                    <button @click="submit()">
                        Login
                    </button>
                </div>
            </form>
        </template>
        <template v-slot:modal-footer>
        </template>
    </modal>
</template>

<script>
import modal from '../modal';

export default {
    props: ['show'],
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
                .then(result => console.log(result))
                .catch(error => console.log("Error"))
        }
    },
    components: {
        modal
    }
}
</script>

<style scoped>
input {
    margin: 5px;
}

.wrapper-field {
    display: flex;
    flex-direction: column;
}

h3 {
    margin: 0;
}
</style>
