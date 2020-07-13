<template>
    <div class="card">
        <div v-for="(error, index) in errors" :key="'error-'+collection.id+'-'+index" class="alert alert-danger mb-0">
            {{ error }}
        </div>
        <div class="card-header" :id="'collectionHeading-'+collection.id">
            <div class="btn-group d-flex" role="group" aria-label="Edit Buttons">
                <button class="btn btn-link collapsed flex-grow-1 text-left" type="button"
                        data-toggle="collapse" :data-target="'#collection-'+collection.id" aria-expanded="true"
                        :aria-controls="'collection-'+collection.id">
                    <span v-if="publicMode">{{ collection.user.username }}: </span> {{ collection.name }}
                </button>
                <a type="button"
                   class="btn flex-grow-0 btn-outline-light"
                   style="margin-right: 1px"
                   title="Direktlink"
                   :href="directLink"
                >🔗</a>
                <button v-if="!publicMode"
                        type="button"
                        class="btn flex-grow-0"
                        :class="collection.public ? 'btn-outline-success' : 'btn-outline-info'"
                        :title="collection.public ? 'Öffentlich' : 'Nicht Öffentlich'"
                        @click.prevent="updateCollectionVisibility"
                >
                    <span v-if="collection.public">👁️</span>
                    <span v-else>🔒</span>
                </button>
                <button v-if="removeCollection && !publicMode" type="button" class="btn btn-outline-danger flex-grow-0"
                        v-on:click.prevent="removeCollection" title="Löschen">🗑️
                </button>
            </div>
        </div>

        <div :id="'collection-'+collection.id" class="collapse" :aria-labelledby="'collectionHeading-'+collection.id"
             data-parent="#collectionList">
            <div class="card-body" v-if="collection.content.length" :id="'collectionForm-'+collection.id">
                <ResourceList
                    :content="collection.content"
                    :update-method="updateCollection"
                ></ResourceList>

                <h5>Log:</h5>
                <log-display :logs="collection.logs"></log-display>
            </div>
        </div>
    </div>
</template>

<script>
    import ResourceList from "./resource/ResourceList";
    import LogDisplay from "./LogDisplay";

    export default {
        name: "Collection",
        components: {ResourceList, LogDisplay},
        props: {
            collection: Object,
            publicMode: Boolean,
        },
        data() {
            return {
                errors: [],
                directLink: this.replaceUrl(window.routes.web.collections.show, this.collection.id)
            }
        },
        mounted() {
            this.initAxios();
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
            updateCollectionVisibility: function () {
                this.axios.patch(this.replaceUrl(window.routes.collections.update, this.collection.id), {
                    public: !this.collection.public,
                }).then(response => {
                    this.collection.public = response.data.public;
                }).catch(() => {
                    this.errors.push(`Fehler beim Ändern der Sichtbarkeit von ${this.collection.name}.`);
                });
            },
            removeCollection: function () {
                this.axios
                    .delete(this.replaceUrl(window.routes.collections.destroy, this.collection.id))
                    .then(() => {
                        this.$emit('delete-collection');
                    })
                    .catch(() => {
                        this.errors.push(`Fehler beim Löschen von ${this.collection.name}.`);
                    });
            }
        }
    }
</script>
