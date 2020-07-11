<template>
    <div>
        <p v-if="collections.length === 0" class="mb-0">Keine Sammlungen vorhanden.</p>

        <div class="accordion" id="collectionList">
            <Collection
                v-for="(collection, index) in collections"
                :key="collection.vueKey"
                :collection="collection"
                :index="index"
                :public-mode="publicMode"
                :create-log-url="createLogUrl"
                :update-url="updateUrl"
                :remove-collection="removeCollection"
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
            createLogUrl: String,
            updateUrl: String,
            removeCollection: Function
        },
    }
</script>
