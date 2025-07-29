<template>
  <AppLayout title="Nuevo Pago">
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold bg-gradient-to-r from-cyan-400 via-purple-500 to-pink-500 bg-clip-text text-transparent">
            Registrar Nuevo Pago
          </h1>
          <p class="text-gray-400 mt-1">Registra un nuevo pago para el Foto Estudio EU</p>
        </div>
        <Link
          :href="route('pagos.index')"
          class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-300"
        >
          <ArrowLeft class="w-4 h-4 mr-2" />
          Volver
        </Link>
      </div>
    </template>

    <div class="max-w-4xl mx-auto">
      <!-- Información del Trabajo Seleccionado -->
      <div v-if="selectedTrabajo" class="bg-gradient-to-r from-blue-900/50 to-purple-900/50 rounded-xl p-6 mb-6 border border-blue-700/50">
        <h3 class="text-lg font-semibold text-white mb-4">Información del Trabajo</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <p class="text-gray-300 text-sm">Trabajo</p>
            <p class="text-white font-medium">{{ selectedTrabajo.titulo }}</p>
          </div>
          <div>
            <p class="text-gray-300 text-sm">Cliente</p>
            <p class="text-white font-medium">{{ selectedTrabajo.cliente?.nombre }}</p>
          </div>
          <div>
            <p class="text-gray-300 text-sm">Precio Total</p>
            <p class="text-green-400 font-bold text-lg">${{ formatCurrency(selectedTrabajo.precio_total) }}</p>
          </div>
          <div>
            <p class="text-gray-300 text-sm">Saldo Pendiente</p>
            <p class="text-yellow-400 font-bold text-lg">${{ formatCurrency(selectedTrabajo.saldo_pendiente) }}</p>
          </div>
        </div>
      </div>

      <!-- Formulario de Pago -->
      <form @submit.prevent="submit" class="bg-gray-900/50 backdrop-blur-sm rounded-xl p-6 border border-gray-700/50">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Selección de Trabajo -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-300 mb-2">
              Trabajo <span class="text-red-400">*</span>
            </label>
            <select
              v-model="form.idTrabajo"
              @change="onTrabajoChange"
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
              :class="{ 'border-red-500': form.errors.idTrabajo }"
            >
              <option value="">Seleccionar trabajo...</option>
              <option v-for="trabajo in trabajos" :key="trabajo.idTrabajo" :value="trabajo.idTrabajo">
                {{ trabajo.titulo }} - {{ trabajo.cliente?.nombre }} (Saldo: ${{ formatCurrency(trabajo.saldo_pendiente) }})
              </option>
            </select>
            <p v-if="form.errors.idTrabajo" class="mt-1 text-sm text-red-400">
              {{ form.errors.idTrabajo }}
            </p>
          </div>

          <!-- Monto -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
              Monto <span class="text-red-400">*</span>
            </label>
            <div class="relative">
              <span class="absolute left-3 top-2 text-gray-400">$</span>
              <input
                v-model="form.monto"
                type="number"
                step="0.01"
                min="0"
                placeholder="0.00"
                class="w-full pl-8 pr-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
                :class="{ 'border-red-500': form.errors.monto }"
                @input="calculateSaldo"
              />
            </div>
            <p v-if="form.errors.monto" class="mt-1 text-sm text-red-400">
              {{ form.errors.monto }}
            </p>
          </div>

          <!-- Fecha de Pago -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
              Fecha de Pago <span class="text-red-400">*</span>
            </label>
            <input
              v-model="form.fecha_pago"
              type="date"
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
              :class="{ 'border-red-500': form.errors.fecha_pago }"
            />
            <p v-if="form.errors.fecha_pago" class="mt-1 text-sm text-red-400">
              {{ form.errors.fecha_pago }}
            </p>
          </div>

          <!-- Método de Pago -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
              Método de Pago <span class="text-red-400">*</span>
            </label>
            <select
              v-model="form.metodo_pago"
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
              :class="{ 'border-red-500': form.errors.metodo_pago }"
            >
              <option value="">Seleccionar método...</option>
              <option value="efectivo">Efectivo</option>
              <option value="tarjeta">Tarjeta</option>
              <option value="transferencia">Transferencia</option>
              <option value="deposito">Depósito</option>
              <option value="otro">Otro</option>
            </select>
            <p v-if="form.errors.metodo_pago" class="mt-1 text-sm text-red-400">
              {{ form.errors.metodo_pago }}
            </p>
          </div>

          <!-- Tipo de Pago -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
              Tipo de Pago <span class="text-red-400">*</span>
            </label>
            <select
              v-model="form.tipo_pago"
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
              :class="{ 'border-red-500': form.errors.tipo_pago }"
            >
              <option value="">Seleccionar tipo...</option>
              <option value="adelanto">Adelanto</option>
              <option value="pago_parcial">Pago Parcial</option>
              <option value="pago_completo">Pago Completo</option>
              <option value="reembolso">Reembolso</option>
            </select>
            <p v-if="form.errors.tipo_pago" class="mt-1 text-sm text-red-400">
              {{ form.errors.tipo_pago }}
            </p>
          </div>

          <!-- Referencia -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
              Referencia
            </label>
            <input
              v-model="form.referencia"
              type="text"
              placeholder="Número de referencia, comprobante..."
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
              :class="{ 'border-red-500': form.errors.referencia }"
            />
            <p v-if="form.errors.referencia" class="mt-1 text-sm text-red-400">
              {{ form.errors.referencia }}
            </p>
          </div>

          <!-- Estado -->
          <div>
            <label class="block text-sm font-medium text-gray-300 mb-2">
              Estado <span class="text-red-400">*</span>
            </label>
            <select
              v-model="form.estado"
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
              :class="{ 'border-red-500': form.errors.estado }"
            >
              <option value="">Seleccionar estado...</option>
              <option value="completado">Completado</option>
              <option value="pendiente">Pendiente</option>
              <option value="cancelado">Cancelado</option>
              <option value="reembolsado">Reembolsado</option>
            </select>
            <p v-if="form.errors.estado" class="mt-1 text-sm text-red-400">
              {{ form.errors.estado }}
            </p>
          </div>

          <!-- Observaciones -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-300 mb-2">
              Observaciones
            </label>
            <textarea
              v-model="form.observaciones"
              rows="3"
              placeholder="Observaciones adicionales sobre el pago..."
              class="w-full px-3 py-2 bg-gray-800 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-cyan-500 focus:border-transparent"
              :class="{ 'border-red-500': form.errors.observaciones }"
            ></textarea>
            <p v-if="form.errors.observaciones" class="mt-1 text-sm text-red-400">
              {{ form.errors.observaciones }}
            </p>
          </div>
        </div>

        <!-- Resumen del Pago -->
        <div v-if="selectedTrabajo && form.monto" class="mt-6 p-4 bg-gray-800/50 rounded-lg border border-gray-700/50">
          <h4 class="text-lg font-semibold text-white mb-3">Resumen del Pago</h4>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <p class="text-gray-300 text-sm">Monto a Pagar</p>
              <p class="text-green-400 font-bold text-lg">${{ formatCurrency(Number(form.monto)) }}</p>
            </div>
            <div>
              <p class="text-gray-300 text-sm">Saldo Actual</p>
              <p class="text-yellow-400 font-bold text-lg">${{ formatCurrency(selectedTrabajo.saldo_pendiente) }}</p>
            </div>
            <div>
              <p class="text-gray-300 text-sm">Saldo Restante</p>
              <p class="text-blue-400 font-bold text-lg">${{ formatCurrency(saldoRestante) }}</p>
            </div>
          </div>
        </div>

        <!-- Botones -->
        <div class="flex justify-end space-x-4 mt-6">
          <Link
            :href="route('pagos.index')"
            class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-all duration-300"
          >
            Cancelar
          </Link>
          <button
            type="submit"
            :disabled="form.processing"
            class="px-6 py-2 bg-gradient-to-r from-cyan-500 to-purple-600 hover:from-cyan-600 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-300 disabled:opacity-50 disabled:cursor-not-allowed"
          >
            <span v-if="form.processing">Guardando...</span>
            <span v-else>Guardar Pago</span>
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { ArrowLeft } from 'lucide-vue-next';
import type { PagoFormData, Trabajo } from '@/types';

interface Props {
  trabajos: Trabajo[];
}

const props = defineProps<Props>();

const form = useForm({
  monto: 0,
  fecha_pago: new Date().toISOString().split('T')[0],
  metodo_pago: '',
  referencia: '',
  estado: 'completado',
  tipo_pago: '',
  observaciones: '',
  idTrabajo: 0
});

// Trabajo seleccionado
const selectedTrabajo = ref<Trabajo | null>(null);

// Saldo restante calculado
const saldoRestante = computed(() => {
  if (!selectedTrabajo.value || !form.monto) return 0;
  return Math.max(0, selectedTrabajo.value.saldo_pendiente - Number(form.monto));
});

// Cambio de trabajo
const onTrabajoChange = () => {
  if (form.idTrabajo) {
    selectedTrabajo.value = props.trabajos.find(t => t.idTrabajo === form.idTrabajo) || null;
  } else {
    selectedTrabajo.value = null;
  }
};

// Calcular saldo
const calculateSaldo = () => {
  // Auto-completar tipo de pago basado en el monto
  if (selectedTrabajo.value && form.monto) {
    const monto = Number(form.monto);
    const saldoPendiente = selectedTrabajo.value.saldo_pendiente;
    
    if (monto >= saldoPendiente) {
      form.tipo_pago = 'pago_completo';
    } else if (monto > 0) {
      form.tipo_pago = 'pago_parcial';
    }
  }
};

// Formatear moneda
const formatCurrency = (amount: number) => {
  return new Intl.NumberFormat('es-BO', {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  }).format(amount);
};

// Enviar formulario
const submit = () => {
  form.post(route('pagos.store'));
};
</script> 