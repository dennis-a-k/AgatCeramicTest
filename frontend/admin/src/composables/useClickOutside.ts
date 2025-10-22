import { ref, onMounted, onBeforeUnmount } from 'vue'

export function useClickOutside(callback: () => void) {
  const elementRef = ref<HTMLElement | null>(null)

  const handleClickOutside = (event: Event) => {
    if (elementRef.value && !elementRef.value.contains(event.target as Node)) {
      callback()
    }
  }

  onMounted(() => {
    document.addEventListener('click', handleClickOutside)
  })

  onBeforeUnmount(() => {
    document.removeEventListener('click', handleClickOutside)
  })

  return elementRef
}
