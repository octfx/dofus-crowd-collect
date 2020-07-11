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
        <ResourceListItem
            v-for="content in filterUnFinished(content)"
            :content="content"
            :update-method="updateMethod"
            unfinished
        ></ResourceListItem>
        <ResourceListItem
            v-for="content in filterFinished(content)"
            :content="content"
            finished
        ></ResourceListItem>
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
