<template>
    <div>
        <div v-if="timeout" class="alert alert-info">Konnte Liste nicht aktualisieren&hellip;</div>
        <p v-if="loading" class="mb-0">Lade Sammlungen&hellip;</p>
        <collection-card-list
                v-if="!loading && !publicMode"
                :collections="collections"
        ></collection-card-list>
        <collection-card-list
                v-if="!loading && publicMode"
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
            publicMode: Boolean,
        },
        data() {
            return {
                loading: true,
                timeout: false,
                collections: [],
                response: {},
                currentUrl: ""
            };
        },
        mounted() {
            this.initAxios().then(() => {
                if (this.publicMode) {
                    this.loadContent(window.routes.collections.index);
                } else {
                    this.loadContent(window.routes.personal.collections.index);
                }

                setInterval(function() {
                    this.loadContentUpdated();
                }.bind(this), 5000);
            });
        },
        methods: {
            loadContent: function(url) {
                this.loading = true;
                this.currentUrl = url;
                this.axios.get(url)
                    .then((response) => {
                        this.loading = false;
                        this.response = response.data;
                        this.collections = response.data.data;
                    });
            },
            loadContentUpdated: function() {
                this.axios.get(this.currentUrl, {timeout: 2000})
                    .then((response) => {
                        this.timeout = false;
                        this.response = response.data;
                        this.collections = response.data.data;
                    })
                    .catch(() => {
                        this.timeout = true;
                    });
            },
        }
    }
</script>
