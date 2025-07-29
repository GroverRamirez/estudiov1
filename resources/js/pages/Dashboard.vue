<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6 overflow-x-auto">
            <!-- Header con efecto neón -->
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-primary via-secondary to-accent mb-4">
                    Bienvenido a Foto Estudio "EU"
                </h1>
                <p class="text-muted-foreground text-lg">
                    Sistema de Control y Gestión Digital
                </p>
            </div>

            <!-- Cards de estadísticas con efecto neón -->
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                <div class="card-neon p-6 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-primary/10 neon-glow-primary">
                            <svg class="h-6 w-6 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Total Clientes</p>
                            <p class="text-2xl font-bold text-primary">0</p>
                        </div>
                    </div>
                </div>

                <div class="card-neon p-6 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-secondary/10 neon-glow-secondary">
                            <svg class="h-6 w-6 text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Trabajos Activos</p>
                            <p class="text-2xl font-bold text-secondary">0</p>
                        </div>
                    </div>
                </div>

                <div class="card-neon p-6 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-accent/10 neon-glow-accent">
                            <svg class="h-6 w-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Ingresos del Mes</p>
                            <p class="text-2xl font-bold text-accent">$0</p>
                        </div>
                    </div>
                </div>

                <div class="card-neon p-6 rounded-xl">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-destructive/10">
                            <svg class="h-6 w-6 text-destructive" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Pendientes</p>
                            <p class="text-2xl font-bold text-destructive">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones rápidas -->
            <div class="grid gap-6 md:grid-cols-2">
                <div class="card-neon p-6 rounded-xl">
                    <h3 class="text-lg font-semibold mb-4 text-primary">Acciones Rápidas</h3>
                    <div class="grid gap-3">
                        <a href="/clientes/create" class="btn-neon-primary p-4 rounded-lg text-center font-medium transition-all duration-300">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Nuevo Cliente
                        </a>
                        <a href="/trabajos/create" class="btn-neon-secondary p-4 rounded-lg text-center font-medium transition-all duration-300">
                            <svg class="h-5 w-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Nuevo Trabajo
                        </a>
                    </div>
                </div>

                <div class="card-neon p-6 rounded-xl">
                    <h3 class="text-lg font-semibold mb-4 text-secondary">Actividad Reciente</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3 p-3 rounded-lg bg-muted/50">
                            <div class="w-2 h-2 rounded-full bg-primary"></div>
                            <span class="text-sm text-muted-foreground">No hay actividad reciente</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
