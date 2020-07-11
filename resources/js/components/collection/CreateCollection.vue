<template>
    <form @submit.prevent="submit" method="POST" :action="postUrl" class="mx-auto w-75 mb-4">
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
            :key="'resourceInput-'+index"
            :content="content"
            :add-method="addContent"
            :search-method="search"
            :remove-method="removeContent"
            :has-resource-error="hasResourceError(index)"
            :has-value-error="hasValueError(index)"
        ></resource-input>

        <div class="form-group mb-0">
            <button type="submit" class="btn btn-block btn-outline-dark">
                Erstellen
            </button>
        </div>
    </form>
</template>

<script>
    import ResourceInput from "./resource/ResourceInput";

    export default {
        name: "CreateCollection",
        components: {ResourceInput},
        props: {
            postUrl: String,
            editUrl: String,
            searchUrl: String,
        },
        data() {
            return {
                collectionName: '',
                collectionPublic: true,
                collectionContent: [],
                errors: [],
                invalidFields: {},
                successes: [],
                creating: '',
            };
        },
        mounted() {
            this.initAxios();
            this.addContent();
        },
        methods: {
            submit: function () {
                this.errors = [];
                this.invalidFields = {};
                this.creating = `Erstelle Sammlung ${this.collectionName}...`;

                this.axios.post(this.postUrl, {
                    name: this.collectionName,
                    public: this.collectionPublic,
                    content: this.collectionContent,
                }).then((response) => {
                    this.creating = '';
                    this.successes.push(response.data);
                    this.clear();
                }).catch((error) => {
                    console.log(error.response);
                    this.creating = '';
                    error.collectionName = this.collectionName;
                    this.errors = [error];
                    this.invalidFields = error.response.data.errors;
                })
            },
            addContent: function () {
                this.collectionContent.push({});
            },
            removeContent: function (content) {
                const len = this.collectionContent.length;
                if (len > 1) {
                    this.collectionContent = this.collectionContent.filter(item => item !== content);
                }
            },
            clear: function () {
                this.collectionContent.length = 0;
                this.collectionPublic = true;
                this.collectionName = '';

                this.addContent();
            },
            search: function (input) {
                return this.axios.post(this.searchUrl, {
                    name: input,
                }).then(result => {
                    return result.data;
                });
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
