<template>
    <div v-if="!loading">
        <div v-for="(error, index) in errors" :key="'error-'+collection.id+'-'+index" class="alert alert-danger mb-0">
            {{ error }}
        </div>
        <ResourceList
            :content="collection.content"
            :update-method="updateCollection"
        ></ResourceList>

        <h5>Log:</h5>
        <log-display :logs="collection.logs"></log-display>
    </div>
</template>

<script>
    import ResourceList from "./resource/ResourceList";
    import LogDisplay from "./LogDisplay";

    export default {
        name: "CollectionSingle",
        components: {ResourceList,LogDisplay},
        props: {
            id: Number
        },
        data() {
            return {
                loading: true,
                errors: [],
                collection: {}
            }
        },
        mounted() {
            this.initAxios().then(() => {
                this.axios.get(this.replaceUrl(window.routes.collections.show, this.id)).then(response => {
                    this.collection = response.data;
                    this.loading = false;
                })
            });
        },
        methods: {
            updateCollection: function (content) {
                if (typeof content.update_amount === "undefined" || content.update_amount <= 0) {
                    return;
                }

                this.errors = [];

                this.axios.post(window.routes.logs.store, {
                    collection_id: this.collection.id,
                    resource_id: content.resource_id,
                    update_amount: content.update_amount,
                }).then(response => {
                    let updatedContent = this.collection.content.find(con => con.id === content.id);
                    updatedContent.sum += (content.update_amount);
                    content.update_amount = 0;

                    this.collection.logs.unshift(response.data);
                }).catch(error => {
                    if (error.response.status === 409) {
                        this.errors.push(`Die angegebene Anzahl von '${content.resource.name}' übersteigt das Sammlungsziel.`);
                    } else {
                        this.errors.push(`Konnte '${content.resource.name}' nicht zur Sammlung hinzufügen.`);
                    }
                });
            },
        }
    }
</script>
