<template>
    <div class="activity">
        <div class="activity-header">
            <IconButton icon="close" @b-i-click="onBack"></IconButton>
            <div class="activity-header-place-text">
                <p class="you-title-large">
                    {{ nameActivity }}
                </p>
            </div>
        </div>
        <div class="activity-content">
            <GroupInputs label="Основное">
                <Input v-model="objectData.name" label="Имя"></Input>
                <Selector v-model="selectTypeIndex" @input="loadAccessTypeTemplates" label="Выбор типа"
                          :variants="types">
                </Selector>
                <Selector v-model="selectTemplateIndex" @input="loadAccessTemplate" label="Выбор шаблона"
                          :variants="templates"></Selector>
                <GroupInputs v-if="dataField[0] != 'nodata'" label="Данные для заполнения">
                    <Input v-for="(elem, index) in dataField" :key="index" v-model="objectData.data[elem]"
                           :label="elem"></Input>
                </GroupInputs>
                <Button text="Сохранить" @b-click="addAccess"></Button>
            </GroupInputs>
            <GroupInputs label="Важное">
                <Button type="error" text="Удалить" @b-click="deleteAccess"></Button>
            </GroupInputs>
        </div>
    </div>
</template>

<script>
import {types} from 'util'
import route from "ziggy-js";

export default {
    data() {
        return {
            types: [{id: 1, name: ' '}],
            templates: [{id: 1, name: ' '}],
            currentTemplate: {},

            dataField: ["nodata"],

            selectTypeIndex: 0,
            selectTemplateIndex: 0,

            objectData: {},
            action: ''
        }
    },
    methods: {
        onBack() {
            this.$router.push(`/app/client/${this.$route.params.clientId}/access`)
        },
        setAction() {
            this.action = this.$route.params.action
        },
        loadAccess() {
            if (this.action == 'edit') {

                this.axios.get(route('access.show', {access: this.$route.params.accessId}))
                    .then(response => {
                        this.objectData = response.data
                        this.setParams(this.objectData.sample_id)
                    }).catch(error => {
                    this.$noty.info('Неудалось получить доступ');
                });
            } else if (this.action == 'create') {
                this.objectData = {
                    name: '',
                    client_id: this.$route.params.clientId,
                    sample_id: 1,
                    data: {}
                }
                this.setParams(this.objectData.sample_id)
            }
        },
        setParams(sample) {
            var url = `/api/sample/${sample}`
            this.axios.get(route()).then(response => {
                this.currentTemplate = response.data

                let stringData = JSON.stringify(this.currentTemplate.data)
                let arrFileds = stringData.match(/(?<=%)\w+(?=%)/g);

                this.dataField = arrFileds;

            }).catch(error => {
                this.$noty.info('Неудалось получить шаблоны');
            });
        },
        loadAccessTypes() {
            var url = `/api/type/`
            this.axios.get(url).then(response => {
                this.types = response.data
            }).catch(error => {
                this.$noty.info('Неудалось получить типы');
            });
        },
        loadAccessTypeTemplates() {
            var url = `/api/type/${this.types[this.selectTypeIndex].id}/sample`
            this.axios.get(url).then(response => {
                this.templates = response.data
            }).catch(error => {
                this.$noty.info('Неудалось получить шаблоны');
            });
        },
        loadAccessTemplate() {
            this.objectData.sample_id = this.templates[this.selectTemplateIndex].id;

            var url = `/api/sample/${this.objectData.sample_id}`
            this.axios.get(url).then(response => {
                this.currentTemplate = response.data
                let stringData = JSON.stringify(this.currentTemplate.data)
                let arrFileds = stringData.match(/(?<=%)\w+(?=%)/g);
                for (let field in arrFileds) {
                    this.objectData.data[field] = ''
                }
                this.dataField = arrFileds;
            }).catch(error => {
                this.$noty.info('Неудалось получить шаблон');
            });
        },
        addAccess() {
            if (this.action == 'create') {
                this.axios.post(route('access.store'), {
                    name: this.objectData.name,
                    client_id: this.objectData.client_id,
                    sample_id: this.objectData.sample_id,
                    data: this.objectData.data
                }).then(response => {
                    this.onBack()
                }).catch(error => {
                    this.$noty.info('Не удалось создать доступ');
                });
            } else if (this.action == 'edit') {
                var url = `/api/client/${this.$route.params.clientId}/access/${this.$route.params.accessId}`
                this.axios.put(route('access.edit', {access: this.$route.params.accessId}), {
                    name: this.objectData.name,
                    client_id: this.objectData.client_id,
                    sample_id: this.objectData.sample_id,
                    data: this.objectData.data
                }).then(response => {
                    this.onBack()
                }).catch(error => {
                    this.$noty.info('Не удалось отредактировать клиента');
                });
            }
        },
        deleteAccess() {
            var url = `/api/client/${this.$route.params.clientId}/access/${this.$route.params.clientId}`
            this.axios.delete(url).then(response => {
                this.onBack()
            }).catch(error => {
                this.$noty.info('Неудалось удалить клиента');
            });
        },

    },
    computed: {
        nameActivity() {
            if (this.action == 'create') {
                return 'Создание доступа'
            } else if (this.action == 'edit') {
                return 'Редактирование доступа'
            }
        },
    },
    mounted() {
        this.setAction()
        this.loadAccess()
        this.loadAccessTypes()
        this.loadAccessTypeTemplates()
    }
}
</script>

<style>
.activity {
    border: 4px solid var(--primary-container);
}

.cos-input {
    width: 100%;
    resize: vertical;
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
</style>
