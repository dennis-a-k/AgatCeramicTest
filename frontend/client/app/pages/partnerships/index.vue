<template>
  <div class="scroll-nav">
    <div v-for="section in sectionsData" :key="section.id" class="nav-dot"
      :class="{ active: activeSectionId === section.id }" :data-title="section.title"
      @click="scrollToSection(section.id)"></div>
  </div>

  <header id="mainHeader" :class="{ scrolled: isHeaderScrolled }" ref="mainHeader">
    <div class="header-container">
      <NuxtLink to="/">
        <div class="row align-items-center logo">
          <NuxtImg src="/images/logo.svg" class="col-3 pe-0" alt="logo" style="filter: invert(1) brightness(2)" />
          <h3 class="col-9 m-0">Agat<span>Ceramic</span></h3>
        </div>
      </NuxtLink>
      <a href="#contact" class="btn visible">Присоединиться</a>
    </div>
  </header>

  <section class="fullscreen-section" id="hero">
    <NuxtImg src="/images/page-partnerships/section-1.png" alt="Дизайн интерьера" class="section-bg" />
    <div class="section-overlay"></div>
    <div class="section-content">
      <h1>Превратите ваши проекты в источник дохода</h1>
      <p>
        Присоединяйтесь к сообществу дизайнеров
        <strong>AgatCeramic</strong> и получайте до 15% с каждого заказа ваших
        клиентов
      </p>
      <a href="#contact" class="btn">Начать сотрудничество</a>
      <a href="#commission" class="btn btn-outline">Узнать подробности</a>
    </div>
  </section>

  <section class="fullscreen-section" id="commission">
    <NuxtImg src="/images/page-partnerships/section-2.png" alt="Высокие комиссионные" class="section-bg" />
    <div class="section-overlay"></div>
    <div class="section-content">
      <div class="benefit-card">
        <div class="benefit-icon">
          <i class="fas fa-percentage floating"></i>
        </div>
        <h2>Высокие комиссионные</h2>
        <p>
          Получайте до 15% от суммы заказа вашего клиента. Чем больше вы
          продаете, тем выше ваш процент.
        </p>
        <p>
          Наши топ-партнеры зарабатывают дополнительно от 150 000 ₽ в месяц.
        </p>
        <a href="#contact" class="btn">Хочу такие условия</a>
      </div>
    </div>
  </section>

  <section class="fullscreen-section" id="exclusive">
    <NuxtImg src="/images/page-partnerships/section-3.png" alt="Эксклюзивные материалы" class="section-bg" />
    <div class="section-overlay"></div>
    <div class="section-content">
      <div class="benefit-card">
        <div class="benefit-icon">
          <i class="fas fa-gem floating"></i>
        </div>
        <h2>Эксклюзивные коллекции</h2>
        <p>
          Доступ к ограниченным сериям и новинкам раньше других. Уникальные
          материалы для ваших проектов.
        </p>
        <p>
          Только через наших партнеров-дизайнеров клиенты могут получить эти
          премиальные материалы.
        </p>
        <a href="#contact" class="btn">Получить доступ</a>
      </div>
    </div>
  </section>

  <section class="fullscreen-section" id="manager">
    <NuxtImg src="/images/page-partnerships/section-4.png" alt="Персональный менеджер" class="section-bg" />
    <div class="section-overlay"></div>
    <div class="section-content">
      <div class="benefit-card">
        <div class="benefit-icon">
          <i class="fas fa-user-tie floating"></i>
        </div>
        <h2>Персональный менеджер</h2>
        <p>
          Выделенный специалист, который поможет с подбором материалов,
          оформлением заказов и решением любых вопросов.
        </p>
        <p>Ваш менеджер знает все о ваших клиентах и предпочтениях.</p>
        <a href="#contact" class="btn">Хочу менеджера</a>
      </div>
    </div>
  </section>

  <section class="fullscreen-section" id="content">
    <NuxtImg src="/images/page-partnerships/section-5.png" alt="Готовый контент" class="section-bg" />
    <div class="section-overlay"></div>
    <div class="section-content">
      <div class="benefit-card">
        <div class="benefit-icon">
          <i class="fas fa-photo-video floating"></i>
        </div>
        <h2>Готовый профессиональный контент</h2>
        <p>
          HD фото, 3D-визуализации и технические спецификации всех коллекций для
          ваших презентаций.
        </p>
        <p>Экономьте время на создание материалов для клиентов.</p>
        <a href="#contact" class="btn">Получить материалы</a>
      </div>
    </div>
  </section>

  <section class="fullscreen-section" id="contact">
    <NuxtImg src="/images/page-partnerships/section-6.png" alt="Присоединиться" class="section-bg" />
    <div class="section-overlay"></div>
    <div class="section-content">
      <form class="contact-form" id="partnerForm" ref="partnerForm" @submit.prevent="handleSubmit">
        <h2 style="text-align: center">
          Стать партнером
          <span style="color: #b8860b">AgatCeramic</span>
        </h2>
        <p style="text-align: center">
          Заполните форму, и наш менеджер свяжется с вами для обсуждения условий
          сотрудничества
        </p>

        <div class="form-group">
          <label for="name">Ваше имя</label>
          <input type="text" id="name" name="name" required />
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required />
        </div>

        <div class="form-group">
          <label for="phone">Телефон</label>
          <input type="tel" id="phone" name="phone" required />
        </div>

        <button type="submit" class="btn">Отправить заявку</button>
      </form>
    </div>
  </section>

  <footer>
    <p>&copy; 2025 AgatCeramic. Все права защищены.</p>
    <p>Сотрудничество с лучшими дизайнерами интерьеров</p>
  </footer>

  <div id="successModal" class="modal" :class="{ show: showSuccessModal }" ref="successModal">
    <div class="modal-content">
      <span class="close-modal" @click="closeModalHandler">&times;</span>
      <h3>Спасибо за вашу заявку!</h3>
      <p>Наш менеджер свяжется с вами в ближайшее время.</p>
    </div>
  </div>

  <div id="loader" class="loader" :style="{ display: showLoader ? 'flex' : 'none' }" ref="loader">
    <div class="spinner"></div>
    <p>Отправка заявки...</p>
  </div>
</template>

<script setup>
import { useRuntimeConfig } from '#imports'

const config = useRuntimeConfig()
const siteUrl = config.public.siteUrl || 'https://agatceramic.ru'

useHead({
  title: 'Партнерство с дизайнерами - AgatCeramic',
  meta: [
    {
      name: 'description',
      content: 'Станьте партнером AgatCeramic и получайте до 15% комиссионных с заказов клиентов. Эксклюзивные условия, широкий ассортимент керамической плитки и керамогранита, профессиональная поддержка. Увеличьте возможности ваших проектов с нами!'
    },
    {
      name: 'keywords',
      content: 'партнерство, дизайнеры, комиссионные, эксклюзивные коллекции, персональный менеджер, AgatCeramic'
    },
    {
      property: 'og:image',
      content: `${siteUrl}/images/stock/logo.png`
    },
    {
      property: 'og:title',
      content: 'AgatCeramic - Интернет-магазин плитки, керамогранита и сантехники'
    },
    {
      property: 'og:description',
      content: 'Станьте партнером AgatCeramic и получайте до 15% комиссионных с заказов клиентов. Эксклюзивные условия, широкий ассортимент керамической плитки и керамогранита, профессиональная поддержка. Увеличьте возможности ваших проектов с нами!'
    },
    {
      property: 'og:url',
      content: `${siteUrl}/partnerships`
    },
    {
      name: 'twitter:image',
      content: `${siteUrl}/images/stock/logo.png`
    }
  ],
  link: [
    {
      rel: 'canonical',
      href: `${siteUrl}/partnerships`
    }
  ],
  script: [{
    type: 'application/ld+json',
    children: JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Organization',
      name: 'AgatCeramic',
      url: siteUrl,
      logo: `${siteUrl}/images/stock/logo.png`,
      description: 'Интернет-магазин плитки, керамогранита и сантехники',
      email: 'zakaz@agatceramic.ru',
      contactPoint: {
        '@type': 'ContactPoint',
        telephone: '+7 (999) 999-99-99',
        contactType: 'customer service'
      }
    })
  }]
})
import { ref, onMounted, onUnmounted } from 'vue';

definePageMeta({
  layout: 'partnerships',
});

// Reactive variables for DOM elements
const mainHeader = ref(null);
const partnerForm = ref(null);
const successModal = ref(null);
const loader = ref(null);

// Reactive variables for state management
const isHeaderScrolled = ref(false);
const showSuccessModal = ref(false);
const showLoader = ref(false);
const activeSectionId = ref('hero'); // Initial active section ID

const sectionsData = [
  { id: 'hero', title: 'Главная' },
  { id: 'commission', title: 'Высокие комиссии' },
  { id: 'exclusive', title: 'Эксклюзивные материалы' },
  { id: 'manager', title: 'Персональный менеджер' },
  { id: 'content', title: 'Готовый контент' },
  { id: 'contact', title: 'Присоединиться' },
];

// Intersection Observer for animations
let observer = null;

const handleScroll = () => {
  isHeaderScrolled.value = window.scrollY > 50;
};

const scrollToSection = (sectionId) => {
  const section = document.getElementById(sectionId);
  if (section) {
    window.scrollTo({
      top: section.offsetTop,
      behavior: 'smooth',
    });
  }
};

const handleSubmit = async (e) => {
  e.preventDefault();
  showLoader.value = true;

  try {
    const formData = new FormData(partnerForm.value);
    // Assuming CSRF token is available via a meta tag or Nuxt configuration
    const csrfToken = document.querySelector('meta[name="csrf-token"]')
      ? document.querySelector('meta[name="csrf-token"]').content
      : '';

    const response = await fetch('/partnerships-call', {
      method: 'POST',
      body: formData,
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        Accept: 'application/json',
      },
    });

    const data = await response.json();

    if (data.success) {
      showSuccessModal.value = true;
      partnerForm.value.reset();
      window.scrollTo({
        top: 0,
        behavior: 'smooth',
      });
    } else {
      alert(
        'Ошибка при отправке формы: ' + (data.message || 'Попробуйте снова')
      );
    }
  } catch (error) {
    console.error('Ошибка:', error);
    alert('Сетевая ошибка. Проверьте соединение.');
  } finally {
    showLoader.value = false;
  }
};

const closeModalHandler = () => {
  showSuccessModal.value = false;
};

// Close when clicking outside modal
const handleWindowClick = (e) => {
  if (successModal.value && e.target === successModal.value) {
    showSuccessModal.value = false;
  }
};

onMounted(() => {
  // Set up Intersection Observer for sections
  const sections = document.querySelectorAll('.fullscreen-section');
  const observerOptions = {
    threshold: 0.1,
  };

  observer = new IntersectionObserver((entries) => {
    entries.forEach((entry) => {
      if (entry.isIntersecting) {
        activeSectionId.value = entry.target.id;

        // Apply animations directly as classes
        const content = entry.target.querySelector('.section-content');
        if (content) content.classList.add('visible');

        const buttons = entry.target.querySelectorAll('.btn');
        buttons.forEach((btn) => {
          if (!btn.classList.contains('visible')) {
            btn.classList.add('visible');
          }
        });

        const bg = entry.target.querySelector('.section-bg');
        if (bg && !bg.classList.contains('zoomed')) {
          bg.classList.add('zoomed');
        }

        const cards = entry.target.querySelectorAll(
          '.benefit-card, .contact-form'
        );
        cards.forEach((card) => {
          if (!card.classList.contains('visible')) {
            card.classList.add('visible');
          }
        });
      }
    });
  }, observerOptions);

  sections.forEach((section) => {
    observer.observe(section);
  });

  // Header scroll effect listener
  window.addEventListener('scroll', handleScroll);

  // Initial animation for the first section
  const firstSection = document.querySelector('#hero'); // Target specific first section
  if (firstSection) {
    firstSection.querySelector('.section-content')?.classList.add('visible');
    firstSection.querySelector('.section-bg')?.classList.add('zoomed');
    firstSection
      .querySelectorAll('.btn')
      .forEach((btn) => btn.classList.add('visible'));
    firstSection
      .querySelectorAll('.benefit-card, .contact-form')
      .forEach((card) => card.classList.add('visible'));
  }

  // Event listener for closing modal by clicking outside
  window.addEventListener('click', handleWindowClick);
});

onUnmounted(() => {
  if (observer) {
    observer.disconnect();
  }
  window.removeEventListener('scroll', handleScroll);
  window.removeEventListener('click', handleWindowClick);
});
</script>

<style scoped>
@import '~/assets/css/font-awesome/css/all.min.css';
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap');

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Montserrat', sans-serif;
  color: #333;
  line-height: 1.6;
  overflow-x: hidden;
  scroll-behavior: smooth;
}

a {
  text-decoration: none;
}

.fullscreen-section {
  height: 100vh;
  width: 100%;
  position: relative;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
}

.section-content {
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 40px;
  position: relative;
  z-index: 2;
  opacity: 0;
  transform: translateY(30px);
  transition: all 0.8s ease-out;
}

.section-content.visible {
  opacity: 1;
  transform: translateY(0);
}

h1,
h2,
h3 {
  font-family: 'Playfair Display', serif;
  font-weight: 700;
}

.btn {
  display: inline-block;
  background-color: #b8860b;
  color: #fff;
  padding: 15px 35px;
  border-radius: 30px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.4s;
  border: none;
  cursor: pointer;
  font-size: 16px;
  margin-top: 20px;
  transform: scale(0.95);
  opacity: 0;
  transition: all 0.6s ease-out 0.3s;
  width: auto;
  height: auto;
}

.btn.visible {
  transform: scale(1);
  opacity: 1;
}

.btn:hover {
  background-color: #a67c10;
  transform: scale(1.05);
}

.btn-outline {
  background-color: transparent;
  border: 2px solid #fff;
  margin-left: 15px;
}

.btn-outline:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.section-bg {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  z-index: 0;
  transform: scale(1.1);
  transition: transform 8s ease-out;
}

.section-bg.zoomed {
  transform: scale(1);
}

.section-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(0, 0, 0, 0.4);
  z-index: 1;
}

/* Navigation */
.scroll-nav {
  position: fixed;
  right: 30px;
  top: 50%;
  transform: translateY(-50%);
  z-index: 1000;
  display: flex;
  flex-direction: column;
}

.nav-dot {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background-color: rgba(255, 255, 255, 0.5);
  margin: 10px 0;
  cursor: pointer;
  transition: all 0.3s;
  position: relative;
}

.nav-dot.active {
  background-color: #b8860b;
  transform: scale(1.3);
}

.nav-dot::after {
  content: attr(data-title);
  position: absolute;
  right: 25px;
  /* Изменено с left на right */
  top: 50%;
  transform: translateY(-50%);
  white-space: nowrap;
  color: white;
  font-size: 14px;
  background-color: rgba(42, 42, 42, 0.8);
  padding: 5px 10px;
  border-radius: 4px;
  opacity: 0;
  transition: opacity 0.3s;
  pointer-events: none;
}

.nav-dot:hover::after {
  opacity: 1;
}

/* Header */
header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  padding: 20px 0;
  z-index: 1000;
  transition: all 0.3s;
  background: none;
}

header.scrolled {
  background-color: rgba(42, 42, 42, 0.9);
  padding: 15px 0;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.header-container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
  padding: 0 40px;
}

.logo h3 {
  font-family: 'Poppins', serif;
  font-size: 24px;
  font-weight: 700;
  color: #fff;
  transition: all 0.3s;
  padding-left: 10px;
}

header.scrolled .logo {
  color: #fff;
}

.logo span {
  color: #b8860b;
}

.cta-btn {
  background-color: #b8860b;
  color: #fff;
  padding: 10px 25px;
  border-radius: 30px;
  text-decoration: none;
  font-weight: 600;
  transition: all 0.3s;
}

.cta-btn:hover {
  background-color: #a67c10;
  transform: translateY(-2px);
}

/* Specific sections */
#hero {
  color: #fff;
  text-align: center;
}

#hero h1 {
  font-size: 64px;
  margin-bottom: 20px;
  line-height: 1.2;
  color: #fff;
}

#hero p {
  font-size: 22px;
  max-width: 800px;
  margin: 0 auto 30px;
}

.benefit-card {
  background-color: rgba(255, 255, 255, 0.9);
  border-radius: 15px;
  padding: 40px;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
  max-width: 500px;
  margin: 0 auto;
  transform: translateX(-50px);
  opacity: 0;
  transition: all 0.8s ease-out;
}

.benefit-card.visible {
  transform: translateX(0);
  opacity: 1;
}

.benefit-card h2 {
  font-size: 36px;
  margin-bottom: 20px;
  color: #2a2a2a;
}

.benefit-card p {
  font-size: 18px;
  color: #555;
  margin-bottom: 20px;
}

.benefit-icon {
  font-size: 50px;
  color: #b8860b;
  margin-bottom: 20px;
}

/* Form section */
#contact {
  background-color: #f9f9f9;
  color: #333;
}

#contact .section-overlay {
  background: rgba(42, 42, 42, 0.7);
}

.contact-form {
  background-color: #fff;
  padding: 50px;
  border-radius: 15px;
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
  max-width: 600px;
  margin: 0 auto;
  transform: scale(0.9);
  opacity: 0;
  transition: all 0.8s ease-out;
}

.contact-form.visible {
  transform: scale(1);
  opacity: 1;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  margin-bottom: 8px;
  font-weight: 500;
}

.form-group input,
.form-group textarea,
.form-group select {
  width: 100%;
  padding: 15px;
  border: 1px solid #ddd;
  border-radius: 8px;
  font-family: 'Montserrat', sans-serif;
  font-size: 16px;
  transition: all 0.3s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
  border-color: #b8860b;
  outline: none;
  box-shadow: 0 0 0 3px rgba(184, 134, 11, 0.2);
}

/* Footer */
footer {
  background-color: #2a2a2a;
  color: #fff;
  padding: 30px 0;
  text-align: center;
}

/* Модальное окно */
.modal {
  display: none;
  position: fixed;
  z-index: 2000;
  left: 0;
  top: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  opacity: 0;
  transition: opacity 0.3s;
}

.modal.show {
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 1;
}

.modal-content {
  background-color: #fff;
  padding: 40px;
  border-radius: 15px;
  max-width: 500px;
  width: 90%;
  text-align: center;
  position: relative;
  transform: translateY(-20px);
  transition: transform 0.3s;
}

.modal.show .modal-content {
  transform: translateY(0);
}

.modal h3 {
  color: #b8860b;
  margin-bottom: 20px;
  font-size: 24px;
}

.modal p {
  font-size: 18px;
  color: #555;
}

.close-modal {
  position: absolute;
  top: 15px;
  right: 15px;
  font-size: 24px;
  color: #aaa;
  cursor: pointer;
  transition: color 0.3s;
}

.close-modal:hover {
  color: #333;
}

/* Animations */
@keyframes float {

  0%,
  100% {
    transform: translateY(0);
  }

  50% {
    transform: translateY(-10px);
  }
}

.floating {
  animation: float 4s ease-in-out infinite;
}

/* Responsive */
@media (max-width: 992px) {
  #hero h1 {
    font-size: 48px;
  }

  #hero p {
    font-size: 18px;
  }

  .benefit-card {
    padding: 30px;
  }

  .benefit-card h2 {
    font-size: 28px;
  }
}

@media (max-width: 768px) {
  #hero h1 {
    font-size: 36px;
  }

  .scroll-nav {
    right: 15px;
  }

  .header-container {
    padding: 0 20px;
  }

  .section-content {
    padding: 0 20px;
  }

  .btn {
    display: block;
    width: 100%;
    margin-bottom: 15px;
  }

  .btn-outline {
    margin-left: 0;
  }

  .scroll-nav {
    right: 15px;
  }

  .nav-dot::after {
    right: 20px;
    /* Уменьшаем отступ для мобильных */
  }
}

@media (max-width: 576px) {
  #hero h1 {
    font-size: 28px;
  }

  .benefit-card {
    padding: 20px;
  }

  .contact-form {
    padding: 30px 20px;
  }
}

/* Стили для прелоадера */
.loader {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  z-index: 9999;
}

.loader .spinner {
  width: 50px;
  height: 50px;
  border: 5px solid #f3f3f3;
  border-top: 5px solid #b8860b;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 20px;
}

.loader p {
  color: white;
  font-size: 18px;
  text-align: center;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}
</style>
