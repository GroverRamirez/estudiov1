<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type TrabajosPageProps } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
    Plus, 
    Search, 
    Filter, 
    Edit, 
    Trash2, 
    Eye,
    Calendar,
    Clock,
    DollarSign,
    User,
    Briefcase,
    AlertCircle,
    CheckCircle,
    XCircle,
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight,
    Users,
    Tag,
    FileText,
    CalendarDays,
    TrendingUp
} from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import { useDebounceFn } from '@vueuse/core';

interface Props {
    trabajos: TrabajosPageProps['trabajos'];
    filters: TrabajosPageProps['filters'];
    clientes?: TrabajosPageProps['clientes'];
    servicios?: TrabajosPageProps['servicios'];
    estados?: TrabajosPageProps['estados'];
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
];

// Reactive filters
const search = ref(props.filters.search || '');
const estadoFilter = ref(props.filters.estado || '');
const prioridadFilter = ref(props.filters.prioridad || '');
const clienteFilter = ref(props.filters.cliente_id || '');
const servicioFilter = ref(props.filters.servicio_id || '');
const sortBy = ref(props.filters.sort_by || 'created_at');
const sortDirection = ref<'asc' | 'desc'>(props.filters.sort_direction || 'desc');

// Debounced search
const debouncedSearch = useDebounceFn((value: string) => {
    updateFilters({ search: value });
}, 300);

// Watch for filter changes
watch(search, (value) => {
    debouncedSearch(value);
});

watch([estadoFilter, prioridadFilter, clienteFilter, servicioFilter, sortBy, sortDirection], () => {
    updateFilters({
        estado: estadoFilter.value,
        prioridad: prioridadFilter.value,
        cliente_id: clienteFilter.value,
        servicio_id: servicioFilter.value,
        sort_by: sortBy.value,
        sort_direction: sortDirection.value,
    });
});

// Update filters and reload
const updateFilters = (newFilters: Partial<Props['filters']>) => {
    router.get('/trabajos', {
        ...props.filters,
        ...newFilters,
    }, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
};

// Pagination
const goToPage = (page: number) => {
    router.get('/trabajos', {
        ...props.filters,
        page,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Delete trabajo
const deleteTrabajo = (trabajoId: number) => {
    if (confirm('¿Estás seguro de que quieres eliminar este trabajo?')) {
        router.delete(`/trabajos/${trabajoId}`, {
            onSuccess: () => {
                // Success handled by Inertia
            },
        });
    }
};

// Computed properties
const hasFilters = computed(() => {
    return search.value || estadoFilter.value || prioridadFilter.value || clienteFilter.value || servicioFilter.value || sortBy.value !== 'created_at' || sortDirection.value !== 'desc';
});

const clearFilters = () => {
    search.value = '';
    estadoFilter.value = '';
    prioridadFilter.value = '';
    clienteFilter.value = '';
    servicioFilter.value = '';
    sortBy.value = 'created_at';
    sortDirection.value = 'desc';
    updateFilters({
        search: '',
        estado: '',
        prioridad: '',
        cliente_id: '',
        servicio_id: '',
        sort_by: 'created_at',
        sort_direction: 'desc',
    });
};

// Format currency
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB',
    }).format(amount);
};

// Format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-BO', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

// Get priority badge class
const getPriorityClass = (prioridad: string) => {
    const classes = {
        'baja': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'media': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'alta': 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300',
        'urgente': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
    };
    return classes[prioridad as keyof typeof classes] || classes.baja;
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

// Get status icon
const getStatusIcon = (estado: string) => {
    const icons = {
        'pendiente': Clock,
        'en_proceso': TrendingUp,
        'completado': CheckCircle,
        'cancelado': XCircle,
    };
    return icons[estado as keyof typeof icons] || Clock;
};

// Calculate progress
const calculateProgress = (trabajo: any) => {
    const startDate = new Date(trabajo.fecha_inicio);
    const endDate = new Date(trabajo.fecha_entrega);
    const today = new Date();
    
    if (today < startDate) return 0;
    if (today > endDate) return 100;
    
    const totalDays = (endDate.getTime() - startDate.getTime()) / (1000 * 60 * 60 * 24);
    const elapsedDays = (today.getTime() - startDate.getTime()) / (1000 * 60 * 60 * 24);
    
    return Math.round((elapsedDays / totalDays) * 100);
};

// Get days remaining
const getDaysRemaining = (fechaEntrega: string) => {
    const endDate = new Date(fechaEntrega);
    const today = new Date();
    const diffTime = endDate.getTime() - today.getTime();
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
    
    if (diffDays < 0) return 'Vencido';
    if (diffDays === 0) return 'Vence hoy';
    if (diffDays === 1) return 'Vence mañana';
    return `${diffDays} días`;
};
</script>

<template>
    <Head title="Trabajos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">
                        Trabajos
                    </h1>
                    <p class="text-muted-foreground">
                        Gestiona todos los trabajos del estudio
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        href="/trabajos/create"
                        class="btn-neon-primary inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2"
                    >
                        <Plus class="mr-2 h-4 w-4" />
                        Nuevo Trabajo
                    </Link>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-col gap-4 rounded-lg border bg-card p-4">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <!-- Search -->
                    <div class="relative flex-1 max-w-sm">
                        <Search class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <input
                            v-model="search"
                            type="text"
                            placeholder="Buscar trabajos..."
                            class="input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10"
                        />
                    </div>

                    <!-- Filters -->
                    <div class="flex items-center gap-2">
                        <select
                            v-model="estadoFilter"
                            class="input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="">Todos los estados</option>
                            <option v-for="estado in estados" :key="estado.idEstado" :value="estado.nombre">
                                {{ estado.nombre }}
                            </option>
                        </select>

                        <select
                            v-model="prioridadFilter"
                            class="input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="">Todas las prioridades</option>
                            <option value="baja">Baja</option>
                            <option value="media">Media</option>
                            <option value="alta">Alta</option>
                            <option value="urgente">Urgente</option>
                        </select>

                        <select
                            v-model="clienteFilter"
                            class="input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="">Todos los clientes</option>
                            <option v-for="cliente in clientes" :key="cliente.idCliente" :value="cliente.idCliente">
                                {{ cliente.nombre }}
                            </option>
                        </select>

                        <select
                            v-model="servicioFilter"
                            class="input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="">Todos los servicios</option>
                            <option v-for="servicio in servicios" :key="servicio.idServicio" :value="servicio.idServicio">
                                {{ servicio.nombre }}
                            </option>
                        </select>

                        <select
                            v-model="sortBy"
                            class="input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="created_at">Fecha de creación</option>
                            <option value="fecha_inicio">Fecha de inicio</option>
                            <option value="fecha_entrega">Fecha de entrega</option>
                            <option value="prioridad">Prioridad</option>
                            <option value="estado">Estado</option>
                            <option value="precio_total">Precio</option>
                        </select>

                        <button
                            @click="sortDirection = sortDirection === 'asc' ? 'desc' : 'asc'"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-3"
                        >
                            <Filter class="h-4 w-4" />
                        </button>

                        <button
                            v-if="hasFilters"
                            @click="clearFilters"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-3"
                        >
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid gap-4 md:grid-cols-4">
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <Briefcase class="h-5 w-5 text-muted-foreground" />
                        <span class="text-sm font-medium text-muted-foreground">Total Trabajos</span>
                    </div>
                    <p class="text-2xl font-bold">{{ trabajos.total }}</p>
                </div>
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <TrendingUp class="h-5 w-5 text-blue-600" />
                        <span class="text-sm font-medium text-muted-foreground">En Proceso</span>
                    </div>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ trabajos.data.filter(t => t.estado === 'en_proceso').length }}
                    </p>
                </div>
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <CheckCircle class="h-5 w-5 text-green-600" />
                        <span class="text-sm font-medium text-muted-foreground">Completados</span>
                    </div>
                    <p class="text-2xl font-bold text-green-600">
                        {{ trabajos.data.filter(t => t.estado === 'completado').length }}
                    </p>
                </div>
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <DollarSign class="h-5 w-5 text-purple-600" />
                        <span class="text-sm font-medium text-muted-foreground">Ingresos</span>
                    </div>
                    <p class="text-2xl font-bold text-purple-600">
                        {{ formatCurrency(trabajos.data.reduce((sum, t) => sum + t.precio_total, 0)) }}
                    </p>
                </div>
            </div>

            <!-- Trabajos Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="trabajo in trabajos.data"
                    :key="trabajo.idTrabajo"
                    class="card-neon group relative overflow-hidden rounded-xl p-6 transition-all duration-300"
                >
                    <!-- Header -->
                    <div class="mb-4 flex items-start justify-between">
                        <div class="flex-1">
                            <h3 class="font-semibold text-lg line-clamp-2">{{ trabajo.titulo }}</h3>
                            <p class="text-sm text-muted-foreground mt-1 line-clamp-1">
                                {{ trabajo.cliente?.nombre }}
                            </p>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span
                                :class="getStatusClass(trabajo.estado)"
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                            >
                                <component :is="getStatusIcon(trabajo.estado)" class="mr-1 h-3 w-3" />
                                {{ trabajo.estado_trabajo?.nombre || trabajo.estado }}
                            </span>
                            <span
                                :class="getPriorityClass(trabajo.prioridad)"
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                            >
                                {{ trabajo.prioridad }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between text-sm mb-1">
                            <span class="text-muted-foreground">Progreso</span>
                            <span class="font-medium">{{ calculateProgress(trabajo) }}%</span>
                        </div>
                        <div class="w-full bg-muted rounded-full h-2">
                            <div 
                                class="h-2 rounded-full transition-all duration-300"
                                :class="{
                                    'bg-green-500': trabajo.estado === 'completado',
                                    'bg-blue-500': trabajo.estado === 'en_proceso',
                                    'bg-yellow-500': trabajo.estado === 'pendiente',
                                    'bg-red-500': trabajo.estado === 'cancelado'
                                }"
                                :style="{ width: `${calculateProgress(trabajo)}%` }"
                            ></div>
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="space-y-3">
                        <div class="flex items-center gap-2 text-sm">
                            <Briefcase class="h-4 w-4 text-muted-foreground" />
                            <span class="text-muted-foreground">{{ trabajo.servicio?.nombre }}</span>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <Calendar class="h-4 w-4 text-muted-foreground" />
                            <span class="text-muted-foreground">
                                {{ formatDate(trabajo.fecha_inicio) }} - {{ formatDate(trabajo.fecha_entrega) }}
                            </span>
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <Clock class="h-4 w-4 text-muted-foreground" />
                            <span class="text-muted-foreground">{{ getDaysRemaining(trabajo.fecha_entrega) }}</span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2 text-sm">
                                <DollarSign class="h-4 w-4 text-muted-foreground" />
                                <span class="font-semibold">{{ formatCurrency(trabajo.precio_total) }}</span>
                            </div>
                            <div class="text-xs text-muted-foreground">
                                {{ formatCurrency(trabajo.adelanto) }} adelanto
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="absolute top-4 right-4 opacity-0 transition-opacity group-hover:opacity-100">
                        <div class="flex items-center gap-1">
                            <Link
                                :href="`/trabajos/${trabajo.idTrabajo}`"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8"
                            >
                                <Eye class="h-4 w-4" />
                            </Link>
                            <Link
                                :href="`/trabajos/${trabajo.idTrabajo}/edit`"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8"
                            >
                                <Edit class="h-4 w-4" />
                            </Link>
                            <button
                                @click="deleteTrabajo(trabajo.idTrabajo)"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 text-red-600 hover:text-red-700"
                            >
                                <Trash2 class="h-4 w-4" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Empty State -->
            <div
                v-if="trabajos.data.length === 0"
                class="flex flex-col items-center justify-center py-12 text-center"
            >
                <Briefcase class="h-12 w-12 text-muted-foreground mb-4" />
                <h3 class="text-lg font-semibold">No hay trabajos</h3>
                <p class="text-muted-foreground mb-4">
                    {{ hasFilters ? 'No se encontraron trabajos con los filtros aplicados.' : 'Comienza agregando tu primer trabajo.' }}
                </p>
                <Link
                    v-if="!hasFilters"
                    href="/trabajos/create"
                    class="btn-neon-primary inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2"
                >
                    <Plus class="mr-2 h-4 w-4" />
                    Agregar Trabajo
                </Link>
            </div>

            <!-- Pagination -->
            <div
                v-if="trabajos.last_page > 1"
                class="flex items-center justify-between border-t bg-muted/50 px-6 py-3"
            >
                <div class="text-sm text-muted-foreground">
                    Mostrando {{ trabajos.from }} a {{ trabajos.to }} de {{ trabajos.total }} resultados
                </div>
                <div class="flex items-center gap-2">
                    <button
                        @click="goToPage(1)"
                        :disabled="trabajos.current_page === 1"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                    >
                        <ChevronsLeft class="h-4 w-4" />
                    </button>
                    <button
                        @click="goToPage(trabajos.current_page - 1)"
                        :disabled="trabajos.current_page === 1"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                    >
                        <ChevronLeft class="h-4 w-4" />
                    </button>
                    <span class="text-sm">
                        Página {{ trabajos.current_page }} de {{ trabajos.last_page }}
                    </span>
                    <button
                        @click="goToPage(trabajos.current_page + 1)"
                        :disabled="trabajos.current_page === trabajos.last_page"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                    >
                        <ChevronRight class="h-4 w-4" />
                    </button>
                    <button
                        @click="goToPage(trabajos.last_page)"
                        :disabled="trabajos.current_page === trabajos.last_page"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                    >
                        <ChevronsRight class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 