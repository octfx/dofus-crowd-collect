<template>
    <div class="form-group row">
        <div class="col-12 col-md-6 mb-2 mb-sm-0">
            <input type="text" class="form-control" name="resource[]" placeholder="Ressourcenname"
                   v-model="content.name">
        </div>
        <div class="col-12 col-md-3 mb-2 mb-sm-0">
            <input type="number" class="form-control" name="amount[]" placeholder="Anzahl" min="1" max="10000"
                   v-model="content.amount">
        </div>
        <div class="col-12 col-md-3">
            <div class="btn-group d-flex" role="group" aria-label="Edit Buttons">
                <button type="button" class="btn btn-outline-success flex-fill" v-on:click.prevent="add">&plus;</button>
                <button type="button" class="btn btn-outline-danger flex-fill" v-on:click.prevent="remove">&times;</button>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "ResourceInput",
        props: {
            addMethod: Function,
            removeMethod: Function,
            content: Object
        },
        methods: {
            add: function () {
                this.addMethod();
            },
            remove: function () {
                this.removeMethod(this.content);
            }
        },
        computed: {
            showDelete: function () {
                return this.content.name.length > 0 && this.content.amount > 0;
            }
        },
    }
</script>
