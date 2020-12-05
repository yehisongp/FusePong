<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Historias de usuario > {{$page.nomProyecto}}
            </h2>
        </template>

        <template #Container>
            <b-button size="sm" variant="primary" @click="nuevaHistoria">Crear historia</b-button>
            <b-card no-body class="mt-3">
                <b-tabs pills card vertical nav-wrapper-class="w-50">
                <b-tab :title="value.Titulo" @click="consultarTickets(value.id)" v-for="(value, key) in listaHistorias" :key="key">
                    <b-overlay :show="showOverlay" rounded="sm">
                    <b-button variant="info" class="mb-3" size="sm" @click="crearTicket(value.id)">Crear ticket</b-button>
                    
                    <b-list-group>
                        <b-list-group-item href="#" class="flex-column" v-for="(value_Ticket, key_Ticket) in listaTickets" :key="key_Ticket">
                            <div class="d-flex w-100 justify-content-between">
                                <small><b-badge variant="primary">{{value_Ticket.FechaOrdenada}}</b-badge></small>
                                <b-dropdown :disabled="value_Ticket.Estado == 'F'" :variant="value_Ticket.Estado == 'A' ? 'success': value_Ticket.Estado == 'E' ? 'secondary' : 'danger'" size="sm" split :text="value_Ticket.Estado == 'A' ? 'Activo': value_Ticket.Estado == 'E' ? 'En proceso' : 'Finalizado'" class="m-2">
                                    <b-dropdown-item-button @click="cambiarEstado(value_Ticket.id, 'E', value_Ticket.idHistoriaUsuario)" v-if="value_Ticket.Estado == 'A'">En proceso</b-dropdown-item-button>
                                    <b-dropdown-item-button @click="cambiarEstado(value_Ticket.id, 'F', value_Ticket.idHistoriaUsuario)" v-if="value_Ticket.Estado == 'A' || value_Ticket.Estado == 'E'">Finalizado</b-dropdown-item-button>
                                    <b-dropdown-divider v-if="value_Ticket.Estado == 'A' || value_Ticket.Estado == 'E'"></b-dropdown-divider>
                                    <b-dropdown-item-button @click="editarTicket(value_Ticket)" v-if="value_Ticket.Estado == 'A' || value_Ticket.Estado == 'E'">Editar</b-dropdown-item-button>
                                    <b-dropdown-item-button @click="cambiarEstado(value_Ticket.id, 'C', value_Ticket.idHistoriaUsuario)" v-if="value_Ticket.Estado == 'A'">Cancelar</b-dropdown-item-button>
                                </b-dropdown>
                            </div>

                            <p class="mb-1">
                                {{value_Ticket.Comentarios}}
                            </p>
                            <hr>
                            <small><b-avatar size="sm"></b-avatar> {{value_Ticket.name}}</small>
                        </b-list-group-item>
                        
                    </b-list-group>
                    </b-overlay>
                </b-tab>
                </b-tabs>
            </b-card>
            <b-modal 
                v-model="modalShow"
                :title="TituloModal"
                size="sm"
            >
                <template>
                    <b-form @submit.prevent="guardarHistoria">
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
                    <b-button size="sm" variant="primary" @click="guardarHistoria">Guardar</b-button>
                    <b-button size="sm" variant="danger" @click="modalShow = !modalShow">Cerrar</b-button>
                </template>
            </b-modal>
            <b-modal 
                v-model="modalShowTicket"
                :title="TituloTicket"
                size="md"
            >
                <template>
                    <b-form @submit.prevent="guardarTicket">
                        <b-form-group
                            class="mb-3"
                            label="Comentarios"
                            label-for="textarea-Comentario"
                            >
                            <b-form-textarea
                                id="textarea-Comentario"
                                rows="3"
                                max-rows="8"
                                v-model="FormularioTicket.Comentarios"
                            ></b-form-textarea>
                        </b-form-group>
                    </b-form>
                </template>
                <template #modal-footer>
                    <b-button size="sm" variant="primary" @click="guardarTicket">Guardar</b-button>
                    <b-button size="sm" variant="danger" @click="modalShowTicket = !modalShowTicket">Cerrar</b-button>
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
                showOverlay: true,
                modalShow: false,
                Accion: 'Guardar',
                TituloModal: 'Crear historia de usuario',
                TituloTicket: 'Crear ticket',
                Formulario: {
                    id: null,
                    Titulo: null,
                    Proyecto: this.$page.Proyecto,
                },
                listaHistorias: [],
                modalShowTicket: false,
                FormularioTicket: {
                    id: null,
                    Comentarios: null,
                    idHistoria: null,
                },
                listaTickets: [],
            }
        },
        created() {
            this.consultarHistorias();
        },
        watch: {
            listaHistorias: function(val){
                if (val.length > 0) {
                    
                    this.consultarTickets(this.listaHistorias[0].id);
                }
            }
        },
        methods: {
            nuevaHistoria(){
                this.modalShow = true;
                this.Accion = 'Guardar';
                this.TituloModal = 'Crear historia de usuario';
                this.resetForm();
            },
            guardarHistoria(){
                this.modalShow = false;
                axios.post('/guardarHistoria', {Accion: this.Accion, Historia: this.Formulario}).then((res) => {
                    if (res.data.Response) {
                        this.$bvToast.toast(res.data.Mensaje, {
                            title: 'Almacenamiento',
                            variant: 'success',
                            solid: true
                        });
                        this.consultarHistorias();
                    }else{

                    }
                });
            },
            consultarHistorias(){
                axios.get('/consultarHistoriasUsuario/'+this.$page.Proyecto).then((res) => {
                    this.listaHistorias = res.data.Historias;
                });
            },
            crearTicket(Historia){
                this.modalShowTicket = true;
                this.FormularioTicket.idHistoria = Historia;
                this.Accion = 'Guardar';
                this.TituloTicket = 'Crear ticket';
            },
            guardarTicket(){
                this.modalShowTicket = false;
                axios.post('/guardarTicket', {Accion: this.Accion, Ticket: this.FormularioTicket}).then((res) => {
                    if (res.data.Response) {
                        this.$bvToast.toast(res.data.Mensaje, {
                            title: 'Almacenamiento',
                            variant: 'success',
                            solid: true
                        });
                        this.consultarTickets(this.FormularioTicket.idHistoria);
                    }else{

                    }
                });
            },
            consultarTickets(Historia){
                this.showOverlay = true;
                this.listaTickets = [];
                axios.get('/consultarTickets/'+Historia).then((res) => {
                    this.listaTickets = res.data.Tickets;
                    this.showOverlay = false;
                });
            },
            cambiarEstado(Ticket, Estado, Historia){
                axios.post('/estadoTicket', {id: Ticket, Estado: Estado}).then((res) => {
                    if (res.data.Response) {
                        this.consultarTickets(Historia);
                    }else{

                    }
                });
            },
            editarTicket(Ticket){
                this.FormularioTicket = {
                    id: Ticket.id,
                    Comentarios: Ticket.Comentarios,
                    idHistoria: Ticket.idHistoriaUsuario,
                };
                this.modalShowTicket = true;
                this.Accion = 'Editar';
                this.TituloTicket = 'Crear ticket';

            },
            resetForm(){
                this.Formulario = {
                    id: null,
                    Titulo: null
                };
            },
        },
        
    }
</script>
