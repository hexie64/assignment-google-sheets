<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Input } from '@/components/ui/input';
import { onMounted, ref } from 'vue';
import { Button } from '@/components/ui/button';
import { ArrowLeft } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Row',
        href: '/row',
    },
];

interface rowType  {
    data: Array<string>
}

const props = defineProps<{
    row: {
        data: rowType
    }
}>()


const rowComments = ref([])
const rowsData = ref([])
const isLoaded = ref(false);

onMounted(() => {
    rowsData.value = <any>props?.row.data;
    isLoaded.value = true;
})

</script>
<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4" v-if="isLoaded">
            <div class="w-75">
                <Link :href="route('dashboard')"  class="flex items-center gap-x-2 mb-5">
                    <Button variant="outline" class="cursor-pointer">
                        <ArrowLeft />
                        <span>Back</span>
                    </Button>
                </Link>

                <div>Row Id: {{ row.id }}</div>

                <div>Status: {{ row.status }}</div>

                <div class="py-5">
                    <h4 class="mb-2">Fields:</h4>
                    <div class="mb-3" v-for="(item, label, index) in rowsData" :key="index">
                        <label :for="`input-` + index ">{{ label }}</label>
                        <Input :id="`input-` + index" type="text" v-model="rowsData[label]" :value="item" :label="label" />
                    </div>
                </div>

                <div v-for="(item, index) in rowComments" :key="index">
                    {{ item }}
                </div>

                <Button>Save</Button>
            </div>
        </div>
    </AppLayout>
</template>

