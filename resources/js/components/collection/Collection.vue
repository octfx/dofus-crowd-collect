<template>
    <div class="card">
        <div v-for="(error, index) in errors" :key="'error-'+index" class="alert alert-danger mb-0">
            {{ error }}
        </div>
        <div class="card-header" :id="'collectionHeading-'+index">
            <div class="btn-group d-flex" role="group" aria-label="Edit Buttons">
                <button class="btn btn-link collapsed flex-grow-1 text-left" type="button"
                        data-toggle="collapse" :data-target="'#collection-'+index" aria-expanded="true"
                        :aria-controls="'collection-'+index">
                    <span v-if="publicMode">{{ collection.user.username }}: </span> {{ collection.name }}
                </button>
                <button v-if="!publicMode"
                        type="button"
                        class="btn flex-grow-0"
                        :class="collection.public ? 'btn-outline-success' : 'btn-outline-info'"
                        :title="collection.public ? 'Öffentlich' : 'Nicht Öffentlich'"
                        @click.prevent="updateCollectionVisibility(collection)"
                >
                    <span v-if="collection.public">👁️</span>
                    <span v-else>🔒</span>
                </button>
                <button v-if="removeCollection && !publicMode" type="button" class="btn btn-outline-danger flex-grow-0"
                        v-on:click.prevent="removeCollection(collection)" title="Löschen">🗑️
                </button>
            </div>
        </div>

        <div :id="'collection-'+index" class="collapse" :aria-labelledby="'collectionHeading-'+index"
             data-parent="#collectionList">
            <div class="card-body" v-if="collection.content.length" :id="'collectionForm-'+index">
                <ResourceList
                    :content="collection.content"
                    :update-method="updateCollection"
                ></ResourceList>

                <hr class="my-5">

                <div>
                    <h5>Log:</h5>
                    <log-display :logs="collection.logs"></log-display>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Collection",
        components: {ResourceList, LogDisplay},
        props: {
            collection: Object,
            index: Number,
            publicMode: Boolean,
            createLogUrl: String,
            updateUrl: String,
            removeCollection: Function
        },
        data() {
            return {
                errors: [],
            }
        },
        mounted() {
            this.initAxios();
        },
        methods: {
            calculateMissing: function (content) {
                return Math.max(content.amount - content.sum, 0);
            },
            resourceFinished: function (content) {
                return content.sum >= content.amount;
            },
            updateCollection: function (content) {
                if (typeof content.update_amount === "undefined" || content.update_amount <= 0) {
                    return;
                }

                this.errors = [];

                this.axios.post(this.createLogUrl, {
                    collection_id: content.collection_id,
                    resource_id: content.resource_id,
                    update_amount: content.update_amount,
                }).then(response => {
                    let updatedContent = this.collection.content.find(con => con.id === content.id);
                    updatedContent.sum += (content.update_amount);
                    content.update_amount = 0;

                    this.collection.logs.unshift(response.data);
                }).catch(error => {
                    console.log(error);
                    if (error.response.status === 409) {
                        this.errors.push(`Die angegebene Anzahl von '${content.resource.name}' übersteigt das Sammlungsziel.`);
                    } else {
                        this.errors.push(`Konnte '${content.resource.name}' nicht zur Sammlung hinzufügen.`);
                    }
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
        }
    }
</script>
