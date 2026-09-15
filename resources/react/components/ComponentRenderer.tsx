export interface ComponentRendererProps {
    componentName: string;
    /** Accepted for API parity; the Vue component does not use it either. */
    componentProps?: Record<string, any>;
    componentKey: string;
    innerHTML?: string;
}

export default function ComponentRenderer({ componentName, componentKey, innerHTML = '' }: ComponentRendererProps) {
    return (
        <div
            data-laravilt-component={componentName}
            data-laravilt-key={componentKey}
            dangerouslySetInnerHTML={{ __html: innerHTML }}
        />
    );
}

export { ComponentRenderer };
