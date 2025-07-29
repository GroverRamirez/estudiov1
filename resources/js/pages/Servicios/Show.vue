<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Servicio } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { 
    ArrowLeft, 
    Edit, 
    Trash2, 
    Camera, 
    DollarSign, 
    Tag, 
    Clock,
    Calendar,
    Image,
    Briefcase,
    Plus,
    Eye
} from 'lucide-vue-next';

interface Props {
    servicio: Servicio;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Servicios',
        href: '/servicios',
    },
    {
        title: props.servicio.nombre,
        href: `/servicios/${props.servicio.idServicio}`,
    },
];

// Format currency
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB',
    }).format(amount);
};

// Format duration
const formatDuration = (minutes: number | undefined) => {
    if (!minutes) return 'No especificada';
    if (minutes < 60) {
        return `${minutes} minutos`;
    }
    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;
    return remainingMinutes > 0 ? `${hours} hora(s) ${remainingMinutes} minuto(s)` : `${hours} hora(s)`;
};

// Format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-BO', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

// Get status badge class
const getStatusClass = (estado: string) => {
    return estado === 'activo' 
        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
};

// Get category badge class
const getCategoryClass = (categoria: string) => {
    const classes = {
        'fotografia': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'video': 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
        'edicion': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
        'impresion': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'eventos': 'bg-pink-100 text-pink-800 dark:bg-pink-900 dark:text-pink-300',
        'otros': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300',
    };
    return classes[categoria as keyof typeof classes] || classes.otros;
};

// Delete servicio
const deleteServicio = (servicioId: number) => {
    if (confirm('¿Estás seguro de que quieres eliminar este servicio?')) {
        // Implementar eliminación
    }
};
</script>

<template>
    <Head :title="`Servicio: ${servicio.nombre}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">
                        {{ servicio.nombre }}
                    </h1>
                    <p class="text-muted-foreground">
                        Detalles del servicio
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        href="/servicios"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Volver
                    </Link>
                    <Link
                        :href="`/servicios/${servicio.idServicio}/edit`"
                        class="btn-neon-primary inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2"
                    >
                        <Edit class="mr-2 h-4 w-4" />
                        Editar
                    </Link>
                </div>
            </div>

            <div class="grid gap-6 lg:grid-cols-3">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Service Image -->
                    <div class="card-neon">
                        <div class="p-6">
                            <div class="aspect-video overflow-hidden rounded-lg bg-muted">
                                <div v-if="servicio.imagen" class="h-full w-full bg-cover bg-center" :style="{ backgroundImage: `url(${servicio.imagen})` }"></div>
                                <div v-else class="flex h-full w-full items-center justify-center">
                                    <Camera class="h-16 w-16 text-muted-foreground" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Details -->
                    <div class="card-neon">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-4">Descripción</h2>
                            <p class="text-muted-foreground leading-relaxed">
                                {{ servicio.descripcion }}
                            </p>
                        </div>
                    </div>

                    <!-- Service Information -->
                    <div class="card-neon">
                        <div class="p-6">
                            <h2 class="text-xl font-semibold mb-4">Información del Servicio</h2>
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-muted-foreground">Categoría</label>
                                    <div class="flex items-center gap-2">
                                        <Tag class="h-4 w-4 text-muted-foreground" />
                                        <span
                                            :class="getCategoryClass(servicio.categoria)"
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize"
                                        >
                                            {{ servicio.categoria }}
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-muted-foreground">Estado</label>
                                    <div class="flex items-center gap-2">
                                        <span
                                            :class="getStatusClass(servicio.estado)"
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium capitalize"
                                        >
                                            {{ servicio.estado }}
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-muted-foreground">Precio</label>
                                    <div class="flex items-center gap-2">
                                        <DollarSign class="h-4 w-4 text-muted-foreground" />
                                        <span class="text-lg font-semibold text-foreground">
                                            {{ formatCurrency(servicio.precio) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <label class="text-sm font-medium text-muted-foreground">Duración Estimada</label>
                                    <div class="flex items-center gap-2">
                                        <Clock class="h-4 w-4 text-muted-foreground" />
                                        <span class="text-foreground">
                                            {{ formatDuration(servicio.duracion_estimada) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="card-neon">
                        <div class="p-6">
                            <h3 class="font-semibold mb-4">Acciones Rápidas</h3>
                            <div class="space-y-3">
                                <Link
                                    :href="`/trabajos/create?servicio_id=${servicio.idServicio}`"
                                    class="flex items-center gap-3 rounded-lg border p-3 hover:bg-accent/50 transition-colors"
                                >
                                    <Briefcase class="h-5 w-5 text-blue-600" />
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">Crear Trabajo</p>
                                        <p class="text-xs text-muted-foreground">Usar este servicio</p>
                                    </div>
                                </Link>

                                <Link
                                    :href="`/servicios/${servicio.idServicio}/edit`"
                                    class="flex items-center gap-3 rounded-lg border p-3 hover:bg-accent/50 transition-colors"
                                >
                                    <Edit class="h-5 w-5 text-green-600" />
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">Editar Servicio</p>
                                        <p class="text-xs text-muted-foreground">Modificar información</p>
                                    </div>
                                </Link>

                                <button
                                    @click="deleteServicio(servicio.idServicio)"
                                    class="flex w-full items-center gap-3 rounded-lg border p-3 hover:bg-accent/50 transition-colors text-red-600 hover:text-red-700"
                                >
                                    <Trash2 class="h-5 w-5" />
                                    <div class="flex-1 text-left">
                                        <p class="text-sm font-medium">Eliminar Servicio</p>
                                        <p class="text-xs text-muted-foreground">Eliminar permanentemente</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Service Stats -->
                    <div class="card-neon">
                        <div class="p-6">
                            <h3 class="font-semibold mb-4">Estadísticas</h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-muted-foreground">Trabajos Realizados</span>
                                    <span class="font-semibold">0</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-muted-foreground">Ingresos Generados</span>
                                    <span class="font-semibold">{{ formatCurrency(0) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-muted-foreground">Último Uso</span>
                                    <span class="text-sm text-muted-foreground">Nunca</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Service Meta -->
                    <div class="card-neon">
                        <div class="p-6">
                            <h3 class="font-semibold mb-4">Información del Sistema</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-muted-foreground">ID del Servicio</span>
                                    <span class="font-mono">{{ servicio.idServicio }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-muted-foreground">Creado</span>
                                    <span>{{ formatDate(servicio.created_at) }}</span>
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-muted-foreground">Actualizado</span>
                                    <span>{{ formatDate(servicio.updated_at) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 