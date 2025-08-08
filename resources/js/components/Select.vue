<template>
    <SelectRoot v-model="inputRef" @update:modelValue="emitSelected">
        <SelectLabel v-if="props.label" class="mb-2">
            {{ props.label }}:
        </SelectLabel>
        <SelectTrigger class="cursor-pointer inline-flex min-w-[130px] text-sm items-center justify-between rounded-lg px-[15px]  leading-none h-[35px] gap-[5px] bg-white text-grass11 hover:bg-stone-50 border shadow-sm focus:outline-none  data-[placeholder]:text-green9 outline-none">
            <SelectValue class="text-gray-800" :placeholder="inputRef ?? 'Not selected'" />
            <ChevronDown class="w-[17px]" />
        </SelectTrigger>
        <SelectContent
            position="popper"
            :side-offset="5"
            class="min-w-[160px] bg-white rounded-lg border shadow-sm will-change-[opacity,transform] data-[side=top]:animate-slideDownAndFade data-[side=right]:animate-slideLeftAndFade data-[side=bottom]:animate-slideUpAndFade data-[side=left]:animate-slideRightAndFade z-[100]"
        >
            <SelectScrollUpButton class="flex items-center justify-center h-[25px] bg-white text-violet11 cursor-default">
                <ChevronUp  class="w-[17px]" />
            </SelectScrollUpButton>

            <SelectViewport class="p-[5px]">
                <SelectGroup>
                    <SelectItem
                        v-for="(option, index) in props.options"
                        :key="index"
                        class="cursor-pointer min-w-[190px] text-sm  leading-none text-grass11 rounded-[3px] flex items-center h-[25px] pr-[35px] pl-[25px] relative select-none data-[disabled]:text-mauve8 data-[disabled]:pointer-events-none data-[highlighted]:outline-none data-[highlighted]:bg-green9 data-[highlighted]:text-green1"
                        :value="option"
                    >
                        <SelectItemIndicator
                            class="absolute left-0 w-[25px] inline-flex items-center justify-center">
                            <Check  class="w-[17px]" />
                        </SelectItemIndicator>
                        {{ option }}
                    </SelectItem>
                </SelectGroup>
            </SelectViewport>

            <SelectScrollDownButton class="flex items-center justify-center h-[25px] bg-white text-violet11 cursor-default">
                <ChevronDown  class="w-[17px]" />
            </SelectScrollDownButton>
        </SelectContent>
    </SelectRoot>
</template>
<script setup lang="ts">

const emitter = defineEmits(['selected'])
import {
    SelectContent,
    SelectGroup, SelectItem, SelectItemIndicator, SelectLabel,
    SelectRoot,
    SelectScrollDownButton,
    SelectScrollUpButton, SelectTrigger,
    SelectValue,
    SelectViewport
} from 'reka-ui';
import { onMounted, ref } from 'vue';

const props = defineProps<{
    options: string[],
    label?: string,
    modelValue: string
}>()

const inputRef = ref()

onMounted(() => {
    inputRef.value = props.modelValue
})

import { Check, ChevronUp, ChevronDown } from 'lucide-vue-next';

const emitSelected = (value: any) => emitter('selected', value)
</script>
