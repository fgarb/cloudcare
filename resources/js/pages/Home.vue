<!-- This template is horrible... Even not optimized for mobile, but I wanted to show something -->
<template>
    <div class="w-10/12 p-10 mx-auto">
        <div class="flex justify-between">
            <h1 class="text-2xl"> Beers! </h1>
            <button class="bg-red-500 hover:bg-orange-900 text-white font-bold py-2 px-4 rounded" @click="handleLogout">Logout</button>
        </div>
        <Loader v-if="isLoading"/>

        <div class=" ml-auto mr-auto text-center text-gray-800 pt-5" v-if="!isLoading && !hasData"> NO MORE BEERS :(</div>

        <ul v-if="!isLoading && hasData" class="border-t mt-3">
            <li :class="`py-3 border-b text-gray-600 ${val.has_completed ? 'line-through' : ''} ${idx%2 == 0 ? 'bg-slate-100' : ''}`"
                v-for="(val, idx) in beers" :key="idx">
                <div class="grid grid-cols-8">
                    <div>
                        <img :src="val.image_url" v-if="val.image_url != null" class="mt-3 w-16 h-16 object-contain ml-auto mr-auto"/>
                        <div v-if="val.image_url == null" class="bg-gray-200 w-16 h-16 ml-auto mr-auto"></div>
                    </div>
                    <div class="text-gray-800 font-bold col-span-2">
                        {{ val.name }}
                        <br/>
                        <span class="text-sm">
                            {{ val.tagline}}
                        </span>
                    </div>
                    <div class="text-xs col-span-2">
                        {{ val.description.length > 150 ? (val.description.substring(0, 150) + "...") : val.description }}
                    </div>
                    <div class="pt-4">
                        <div class="rounded-full bg-amber-800 w-16 h-16 ml-auto mr-auto text-center text-gray-200 pt-5">
                            <div class="h-6 mt-auto mb-auto font-bold text-sm">
                                {{ val.abv }}%
                            </div>
                        </div>
                    </div>
                    <div class="pt-4">
                        <div class="ml-auto mr-auto text-center  text-sm rounded-full bg-gray-800 pl-2 pr-2 text-gray-200">
                            volume: <br/> {{ val.volume.value }} {{ val.volume.unit }}
                        </div>
                    </div>
                    <div class="pt-4">
                        <div class="ml-auto mr-auto text-center  text-sm pl-2 pr-2">
                            first brewed: {{ val.first_brewed }}
                        </div>
                    </div>
                </div>
            </li>
        </ul>

        <div class="flex items-center justify-between w-full mt-4">
            <div class="flex items-center justify-between w-60 mt-4">
                <button v-if="!isLoading && hasData"
                    class="bg-blue-500 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded"
                    @click="nextPage">Next
                </button>
                <button v-if="page > 1 && !isLoading" class="bg-blue-500 hover:bg-blue-900 text-white font-bold py-2 px-4 rounded"
                    @click="prevPage">Previous
                </button>
            </div>
            <div v-if="!isLoading && hasData" class="font-bold text-white rounded-full bg-gray-500 flex items-center justify-center w-32 "> PAGE  {{ page }} </div>
        </div>
    </div>
</template>
<script>
import {ref, onMounted} from 'vue';
import {useRouter} from "vue-router";
import {request} from '../helper';
import Loader from '../components/Loader.vue';

export default {
    components: {
        Loader
    },
    setup() {
        const beers = ref([])
        const user = ref()
        const isLoading = ref()
        const page = ref()
        const hasData = ref()

        let router = useRouter();
        onMounted(() => {
            getBeers()
        });

        const getBeers = async () => {
            isLoading.value = true
            try {
                const req = await request('get', '/api/v1/proxy/beers?page=1&per_page=30')
                beers.value = req.data.data
                page.value = 1
                hasData.value = true
            } catch (e) {
                await router.push('/')
            }
            isLoading.value = false
        }

        const nextPage = async () => {
            try {
                page.value++;
                isLoading.value = true;
                const req = await request('get', `/api/v1/proxy/beers?page=${page.value}&per_page=30`)
                if (req.data.data.length > 0){
                    beers.value = req.data.data
                    hasData.value = true
                }else{
                    beers.value = []
                    hasData.value = false
                }
                isLoading.value = false;
            } catch (e) {
                await router.push('/')
            }
        }

        const prevPage = async () => {
            if (page.value == 1)
                return;
            try {
                page.value--;
                isLoading.value = true;
                const req = await request('get', `/api/v1/proxy/beers?page=${page.value}&per_page=30`)
                beers.value = req.data.data
                isLoading.value = false;
                hasData.value = true;
            } catch (e) {
                await router.push('/')
            }
        }

        const handleLogout = async () => {
            const req = await request('get', `/api/v1/auth/logout`)
            localStorage.removeItem('APP_DEMO_USER_TOKEN')
            router.push('/')
        }

        return {
            beers,
            user,
            isLoading,
            hasData,
            handleLogout,
            nextPage,
            prevPage,
            page
        }
    },
}
</script>
