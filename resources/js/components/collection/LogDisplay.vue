<template>
    <table class="table table-striped table-sm mb-0">
        <thead>
        <tr>
            <th v-if="mode === 'singleCollection'">Benutzer</th>
            <th v-else>Sammlung</th>
            <th>Resource</th>
            <th>Anzahl</th>
            <th class="text-right">Datum</th>
        </tr>
        </thead>
        <tbody>
        <tr v-if="logs.length === 0">
            <td colspan="5">Keine Beiträge vorhanden.</td>
        </tr>
        <tr v-for="log in logs">
            <td v-if="mode === 'singleCollection'">{{ log.user.username }}</td>
            <td v-else>
                <span v-if="log.collection === null">Gelöscht</span>
                <span v-else-if="log.collection.user.username === log.user.username">{{ log.collection.name }}</span>
                <span v-else>{{ log.collection.name }} ({{ log.collection.user.username }})</span>
            </td>
            <td>{{ log.resource.name }}</td>
            <td>{{ log.amount }}</td>
            <td class="text-right">{{ formatLogTime(log.created_at) }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
    export default {
        name: "LogDisplay",
        props: {
            logs: Array,
            mode: {
                type: String,
                default: "singleCollection"
            }
        },
        methods: {
            formatLogTime: function (time) {
                let parsed = new Date(time);
                return `${parsed.toLocaleTimeString()} - ${parsed.toLocaleDateString()}`;
            }
        }
    }
</script>
