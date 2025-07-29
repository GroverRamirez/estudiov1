<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type ClienteFormData } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { 
    ArrowLeft, 
    Save, 
    User, 
    Mail, 
    Phone, 
    MapPin, 
    FileText,
    CheckCircle,
    XCircle
} from 'lucide-vue-next';
import { ref } from 'vue';

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
        title: 'Nuevo Cliente',
        href: '/clientes/create',
    },
];

const form = useForm({
    nombre: '',
    email: '',
    telefono: '',
    direccion: '',
    estado: 'activo',
    observaciones: '',
});

const isSubmitting = ref(false);

const submit = () => {
    isSubmitting.value = true;
    form.post('/clientes', {
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
</script>

<template>
    <Head title="Nuevo Cliente" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-foreground">
                        Nuevo Cliente
                    </h1>
                    <p class="text-muted-foreground">
                        Agrega un nuevo cliente a tu base de datos
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
                </div>
            </div>

            <!-- Form -->
            <div class="mx-auto w-full max-w-2xl">
                <div class="rounded-lg border bg-card">
                    <div class="p-6">
                        <form @submit.prevent="submit" class="space-y-6">
                            <!-- Nombre -->
                            <div class="space-y-2">
                                <label for="nombre" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Nombre Completo *
                                </label>
                                <div class="relative">
                                    <User class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        id="nombre"
                                        v-model="form.nombre"
                                        type="text"
                                        :class="[
                                            'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.nombre ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="Ingresa el nombre completo"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.nombre" class="text-sm text-red-600">
                                    {{ form.errors.nombre }}
                                </p>
                            </div>

                            <!-- Email -->
                            <div class="space-y-2">
                                <label for="email" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Email *
                                </label>
                                <div class="relative">
                                    <Mail class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        id="email"
                                        v-model="form.email"
                                        type="email"
                                        :class="[
                                            'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.email ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="ejemplo@email.com"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.email" class="text-sm text-red-600">
                                    {{ form.errors.email }}
                                </p>
                            </div>

                            <!-- Teléfono -->
                            <div class="space-y-2">
                                <label for="telefono" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Teléfono *
                                </label>
                                <div class="relative">
                                    <Phone class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        id="telefono"
                                        v-model="form.telefono"
                                        type="tel"
                                        :class="[
                                            'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.telefono ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="+591 63853001"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.telefono" class="text-sm text-red-600">
                                    {{ form.errors.telefono }}
                                </p>
                            </div>

                            <!-- Dirección -->
                            <div class="space-y-2">
                                <label for="direccion" class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70">
                                    Dirección *
                                </label>
                                <div class="relative">
                                    <MapPin class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <input
                                        id="direccion"
                                        v-model="form.direccion"
                                        type="text"
                                        :class="[
                                            'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.direccion ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="Calle, Ciudad, Estado, Código Postal"
                                        required
                                    />
                                </div>
                                <p v-if="form.errors.direccion" class="text-sm text-red-600">
                                    {{ form.errors.direccion }}
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
                                            'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50',
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
                                        rows="4"
                                        :class="[
                                            'flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 pl-10',
                                            form.errors.observaciones ? 'border-red-500 focus-visible:ring-red-500' : ''
                                        ]"
                                        placeholder="Información adicional sobre el cliente..."
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
                                    <XCircle class="mr-2 h-4 w-4" />
                                    Limpiar
                                </button>
                                <button
                                    type="submit"
                                    :disabled="isSubmitting"
                                    class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2"
                                >
                                    <Save v-if="!isSubmitting" class="mr-2 h-4 w-4" />
                                    <div v-else class="mr-2 h-4 w-4 animate-spin rounded-full border-2 border-current border-t-transparent"></div>
                                    {{ isSubmitting ? 'Guardando...' : 'Guardar Cliente' }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template> 