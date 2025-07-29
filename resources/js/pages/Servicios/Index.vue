<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type ServiciosPageProps } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
    Plus, 
    Search, 
    Filter, 
    Edit, 
    Trash2, 
    Eye,
    Camera,
    Clock,
    DollarSign,
    Tag,
    CheckCircle,
    XCircle,
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight,
    Image,
    Briefcase
} from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import { useDebounceFn } from '@vueuse/core';

interface Props {
    servicios: ServiciosPageProps['servicios'];
    filters: ServiciosPageProps['filters'];
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
];

// Reactive filters
const search = ref(props.filters.search || '');
const categoriaFilter = ref(props.filters.categoria || '');
const estadoFilter = ref(props.filters.estado || '');
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

watch([categoriaFilter, estadoFilter, sortBy, sortDirection], () => {
    updateFilters({
        categoria: categoriaFilter.value,
        estado: estadoFilter.value,
        sort_by: sortBy.value,
        sort_direction: sortDirection.value,
    });
});

// Update filters and reload
const updateFilters = (newFilters: Partial<Props['filters']>) => {
    router.get('/servicios', {
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
    router.get('/servicios', {
        ...props.filters,
        page,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Delete servicio
const deleteServicio = (servicioId: number) => {
    if (confirm('¿Estás seguro de que quieres eliminar este servicio?')) {
        router.delete(`/servicios/${servicioId}`, {
            onSuccess: () => {
                // Success handled by Inertia
            },
        });
    }
};

// Computed properties
const hasFilters = computed(() => {
    return search.value || categoriaFilter.value || estadoFilter.value || sortBy.value !== 'created_at' || sortDirection.value !== 'desc';
});

const clearFilters = () => {
    search.value = '';
    categoriaFilter.value = '';
    estadoFilter.value = '';
    sortBy.value = 'created_at';
    sortDirection.value = 'desc';
    updateFilters({
        search: '',
        categoria: '',
        estado: '',
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

// Format duration
const formatDuration = (minutes: number) => {
    if (minutes < 60) {
        return `${minutes} min`;
    }
    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;
    return remainingMinutes > 0 ? `${hours}h ${remainingMinutes}min` : `${hours}h`;
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
</script>

<template>
    <Head title="Servicios" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">
                        Servicios
                    </h1>
                    <p class="text-muted-foreground">
                        Gestiona el catálogo de servicios del estudio
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        href="/servicios/create"
                        class="btn-neon-primary inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2"
                    >
                        <Plus class="mr-2 h-4 w-4" />
                        Nuevo Servicio
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
                            placeholder="Buscar servicios..."
                            class="input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10"
                        />
                    </div>

                    <!-- Filters -->
                    <div class="flex items-center gap-2">
                        <select
                            v-model="categoriaFilter"
                            class="input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="">Todas las categorías</option>
                            <option value="fotografia">Fotografía</option>
                            <option value="video">Video</option>
                            <option value="edicion">Edición</option>
                            <option value="impresion">Impresión</option>
                            <option value="eventos">Eventos</option>
                            <option value="otros">Otros</option>
                        </select>

                        <select
                            v-model="estadoFilter"
                            class="input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="">Todos los estados</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>

                        <select
                            v-model="sortBy"
                            class="input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="created_at">Fecha de creación</option>
                            <option value="nombre">Nombre</option>
                            <option value="precio">Precio</option>
                            <option value="categoria">Categoría</option>
                            <option value="estado">Estado</option>
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
                        <span class="text-sm font-medium text-muted-foreground">Total Servicios</span>
                    </div>
                    <p class="text-2xl font-bold">{{ servicios.total }}</p>
                </div>
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <Camera class="h-5 w-5 text-blue-600" />
                        <span class="text-sm font-medium text-muted-foreground">Fotografía</span>
                    </div>
                    <p class="text-2xl font-bold text-blue-600">
                        {{ servicios.data.filter(s => s.categoria === 'fotografia').length }}
                    </p>
                </div>
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <Tag class="h-5 w-5 text-green-600" />
                        <span class="text-sm font-medium text-muted-foreground">Activos</span>
                    </div>
                    <p class="text-2xl font-bold text-green-600">
                        {{ servicios.data.filter(s => s.estado === 'activo').length }}
                    </p>
                </div>
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <DollarSign class="h-5 w-5 text-purple-600" />
                        <span class="text-sm font-medium text-muted-foreground">Precio Promedio</span>
                    </div>
                    <p class="text-2xl font-bold text-purple-600">
                        {{ formatCurrency(servicios.data.reduce((sum, s) => sum + s.precio, 0) / Math.max(servicios.data.length, 1)) }}
                    </p>
                </div>
            </div>

            <!-- Servicios Grid -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <div
                    v-for="servicio in servicios.data"
                    :key="servicio.idServicio"
                    class="card-neon group relative overflow-hidden rounded-xl p-6 transition-all duration-300"
                >
                    <!-- Imagen del servicio -->
                    <div class="mb-4 aspect-video overflow-hidden rounded-lg bg-muted">
                        <div v-if="servicio.imagen" class="h-full w-full bg-cover bg-center" :style="{ backgroundImage: `url(${servicio.imagen})` }"></div>
                        <div v-else class="flex h-full w-full items-center justify-center">
                            <Camera class="h-12 w-12 text-muted-foreground" />
                        </div>
                    </div>

                    <!-- Información del servicio -->
                    <div class="space-y-3">
                        <div class="flex items-start justify-between">
                            <h3 class="font-semibold text-lg">{{ servicio.nombre }}</h3>
                            <span
                                :class="getStatusClass(servicio.estado)"
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                            >
                                {{ servicio.estado }}
                            </span>
                        </div>

                        <p class="text-sm text-muted-foreground line-clamp-2">
                            {{ servicio.descripcion }}
                        </p>

                        <div class="flex items-center gap-2">
                            <span
                                :class="getCategoryClass(servicio.categoria)"
                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                            >
                                {{ servicio.categoria }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4 text-sm text-muted-foreground">
                                <div class="flex items-center gap-1">
                                    <DollarSign class="h-4 w-4" />
                                    <span class="font-semibold text-foreground">{{ formatCurrency(servicio.precio) }}</span>
                                </div>
                                <div v-if="servicio.duracion_estimada" class="flex items-center gap-1">
                                    <Clock class="h-4 w-4" />
                                    <span>{{ formatDuration(servicio.duracion_estimada) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones -->
                    <div class="absolute top-4 right-4 opacity-0 transition-opacity group-hover:opacity-100">
                        <div class="flex items-center gap-1">
                            <Link
                                :href="`/servicios/${servicio.idServicio}`"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8"
                            >
                                <Eye class="h-4 w-4" />
                            </Link>
                            <Link
                                :href="`/servicios/${servicio.idServicio}/edit`"
                                class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8"
                            >
                                <Edit class="h-4 w-4" />
                            </Link>
                            <button
                                @click="deleteServicio(servicio.idServicio)"
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
                v-if="servicios.data.length === 0"
                class="flex flex-col items-center justify-center py-12 text-center"
            >
                <Camera class="h-12 w-12 text-muted-foreground mb-4" />
                <h3 class="text-lg font-semibold">No hay servicios</h3>
                <p class="text-muted-foreground mb-4">
                    {{ hasFilters ? 'No se encontraron servicios con los filtros aplicados.' : 'Comienza agregando tu primer servicio.' }}
                </p>
                <Link
                    v-if="!hasFilters"
                    href="/servicios/create"
                    class="btn-neon-primary inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2"
                >
                    <Plus class="mr-2 h-4 w-4" />
                    Agregar Servicio
                </Link>
            </div>

            <!-- Pagination -->
            <div
                v-if="servicios.last_page > 1"
                class="flex items-center justify-between border-t bg-muted/50 px-6 py-3"
            >
                <div class="text-sm text-muted-foreground">
                    Mostrando {{ servicios.from }} a {{ servicios.to }} de {{ servicios.total }} resultados
                </div>
                <div class="flex items-center gap-2">
                    <button
                        @click="goToPage(1)"
                        :disabled="servicios.current_page === 1"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                    >
                        <ChevronsLeft class="h-4 w-4" />
                    </button>
                    <button
                        @click="goToPage(servicios.current_page - 1)"
                        :disabled="servicios.current_page === 1"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                    >
                        <ChevronLeft class="h-4 w-4" />
                    </button>
                    <span class="text-sm">
                        Página {{ servicios.current_page }} de {{ servicios.last_page }}
                    </span>
                    <button
                        @click="goToPage(servicios.current_page + 1)"
                        :disabled="servicios.current_page === servicios.last_page"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                    >
                        <ChevronRight class="h-4 w-4" />
                    </button>
                    <button
                        @click="goToPage(servicios.last_page)"
                        :disabled="servicios.current_page === servicios.last_page"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                    >
                        <ChevronsRight class="h-4 w-4" />
                    </button>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 