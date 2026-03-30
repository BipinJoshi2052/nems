<template>
  <v-row>
    <v-col cols="12" sm="6" md="3">
      <v-card color="primary" theme="dark">
        <v-card-text>
          <div class="text-h6">Total Students</div>
          <div class="text-h4">124</div>
        </v-card-text>
        <v-card-actions>
          <v-btn variant="text" size="small">View Details</v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
    <v-col cols="12" sm="6" md="3">
      <v-card color="success" theme="dark">
        <v-card-text>
          <div class="text-h6">Classes Today</div>
          <div class="text-h4">8</div>
        </v-card-text>
        <v-card-actions>
          <v-btn variant="text" size="small">View Schedule</v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
    <v-col cols="12" sm="6" md="3">
      <v-card color="warning" theme="dark">
        <v-card-text>
          <div class="text-h6">Pending Fees</div>
          <div class="text-h4">NPR 45k</div>
        </v-card-text>
        <v-card-actions>
          <v-btn variant="text" size="small">Manage Finance</v-btn>
        </v-card-actions>
      </v-card>
    </v-col>
    <v-col cols="12" sm="6" md="3">
      <v-card color="info" theme="dark">
        <v-card-text>
          <div class="text-h6">Storage Used</div>
          <v-progress-linear model-value="45" height="10" color="white" class="mt-2"></v-progress-linear>
          <div class="text-caption mt-1">0.9 GB / 2 GB</div>
        </v-card-text>
      </v-card>
    </v-col>
  </v-row>
</template>

<script setup>
// Dashboard logic can go here
</script>
