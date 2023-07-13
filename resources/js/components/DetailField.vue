<template>
    <PanelItem :index="index" :field="field">
        <template #value>
            <Link
                v-if="field.viewable && field.value"
                :href="$url(`/resources/${field.entityResourceKey}/${field.value}`)"
                class="link-default"
            >
                {{ field.display || field.value }}
            </Link>
            <p v-else-if="field.value">{{ field.display || field.value }}</p>
            <p v-else>&mdash;</p>
        </template>
    </PanelItem>
</template>

<script>
export default {
    props: ['index', 'resource', 'resourceName', 'resourceId', 'field'],
}
</script>
