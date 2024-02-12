<template>
    <div class="mx-auto w-4/12 mt-10 bg-indigo-50 p-4 rounded-lg">
        <div class="bg-white shadow-lg rounded-lg px-8 pt-6 pb-8 mb-2 flex flex-col">
            <h1 class="text-gray-600 py-3 font-bold text-3xl"> Login </h1>
            <h3 class="text-gray-600 py-3 font-bold "> A fresh beer is waiting for you guys! </h3>
            <form method="post" @submit.prevent="handleLogin">
                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2" for="username">
                        Username
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-grey-darker" id="username" type="text" v-model="form.username" required/>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-600 text-sm font-bold mb-2" for="password">
                        Password
                    </label>
                    <input class="shadow appearance-none border border-red rounded w-full py-2 px-3 text-grey-darker mb-3" id="password" type="password" v-model="form.password" required/>
                </div>
                <div class="flex items-center justify-center">
                    <button class="bg-blue-500 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded" type="submit">
                        Sign In
                    </button>
                </div>
            </form>
            <p class="list text-red-500 py-4 bg-gray-100" v-if="typeof errors === 'string'">{{errors}}</p>
            <ul class="list text-red-500 py-4 mt-3 bg-gray-100 text-center" v-for="(value, index) in errors" :key="index" v-if="typeof errors === 'object'">
                <li>{{value[0]}}</li>
            </ul>
        </div>
    </div>
</template>

<script>
import { reactive, ref } from 'vue';
import axios from 'axios';
import {useRouter} from "vue-router";

export default {
    setup() {
        const errors = ref()
        const router = useRouter();
        const form = reactive({
            username: '',
            password: '',
        })

        const handleLogin = async () => {
            try {
                const result = await axios.post('/api/v1/auth/login', form)
                if (result.status === 200 && result.data && result.data.token) {
                    localStorage.setItem('APP_DEMO_USER_TOKEN', result.data.token)
                    await router.push('home')
                }
            } catch (e) {
                if(e && e.response.data && e.response.data.errors) {
                    errors.value = Object.values(e.response.data.errors)
                } else {
                    errors.value = e.response.data.message || ""
                }
            }
        }

        return {
            form,
            errors,
            handleLogin
        }
    }
}
</script>
