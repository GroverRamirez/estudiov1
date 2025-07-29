<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Pagos',
        href: '/pagos',
    },
];
</script>

<template>
    <Head title="Pagos" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 overflow-x-auto">
            <div class="text-center py-12">
                <h1 class="text-2xl font-bold mb-4">Módulo de Pagos</h1>
                <p class="text-muted-foreground">Próximamente...</p>
            </div>
        </div>
    </AppLayout>
</template>
