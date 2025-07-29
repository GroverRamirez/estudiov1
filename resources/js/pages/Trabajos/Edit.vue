<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type TrabajoFormData, type Cliente, type Servicio, type Trabajo } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    Calendar, 
    Clock, 
    DollarSign, 
    FileText, 
    User, 
    Briefcase, 
    AlertTriangle,
    ArrowLeft,
    Save
} from 'lucide-vue-next';

interface Props {
    trabajo: Trabajo & {
        cliente: Cliente;
        servicio: Servicio;
    };
    clientes: Cliente[];
    servicios: Servicio[];
    estados: Record<string, string>;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Trabajos',
        href: '/trabajos',
    },
    {
        title: 'Editar Trabajo',
        href: '#',
    },
];

const form = useForm({
    titulo: props.trabajo.titulo,
    descripcion: props.trabajo.descripcion,
    fecha_inicio: props.trabajo.fecha_inicio,
    fecha_entrega: props.trabajo.fecha_entrega,
    estado: props.trabajo.estado,
    prioridad: props.trabajo.prioridad,
    precio_total: props.trabajo.precio_total,
    adelanto: props.trabajo.adelanto,
    observaciones: props.trabajo.observaciones,
    idCliente: props.trabajo.idCliente,
    idServicio: props.trabajo.idServicio,
});

// Formatear moneda
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB',
        minimumFractionDigits: 2
    }).format(amount);
};

// Calcular días entre fechas
const calcularDias = (fechaInicio: string, fechaEntrega: string) => {
    if (!fechaInicio || !fechaEntrega) return 0;
    const inicio = new Date(fechaInicio);
    const entrega = new Date(fechaEntrega);
    const diffTime = Math.abs(entrega.getTime() - inicio.getTime());
    return Math.ceil(diffTime / (1000 * 60 * 60 * 24));
};

// Calcular progreso basado en fechas
const calcularProgreso = (fechaInicio: string, fechaEntrega: string) => {
    if (!fechaInicio || !fechaEntrega) return 0;
    const inicio = new Date(fechaInicio);
    const entrega = new Date(fechaEntrega);
    const hoy = new Date();
    
    const totalDias = Math.ceil((entrega.getTime() - inicio.getTime()) / (1000 * 60 * 60 * 24));
    const diasTranscurridos = Math.ceil((hoy.getTime() - inicio.getTime()) / (1000 * 60 * 60 * 24));
    
    if (diasTranscurridos <= 0) return 0;
    if (diasTranscurridos >= totalDias) return 100;
    
    return Math.round((diasTranscurridos / totalDias) * 100);
};

// Manejar cambio de servicio
const onServicioChange = () => {
    if (form.idServicio) {
        const servicio = props.servicios.find(s => s.idServicio === form.idServicio);
        if (servicio) {
            form.precio_total = servicio.precio;
            form.adelanto = servicio.precio * 0.5; // 50% de adelanto por defecto
        }
    }
};

// Manejar cambio de fechas
const onFechaChange = () => {
    if (form.fecha_inicio && form.fecha_entrega) {
        const dias = calcularDias(form.fecha_inicio, form.fecha_entrega);
        if (dias < 0) {
            form.fecha_entrega = form.fecha_inicio;
        }
    }
};

const submit = () => {
    form.put(route('trabajos.update', props.trabajo.idTrabajo));
};
</script>

<template>
    <Head title="Editar Trabajo" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-purple-500 to-pink-500">
                        Editar Trabajo
                    </h1>
                    <p class="text-gray-400 mt-2">
                        Actualiza la información del trabajo seleccionado
                    </p>
                </div>
                <Link
                    :href="route('trabajos.index')"
                    class="inline-flex items-center justify-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200"
                >
                    <ArrowLeft class="h-4 w-4 mr-2" />
                    Volver
                </Link>
            </div>

            <!-- Formulario -->
            <div class="bg-gray-900/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50">
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Información básica -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Información Básica</h3>
                        
                        <!-- Título y Estado -->
                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- Título -->
                            <div class="space-y-2">
                                <label for="titulo" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Título del Trabajo *
                                </label>
                                <div class="relative">
                                    <FileText class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        id="titulo"
                                        v-model="form.titulo"
                                        type="text"
                                        :class="[
                                            'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.titulo ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="Ej: Sesión de Fotos para Boda"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.titulo" class="text-sm text-red-600">
                                    {{ form.errors.titulo }}
                                </p>
                            </div>

                            <!-- Estado -->
                            <div class="space-y-2">
                                <label for="estado" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Estado *
                                </label>
                                <div class="relative">
                                    <AlertTriangle class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <select
                                        id="estado"
                                        v-model="form.estado"
                                        :class="[
                                            'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.estado ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        required
                                    >
                                        <option v-for="(nombre, key) in estados" :key="key" :value="key">
                                            {{ nombre }}
                                        </option>
                                    </select>
                                </div>
                                <p v-if="form.errors.estado" class="text-sm text-red-600">
                                    {{ form.errors.estado }}
                                </p>
                            </div>
                        </div>

                        <!-- Cliente y Servicio -->
                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- Cliente -->
                            <div class="space-y-2">
                                <label for="idCliente" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Cliente *
                                </label>
                                <div class="relative">
                                    <User class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <select
                                        id="idCliente"
                                        v-model="form.idCliente"
                                        :class="[
                                            'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.idCliente ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        required
                                    >
                                        <option value="0">Seleccionar cliente</option>
                                        <option v-for="cliente in clientes" :key="cliente.idCliente" :value="cliente.idCliente">
                                            {{ cliente.nombre }} - {{ cliente.email }}
                                        </option>
                                    </select>
                                </div>
                                <p v-if="form.errors.idCliente" class="text-sm text-red-600">
                                    {{ form.errors.idCliente }}
                                </p>
                            </div>

                            <!-- Servicio -->
                            <div class="space-y-2">
                                <label for="idServicio" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Servicio *
                                </label>
                                <div class="relative">
                                    <Briefcase class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <select
                                        id="idServicio"
                                        v-model="form.idServicio"
                                        @change="onServicioChange"
                                        :class="[
                                            'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.idServicio ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        required
                                    >
                                        <option value="0">Seleccionar servicio</option>
                                        <option v-for="servicio in servicios" :key="servicio.idServicio" :value="servicio.idServicio">
                                            {{ servicio.nombre }} - {{ formatCurrency(servicio.precio) }}
                                        </option>
                                    </select>
                                </div>
                                <p v-if="form.errors.idServicio" class="text-sm text-red-600">
                                    {{ form.errors.idServicio }}
                                </p>
                            </div>
                        </div>

                        <!-- Fechas -->
                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- Fecha de Inicio -->
                            <div class="space-y-2">
                                <label for="fecha_inicio" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Fecha de Inicio *
                                </label>
                                <div class="relative">
                                    <Calendar class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        id="fecha_inicio"
                                        v-model="form.fecha_inicio"
                                        type="date"
                                        @change="onFechaChange"
                                        :class="[
                                            'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.fecha_inicio ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.fecha_inicio" class="text-sm text-red-600">
                                    {{ form.errors.fecha_inicio }}
                                </p>
                            </div>

                            <!-- Fecha de Entrega -->
                            <div class="space-y-2">
                                <label for="fecha_entrega" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Fecha de Entrega *
                                </label>
                                <div class="relative">
                                    <Calendar class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        id="fecha_entrega"
                                        v-model="form.fecha_entrega"
                                        type="date"
                                        @change="onFechaChange"
                                        :class="[
                                            'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.fecha_entrega ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.fecha_entrega" class="text-sm text-red-600">
                                    {{ form.errors.fecha_entrega }}
                                </p>
                            </div>
                        </div>

                        <!-- Descripción -->
                        <div class="space-y-2">
                            <label for="descripcion" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                Descripción *
                            </label>
                            <textarea
                                id="descripcion"
                                v-model="form.descripcion"
                                rows="4"
                                :class="[
                                    'input-neon flex w-full rounded-md px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
                                    form.errors.descripcion ? 'border-red-500 focus-visible:ring-red-500' : ''
                                ]"
                                placeholder="Describe los detalles del trabajo..."
                                required
                            ></textarea>
                            <p v-if="form.errors.descripcion" class="text-sm text-red-600">
                                {{ form.errors.descripcion }}
                            </p>
                        </div>
                    </div>

                    <!-- Información financiera -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-white mb-4">Información Financiera</h3>
                        
                        <div class="grid gap-6 md:grid-cols-3">
                            <!-- Precio Total -->
                            <div class="space-y-2">
                                <label for="precio_total" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Precio Total *
                                </label>
                                <div class="relative">
                                    <DollarSign class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        id="precio_total"
                                        v-model="form.precio_total"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        :class="[
                                            'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.precio_total ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="0.00"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.precio_total" class="text-sm text-red-600">
                                    {{ form.errors.precio_total }}
                                </p>
                            </div>

                            <!-- Adelanto -->
                            <div class="space-y-2">
                                <label for="adelanto" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Adelanto
                                </label>
                                <div class="relative">
                                    <DollarSign class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        id="adelanto"
                                        v-model="form.adelanto"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        :class="[
                                            'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.adelanto ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="0.00"
                                    />
                                </div>
                                <p v-if="form.errors.adelanto" class="text-sm text-red-600">
                                    {{ form.errors.adelanto }}
                                </p>
                            </div>

                            <!-- Prioridad -->
                            <div class="space-y-2">
                                <label for="prioridad" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Prioridad *
                                </label>
                                <div class="relative">
                                    <AlertTriangle class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <select
                                        id="prioridad"
                                        v-model="form.prioridad"
                                        :class="[
                                            'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.prioridad ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        required
                                    >
                                        <option value="baja">Baja</option>
                                        <option value="media">Media</option>
                                        <option value="alta">Alta</option>
                                        <option value="urgente">Urgente</option>
                                    </select>
                                </div>
                                <p v-if="form.errors.prioridad" class="text-sm text-red-600">
                                    {{ form.errors.prioridad }}
                                </p>
                            </div>
                        </div>

                        <!-- Observaciones -->
                        <div class="space-y-2">
                            <label for="observaciones" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                Observaciones
                            </label>
                            <textarea
                                id="observaciones"
                                v-model="form.observaciones"
                                rows="3"
                                :class="[
                                    'input-neon flex w-full rounded-md px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
                                    form.errors.observaciones ? 'border-red-500 focus-visible:ring-red-500' : ''
                                ]"
                                placeholder="Observaciones adicionales..."
                            ></textarea>
                            <p v-if="form.errors.observaciones" class="text-sm text-red-600">
                                {{ form.errors.observaciones }}
                            </p>
                        </div>
                    </div>

                    <!-- Resumen del trabajo -->
                    <div class="bg-gray-800/50 rounded-lg p-4">
                        <h4 class="text-sm font-medium text-gray-300 mb-3">Resumen del Trabajo</h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-gray-400">Duración:</span>
                                <p class="text-white font-medium">
                                    {{ calcularDias(form.fecha_inicio, form.fecha_entrega) }} días
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-400">Progreso:</span>
                                <p class="text-white font-medium">
                                    {{ calcularProgreso(form.fecha_inicio, form.fecha_entrega) }}%
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-400">Saldo Pendiente:</span>
                                <p class="text-white font-medium">
                                    {{ formatCurrency(form.precio_total - form.adelanto) }}
                                </p>
                            </div>
                            <div>
                                <span class="text-gray-400">Estado:</span>
                                <p class="text-white font-medium capitalize">
                                    {{ form.estado }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-700">
                        <Link
                            :href="route('trabajos.index')"
                            class="inline-flex items-center justify-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200"
                        >
                            Cancelar
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center justify-center px-6 py-2 bg-gradient-to-r from-purple-600 to-cyan-600 hover:from-purple-700 hover:to-cyan-700 text-white font-medium rounded-lg transition-all duration-200 transform hover:scale-105 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <Save class="h-4 w-4 mr-2" />
                            {{ form.processing ? 'Guardando...' : 'Guardar Cambios' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template> 