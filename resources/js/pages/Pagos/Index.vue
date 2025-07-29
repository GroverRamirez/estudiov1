<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import AppLayout from '@/layouts/AppLayout.vue';
import {
  Plus,
  DollarSign,
  Clock,
  XCircle,
  Calendar,
  ArrowUpDown,
  Eye,
  Edit,
  Trash2
} from 'lucide-vue-next';
import type { PagosPageProps, Pago } from '@/types';

const props = defineProps<PagosPageProps>();

const filters = ref({
  search: props.filters.search || '',
  estado: props.filters.estado || '',
  metodo_pago: props.filters.metodo_pago || '',
  tipo_pago: props.filters.tipo_pago || '',
  fecha_desde: props.filters.fecha_desde || '',
  fecha_hasta: props.filters.fecha_hasta || '',
  trabajo_id: props.filters.trabajo_id || '',
  sort_by: props.filters.sort_by || 'fecha_pago',
  sort_direction: props.filters.sort_direction || 'desc'
});

const { pagos, trabajos, estadisticas } = props;

// Búsqueda con debounce
const debouncedSearch = useDebounceFn(() => {
  applyFilters();
}, 300);

// Aplicar filtros
const applyFilters = () => {
  router.get(route('pagos.index'), filters.value, {
    preserveState: true,
    preserveScroll: true,
    replace: true
  });
};

// Limpiar filtros
const clearFilters = () => {
  filters.value = {
    search: '',
    estado: '',
    metodo_pago: '',
    tipo_pago: '',
    fecha_desde: '',
    fecha_hasta: '',
    trabajo_id: '',
    sort_by: 'fecha_pago',
    sort_direction: 'desc'
  };
  applyFilters();
};

// Cambiar dirección de ordenamiento
const toggleSortDirection = () => {
  filters.value.sort_direction = filters.value.sort_direction === 'asc' ? 'desc' : 'asc';
  applyFilters();
};

// Eliminar pago
const deletePago = (id: number) => {
  if (confirm('¿Estás seguro de que quieres eliminar este pago?')) {
    router.delete(route('pagos.destroy', id));
  }
};

// Formatear moneda
const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('es-BO', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount);
};

// Formatear fecha
const formatDate = (date: string) => {
  return new Date(date).toLocaleDateString('es-BO', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  });
};

// Clases para métodos de pago
const getMetodoPagoClass = (metodo: string) => {
  const classes = {
    efectivo: 'inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800',
    tarjeta: 'inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800',
    transferencia: 'inline-flex px-2 py-1 text-xs font-medium rounded-full bg-purple-100 text-purple-800',
    deposito: 'inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800',
    otro: 'inline-flex px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800'
  };
  return classes[metodo as keyof typeof classes] || classes.otro;
};

// Clases para estados de pago
const getEstadoPagoClass = (estado: string) => {
  const classes = {
    completado: 'inline-flex px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800',
    pendiente: 'inline-flex px-2 py-1 text-xs font-medium rounded-full bg-yellow-100 text-yellow-800',
    cancelado: 'inline-flex px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800',
    reembolsado: 'inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800'
  };
  return classes[estado as keyof typeof classes] || classes.pendiente;
};
</script>

<template>
  <AppLayout title="Pagos">
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 via-purple-500 to-pink-500 bg-clip-text text-transparent">
            Gestión de Pagos
          </h1>
          <p class="text-gray-400 mt-1">Control financiero del Foto Estudio EU</p>
        </div>
        <Link
          :href="route('pagos.create')"
          class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-cyan-600 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl"
        >
          <Plus class="w-4 h-4 mr-2" />
          Nuevo Pago
        </Link>
      </div>
    </template>

    <!-- Estadísticas Financieras -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
      <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-green-100 text-sm font-medium">Total Pagado</p>
            <p class="text-2xl font-bold">${{ formatCurrency(estadisticas?.total_pagado || 0) }}</p>
          </div>
          <DollarSign class="w-8 h-8 text-green-200" />
        </div>
      </div>

      <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-yellow-100 text-sm font-medium">Pendiente</p>
            <p class="text-2xl font-bold">${{ formatCurrency(estadisticas?.total_pendiente || 0) }}</p>
          </div>
          <Clock class="w-8 h-8 text-yellow-200" />
        </div>
      </div>

      <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-red-100 text-sm font-medium">Cancelado</p>
            <p class="text-2xl font-bold">${{ formatCurrency(estadisticas?.total_cancelado || 0) }}</p>
          </div>
          <XCircle class="w-8 h-8 text-red-200" />
        </div>
      </div>

      <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-blue-100 text-sm font-medium">Hoy</p>
            <p class="text-2xl font-bold">${{ formatCurrency(estadisticas?.pagos_hoy || 0) }}</p>
          </div>
          <Calendar class="w-8 h-8 text-blue-200" />
        </div>
      </div>
    </div>

    <!-- Filtros Avanzados -->
    <div class="bg-gray-900/50 backdrop-blur-sm rounded-xl p-6 mb-6 border border-gray-700/50">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Búsqueda -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Buscar</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Buscar pagos..."
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
            @input="debouncedSearch"
          />
        </div>

        <!-- Estado -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Estado</label>
          <select
            v-model="filters.estado"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
            @change="applyFilters"
          >
            <option value="">Todos los estados</option>
            <option value="completado">Completado</option>
            <option value="pendiente">Pendiente</option>
            <option value="cancelado">Cancelado</option>
            <option value="reembolsado">Reembolsado</option>
          </select>
        </div>

        <!-- Método de Pago -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Método</label>
          <select
            v-model="filters.metodo_pago"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
            @change="applyFilters"
          >
            <option value="">Todos los métodos</option>
            <option value="efectivo">Efectivo</option>
            <option value="tarjeta">Tarjeta</option>
            <option value="transferencia">Transferencia</option>
            <option value="deposito">Depósito</option>
            <option value="otro">Otro</option>
          </select>
        </div>

        <!-- Tipo de Pago -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Tipo</label>
          <select
            v-model="filters.tipo_pago"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
            @change="applyFilters"
          >
            <option value="">Todos los tipos</option>
            <option value="adelanto">Adelanto</option>
            <option value="pago_parcial">Pago Parcial</option>
            <option value="pago_completo">Pago Completo</option>
            <option value="reembolso">Reembolso</option>
          </select>
        </div>

        <!-- Fecha Desde -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Desde</label>
          <input
            v-model="filters.fecha_desde"
            type="date"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
            @change="applyFilters"
          />
        </div>

        <!-- Fecha Hasta -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Hasta</label>
          <input
            v-model="filters.fecha_hasta"
            type="date"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
            @change="applyFilters"
          />
        </div>

        <!-- Trabajo -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Trabajo</label>
          <select
            v-model="filters.trabajo_id"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
            @change="applyFilters"
          >
            <option value="">Todos los trabajos</option>
            <option v-for="trabajo in trabajos" :key="trabajo.idTrabajo" :value="trabajo.idTrabajo">
              {{ trabajo.titulo }}
            </option>
          </select>
        </div>

        <!-- Ordenar -->
        <div>
          <label class="block text-sm font-medium text-gray-300 mb-2">Ordenar</label>
          <select
            v-model="filters.sort_by"
            class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
            @change="applyFilters"
          >
            <option value="fecha_pago">Fecha de Pago</option>
            <option value="monto">Monto</option>
            <option value="created_at">Fecha de Registro</option>
          </select>
        </div>
      </div>

      <!-- Botones de Acción -->
      <div class="flex justify-between items-center mt-4">
        <button
          @click="clearFilters"
          class="px-4 py-2 text-gray-400 hover:text-white transition-colors duration-200"
        >
          Limpiar Filtros
        </button>
        <div class="flex items-center space-x-2">
          <button
            @click="toggleSortDirection"
            class="px-3 py-2 text-gray-400 hover:text-white transition-colors duration-200"
          >
            <ArrowUpDown class="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- Lista de Pagos -->
    <div class="bg-gray-900/50 backdrop-blur-sm rounded-xl border border-gray-700/50 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-800/50">
            <tr>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                Pago
              </th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                Trabajo
              </th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                Monto
              </th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                Método
              </th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                Estado
              </th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                Fecha
              </th>
              <th class="px-6 py-4 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                Acciones
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-700/50">
            <tr v-for="pago in pagos.data" :key="pago.idPago" class="hover:bg-gray-800/30 transition-colors duration-200">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-cyan-500 to-purple-600 flex items-center justify-center">
                      <DollarSign class="w-5 h-5 text-white" />
                    </div>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-white">
                      #{{ pago.idPago }}
                    </div>
                    <div class="text-sm text-gray-400">
                      {{ pago.tipo_pago.replace('_', ' ').toUpperCase() }}
                    </div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-white">{{ pago.trabajo?.titulo || 'N/A' }}</div>
                <div class="text-sm text-gray-400">{{ pago.trabajo?.cliente?.nombre || 'Cliente N/A' }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-lg font-bold text-green-400">
                  ${{ formatCurrency(pago.monto) }}
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getMetodoPagoClass(pago.metodo_pago)">
                  {{ pago.metodo_pago.toUpperCase() }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getEstadoPagoClass(pago.estado)">
                  {{ pago.estado.toUpperCase() }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                {{ formatDate(pago.fecha_pago) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex items-center space-x-2">
                  <Link
                    :href="route('pagos.show', pago.idPago)"
                    class="text-cyan-400 hover:text-cyan-300 transition-colors duration-200"
                  >
                    <Eye class="w-4 h-4" />
                  </Link>
                  <Link
                    :href="route('pagos.edit', pago.idPago)"
                    class="text-yellow-400 hover:text-yellow-300 transition-colors duration-200"
                  >
                    <Edit class="w-4 h-4" />
                  </Link>
                  <button
                    @click="deletePago(pago.idPago)"
                    class="text-red-400 hover:text-red-300 transition-colors duration-200"
                  >
                    <Trash2 class="w-4 h-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

             <!-- Paginación -->
       <div v-if="pagos.last_page > 1" class="px-6 py-4 bg-gray-800/30">
         <!-- Paginación manual -->
         <div class="flex items-center justify-between">
           <div class="text-sm text-gray-400">
             Mostrando {{ pagos.from }} a {{ pagos.to }} de {{ pagos.total }} resultados
           </div>
           <div class="flex items-center space-x-2">
             <button
               v-if="pagos.current_page > 1"
               @click="router.get(route('pagos.index'), { ...filters, page: pagos.current_page - 1 })"
               class="px-3 py-2 text-gray-400 hover:text-white transition-colors duration-200"
             >
               Anterior
             </button>
             <span class="px-3 py-2 text-white">
               Página {{ pagos.current_page }} de {{ pagos.last_page }}
             </span>
             <button
               v-if="pagos.current_page < pagos.last_page"
               @click="router.get(route('pagos.index'), { ...filters, page: pagos.current_page + 1 })"
               class="px-3 py-2 text-gray-400 hover:text-white transition-colors duration-200"
             >
               Siguiente
             </button>
           </div>
         </div>
       </div>
    </div>

    <!-- Estado vacío -->
    <div v-if="pagos.data.length === 0" class="text-center py-12">
      <div class="mx-auto h-24 w-24 text-gray-400 mb-4">
        <DollarSign class="w-full h-full" />
      </div>
      <h3 class="text-lg font-medium text-gray-300 mb-2">No hay pagos registrados</h3>
      <p class="text-gray-400 mb-6">Comienza registrando el primer pago del estudio.</p>
      <Link
        :href="route('pagos.create')"
        class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-cyan-600 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-300"
      >
        <Plus class="w-4 h-4 mr-2" />
        Registrar Pago
      </Link>
    </div>
  </AppLayout>
</template>
