import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
}

export type AppPageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
};

export interface User {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

// Cliente types
export interface Cliente {
    idCliente: number;
    nombre: string;
    email: string;
    telefono: string;
    direccion: string;
    fecha_registro: string;
    estado: 'activo' | 'inactivo';
    observaciones?: string;
    created_at: string;
    updated_at: string;
}

export interface ClienteFormData {
    nombre: string;
    email: string;
    telefono: string;
    direccion: string;
    estado: 'activo' | 'inactivo';
    observaciones?: string;
}

export interface ClientesPageProps {
    clientes: {
        data: Cliente[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number;
        to: number;
    };
    filters: {
        search?: string;
        estado?: string;
        sort_by?: string;
        sort_direction?: 'asc' | 'desc';
    };
}

// Servicio types
export interface Servicio {
    idServicio: number;
    nombre: string;
    descripcion: string;
    precio: number;
    categoria: string;
    estado: 'activo' | 'inactivo';
    duracion_estimada?: number; // en minutos
    imagen?: string;
    created_at: string;
    updated_at: string;
}

export interface ServicioFormData {
    nombre: string;
    descripcion: string;
    precio: number;
    categoria: string;
    estado: 'activo' | 'inactivo';
    duracion_estimada?: number;
    imagen?: string;
}

export interface ServiciosPageProps {
    servicios: {
        data: Servicio[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        from: number;
        to: number;
    };
    filters: {
        search?: string;
        categoria?: string;
        estado?: string;
        sort_by?: string;
        sort_direction?: 'asc' | 'desc';
    };
}
