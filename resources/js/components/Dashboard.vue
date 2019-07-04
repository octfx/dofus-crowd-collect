<template>
    <div>
        <p v-if="loading" class="mb-0">Lade Sammlungen...</p>
        <div v-for="(error, index) in errors" :key="'error-'+index" class="alert alert-danger">
            {{ error }}
        </div>
        <collection-card-list
                v-if="!loading && !publicMode"
                :edit-url="editUrl"
                :delete-method="removeCollection"
                :update-method="updateCollection"
                :collections="collections"
                :update-collection-visibility="updateCollectionVisibility"
        ></collection-card-list>
        <collection-card-list
                v-if="!loading && publicMode"
                :update-method="updateCollection"
                :collections="collections"
                :public-mode="publicMode"
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
            this.initAxios();
            this.loadContent(this.getUrl);
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
            removeCollection: function (collection) {
                this.collections = this.collections.filter(item => item !== collection);
                this.axios.delete(this.replaceUrl(this.deleteUrl, collection))
                    .catch(() => {
                        this.errors.push(`Fehler beim Löschen von ${collection.name}.`);
                        this.collections.push(collection);
                    });
            },
            updateCollectionVisibility: function (collection) {
                this.axios.patch(this.replaceUrl(this.updateUrl, collection), {
                    public: !collection.public,
                }).then(response => {
                    collection.public = response.data.public;
                }).catch(() => {
                    this.errors.push(`Fehler beim Ändern der Sichtbarkeit von ${collection.name}.`);
                });
            },
            updateCollection: function (content) {
                if (typeof content.update_amount === "undefined" || content.update_amount <= 0) {
                    return;
                }

                this.axios.post(this.createLogUrl, {
                    collection_id: content.collection_id,
                    resource_id: content.resource_id,
                    update_amount: content.update_amount,
                }).then(response => {
                    let collection = this.collections.find(col => col.id === content.collection_id);
                    let updatedContent = collection.content.find(con => con.id === content.id);
                    updatedContent.sum += (content.update_amount);
                    content.update_amount = 0;

                    collection.logs.unshift(response.data);
                }).catch(() => {
                    this.errors.push(`Konnte <i>${content.resource.name}</i> nicht zur Sammlung hinzufügen.`);
                });
            }
        }
    }
</script>
