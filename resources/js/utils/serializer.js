/**
 * Component Serializer
 *
 * Handles client-side serialization of components.
 */

/**
 * Serialize component data to JSON.
 */
export function serialize(component) {
    return JSON.stringify(component);
}

/**
 * Deserialize JSON to component data.
 */
export function deserialize(json) {
    try {
        return JSON.parse(json);
    } catch (error) {
        console.error('Failed to deserialize component:', error);
        return null;
    }
}

/**
 * Deep clone a component object.
 */
export function clone(component) {
    return deserialize(serialize(component));
}

/**
 * Merge component props.
 */
export function mergeProps(target, source) {
    return Object.assign({}, target, source);
}

/**
 * Extract component data from DOM element.
 */
export function extractFromElement(element) {
    const propsAttr = element.getAttribute('data-laravilt-props');
    return propsAttr ? deserialize(propsAttr) : null;
}
