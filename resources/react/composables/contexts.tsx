import { createContext, useContext } from 'react';

/**
 * Validation errors, keyed by field name.
 * React twin of the Vue `provide('errors', …)` / `inject('errors')` pair.
 */
export type FieldErrors = Record<string, string | string[]>;

export const ErrorsContext = createContext<FieldErrors>({});

export function useErrors(): FieldErrors {
    return useContext(ErrorsContext);
}

/**
 * First error message for a field, or null.
 */
export function useFieldError(name: string | undefined): string | null {
    const errors = useErrors();

    if (!name || errors[name] === undefined) {
        return null;
    }

    const error = errors[name];

    return Array.isArray(error) ? (error[0] ?? null) : error;
}

/**
 * Values the Vue Schema component provides to every nested field
 * (`getFormData`, `validateForm`, `updateSchema`, `schemaId`, `formController`, `formMethod`).
 */
export interface SchemaContextValue {
    getFormData?: () => Record<string, any>;
    validateForm?: () => boolean;
    updateSchema?: (schema: any[]) => void;
    schemaId?: string | null;
    formController?: string;
    formMethod?: string;
}

export const SchemaContext = createContext<SchemaContextValue>({});

export function useSchemaContext(): SchemaContextValue {
    return useContext(SchemaContext);
}

/**
 * Id of the nearest form root (Form, or the root Schema). ActionButton tags its
 * `action-updated-data` window event with it (`event.laraviltFormScope`) so only that
 * form merges the updated data. React twin of Vue `provide('laravilt:form-scope', id)`.
 */
export const FormScopeContext = createContext<string | null>(null);

export function useFormScope(): string | null {
    return useContext(FormScopeContext);
}

let formScopeCounter = 0;

/**
 * New unique form scope id (call once per form root, e.g. in a `useState` initializer).
 */
export function createFormScopeId(prefix: string = 'form'): string {
    formScopeCounter += 1;

    return `laravilt-${prefix}-${formScopeCounter}`;
}
