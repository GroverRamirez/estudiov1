<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { 
    Users, 
    FileText, 
    DollarSign, 
    Clock, 
    Plus, 
    TrendingUp,
    CheckCircle,
    AlertCircle,
    XCircle,
    RefreshCw
} from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Props from backend
interface Props {
    stats: {
        totalClientes: number;
        totalServicios: number;
        totalTrabajos: number;
        totalPagos: number;
        trabajosPendientes: number;
        pagosPendientes: number;
        pagosCompletados: number;
        totalIngresos: number;
        ingresosMes: number;
        pagosHoy: number;
    };
    recentTrabajos: Array<{
        id: number;
        titulo: string;
        cliente: string;
        servicio: string;
        estado: string;
        precio_total: number;
        saldo_pendiente: number;
        fecha_inicio: string;
        fecha_entrega: string;
    }>;
    recentPagos: Array<{
        id: number;
        monto: number;
        metodo_pago: string;
        estado: string;
        tipo_pago: string;
        fecha_pago: string;
        trabajo: string;
        cliente: string;
    }>;
    estadosTrabajos: Record<string, { count: number; color: string; icon: string }>;
    estadosPagos: Record<string, { count: number; total: number; color: string; icon: string }>;
    metodosPago: Array<{
        metodo_pago: string;
        count: number;
        total: number;
    }>;
    actividadMes: {
        trabajos_nuevos: number;
        pagos_realizados: number;
        clientes_nuevos: number;
    };
}

const props = defineProps<Props>();

// Formatear moneda
const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('es-BO', {
        style: 'currency',
        currency: 'BOB',
        minimumFractionDigits: 2
    }).format(amount);
};

// Obtener icono por estado
const getEstadoIcon = (estado: string) => {
    const icons = {
        'pendiente': Clock,
        'en proceso': TrendingUp,
        'completado': CheckCircle,
        'cancelado': XCircle,
        'reembolsado': RefreshCw
    };
    return icons[estado as keyof typeof icons] || AlertCircle;
};

// Obtener color por estado
const getEstadoColor = (estado: string) => {
    const colors = {
        'pendiente': 'text-yellow-500',
        'en proceso': 'text-blue-500',
        'completado': 'text-green-500',
        'cancelado': 'text-red-500',
        'reembolsado': 'text-purple-500'
    };
    return colors[estado as keyof typeof colors] || 'text-gray-500';
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <!-- Header con efecto neón -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-purple-500 to-pink-500 mb-4">
                    Bienvenido a Foto Estudio "EU"
                </h1>
                <p class="text-gray-400 text-lg">
                    Sistema de Control y Gestión Digital
                </p>
            </div>

            <!-- Cards de estadísticas principales -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-blue-200/20">
                            <Users class="h-6 w-6" />
                        </div>
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Clientes</p>
                            <p class="text-2xl font-bold">{{ stats.totalClientes }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-200/20">
                            <FileText class="h-6 w-6" />
                        </div>
                        <div>
                            <p class="text-green-100 text-sm font-medium">Trabajos Activos</p>
                            <p class="text-2xl font-bold">{{ stats.trabajosPendientes }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-purple-200/20">
                            <DollarSign class="h-6 w-6" />
                        </div>
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Ingresos del Mes</p>
                            <p class="text-2xl font-bold">{{ formatCurrency(stats.ingresosMes) }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-yellow-200/20">
                            <Clock class="h-6 w-6" />
                        </div>
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Pendientes</p>
                            <p class="text-2xl font-bold">{{ stats.pagosPendientes }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas detalladas -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Estados de Trabajos -->
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50">
                    <h3 class="text-lg font-semibold text-white mb-4">Estados de Trabajos</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div v-for="(estado, key) in estadosTrabajos" :key="key" 
                             class="bg-gray-800/50 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <component :is="getEstadoIcon(key)" 
                                         :class="getEstadoColor(key)" 
                                         class="h-5 w-5" />
                                <div>
                                    <p class="text-sm text-gray-300 capitalize">{{ key.replace('_', ' ') }}</p>
                                    <p class="text-lg font-bold text-white">{{ estado.count }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Estados de Pagos -->
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50">
                    <h3 class="text-lg font-semibold text-white mb-4">Estados de Pagos</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div v-for="(estado, key) in estadosPagos" :key="key" 
                             class="bg-gray-800/50 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <component :is="getEstadoIcon(key)" 
                                         :class="getEstadoColor(key)" 
                                         class="h-5 w-5" />
                                <div>
                                    <p class="text-sm text-gray-300 capitalize">{{ key.replace('_', ' ') }}</p>
                                    <p class="text-lg font-bold text-white">{{ estado.count }}</p>
                                    <p class="text-xs text-gray-400">{{ formatCurrency(estado.total) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contenido principal -->
            <div class="grid gap-6 md:grid-cols-2">
                <!-- Acciones rápidas -->
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50">
                    <h3 class="text-lg font-semibold text-white mb-4">Acciones Rápidas</h3>
                    <div class="grid gap-3">
                        <a href="/clientes/create" 
                           class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-cyan-600 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-300 transform hover:scale-105">
                            <Plus class="h-5 w-5 mr-2" />
                            Nuevo Cliente
                        </a>
                        <a href="/trabajos/create" 
                           class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-green-500 to-blue-600 hover:from-green-600 hover:to-blue-700 text-white font-medium rounded-lg transition-all duration-300 transform hover:scale-105">
                            <FileText class="h-5 w-5 mr-2" />
                            Nuevo Trabajo
                        </a>
                        <a href="/pagos/create" 
                           class="inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-yellow-500 to-orange-600 hover:from-yellow-600 hover:to-orange-700 text-white font-medium rounded-lg transition-all duration-300 transform hover:scale-105">
                            <DollarSign class="h-5 w-5 mr-2" />
                            Nuevo Pago
                        </a>
                    </div>
                </div>

                <!-- Actividad Reciente -->
                <div class="bg-gray-900/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50">
                    <h3 class="text-lg font-semibold text-white mb-4">Actividad Reciente</h3>
                    <div class="space-y-3">
                        <!-- Trabajos recientes -->
                        <div v-if="recentTrabajos.length > 0">
                            <h4 class="text-sm font-medium text-gray-300 mb-2">Trabajos Recientes</h4>
                            <div class="space-y-2">
                                <div v-for="trabajo in recentTrabajos.slice(0, 3)" :key="trabajo.id" 
                                     class="flex items-center gap-3 p-3 rounded-lg bg-gray-800/30">
                                    <div class="w-2 h-2 rounded-full bg-cyan-400"></div>
                                    <div class="flex-1">
                                        <p class="text-sm text-white font-medium">{{ trabajo.titulo }}</p>
                                        <p class="text-xs text-gray-400">{{ trabajo.cliente }} - {{ trabajo.servicio }}</p>
                                    </div>
                                    <span :class="getEstadoColor(trabajo.estado)" class="text-xs font-medium">
                                        {{ trabajo.estado }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Pagos recientes -->
                        <div v-if="recentPagos.length > 0">
                            <h4 class="text-sm font-medium text-gray-300 mb-2 mt-4">Pagos Recientes</h4>
                            <div class="space-y-2">
                                <div v-for="pago in recentPagos.slice(0, 3)" :key="pago.id" 
                                     class="flex items-center gap-3 p-3 rounded-lg bg-gray-800/30">
                                    <div class="w-2 h-2 rounded-full bg-green-400"></div>
                                    <div class="flex-1">
                                        <p class="text-sm text-white font-medium">{{ formatCurrency(pago.monto) }}</p>
                                        <p class="text-xs text-gray-400">{{ pago.trabajo }} - {{ pago.cliente }}</p>
                                    </div>
                                    <span :class="getEstadoColor(pago.estado)" class="text-xs font-medium">
                                        {{ pago.estado }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div v-if="recentTrabajos.length === 0 && recentPagos.length === 0" 
                             class="text-center py-4">
                            <p class="text-gray-400">No hay actividad reciente</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Resumen financiero -->
            <div class="bg-gray-900/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50">
                <h3 class="text-lg font-semibold text-white mb-4">Resumen Financiero</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-green-500/10 rounded-lg p-4 border border-green-500/20">
                        <p class="text-sm text-green-400">Ingresos Totales</p>
                        <p class="text-2xl font-bold text-green-400">{{ formatCurrency(stats.totalIngresos) }}</p>
                    </div>
                    <div class="bg-blue-500/10 rounded-lg p-4 border border-blue-500/20">
                        <p class="text-sm text-blue-400">Pagos Hoy</p>
                        <p class="text-2xl font-bold text-blue-400">{{ formatCurrency(stats.pagosHoy) }}</p>
                    </div>
                    <div class="bg-yellow-500/10 rounded-lg p-4 border border-yellow-500/20">
                        <p class="text-sm text-yellow-400">Pagos Pendientes</p>
                        <p class="text-2xl font-bold text-yellow-400">{{ stats.pagosPendientes }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
