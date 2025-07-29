<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type Cliente } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { 
    ArrowLeft, 
    Edit, 
    User, 
    Mail, 
    Phone, 
    MapPin, 
    Calendar,
    FileText,
    Briefcase,
    CreditCard,
    TrendingUp,
    Clock,
    CheckCircle,
    XCircle
} from 'lucide-vue-next';

interface Props {
    cliente: Cliente;
    estadisticas?: {
        total_trabajos: number;
        trabajos_completados: number;
        trabajos_pendientes: number;
        total_pagado: number;
        saldo_pendiente: number;
    };
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
    {
        title: props.cliente.nombre,
        href: `/clientes/${props.cliente.idCliente}`,
    },
];

// Format date
const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('es-ES', {
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

// Format currency
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('es-MX', {
        style: 'currency',
        currency: 'MXN',
    }).format(amount);
};
</script>

<template>
    <Head :title="`Cliente - ${cliente.nombre}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">
                        {{ cliente.nombre }}
                    </h1>
                    <p class="text-muted-foreground">
                        Detalles del cliente
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        href="/clientes"
                        class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
                    >
                        <ArrowLeft class="mr-2 h-4 w-4" />
                        Volver
                    </Link>
                                            <Link
                            :href="`/clientes/${cliente.idCliente}/edit`"
                            class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2"
                        >
                        <Edit class="mr-2 h-4 w-4" />
                        Editar
                    </Link>
                </div>
            </div>

            <!-- Stats Cards -->
            <div v-if="estadisticas" class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <Briefcase class="h-5 w-5 text-blue-600" />
                        <span class="text-sm font-medium text-muted-foreground">Total Trabajos</span>
                    </div>
                    <p class="text-2xl font-bold text-blue-600">{{ estadisticas.total_trabajos }}</p>
                </div>
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <CheckCircle class="h-5 w-5 text-green-600" />
                        <span class="text-sm font-medium text-muted-foreground">Completados</span>
                    </div>
                    <p class="text-2xl font-bold text-green-600">{{ estadisticas.trabajos_completados }}</p>
                </div>
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <Clock class="h-5 w-5 text-yellow-600" />
                        <span class="text-sm font-medium text-muted-foreground">Pendientes</span>
                    </div>
                    <p class="text-2xl font-bold text-yellow-600">{{ estadisticas.trabajos_pendientes }}</p>
                </div>
                <div class="rounded-lg border bg-card p-4">
                    <div class="flex items-center gap-2">
                        <CreditCard class="h-5 w-5 text-purple-600" />
                        <span class="text-sm font-medium text-muted-foreground">Saldo Pendiente</span>
                    </div>
                    <p class="text-2xl font-bold text-purple-600">{{ formatCurrency(estadisticas.saldo_pendiente) }}</p>
                </div>
            </div>

            <!-- Cliente Information -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Basic Information -->
                <div class="rounded-lg border bg-card">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold mb-4">Información Básica</h2>
                        <div class="space-y-4">
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-primary/10">
                                    <User class="h-5 w-5 text-primary" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Nombre</p>
                                    <p class="font-medium">{{ cliente.nombre }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                                    <Mail class="h-5 w-5 text-blue-600" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Email</p>
                                    <p class="font-medium">{{ cliente.email }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                                    <Phone class="h-5 w-5 text-green-600" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Teléfono</p>
                                    <p class="font-medium">{{ cliente.telefono }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900">
                                    <MapPin class="h-5 w-5 text-orange-600" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Dirección</p>
                                    <p class="font-medium">{{ cliente.direccion }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                                    <Calendar class="h-5 w-5 text-purple-600" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Fecha de Registro</p>
                                    <p class="font-medium">{{ formatDate(cliente.fecha_registro) }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full" :class="cliente.estado === 'activo' ? 'bg-green-100 dark:bg-green-900' : 'bg-red-100 dark:bg-red-900'">
                                    <CheckCircle v-if="cliente.estado === 'activo'" class="h-5 w-5 text-green-600" />
                                    <XCircle v-else class="h-5 w-5 text-red-600" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Estado</p>
                                    <span
                                        :class="getStatusClass(cliente.estado)"
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                    >
                                        {{ cliente.estado }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Information -->
                <div class="rounded-lg border bg-card">
                    <div class="p-6">
                        <h2 class="text-lg font-semibold mb-4">Información Adicional</h2>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-900">
                                    <FileText class="h-5 w-5 text-gray-600" />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-muted-foreground mb-2">Observaciones</p>
                                    <p v-if="cliente.observaciones" class="text-sm">{{ cliente.observaciones }}</p>
                                    <p v-else class="text-sm text-muted-foreground italic">Sin observaciones</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-3">
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-900">
                                    <TrendingUp class="h-5 w-5 text-gray-600" />
                                </div>
                                <div>
                                    <p class="text-sm text-muted-foreground">Última Actualización</p>
                                    <p class="font-medium">{{ formatDate(cliente.updated_at) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="rounded-lg border bg-card">
                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4">Acciones Rápidas</h2>
                    <div class="grid gap-4 md:grid-cols-3">
                        <Link
                            :href="`/trabajos/create?cliente_id=${cliente.idCliente}`"
                            class="flex items-center gap-3 rounded-lg border p-4 hover:bg-accent/50 transition-colors"
                        >
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                                <Briefcase class="h-5 w-5 text-blue-600" />
                            </div>
                            <div>
                                <p class="font-medium">Nuevo Trabajo</p>
                                <p class="text-sm text-muted-foreground">Crear trabajo para este cliente</p>
                            </div>
                        </Link>
                        
                        <Link
                            :href="`/pagos/create?cliente_id=${cliente.idCliente}`"
                            class="flex items-center gap-3 rounded-lg border p-4 hover:bg-accent/50 transition-colors"
                        >
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
                                <CreditCard class="h-5 w-5 text-green-600" />
                            </div>
                            <div>
                                <p class="font-medium">Nuevo Pago</p>
                                <p class="text-sm text-muted-foreground">Registrar pago del cliente</p>
                            </div>
                        </Link>
                        
                        <Link
                            :href="`/clientes/${cliente.idCliente}/edit`"
                            class="flex items-center gap-3 rounded-lg border p-4 hover:bg-accent/50 transition-colors"
                        >
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900">
                                <Edit class="h-5 w-5 text-orange-600" />
                            </div>
                            <div>
                                <p class="font-medium">Editar Cliente</p>
                                <p class="text-sm text-muted-foreground">Modificar información</p>
                            </div>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 