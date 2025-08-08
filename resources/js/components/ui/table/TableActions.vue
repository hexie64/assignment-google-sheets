<template>
    <div class="flex w-[100%]">
        <div class="mb-3 lg:px-2 xl:px-2 lg:mb-0">
            <Link :href="route('row.create')">
                <Button class="cursor-pointer opacity-70" size="sm">Create</Button>
            </Link>
        </div>
        <div class="mb-3 lg:px-2 xl:px-2 lg:mb-0 flex">
            <Input v-model="spreadSheetUrl" :value="props.url" class="mx-2" placeholder="Google Spreadsheet URL" />
            <Button @click="saveUrl" size="sm"
                    class="text-dark cursor-pointer bg-blue-200 hover:bg-blue-200 focus:ring-blue-300dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Sync
            </Button>
        </div>
        <div class="mb-3 lg:px-2 xl:px-2 lg:mb-0">
            <Button size="sm" @click="truncateSheet" class="cursor-pointer opacity-70" variant="destructive">
                Clear Table
            </Button>
        </div>
        <div class="mb-3 lg:px-2 xl:px-2 lg:mb-0">
            <Button
                size="sm"
                @click="generateSheet"
                class="cursor-pointer bg-green-700 opacity-70 hover:bg-green-800 focus:ring-green-300  dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                Generate
            </Button>
        </div>
    </div>
</template>
<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import axios from 'axios';
import { onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const emitter = defineEmits(['truncated', 'generated']);
const spreadSheetUrl = ref();
const props = defineProps<{
    url?: string
}>();

const truncateSheet = () => axios.delete('/api/v1/sheets/truncate')
    .then(() => emitter('truncated'));

const generateSheet = () => axios.get('/api/v1/sheets/generate')
    .then(({ data }) => emitter('generated', data));

const saveUrl = () => axios.post('/api/v1/sheets/sync', {
    value: spreadSheetUrl.value
});

onMounted(() => {
    spreadSheetUrl.value = props.url;
});
</script>
