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
                <Selector v-model="selectTypeIndex" @input="setTypeId" label="Выбор типа" :variants="types"></Selector>
                <v-jsoneditor v-model="data" :options="{mode:'code'}" height="500px"></v-jsoneditor>
                <Button text="Сохранить" @b-click="addTemplate"></Button>
            </GroupInputs>
            <GroupInputs label="Важное">
                <Button type="error" text="Удалить" @b-click="deleteTemplate"></Button>
            </GroupInputs>
        </div>
    </div>
</template>

<script>

import VJsoneditor from 'v-jsoneditor'
export default {
    comments: {
        VJsoneditor
    },
    data() {
        return {
            types: [{ id: 1, name: ' ' }],
            selectTypeIndex: 0,

            currentType: {},
            data: "",

            objectData: {},
            action: '',
            check: ''
        }
    },
    methods: {
        onBack() {
            this.$router.push(`/app/template`)
        },
        setAction() {
            this.action = this.$route.params.action
        },
        loadTemplates() {
            if (this.action == 'edit') {
                var url = `/api/sample/${this.$route.params.templateId}`
                this.axios.get(url).then(response => {
                    this.objectData = response.data
                    this.data = JSON.parse(response.data.data)
                    this.setParams();
                }).catch(error => { this.$noty.info('Неудалось получить шаблон'); });
            }
        },
        setParams() {
            var url = `/api/type/`
            this.axios.get(url).then(response => {
                this.types = response.data

                for (let i = 0; i < this.types.length; i++) {
                    if (this.types[i].id == this.objectData.type_id) {
                        this.selectTypeIndex = i;

                        var url = `/api/type/${this.types[this.selectTypeIndex].id}`
                        this.axios.get(url).then(response => {
                            this.currentType = response.data
                        }).catch(error => { this.$noty.info('Неудалось получить тип'); });
                    }
                }
            }).catch(error => { this.$noty.info('Неудалось получить типы'); });
        },
        setTypeId() {
            Vue.delete(this.objectData, 'data')
            this.$set(this.objectData, 'data', {})
            this.objectData.type_id = this.types[this.selectTypeIndex].id
            this.loadType();
        },
        loadAccessTypes() {
            var url = `/api/type/`
            this.axios.get(url).then(response => {
                this.types = response.data
            }).catch(error => { this.$noty.info('Неудалось получить типы'); });
        },
        loadType() {
            var url = `/api/type/${this.types[this.selectTypeIndex].id}`
            this.axios.get(url).then(response => {
                this.currentType = response.data
            }).catch(error => { this.$noty.info('Неудалось получить тип'); });
        },
        addTemplate() {
            if (this.action == 'create') {
                var url = '/api/sample/'
                this.axios.post(url, {
                    name: this.objectData.name,
                    type_id: this.objectData.type_id,
                    data: JSON.stringify(this.data)
                }).then(response => {
                    if (Object.hasOwn(response.data, 1)) {
                        if (Object.hasOwn(response.data[1], 'valid')) {
                            this.$noty.info('Невалидный шаблон');
                            console.error(response.data)
                        }
                    }
                    else {
                        this.onBack()
                    }
                }).catch(error => {
                    console.log(error)
                    console.log("error?")
                    this.$noty.info('Не удалось создать шаблон');
                });
            }
            else if (this.action == 'edit') {
                var url = `/api/sample/${this.$route.params.templateId}`
                this.axios.put(url, {
                    name: this.objectData.name,
                    type_id: this.objectData.type_id,
                    data: JSON.stringify(this.data)
                }).then(response => {
                    this.onBack()
                }).catch(error => {
                    this.$noty.info('Не удалось отредактировать шаблон');
                });
            }
        },
        deleteTemplate() {
            var url = `/api/sample/${this.$route.params.templateId}`
            this.axios.delete(url).then(response => {
                this.onBack()
            }).catch(error => { this.$noty.info('Неудалось удалить шаблон'); });
        },

    },
    computed: {
        nameActivity() {
            if (this.action == 'create') {
                return 'Создание шаблона'
            }
            else if (this.action == 'edit') {
                return 'Редактирование шаблона'
            }
        },
    },
    created() {
        this.setAction()
        this.loadTemplates()
        // this.loadAccessTypes()
        // this.loadType()
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
</style>
