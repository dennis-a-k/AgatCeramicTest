<template>
  <aside :class="[
    'fixed mt-16 flex flex-col lg:mt-0 top-0 px-5 left-0 bg-white dark:bg-gray-900 dark:border-gray-800 text-gray-900 h-screen transition-all duration-300 ease-in-out z-99999 border-r border-gray-200',
    {
      'lg:w-[290px]': isExpanded || isMobileOpen || isHovered,
      'lg:w-[90px]': !isExpanded && !isHovered,
      'translate-x-0 w-[290px]': isMobileOpen,
      '-translate-x-full': !isMobileOpen,
      'lg:translate-x-0': true,
    },
  ]" @mouseenter="!isExpanded && (isHovered = true)" @mouseleave="isHovered = false">
    <div :class="[
      'py-8 flex',
      !isExpanded && !isHovered ? 'lg:justify-center' : 'justify-start',
    ]">
      <router-link to="/" class="flex flex-row items-center">
        <img v-if="isExpanded || isHovered || isMobileOpen" class="dark:hidden mr-2" src="/images/logo/logo.svg"
          alt="Logo" width="50" height="50" />
        <div v-if="isExpanded || isHovered || isMobileOpen" class="dark:hidden text-logo font-bold">
          <p>Agat<span class="text-gray-400">Ceramic</span></p>
          <p class="text-theme-xs text-end">Админ-панель</p>
        </div>
        <img v-if="isExpanded || isHovered || isMobileOpen" class="hidden dark:block mr-2" src="/images/logo/logo.svg"
          style="filter: invert(1) brightness(2)" alt="Logo" width="50" height="50" />
        <div v-if="isExpanded || isHovered || isMobileOpen" class="hidden dark:block text-logo font-bold">
          <p class="text-white">Agat<span class="text-gray-400">Ceramic</span></p>
          <p class="text-white text-theme-xs text-end">Админ-панель</p>
        </div>
        <img v-if="!isMobileOpen && !(isExpanded || isHovered)" class="dark:hidden" src="/images/logo/logo.svg"
          alt="Logo" width="32" height="32" />
        <img v-if="!isMobileOpen && !(isExpanded || isHovered)" class="hidden dark:block" src="/images/logo/logo.svg"
          style="filter: invert(1) brightness(2)" alt="Logo" width="32" height="32" />
      </router-link>
    </div>
    <div class="flex flex-col overflow-y-auto duration-300 ease-linear no-scrollbar">
      <nav class="mb-6">
        <div class="flex flex-col gap-4">
          <div v-for="(menuGroup, groupIndex) in menuGroups" :key="groupIndex">
            <h2 :class="[
              'mb-4 text-xs uppercase flex leading-[20px] text-gray-400',
              !isExpanded && !isHovered
                ? 'lg:justify-center'
                : 'justify-start',
            ]">
              <template v-if="isExpanded || isHovered || isMobileOpen">
                {{ menuGroup.title }}
              </template>
              <HorizontalDots v-else />
            </h2>
            <ul class="flex flex-col gap-4">
              <li v-for="(item, index) in menuGroup.items" :key="item.name">
                <button v-if="item.subItems" @click="toggleSubmenu(groupIndex, index)" :class="[
                  'menu-item group w-full',
                  {
                    'menu-item-active': isSubmenuOpen(groupIndex, index),
                    'menu-item-inactive': !isSubmenuOpen(groupIndex, index),
                  },
                  !isExpanded && !isHovered
                    ? 'lg:justify-center'
                    : 'lg:justify-start',
                ]">
                  <span :class="[
                    isSubmenuOpen(groupIndex, index)
                      ? 'menu-item-icon-active'
                      : 'menu-item-icon-inactive',
                  ]">
                    <component :is="item.icon" />
                  </span>
                  <span v-if="isExpanded || isHovered || isMobileOpen" class="menu-item-text">{{ item.name }}</span>
                  <ChevronDownIcon v-if="isExpanded || isHovered || isMobileOpen" :class="[
                    'ml-auto w-5 h-5 transition-transform duration-200',
                    {
                      'rotate-180 text-brand-500': isSubmenuOpen(
                        groupIndex,
                        index
                      ),
                    },
                  ]" />
                </button>
                <router-link v-else-if="item.path" :to="item.path" :class="[
                  'menu-item group',
                  {
                    'menu-item-active': isActive(item.path),
                    'menu-item-inactive': !isActive(item.path),
                  },
                ]">
                  <span :class="[
                    isActive(item.path)
                      ? 'menu-item-icon-active'
                      : 'menu-item-icon-inactive',
                  ]">
                    <component :is="item.icon" />
                  </span>
                  <span v-if="isExpanded || isHovered || isMobileOpen" class="menu-item-text">{{ item.name }}</span>
                </router-link>
                <transition @enter="startTransition" @after-enter="endTransition" @before-leave="startTransition"
                  @after-leave="endTransition">
                  <div v-show="isSubmenuOpen(groupIndex, index) &&
                    (isExpanded || isHovered || isMobileOpen)
                    ">
                    <ul class="mt-2 space-y-1 ml-9">
                      <li v-for="subItem in item.subItems" :key="subItem.name">
                        <router-link :to="subItem.path" :class="[
                          'menu-dropdown-item',
                          {
                            'menu-dropdown-item-active': isActive(
                              subItem.path
                            ),
                            'menu-dropdown-item-inactive': !isActive(
                              subItem.path
                            ),
                          },
                        ]">
                          {{ subItem.name }}
                          <span class="flex items-center gap-1 ml-auto">
                            <span v-if="subItem.new" :class="[
                              'menu-dropdown-badge',
                              {
                                'menu-dropdown-badge-active': isActive(
                                  subItem.path
                                ),
                                'menu-dropdown-badge-inactive': !isActive(
                                  subItem.path
                                ),
                              },
                            ]">
                              new
                            </span>
                            <span v-if="subItem.pro" :class="[
                              'menu-dropdown-badge',
                              {
                                'menu-dropdown-badge-active': isActive(
                                  subItem.path
                                ),
                                'menu-dropdown-badge-inactive': !isActive(
                                  subItem.path
                                ),
                              },
                            ]">
                              pro
                            </span>
                          </span>
                        </router-link>
                      </li>
                    </ul>
                  </div>
                </transition>
              </li>
            </ul>
          </div>
        </div>
      </nav>

    </div>
  </aside>
</template>

<script setup>
import { computed, ref } from "vue";
import { useRoute } from "vue-router";

import {
  GridIcon,
  ChevronDownIcon,
  HorizontalDots,
  PackageIcon,
  PhoneCallIcon,
  SquareChartGanttIcon,
  ShoppingCartIcon,
  SettingsIcon,
} from "../../icons";
import { useSidebar } from "@/composables/useSidebar";

const route = useRoute();

const { isExpanded, isMobileOpen, isHovered, openSubmenu } = useSidebar();

const getUserRole = () => {
  const user = localStorage.getItem('auth_user')
  if (user) {
    try {
      const userData = JSON.parse(user)
      return userData.role
    } catch {
      return null
    }
  }
  return null
}

const userRole = ref(getUserRole())

const allMenuItems = [
  {
    icon: GridIcon,
    name: "Статистика",
    path: "/",
    roles: ['admin', 'moderator', 'user']
  },
  {
    icon: PackageIcon,
    name: "Товары",
    path: "/products",
    roles: ['admin', 'moderator']
  },
  {
    icon: SquareChartGanttIcon,
    name: "Характеричтики",
    path: "/characteristics",
    roles: ['admin']
  },
  {
    icon: ShoppingCartIcon,
    name: "Заказы",
    path: "/orders",
    roles: ['admin', 'moderator']
  },
  {
    icon: PhoneCallIcon,
    name: "Звонки",
    path: "/calls",
    roles: ['admin', 'moderator']
  },
  {
    icon: SettingsIcon,
    name: "Настройки сайта",
    path: "/settings",
    roles: ['admin']
  },
]

const menuGroups = [
  {
    title: "Меню",
    items: allMenuItems.filter(item => item.roles.includes(userRole.value)),
  },
]

const isActive = (path) => route.path === path;

const toggleSubmenu = (groupIndex, itemIndex) => {
  const key = `${groupIndex}-${itemIndex}`;
  openSubmenu.value = openSubmenu.value === key ? null : key;
};

const isAnySubmenuRouteActive = computed(() => {
  return menuGroups.some((group) =>
    group.items.some(
      (item) =>
        item.subItems && item.subItems.some((subItem) => isActive(subItem.path))
    )
  );
});

const isSubmenuOpen = (groupIndex, itemIndex) => {
  const key = `${groupIndex}-${itemIndex}`;
  return (
    openSubmenu.value === key ||
    (isAnySubmenuRouteActive.value &&
      menuGroups[groupIndex].items[itemIndex].subItems?.some((subItem) =>
        isActive(subItem.path)
      ))
  );
};

const startTransition = (el) => {
  el.style.height = "auto";
  const height = el.scrollHeight;
  el.style.height = "0px";
  el.offsetHeight; // force reflow
  el.style.height = height + "px";
};

const endTransition = (el) => {
  el.style.height = "";
};
</script>
