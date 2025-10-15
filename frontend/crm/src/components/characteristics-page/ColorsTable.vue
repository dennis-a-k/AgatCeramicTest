<template>
  <div
    class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
    <div class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Управление цветами</h3>
      </div>
      <div class="flex items-center gap-3">
        <div class="relative flex-1 sm:flex-auto sm:w-auto ">
          <span class="absolute top-1/2 left-4 -translate-y-1/2 text-gray-500 dark:text-gray-400">
            <component :is="searchIcon" />
          </span>
          <input type="text" placeholder="Поиск..." :value="searchQuery"
            @input="emit('update:searchQuery', $event.target.value)"
            class="dark:bg-dark-900 shadow-theme-xs focus:border-brand-300 focus:ring-brand-500/10 dark:focus:border-brand-800 h-11 w-full rounded-lg border border-gray-300 bg-transparent py-2.5 pr-12 pl-11 text-sm text-gray-800 placeholder:text-gray-400 focus:ring-3 focus:outline-hidden dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30">
          <button v-if="searchQuery" @click="clearSearch"
            class="absolute top-1/2 right-2 -translate-y-1/2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl leading-none cursor-pointer bg-white dark:bg-gray-900 px-1 z-10">
            ✕
          </button>
        </div>
        <button type="button"
          class="bg-brand-500 shadow-theme-xs hover:bg-brand-600 inline-flex items-center justify-center gap-2 rounded-lg px-4 py-3 text-sm font-medium text-white transition"
          @click="openCreateModal">
          <component :is="plusIcon" width="20" height="20" />
          Добавить цвет
        </button>
      </div>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
      <table class="min-w-full">
        <thead>
          <tr class="border-t border-gray-100 dark:border-gray-800">
            <th class="py-3 text-left">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Название</p>
            </th>
            <th class="py-3 text-left">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">HEX код </p>
            </th>
            <th class="py-3 text-center">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Предпросмотр</p>
            </th>
            <th class="py-3 text-center">
              <p class="font-bold text-gray-500 text-theme-xs dark:text-gray-400">Действие</p>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="color in colors" :key="color.id" class="border-t border-gray-100 dark:border-gray-800">
            <td class="py-3 whitespace-nowrap">
              <div class="flex items-center gap-3">
                <div>
                  <p class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                    {{ color.name }}
                  </p>
                </div>
              </div>
            </td>
            <td class="py-3 whitespace-nowrap">
              <p class="text-gray-500 text-theme-sm dark:text-gray-400">{{ color.hex }}</p>
            </td>
            <td class="py-3 whitespace-nowrap text-center">
              <div class="color-preview" :style="{ backgroundColor: color.hex }"></div>
            </td>
            <td class="py-3 whitespace-nowrap text-center">
              <div
                class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-success-50 hover:ring-success-300 hover:text-success-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-success-500/15 dark:hover:ring-success-500/50 dark:hover:text-success-500 cursor-pointer p-1 mr-3"
                @click="openEditModal(color)">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M15.0911 2.78206C14.2125 1.90338 12.7878 1.90338 11.9092 2.78206L4.57524 10.116C4.26682 10.4244 4.0547 10.8158 3.96468 11.2426L3.31231 14.3352C3.25997 14.5833 3.33653 14.841 3.51583 15.0203C3.69512 15.1996 3.95286 15.2761 4.20096 15.2238L7.29355 14.5714C7.72031 14.4814 8.11172 14.2693 8.42013 13.9609L15.7541 6.62695C16.6327 5.74827 16.6327 4.32365 15.7541 3.44497L15.0911 2.78206ZM12.9698 3.84272C13.2627 3.54982 13.7376 3.54982 14.0305 3.84272L14.6934 4.50563C14.9863 4.79852 14.9863 5.2734 14.6934 5.56629L14.044 6.21573L12.3204 4.49215L12.9698 3.84272ZM11.2597 5.55281L5.6359 11.1766C5.53309 11.2794 5.46238 11.4099 5.43238 11.5522L5.01758 13.5185L6.98394 13.1037C7.1262 13.0737 7.25666 13.003 7.35947 12.9002L12.9833 7.27639L11.2597 5.55281Z"
                    fill=""></path>
                </svg>
              </div>
              <div
                class="inline-flex items-center justify-center gap-2 rounded-lg transition shadow-theme-xs bg-white text-gray-700 ring-1 ring-gray-300 hover:bg-error-50 hover:ring-error-300 hover:text-error-700 dark:bg-gray-800 dark:text-gray-400 dark:ring-gray-700 dark:hover:bg-error-500/15 dark:hover:ring-error-500/50 dark:hover:text-error-500 cursor-pointer p-1"
                @click="deleteColor(color.id)">
                <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd"
                    d="M6.54142 3.7915C6.54142 2.54886 7.54878 1.5415 8.79142 1.5415H11.2081C12.4507 1.5415 13.4581 2.54886 13.4581 3.7915V4.0415H15.6252H16.666C17.0802 4.0415 17.416 4.37729 17.416 4.7915C17.416 5.20572 17.0802 5.5415 16.666 4.7915H16.3752V8.24638V13.2464V16.2082C16.3752 17.4508 15.3678 18.4582 14.1252 18.4582H5.87516C4.63252 18.4582 3.62516 17.4508 3.62516 16.2082V13.2464V8.24638V5.5415H3.3335C2.91928 5.5415 2.5835 5.20572 2.5835 4.7915C2.5835 4.37729 2.91928 4.0415 3.3335 4.0415H4.37516H6.54142V3.7915ZM14.8752 13.2464V8.24638V5.5415H13.4581H12.7081H7.29142H6.54142H5.12516V8.24638V13.2464V16.2082C5.12516 16.6224 5.46095 16.9582 5.87516 16.9582H14.1252C14.5394 16.9582 14.8752 16.6224 14.8752 16.2082V13.2464ZM8.04142 4.0415H11.9581V3.7915C11.9581 3.37729 11.6223 3.0415 11.2081 3.0415H8.79142C8.37721 3.0415 8.04142 3.37729 8.04142 3.7915V4.0415ZM8.3335 7.99984C8.74771 7.99984 9.0835 8.33562 9.0835 8.74984V13.7498C9.0835 14.1641 8.74771 14.4998 8.3335 14.4998C7.91928 14.4998 7.5835 14.1641 7.5835 13.7498V8.74984C7.5835 8.33562 7.91928 7.99984 8.3335 7.99984ZM12.4168 8.74984C12.4168 8.33562 12.081 7.99984 11.6668 7.99984C11.2526 7.99984 10.9168 8.33562 10.9168 8.74984V13.7498C10.9168 14.1641 11.2526 14.4998 11.6668 14.4998C12.081 14.4998 12.4168 14.1641 12.4168 13.7498V8.74984Z"
                    fill=""></path>
                </svg>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="border-t border-gray-200 py-4 dark:border-gray-800">
      <div class="flex items-center justify-between">
        <button
          class="text-theme-sm shadow-theme-xs flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 sm:px-3.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
          <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
              d="M2.58301 9.99868C2.58272 10.1909 2.65588 10.3833 2.80249 10.53L7.79915 15.5301C8.09194 15.8231 8.56682 15.8233 8.85981 15.5305C9.15281 15.2377 9.15297 14.7629 8.86018 14.4699L5.14009 10.7472L16.6675 10.7472C17.0817 10.7472 17.4175 10.4114 17.4175 9.99715C17.4175 9.58294 17.0817 9.24715 16.6675 9.24715L5.14554 9.24715L8.86017 5.53016C9.15297 5.23717 9.15282 4.7623 8.85983 4.4695C8.56684 4.1767 8.09197 4.17685 7.79917 4.46984L2.84167 9.43049C2.68321 9.568 2.58301 9.77087 2.58301 9.99715C2.58301 9.99766 2.58301 9.99817 2.58301 9.99868Z"
              fill=""></path>
          </svg>
        </button>

        <span class="block text-sm font-medium text-gray-700 sm:hidden dark:text-gray-400">
          1 из 10
        </span>

        <ul class="hidden items-center gap-0.5 sm:flex">
          <li>
            <a href="#"
              class="bg-brand-500/[0.08] text-theme-sm text-brand-500 hover:bg-brand-500/[0.08] hover:text-brand-500 dark:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium">
              1
            </a>
          </li>

          <li>
            <a href="#"
              class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400">
              2
            </a>
          </li>

          <li>
            <a href="#"
              class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400">
              3
            </a>
          </li>

          <li>
            <a href="#"
              class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400">
              ...
            </a>
          </li>

          <li>
            <a href="#"
              class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400">
              8
            </a>
          </li>

          <li>
            <a href="#"
              class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400">
              9
            </a>
          </li>

          <li>
            <a href="#"
              class="text-theme-sm hover:bg-brand-500/[0.08] hover:text-brand-500 dark:hover:text-brand-500 flex h-10 w-10 items-center justify-center rounded-lg font-medium text-gray-700 dark:text-gray-400">
              10
            </a>
          </li>
        </ul>

        <button
          class="text-theme-sm shadow-theme-xs flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-2 py-2 font-medium text-gray-700 hover:bg-gray-50 hover:text-gray-800 sm:px-3.5 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
          <svg class="fill-current" width="20" height="20" viewBox="0 0 20 20" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd"
              d="M17.4175 9.9986C17.4178 10.1909 17.3446 10.3832 17.198 10.53L12.2013 15.5301C11.9085 15.8231 11.4337 15.8233 11.1407 15.5305C10.8477 15.2377 10.8475 14.7629 11.1403 14.4699L14.8604 10.7472L3.33301 10.7472C2.91879 10.7472 2.58301 10.4114 2.58301 9.99715C2.58301 9.58294 2.91879 9.24715 3.33301 9.24715L14.8549 9.24715L11.1403 5.53016C10.8475 5.23717 10.8477 4.7623 11.1407 4.4695C11.4336 4.1767 11.9085 4.17685 12.2013 4.46984L17.1588 9.43049C17.3173 9.568 17.4175 9.77087 17.4175 9.99715C17.4175 9.99763 17.4175 9.99812 17.4175 9.9986Z"
              fill=""></path>
          </svg>
        </button>
      </div>
    </div>
  </div>
  <!-- Create/Edit Modal -->
  <div class="modal fade" :class="{ show: showModal }" :style="{ display: showModal ? 'block' : 'none' }">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">
            {{ isEditing ? 'Редактировать цвет' : 'Создать цвет' }}
          </h5>
          <button type="button" class="close" @click="closeModal">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form @submit.prevent="saveColor">
            <div class="form-group">
              <label for="name">Название</label>
              <input type="text" class="form-control" id="name" v-model="form.name" required />
            </div>
            <div class="form-group">
              <label for="hex">HEX код</label>
              <div class="input-group">
                <input type="text" class="form-control" id="hex" v-model="form.hex" placeholder="#000000"
                  pattern="^#[a-fA-F0-9]{6}$" required />
                <div class="input-group-append">
                  <div class="input-group-text color-preview-input" :style="{ backgroundColor: form.hex }"></div>
                </div>
              </div>
              <small class="form-text text-muted">Введите HEX код цвета в формате #RRGGBB</small>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" @click="closeModal">
                Отмена
              </button>
              <button type="submit" class="btn btn-primary">
                {{ isEditing ? 'Сохранить' : 'Создать' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal Backdrop -->
  <div v-if="showModal" class="modal-backdrop fade show" @click="closeModal"></div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useColors } from '@/composables/useColors'
import { PlusIcon, SearchIcon } from "../../icons";

const plusIcon = PlusIcon
const searchIcon = SearchIcon
const { colors, fetchColors, createColor, updateColor, deleteColor: deleteColorApi } = useColors()

const showModal = ref(false)
const isEditing = ref(false)
const currentColor = ref(null)

const emit = defineEmits(['update:searchQuery'])

const clearSearch = () => {
  emit('update:searchQuery', '')
}

const form = ref({
  name: '',
  hex: '#000000'
})

const openCreateModal = () => {
  isEditing.value = false
  currentColor.value = null
  resetForm()
  showModal.value = true
}

const openEditModal = (color) => {
  isEditing.value = true
  currentColor.value = color
  form.value = {
    name: color.name,
    hex: color.hex
  }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  resetForm()
}

const resetForm = () => {
  form.value = {
    name: '',
    hex: '#000000'
  }
}

const saveColor = async () => {
  try {
    if (isEditing.value) {
      await updateColor(currentColor.value.id, form.value)
    } else {
      await createColor(form.value)
    }

    await fetchColors()
    closeModal()
  } catch (error) {
    console.error('Error saving color:', error)
    alert('Ошибка при сохранении цвета')
  }
}

const deleteColor = async (id) => {
  if (confirm('Вы уверены, что хотите удалить этот цвет?')) {
    try {
      await deleteColorApi(id)
      await fetchColors()
    } catch (error) {
      console.error('Error deleting color:', error)
      alert('Ошибка при удалении цвета')
    }
  }
}

onMounted(async () => {
  await fetchColors()
})
</script>

<style>
.color-preview {
  width: 30px;
  height: 30px;
  border-radius: 50%;
  border: 1px solid #ddd;
  display: inline-block;
}

.color-preview-input {
  width: 40px;
  height: 38px;
  border: 1px solid #ced4da;
  border-left: none;
}

.modal-backdrop {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  z-index: 1040;
}

.modal {
  z-index: 1050;
}
</style>
