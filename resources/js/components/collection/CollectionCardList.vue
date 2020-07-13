<template>
    <div>
        <p v-if="collections.length === 0" class="mb-0">Keine Sammlungen vorhanden.</p>

        <div class="accordion" id="collectionList">
            <Collection
                v-for="collection in collections"
                :key="collection.id + '_' + collection.vueKey"
                :collection="collection"
                :public-mode="publicMode"
                v-on:delete-collection="removeCollection(collection)"
            ></Collection>
        </div>
    </div>
</template>

<script>
    import LogDisplay from "./LogDisplay";
    import ResourceList from "./resource/ResourceList";
    import Collection from "./Collection";

    export default {
        components: {Collection, ResourceList, LogDisplay},
        props: {
            collections: Array,
            publicMode: Boolean,
        },
        methods: {
            removeCollection: function (collection) {
                this.collections.filter(item => item !== collection);
            },
        }
    }
</script>
