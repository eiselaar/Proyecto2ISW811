<template>
  <div class="post-editor">
    <div class="mb-4">
      <textarea
        v-model="content"
        class="form-input w-full"
        :class="{ 'border-red-500': errors.content }"
        rows="4"
        placeholder="What's on your mind?"
        @input="updateCharCount"
      ></textarea>
      <div class="flex justify-between text-sm text-gray-500 mt-1">
        <span>{{ charCount }}/280 characters</span>
        <span v-if="errors.content" class="text-red-500">{{ errors.content }}</span>
      </div>
    </div>

    <div class="platforms mb-4">
      <h3 class="text-sm font-semibold mb-2">Choose platforms</h3>
      <div class="flex gap-2">
        <label v-for="platform in availablePlatforms" :key="platform" class="flex items-center">
          <input
            type="checkbox"
            v-model="selectedPlatforms"
            :value="platform"
            class="form-checkbox"
          >
          <span class="ml-2">{{ platform }}</span>
        </label>
      </div>
    </div>

    <div class="scheduling mb-4">
      <h3 class="text-sm font-semibold mb-2">When to post</h3>
      <div class="space-y-2">
        <label class="flex items-center">
          <input
            type="radio"
            v-model="scheduleType"
            value="now"
            class="form-radio"
          >
          <span class="ml-2">Post Now</span>
        </label>
        <label class="flex items-center">
          <input
            type="radio"
            v-model="scheduleType"
            value="queue"
            class="form-radio"
          >
          <span class="ml-2">Add to Queue</span>
        </label>
        <label class="flex items-center">
          <input
            type="radio"
            v-model="scheduleType"
            value="scheduled"
            class="form-radio"
          >
          <span class="ml-2">Schedule for Specific Time</span>
        </label>
      </div>

      <div v-if="scheduleType === 'scheduled'" class="mt-4">
        <input
          type="datetime-local"
          v-model="scheduledFor"
          class="form-input"
          :min="minDateTime"
        >
      </div>
    </div>

    <div class="flex justify-end">
      <button
        @click="submitPost"
        class="btn-primary"
        :disabled="isSubmitting"
      >
        {{ submitButtonText }}
      </button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      content: '',
      selectedPlatforms: [],
      scheduleType: 'now',
      scheduledFor: '',
      errors: {},
      isSubmitting: false,
      availablePlatforms: ['linkedin', 'reddit', 'mastodon']
    }
  },
  computed: {
    charCount() {
      return this.content.length;
    },
    minDateTime() {
      return new Date().toISOString().slice(0, 16);
    },
    submitButtonText() {
      if (this.isSubmitting) return 'Submitting...';
      if (this.scheduleType === 'now') return 'Post Now';
      if (this.scheduleType === 'queue') return 'Add to Queue';
      return 'Schedule Post';
    }
  },
  methods: {
    async submitPost() {
      this.isSubmitting = true;
      this.errors = {};

      try {
        const response = await axios.post('/posts', {
          content: this.content,
          platforms: this.selectedPlatforms,
          schedule_type: this.scheduleType,
          scheduled_for: this.scheduleType === 'scheduled' ? this.scheduledFor : null
        });

        // Redireccionar o mostrar mensaje de éxito
        window.location.href = '/posts';
      } catch (error) {
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        }
      } finally {
        this.isSubmitting = false;
      }
    }
  }
}
</script>