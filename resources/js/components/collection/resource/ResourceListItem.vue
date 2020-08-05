<template>
    <tr>
        <td
            class="pt-3 text-nowrap"
            v-bind:class="{ 'text-success': finished} "
        >
            <label :for="content.collection_id + '-' + content.resource_id">
                <a :href="content.resource.url" target="_blank" rel="noreferrer noopener">
                    <span :title="content.resource.description">{{ content.resource.name }}</span>
                </a>
                <span v-if="hasContent" :title="content.note.content">✏</span>
            </label>
        </td>

        <template v-if="!finished">
            <td>
                <input type="number"
                       :id="content.collection_id + '-' + content.resource_id"
                       :name="content.collection_id + '-' + content.resource_id"
                       min="0"
                       :max="calculateMissing(content)"
                       placeholder="0"
                       class="form-control-sm w-100"
                       v-model.number="update">
            </td>
            <td class="pt-3 text-nowrap">
                <label :for="content.collection_id + '-' + content.resource_id">/ {{ calculateMissing(content) }}</label>
            </td>
        </template>

        <td v-else class="pt-3 text-nowrap" colspan="2">0</td>

        <td class="pt-3 text-nowrap">
            <label :for="content.collection_id + '-' + content.resource_id">{{ content.sum }} / {{ content.amount }}</label>
        </td>

        <td>
            <button type="button"
                    class="btn btn-outline-success flex-fill"
                    v-if="!finished"
                    title="Angegebene Anzahl hinzufügen"
                    v-on:click.prevent="callUpdate">&plus;
            </button>
        </td>
    </tr>
</template>

<script>
    export default {
        name: "ResourceListItem",
        props: {
            unfinished: Boolean,
            finished: Boolean,
            content: Object,
            updateMethod: Function,
        },
        data() {
            return {
                update: 0,
            }
        },
        computed: {
            hasContent() {
                return !!this.content.note;
            }
        },
        methods: {
            callUpdate: function () {
                this.content.update_amount = this.update;
                this.updateMethod(this.content);
                this.update = null;
            },
            calculateMissing: function (content) {
                return Math.max(content.amount - content.sum, 0);
            },
            resourceFinished: function (content) {
                return content.sum >= content.amount;
            },
        },
    }
</script>
