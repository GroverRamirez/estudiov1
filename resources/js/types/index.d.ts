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

// Trabajo types
export interface Trabajo {
    idTrabajo: number;
    titulo: string;
    descripcion: string;
    fecha_inicio: string;
    fecha_entrega: string;
    estado: string;
    prioridad: 'baja' | 'media' | 'alta' | 'urgente';
    precio_total: number;
    adelanto: number;
    saldo_pendiente: number;
    observaciones?: string;
    idCliente: number;
    idServicio: number;
    idUsuario: number;
    created_at: string;
    updated_at: string;
    // Relaciones
    cliente?: Cliente;
    servicio?: Servicio;
    usuario?: User;

}

export interface TrabajoFormData {
    titulo: string;
    descripcion: string;
    fecha_inicio: string;
    fecha_entrega: string;
    estado: string;
    prioridad: 'baja' | 'media' | 'alta' | 'urgente';
    precio_total: number;
    adelanto: number;
    observaciones?: string;
    idCliente: number;
    idServicio: number;
}



export interface TrabajosPageProps {
    trabajos: {
        data: Trabajo[];
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
        prioridad?: string;
        cliente_id?: number;
        servicio_id?: number;
        sort_by?: string;
        sort_direction?: 'asc' | 'desc';
    };
    clientes?: Cliente[];
    servicios?: Servicio[];
    estados?: Record<string, string>;
}

// Pago types
export interface Pago {
    idPago: number;
    monto: number;
    fecha_pago: string;
    metodo_pago: 'efectivo' | 'tarjeta' | 'transferencia' | 'deposito' | 'otro';
    referencia?: string;
    estado: 'completado' | 'pendiente' | 'cancelado' | 'reembolsado';
    tipo_pago: 'adelanto' | 'pago_parcial' | 'pago_completo' | 'reembolso';
    observaciones?: string;
    idTrabajo: number;
    idUsuario: number;
    created_at: string;
    updated_at: string;
    // Relaciones
    trabajo?: Trabajo;
    usuario?: User;
}

export interface PagoFormData {
    monto: number;
    fecha_pago: string;
    metodo_pago: 'efectivo' | 'tarjeta' | 'transferencia' | 'deposito' | 'otro';
    referencia?: string;
    estado: 'completado' | 'pendiente' | 'cancelado' | 'reembolsado';
    tipo_pago: 'adelanto' | 'pago_parcial' | 'pago_completo' | 'reembolso';
    observaciones?: string;
    idTrabajo: number;
}

export interface MetodoPago {
    id: string;
    nombre: string;
    descripcion: string;
    icono: string;
    color: string;
}



export interface TipoPago {
    id: string;
    nombre: string;
    descripcion: string;
    color: string;
}

export interface PagosPageProps {
    pagos: {
        data: Pago[];
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
        metodo_pago?: string;
        tipo_pago?: string;
        fecha_desde?: string;
        fecha_hasta?: string;
        trabajo_id?: number;
        sort_by?: string;
        sort_direction?: 'asc' | 'desc';
    };
    trabajos?: Trabajo[];
    estadisticas?: {
        total_pagado: number;
        total_pendiente: number;
        total_cancelado: number;
        total_reembolsado: number;
        pagos_hoy: number;
        pagos_mes: number;
    };
}
