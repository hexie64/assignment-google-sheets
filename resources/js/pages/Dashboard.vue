<script setup lang="ts">
import axios from 'axios';
import { onMounted, ref } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import DataTable from '@/components/ui/table/DataTable.vue';
import TableActions from '@/components/ui/table/TableActions.vue';

const props = defineProps<{
    spreadsheet_url: string;
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const tableRows = ref([]);
const isLoaded = ref(false);

onMounted(() => {
    axios.get('/api/v1/rows').then(({ data }) => {
        tableRows.value = data;
        isLoaded.value = true;
    });
});

const tableTruncated = () => {
    tableRows.value = [];
}

const tableGenerated = (data: any) => {
    tableRows.value = data
}

const rowDeleted = (rowId: number) => {
    const index = tableRows.value.findIndex((row) => row.id === rowId);
    if (index > -1) tableRows.value.splice(index, 1);
}

</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <TableActions
                :url="props.spreadsheet_url"
                @truncated="tableTruncated"
                @generated="tableGenerated"
            />
            <DataTable v-if="isLoaded" :rows="tableRows"  @row-deleted="rowDeleted" />
        </div>
    </AppLayout>
</template>
