export class EventHelper {
    public static onEnter(callback: (event: Event) => void) {
        return (event: KeyboardEvent) => {
            if (event.key === 'Enter') {
                callback(event);
            }
        }
    }
}
