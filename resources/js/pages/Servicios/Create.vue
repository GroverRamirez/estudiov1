<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type ServicioFormData } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    ArrowLeft, 
    Save, 
    Camera, 
    DollarSign, 
    Tag, 
    Clock,
    FileText,
    Image,
    XCircle
} from 'lucide-vue-next';
import { ref } from 'vue';

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
        title: 'Nuevo Servicio',
        href: '/servicios/create',
    },
];

const form = useForm({
    nombre: '',
    descripcion: '',
    precio: 0,
    categoria: 'fotografia',
    estado: 'activo',
    duracion_estimada: 60,
    imagen: '',
});

const isSubmitting = ref(false);

const submit = () => {
    isSubmitting.value = true;
    form.post('/servicios', {
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

const categorias = [
    { value: 'fotografia', label: 'Fotografía' },
    { value: 'video', label: 'Video' },
    { value: 'edicion', label: 'Edición' },
    { value: 'impresion', label: 'Impresión' },
    { value: 'eventos', label: 'Eventos' },
    { value: 'otros', label: 'Otros' },
];
</script>

<template>
    <Head title="Nuevo Servicio" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">
                        Nuevo Servicio
                    </h1>
                    <p class="text-muted-foreground">
                        Agrega un nuevo servicio al catálogo
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
                </div>
            </div>

            <!-- Form -->
            <div class="mx-auto w-full max-w-2xl">
                <div class="card-neon">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Nombre -->
                            <div class="space-y-2">
                                <label for="nombre" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Nombre del Servicio *
                                </label>
                                <div class="relative">
                                    <Camera class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        id="nombre"
                                        v-model="form.nombre"
                                        type="text"
                                        :class="[
                                            'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.nombre ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="Ej: Sesión de fotos profesional"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.nombre" class="text-sm text-red-600">
                                    {{ form.errors.nombre }}
                                </p>
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
                                        placeholder="Describe detalladamente el servicio..."
                                        required
                                    ></textarea>
                                </div>
                                <p v-if="form.errors.descripcion" class="text-sm text-red-600">
                                    {{ form.errors.descripcion }}
                                </p>
                            </div>

                            <!-- Precio y Categoría -->
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="precio" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        Precio (BOB) *
                                    </label>
                                    <div class="relative">
                                        <DollarSign class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <input
                                            id="precio"
                                            v-model="form.precio"
                                            type="number"
                                            step="0.01"
                                            min="0"
                                            :class="[
                                                'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                                form.errors.precio ? 'border-red-500 focus-visible:ring-red-500' : ''
                                            ]"
                                            placeholder="0.00"
                                            required
                                        />
                                    </div>
                                    <p v-if="form.errors.precio" class="text-sm text-red-600">
                                        {{ form.errors.precio }}
                                    </p>
                                </div>

                                <div class="space-y-2">
                                    <label for="categoria" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        Categoría *
                                    </label>
                                    <div class="relative">
                                        <Tag class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <select
                                            id="categoria"
                                            v-model="form.categoria"
                                            :class="[
                                                'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                                form.errors.categoria ? 'border-red-500 focus-visible:ring-red-500' : ''
                                            ]"
                                            required
                                        >
                                            <option v-for="categoria in categorias" :key="categoria.value" :value="categoria.value">
                                                {{ categoria.label }}
                                            </option>
                                        </select>
                                    </div>
                                    <p v-if="form.errors.categoria" class="text-sm text-red-600">
                                        {{ form.errors.categoria }}
                                    </p>
                                </div>
                            </div>

                            <!-- Duración y Estado -->
                            <div class="grid gap-4 md:grid-cols-2">
                                <div class="space-y-2">
                                    <label for="duracion_estimada" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                        Duración Estimada (minutos)
                                    </label>
                                    <div class="relative">
                                        <Clock class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <input
                                            id="duracion_estimada"
                                            v-model="form.duracion_estimada"
                                            type="number"
                                            min="0"
                                            :class="[
                                                'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                                form.errors.duracion_estimada ? 'border-red-500 focus-visible:ring-red-500' : ''
                                            ]"
                                            placeholder="60"
                                        />
                                    </div>
                                    <p v-if="form.errors.duracion_estimada" class="text-sm text-red-600">
                                        {{ form.errors.duracion_estimada }}
                                    </p>
                                </div>

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
                                            <option value="activo">Activo</option>
                                            <option value="inactivo">Inactivo</option>
                                        </select>
                                    </div>
                                    <p v-if="form.errors.estado" class="text-sm text-red-600">
                                        {{ form.errors.estado }}
                                    </p>
                                </div>
                            </div>

                            <!-- Imagen -->
                            <div class="space-y-2">
                                <label for="imagen" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    URL de Imagen
                                </label>
                                <div class="relative">
                                    <Image class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        id="imagen"
                                        v-model="form.imagen"
                                        type="url"
                                        :class="[
                                            'input-neon flex h-10 w-full rounded-md px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.imagen ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="https://ejemplo.com/imagen.jpg"
                                    />
                                </div>
                                <p v-if="form.errors.imagen" class="text-sm text-red-600">
                                    {{ form.errors.imagen }}
                                </p>
                            </div>

                            <!-- Form Actions -->
                            <div class="flex items-center justify-end gap-4 pt-6">
                                <button
                                    type="button"
                                    @click="resetForm"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
                                >
                                    <XCircle class="mr-2 h-4 w-4" />
                                    Limpiar
                                </button>
                                <button
                                    type="submit"
                                    :disabled="isSubmitting"
                                    class="btn-neon-primary inline-flex items-center justify-center rounded-md text-sm font-medium h-10 px-4 py-2"
                                >
                                    <Save v-if="!isSubmitting" class="mr-2 h-4 w-4" />
                                    <div v-else class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"></div>
                                    {{ isSubmitting ? 'Guardando...' : 'Guardar Servicio' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 