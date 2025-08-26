export default defineNuxtPlugin(async () => {
  const runtimeConfig = useRuntimeConfig();
  const apiBase = runtimeConfig.public.apiBase;

  if (process.client && !document.cookie.includes('XSRF-TOKEN')) {
    try {
      await $fetch(`${apiBase}/sanctum/csrf-cookie`, {
        method: 'GET',
        credentials: 'include',
      });
      console.log('CSRF cookie obtained.');
    } catch (error) {
      console.error('Failed to obtain CSRF cookie:', error);
    }
  }
});
