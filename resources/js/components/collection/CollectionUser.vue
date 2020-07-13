<template>
    <div>
        <p v-if="collections.length === 0 && !loading" class="mb-0">Keine Sammlungen vorhanden.</p>
        <p v-if="loading" class="mb-0">Lade Sammlungen&hellip;</p>

        <div class="accordion" id="collectionList">
            <Collection
                v-for="collection in collections"
                :key="collection.id + '_' + collection.vueKey"
                :collection="collection"
                :public-mode="true"
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
            name: String
        },
        data() {
            return {
                collections: [],
                loadUrl: '',
                loading: true,
            }
        },
        mounted() {
            this.loadUrl = this.replaceUrl(window.routes.collections.showUser, this.name);

            this.initAxios().then(() => {
                this.loadContent();

                setInterval(function () {
                    this.loadContentUpdated();
                }.bind(this), 5000);
            });
        },
        methods: {
            loadContent: function () {
                this.loading = true;

                this.axios.get(this.loadUrl)
                    .then((response) => {
                        this.loading = false;
                        this.collections = response.data;
                    });
            },
            loadContentUpdated: function () {
                this.axios.get(this.loadUrl, {timeout: 2000})
                    .then((response) => {
                        this.timeout = false;
                        this.collections = response.data;
                    })
                    .catch(() => {
                        this.timeout = true;
                    });
            },
        }
    }
</script>
