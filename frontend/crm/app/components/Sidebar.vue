<template>
  <aside 
    :class="[
      'border-r border-sidebar-border bg-sidebar/95 backdrop-blur-md',
      'transition-all duration-300',
      collapsed ? 'w-16' : 'w-64'
    ]"
  >
    <!-- Шапка сайдбара -->
    <div class="border-b border-sidebar-border p-4 relative">
      <div class="flex items-center gap-3">
        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-sidebar-primary">
          <Store class="h-6 w-6 text-sidebar-primary-foreground" />
        </div>
        <div v-if="!collapsed">
          <h2 class="text-lg font-semibold text-sidebar-foreground">ShopCRM</h2>
          <p class="text-sm text-sidebar-foreground/70">Админ-панель</p>
        </div>
      </div>
      <button
        @click="toggleSidebar"
        class="absolute -right-3 top-6 h-6 w-6 rounded-full border border-sidebar-border bg-sidebar p-0 text-sidebar-foreground hover:bg-sidebar-accent"
      >
        <ChevronLeft :class="['h-4 w-4 transition-transform', collapsed ? 'rotate-180' : '']" />
      </button>
    </div>

    <!-- Контент сайдбара -->
    <div class="p-2">
      <div>
        <div :class="['text-sidebar-foreground/70 text-xs uppercase tracking-wider font-medium px-3 py-2', collapsed ? 'sr-only' : '']">
          Навигация
        </div>
        <div>
          <ul class="space-y-1">
            <li v-for="item in menuItems" :key="item.title">
              <NuxtLink
                :to="item.url"
                :class="[
                  'flex items-center gap-3 px-3 py-3 rounded-lg transition-all duration-200',
                  isActive(item.url) 
                    ? 'bg-sidebar-primary text-sidebar-primary-foreground shadow-lg' 
                    : 'text-sidebar-foreground hover:bg-sidebar-accent hover:text-sidebar-accent-foreground'
                ]"
              >
                <component :is="item.icon" class="h-5 w-5 flex-shrink-0" />
                <span v-if="!collapsed" class="font-medium">{{ item.title }}</span>
              </NuxtLink>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRoute } from 'vue-router';
import {
  LayoutDashboard,
  Package,
  ShoppingCart,
  Users,
  Phone,
  FileText,
  Settings,
  ChevronLeft,
  Store
} from 'lucide-vue-next';

const route = useRoute();
const collapsed = ref(false);

const toggleSidebar = () => {
  collapsed.value = !collapsed.value;
};

const menuItems = [
  { title: "Дашборд", url: "/", icon: LayoutDashboard },
  { title: "Товары", url: "/products", icon: Package },
  { title: "Заказы", url: "/orders", icon: ShoppingCart },
  { title: "Клиенты", url: "/customers", icon: Users },
  { title: "Обратные звонки", url: "/callbacks", icon: Phone },
  { title: "Контент", url: "/content", icon: FileText },
  { title: "Настройки", url: "/settings", icon: Settings },
];

const isActive = (path: string) => {
  if (path === "/") return route.path === "/";
  return route.path.startsWith(path);
};
</script>

<style scoped>
/* Стили остаются аналогичными */
</style>