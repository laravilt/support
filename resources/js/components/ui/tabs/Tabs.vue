<script setup lang="ts">
import type { TabsRootEmits, TabsRootProps } from "reka-ui"
import { TabsRoot, useForwardPropsEmits } from "reka-ui"

const props = defineProps</* @vue-ignore */ TabsRootProps>()
const emits = defineEmits</* @vue-ignore */ TabsRootEmits>()

const forwarded = useForwardPropsEmits(props, emits)
</script>

<template>
  <TabsRoot v-bind="forwarded">
    <slot />
  </TabsRoot>
</template>
