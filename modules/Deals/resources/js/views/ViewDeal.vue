<template>
  <ILayout :overlay="!componentReady">
    <div class="mx-auto max-w-7xl">
      <IAlert
        v-if="componentReady && $gate.denies('view', record)"
        class="mb-6"
        variant="warning"
      >
        {{ $t('core::role.view_non_authorized_after_record_create') }}
      </IAlert>

      <div
        v-if="componentReady"
        class="overflow-hidden rounded-lg border border-neutral-200 bg-white shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
      >
        <div class="bg-white px-3 py-4 dark:bg-neutral-900 sm:p-6">
          <div class="lg:flex lg:items-center lg:justify-between lg:space-x-5">
            <div class="text-center lg:mt-0 lg:text-left">
              <div
                :class="[
                  'font-bold text-neutral-900 dark:text-white lg:flex lg:items-baseline',
                  record.display_name.length > 60 ? 'text-lg' : 'text-2xl',
                ]"
              >
                <IPopover
                  v-if="record.authorizations.update"
                  class="w-72"
                  @show="editName = record.name"
                  @hide="updateForm.errors.clear()"
                  ref="namePopoverRef"
                >
                  <button
                    type="button"
                    class="rounded-md text-left focus:outline-none hover:bg-neutral-100 dark:hover:bg-neutral-700"
                    v-text="record.display_name"
                  />

                  <template #popper>
                    <div class="px-5 py-4">
                      <IFormGroup
                        required
                        :label="$t('deals::fields.deals.name')"
                        label-for="editDealName"
                      >
                        <component
                          :is="
                            record.name.length <= 60
                              ? 'IFormInput'
                              : 'IFormTextarea'
                          "
                          v-model="editName"
                          id="editDealName"
                          class="font-normal"
                          @keydown.enter="updateName"
                          @keydown="updateForm.errors.clear('name')"
                        />

                        <IFormError v-text="updateForm.getError('name')" />
                      </IFormGroup>

                      <div
                        class="-mx-5 -mb-4 mt-4 flex justify-end space-x-1 bg-neutral-100 px-4 py-3 dark:bg-neutral-900"
                      >
                        <IButton
                          size="sm"
                          variant="white"
                          :disabled="updateForm.busy"
                          :text="$t('core::app.cancel')"
                          @click="() => $refs.namePopoverRef.hide()"
                        />
                        <IButton
                          size="sm"
                          variant="primary"
                          :loading="updateForm.busy"
                          :disabled="updateForm.busy || !editName"
                          :text="$t('core::app.save')"
                          @click="updateName"
                        />
                      </div>
                    </div>
                  </template>
                </IPopover>

                <span v-else v-text="record.display_name"></span>
              </div>
              <div
                class="flex flex-col justify-center space-x-3 sm:flex-row lg:mb-1 lg:justify-start"
              >
                <a
                  v-if="record.authorizations.update"
                  href="#"
                  @click.prevent="
                    () => $refs.productsTabRef[0].showProductsDialog()
                  "
                  class="link order-2 text-sm font-normal sm:order-1"
                  v-t="{
                    path: 'billable::product.count',
                    args: { count: totalProducts },
                  }"
                />
                <span
                  v-else
                  class="order-2 text-sm text-neutral-700 dark:text-neutral-200 sm:order-1"
                  v-t="{
                    path: 'billable::product.count',
                    args: { count: totalProducts },
                  }"
                />

                <DealStagePopover
                  :deal-id="record.id"
                  :pipeline-id="record.pipeline_id"
                  :stage-id="record.stage_id"
                  :status="record.status"
                  :authorized-to-update="record.authorizations.update"
                  class="order-1 justify-center text-sm font-medium text-neutral-800 hover:text-neutral-600 dark:text-neutral-200 dark:hover:text-neutral-400 sm:order-2 lg:justify-start"
                />
              </div>
              <p class="text-sm text-neutral-600 dark:text-neutral-300" v-once>
                {{ $t('core::app.created_at') }}
                {{ localizedDateTime(record.created_at) }}
              </p>
            </div>
            <div
              class="mt-3 flex shrink-0 flex-col items-center justify-center lg:mt-0 lg:flex-row lg:space-x-2"
            >
              <DealStatusChange
                v-if="record.authorizations.update"
                class="mr-0 lg:mr-5"
                :deal-id="record.id"
                :deal-status="record.status"
              />

              <div class="mt-3 flex shrink-0 space-x-3 lg:mt-0">
                <FormDropdownSelect
                  v-if="record.authorizations.update"
                  :items="ownerDropdownOptions"
                  :modelValue="record.user_id"
                  value-key="id"
                  label-key="name"
                  @change="update({ user_id: $event.id })"
                >
                  <template #label="{ label }">
                    <span
                      v-if="record.user"
                      class="inline-flex items-center"
                      v-i-tooltip.top="$t('deals::fields.deals.user.name')"
                    >
                      <IAvatar
                        size="xs"
                        class="mr-1.5"
                        :src="record.user.avatar_url"
                      />
                      {{ label }}
                    </span>
                    <span
                      v-else
                      v-t="'core::app.no_owner'"
                      class="text-neutral-500 dark:text-neutral-300"
                    />
                  </template>
                </FormDropdownSelect>
                <p
                  v-else-if="record.user"
                  class="inline-flex items-center text-sm text-neutral-800 dark:text-neutral-200"
                >
                  <IAvatar
                    size="xs"
                    class="mr-1.5"
                    :src="record.user.avatar_url"
                  />
                  {{ record.user.name }}
                </p>

                <Actions
                  type="dropdown"
                  :ids="record.id || []"
                  :actions="record.actions || []"
                  :resource-name="resourceName"
                  @run="handleActionExecuted"
                />

                <IMinimalDropdown
                  v-once
                  v-if="$gate.isSuperAdmin()"
                  placement="bottom-end"
                >
                  <IDropdownItem
                    @click="sidebarBeingManaged = true"
                    :text="$t('core::app.record_view.manage_sidebar')"
                  />
                </IMinimalDropdown>
              </div>
            </div>
          </div>

          <DealMiniPipeline
            :stages="stagesForMiniPipeline"
            :time-in-stages="record.time_in_stages"
            :deal-status="record.status"
            :deal-stage-id="record.stage_id"
            :deal-id="record.id"
            @stage-updated="handleUpdatedEvent"
            class="mt-5"
          />
        </div>
      </div>

      <div class="mt-8" v-if="componentReady">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
          <div class="col-span-4 space-y-3">
            <div
              v-show="!sidebarBeingManaged"
              v-for="section in enabledSections"
              :key="section.id"
            >
              <component
                ref="sectionRefs"
                :is="
                  sectionComponents.hasOwnProperty(section.component)
                    ? sectionComponents[section.component]
                    : section.component
                "
                :deal="record"
                @refetch="fetchRecordAndSetInStore"
              />
            </div>
            <ManageViewSections
              :identifier="resourceSingular"
              v-model:sections="template.sections"
              v-model:show="sidebarBeingManaged"
              @saved="sidebarBeingManaged = false"
              class="-mt-3 inline"
            />
          </div>

          <div class="col-span-8 mt-4 lg:mt-0">
            <ITabGroup :default-index="defaultTabIndex">
              <ITabList
                centered
                :bordered="false"
                list-wrapper-class="rounded-md border border-neutral-200 bg-white py-0.5 shadow-sm dark:border-neutral-700 dark:bg-neutral-900"
              >
                <component
                  v-for="tab in template.tabs"
                  :key="tab.id"
                  :is="
                    tabComponents.hasOwnProperty(tab.component)
                      ? tabComponents[tab.component]
                      : tab.component
                  "
                />
              </ITabList>
              <ITabPanels>
                <component
                  v-for="tab in template.tabs"
                  :key="tab.id"
                  :id="'tabPanel-' + tab.id"
                  :ref="tab.id === 'products' ? 'productsTabRef' : undefined"
                  :is="
                    tabComponents.hasOwnProperty(tab.panelComponent)
                      ? tabComponents[tab.panelComponent]
                      : tab.panelComponent
                  "
                  :resource-name="resourceName"
                  scroll-element="#main"
                />
              </ITabPanels>
            </ITabGroup>
          </div>
        </div>
      </div>
    </div>

    <template v-if="componentReady">
      <!-- Contact, Company Create -->
      <router-view
        :via-resource="resourceName"
        :go-to-list="false"
        @associated="fetchRecordAndSetInStore(), $router.back()"
        @created="
          ({ isRegularAction }) => (
            isRegularAction ? $router.back() : '', fetchRecordAndSetInStore()
          )
        "
        @modal-hidden="$router.back"
      />
    </template>

    <PreviewModal :via-resource="resourceName" @updated="updateFieldsValues" />
  </ILayout>
</template>
<script setup>
import { ref, computed, watch, onBeforeMount, onBeforeUnmount } from 'vue'
import { onBeforeRouteUpdate, useRoute, useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useApp } from '~/Core/resources/js/composables/useApp'
import { useDates } from '~/Core/resources/js/composables/useDates'
import { useRecordStore } from '~/Core/resources/js/composables/useRecordStore'
import { useResource } from '~/Core/resources/js/composables/useResource'

import DealStatusChange from '../components/DealStatusChange.vue'
import DealStagePopover from '../components/DealStagePopover.vue'
import DealMiniPipeline from '../components/MiniPipeline/DealMiniPipeline.vue'

import Actions from '~/Core/resources/js/components/Actions/Actions.vue'
import ManageViewSections from '~/Core/resources/js/components/ManageViewSections.vue'

import TimelineTab from '~/Core/resources/js/views/Timeline/RecordTabTimeline.vue'
import TimelineTabPanel from '~/Core/resources/js/views/Timeline/RecordTabTimelinePanel.vue'

import DetailsSection from '../components/ViewDealSidebar/ViewDealDetails.vue'
import ContactsSection from '../components/ViewDealSidebar/ViewDealContacts.vue'
import CompaniesSection from '../components/ViewDealSidebar/ViewDealCompanies.vue'
import MediaSection from '../components/ViewDealSidebar/ViewDealMedia.vue'

import { useForm } from '~/Core/resources/js/composables/useForm'
import { usePrivateChannel } from '~/Core/resources/js/composables/useBroadcast'
import { useGlobalEventListener } from '~/Core/resources/js/composables/useGlobalEventListener'

const tabComponents = {
  'timeline-tab': TimelineTab,
  'timeline-tab-panel': TimelineTabPanel,
}

const sectionComponents = {
  'details-section': DetailsSection,
  'contacts-section': ContactsSection,
  'companies-section': CompaniesSection,
  'media-section': MediaSection,
}

const resourceName = Innoclapps.config('resources.deals.name')

const { t } = useI18n()
const router = useRouter()
const route = useRoute()

const { localizedDateTime } = useDates()
const { users, setPageTitle } = useApp()

const { singularName: resourceSingular } = useResource(resourceName)

const { setRecord, resetRecord, record } = useRecordStore()

useGlobalEventListener('refresh-details-view', refreshRecordView)

const sectionRefs = ref([])
const namePopoverRef = ref(null)
const sidebarBeingManaged = ref(false)
const template = ref(Innoclapps.config('resources.deals.frontend.view'))
const editName = ref(null)

const defaultTabIndex = route.query.section
  ? template.value.tabs.findIndex(tab => tab.id === route.query.section)
  : 0

const { form: updateForm } = useForm()

let dealId = ref(route.params.id)

const channelName = computed(() =>
  record.value?.authorizations?.view
    ? `Modules.Deals.Models.Deal.${dealId.value}`
    : null
)

usePrivateChannel(channelName, '.DealUpdated', refreshRecordView)

const ownerDropdownOptions = computed(() => {
  if (record.value.user) {
    return [
      ...users.value,
      {
        id: null,
        icon: 'X',
        prependIcon: true,
        name: t('core::app.no_owner'),
        class: 'border-t border-neutral-200 dark:border-neutral-700',
      },
    ].map(user => ({
      id: user.id,
      name: user.name,
      class: user.class || undefined,
      prependIcon: user.prependIcon || undefined,
      icon: user.icon || undefined,
    }))
  }

  return users.value
})

const enabledSections = computed(() =>
  template.value.sections.filter(section => section.enabled === true)
)

const stagesForMiniPipeline = computed(() =>
  record.value.pipeline.stages.map(({ id, name }) => ({ id, name }))
)

const componentReady = computed(() => Object.keys(record.value).length > 0)

const products = computed(() => {
  if (!componentReady.value || !record.value.billable) {
    return []
  }

  return record.value.billable.products
})

const totalProducts = computed(() => products.value.length)

const sumProductsAmount = computed(() =>
  products.value
    .map(product => product.amount)
    .reduce((amount, current) => amount + current, 0)
)

async function fetchRecord() {
  let { data } = await Innoclapps.request().get(
    `/${resourceName}/${dealId.value}`
  )

  return data
}

async function fetchRecordAndSetInStore() {
  let newRecord = await fetchRecord()
  setRecord(newRecord)
  return newRecord
}

function refreshRecordView() {
  fetchRecordAndSetInStore().then(updateFieldsValues)
}

function boot() {
  resetRecord()

  if (router[resourceSingular.value]) {
    setRecord(router[resourceSingular.value])
    delete router[resourceSingular.value]
  } else {
    fetchRecordAndSetInStore()
  }
}

function handleActionExecuted(action) {
  if (!action.destroyable) {
    refreshRecordView()
  } else {
    router.push({ name: 'deal-index' })
  }
}

async function update(payload = {}) {
  let deal = await updateForm
    .clear()
    .set(payload)
    .put(`/deals/${record.value.id}`)

  handleUpdatedEvent(deal)

  return deal
}

function updateName() {
  update({ name: editName.value }).then(() => namePopoverRef.value.hide())
}

/**
 * Handle the stage updated via mini pipeline event
 *
 * We need to re-set the record with all activities and new stage data
 * However, we need to update the fields as well, because the stage won't be updated
 * on the fields after the stage is updated via the mini pipeline
 */
function handleUpdatedEvent(record) {
  setRecord(record)
  updateFieldsValues(record)
}

watch(
  () => route.params,
  (newVal, oldVal) => {
    if (route.name === 'view-deal' && newVal.id !== oldVal.id) {
      dealId.value = newVal.id

      boot()
    }
  }
)

onBeforeMount(boot)
onBeforeUnmount(resetRecord)

watch(
  () => record.value.display_name,
  newVal => {
    if (newVal) setPageTitle(newVal)
  }
)

function getDetailsSectionRef() {
  return sectionRefs.value[
    template.value.sections.findIndex(section => section.id === 'details')
  ]
}

onBeforeRouteUpdate((to, from) => {
  if (to.name === 'view-deal') {
    // Reset the page title when the route is updated
    // e.q. create deal then back to this route
    setPageTitle(record.value.display_name)
  }
})

function updateFieldsValues(record) {
  let detailsSectionRef = getDetailsSectionRef()
  // Perhaps the sidebar section item is not enabled?
  if (!detailsSectionRef) return

  detailsSectionRef.setFormValues(record)
}

watch(totalProducts, newVal => {
  let detailsSectionRef = getDetailsSectionRef()
  if (!detailsSectionRef) return

  const isReadOnly = newVal > 0

  detailsSectionRef.fields.update('amount', {
    readonly: isReadOnly,
  })
})

watch(sumProductsAmount, () => {
  let detailsSectionRef = getDetailsSectionRef()
  if (!detailsSectionRef || !record.value.billable) return

  detailsSectionRef.fields
    .find('amount')
    .handleChange(record.value.billable.total)
})
</script>
