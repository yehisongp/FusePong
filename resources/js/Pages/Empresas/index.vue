<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Empresas
            </h2>
        </template>

        <template #Container>
            <b-button size="sm" variant="primary" @click="nuevaEmpresa">Crear empresa</b-button>
            <b-table class="mt-3" striped hover :items="listaEmpresas" :fields="Fields">
                <template #cell(Acciones)="data">
                    <b-button size="sm" variant="info" @click="editarEmpresa(data.item)">Editar</b-button>
                </template>
            </b-table>
            <b-modal 
                v-model="modalShow"
                :title="TituloModal"
            >
                <template>
                    <b-form @submit.prevent="guardarEmpresa">
                        <b-form-group
                            class="mb-3"
                            label="NIT"
                            label-for="input-NIT"
                            >
                            <b-form-input
                                id="input-NIT"
                                v-model="Formulario.NIT"
                                placeholder="NIT"
                            ></b-form-input>
                        </b-form-group>
                        <b-form-group
                            class="mb-3"
                            label="Nombre"
                            label-for="input-Nombre"
                            >
                            <b-form-input
                                id="input-Nombre"
                                v-model="Formulario.Nombre"
                                placeholder="Nombre"
                            ></b-form-input>
                        </b-form-group>
                        <b-form-group
                            class="mb-3"
                            label="Telefono"
                            label-for="input-Telefono"
                            >
                            <b-form-input
                                id="input-Telefono"
                                v-model="Formulario.Telefono"
                                placeholder="Telefono"
                            ></b-form-input>
                        </b-form-group>
                        <b-form-group
                            class="mb-3"
                            label="Direccion"
                            label-for="input-Direccion"
                            >
                            <b-form-input
                                id="input-Direccion"
                                v-model="Formulario.Direccion"
                                placeholder="Direccion"
                            ></b-form-input>
                        </b-form-group>
                        <b-form-group
                            class="mb-3"
                            label="Email"
                            label-for="input-Email"
                            >
                            <b-form-input
                                id="input-Email"
                                v-model="Formulario.Email"
                                placeholder="Email"
                            ></b-form-input>
                        </b-form-group>
                    </b-form>
                </template>
                <template #modal-footer>
                    <b-button size="sm" variant="primary" @click="guardarEmpresa">Guardar</b-button>
                    <b-button size="sm" variant="danger" @click="modalShow = !modalShow">Cerrar</b-button>
                </template>
            </b-modal>
        </template>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'

    export default {
        components: {
            AppLayout,
        },
        data() {
            return {
                modalShow: false,
                Accion: 'Guardar',
                TituloModal: 'Crear empresa',
                Fields: [
                    { key: 'Acciones', label: ''},
                    { key: 'Nit', label: 'NIT'},
                    { key: 'Nombre', label: 'Nombre empresa'},
                    { key: 'Telefono', label: 'Teléfono'},
                    { key: 'Direccion', label: 'Dirección'},
                    { key: 'Email', label: 'E-Mail'}
                ],
                listaEmpresas: [],
                Formulario: {
                    id: null,
                    NIT: null,
                    Nombre: null,
                    Telefono: null,
                    Direccion: null,
                    Email: null,
                },
            }
        },
        created() {
            this.consultarEmpresas();
        },
        methods: {
            nuevaEmpresa(){
                this.resetForm();
                this.Accion = 'Guardar';
                this.modalShow = true;
            },
            guardarEmpresa(){
                this.modalShow = false;
                axios.post('/guardarEmpresa', {Empresa: this.Formulario, Accion: this.Accion}).then((res) => {
                    if (res.data.Response) {
                        this.$bvToast.toast(res.data.Mensaje, {
                            title: 'Almacenamiento',
                            variant: 'success',
                            solid: true
                        });
                        this.consultarEmpresas();

                    }else{
                        res.data.Mensaje.forEach(element => {
                            this.$bvToast.toast(element, {
                                title: 'Advertencia',
                                variant: 'warning',
                                solid: true
                            });
                        });
                        
                        this.modalShow = true;
                    }
                });
            },
            consultarEmpresas(){
                axios.get('/consultarEmpresas').then((res) => {
                    this.listaEmpresas = res.data.Empresas;
                    console.log(res.data.Empresas)
                });
            },
            editarEmpresa(Empresa){
                this.Formulario = {
                    id: Empresa.id,
                    NIT: Empresa.Nit,
                    Nombre: Empresa.Nombre,
                    Telefono: Empresa.Telefono,
                    Direccion: Empresa.Direccion,
                    Email: Empresa.Email,
                };
                this.Accion = 'Editar';
                this.TituloModal = 'Editar empresa';
                this.modalShow = true;

            },
            resetForm(){
                this.Formulario = {
                    id: null,
                    NIT: null,
                    Nombre: null,
                    Telefono: null,
                    Direccion: null,
                    Email: null,
                };
            },
        },
    }
</script>
