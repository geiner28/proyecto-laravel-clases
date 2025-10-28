<script setup>
import {Head, Link, useForm, router} from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { onMounted, ref } from 'vue';

const props = defineProps({
    event: Object,
    venues: Array,
});

const form = useForm({
    event_name: props.event.event_name,
    event_date: '',
    event_is_virtual: props.event.event_is_virtual,
    event_speaker_name: props.event.event_speaker_name,
    fk_venue_event: props.event.fk_venue_event,
    event_image: null,
    _method: 'PUT',
});

const imagePreview = ref(props.event.event_image ? `/storage/${props.event.event_image}` : null);

const handleImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.event_image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const submit = () => {
    router.post(route('events.update', props.event.id), form);
};

onMounted(() => {
    // Formatear la fecha para el input datetime-local
    if (props.event.event_date) {
        const date = new Date(props.event.event_date);
        date.setMinutes(date.getMinutes() - date.getTimezoneOffset());
        form.event_date = date.toISOString().slice(0, 16);
    }
});
</script>

<template>
    <Head title="Edit Event"/>

    <AppLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Event</h2>
                <Link :href="route('events.index')"
                      class="px-4 py-2 bg-gray-800 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600">
                    Back to Events
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <form @submit.prevent="submit">
                            <div class="mb-4">
                                <InputLabel for="event_name" value="Event Name" class="dark:text-gray-300"/>
                                <TextInput
                                    id="event_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.event_name"
                                    required
                                    autofocus
                                />
                                <InputError class="mt-2" :message="form.errors.event_name"/>
                            </div>

                            <div class="mb-4">
                                <InputLabel for="event_date" value="Event Date" class="dark:text-gray-300"/>
                                <input
                                    id="event_date"
                                    type="datetime-local"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    v-model="form.event_date"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.event_date"/>
                            </div>

                            <div class="mb-4 flex items-center">
                                <input
                                    id="event_is_virtual"
                                    type="checkbox"
                                    class="rounded border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600"
                                    v-model="form.event_is_virtual"
                                />
                                <InputLabel for="event_is_virtual" value="Is Virtual Event?" class="ml-2 dark:text-gray-300"/>
                                <InputError class="mt-2" :message="form.errors.event_is_virtual"/>
                            </div>

                            <div class="mb-4">
                                <InputLabel for="event_speaker_name" value="Speaker Name" class="dark:text-gray-300"/>
                                <TextInput
                                    id="event_speaker_name"
                                    type="text"
                                    class="mt-1 block w-full"
                                    v-model="form.event_speaker_name"
                                    required
                                />
                                <InputError class="mt-2" :message="form.errors.event_speaker_name"/>
                            </div>

                            <div class="mb-4">
                                <InputLabel for="fk_venue_event" value="Venue" class="dark:text-gray-300"/>
                                <select
                                    id="fk_venue_event"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                    v-model="form.fk_venue_event"
                                >
                                    <option value="">Select a Venue</option>
                                    <option v-for="venue in venues" :key="venue.id" :value="venue.id">
                                        {{ venue.venue_name }} (Capacity: {{ venue.venue_max_capacity }})
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.fk_venue_event"/>
                            </div>

                            <div class="mb-4">
                                <InputLabel for="event_image" value="Event Image" class="dark:text-gray-300"/>
                                
                                <!-- Imagen actual -->
                                <div v-if="event.event_image && !form.event_image" class="mb-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Current Image:</p>
                                    <img :src="`/storage/${event.event_image}`" alt="Current event image" class="max-w-xs h-48 object-cover rounded-lg shadow-md"/>
                                </div>
                                
                                <input
                                    id="event_image"
                                    type="file"
                                    accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                           file:mr-4 file:py-2 file:px-4
                                           file:rounded-md file:border-0
                                           file:text-sm file:font-semibold
                                           file:bg-indigo-50 dark:file:bg-indigo-900 file:text-indigo-700 dark:file:text-indigo-300
                                           hover:file:bg-indigo-100 dark:hover:file:bg-indigo-800"
                                    @change="handleImageChange"
                                />
                                <InputError class="mt-2" :message="form.errors.event_image"/>
                                
                                <!-- Vista previa de la nueva imagen -->
                                <div v-if="form.event_image && imagePreview" class="mt-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">New Image Preview:</p>
                                    <img :src="imagePreview" alt="Preview" class="max-w-xs h-48 object-cover rounded-lg shadow-md"/>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }"
                                               :disabled="form.processing">
                                    Update Event
                                </PrimaryButton>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>