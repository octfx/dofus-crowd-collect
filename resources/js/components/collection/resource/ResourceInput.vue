<template>
    <div class="form-group row">
        <div class="col-12 col-md-5">
            <autocomplete
                :search="search"
                placeholder="Ressourcenname"
                :get-result-value="format"
                :default-value="content.name"
                v-bind:base-class="[hasResourceError ? 'autocomplete is-invalid' : 'autocomplete']"
                @submit="submit"
            ></autocomplete>
            <small v-if="hasResourceError"
                   class="d-block invalid-feedback">
                <span v-if="!content.name">Dieses Feld wird benötigt.</span>
                <span v-else>Diese Ressource existiert nicht im Spiel.</span>

            </small>
        </div>
        <div class="col-12 col-md-3">
            <input type="number"
                   class="form-control"
                   v-bind:class="{ 'is-invalid': hasValueError }"
                   name="amount[]"
                   placeholder="Anzahl"
                   min="1"
                   max="10000"
                   v-model.number="content.amount">
            <small v-if="hasValueError"
                   class="d-block invalid-feedback">
                <span v-if="!content.amount">Dieses Feld wird benötigt.</span>
                <span v-else>Die Anzahl übersteigt die zulässigen 10.000.</span>
            </small>
        </div>
        <div class="col-12 col-md-3">
            <input type="text"
                   class="form-control"
                   name="note[]"
                   placeholder="Notiz"
                   maxlength="250"
                   v-model.number="content.note">
        </div>
        <div class="col-12 col-md-1">
            <button type="button"
                    class="btn btn-outline-danger flex-fill"
                    title="Resource aus der Sammlung entfernen"
                    v-on:click.prevent="$emit('remove')">&minus;
            </button>
        </div>

    </div>
</template>

<script>
    import Autocomplete from '@trevoreyre/autocomplete-vue'

    export default {
        name: "ResourceInput",
        components: {
            Autocomplete,
        },
        props: {
            searchMethod: Function,
            content: Object,
            hasResourceError: Boolean,
            hasValueError: Boolean
        },
        methods: {
            search: function (input) {
                this.content.name = input;

                if (input.length < 2) {
                    return [];
                }

                return this.axios.post(window.routes.resources.search, {
                    name: input,
                }).then(result => {
                    return result.data;
                });
            },
            submit: function (result) {
                this.content.name = result.name;
            },
            format: function (result) {
                return result.name;
            }
        },
    }
</script>
