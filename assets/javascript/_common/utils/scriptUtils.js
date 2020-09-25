/**
 * Inject un script dans <head>...</head>
 * @param script_src source
 * @param callback fonction callback
 * @param async asychrone ?
 */
export function injectHeadScript(script_src, callback, async = true) {
    const script = document.createElement('script');
    script.src = script_src;
    if (callback) {
        script.onload = callback;
    }
    script.async = async;
    document.head.appendChild(script);
}