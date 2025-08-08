<script setup lang="ts">
import Select from '@/components/Select.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import { ArrowLeft } from 'lucide-vue-next';
import { onMounted, reactive, ref } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Row',
        href: '/row',
    },
];

const row = reactive({
    status: 'allowed',
    data: <any>[],
    comments: <any>[],
});

const isLoaded = ref(false);

onMounted(() => {
    isLoaded.value = true;
});

const addField = () => {
    const emptyRow = { label: '', value: '' };
    row.data.push(emptyRow);
};

const createRow = () => axios.post('/api/v1/rows', row).then(() => {
    router.visit('/');
});
</script>
<template>
    <Head title="Dashboard" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4" v-if="isLoaded">
            <div class="w-75">
                <Link :href="route('dashboard')" class="mb-5 flex items-center gap-x-2">
                    <Button variant="outline" class="cursor-pointer">
                        <ArrowLeft />
                        <span>Back</span>
                    </Button>
                </Link>

                <Select v-model="row.status" :options="['allowed', 'prohibited']" />

                <div class="py-5">
                    <h4 class="mb-2">Fields:</h4>
                    <div class="mb-3 flex gap-2" v-for="(item, label, index) in row.data" :key="index">
                        <Input type="text" v-model="row.data[label]['label']" placeholder="Column name" />
                        <Input type="text" v-model="row.data[label]['value']" :label="label" placeholder="Row value" />
                    </div>
                    <Button class="cursor-pointer text-sm" variant="outline" @click="addField">Add Field</Button>
                </div>

                <div class="my-5">
                    <Button @click="createRow" class="cursor-pointer text-sm">Create</Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
