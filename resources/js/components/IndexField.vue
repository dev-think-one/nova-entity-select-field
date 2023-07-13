<template>
    <div :class="`text-${field.textAlign}`">
        <span>
            <Link
                v-if="field.viewable && field.value"
                @click.stop
                :href="$url(`/resources/${field.entityResourceKey}/${field.value}`)"
                class="link-default"
            >
              {{ field.display || field.value }}
            </Link>
          <span v-else-if="field.value"> {{ field.display || field.value }}</span>
          <span v-else>&mdash;</span>
        </span>
    </div>
</template>

<script setup>
const props = defineProps({
    resource: {type: Object},
    resourceName: {type: String},
    field: {type: Object},
})
</script>
