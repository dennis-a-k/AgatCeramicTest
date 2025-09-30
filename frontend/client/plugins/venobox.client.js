import VenoBox from 'venobox';

export default defineNuxtPlugin(() => {
  new VenoBox({
    selector: '.venobox',
    galleries: true,
  });
});