<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Proyectos
            </h2>
        </template>

        <template #Container>
            <b-button size="sm" variant="primary" @click="nuevoProyecto">Crear proyecto</b-button>
            <b-list-group class="mt-3" v-for="(value, key) in listaProyectos" :key="key">
                <b-list-group-item class="flex-column align-items-start">
                    <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{value.Titulo}}</h5>
                    <small>{{value.Fecha}}</small>
                    </div>

                    <b-button size="sm" variant="primary" @click="editarProyecto(value)">Editar</b-button>
                    <jet-nav-link class="btn btn-secondary btn-sm" :href="route('HistoriaUsuario', [{id: value.id}])">
                        Historias de usuario
                    </jet-nav-link>
                </b-list-group-item>
            </b-list-group>
            <b-modal 
                v-model="modalShow"
                :title="TituloModal"
                size="sm"
            >
                <template>
                    <b-form @submit.prevent="guardarProyecto">
                        <b-form-group
                            class="mb-3"
                            label="Título"
                            label-for="input-Titulo"
                            >
                            <b-form-input
                                id="input-Titulo"
                                v-model="Formulario.Titulo"
                                placeholder="Titulo"
                            ></b-form-input>
                        </b-form-group>
                    </b-form>
                </template>
                <template #modal-footer>
                    <b-button size="sm" variant="primary" @click="guardarProyecto">Guardar</b-button>
                    <b-button size="sm" variant="danger" @click="modalShow = !modalShow">Cerrar</b-button>
                </template>
            </b-modal>
        </template>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import JetNavLink from '@/Jetstream/NavLink'
    export default {
        components: {
            AppLayout,
            JetNavLink,
        },
        data() {
            return {
                listaProyectos: [],
                modalShow: false,
                Accion: 'Guardar',
                TituloModal: 'Crear proyecto',
                Formulario: {
                    id: null,
                    Titulo: null,
                },
            }
        },
        created() {
            this.consultarProyectos();
        },
        methods: {
            nuevoProyecto(){
                this.modalShow = true;
                this.TituloModal = 'Nuevo proyecto';
                this.Accion = 'Guardar';
                this.resetFormulario();
            },
            guardarProyecto(){
                this.modalShow = false;
                axios.post('/guardarProyecto', {Proyecto: this.Formulario, Accion: this.Accion}).then((res) => {
                    if (res.data.Response) {
                        this.$bvToast.toast(res.data.Mensaje, {
                            title: 'Almacenamiento',
                            variant: 'success',
                            solid: true
                        });
                        this.consultarProyectos();
                    }else{
                        this.modalShow = true;
                    }
                });
            },
            consultarProyectos(){
                axios.get('/consultarProyectos').then((res) => {
                    this.listaProyectos = res.data.Proyectos;
                });
            },
            editarProyecto(Proyecto){
                this.Formulario = {
                    id: Proyecto.id,
                    Titulo: Proyecto.Titulo,
                };
                this.Accion = 'Editar';
                this.modalShow = true;
                this.TituloModal = 'Editar proyecto';
            },
            resetFormulario(){
                this.Formulario = {
                    id: null,
                    Titulo: null,
                };
            }
        },
        
    }
</script>
