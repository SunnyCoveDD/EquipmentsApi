<template>
    <div class="container m-5 d-grid gap-4">
        <div class="d-flex gap-3">
            <input @input="search()" v-model="searchText" class="form-control" style="width: 300px" type="text">
            <button v-show="!isChecked & !createEquipmentForm" @click="createForm()" type="button" class="btn btn-success">Создать</button>
            <button v-show="!isChecked & createEquipmentForm" @click="createEquipmentForm = false" type="button" class="btn btn-outline-success">Убрать форму создания</button>
            <button v-show="isChecked" @click="getOneEquipment()" type="button" class="btn btn-primary">Изменить</button>
            <button v-show="isChecked" @click="deleteEquipment()" type="button" class="btn btn-danger">Удалить</button>
        </div>
        <table class="table border shadow p-3 mb-5 bg-body-tertiary rounded">
            <thead>
                <tr>
                    <th class="col"></th>
                    <th class="col">#</th>
                    <th class="col">Тип оборудования</th>
                    <th class="col">Серийный номер</th>
                    <th class="col">Комментарий</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <tr v-for="equipment in equipments">
                    <td class="col">
                        <input class="form-check-input"
                               type="checkbox"
                               :checked="checkedEquipment === equipment.id"
                               @change="check(equipment.id)"
                        >
                    </td>
                    <td class="col">{{equipment.id}}</td>
                    <td class="col">{{equipment.equipment_type.name}}</td>
                    <td class="col">{{equipment.serial_number}}</td>
                    <td class="col">{{equipment.description}}</td>
                </tr>
            </tbody>
        </table>
        <div class="d-flex gap-3">
            <button
                type="button"
                class="btn btn-outline-secondary"
                @click="prevPage"
                :disabled="page === 1"
            >
                Предыдущая страница
            </button>
            <button
                class="btn btn-outline-secondary"
                type="button"
                @click="nextPage"
                :disabled="page === maxPage"
            >
                Следующая страница
            </button>
        </div>
        <form class="border shadow p-3 mb-5 bg-body-tertiary rounded p-3" style="max-width: 500px" v-show="oneEquipment.hasOwnProperty('id') && isChecked">
            <p class="fs-4">Редактирование записи</p>
            <div class="d-grid gap-3">
                <div>
                    <label>Тип оборудования: </label>
                    <select class="form-control mt-1" v-model="oneEquipment.equipment_type_id">
                        <option v-for="type in equipmentsType" :selected="type.id === oneEquipment.equipment_type_id" :value="type.id">{{type.name}}</option>
                    </select>
                </div>
                <div>
                    <label>Серийный номер: </label>
                    <input :class="{'is-invalid': errorList.serial_number}" class="form-control mt-1" v-model="oneEquipment.serial_number" type="text">
                    <p class="invalid-feedback">{{errorList.serial_number}}</p>
                </div>
                <div>
                    <label>Комментарий: </label>
                    <textarea :class="{'is-invalid': errorList.description}" class="form-control mt-1" v-model="oneEquipment.description"></textarea>
                    <p class="invalid-feedback">{{errorList.description}}</p>
                </div>
                <button style="width: 300px" type="button" class="btn btn-primary" @click="editEquipment()">Изменить</button>
            </div>
        </form>
        <form class="border shadow p-3 mb-5 bg-body-tertiary rounded p-3" style="max-width: 500px" v-show="createEquipmentForm">
            <p class="fs-4">Создание записи</p>
            <div class="d-grid gap-3">
                <div>
                    <label>Тип оборудования: </label>
                    <select class="form-control mt-1" v-model="newEquipment.equipment_type_id">
                        <option :value="0" selected>Выберите тип оборудования</option>
                        <option v-for="type in equipmentsType" v-show="type.id !== oneEquipment.equipment_type_id" :value="type.id">{{type.name}}</option>
                    </select>
                </div>
                <div>
                    <label>Серийные номера: </label>
                    <textarea :class="{'is-invalid': errorList.serial_number}" class="form-control mt-1" v-model="newEquipment.serial_number"></textarea>
                    <p class="invalid-feedback">{{errorList.serial_number}}</p>
                </div>
                <div>
                    <label>Комментарий: </label>
                    <textarea :class="{'is-invalid': errorList.description}" class="form-control mt-1" v-model="newEquipment.description"></textarea>
                    <p class="invalid-feedback">{{errorList.description}}</p>
                </div>
                <button style="width: 300px" type="button" class="btn btn-primary" @click="createEquipment()">Создать</button>
            </div>
        </form>
    </div>
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    name: "equipment",
    data() {
        return {
            equipments: [],
            page: 1,
            maxPage: 1,
            newEquipment: {
                equipment_type_id: null,
                serial_number: null,
                description: null,
            },
            oneEquipment: {
                equipment_type: {
                    name: null,
                }
            },
            equipmentsType: [],
            searchText: '',
            checkedEquipment: null,
            isChecked: false,
            errorList: {
                serial_number: null,
                description: null,
            },
            createEquipmentForm: false,
        };
    },
    methods: {
        getEquipments(){
            let url = '/api/equipment?page=' + this.page;
            axios.get(url).then((response) => {
                this.equipments = response.data[0];
                this.maxPage = response.data[1];
            });
        },
        prevPage(){
            this.page--;
            this.getEquipments()
        },
        nextPage(){
            this.page++;
            this.getEquipments()
        },
        search(){
            if(this.searchText !== ''){
                let url = '/api/equipment?q=' + this.searchText;
                axios.get(url).then((response) => {
                    this.equipments = response.data[0];
                });
            }else this.getEquipments();
        },
        check(deleteId){
            this.createEquipmentForm = false;
            this.errorList = {
                serial_number: null,
                    description: null,
            };
            if(deleteId === this.checkedEquipment){
                this.isChecked = false;
                this.checkedEquipment = null;
                this.oneEquipment = {
                    equipment_type: {
                        name: null
                    },
                };
            }else {
                this.checkedEquipment = deleteId;
                this.isChecked = true;
                this.oneEquipment = {
                    equipment_type: {
                        name: null
                    },
                };
            }

        },
        getTypes(){
            let url_types = '/api/equipment-type';
            axios.get(url_types).then((response) => {
                this.equipmentsType = response.data;
            });
        },
        createForm(){
            this.createEquipmentForm = true;
            if(this.equipmentsType.length === 0){
                this.getTypes()
            }
        },
        createEquipment(){
            let url = '/api/equipment';
            axios.post(url, this.newEquipment).then((response) => {
                console.log(response.data);
                this.getEquipments();
                this.newEquipment = {
                    equipment_type_id: null,
                        serial_number: null,
                        description: null,
                };
            });
        },
        deleteEquipment(){
            let url = '/api/equipment/' + this.checkedEquipment;
            axios.delete(url).then(() => {
                this.getEquipments();
                this.isChecked = false;
            });
        },
        async getOneEquipment(){
            let url = '/api/equipment/' + this.checkedEquipment;
            await axios.get(url).then((response) => {
                this.oneEquipment = response.data;
            });
            if(this.equipmentsType.length === 0){
                this.getTypes()
            }
        },
        editEquipment(){
            let url = '/api/equipment/' + this.checkedEquipment;
            axios.put(url, this.oneEquipment).then(() => {
                this.getEquipments();
                this.oneEquipment = {
                    equipment_type: {
                        name: null
                    },
                };
                this.errorList = {
                    serial_number: null,
                        description: null,
                };
                this.checkedEquipment = null;
                this.isChecked = false;
            }).catch((error) => {
                if(error.response.data.errors.serial_number) this.errorList.serial_number = error.response.data.errors.serial_number[0];
                if(error.response.data.errors.description) this.errorList.description = error.response.data.errors.description[0];
            });
        },
    },
    mounted() {
        this.getEquipments();
    }
});
</script>

<style scoped>
</style>
