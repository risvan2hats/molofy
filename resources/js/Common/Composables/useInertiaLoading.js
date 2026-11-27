import { router } from '@inertiajs/vue3';
import { onUnmounted, ref } from 'vue';

export function useInertiaLoading() {
    const isLoading = ref(false);
    let delayTimer = null;

    const removeStart = router.on('start', () => {
        clearTimeout(delayTimer);
        delayTimer = setTimeout(() => {
            isLoading.value = true;
        }, 120);
    });

    const removeFinish = router.on('finish', () => {
        clearTimeout(delayTimer);
        isLoading.value = false;
    });

    onUnmounted(() => {
        clearTimeout(delayTimer);
        removeStart();
        removeFinish();
    });

    return { isLoading };
}
