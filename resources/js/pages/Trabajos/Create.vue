<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type TrabajoFormData, type Cliente, type Servicio } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    ArrowLeft, 
    Save, 
    Calendar, 
    Clock, 
    DollarSign, 
    User,
    Briefcase,
    FileText,
    AlertCircle,
    CheckCircle,
    XCircle,
    TrendingUp
} from 'lucide-vue-next';
import { ref, computed } from 'vue';

interface Props {
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
        title: 'Nuevo Trabajo',
        href: '/trabajos/create',
    },
];

const form = useForm({
    titulo: '',
    descripcion: '',
    fecha_inicio: '',
    fecha_entrega: '',
    estado: 'pendiente',
    prioridad: 'media',
    precio_total: 0,
    adelanto: 0,
    observaciones: '',
    idCliente: 0,
    idServicio: 0,
});

const isSubmitting = ref(false);

const submit = () => {
    isSubmitting.value = true;
    form.post('/trabajos', {
        onSuccess: () => {
            isSubmitting.value = false;
        },
        onError: () => {
            isSubmitting.value = false;
        },
    });
};

const resetForm = () => {
    form.reset();
};

// Computed properties
const selectedCliente = computed(() => {
    return props.clientes.find(c => c.idCliente === form.idCliente);
});

const selectedServicio = computed(() => {
    return props.servicios.find(s => s.idServicio === form.idServicio);
});

const saldoPendiente = computed(() => {
    return form.precio_total - form.adelanto;
});

// Auto-fill precio when servicio is selected
const onServicioChange = () => {
    if (selectedServicio.value) {
        form.precio_total = selectedServicio.value.precio;
    }
};

// Get priority badge class
const getPriorityClass = (prioridad: string) => {
    const classes = {
        'baja': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'media': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'alta': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
        'urgente': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    };
    return classes[prioridad as keyof typeof classes] || classes.media;
};

// Get status badge class
const getStatusClass = (estado: string) => {
    const classes = {
        'pendiente': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'en_proceso': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'completado': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'cancelado': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    };
    return classes[estado as keyof typeof classes] || classes.pendiente;
};

// Format currency
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB',
    }).format(amount);
};
</script>

<template>
    <Head title="Nuevo Trabajo" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">
                        Nuevo Trabajo
                    </h1>
                    <p class="text-muted-foreground">
                        Crea un nuevo trabajo para el estudio
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        href="/trabajos"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Volver
                    </Link>
                </div>
            </div>

            <!-- Form -->
            <div class="mx-auto w-full max-w-4xl">
                <div class="card-neon">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Basic Information -->
                            <div class="grid gap-6 md:grid-cols-2">
                                <!-- Título -->
                                <div class="space-y-2">
                                    <label for="titulo" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        Título del Trabajo *
                                    </label>
                                    <div class="relative">
                                        <Briefcase class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <input
                                            id="titulo"
                                            v-model="form.titulo"
                                            type="text"
                                            :class="[
                                                'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                                form.errors.titulo ? 'border-red-500 focus-visible:ring-red-500' : ''
                                            ]"
                                            placeholder="Ej: Sesión de fotos para boda"
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
                                        <select
                                            id="estado"
                                            v-model="form.estado"
                                            :class="[
                                                'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
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

                            <!-- Prioridad y Precios -->
                            <div class="grid gap-6 md:grid-cols-3">
                                <!-- Prioridad -->
                                <div class="space-y-2">
                                    <label for="prioridad" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        Prioridad *
                                    </label>
                                    <div class="relative">
                                        <select
                                            id="prioridad"
                                            v-model="form.prioridad"
                                            :class="[
                                                'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
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

                                <!-- Precio Total -->
                                <div class="space-y-2">
                                    <label for="precio_total" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        Precio Total (BOB) *
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
                                        Adelanto (BOB)
                                    </label>
                                    <div class="relative">
                                        <DollarSign class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <input
                                            id="adelanto"
                                            v-model="form.adelanto"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            :max="form.precio_total"
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
                            </div>

                            <!-- Saldo Pendiente -->
                            <div v-if="form.precio_total > 0" class="rounded-lg border bg-muted/50 p-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium">Saldo Pendiente:</span>
                                    <span class="text-lg font-bold text-foreground">{{ formatCurrency(saldoPendiente) }}</span>
                                </div>
                            </div>

                            <!-- Descripción -->
                            <div class="space-y-2">
                                <label for="descripcion" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Descripción *
                                </label>
                                <div class="relative">
                                    <FileText class="absolute left-3 top-3 h-4 w-4 text-muted-foreground" />
                                    <textarea
                                        id="descripcion"
                                        v-model="form.descripcion"
                                        rows="4"
                                        :class="[
                                            'input-neon flex w-full rounded-md px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.descripcion ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="Describe detalladamente el trabajo a realizar..."
                                        required
                                    ></textarea>
                                </div>
                                <p v-if="form.errors.descripcion" class="text-sm text-red-600">
                                    {{ form.errors.descripcion }}
                                </p>
                            </div>

                            <!-- Observaciones -->
                            <div class="space-y-2">
                                <label for="observaciones" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Observaciones
                                </label>
                                <div class="relative">
                                    <FileText class="absolute left-3 top-3 h-4 w-4 text-muted-foreground" />
                                    <textarea
                                        id="observaciones"
                                        v-model="form.observaciones"
                                        rows="3"
                                        :class="[
                                            'input-neon flex w-full rounded-md px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.observaciones ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="Observaciones adicionales..."
                                    ></textarea>
                                </div>
                                <p v-if="form.errors.observaciones" class="text-sm text-red-600">
                                    {{ form.errors.observaciones }}
                                </p>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end gap-4 pt-6">
                                <button
                                    type="button"
                                    @click="resetForm"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
                                >
                                    Limpiar
                                </button>
                                <button
                                    type="submit"
                                    :disabled="isSubmitting"
                                    class="btn-neon-primary inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2"
                                >
                                    <Save v-if="!isSubmitting" class="mr-2 h-4 w-4" />
                                    <div v-else class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"></div>
                                    {{ isSubmitting ? 'Guardando...' : 'Guardar Trabajo' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 