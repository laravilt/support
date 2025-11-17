/**
 * Simple Event Bus for inter-component communication.
 */
export default class EventBus {
    constructor() {
        this.events = new Map();
    }

    /**
     * Register an event listener.
     *
     * @param {string} event - Event name
     * @param {Function} callback - Callback function
     */
    on(event, callback) {
        if (!this.events.has(event)) {
            this.events.set(event, []);
        }
        this.events.get(event).push(callback);
    }

    /**
     * Remove an event listener.
     *
     * @param {string} event - Event name
     * @param {Function} callback - Callback function to remove
     */
    off(event, callback) {
        if (!this.events.has(event)) {
            return;
        }

        const callbacks = this.events.get(event);
        const index = callbacks.indexOf(callback);

        if (index > -1) {
            callbacks.splice(index, 1);
        }
    }

    /**
     * Emit an event.
     *
     * @param {string} event - Event name
     * @param {*} data - Data to pass to listeners
     */
    emit(event, data) {
        if (!this.events.has(event)) {
            return;
        }

        this.events.get(event).forEach(callback => {
            callback(data);
        });
    }

    /**
     * Clear all listeners for an event.
     *
     * @param {string} event - Event name
     */
    clear(event) {
        this.events.delete(event);
    }

    /**
     * Clear all event listeners.
     */
    clearAll() {
        this.events.clear();
    }
}
