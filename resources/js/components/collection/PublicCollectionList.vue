<template>
    <div>
        <p v-if="loading" class="mb-0">Lade Sammlungen...</p>
        <collection-card-list
                v-if="!loading"
                :update-method="updateCollection"
                :collections="collections"
        ></collection-card-list>
    </div>
</template>

<script>
    import CollectionCardList from "./CollectionCardList";

    export default {
        components: {CollectionCardList},
        props: {
            getUrl: String,
            viewUrl: String,
            editUrl: String,
            updateUrl: String,
            deleteUrl: String,
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
            updateCollection: function (content) {
                if (parseInt(content.update_amount) <= 0) {
                    return;
                }

                this.axios.post(this.updateUrl, {
                    'collection_id': parseInt(content.collection_id),
                    'resource_id': parseInt(content.resource_id),
                    'update_amount': parseInt(content.update_amount),
                }).then(response => {
                    let updatedContent = this.collections.find(col => col.id === parseInt(content.collection_id)).content.find(con => con.id === parseInt(content.id));
                    updatedContent.sum += parseInt(content.update_amount);
                    content.update_amount = 0;
                }).catch(error => {

                });
            }
        }
    }
</script>
