<template>
    <table v-if="logs.length" class="table table-striped   table-sm mb-0">
        <thead>
        <tr>
            <th>Benutzer</th>
            <th>Resource</th>
            <th>Anzahl</th>
            <th>Datum</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="log in logs">
            <td>{{ log.user.username }}</td>
            <td>{{ log.resource.name }}</td>
            <td>{{ log.amount }}</td>
            <td>{{ formatLogTime(log.created_at) }}</td>
        </tr>
        </tbody>

    </table>
</template>

<script>
    export default {
        name: "LogDisplay",
        props: {
            logs: Array
        },
        methods: {
            formatLogTime: function (time) {
                let parsed = new Date(time);
                return parsed.toLocaleTimeString();
            }
        }
    }
</script>
