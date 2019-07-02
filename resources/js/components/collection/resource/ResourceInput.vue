<template>
    <div class="form-group row">
        <div class="col-12 col-md-6 mb-2 mb-sm-0">
            <autocomplete
                    :search="search"
                    placeholer="Ressourcenname"
                    :get-result-value="format"
                    @submit="submit"
            ></autocomplete>
        </div>
        <div class="col-12 col-md-3 mb-2 mb-sm-0">
            <input type="number" class="form-control" name="amount[]" placeholder="Anzahl" min="1" max="10000"
                   v-model="content.amount">
        </div>
        <div class="col-12 col-md-3">
            <div class="btn-group d-flex" role="group" aria-label="Edit Buttons">
                <button type="button" class="btn btn-outline-success flex-fill" v-on:click.prevent="add">&plus;</button>
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
            Autocomplete
        },
        props: {
            addMethod: Function,
            removeMethod: Function,
            searchMethod: Function,
            content: Object
        },
        methods: {
            add: function () {
                this.addMethod();
            },
            remove: function () {
                this.removeMethod(this.content);
            },
            search: function (input) {
                if (input.length < 2) {
                    return [];
                }

                this.content.name = input;

                return this.searchMethod(input);
            },
            submit: function (result) {
                this.content.name = result;
            },
            format: function (result) {
                return result.name;
            }
        },
    }
</script>
