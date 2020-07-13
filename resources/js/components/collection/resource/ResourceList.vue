<template>
    <table class="table table-borderless">
        <thead>
        <tr>
            <th>Ressource</th>
            <th colspan="2">Hinzufügen</th>
            <th>Gesammelt</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <ResourceListItem
            v-for="item in filterUnFinished(content)"
            :key="'item_' + item.id + '_' + item.collection_id + '_' + item.vueKey"
            :content="item"
            :update-method="updateMethod"
            unfinished
        ></ResourceListItem>
        <ResourceListItem
            v-for="item in filterFinished(content)"
            :key="'item_' + item.id + '_' + item.collection_id + '_' + item.vueKey"
            :content="item"
            finished
        ></ResourceListItem>
        </tbody>
    </table>
</template>

<script>
    import ResourceListItem from "./ResourceListItem";

    export default {
        name: "ResourceList",
        components: {ResourceListItem},
        props: {
            content: Array,
            updateMethod: Function,
        },
        methods: {
            resourceFinished: function (content) {
                return content.sum >= content.amount;
            },
            filterUnFinished: function (contents) {
                return contents.filter(content => {
                    return !this.resourceFinished(content);
                });
            },
            filterFinished: function (contents) {
                return contents.filter(content => {
                    return this.resourceFinished(content);
                });
            },
        },
    }
</script>
