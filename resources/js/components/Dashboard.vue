<template>
    <div>
        <p v-if="loading" class="mb-0">Lade Sammlungen...</p>
        <collection-card-list
                v-if="!loading && !publicMode"
                :edit-url="editUrl"
                :delete-method="removeCollection"
                :update-method="updateCollection"
                :collections="collections"
        ></collection-card-list>
        <collection-card-list
                v-if="!loading && publicMode"
                :update-method="updateCollection"
                :collections="collections"
                :public-mode="publicMode"
        ></collection-card-list>
    </div>
</template>

<script>
    import CollectionCardList from "./collection/CollectionCardList";

    export default {
        components: {CollectionCardList},
        props: {
            getUrl: String,
            viewUrl: String,
            editUrl: String,
            updateUrl: String,
            deleteUrl: String,
            publicMode: Boolean
        },
        data() {
            return {
                loading: true,
                collections: []
            }
        },
        mounted() {
            this.initAxios();

            this.axios.get(this.getUrl)
                .then((response) => {
                    this.loading = false;
                    this.collections = response.data.data;
                })
        },
        methods: {
            removeCollection: function (collection) {
                this.collections = this.collections.filter(item => item !== collection);
                this.axios.delete(this.replaceUrl(this.deleteUrl, collection))
                    .catch(error => {
                        console.error(error);
                        this.collections.push(collection);
                    });
            },
            updateCollection: function (content) {
                if (typeof content.update_amount === "undefined" || parseInt(content.update_amount) <= 0) {
                    return;
                }

                this.axios.post(this.updateUrl, {
                    'collection_id': parseInt(content.collection_id),
                    'resource_id': parseInt(content.resource_id),
                    'update_amount': parseInt(content.update_amount),
                }).then(response => {
                    let collection = this.collections.find(col => col.id === parseInt(content.collection_id));
                    let updatedContent = collection.content.find(con => con.id === parseInt(content.id));
                    updatedContent.sum += parseInt(content.update_amount);
                    content.update_amount = 0;

                    collection.logs.unshift(response.data);
                }).catch(error => {

                });
            }
        }
    }
</script>
