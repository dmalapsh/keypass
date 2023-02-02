<template>
    <div class="activity">
        <div class="activity-header">
            <IconButton icon="close" @b-i-click="onBack"></IconButton>
            <div class="activity-header-place-text">
                <p class="you-title-large">
                    {{ client.name }}
                </p>
            </div>
        </div>
        <div class="activity-content">
            <Button text="Добавить" :round="false" :withIcon="true" icon="add" @b-click="addAccess"></Button>
            <div class="table">
                <TableElem v-for="(elem, index) in accesses" :key="index" :id="elem.id" :text="elem.name">
                    <IconButton :idData="{id: index}" icon="edit" @b-i-click="editAccess"></IconButton>
                    <Button :idData="{id: index}" text="Войти" @b-click="signAccess"></Button>
                </TableElem>
            </div>
        </div>
    </div>
</template>

<script>
import IconButton from './elems/IconButton.vue';
export default {
    data() {
        return {
            client: [],
            accesses: []
        };
    },
    methods: {
        getDataApi() {
            let url = `/api/client/${this.$route.params.clientId}`;
            this.axios.get(url).then(response => {
                this.client = response.data;
            }).catch(error => { this.$noty.info("Неудалось получить клиента"); });
            url = `/api/client/${this.$route.params.clientId}/access`;
            this.axios.get(url).then(response => {
                this.accesses = response.data;
            }).catch(error => { this.$noty.info("Неудалось получить доступы клиента"); });
        },
        signAccess(e) {
            let url = `/api/client/${this.$route.params.clientId}/access/${this.accesses[e.id].id}/compil`;
            this.axios.get(url).then(response => {
                console.log(response.data);
                axios.post('http://localhost:8005', response.data.data)
            }).catch(error => { this.$noty.info("Неудалось получить клиента"); });
        },
        editAccess(e) {
            this.$router.push(`/app/client/${this.$route.params.clientId}/access/${this.accesses[e.id].id}/action/edit`)
        },
        addAccess() {
            this.$router.push(`/app/client/${this.$route.params.clientId}/access/action/create`)
        },
        onBack() {
            this.$router.push('/app/client/')
        }
    },
    created() {
        this.getDataApi();
    },
    components: { IconButton }
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
