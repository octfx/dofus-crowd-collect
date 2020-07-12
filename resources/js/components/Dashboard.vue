<template>
    <div>
        <p v-if="loading" class="mb-0">Lade Sammlungen...</p>
        <collection-card-list
                v-if="!loading && !publicMode"
                :edit-url="editUrl"
                :collections="collections"
                :create-log-url="createLogUrl"
                :remove-collection="removeCollection"
                :update-url="updateUrl"
        ></collection-card-list>
        <collection-card-list
                v-if="!loading && publicMode"
                :collections="collections"
                :public-mode="publicMode"
                :create-log-url="createLogUrl"
        ></collection-card-list>
        <div class="d-flex mt-3">
            <button v-if="response.prev_page_url !== null"
                    @click.prevent="loadContent(response.prev_page_url)"
                    class="btn btn-outline-dark mr-auto">Zurück</button>
            <button v-if="response.next_page_url !== null"
                    @click.prevent="loadContent(response.next_page_url)"
                    class="btn btn-outline-dark ml-auto">Weiter</button>
        </div>
    </div>
</template>

<script>
    import CollectionCardList from "./collection/CollectionCardList";

    export default {
        components: {CollectionCardList},
        props: {
            getUrl: String,
            updateUrl: String,
            editUrl: String,
            createLogUrl: String,
            deleteUrl: String,
            publicMode: Boolean,
        },
        data() {
            return {
                loading: true,
                errors: [],
                collections: [],
                response: {},
            };
        },
        mounted() {
            this.initAxios().then(() => {
                this.loadContent(this.getUrl);

                setInterval(function() {
                    this.loadContentUpdated(this.getUrl);
                }.bind(this), 5000);
            });
        },
        methods: {
            loadContent: function(url) {
                this.loading = true;
                this.axios.get(url)
                    .then((response) => {
                        this.loading = false;
                        this.response = response.data;
                        this.collections = response.data.data;
                    });
            },
            loadContentUpdated: function(url) {
                this.axios.get(url)
                    .then((response) => {
                        this.response = response.data;
                        this.collections = response.data.data;
                    });
            },
            removeCollection: function (collection) {
                this.collections = this.collections.filter(item => item !== collection);
                this.axios.delete(this.replaceUrl(this.deleteUrl, collection))
                    .catch(() => {
                        this.errors.push(`Fehler beim Löschen von ${collection.name}.`);
                        this.collections.push(collection);
                    });
            },
        }
    }
</script>
