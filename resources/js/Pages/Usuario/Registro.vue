<template>
    <div class="flex items-center justify-center">
        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md">
            <b-alert show variant="danger" v-if="Errores.length > 0">
                <ul>
                    <li v-for="(value, key) in Errores" :key="key">
                        {{value}}
                    </li>
                </ul>
            </b-alert>
            <form @submit.prevent="registrarUsuario">
                <div>
                    <label class="block font-medium text-sm text-gray-700" for="name">
                        Nombre
                    </label>
                    <input class="form-input rounded-md shadow-sm block mt-1 w-full" v-model="Usuario.name" id="name" type="text" name="name" required="required" autofocus="autofocus" autocomplete="name">
                </div>
                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700" for="email">
                        Correo electrónico
                    </label>
                    <input class="form-input rounded-md shadow-sm block mt-1 w-full" v-model="Usuario.email" id="email" type="email" name="email" required="required">
                </div>
                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700" for="password">
                        Contraseña
                    </label>
                    <input class="form-input rounded-md shadow-sm block mt-1 w-full" v-model="Usuario.password" id="password" type="password" name="password" required="required" autocomplete="new-password">
                </div>
                <div class="mt-4">
                    <label class="block font-medium text-sm text-gray-700" for="password_confirmation">
                        Empresa
                    </label>
                    <select class="custom-select" v-model="Usuario.Empresa">
                        <option :value="value.id" v-for="(value, key) in listaEmpresas" :key="key">
                            {{value.Nombre}}
                        </option>
                    </select>
                </div>
                <div class="flex items-center justify-end mt-4">
                    <a class="underline text-sm text-gray-600 hover:text-gray-900" href="/login">
                        ¿Ya estás registrado?
                    </a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:shadow-outline-gray disabled:opacity-25 transition ease-in-out duration-150 ml-4">
                        Registrarse
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            Usuario: {
                name: null,
                email: null,
                password: null,
                Empresa: '1'
            },
            Errores: [],
            listaEmpresas: []
        }
    },
    created() {
        this.consultarEmpresas();
    },
    methods: {
        registrarUsuario(){
            axios.post('/registrarUsuario', {Usuario: this.Usuario}).then((res) => {
                if (res.data.Response) {
                    window.location.href = '/login';
                    
                }else{
                    this.Errores = res.data.Mensaje;
                }
            })
        },
        consultarEmpresas(){
            axios.get('/consultarEmpresas').then((res) => {
                this.listaEmpresas = res.data.Empresas;
            });
        },
    },
}
</script>