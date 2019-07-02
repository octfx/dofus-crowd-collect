<template>
    <form @submit.prevent="submit" method="POST" :action="postUrl" class="mx-auto w-75 mb-4">
        <div v-if="errors.length" class="error-container">
            <div v-for="(error, index) in errors" :key="index" class="alert alert-danger">
                Fehler!<br>
                Sammlung <i>{{error.collectionName}}</i> konnte nicht erstellt werden.
            </div>
        </div>
        <div v-if="successes.length" class="success-container">
            <div v-for="(collection, index) in successes" :key="index" class="alert alert-success">
                Sammlung <i>{{collection.name}}</i> erfolgreich erstellt!
            </div>
        </div>
        <div class="form-group">
            <label for="name">Name</label>

            <input id="name" type="text" class="form-control" name="name" required autofocus v-model="collectionName">
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

        <h5>Inhalt:</h5>
        <resource-input
                v-for="(content, index) in collectionContent"
                :key="index"
                :content="content"
                :add-method="addContent"
                :search-method="search"
                :remove-method="removeContent"
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
                successes: [],
            }
        },
        mounted() {
            this.initAxios();
            this.addContent();
        },
        methods: {
            submit: function () {
                this.collectionContent = this.collectionContent.filter(content => {
                    return content.name.length > 0
                });

                this.axios.post(this.postUrl, {
                    name: this.collectionName,
                    'public': this.collectionPublic,
                    content: this.collectionContent
                }).then((response) => {
                    this.successes.push(response.data);
                    this.clear();
                }).catch((error) => {
                    error.collectionName = this.collectionName;
                    this.errors.push(error);
                })
            },
            addContent: function () {
                this.collectionContent.push({
                    name: '',
                    amount: 0,
                });
            },
            removeContent: function (content) {
                const len = this.collectionContent.length;
                if (len > 1) {
                    this.collectionContent = this.collectionContent.filter(item => item !== content);
                }
            },
            clear: function () {
                this.collectionContent = [];
                this.collectionPublic = true;
                this.collectionName = '';

                this.addContent();
            },
            search: function (input) {
                return this.axios.post(this.searchUrl, {
                    name: input
                }).then(result => {
                    return result.data;
                })
            }
        }
    }
</script>
