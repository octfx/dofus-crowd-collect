<template>
    <div>
        <p v-if="collections.length === 0" class="mb-0">Keine Sammlungen vorhanden.</p>

        <div class="accordion" id="collectionList">
            <div class="card" v-for="(collection, index) in collections">
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
                        <button v-if="deleteMethod && !publicMode" type="button" class="btn btn-outline-danger flex-grow-0"
                                v-on:click.prevent="deleteMethod(collection)" title="Löschen">🗑️
                        </button>
                    </div>
                </div>

                <div :id="'collection-'+index" class="collapse" :aria-labelledby="'collectionHeading-'+index"
                     data-parent="#collectionList">
                    <div class="card-body" v-if="collection.content.length" :id="'collectionForm-'+index">
                        <table class="table table-borderless">
                            <thead>
                            <tr>
                                <th>Ressource</th>
                                <th colspan="2">Fehlend</th>
                                <th>Gesammelt</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="content in collection.content" :key="content.name">
                                <td class="pt-3 text-nowrap" :class="resourceFinished(content) ? 'text-success' : ''">{{ content.resource.name }}</td>
                                <td>
                                    <input type="number" min="0" :max="calculateMissing(content)" placeholder="0"
                                           class="form-control-sm w-100" v-model="content.update_amount">
                                </td>
                                <td class="pt-3 text-nowrap">/ {{ calculateMissing(content) }}</td>
                                <td class="pt-3 text-nowrap">{{ content.sum }} / {{ content.amount }}</td>
                                <td>
                                    <button type="button" class="btn btn-outline-success flex-fill"
                                            v-on:click.prevent="updateMethod(content)">&plus;
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                        <hr class="my-5">
                        <div>
                            <h5>Log:</h5>
                            <log-display :logs="collection.logs"></log-display>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
    import LogDisplay from "./LogDisplay";

    export default {
        components: {LogDisplay},
        props: {
            updateMethod: Function,
            updateCollectionVisibility: Function,
            deleteMethod: Function,
            collections: Array,
            publicMode: Boolean
        },
        data() {
            return {
                updatedValues: [],
            }
        },
        methods: {
            calculateMissing: function (content) {
                return Math.max(content.amount - content.sum, 0);
            },
            resourceFinished: function (content) {
                return parseInt(content.sum) === parseInt(content.amount);
            }
        },
    }
</script>
