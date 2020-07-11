<template>
    <tbody v-if="unfinished">
    <tr :key="content.name">
        <td class="pt-3 text-nowrap">{{ content.resource.name }}</td>
        <td>
            <input type="number" min="0" :max="calculateMissing(content)" placeholder="0"
                   class="form-control-sm w-100" v-model.number="update" @input="saveAmount">
        </td>
        <td class="pt-3 text-nowrap">/ {{ calculateMissing(content) }}</td>
        <td class="pt-3 text-nowrap">{{ content.sum }} / {{ content.amount }}</td>
        <td>
            <button type="button" class="btn btn-outline-success flex-fill"
                    v-on:click.prevent="callUpdate(content)">&plus;
            </button>
        </td>
    </tr>
    </tbody>

    <tbody v-else-if="finished">
    <tr :key="content.name">
        <td class="pt-3 text-nowrap text-success">{{ content.resource.name }}</td>
        <td class="pt-3 text-nowrap" colspan="2">0</td>
        <td class="pt-3 text-nowrap">{{ content.sum }} / {{ content.amount }}</td>
        <td></td>
    </tr>
    </tbody>
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
        methods: {
            callUpdate: function (content) {
                this.updateMethod(content);
                this.update = null;
            },
            saveAmount: function () {
                this.content.update_amount = this.update;
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
