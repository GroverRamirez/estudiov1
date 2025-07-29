<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type ClientesPageProps } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { 
    Plus, 
    Search, 
    Filter, 
    MoreHorizontal, 
    Edit, 
    Trash2, 
    Eye,
    Mail,
    Phone,
    MapPin,
    Calendar,
    User,
    Users,
    ChevronLeft,
    ChevronRight,
    ChevronsLeft,
    ChevronsRight
} from 'lucide-vue-next';
import { ref, computed, watch } from 'vue';
import { useDebounceFn } from '@vueuse/core';

interface Props {
    clientes: ClientesPageProps['clientes'];
    filters: ClientesPageProps['filters'];
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Clientes',
        href: '/clientes',
    },
];

// Reactive filters
const search = ref(props.filters.search || '');
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

watch([estadoFilter, sortBy, sortDirection], () => {
    updateFilters({
        estado: estadoFilter.value,
        sort_by: sortBy.value,
        sort_direction: sortDirection.value,
    });
});

// Update filters and reload
const updateFilters = (newFilters: Partial<Props['filters']>) => {
    router.get('/clientes', {
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
    router.get('/clientes', {
        ...props.filters,
        page,
    }, {
        preserveState: true,
        preserveScroll: true,
    });
};

// Delete cliente
const deleteCliente = (clienteId: number) => {
    if (confirm('¿Estás seguro de que quieres eliminar este cliente?')) {
        router.delete(`/clientes/${clienteId}`, {
            onSuccess: () => {
                // Success handled by Inertia
            },
        });
    }
};

// Computed properties
const hasFilters = computed(() => {
    return search.value || estadoFilter.value || sortBy.value !== 'created_at' || sortDirection.value !== 'desc';
});

const clearFilters = () => {
    search.value = '';
    estadoFilter.value = '';
    sortBy.value = 'created_at';
    sortDirection.value = 'desc';
    updateFilters({
        search: '',
        estado: '',
        sort_by: 'created_at',
        sort_direction: 'desc',
    });
};

// Format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
};

// Get status badge class
const getStatusClass = (estado: string) => {
    return estado === 'activo' 
        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300'
        : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
};
</script>

<template>
    <Head title="Clientes" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">
                        Clientes
                    </h1>
                    <p class="text-muted-foreground">
                        Gestiona tu base de datos de clientes
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        href="/clientes/create"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2"
                    >
                        <Plus class="mr-2 h-4 w-4" />
                        Nuevo Cliente
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
                            placeholder="Buscar clientes..."
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10"
                        />
                    </div>

                    <!-- Filters -->
                    <div class="flex items-center gap-2">
                        <select
                            v-model="estadoFilter"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="">Todos los estados</option>
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>

                        <select
                            v-model="sortBy"
                            class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            <option value="created_at">Fecha de registro</option>
                            <option value="nombre">Nombre</option>
                            <option value="email">Email</option>
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
            <div class="grid gap-4 md:grid-cols-3">
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <Users class="h-5 w-5 text-muted-foreground" />
                        <span class="text-sm font-medium text-muted-foreground">Total Clientes</span>
                    </div>
                    <p class="text-2xl font-bold">{{ clientes.total }}</p>
                </div>
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <User class="h-5 w-5 text-green-600" />
                        <span class="text-sm font-medium text-muted-foreground">Activos</span>
                    </div>
                    <p class="text-2xl font-bold text-green-600">
                        {{ clientes.data.filter(c => c.estado === 'activo').length }}
                    </p>
                </div>
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <User class="h-5 w-5 text-red-600" />
                        <span class="text-sm font-medium text-muted-foreground">Inactivos</span>
                    </div>
                    <p class="text-2xl font-bold text-red-600">
                        {{ clientes.data.filter(c => c.estado === 'inactivo').length }}
                    </p>
                </div>
            </div>

            <!-- Clientes List -->
            <div class="rounded-lg border bg-card">
                <div class="p-6">
                    <div class="space-y-4">
                        <div
                            v-for="cliente in clientes.data"
                            :key="cliente.idCliente"
                            class="flex items-center justify-between rounded-lg border p-4 hover:bg-accent/50 transition-colors"
                        >
                            <div class="flex items-center gap-4">
                                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10">
                                    <User class="h-6 w-6 text-primary" />
                                </div>
                                <div class="space-y-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="font-semibold">{{ cliente.nombre }}</h3>
                                        <span
                                            :class="getStatusClass(cliente.estado)"
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                        >
                                            {{ cliente.estado }}
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-4 text-sm text-muted-foreground">
                                        <div class="flex items-center gap-1">
                                            <Mail class="h-4 w-4" />
                                            {{ cliente.email }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <Phone class="h-4 w-4" />
                                            {{ cliente.telefono }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <MapPin class="h-4 w-4" />
                                            {{ cliente.direccion }}
                                        </div>
                                        <div class="flex items-center gap-1">
                                            <Calendar class="h-4 w-4" />
                                            {{ formatDate(cliente.fecha_registro) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <Link
                                    :href="`/clientes/${cliente.idCliente}`"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8"
                                >
                                    <Eye class="h-4 w-4" />
                                </Link>
                                <Link
                                    :href="`/clientes/${cliente.idCliente}/edit`"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8"
                                >
                                    <Edit class="h-4 w-4" />
                                </Link>
                                <button
                                    @click="deleteCliente(cliente.idCliente)"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 text-red-600 hover:text-red-700"
                                >
                                    <Trash2 class="h-4 w-4" />
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div
                        v-if="clientes.data.length === 0"
                        class="flex flex-col items-center justify-center py-12 text-center"
                    >
                        <Users class="h-12 w-12 text-muted-foreground mb-4" />
                        <h3 class="text-lg font-semibold">No hay clientes</h3>
                        <p class="text-muted-foreground mb-4">
                            {{ hasFilters ? 'No se encontraron clientes con los filtros aplicados.' : 'Comienza agregando tu primer cliente.' }}
                        </p>
                        <Link
                            v-if="!hasFilters"
                            href="/clientes/create"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2"
                        >
                            <Plus class="mr-2 h-4 w-4" />
                            Agregar Cliente
                        </Link>
                    </div>
                </div>

                <!-- Pagination -->
                <div
                    v-if="clientes.last_page > 1"
                    class="flex items-center justify-between border-t bg-muted/50 px-6 py-3"
                >
                    <div class="text-sm text-muted-foreground">
                        Mostrando {{ clientes.from }} a {{ clientes.to }} de {{ clientes.total }} resultados
                    </div>
                    <div class="flex items-center gap-2">
                        <button
                            @click="goToPage(1)"
                            :disabled="clientes.current_page === 1"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                        >
                            <ChevronsLeft class="h-4 w-4" />
                        </button>
                        <button
                            @click="goToPage(clientes.current_page - 1)"
                            :disabled="clientes.current_page === 1"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                        >
                            <ChevronLeft class="h-4 w-4" />
                        </button>
                        <span class="text-sm">
                            Página {{ clientes.current_page }} de {{ clientes.last_page }}
                        </span>
                        <button
                            @click="goToPage(clientes.current_page + 1)"
                            :disabled="clientes.current_page === clientes.last_page"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                        >
                            <ChevronRight class="h-4 w-4" />
                        </button>
                        <button
                            @click="goToPage(clientes.last_page)"
                            :disabled="clientes.current_page === clientes.last_page"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 w-8 disabled:opacity-50"
                        >
                            <ChevronsRight class="h-4 w-4" />
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 