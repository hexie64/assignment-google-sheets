<template>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <tr>
                <th scope="col" class="px-6 py-3" v-html="`Actions`" />
                <!-- <th scope="col" class="px-6 py-3" v-html="`Status`" /> -->
                <th scope="col" v-for="(item, index) in rows" class="px-6 py-3 max-w-[50px] text-nowrap">
                    {{ item.row }}
                </th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(row, index) in rows"
                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200">
                <th :style="{ width: '50px'}">
                    <div class="flex justify-center-safe pe-5 gap-2">
                        <Link :href="`row/${row.id}`">
                            <Button class="cursor-pointer" variant="outline" size="sm">
                                <Eye />
                            </Button>
                        </Link>
                        <Button variant="outline" size="sm" class="p-0 cursor-pointer" @click="deleteRow(row.id)">
                            <Trash />
                        </Button>
                    </div>
                </th>
                <!--
                <th :style="{ width: '100px'}">
                    <div class="flex justify-center">
                        <Select
                            @selected="status => statusChanged(status, row.id)"
                            :options="['allowed', 'prohibited']"
                            v-model="rows[index].status"
                        />
                    </div>
                </th>
                -->
                <th v-for="(cell, index) in row.cells"
                    v-bind="!index ? {scope: 'row'} : {}"
                    :style="{ maxWidth: '200px'}"
                    class="px-6 py-4 font-medium overflow-hidden text-gray-900 whitespace-nowrap dark:text-white"
                >
                    {{ cell.content }}
                </th>
            </tr>
            </tbody>
        </table>
    </div>
</template>
<script setup lang="ts">
import { Eye, Trash } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import Select from '@/components/Select.vue';
import { Button } from '@/components/ui/button';
import axios from 'axios';
import { onMounted, ref } from 'vue';

const emitter = defineEmits(['row-deleted']);

type RowType = {
    id: number;
    status: string;
    data?: Object
}

const props = defineProps<{
    rows: Object[]
}>();

const deleteRow = (rowId: number) => axios.delete(`/api/v1/rows/${rowId}`).then(() => {
    emitter('row-deleted', rowId);
});

const statusChanged = (
    status: string,
    rowId: number
) => {
    axios.post(`/api/v1/rows/${rowId}/change-status`, { status: status });
};
</script>
