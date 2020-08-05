<template>
    <form @submit.prevent="submit" method="POST" :action="store" class="mx-auto mb-4">
        <div v-for="(error, index) in errors" :key="'error-'+index" class="alert alert-danger">
            Fehler<br>
            Sammlung <i>{{error.collectionName}}</i> konnte nicht erstellt werden.
        </div>
        <div v-for="(collection, index) in successes" :key="'success-'+index" class="alert alert-success">
            Sammlung <i>{{collection.name}}</i> erfolgreich erstellt!
        </div>
        <div v-if="creating" class="alert alert-info">
            {{ creating }}
        </div>
        <div class="form-group">
            <label for="name">Name</label>
            <input id="name" type="text" class="form-control" name="name" placeholder="Bezeichnung der Sammlung" required autofocus v-model="collectionName">
        </div>

        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input id="public" type="checkbox" checked class="custom-control-input" name="public"
                       v-model="collectionPublic">
                <label class="custom-control-label" for="public">Öffentlich listen</label>
            </div>
            <small id="publicHelpBlock" class="form-text text-muted">
                Damit wird deine Sammlung öffentlich für alle angezeigt.
            </small>
        </div>

        <h5>Inhalt</h5>
        <resource-input
            v-for="(content, index) in collectionContent"
            :key="content.uuid+'_'+id"
            :content="content"
            :has-resource-error="hasResourceError(index)"
            :has-value-error="hasValueError(index)"
            v-on:remove="removeContent(index)"
        ></resource-input>

        <div class="row">
            <div class="col-12">
                <button class="btn btn-outline-success btn-block mb-3" v-on:click.prevent="addContent">
                    Weitere Ressource hinzufügen
                </button>
            </div>
        </div>

        <div class="form-group mt-4 mb-0">
            <button type="submit" class="btn btn-block btn-outline-dark">
                Sammlung erstellen
            </button>
        </div>
    </form>
</template>

<script>
    import {uniqueId} from "lodash";

    import ResourceInput from "./resource/ResourceInput";

    export default {
        name: "CreateCollection",
        components: {ResourceInput},
        data() {
            return {
                store: window.routes.collections.store,
                collectionName: '',
                collectionPublic: true,
                collectionContent: [],
                errors: [],
                invalidFields: {},
                successes: [],
                creating: '',
                id: 0
            };
        },
        mounted() {
            this.initAxios();
            this.addContent();
        },
        methods: {
            submit: function () {
                this.errors = [];
                this.successes = [];
                this.invalidFields = {};
                this.creating = `Erstelle Sammlung ${this.collectionName}...`;

                this.axios.post(window.routes.collections.store, {
                    name: this.collectionName,
                    public: this.collectionPublic,
                    content: this.collectionContent,
                }).then((response) => {
                    this.creating = '';
                    this.successes.push(response.data);
                    this.id = response.data.id + 1;
                    this.clear();
                }).catch((error) => {
                    this.creating = '';
                    error.collectionName = this.collectionName;
                    this.errors = [error];
                    this.invalidFields = error.response.data.errors;
                })
            },
            addContent: function () {
                this.collectionContent.push({
                    uuid: uniqueId('resourceInput_')
                });
            },
            removeContent: function (index) {
                if (this.collectionContent.length > 1) {
                    this.$delete(this.collectionContent, index);
                }
            },
            clear: function () {
                this.collectionContent.length = 0;
                this.collectionContent = [];

                this.collectionPublic = true;
                this.collectionName = '';

                this.addContent();
            },
            hasResourceError(index) {
                for (const invalidFieldsKey in this.invalidFields) {
                    if (invalidFieldsKey === `content.${index}.name`) {
                        return true;
                    }
                }
                return false;
            },
            hasValueError(index) {
                for (const invalidFieldsKey in this.invalidFields) {
                    if (invalidFieldsKey === `content.${index}.amount`) {
                        return true;
                    }
                }
                return false;
            }
        },
        computed: {}
    }
</script>
