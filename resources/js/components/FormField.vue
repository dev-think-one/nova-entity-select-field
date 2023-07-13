<template>
    <DefaultField
        :field="field"
        :errors="errors"
        :show-help-text="showHelpText"
        :full-width-content="fullWidthContent"
    >
        <template #field>
            <div class="flex items-center space-x-2">
                <SearchInput
                    :data-testid="`${field.resourceName}-search-input`"
                    :disabled="field.readonly"
                    @input="performResourceSearch"
                    @clear="clearResourceSelection"
                    @selected="selectResource"
                    :error="hasError"
                    :debounce="field.debounce"
                    :value="selectedResource"
                    :data="availableResources"
                    :clearable="field.nullable"
                    trackBy="value"
                    class="w-full"
                    :mode="mode"
                >
                    <div v-if="selectedResource" class="flex items-center">
                        <div v-if="selectedResource.avatar" class="mr-3">
                            <img
                                :src="selectedResource.avatar"
                                class="w-8 h-8 rounded-full block"
                            />
                        </div>

                        {{ selectedResource.display }}
                    </div>

                    <template #option="{ selected, option }">
                        <SearchInputResult
                            :option="option"
                            :selected="selected"
                            :with-subtitles="field.withSubtitles"
                        />
                    </template>
                </SearchInput>
            </div>

            <TrashedCheckbox
                v-if="field.softDeletes"
                class="mt-3"
                :resource-name="field.resourceName"
                :checked="withTrashed"
                @input="withTrashed = !withTrashed; performResourceSearch()"
            />
        </template>
    </DefaultField>
</template>

<script>
import {FormField, HandlesValidationErrors} from 'laravel-nova'
import {find, isNil} from "lodash";

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data: () => ({
        availableResources: [],
        selectedResource: null,
        withTrashed: false,
        search: '',
    }),

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''

            this.performResourceSearch();
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.fieldAttribute, this.value || '')
        },

        isSelectedResourceId(value) {
            return (
                !isNil(value) &&
                value?.toString() === this.value?.toString()
            )
        },

        selectResource(selectedResource) {
            this.value = selectedResource.value;
            this.selectedResource = selectedResource;
        },

        clearResourceSelection() {
            this.value = null;
            this.selectedResource = null;
        },

        performResourceSearch(search) {
            Nova.$progress.start()

            this.search = search;

            return Nova.request()
                .get('/nova-vendor/nova-entity-select-field/autocomplete', {
                    params: {
                        entityResourceKey: this.field.entityResourceKey,
                        current: this.value,
                        search: this.search,
                        limit: this.field.limit,
                        withTrashed: this.withTrashed,
                        resourceName: this.resourceName,
                        resourceId: this.resourceId,
                        fieldAttribute: this.field.attribute,
                        viaResource: this.viaResource,
                        viaResourceId: this.viaResourceId,
                        viaRelationship: this.viaRelationship,
                    }
                })
                .then(({data: {resources, withTrashed}}) => {
                    Nova.$progress.done()

                    this.withTrashed = withTrashed

                    this.availableResources = resources;

                    this.selectedResource = find(resources, r =>
                        this.isSelectedResourceId(r.value)
                    );
                })
                .catch(e => {
                    Nova.$progress.done()
                })
        }

    },
}
</script>
