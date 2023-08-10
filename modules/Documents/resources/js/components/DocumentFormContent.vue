<template>
  <div class="mx-auto max-w-6xl" v-show="visible">
    <div class="mb-8 flex justify-end space-x-2 text-right">
      <IModal
        id="saveAsTemplateModal"
        size="sm"
        @shown="templatesModalShownHandler"
        :ok-title="$t('core::app.save')"
        :ok-disabled="saveTemplateForm.busy"
        static-backdrop
        :title="$t('documents::document.template.save_as_template')"
        form
        @submit="saveContentAsTemplate"
        @keydown="saveTemplateForm.onKeydown($event)"
      >
        <IFormGroup
          label-for="name"
          :label="$t('documents::document.template.name')"
          required
        >
          <IFormInput
            ref="inputNameRef"
            v-model="saveTemplateForm.name"
            id="name"
          />
          <IFormError v-text="saveTemplateForm.getError('name')" />
        </IFormGroup>

        <IFormGroup>
          <IFormCheckbox
            id="is_shared"
            name="is_shared"
            v-model:checked="saveTemplateForm.is_shared"
            :label="$t('documents::document.template.share_with_team_members')"
          />
          <IFormError v-text="saveTemplateForm.getError('is_shared')" />
        </IFormGroup>
      </IModal>

      <IButtonIcon
        @click="showSettings = !showSettings"
        icon="AdjustmentsVertical"
        class="mr-2 mt-2 shrink-0 self-start"
      />

      <IButton
        @click="showSnippets"
        icon="Sparkles"
        size="sm"
        :disabled="document.status === 'accepted'"
        variant="white"
        class="shrink-0 self-start"
        :text="$t('core::contentbuilder.builder.Snippets')"
      />

      <IButton
        v-i-modal="'saveAsTemplateModal'"
        variant="white"
        v-show="form.content"
        size="sm"
        icon="Bookmark"
        class="shrink-0 self-start"
        :text="$t('documents::document.template.save_as_template')"
      />

      <div class="relative w-72">
        <ICustomSelect
          label="name"
          size="sm"
          :clearable="false"
          :loading="templatesAreBeingLoaded"
          :disabled="document.status === 'accepted'"
          :placeholder="$t('documents::document.template.insert_template')"
          :options="templatesForOptions"
          v-model="selectedTemplate"
          @option:selected="templateSelectedHandler"
        />
        <a
          v-show="!templatesAreBeingLoaded"
          href="#"
          @click.prevent.stop="loadTemplates"
          class="absolute right-9 top-2 mt-px text-neutral-400 hover:text-neutral-600 focus:outline-none"
        >
          <Icon icon="Refresh" class="h-4 w-4" />
        </a>
        <router-link
          target="_blank"
          :to="{ name: 'document-templates-index' }"
          class="link inline-flex items-center text-sm"
        >
          {{ $t('documents::document.template.manage') }}
          <Icon icon="ExternalLink" class="ml-2 h-4 w-4" />
        </router-link>
      </div>
    </div>

    <div
      v-show="showSettings"
      class="border-b border-neutral-200 pb-3 dark:border-neutral-700"
    >
      <div class="inline-flex items-center">
        <i18n-t
          scope="global"
          keypath="documents::document.add_pdf_padding"
          class="text-base text-neutral-800 dark:text-neutral-200"
          tag="span"
        >
          <template #px>
            <input
              type="text"
              v-model="form.pdf.padding"
              placeholder="px"
              class="h-6 w-auto max-w-[60px] rounded-sm border-neutral-200 bg-white px-1 text-center text-sm focus:ring-0 dark:border-neutral-500 dark:bg-neutral-600"
            />
          </template>
        </i18n-t>
      </div>
    </div>

    <div class="mt-10">
      <IAlert class="mb-4 mx-4" v-if="displayPlaceholdersMessage">
        {{ $t('documents::document.placeholders_replacement_info') }}
      </IAlert>

      <IAlert class="mb-4 mx-4" v-if="displayProductsMissingMessage">
        <i18n-t
            scope="global"
            :keypath="'documents::document.products_snippet_missing'"
            tag="span"
            class="inline-flex"
          >
            <template #icon>
              <Icon icon="Plus" class="h-5 w-5" />
            </template>
          </i18n-t>
      </IAlert>

      <IAlert class="mb-4 mx-4" v-if="displaySignaturesMissingMessage">
        <i18n-t
            scope="global"
            :keypath="'documents::document.signatures_snippet_missing'"
            tag="span"
            class="inline-flex"
          >
            <template #icon>
              <Icon icon="Plus" class="h-5 w-5" />
            </template>
          </i18n-t>
      </IAlert>

      <div
        class="prose prose-sm prose-neutral relative max-w-none dark:prose-invert"
      >
        <ContentBuilder
          ref="builderRef"
          v-model="form.content"
          :disabled="document.status === 'accepted'"
          :placeholders="placeholders"
        />
      </div>
    </div>
  </div>
</template>
<script setup>
import { ref, computed, watch } from 'vue'
import propsDefinition from './formSectionProps'
import { whenever } from '@vueuse/core'
import find from 'lodash/find'
import omit from 'lodash/omit'
import sortBy from 'lodash/sortBy'
import ContentBuilder from '~/Core/resources/js/components/ContentBuilder/ContentBuilder.vue'
import { addGoogleFontsStyle } from '~/Core/resources/js/components/ContentBuilder/utils'
import { useForm } from '~/Core/resources/js/composables/useForm'
import IAlert from '~/Core/resources/js/components/UI/IAlert.vue'

const props = defineProps({
  ...propsDefinition,
  ...{ isReady: { default: true, type: Boolean } },
})

const builderRef = ref(null)
const inputNameRef = ref(null)
const templates = ref([])
const selectedTemplate = ref(null)
const templatesLoaded = ref(false)
const templatesAreBeingLoaded = ref(false)
const placeholders = Innoclapps.config('documents.placeholders')
const showSettings = ref(false)

const { form: saveTemplateForm } = useForm({
  name: '',
  content: '',
  is_shared: false,
})

watch(
  () => props.document.updated_at,
  () => {
    if (props.visible) {
      addUsedDocumentGoogleFonts()
    }
  }
)

whenever(
  () => props.visible,
  () => {
    !templatesLoaded.value && loadTemplates()
    addUsedDocumentGoogleFonts()
  },
  { immediate: true }
)

const contentHasPlaceholders = computed(
  () => props.form.content && props.form.content.indexOf('{{ ') > -1
)

const contentContainsPlaceholdersFromResources = computed(
  () =>
    props.form.content && props.form.content.match(/(contact.|deal.|company.)/)
)

const hasAssociations = computed(() => {
  let val = false

  Object.keys(props.document.associations || {}).forEach(resource => {
    if (props.document.associations[resource]?.length > 0) {
      val = true
    }
  })

  return val
})

const displayProductsMissingMessage = computed(
  () =>
    props.form.billable.products.length > 0 &&
    (!props.form.content || props.form.content.indexOf('products-section') === -1)
)


const displaySignaturesMissingMessage = computed(
  () =>
    props.form.signers.length > 0 &&
    (!props.form.content || props.form.content.indexOf('signatures-section') === -1)
)

const displayPlaceholdersMessage = computed(
  () =>
    props.isReady &&
    props.document.id &&
    !hasAssociations.value &&
    contentHasPlaceholders.value &&
    contentContainsPlaceholdersFromResources.value
)

// Removes content for performance reasons e.q. avoid watching long contents
const templatesForOptions = computed(() =>
  sortBy(templates.value, ['name', 'asc']).map(t => omit(t, ['content']))
)

function addUsedDocumentGoogleFonts() {
  addGoogleFontsStyle(props.document.google_fonts || [])
}

function showSnippets() {
  builderRef.value.viewSnippets()
}

function templatesModalShownHandler() {
  inputNameRef.value.focus()
  saveTemplateForm.content = props.form.content
}

/**
 * Save the current content as template
 */
function saveContentAsTemplate() {
  saveTemplateForm.post('/document-templates').then(template => {
    templates.value.push(template)
    Innoclapps.modal().hide('saveAsTemplateModal')
  })
}

/**
 * Template selected handler
 */
function templateSelectedHandler(option) {
  addGoogleFontsStyle(option.google_fonts)

  if (props.form.content === null) {
    props.form.content = ''
  }

  let template = find(templates.value, ['id', option.id])
  props.form.content += template.content

  if (template.view_type) {
    props.form.view_type = template.view_type
  }

  setTimeout(() => (selectedTemplate.value = null), 500)
}

/**
 * Fetch the document templates
 */
function loadTemplates() {
  templatesAreBeingLoaded.value = true
  Innoclapps.request()
    .get('document-templates', {
      params: { per_page: 100 },
    })
    .then(({ data: pagination }) => {
      templates.value = pagination.data
      templatesLoaded.value = true
    })
    .finally(() => (templatesAreBeingLoaded.value = false))
}

defineExpose({
  builderRef,
})
</script>
<style>
body:not(.document-section-content) #divSnippetHandle {
  display: none;
}
</style>
