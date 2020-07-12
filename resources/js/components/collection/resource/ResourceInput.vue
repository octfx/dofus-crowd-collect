<template>
    <div class="form-group row">
        <div class="col-12 col-md-5 mb-2 mb-sm-0">
            <autocomplete
                    :search="search"
                    placeholder="Ressourcenname"
                    :get-result-value="format"
                    :default-value="content.name"
                    @submit="submit"
            ></autocomplete>
            <small v-if="hasResourceError" class="d-block invalid-feedback">Diese Ressource existiert nicht im
                Spiel.</small>
        </div>
        <div class="col-12 col-md-2 mb-2 mb-sm-0">
            <input type="number" class="form-control" name="amount[]" placeholder="Anzahl" min="1" max="10000"
                   v-model.number="content.amount">
            <small v-if="hasValueError" class="d-block invalid-feedback">Die Anzahl übersteigt die zulässigen
                10.000.</small>
        </div>
        <div class="col-12 col-md-3 mb-2 mb-sm-0">
            <input type="text" class="form-control" name="note[]" placeholder="Notiz" maxlength="250"
                   v-model.number="content.note">
        </div>
        <div class="col-12 col-md-2">
            <div class="btn-group d-flex" role="group" aria-label="Edit Buttons">
                <button type="button" class="btn btn-outline-success flex-fill" v-on:click.prevent="addMethod">&plus;</button>
                <button type="button" class="btn btn-outline-danger flex-fill" v-on:click.prevent="remove">&minus;
                </button>
            </div>
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
            addMethod: Function,
            removeMethod: Function,
            searchMethod: Function,
            content: Object,
            hasResourceError: Boolean,
            hasValueError: Boolean
        },
        methods: {
            remove: function () {
                this.removeMethod(this.content);
            },
            search: function (input) {
                this.content.name = input;

                if (input.length < 2) {
                    return [];
                }

                return this.searchMethod(input);
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
