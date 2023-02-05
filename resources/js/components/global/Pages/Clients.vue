<template>
    <div class="activity">
        <div class="activity-header">
            <div class="activity-header-place-text">
                <p class="you-title-large">
                    Клиенты
                </p>
            </div>
        </div>
        <div class="activity-content">
            <Button text="Добавить" :round="false" :withIcon="true" icon="add" @b-click="addClient"></Button>
            <div class="table">
                <TableElem v-for="(elem, index) in clients" :key="index" :id="elem.id" :text="elem.name">
                    <IconButton :idData="{ id: index }" icon="edit" @b-i-click="editClient"></IconButton>
                    <Button :idData="{ id: index }" text="Открыть" @b-click="openClient"></Button>
                </TableElem>
            </div>
        </div>
    </div>
</template>

<script>
import route from "ziggy-js";

export default {
    data() {
        return {
            clients: []
        }
    },
    methods: {
        getClient() {
            var url = '/api/client'
            this.axios.get(route('clients.index')).then(response => {
                this.clients = response.data
            }).catch(error => { this.$noty.info('Неудалось получить клиентов'); });
        },
        openClient(e) {
            this.$router.push(`/app/client/${this.clients[e.id].id}/access`)
        },
        editClient(e) {
            this.$router.push(`/app/client/${this.clients[e.id].id}/action/edit`)
        },
        addClient() {
            this.$router.push('/app/client/action/create')
        }
    },
    created() {
        this.getClient()
    }
}
</script>

<style>
.activity {
    border: 4px solid var(--primary-container);
}

.activity-header {
    display: flex;
    flex-direction: row;
    align-items: center;
    padding: 12px 16px;
    gap: 8px;
    background: var(--primary-container);
}

.activity-header-place-text {
    display: flex;
    padding: 6px 0px;
    gap: 10px;
}

.activity-content {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    padding: 16px;
    gap: 16px;
}

.table {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    align-self: stretch;
}
</style>
